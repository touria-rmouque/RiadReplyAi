<?php
namespace App\Http\Requests;
use App\Enums\EstablishmentTone;
use App\Enums\EstablishmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEstablishmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(EstablishmentType::class)],
            'tone' => ['required', new Enum(EstablishmentTone::class)],
        ];
    }
}
