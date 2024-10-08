import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                './resources/css/filament.css',
            ],
            refresh: true,
        }),
    ],
});
