<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedGood extends Model
{
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
}