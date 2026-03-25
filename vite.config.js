import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/images/pgmf-logo.jpg',
                'resources/images/Thai_QR_Payment_Logo-01.jpg',
                'resources/images/ThailandPost_Logo.svg',
                'resources/images/paymentbiller.png',
                'resources/images/banks/bay.svg',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
