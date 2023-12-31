<?php

namespace App\Models;

class suppliertypes extends Mmb
{
    protected $table      = 'def_supplier_type';
    protected $primaryKey = 'suppliertypeID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_supplier_type.* FROM def_supplier_type  ';
    }

    public static function queryWhere()
    {
        return '  WHERE def_supplier_type.suppliertypeID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function suppliers()
    {
        return $this->hasMany(Suppliers::class, 'suppliertypeID');
    }
}
