<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest {
    public function rules() : array {
        return [
            'name' => 'required|string|max:100',
            'customer_id' => 'int|required',
            'members' => 'array|nullable',
            'services' => 'array|nullable',
            'description' => 'string|nullable',
            'deadline' => 'date|nullable',
            'started_at' => 'date|nullable',
            'closed_at' => 'date|nullable',
            'quote' => 'int|nullable',
            'deposit' => 'int|nullable',
            'price' => 'int|nullable',
        ];
    }

    public function authorize() : bool {
        return true;
    }
}
