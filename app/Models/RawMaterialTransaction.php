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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(
        $query,
        ?string $search
    )
    {
        return $query->when(

            $search,

            function ($query) use ($search) {

                $query->whereHas(

                    'rawMaterial',

                    function ($material) use ($search) {

                        $material->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                    }

                );

            }

        );
    }

    public function scopeType(
        $query,
        ?string $type
    )
    {
        return $query->when(

            $type,

            fn ($query) =>

                $query->where(
                    'type',
                    $type
                )

        );
    }

    public function scopeTimeframe(
        $query,
        ?string $timeframe
    ) {

        return match ($timeframe) {

            'today' =>
                $query->whereDate(
                    'created_at',
                    today()
                ),

            'past_7_days' =>
                $query->where(
                    'created_at',
                    '>=',
                    now()->subDays(7)
                ),

            'past_30_days' =>
                $query->where(
                    'created_at',
                    '>=',
                    now()->subDays(30)
                ),

            'this_month' =>
                $query->whereMonth(
                    'created_at',
                    now()->month
                )
                ->whereYear(
                    'created_at',
                    now()->year
                ),

            default => $query,

        };

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