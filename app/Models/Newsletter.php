<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $guarded = ['id'];

    public function scopeForTenant($query)
    {
        return $query->where('owner_id', owner()->id)->latest();
    }
}
