<?php

namespace App\Models;

class testimonials extends Mmb
{
    protected $table      = 'testimonials';
    protected $primaryKey = 'testimonialID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT testimonials.* FROM testimonials  ';
    }

    public static function queryWhere()
    {
        return '  WHERE testimonials.owner_id = ' . CNF_OWNER . ' AND testimonials.testimonialID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
