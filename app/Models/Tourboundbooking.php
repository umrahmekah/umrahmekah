<?php

namespace App\Models;

class tourboundbooking extends Mmb
{
    protected $table      = 'bookings';
    protected $primaryKey = 'bookingsID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT bookings.* FROM bookings  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  bookings.owner_id = ' . CNF_OWNER . ' AND bookings.type = 2 AND bookings.bookingsID IS NOT NULL';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
