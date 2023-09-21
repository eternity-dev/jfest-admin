<?php

namespace App\Models;

use App\Enums\OrganizerRoleEnum;
use App\Traits\Uuid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Organizer extends Authenticatable
{
    use HasApiTokens, Uuid;

    protected $casts = [
        'password' => 'hashed',
        'role' => OrganizerRoleEnum::class
    ];

    protected $fillable = [
        'name',
        'username',
        'password',
        'role'
    ];

    protected $guarded = ['id'];

    protected $hidden = [
        'id',
        'password'
    ];
}
