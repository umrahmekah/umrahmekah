<?php

namespace App\Models\Mmb;

use App\Models\Mmb;

class Module extends Mmb
{
    protected $table      = 'tb_module';
    protected $primaryKey = 'module_id';

    public function __construct()
    {
        parent::__construct();
    }
}
