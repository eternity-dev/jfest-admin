<?php

namespace App\Models;

use App\Enums\EventTypeEnum;
use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use Slug;

    protected $casts = [
        'price' => 'integer',
        'with_ticket' => 'boolean',
        'use_instagram_field' => 'boolean',
        'use_nickname_field' => 'boolean',
        'use_multi_participant' => 'boolean',
        'min_participants' => 'integer',
        'max_participants' => 'integer',
        'registration_opened_at' => 'datetime:Y-m-d',
        'registration_closed_at' => 'datetime:Y-m-d'
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'price_tag',
        'group_url',
        'guide_book_url',
        'image_url',
        'with_ticket',
        'use_instagram_field',
        'use_nickname_field',
        'use_multi_participant',
        'min_participants',
        'max_participants',
        'registration_opened_at',
        'registration_closed_at'
    ];

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::retrieved(function (Model $model) {
            $model->setAttribute('type', EventTypeEnum::Competition->value);
        });
    }
}
