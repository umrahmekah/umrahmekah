<?php

namespace App\Models;

class tourdetail extends Mmb
{
    protected $table      = 'tour_detail';
    protected $primaryKey = 'tourdetailID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tour_detail.* FROM tour_detail  ';
    }

    public static function queryWhere()
    {
        return '  WHERE tour_detail.owner_id = ' . CNF_OWNER . ' AND tour_detail.tourdetailID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
