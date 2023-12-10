<?php

namespace App\Models;

class groupsales extends Mmb
{
    protected $table      = 'sale_record';
    protected $primaryKey = 'saleID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT sale_record.* FROM sale_record  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  sale_record.owner_id = ' . CNF_OWNER . ' AND sale_record.saleID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
