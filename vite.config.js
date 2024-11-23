import path from 'path'
import { defineConfig } from 'vite';
import fs from 'fs';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
       // Dynamically include all CSS files from resources/css
       ...fs.readdirSync(path.resolve(__dirname, 'resources/css'))
       .filter(file => file.endsWith('.css'))  // Only include .css files
       .map(file => `resources/css/${file}`), // Map to file paths

     // Dynamically include all JS files from resources/js
     ...fs.readdirSync(path.resolve(__dirname, 'resources/js'))
       .filter(file => file.endsWith('.js'))  // Only include .js files
       .map(file => `resources/js/${file}`), // Map to file paths
      ],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      '@tailwindConfig': path.resolve(__dirname, 'tailwind.config.js'),
    },
  },
  optimizeDeps: {
    include: [
      '@tailwindConfig',
    ]
  },
});
