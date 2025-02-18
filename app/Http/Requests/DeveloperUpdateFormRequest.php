<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeveloperUpdateFormRequest extends FormRequest
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
            'name'        => ['required', 'string'],
            'project_num' => ['required', 'string'],
            'unit_num'    => ['required', 'string'],
            'phone_num'   => ['required', 'string'],
            'address'     => ['required', 'string'],
            'user_id'     => ['nullable', 'integer', Rule::exists('users', 'id')],
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
