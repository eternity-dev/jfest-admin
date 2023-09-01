<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginStoreRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return !Auth::check();
    }

    public function messages()
    {
        return  [
            'username' => [
                'exists' => "It seems you are not an organizer",
            ],
            'password' => [
                'string' => 'Field :attribute must be a string',
                'min' => 'Field :attribute needs at least :min chars length'
            ]
        ];
    }

    public function rules(): array
    {
        return [
            'username' => ['nullable', 'exists:organizers,username'],
            'password' => ['nullable', 'string', 'min:8']
        ];
    }
}
