<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialTransaction extends Model
{

    protected $casts = [
        'purchase_quantity' => 'decimal:2',
        'quantity' => 'decimal:2',
        'before_stock' => 'decimal:2',
        'after_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

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

    public const TYPE_RESTOCK = 'restock';
    public const TYPE_ADJUSTMENT_ADD = 'adjustment_add';
    public const TYPE_ADJUSTMENT_REDUCE = 'adjustment_reduce';
    public const TYPE_PRODUCTION_USAGE = 'production_usage';

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

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {

            self::TYPE_RESTOCK =>
                'Restock',

            self::TYPE_ADJUSTMENT_ADD =>
                'Adjustment Add',

            self::TYPE_ADJUSTMENT_REDUCE =>
                'Adjustment Reduce',

            self::TYPE_PRODUCTION_USAGE =>
                'Production Usage',

            default =>
                'Activity',
        };
    }

    public function getIsRestockAttribute(): bool
    {
        return $this->type === self::TYPE_RESTOCK;
    }
    
    public function getIsAdjustmentAttribute(): bool
    {
        return in_array(
            $this->type,
            [
                self::TYPE_ADJUSTMENT_ADD,
                self::TYPE_ADJUSTMENT_REDUCE,
            ]
        );
    }
}