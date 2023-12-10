<?php

namespace App\Models;

class credittotals extends Mmb
{
    protected $table      = 'credittotals';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT credittotals.*, o.name FROM credittotals LEFT JOIN tb_owners AS o on o.id=credittotals.owner_id';
    }

    public static function queryWhere()
    {
        return '  WHERE credittotals.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
