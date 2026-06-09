<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductionItem;
use App\Models\User;

class Production extends Model
{
    protected $fillable = ['plan_date', 'status','execution_notes', 'notes', 'created_by'];

    protected $casts = [
        'plan_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(ProductionItem::class);
    }

    public function creator()
    {
        // Relasi ini memberi tahu Laravel bahwa kolom 'created_by' adalah milik tabel User
        return $this->belongsTo(User::class, 'created_by');
    }

    public function wastes()
    {
        return $this->hasMany(ProductionWaste::class);
    }
}