<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'role'
    ];

    protected $guarded = ['id'];
}
