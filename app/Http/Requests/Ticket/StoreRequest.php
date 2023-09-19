<?php

namespace App\Http\Requests\Ticket;

use App\Enums\RoleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role->value == RoleTypeEnum::Admin->value;
    }

    public function rules(): array
    {
        return [
            'activity' => ['exists:activities,name'],
            'user' => ['exists:users,email'],
            'price' => ['exists:activity_sales,id'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
