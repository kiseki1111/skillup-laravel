import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // === TAMBAHKAN SELURUH BLOK 'server' DI BAWAH INI ===
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            // Perintah ini memaksa Vite untuk aktif memeriksa perubahan file,
            // alih-alih mengandalkan sinyal dari sistem file yang mungkin tidak sampai.
            usePolling: true,
        }
    },
});