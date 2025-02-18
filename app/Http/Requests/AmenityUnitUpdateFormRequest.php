<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmenityUnitUpdateFormRequest extends FormRequest
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
            'amenity_ids'   => ['required', 'array'],
            'amenity_ids.*' => ['required', 'integer', Rule::exists('amenities', 'id')->whereNull('deleted_at')],
            // 'unit_id'       => ['required', 'integer', Rule::exists('units', 'id')->whereNull('deleted_at')],
        ];
    }
}
