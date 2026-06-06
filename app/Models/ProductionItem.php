<?php

// app/Models/ProductionItem.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class ProductionItem extends Model
{
    protected $fillable = ['production_id', 'menu_id', 'target_quantity', 'actual_quantity', 'wasted_quantity'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
