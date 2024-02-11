import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";

import fs from 'fs';
import path from 'path';

const certPath = path.resolve(__dirname, '/data/caddy/certificates/local/localhost/localhost.crt');
const keyPath = path.resolve(__dirname, '/data/caddy/certificates/local/localhost/localhost.key');

export default defineConfig({
    server: {
        host: "localhost",
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certPath),
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
});
