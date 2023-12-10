<?php

namespace App\Models;

class invoice extends Mmb
{
    protected $table      = 'invoice';
    protected $primaryKey = 'invoiceID';

    protected $dates = ['discount_at'];

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT invoice.* FROM invoice  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  invoice.owner_id = ' . CNF_OWNER . ' AND invoice.invoiceID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function products()
    {
        return $this->hasMany(InvoiceProduct::class, 'InvID');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'invoiceID');
    }

    public function booking()
    {
        return $this->belongsTo(Createbooking::class, 'bookingID');
    }

    public function entryByUser()
    {
        return $this->belongsTo('App\User', 'entry_by');
    }

    public function discountByUser()
    {
        return $this->belongsTo('App\User', 'discount_by');
    }

    public function invoicePaymentMethod()
    {
        return $this->hasOne('App\Models\InvoicePaymentMethod', 'invoiceID');
    }

    public function getTotalPaidAttribute()
    {
        $payments = $this->payments;

        $total = 0;

        foreach ($payments as $payment) {
            $total += $payment->amount;
        }

        return $total;
    }

    public function getBalanceAttribute()
    {
        $paid = $this->totalPaid;

        $balance = $this->InvTotal - $paid;

        return $balance;
    }

    public function getPayStatusAttribute()
    {
        $balance = $this->balance;
        if ($balance == $this->InvTotal && $balance != 0) {
            $status = 'Awaiting Payment';
        }elseif ($balance < $this->InvTotal && $balance != 0) {
            $status = 'Partially Paid';
        }else{
            $status = "Paid";
        }
        return $status;
    }
}
