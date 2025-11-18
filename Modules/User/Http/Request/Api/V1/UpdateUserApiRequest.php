<?php

namespace Modules\User\Http\Request\Api\V1;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserApiRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|unique:' . User::table . ',' . User::email,
            'date_of_birth' => 'nullable|date',
            'gender' => [
                'nullable',
                Rule::in(array_column(Gender::cases(), 'value')),
            ],
            'profile_image' => 'nullable|image',
            'bio' => 'nullable'
        ];
    }
}
