<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MemberController extends Controller {
    public function index() {

    }

    public function store(Request $request) {
    }

    public function show(int $id) {
        $user = User::with('address')->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function update(UpdateUserRequest $request, $id) {
        try {
            $user = User::findOrFail($id);

            if (!empty($request->except('address'))) {
                $user->updateOrFail($request->except('address'));
            }

            if ($request->filled('address')) {
                $address = $request->input('address');
                unset($address['id']);
                $addressId = $request->input('address')['id'];

                if ($addressId) {
                    $user->address()->update($address);
                }
            }
            return response()->json(['toast' => 'Aggiornato', 'user' => $user->load('address', 'latestAttendance')]);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function destroy($id) {
    }
}
