<?php

namespace App\Support;

use RuntimeException;

/**
 * Reads the manifest written by scripts/build-images.php.
 *
 * The manifest is the only place that knows a generated file's name, which is
 * content-hashed. Templates ask for an image by its plain name, so replacing
 * artwork never means editing a page.
 */
class Images
{
    /** @var array<string, array>|null */
    private static ?array $manifest = null;

    /** @return array<string, array> */
    private static function manifest(): array
    {
        if (self::$manifest !== null) {
            return self::$manifest;
        }

        $path = public_path('images/manifest.json');

        if (! is_file($path)) {
            throw new RuntimeException(
                'public/images/manifest.json is missing — run: php scripts/build-images.php'
            );
        }

        return self::$manifest = json_decode(file_get_contents($path), true);
    }

    /**
     * Everything a template needs to render one image: the fallback src, a
     * srcset covering each density, and the intrinsic width and height.
     *
     * Width and height are the important part. Without them the browser does
     * not know how much room to leave, and the page jumps as each image
     * arrives — which is exactly what Cumulative Layout Shift measures.
     *
     * @return array{src: string, srcset: string, width: int, height: int}|null
     */
    public static function get(string $name): ?array
    {
        $entry = self::manifest()[$name] ?? null;

        if (! $entry) {
            return null;
        }

        return [
            'src' => $entry['variants'][0]['src'],
            'srcset' => implode(', ', array_map(
                fn (array $v): string => "{$v['src']} {$v['w']}w",
                $entry['variants']
            )),
            'width' => $entry['width'],
            'height' => $entry['height'],
        ];
    }

    /** The largest variant, for preloading the one image above the fold. */
    public static function largest(string $name): ?string
    {
        $entry = self::manifest()[$name] ?? null;

        return $entry ? end($entry['variants'])['src'] : null;
    }
}
