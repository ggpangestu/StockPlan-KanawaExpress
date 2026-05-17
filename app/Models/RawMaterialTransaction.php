<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialTransaction extends Model
{
    protected $fillable = [

        'raw_material_id',

        'type',

        'purchase_quantity',

        'quantity',

        'before_stock',
        'after_stock',

        'notes',

        'unit_price',
        'total_price',

        'created_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}