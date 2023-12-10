<?php

namespace App\Models\Core;

use App\Models\Mmb;

class Owners extends Mmb
{
    protected $table      = 'tb_owners';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return ' SELECT  tb_owners.* FROM tb_owners ';
    }

    public static function queryWhere()
    {
        return "    WHERE tb_owners.id !=''   ";
    }

    public static function queryGroup()
    {
        return '      ';
    }
}
