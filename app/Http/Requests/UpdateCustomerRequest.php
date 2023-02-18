<?php

namespace App\Http\Requests;

use App\Enums\CustomerCategory;
use App\Enums\CustomerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|required|max:50|min:3',

            'type' => ['sometimes', 'nullable', Rule::enum(CustomerType::class)],
            'category' => ['sometimes', 'nullable', Rule::enum(CustomerCategory::class)],

            'email' => ['sometimes','nullable','email'],
            'pec' => ['sometimes', 'nullable', 'email'],
            'phone' => 'sometimes|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|nullable',

            'vat_number' => ['sometimes', 'nullable', 'string','max:15', Rule::unique('customers')->ignore($this->user())],
            'tax_code' => ['sometimes', 'nullable', 'string', 'max:15', Rule::unique('customers')->ignore($this->user())],

            'e_invoice' => ['sometimes', 'bool'],
            'sdi_code' => ['nullable', 'string', 'min:7','max:7', 'unique:customers,tax_code', 'required_if:e_invoice,true'],


            'website' => ['sometimes', 'nullable', 'url'],

            'address' => 'sometimes|array',
            'address.country_code' => 'sometimes|required|alpha|min:2|max:2',
            'address.street' => 'sometimes|string|nullable',
            'address.city' => 'string|sometimes|nullable',
            'address.province' => 'alpha|min:2|max:2|sometimes|nullable',
            'address.postcode' => 'alpha_num|min:4|max:10|sometimes|nullable',
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
