<?php

namespace App\Models;

class bookflight extends Mmb
{
    protected $table      = 'book_flight';
    protected $primaryKey = 'bookflightID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT book_flight.* FROM book_flight  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  book_flight.owner_id = ' . CNF_OWNER . ' AND book_flight.bookflightID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
