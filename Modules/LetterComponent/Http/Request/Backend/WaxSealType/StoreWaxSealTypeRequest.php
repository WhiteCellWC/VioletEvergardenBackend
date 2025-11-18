<?php

namespace Modules\LetterComponent\Http\Request\Backend\WaxSealType;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreWaxSealTypeRequest extends FormRequest
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
            'name' => 'required',
            'images' => 'required|array',
            'images.*' => 'image',
            'is_custom' => 'nullable|boolean',
            'price' => 'required|numeric|min:0',
            'is_premium' => 'nullable|boolean',
            'discount' => 'nullable|min:0|max:100',
            'status' => 'nullable|boolean'
        ];
    }
}
