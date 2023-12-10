<?php

namespace App\Models;

class hotels extends Mmb
{
    protected $table      = 'hotels';
    protected $primaryKey = 'hotelID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT hotels.* FROM hotels ';
    }

    public static function queryWhere()
    {
        return '  WHERE  hotels.owner_id = ' . CNF_OWNER . ' AND hotels.hotelID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'countryID');
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'cityID');
    }
}
