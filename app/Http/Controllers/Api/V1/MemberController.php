<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MemberController extends Controller {
    public function index() {

    }

    public function store(Request $request) {
    }

    public function get(int $id) {
        $user = User::with('address')->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function update(Request $request, $id) {
    }

    public function destroy($id) {
    }
}
