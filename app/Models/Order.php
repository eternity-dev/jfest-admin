<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $casts = [
        'expired_at' => 'date:Y-m-d',
        'status' => OrderStatusEnum::class,
        'total_price' => 'integer'
    ];

    protected $fillable = [
        'user_id',
        'status',
        'total_price'
    ];

    protected $guarded = ['id'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Model $model) {
            if ($model->getAttribute('reference') === null) {
                $userId = $model->getAttribute('user_id');
                $userId = explode('-', $userId)[0];

                $model->setAttribute('reference', Str::upper(
                    uniqid(Str::slug(
                        env('APP_NAME') . '-' .
                        $userId
                    ) . '-')
                ));
            }

            if ($model->getAttribute('expired_at') === null) {
                $model->setAttribute('expired_at', now()->addDay());
            }
        });
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
}
