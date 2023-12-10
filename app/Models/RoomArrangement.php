<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomArrangement extends Model
{
    public function entry()
    {
    	return $this->belongsTo('App\User', 'entry_by');
    }

    public function tourdate()
    {
    	return $this->belongsTo(Tourdates::class, 'tourdate_id');
    }
}
