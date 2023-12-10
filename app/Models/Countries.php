<?php

namespace App\Models;

class countries extends Mmb
{
    protected $table      = 'def_country';
    protected $primaryKey = 'countryID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_country.* FROM def_country  ';
    }

    public static function queryWhere()
    {
        return '  WHERE def_country.countryID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
