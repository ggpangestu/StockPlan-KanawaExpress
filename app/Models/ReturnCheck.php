<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnCheck extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_READY = 'ready';
    public const STATUS_EXPIRED_DAMAGED = 'expired_damaged';

    protected $fillable = [
        'armada_session_item_id',
        'finished_good_id',
        'armada_user_id',
        'checked_by',
        'rejected_finished_good_id',
        'quantity',
        'status',
        'checked_at',
        'notes',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function sessionItem()
    {
        return $this->belongsTo(ArmadaSessionItem::class, 'armada_session_item_id');
    }

    public function finishedGood()
    {
        return $this->belongsTo(FinishedGood::class);
    }

    public function armada()
    {
        return $this->belongsTo(User::class, 'armada_user_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function rejectedFinishedGood()
    {
        return $this->belongsTo(FinishedGood::class, 'rejected_finished_good_id');
    }
}
