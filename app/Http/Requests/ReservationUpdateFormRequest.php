<?php

namespace App\Http\Requests;

use App\Models\enums\PaymentMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationUpdateFormRequest extends FormRequest
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
            'user_id'        => ['sometimes', 'integer', Rule::exists('users', 'id')],
            'unit_id'        => ['sometimes', 'integer', Rule::exists('units', 'id')->whereNull('deleted_at')],
            'payment_method' => ['required', 'string',  Rule::in(PaymentMethodEnum::cases())],
            'down_payment'   => ['required', 'numeric', 'min:0'],
            'payment_date'   => ['required', 'date_format:Y-m-d'],
        ];
    }
}
