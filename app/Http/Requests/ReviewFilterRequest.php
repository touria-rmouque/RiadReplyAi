<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],

            'sentiment' => [
                'nullable',
                Rule::in([
                    'positive',
                    'neutral',
                    'negative',
                ]),
            ],

            'flagged' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}