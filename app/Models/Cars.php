<?php

namespace App\Models;

class cars extends Mmb
{
    protected $table      = 'cars';
    protected $primaryKey = 'carsID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT cars.* FROM cars  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  cars.owner_id = ' . CNF_OWNER . ' AND cars.carsID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
