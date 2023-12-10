<?php

namespace App\Models;

class travellersfiles extends Mmb
{
    protected $table      = 'travellers_files';
    protected $primaryKey = 'fileID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT travellers_files.* FROM travellers_files  ';
    }

    public static function queryWhere()
    {
        return '  WHERE travellers_files.owner_id = ' . CNF_OWNER . ' AND travellers_files.fileID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
