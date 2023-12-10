<?php

namespace App\Models;

class travelagentnote extends Mmb
{
    protected $table      = 'travel_agent_notes';
    protected $primaryKey = 'travelagentnoteID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT travel_agent_notes.* FROM travel_agent_notes  ';
    }

    public static function queryWhere()
    {
        return '  WHERE travel_agent_notes.owner_id = ' . CNF_OWNER . ' AND travel_agent_notes.travelagentnoteID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
