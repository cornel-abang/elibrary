<?php

namespace App\Http\Requests;


class updateBookRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'author_id' => 'required|exists:authors,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please provide a title',
            'title.string' => 'The title must be a valid string of character(s)',
            'author_id.required' => 'Please provide an author',
            'author_id.exists' => 'The provided author does not exist in our database',
        ];
    }
}
