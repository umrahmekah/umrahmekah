<?php

namespace App\Models\Core;

use App\Models\Mmb;

class banners extends Mmb
{
    protected $table      = 'banners';
    protected $primaryKey = 'bannerID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT banners.* FROM banners  ';
    }

    public static function queryWhere()
    {
        return '  WHERE banners.owner_id = ' . CNF_OWNER . ' AND banners.bannerID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
