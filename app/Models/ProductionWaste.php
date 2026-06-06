<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionWaste extends Model
{
    protected $fillable = ['production_id', 'raw_material_id', 'quantity'];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
}
