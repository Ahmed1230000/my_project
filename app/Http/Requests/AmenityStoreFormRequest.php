<?php

namespace App\Http\Requests;

use App\Models\enums\PlaceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmenityStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => ['sometimes','nullable' ,'string', 'max:255'],
            // 'place_type' => ['required', 'string', Rule::in('Gym', 'Club', 'university', 'hospital', 'other')],
            'place_type' => ['required', 'string', Rule::in(PlaceTypeEnum::cases())],
            'distance'   => ['required', 'string', 'max:255']
        ];
    }
}
