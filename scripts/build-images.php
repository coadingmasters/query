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
    $hash = substr(hash('xxh128', md5_file($sourcePath)."$dw:$dh:$quality"), 0, 8);

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

            $cropped = cropToRatio($src, $ratio);

            // Never upscale: if the source is smaller than 2x, that density
            // is emitted at whatever the source can honestly provide.
            $tw = min($tw, imagesx($cropped));
            $th = (int) round($tw / $ratio);

            $out = imagecreatetruecolor($tw, $th);
            imagecopyresampled($out, $cropped, 0, 0, 0, 0, $tw, $th, imagesx($cropped), imagesy($cropped));
            imagewebp($out, $path, $quality);

            imagedestroy($out);
            imagedestroy($cropped);
            $built++;
        }

        $bytes += filesize($path);
        $variants[] = ['w' => $tw, 'src' => "/images/$file"];
    }

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
