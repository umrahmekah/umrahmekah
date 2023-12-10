<?php

namespace App\Models;

class calendartourdates extends Mmb
{
    protected $table      = 'tour_date';
    protected $primaryKey = 'tourdateID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tour_date.* FROM tour_date  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  tour_date.owner_id = ' . CNF_OWNER . ' AND tour_date.tourdateID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
