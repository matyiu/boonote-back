<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'required|unique:users,username|string|min:4|max:255',
            'email' => 'required|unique:users,email|string|min:4|max:255|email',
            'name' => 'required|string',
            'password' => 'required|string|min:10',
            'confirm_password' => 'required|string|min:10|same:password',
        ];
    }
}
