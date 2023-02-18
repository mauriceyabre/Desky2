<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'string|nullable',
            'phase' => 'string|max:20|nullable',
            'index' => 'integer|nullable',
            'quote' => 'int|nullable',
            'deposit' => 'int|nullable',
            'services' => 'array|nullable',
            'started_at' => 'date|nullable',
            'closed_at' => 'date|nullable',
            'reviews_count' => 'int|nullable',
            'price' => 'int|nullable',
            'deadline' => 'date|nullable',
            'customer_id' => 'int|nullable',
            'created_by' => 'int|nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
