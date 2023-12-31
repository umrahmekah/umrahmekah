<?php

namespace App\Models;

class carextras extends Mmb
{
    protected $table      = 'def_car_extras';
    protected $primaryKey = 'carsextrasID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_car_extras.* FROM def_car_extras  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  def_car_extras.owner_id = ' . CNF_OWNER . ' AND def_car_extras.carsextrasID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
