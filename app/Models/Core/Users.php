<?php

namespace App\Models\Core;

use App\Models\Mmb;

class Users extends Mmb
{
    protected $table      = 'tb_users';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return ' SELECT  tb_users.*,  tb_groups.name 
FROM tb_users LEFT JOIN tb_groups ON tb_groups.group_id = tb_users.group_id ';
    }

    public static function queryWhere()
    {
        return '    WHERE  tb_users.owner_id = ' . CNF_OWNER . " AND tb_users.id !='' ";
    }

    public static function queryGroup()
    {
        return '      ';
    }
}
