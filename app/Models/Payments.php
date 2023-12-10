<?php

namespace App\Models;

class payments extends Mmb
{
    protected $table      = 'invoice_payments';
    protected $primaryKey = 'invoicePaymentID';
    protected $dates = ['created_at', 'updated_at'];

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT invoice_payments.* FROM invoice_payments  ';
    }

    public static function queryWhere()
    {
        return '  WHERE invoice_payments.owner_id = ' . CNF_OWNER . ' AND  invoice_payments.invoicePaymentID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function traveller()
    {
        return $this->belongsTo(Travellers::class, 'travellerID');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoiceID');
    }

    public function entryByUser()
    {
        return $this->belongsTo('App\User', 'entry_by');
    }
}
