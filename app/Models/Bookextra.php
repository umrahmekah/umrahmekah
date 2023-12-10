<?php

namespace App\Models;

class bookextra extends Mmb
{
    protected $table      = 'book_extra';
    protected $primaryKey = 'bookextraID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT book_extra.* FROM book_extra  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  book_extra.owner_id = ' . CNF_OWNER . ' AND book_extra.bookextraID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
