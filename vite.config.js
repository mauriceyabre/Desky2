import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/ts/main.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                }
            },
        }),
    ],
    build: {
        commonjsOptions: {
            esmExternals: true
        }
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/ts/Boot'),
            '@Helpers': path.resolve('resources/ts/Helpers'),
            '@Composables': path.resolve('resources/ts/Helpers/Composables'),
            '@Stores': path.resolve('resources/ts/Stores'),
            '@Models': path.resolve('resources/ts/Helpers/Models'),
            '@Pages': path.resolve('resources/ts/Pages'),
            '@Components': path.resolve('resources/ts/Components'),
            '@Layouts': path.resolve('resources/ts/Layouts'),
        }
    }
});
