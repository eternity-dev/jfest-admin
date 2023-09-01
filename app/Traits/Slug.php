<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Slug
{
    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Model $model) {
            if ($model->getAttribute('slug') === null) {
                $slug = Str::slug($model->getAttributeValue('name'));
                $model->setAttribute('slug', $slug);
            }
        });
    }
}
