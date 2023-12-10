<?php

namespace App\Models\Core;

use App\Models\Mmb;

class Pages extends Mmb
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    protected $table      = 'tb_pages';
    protected $primaryKey = 'pageID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tb_pages.* FROM tb_pages  ';
    }

    public static function queryWhere()
    {
        return '  WHERE tb_pages.owner_id = ' . CNF_OWNER . ' AND tb_pages.pageID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
