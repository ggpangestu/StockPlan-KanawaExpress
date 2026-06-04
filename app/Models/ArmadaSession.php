<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArmadaSession extends Model
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_FINISHED = 'finished';

    protected $fillable = [
        'armada_user_id',
        'allocated_by',
        'session_date',
        'status',
        'started_at',
        'finished_at',
        'notes',
    ];

    protected $casts = [
        'session_date' => 'date',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function armada()
    {
        return $this->belongsTo(User::class, 'armada_user_id');
    }

    public function allocator()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function items()
    {
        return $this->hasMany(ArmadaSessionItem::class);
    }
}
