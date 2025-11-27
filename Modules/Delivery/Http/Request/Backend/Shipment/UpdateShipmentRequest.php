<?php

namespace Modules\Delivery\Http\Request\Backend\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'recipients' => 'required|array|min:1',
            'recipients.*.recipient_id' => 'required|exists:recipients,id',
            'recipients.*.letter_delivery_id' => 'required|exists:letter_deliveries,id',
            'recipients.*.delivery_status' => 'required|in:pending,delivered',
        ];
    }
}
