<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class UserUpdateEmailRequest extends FormRequest {
        public function rules()
        : array {
            return [
                'email' => 'email|required|confirmed|unique:users',
                'email_confirmation' => 'email|required'
            ];
        }

        public function messages(): array {
            return [
                'email.unique' => 'Prova con un indirizzo email diverso.'
            ];
        }

        public function authorize()
        : bool {
            return true;
        }
    }
