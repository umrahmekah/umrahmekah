<?php

namespace App\Models\Template;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = ['id'];

    protected $table = 'template_configurations';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'config' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(\App\Models\Owners::class, 'owner_id');
    }

    public function scopeForOwner($query)
    {
        return $query
            ->with('owner')
            ->where('owner_id', owner()->id);
    }
}
