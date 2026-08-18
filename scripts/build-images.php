<?php

/**
 * Builds the images the site actually serves.
 *
 * Reads config/images.php, then for every entry writes a centre-cropped,
 * resized, recompressed WebP into public/images/ at 1x and 2x, plus a
 * manifest the <x-img> component reads for paths and dimensions.
 *
 * Run: php scripts/build-images.php [--force]
 *
 * Output is committed to git, because the production host cannot run a build
 * (see README). Files are content-hashed, so replacing a source image changes
 * its URL and no visitor is served a stale copy from cache.
 *
 * AVIF is deliberately not produced: gd_info() advertises AVIF support on
 * both this machine and the server, but imageavif() reports "AVIF image
 * support has been disabled". WebP is what actually encodes here, and it is
 * supported by every browser this site targets.
 */

$root = dirname(__DIR__);
$config = require $root.'/config/images.php';

$sourceDir = $root.'/resources/images';
$outputDir = $root.'/public/images';
$force = in_array('--force', $argv, true);

@mkdir($outputDir, 0755, true);

/**
 * Rotate one band of hues onto another, leaving the rest of the picture alone.
 *
 * Artwork commissioned against the old purple palette is otherwise unusable on
 * the current one: a lavender panel in the middle of a coral and teal page
 * reads as a mistake. Only pixels inside $from..$to degrees move, so a cat's
 * orange, cream and grey fur, which sits nowhere near violet, is untouched.
 *
 * Greys are excluded by the saturation floor. Without it, every neutral pixel
 * would pick up a colour cast from whatever hue the maths happened to find.
 */
function shiftHue(GdImage $src, int $from, int $to, int $target): GdImage
{
    $w = imagesx($src);
    $h = imagesy($src);

    $out = imagecreatetruecolor($w, $h);
    imagealphablending($out, false);
    imagesavealpha($out, true);

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $c = imagecolorat($src, $x, $y);
            $a = ($c >> 24) & 0x7F;
            $r = ($c >> 16) & 0xFF;
            $g = ($c >> 8) & 0xFF;
            $b = $c & 0xFF;

            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $delta = $max - $min;

            if ($delta > 8) {
                $hue = match ($max) {
                    $r => fmod(($g - $b) / $delta, 6),
                    $g => (($b - $r) / $delta) + 2,
                    default => (($r - $g) / $delta) + 4,
                };
                $hue = fmod($hue * 60 + 360, 360);

                if ($hue >= $from && $hue <= $to) {
                    $saturation = $max === 0 ? 0 : $delta / $max;
                    $value = $max / 255;
                    $f = $target / 60;

                    $r = (int) round($value * 255);
                    $g = (int) round($value * (1 - $saturation * (1 - $f)) * 255);
                    $b = (int) round($value * (1 - $saturation) * 255);
                }
            }

            imagesetpixel($out, $x, $y, ($a << 24) | ($r << 16) | ($g << 8) | $b);
        }
    }

    return $out;
}

/**
 * Turn a flat white studio surround into transparency.
 *
 * Some artwork arrives as a subject on white rather than on alpha, which
 * shows as a white rectangle the moment it is placed on a tinted band. The
 * fill is seeded from the border and spreads only through connected near-white
 * pixels, so enclosed white — a cat's chest, a whisker, the highlight in an
 * eye — is left alone. A flat threshold over the whole image would eat those.
 *
 * Alpha ramps across $soft..$hard rather than switching at one value, or the
 * cut leaves a hard fringe where the subject's own glow meets the surround.
 */
function keyOutWhite(GdImage $src, int $soft = 200, int $hard = 246): GdImage
{
    $w = imagesx($src);
    $h = imagesy($src);

    $out = imagecreatetruecolor($w, $h);
    imagealphablending($out, false);
    imagesavealpha($out, true);
    imagecopy($out, $src, 0, 0, 0, 0, $w, $h);

    $seen = array_fill(0, $w * $h, false);
    $queue = [];

    for ($x = 0; $x < $w; $x++) {
        $queue[] = [$x, 0];
        $queue[] = [$x, $h - 1];
    }
    for ($y = 0; $y < $h; $y++) {
        $queue[] = [0, $y];
        $queue[] = [$w - 1, $y];
    }

    for ($i = 0; $i < count($queue); $i++) {
        [$x, $y] = $queue[$i];

        if ($x < 0 || $y < 0 || $x >= $w || $y >= $h) {
            continue;
        }

        $key = $y * $w + $x;
        if ($seen[$key]) {
            continue;
        }
        $seen[$key] = true;

        $rgb = imagecolorat($src, $x, $y);
        $min = min(($rgb >> 16) & 0xFF, ($rgb >> 8) & 0xFF, $rgb & 0xFF);

        if ($min < $soft) {
            continue;                 // subject edge — stop spreading here
        }

        $alpha = $min >= $hard
            ? 127
            : (int) round(127 * ($min - $soft) / ($hard - $soft));

        imagesetpixel($out, $x, $y, ($alpha << 24) | ($rgb & 0xFFFFFF));

        $queue[] = [$x + 1, $y];
        $queue[] = [$x - 1, $y];
        $queue[] = [$x, $y + 1];
        $queue[] = [$x, $y - 1];
    }

    return $out;
}

/** Centre-crop $src to $ratio, so nothing is squashed out of proportion. */
function cropToRatio(GdImage $src, float $ratio): GdImage
{
    $w = imagesx($src);
    $h = imagesy($src);
    $current = $w / $h;

    if (abs($current - $ratio) < 0.001) {
        return $src;
    }

    if ($current > $ratio) {          // too wide — trim the sides
        $cropW = (int) round($h * $ratio);
        $cropH = $h;
    } else {                          // too tall — trim top and bottom
        $cropW = $w;
        $cropH = (int) round($w / $ratio);
    }

    $cropped = imagecrop($src, [
        'x' => intdiv($w - $cropW, 2),
        'y' => intdiv($h - $cropH, 2),
        'width' => $cropW,
        'height' => $cropH,
    ]);

    return $cropped ?: $src;
}

$manifest = [];
$built = 0;
$skipped = 0;
$bytes = 0;
$missing = [];

foreach ($config['images'] as $name => $preset) {
    // A placeholder entry means this artwork does not exist yet and another
    // image stands in for it under the real name.
    $sourceName = $config['placeholders'][$name] ?? $name;
    $sourcePath = "$sourceDir/$sourceName.webp";

    if (! is_file($sourcePath)) {
        $missing[] = $name;
        continue;
    }

    [$dw, $dh] = $config['presets'][$preset];
    $quality = $config['quality'][$preset];
    $ratio = $dw / $dh;

    // Hash covers the source bytes and every setting that shapes the output,
    // so any change to either produces a new filename.
    //
    // The transforms belong in here as much as the dimensions do. They were
    // missing, which meant adding a crop or a recolour changed nothing: the
    // filename stayed the same, the file was found on disk, and the build
    // reported success while serving the old pixels. Only --force worked, and
    // only if you knew to reach for it.
    $transforms = json_encode([
        $config['crops'][$name] ?? null,
        in_array($name, $config['keyed'] ?? [], true),
        $config['recolour'][$name] ?? $config['recolour'][$sourceName] ?? null,
    ]);

    $hash = substr(hash('xxh128', md5_file($sourcePath)."$dw:$dh:$quality:$transforms"), 0, 8);

    $variants = [];

    // 1x, 1.5x and 2x. Two rungs was too coarse: a phone needing roughly
    // 660px had nothing between 600 and 1200 and took the larger file.
    foreach ([1, 1.5, 2] as $density) {
        $tw = (int) round($dw * $density);
        $th = (int) round($dh * $density);
        $file = "$name-{$tw}.$hash.webp";
        $path = "$outputDir/$file";

        if (! $force && is_file($path)) {
            $skipped++;
        } else {
            $src = imagecreatefromwebp($sourcePath);
            if (! $src) {
                fwrite(STDERR, "  cannot read $sourcePath\n");
                continue;
            }

            // A source that is mostly frame around the part we want: take the
            // region first, so the preset's ratio crop works on the subject
            // rather than on the whole artwork.
            if ($box = $config['crops'][$name] ?? null) {
                $region = imagecrop($src, array_combine(['x', 'y', 'width', 'height'], $box));
                if ($region) {
                    $src = $region;
                }
            }

            if (in_array($name, $config['keyed'] ?? [], true)) {
                $src = keyOutWhite($src);
            }

            $shift = $config['recolour'][$name] ?? $config['recolour'][$sourceName] ?? null;

            if ($shift) {
                $src = shiftHue($src, $shift['from'], $shift['to'], $shift['target']);
            }

            $cropped = cropToRatio($src, $ratio);

            // Never upscale: if the source is smaller than 2x, that density
            // is emitted at whatever the source can honestly provide.
            $tw = min($tw, imagesx($cropped));
            $th = (int) round($tw / $ratio);

            $out = imagecreatetruecolor($tw, $th);

            // A new truecolor canvas is opaque black. The logo has a
            // transparent surround, so without this it would gain black
            // corners the moment it was resized.
            imagealphablending($out, false);
            imagesavealpha($out, true);
            imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));

            imagecopyresampled($out, $cropped, 0, 0, 0, 0, $tw, $th, imagesx($cropped), imagesy($cropped));
            imagewebp($out, $path, $quality);

            imagedestroy($out);
            imagedestroy($cropped);
            $built++;
        }

        $bytes += filesize($path);

        // Read the width back off the file rather than trusting $tw. The
        // never-upscale cap above only runs on the branch that builds, so on a
        // reused variant $tw is still the requested width — which for a source
        // smaller than 2x is larger than the file really is. That difference
        // goes straight into srcset, telling the browser a 256w candidate
        // exists when the bytes are 100px wide.
        $variants[] = ['w' => (int) (getimagesize($path)[0] ?? $tw), 'src' => "/images/$file"];
    }

    // A source smaller than 2x makes the higher densities collapse onto the
    // same width, which would emit a srcset repeating one descriptor three
    // times. Keep the first of each width; the duplicates are byte-identical.
    $seen = [];
    $variants = array_values(array_filter($variants, function (array $v) use (&$seen): bool {
        if (isset($seen[$v['w']])) {
            return false;
        }

        return $seen[$v['w']] = true;
    }));

    $manifest[$name] = [
        'width' => $dw,
        'height' => $dh,
        'preset' => $preset,
        'placeholder' => isset($config['placeholders'][$name]),
        'variants' => $variants,
    ];
}

ksort($manifest);
file_put_contents(
    "$outputDir/manifest.json",
    json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
);

// Remove generated files that no longer belong to any current hash, so old
// versions do not accumulate in the repository forever.
$keep = [];
foreach ($manifest as $entry) {
    foreach ($entry['variants'] as $v) {
        $keep[basename($v['src'])] = true;
    }
}
$pruned = 0;
foreach (glob("$outputDir/*.webp") as $file) {
    if (! isset($keep[basename($file)])) {
        unlink($file);
        $pruned++;
    }
}

printf("built %d, reused %d, pruned %d — %d images, %.2f MB total\n",
    $built, $skipped, $pruned, count($manifest), $bytes / 1048576);

if ($missing) {
    printf("\nno source and no placeholder for %d image(s):\n  - %s\n",
        count($missing), implode("\n  - ", $missing));
    exit(1);
}
