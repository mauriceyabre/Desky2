<?php

use Illuminate\Support\HtmlString;

if (!function_exists('vite_assets')) {
    /**
     * @return HtmlString
     * @throws Exception
     */
    function vite_assets() : HtmlString {
        $devServerIsRunning = false;

        if (app()->environment('local')) {
            try {
                $devServerIsRunning = file_get_contents(public_path('hot')) == 'dev';
            } catch (Exception) {
            }
        }

        if ($devServerIsRunning) {
            return new HtmlString(<<<HTML
            <script type="module" src="http://localhost:3000/@vite/client"></script>
            <script type="module" src="http://localhost:3000/resources/js/app.js"></script>
        HTML
            );
        }
        $manifest = json_decode(file_get_contents(public_path('mix-manifest.json')), true);
        return new HtmlString(<<<HTML
        <script type="module" src="/public/{$manifest['resources/js/app.ts']['file']}"></script>
        <link rel="stylesheet" href="/public/{$manifest['resources/js/app.ts']['css'][0]}">
    HTML
        );
    }
}
