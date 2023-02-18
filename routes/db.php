<?php

Route::prefix('db')->group(function () {
    Route::get('/seed', function () {
        Artisan::call('migrate:fresh --seed');
        return route('projects.index');
    });
});
