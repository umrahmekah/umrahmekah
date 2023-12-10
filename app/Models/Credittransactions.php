<?php

namespace App\Models;

class credittransactions extends Mmb
{
    protected $table      = 'credit_transactions';
    protected $primaryKey = 'id';
    protected $fillable   = ['entry_by', 'owner_id', 'transaction_id', 'amount_paid', 'credit_request', 'transaction_date', 'payment_gateway_id', 'currency', 'agency'];

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT credit_transactions.* FROM credit_transactions  ';
    }

    public static function queryWhere()
    {
        return '  WHERE credit_transactions.owner_id = ' . CNF_OWNER . ' AND credit_transactions.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
