<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateDetailsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|alpha|required',
            'last_name' => 'sometimes|alpha|required',

            'job' => 'sometimes|string|max:20|min:3|nullable',

            'tax_id' => 'sometimes|string|nullable',
            'vat_id' => 'sometimes|string|nullable',
            'iban' => 'sometimes|string|nullable',

            'hire_date' => 'sometimes|date:Y-m-d|nullable',

            'address' => 'sometimes',
            'address.country_code' => 'sometimes|required|alpha|uppercase|min:2|max:2',
            'address.street' => 'sometimes|string|nullable',
            'address.city' => 'string|sometimes|nullable',
            'address.province' => 'alpha|min:2|max:2|sometimes|nullable',
            'address.postcode' => 'alpha_num|min:4|max:10|sometimes|nullable',

            'phone' => 'sometimes|string|min:7|max:15|nullable',
            'birthday' => 'sometimes|date_format:Y-m-d|nullable'
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
