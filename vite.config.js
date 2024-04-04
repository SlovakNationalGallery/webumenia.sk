import { defineConfig } from 'vite'
import inject from '@rollup/plugin-inject'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
            jQuery: 'jquery',
        }),
        vue(),
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/zoom.js',
                'resources/less/style.less',
                'resources/less/admin.less',
                'resources/css/app-tailwind.css',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
})
