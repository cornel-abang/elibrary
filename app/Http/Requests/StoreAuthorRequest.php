<?php

namespace App\Http\Requests;


class StoreAuthorRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide a name',
            'name.string' => 'The name must be a valid string of character(s)',
        ];
    }
}
