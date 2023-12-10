<?php

namespace App\Models;

class hotelsnote extends Mmb
{
    protected $table      = 'hotels_note';
    protected $primaryKey = 'hotel_noteID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT hotels_note.* FROM hotels_note  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  hotels_note.owner_id = ' . CNF_OWNER . ' AND hotels_note.hotel_noteID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
