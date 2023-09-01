<?php

namespace App\Models;

use App\Enums\RoleTypeEnum;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes, Uuid;

    protected $casts = [
        'password' => 'hashed',
        'role' => RoleTypeEnum::class
    ];

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'id',
        'password',
        'remember_token'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'uuid');
    }
}
