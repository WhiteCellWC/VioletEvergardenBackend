<?php

namespace Modules\Delivery\Http\Request\Backend\DeliveryTier;

use App\Models\DeliveryOption;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryTierRequest extends FormRequest
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
            'delivery_option_id' => 'required|exists:' . DeliveryOption::table . ',' . DeliveryOption::id,
            'max_weight' => 'required|numeric',
            'price' => 'required|numeric',
            'status' => 'nullable|boolean'
        ];
    }
}
