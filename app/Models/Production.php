<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductionItem;

class Production extends Model
{
    protected $fillable = ['plan_date', 'status', 'notes', 'created_by'];

    protected $casts = [
        'plan_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(ProductionItem::class);
    }
}