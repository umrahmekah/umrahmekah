<?php

namespace App\Models;

class calendar extends Mmb
{
    protected $table      = 'calendar';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT calendar.* FROM calendar  ';
    }

    public static function queryWhere()
    {
        return '  WHERE calendar.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
