<?php

namespace App\Models;

class tourcategories extends Mmb
{
    protected $table      = 'def_tour_categories';
    protected $primaryKey = 'tourcategoriesID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT def_tour_categories.* FROM def_tour_categories  ';
    }

    public static function queryWhere()
    {
        return '  WHERE def_tour_categories.owner_id = ' . CNF_OWNER . ' AND def_tour_categories.type = 1 AND def_tour_categories.tourcategoriesID IS NOT NULL ';
        //return "  WHERE def_tour_categories.tourcategoriesID IS NOT NULL ";
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function tours()
    {
        return $this->hasMany(Tours::class, 'tourcategoriesID');
    }

    public function tourdates()
    {
        return $this->hasMany(Tourdates::class, 'tourcategoriesID');
    }

    public function getCategoryCountattribute()
    {
        return $this->tours->where('status', 1)->count();
    }
}
