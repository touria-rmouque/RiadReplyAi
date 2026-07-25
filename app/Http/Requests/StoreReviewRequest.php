<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'raw_text' => ['required', 'string', 'min:20', 'max:5000'],
            'rating'   => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
    public function messages(): array {
        return [
            'raw_text.required' => 'L\'avis est obligatoire.',
            'raw_text.min'      => 'L\'avis est trop court (20 caractères minimum).',
        ];
    }
}
