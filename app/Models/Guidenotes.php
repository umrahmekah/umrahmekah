<?php

namespace App\Models;

class guidenotes extends Mmb
{
    protected $table      = 'guide_notes';
    protected $primaryKey = 'guidenotesID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT guide_notes.* FROM guide_notes  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  guide_notes.owner_id = ' . CNF_OWNER . ' AND guide_notes.guidenotesID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
