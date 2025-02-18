<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city'          => ['sometimes', 'string',   'max:255'],
            'new_attribute' => ['nullable',  'string',   'max:255'], // Adjust type if necessary
            'neighborhood'  => ['sometimes', 'string',   'max:255'],
            'lat'           => ['sometimes', 'nullable', 'numeric'],
            'lon'           => ['sometimes', 'nullable', 'numeric'],
            'user_id'       => ['sometimes', 'exists:users,id'],
            'unit_id'       => ['sometimes', 'exists:units,id'],
        ];
    }
    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null)
    {
        $data  = parent::validated();

        $data['user_id'] = auth()->user()->id ?? null;

        return $data;
    }
}
