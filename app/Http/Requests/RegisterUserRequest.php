<?php

namespace App\Http\Requests;


class RegisterUserRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide a name',
            'name.string' => 'The name must be a valid string of character(s)',
            'name.max' => 'The name must must not exceed 255 characters',
            'email.required' => 'Please provide an email address',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address has already been taken',
            'password.required' => 'Please provide a password',
            'password.string' => 'The password must be a valid string of character(s)',
            'password.confirmed' => 'The password and its confirmation do not match',
            'password.min' => 'The password must be at least 8 characters long',
        ];
    }
}
