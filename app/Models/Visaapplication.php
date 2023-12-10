<?php

namespace App\Models;

class visaapplication extends Mmb
{
    protected $table      = 'visaapplications';
    protected $primaryKey = 'applicationID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT visaapplications.* FROM visaapplications  ';
    }

    public static function queryWhere()
    {
        return '  WHERE visaapplications.owner_id = ' . CNF_OWNER . ' AND visaapplications.applicationID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
