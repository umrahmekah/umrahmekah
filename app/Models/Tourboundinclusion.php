<?php

namespace App\Models;

class tourboundinclusion extends Mmb
{
    protected $table      = 'def_inclusions';
    protected $primaryKey = 'inclusionID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_inclusions.* FROM def_inclusions  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  def_inclusions.owner_id = ' . CNF_OWNER . ' AND def_inclusions.type = 2 AND def_inclusions.inclusionID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
