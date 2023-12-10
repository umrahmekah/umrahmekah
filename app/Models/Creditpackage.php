<?php

namespace App\Models;

class creditpackage extends Mmb
{
    protected $table      = 'credit_package';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT credit_package.* FROM credit_package  ';
    }

    public static function queryWhere()
    {
        return '  WHERE credit_package.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
