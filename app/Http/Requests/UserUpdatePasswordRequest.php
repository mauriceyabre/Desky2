<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rules\Password;

    class UserUpdatePasswordRequest extends FormRequest {
        public function rules()
        : array {
            return [
                'current_password' => 'required',
                'password' => ['required','confirmed','min:6', Password::default()],
            ];
        }

        public function authorize()
        : bool {
            return true;
        }
    }
