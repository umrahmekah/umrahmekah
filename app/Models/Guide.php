<?php

namespace App\Models;

class guide extends Mmb
{
    protected $table      = 'guides';
    protected $primaryKey = 'guideID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT guides.* FROM guides ';
    }

    public static function queryWhere()
    {
        return '  WHERE  guides.owner_id = ' . CNF_OWNER . ' AND guides.guideID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
