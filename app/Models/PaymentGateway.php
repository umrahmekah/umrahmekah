<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
	protected $table = 'payment_gateways';
	
    /**
     * The attributes that should be cast to native types.
     *
     * @var object
     */
    protected $casts = [
        'data' => 'object'
    ];
}
