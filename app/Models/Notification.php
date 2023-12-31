<?php

namespace App\Models;

class notification extends Mmb
{
    protected $table      = 'tb_notification';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tb_notification.* FROM tb_notification  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  tb_notification.owner_id = ' . CNF_OWNER . ' AND tb_notification.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
