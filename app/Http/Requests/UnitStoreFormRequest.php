<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitStoreFormRequest extends FormRequest
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
            'unit_type'   => ['required', Rule::in([
                'apartment',
                'villa',
                'townhouse',
                'studio',
                'penthouse',
                'duplex',
                'office_space',
                'retail_store',
                'warehouse',
                'serviced_apartment'
            ])],
            'unit_area'              => ['required', 'numeric', 'min:1'],
            'unit_status'            => ['required', Rule::in(['available', 'sold', 'rented', 'under_construction'])],
            'number_bedrooms'        => ['required', 'integer', 'min:0'],
            'number_bathrooms'       => ['required', 'integer', 'min:0'],
            'expected_delivery_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'location_id'            => ['required', 'exists:locations,id'],
            'user_id'                => ['required', 'exists:users,id'],
            'developer_id'           => ['required', 'exists:developers,id'],
        ];
    }
}
