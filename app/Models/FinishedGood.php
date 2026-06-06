<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedGood extends Model
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_EMPTY = 'empty';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_EXPIRED_DAMAGED = 'expired_damaged';

    protected $fillable = [
        'menu_id',
        'production_id',
        'initial_quantity',
        'current_quantity',
        'production_date',
        'expired_date',
        'status'
    ];

    protected $casts = [
        'production_date' => 'date',
        'expired_date' => 'date',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function armadaSessionItems()
    {
        return $this->hasMany(ArmadaSessionItem::class);
    }

    public function returnChecks()
    {
        return $this->hasMany(ReturnCheck::class);
    }
}
