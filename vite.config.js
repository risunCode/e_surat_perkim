import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { copyFileSync, existsSync, mkdirSync } from 'fs';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        {
            name: 'copy-qrcode',
            buildStart() {
                // Ensure public/js directory exists
                const jsDir = path.resolve('public/js');
                if (!existsSync(jsDir)) {
                    mkdirSync(jsDir, { recursive: true });
                }
                
                // Copy qrcode library to public
                const qrcodeSource = path.resolve('node_modules/qrcode/build/qrcode.min.js');
                const qrcodeDest = path.resolve('public/js/qrcode.min.js');
                
                if (existsSync(qrcodeSource)) {
                    copyFileSync(qrcodeSource, qrcodeDest);
                    console.log('✓ QRCode library copied to public/js/');
                }
            }
        },
        tailwindcss(),
    ],
});
