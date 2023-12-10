<?php

namespace App\Models\Mmb;

use App\Models\Mmb;

class Menu extends Mmb
{
    public $timestamps = false;

    protected $table      = 'tb_menu';
    protected $primaryKey = 'menu_id';

    public function __construct()
    {
        parent::__construct();
    }
}
