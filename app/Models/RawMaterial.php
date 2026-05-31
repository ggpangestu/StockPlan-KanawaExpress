<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RawMaterial extends Model
{

    protected $casts = [
        'sealed_stock' => 'decimal:2',
        'opened_stock' => 'decimal:2',
        'conversion_value' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'latest_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

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
    | STOCK HEALTH
    |--------------------------------------------------------------------------
    |
    | CURRENT ENGINE
    |--------------------------------------------------------------------------
    |
    | Current stock health uses STATIC ratio calculation:
    |
    | total_stock / minimum_stock
    |
    | This is intentionally deterministic and lightweight while
    | the inventory system still lacks stable production usage data.
    |
    |--------------------------------------------------------------------------
    | FUTURE PREDICTIVE ENGINE
    |--------------------------------------------------------------------------
    |
    | When the system has enough operational history
    | (recipe usage, production records, depletion trends),
    | this accessor should evolve into a predictive engine.
    |
    | Future calculation candidates:
    |
    | - average daily usage
    | - estimated depletion days
    | - abnormal usage spikes
    | - production consumption trends
    |
    | IMPORTANT:
    | Keep the OUTPUT interface stable:
    |
    | critical
    | warning
    | caution
    | healthy
    |
    | Only replace the INTERNAL calculation engine.
    | prompts for future AI integration: mari lanjutkan future predictive engine yang dulu kita tanam di stock health accessor.
    |
    */

    public function getStockHealthAttribute(): string
    {
        $minimum =
            max($this->minimum_stock, 1);

        $ratio =
            $this->total_stock / $minimum;

        return match (true) {

            $ratio <= 1 => 'critical',

            $ratio <= 1.5 => 'warning',

            $ratio <= 2 => 'caution',

            default => 'healthy',
        };
    }

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

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
}