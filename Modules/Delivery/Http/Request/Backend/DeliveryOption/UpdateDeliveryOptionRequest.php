<?php

namespace Modules\Delivery\Http\Request\Backend\DeliveryOption;

use App\Models\DeliveryOption;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryOptionRequest extends FormRequest
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
            'name' => 'required|unique:' . DeliveryOption::table . ',' . DeliveryOption::name .  ',' . $this->route('delivery_option'),
            'is_weight_based' => 'required|boolean',
            'base_cost' => 'required|numeric',
            'delivery_type' => 'nullable',
            'estimated_days' => 'required|numeric',
            'has_tracking' => 'nullable|boolean',
            'status' => 'nullable|boolean'
        ];
    }
}
