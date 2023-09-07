<?php

namespace App\Http\Requests\Export;

use App\Enums\RoleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegistrationsStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role->value == RoleTypeEnum::Admin->value;
    }

    public function rules(): array
    {
        return [
            'competition' => ['required', 'exists:competitions,id'],
            'include_email' => ['required'],
            'include_name' => ['required'],
            'include_phone' => ['required'],
            'include_instagram' => ['nullable'],
            'include_nickname' => ['nullable'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date']
        ];
    }
}
