<?php

namespace Modules\Delivery\Http\Request\Api\V1;

use App\Enums\SendType;
use App\Models\Country;
use App\Models\EnvelopeType;
use App\Models\FragranceType;
use App\Models\Letter;
use App\Models\PaperType;
use App\Models\User;
use App\Models\WaxSealType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SendLetterApiRequest extends FormRequest
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
            'user_id' => 'nullable|exists:' . User::table . ',' . User::id,
            'letter_id' => 'nullable|exists:' . Letter::table . ',' . Letter::id,
            'is_draft' => 'required|boolean',
            'is_sealed' => 'required|boolean',

            'body' => 'required',
            'paper_type_id' => 'required|exists:' . PaperType::table . ',' . PaperType::id,
            'envelope_type_id' => 'required|exists:' . EnvelopeType::table . ',' . EnvelopeType::id,
            'wax_seal_type_id' => ['nullable', 'required_if:send_type,' . SendType::PHYSICAL->value, 'exists:' . WaxSealType::table . ',' . WaxSealType::id],
            'fragrance_type_id' => 'nullable|exists:' . FragranceType::table . ',' . FragranceType::id,
            'send_type' => ['required', new Enum(SendType::class)],

            'recipients' => 'required|array',
            'recipients.*.name' => 'required|string|max:255',
            'recipients.*.email' => 'required|email',
            'recipients.*.address_line1' => 'required|string|max:255',
            'recipients.*.country_id' => 'required|exists:countries,id',
            'recipients.*.state_id' => 'required|exists:states,id',
            'recipients.*.postal_code' => 'required|string|max:20',
            'recipients.*.phone' => 'nullable|string|max:50',
            'recipients.*.address_line2' => 'nullable|string|max:255',
            'recipients.*.variables' => 'nullable',

            'delivery_option_id' => ['nullable', 'required_if:send_type,' . SendType::PHYSICAL->value],
            'delivery_tier_id' => ['nullable', 'required_if:send_type,' . SendType::PHYSICAL->value],
            'scheduled_date' => 'required'
        ];
    }
}
