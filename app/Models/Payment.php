<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, Uuid;

    protected $casts = [
        'amount' => 'float',
        'fee' => 'float',
        'status' => PaymentStatusEnum::class
    ];

    protected $fillable = [
        'order_id',
        'transaction_id',
        'amount',
        'fee',
        'link',
        'method',
        'status'
    ];

    protected $guarded = ['id', 'transaction_id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
