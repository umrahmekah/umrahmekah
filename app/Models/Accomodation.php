<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accomodation extends Model
{
    public function hotel()
    {
    	return $this->belongsTo(Hotels::class, 'hotel_id');
    }
}
