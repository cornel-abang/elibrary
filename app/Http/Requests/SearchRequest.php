<?php

namespace App\Http\Requests;


class SearchRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return ['Invalid input'];
    }
}