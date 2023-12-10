<?php

namespace App\Models;

class paymentgateways extends Mmb
{
    protected $table      = 'payment_gateways';
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var object
     */
    protected $casts = [
        'data' => 'object'
    ];

    
}
