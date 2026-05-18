<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'image',
        'description',
        'is_active',
        'created_by'
    ];

    // Relasi ke Raw Materials (Ingredients)
    public function ingredients()
    {
        return $this->belongsToMany(RawMaterial::class, 'menu_raw_material')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
