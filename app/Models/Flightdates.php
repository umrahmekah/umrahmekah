<?php

namespace App\Models;

class flightdates extends Mmb
{
    protected $table      = 'flight_date';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT flight_date.* FROM flight_date  ';
    }

    public static function queryWhere()
    {
        return '  WHERE flight_date.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
