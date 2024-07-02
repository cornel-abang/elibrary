<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * The BaseRequest class serves as a base class for 
 * all request validation classes. 
 * 
 * The single method here ensures 
 * validation errors are returned as json
 * 
 * @param Validator $validator
 * 
 * @throws HttpResponseException
*/
class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
