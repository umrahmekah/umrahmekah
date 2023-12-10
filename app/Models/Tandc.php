<?php

namespace App\Models;

class tandc extends Mmb
{
    protected $table      = 'termsandconditions';
    protected $primaryKey = 'tandcID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT termsandconditions.* FROM termsandconditions  ';
    }

    public static function queryWhere()
    {
        return '  WHERE termsandconditions.owner_id = ' . CNF_OWNER . ' AND termsandconditions.type = 1 AND termsandconditions.tandcID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
