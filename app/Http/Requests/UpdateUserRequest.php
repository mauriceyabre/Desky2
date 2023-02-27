<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|alpha|required',
            'last_name' => 'sometimes|alpha|required',
            'role_key' => 'required|sometimes|exists:roles,key',

            'job' => 'sometimes|string|max:20|min:3|nullable',

            'address' => 'sometimes|array',
            'address.country_code' => 'sometimes|required|alpha|min:2|max:2',
            'address.street' => 'sometimes|string|nullable',
            'address.city' => 'string|sometimes|nullable',
            'address.province' => 'alpha|min:2|max:2|sometimes|nullable',
            'address.postcode' => 'alpha_num|min:4|max:10|sometimes|nullable',

            'phone' => 'sometimes|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|nullable',
            'birthday' => 'sometimes|date_format:Y-m-d|nullable',
            'description' => 'string|sometimes|nullable',
            'iban' => 'string|sometimes|digits:34|nullable',
            'tax_id' => 'string|sometimes|digits:16|nullable',
            'vat_id' => 'string|sometimes|max:12|min:5|nullable',
            'hire_date' => 'date|date_format:Y-m-d|sometimes|nullable'
        ];
    }

    public function messages() : array {
        return [
            'address.country_code.required' => 'Questo campo è obbligatorio.'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
