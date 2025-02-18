<?php

namespace App\Http\Requests;

use App\Models\enums\PaymentMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationStoreFormRequest extends FormRequest
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
            'unit_id'        => ['required', 'integer', Rule::exists('units', 'id')->whereNull('deleted_at')],
            'payment_method' => ['required', 'string', Rule::in(PaymentMethodEnum::cases())],
            'down_payment'   => ['required', 'numeric', 'min:0'],
            'payment_date'   => ['required', 'date'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();
        $validated['user_id'] = auth()->user()->id;
        return $validated;
    }
}
