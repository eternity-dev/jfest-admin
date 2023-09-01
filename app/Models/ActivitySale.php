<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivitySale extends Model
{
    protected $casts = [
        'price' => 'float',
    ];

    protected $fillable = [
        'unique_id',
        'name',
        'price',
        'tickets_qty_available'
    ];

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::retrieved(function (Model $model) {
            $dbTicketsCount = Ticket::whereNot('code', null)->whereNot('price', 0)->count();
            $ticketsQtyAvailable = $model->getAttribute('tickets_qty_available');
            
            logger()->channel('error')->error('debug sale', ['db' => $dbTicketsCount, 'local' => $ticketsQtyAvailable]);

            $model->setAttribute(
                'is_tickets_available',
                is_null($ticketsQtyAvailable) ? true : ($ticketsQtyAvailable >= $dbTicketsCount)
            );
        });
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
