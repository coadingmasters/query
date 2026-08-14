import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Fonts are downloaded at build time and served from our own
            // origin. That removes a third-party connection from the critical
            // path and keeps the site free of external font requests.
            fonts: [
                bunny('Inter', { weights: [400, 500, 600, 700] }),
                bunny('Plus Jakarta Sans', { weights: [600, 700, 800] }),
            ],
        }),
        tailwindcss(),
    ],
    build: {
        // Lets the browser drop unused CSS rules and keeps sourcemaps out of
        // the production bundle.
        cssMinify: 'lightningcss',
        sourcemap: false,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
