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
            'competition' => ['required']
        ];
    }
}
