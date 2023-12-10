<?php

namespace App\Models;

class flightmatching extends Mmb
{
    protected $table      = 'flight_matching';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT flight_matching.* FROM flight_matching  ';
    }

    public static function queryWhere()
    {
        return '  WHERE flight_matching.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function flightDate()
    {
        return $this->belongsTo(Flightdates::class, 'flight_date');
    }
}
