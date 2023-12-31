<?php

namespace App\Models;

class bookhotel extends Mmb
{
    protected $table      = 'book_hotel';
    protected $primaryKey = 'bookhotelID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT book_hotel.* FROM book_hotel  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  book_hotel.owner_id = ' . CNF_OWNER . ' AND book_hotel.bookhotelID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
