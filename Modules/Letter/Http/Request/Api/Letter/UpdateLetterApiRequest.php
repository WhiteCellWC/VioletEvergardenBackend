<?php

namespace Modules\Letter\Http\Request\Api\Letter;

use App\Enums\SendType;
use App\Models\EnvelopeType;
use App\Models\FragranceType;
use App\Models\LetterType;
use App\Models\PaperType;
use App\Models\User;
use App\Models\WaxSealType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateLetterApiRequest extends FormRequest
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
        $letterValidations = [
            'user_id' => 'required_if:is_draft,0|exists:' . User::table . ',' . User::id,
            'title' => 'nullable|required_if:is_draft,0',
            'body' => 'nullable|required_if:is_draft,0',
            'send_type' => ['nullable', 'required_if:is_draft,0', new Enum(SendType::class)],
            'paper_type_id' => 'nullable|required_if:is_draft,0|exists:' . PaperType::table . ',' . PaperType::id,
            'fragrance_type_id' => 'nullable|exists:' . FragranceType::table . ',' . FragranceType::id,
            'envelope_type_id' => 'nullable|required_if:is_draft,0|exists:' . EnvelopeType::table . ',' . EnvelopeType::id,
            'wax_seal_type_id' => 'nullable|required_if:is_draft,0|exists:' . WaxSealType::table . ',' . WaxSealType::id,
            'is_draft' => 'nullable|boolean',
            'is_sent' => 'nullable|boolean',
            'is_sealed' => 'nullable|boolean',
            'is_printed' => 'nullable|boolean',
        ];

        $letterTypeValidation = [
            'letter_type_ids' => 'nullable|array',
            'letter_type_ids.*' => 'exists:' . LetterType::table . ',' . LetterType::id
        ];

        return array_merge($letterValidations, $letterTypeValidation);
    }
}
