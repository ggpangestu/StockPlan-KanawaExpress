<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [

        'image',
        'name',
        'category',

        'sealed_stock',
        'opened_stock',

        'purchase_unit',
        'base_unit',
        'conversion_value',

        'minimum_stock',
        'latest_price',

        'is_active',

        'created_by',
    ];

    public function getTotalStockAttribute(): float
    {
        return $this->opened_stock +
            $this->convertToBaseUnit(
                $this->sealed_stock
            );
    }

    public function convertToBaseUnit(
        int|float $quantity
    ): float
    {
        return $quantity * $this->conversion_value;
    }

    public function menus() { 
        return $this->belongsToMany(Menu::class, 'menu_raw_material'); }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function transactions()
    {
        return $this->hasMany(
            RawMaterialTransaction::class
        );
    }
}