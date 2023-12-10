<?php

namespace App\Models;

class agents extends Mmb
{
    protected $table      = 'travel_agent_agent';
    protected $primaryKey = 'agentID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT travel_agent_agent.* FROM travel_agent_agent  ';
    }

    public static function queryWhere()
    {
        return '  WHERE travel_agent_agent.owner_id = ' . CNF_OWNER . ' AND travel_agent_agent.agentID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
