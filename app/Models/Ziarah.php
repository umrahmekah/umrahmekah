<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ziarah extends Model
{
    public function transportsupplier()
    {
    	return $this->belongsTo(Suppliers::class, 'transport');
    }
}
