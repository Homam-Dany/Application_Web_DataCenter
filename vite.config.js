import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/layouts/app.css',
                'resources/css/auth/login.css',
                'resources/css/auth/forgot-password.css',
                'resources/css/auth/reset-password.css',
                'resources/css/dashboard.css',
                'resources/css/about.css',
                'resources/css/resources/manager.css',
                'resources/css/resources/create.css',
                'resources/css/resources/index.css',
                'resources/css/resources/edit.css',
                'resources/css/reservations/index.css',
                'resources/css/reservations/manager.css',
                'resources/css/reservations/create.css',
                'resources/css/admin/logs.css',
                'resources/js/app.js',
                'resources/js/theme-init.js',
                'resources/js/layouts/app.js',
                'resources/js/auth/login.js',
                'resources/js/admin/dashboard.js',
                'resources/js/admin/users.js',
                'resources/js/admin/logs.js',
                'resources/js/reservations/create.js',
                'resources/js/notifications/index.js',
                'resources/js/about.js',
            ],
            refresh: true,
        }),
    ],
});
