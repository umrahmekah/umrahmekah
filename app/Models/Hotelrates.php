<?php

namespace App\Models;

class hotelrates extends Mmb
{
    protected $table      = 'hotel_rates';
    protected $primaryKey = 'hotelrateid';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT hotel_rates.* FROM hotel_rates  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  hotel_rates.owner_id = ' . CNF_OWNER . ' AND hotel_rates.hotelrateid IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
