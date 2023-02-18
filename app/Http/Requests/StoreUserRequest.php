<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest {
    public function rules() : array {
        return [
            'role_key' => 'required|exists:roles,key',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => ['required', 'email', 'unique:users,email'],
            'country_code' => 'required|string|min:2|max:2',
            'password' => ['required', 'confirmed', Password::min(6)],
            'password_confirmation' => 'required'
        ];
    }

    public function authorize() : bool {
        return true;
    }
}
