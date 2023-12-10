<?php

namespace App\Models;

class suppliers extends Mmb
{
    protected $table      = 'def_supplier';
    protected $primaryKey = 'supplierID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_supplier.* FROM def_supplier  ';
    }

    public static function queryWhere()
    {
        return '  WHERE def_supplier.owner_id = ' . CNF_OWNER . ' AND def_supplier.supplierID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function type()
    {
        return $this->belongsTo(Suppliertypes::class, 'suppliertypeID');
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'cityID');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'countryID');
    }

    public function services()
    {
        return $this->hasMany(SupplierService::class, 'supplier_id');
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="label label-danger">Not Active</span>';
        }else{
            return '<span class="label label-success">Active</span>';
        }
    }
}
