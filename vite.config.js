import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                //Catalog
                'resources/catalog/css/main.css',
                'resources/catalog/css/main_m.css',
                'resources/catalog/css/bootstrap.css',
                'resources/catalog/css/auth.css',
                'resources/catalog/css/all.min.css',
                'resources/catalog/css/notify.css',
                'resources/catalog/css/ex_select_checkbox.css',
                'resources/catalog/css/jquery-ui.css',
                'resources/catalog/css/header.css',
                'resources/catalog/css/post.css',
                'resources/catalog/css/post_m.css',
                'resources/catalog/css/id/account.css',
                'resources/catalog/css/id/blacklist.css',
                'resources/catalog/css/id/post.css',
                'resources/catalog/css/id/post_m.css',
                'resources/catalog/css/id/post_created.css',
                'resources/catalog/css/id/payment.css',
                'resources/catalog/css/id/tickets.css',
                'resources/catalog/css/map.css',
                'resources/catalog/css/news.css',
                'resources/catalog/css/information.css',
                'resources/catalog/css/blocks/post_banner.css',
                'resources/catalog/css/blocks/post_banner_m.css',
                'resources/catalog/css/blocks/banner.css',
                'resources/catalog/css/blocks/news.css',
                'resources/catalog/css/blocks/information.css',
                //Admin
                'resources/admin/css/dashmix.css',
                'resources/admin/css/all.min.css',
                'resources/admin/css/main.css',
                'resources/admin/css/tickets.css',
                //Catalog
                'resources/catalog/js/main.js',
                'resources/catalog/js/posts.js',
                'resources/catalog/js/bootstrap.bundle.js',
                'resources/catalog/js/ex_select_checkbox.js',
                'resources/catalog/js/post_created.js',
                //Admin
                'resources/admin/js/dashmix.app.min.js',
                'resources/admin/js/main.js',

                //Main
                'resources/admin/js/post_created.js'
            ],
            refresh: true,
        }),
    ],
    build: {
    rollupOptions: {
        output: {
            assetFileNames: 'assets/[name].[hash][extname]',
            entryFileNames: 'assets/[name].[hash].js',
        },
    },
},
});
