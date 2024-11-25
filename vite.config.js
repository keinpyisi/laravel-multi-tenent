import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { glob } from 'glob';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...glob.sync('resources/css/**/*.css').map(file => path.relative('', file)),
                ...glob.sync('resources/js/**/*.js').map(file => path.relative('', file)),
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@tailwindConfig': path.resolve(__dirname, './tailwind.config.js'),
        },
    },
});