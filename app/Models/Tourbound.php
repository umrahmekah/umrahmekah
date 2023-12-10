<?php

namespace App\Models;

class tourbound extends Mmb
{
    protected $table      = 'tours';
    protected $primaryKey = 'tourID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tours.* FROM tours ';
    }

    public static function queryWhere()
    {
        return '  WHERE tours.owner_id = ' . CNF_OWNER . ' AND tours.type = 2 AND tours.tourID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function tourdates()
    {
        return $this->hasMany(Tourdates::class, 'tourID');
    }

    public function getMinPriceAttribute()
    {
        $tourdates = $this->tourdates;

        $min_price = 0;

        foreach ($tourdates as $key => $tourdate) {
            if (($tourdate->cost_single > 0 && $tourdate->cost_single < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_single;
            }

            if (($tourdate->cost_double > 0 && $tourdate->cost_double < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_double;
            }

            if (($tourdate->cost_triple > 0 && $tourdate->cost_triple < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_triple;
            }

            if (($tourdate->cost_quad > 0 && $tourdate->cost_quad < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_quad;
            }

            if (($tourdate->cost_quint > 0 && $tourdate->cost_quint < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_quint;
            }

            if (($tourdate->cost_sext > 0 && $tourdate->cost_sext < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_sext;
            }
        }

        return $min_price;
    }

    public function getFilteredTourdateAttribute()
    {
        $tourdates = $this->tourdates->filter( function ($query) {
            return $query->notStartYet && $query->capacity > 0;
        });

        return $tourdates;
    }
}
