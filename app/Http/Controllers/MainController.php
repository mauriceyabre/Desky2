<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MainController extends Controller {
    public function dashboard() {
        return Inertia::render('App/Dashboard/Dashboard', [
            'page' => [
                'title' => 'Dashboard'
            ]
        ]);
    }
}
