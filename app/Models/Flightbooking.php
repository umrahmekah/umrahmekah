<?php

namespace App\Models;

class flightbooking extends Mmb
{
    protected $table      = 'flight_booking';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT flight_booking.* FROM flight_booking  ';
    }

    public static function queryWhere()
    {
        return '  WHERE flight_booking.owner_id = ' . CNF_OWNER . ' AND flight_booking.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function depart()
    {
        return $this->belongsTo(Flightmatching::class, 'flight_match_depart_id');
    }

    public function return()
    {
        return $this->belongsTo(Flightmatching::class, 'flight_match_return_id');
    }
}
