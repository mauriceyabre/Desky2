<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'string|required|min:2',
            'last_name' => 'string|required|min:2',
            'email' => 'email|required|unique:users,email',
            'job' => 'string|max:20|min:3|nullable',
            'role_id' => 'numeric|max:10|min:1',
            'phone' => 'string|min:9|max:14|nullable',
            'address' => 'required_if:country,===,IT',
            'address.street' => 'string|nullable',
            'address.city' => 'string|nullable',
            'address.province' => 'string|min:2|max:2|nullable',
            'address.postcode' => 'string|min:5|max:5|nullable',
            'birthday' => 'date_format:Y-m-d|nullable',
            'country_code' => 'string|min:2|max:2|required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
