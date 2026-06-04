<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArmadaSessionItem extends Model
{
    protected $fillable = [
        'armada_session_id',
        'finished_good_id',
        'quantity_sent',
        'quantity_sold',
        'quantity_returned',
    ];

    public function session()
    {
        return $this->belongsTo(ArmadaSession::class, 'armada_session_id');
    }

    public function finishedGood()
    {
        return $this->belongsTo(FinishedGood::class);
    }

    public function returnCheck()
    {
        return $this->hasOne(ReturnCheck::class);
    }
}
