<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Uuid
{
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Model $model) {
            if ($model->getAttribute('uuid') === null) {
                $model->setAttribute('uuid', Str::uuid()->toString());
            }
        });
    }
}
