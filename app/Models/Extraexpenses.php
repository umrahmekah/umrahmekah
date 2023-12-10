<?php

namespace App\Models;

class extraexpenses extends Mmb
{
    protected $table      = 'def_extra_expenses';
    protected $primaryKey = 'expenseID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_extra_expenses.* FROM def_extra_expenses  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  def_extra_expenses.owner_id = ' . CNF_OWNER . ' AND def_extra_expenses.expenseID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
