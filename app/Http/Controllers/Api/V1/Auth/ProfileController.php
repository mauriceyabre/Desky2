<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Validation\Rule;
use Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProfileController extends Controller {
    public function show(Request $request) {
        return response()->json($request->user()->only('name', 'email'));
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
        ]);

        auth()->user()->update($validatedData);

        return response()->json($validatedData, Response::HTTP_ACCEPTED);
    }

    public function user() {
        try {
            $user = Auth::user()?->only(['id', 'first_name', 'last_name', 'email']);
            return response()->json(['user' => $user], Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json($e, Response::HTTP_FORBIDDEN);
        }
    }
}
