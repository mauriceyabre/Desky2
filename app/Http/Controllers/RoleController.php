<?php

namespace App\Http\Controllers;

use App\Models\Role;

class RoleController extends Controller {
    public function fetch() {
        return Role::all();
    }
}
