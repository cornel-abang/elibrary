<?php

namespace App\Http\Requests;


class LoginRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Please provide an email address',
            'email.email' => 'Please provide a valid email address',
            'password.required' => 'Please provide a password',
        ];
    }
}
