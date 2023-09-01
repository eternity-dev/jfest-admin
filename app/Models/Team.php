<?php

namespace App\Models;

use App\Traits\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use Slug;

    protected $fillable = [
        'registration_id',
        'name',
        'number_of_members',
    ];

    protected $guarded = ['id'];

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
