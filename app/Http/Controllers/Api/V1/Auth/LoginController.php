<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller {
    public function __invoke(Request $request) {
        $this->validateRequest($request);

        $user = User::query()->where('email', $request->input('email'))->first();
        $this->checkUserCredentials($request, $user);

        $device = substr($request->userAgent() ?? '', 0, 255);
        $expiresAt = $this->getExpiresAt($request);

        sleep(1);

        return response()->json([
            'access_token' => $user->createToken($device, ['expires_at' => $expiresAt])->plainTextToken,
            'user' => $user
        ]);
    }

    private function validateRequest(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }

    private function checkUserCredentials(Request $request, $user) {
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    }

    private function getExpiresAt(Request $request) {
        return $request->input('remember') ? null : now()->addMinutes(config('session.lifetime'));
    }
}
