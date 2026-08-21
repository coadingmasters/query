import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            // The PDF report is its own entry: it is several kilobytes of
            // format-writing that only runs when someone asks for a download,
            // so it stays out of the bundle every visitor pays for.
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/cat-calorie-pdf.js',
                'resources/js/cat-vaccination-pdf.js',
                'resources/js/post-editor.js',
            ],
            refresh: true,
            // Fonts are downloaded at build time and served from our own
            // origin. That removes a third-party connection from the critical
            // path and keeps the site free of external font requests.
            // Preloading all seven weights put ~130 KB of fonts in front of
            // the hero image, which is the element the browser measures for
            // Largest Contentful Paint. Only the two the first screen
            // actually needs are preloaded; the rest still load, they just
            // stop competing with the image. Plus Jakarta Sans 600 is gone
            // entirely — no heading uses semibold.
            fonts: [
                bunny('Inter', {
                    weights: [400, 500, 600, 700],
                    preload: [{ weight: 400 }],
                }),
                bunny('Plus Jakarta Sans', {
                    weights: [700, 800],
                    preload: [{ weight: 800 }],
                }),
            ],
        }),
        tailwindcss(),
    ],
    build: {
        // Lets the browser drop unused CSS rules and keeps sourcemaps out of
        // the production bundle.
        cssMinify: 'lightningcss',
        sourcemap: false,
        rollupOptions: {
            // Entries are treated as scripts by default, so anything they
            // export is dead code and gets shaken out. The PDF report is an
            // entry precisely so it can be imported on demand, and without
            // this its one export was stripped and the file built to nothing.
            preserveEntrySignatures: 'exports-only',
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
