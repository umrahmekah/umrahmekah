<?php

namespace App\Models;

class owners extends Mmb
{
    public $timestamps = false;

    protected $table      = 'tb_owners';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tb_owners.* FROM tb_owners  ';
    }

    public static function queryWhere()
    {
        return '  WHERE tb_owners.id IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function scopeDomain($query, $domain)
    {
        return $query
            ->where('domain', $domain)
            ->orWhere('subdomain', $domain);
    }

    public function admins()
    {
        return $this->hasMany('App\User', 'owner_id')->where('group_id', 2);
    }

    public function travellers()
    {
        return $this->hasMany(Travellers::class, 'owner_id');
    }
    
    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'id');
    }
}
