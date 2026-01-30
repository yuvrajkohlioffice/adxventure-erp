import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';

export default defineConfig({
    plugins: [
        inject({
            include: ['**/*.js', '**/*.ts'], 
            $: 'jquery',
            jQuery: 'jquery',
            // REMOVED: 'window.jQuery': 'jquery'  <-- CAUSES THE REASSIGNMENT ERROR
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$': 'jquery',
            'jQuery': 'jquery',
        },
    },
});