<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class MembersController extends Controller {
    public function index() {
        return Inertia::render('App/Members/MembersIndex', [
            'members' => User::with('todayAttendance')->get(),
            'page' => [
                'title' => 'Membri del Team'
            ]
        ]);
    }
}
