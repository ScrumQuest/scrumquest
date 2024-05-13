import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss',
                'resources/js/app.js',
                'resources/js/sprint.show.js',
                'resources/js/tinymce.js'],
            refresh: true,
        }),
    ],
});
