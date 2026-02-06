<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Make email unique, but ignore the current user
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'phone' => ['required', 'string', 'min:7', 'max:30'],
        ];
    }
}