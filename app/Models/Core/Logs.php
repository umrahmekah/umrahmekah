<?php

namespace App\Models\Core;

use App\Models\Mmb;

class Logs extends Mmb
{
    protected $table      = 'tb_logs';
    protected $primaryKey = 'auditID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tb_logs.* FROM tb_logs  ';
    }

    public static function queryWhere()
    {
        return '  WHERE tb_logs.owner_id = ' . CNF_OWNER . ' AND  tb_logs.auditID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
