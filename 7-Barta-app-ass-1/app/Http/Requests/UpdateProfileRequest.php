<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'first-name' => 'required|string|max:255',
            'last-name' => 'nullable|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$userId}",
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'first-name.required' => 'The first name is required.',
            'email.unique' => 'This email is already taken.',
        ];
    }
}
