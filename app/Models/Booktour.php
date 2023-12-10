<?php

namespace App\Models;

class booktour extends Mmb
{
    protected $table      = 'book_tour';
    protected $primaryKey = 'booktourID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT book_tour.* FROM book_tour  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  book_tour.owner_id = ' . CNF_OWNER . ' AND book_tour.booktourID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function tourdate()
    {
        return $this->belongsTo(Tourdates::class, 'tourdateID');
    }

    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tourID');
    }

    public function booking()
    {
        return $this->belongsTo(Createbooking::class, 'bookingID');
    }

}
