<?php

namespace App\Models;

use App\Enums\EventTypeEnum;
use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use Slug;

    protected $casts = [
        'date' => 'date:Y-m-d',
        'purchase_opened_at' => 'datetime:Y-m-d',
        'purchase_closed_at' => 'datetime:Y-m-d'
    ];

    protected $fillable = [
        'activity_sale_id',
        'name',
        'description',
        'image_url',
        'date',
        'purchase_opened_at',
        'purchase_closed_at'
    ];

    protected $guarded = ['id'];

    protected $with = ['sale'];

    protected static function booted()
    {
        static::retrieved(function (Model $model) {
            $model->setAttribute('type', EventTypeEnum::Activity->value);
        });
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(ActivitySale::class, 'activity_sale_id', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
