<?php

namespace Modules\User\Http\Request\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterApiRequest extends FormRequest
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
            'password' => 'required|confirmed|min:8'
        ];
    }
}
