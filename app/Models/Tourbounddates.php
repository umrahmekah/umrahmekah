<?php

namespace App\Models;
use Carbon;

class tourbounddates extends Mmb
{
    protected $table      = 'tour_date';
    protected $primaryKey = 'tourdateID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tour_date.* FROM tour_date  ';
    }

    public static function queryWhere()
    {
        return '  WHERE tour_date.owner_id = ' . CNF_OWNER . ' AND tour_date.type = 2 AND tour_date.tourdateID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function booktours()
    {
        return $this->hasMany(Booktour::class, 'tourdateID');
    }

    public function getPaxAttribute()
    {
        $pax = 0;

        foreach ($this->booktours as $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                $pax += $booking->pax;
            }
        }

        return $pax;
    }

    public function getCapacityAttribute()
    {
        $pax = $this->pax;

        return $this->total_capacity - $pax;
    }

    public function getNotStartYetAttribute()
    {
        $today = Carbon::today();
        $start = Carbon::parse($this->start);

        return $start > $today;
    }
}
