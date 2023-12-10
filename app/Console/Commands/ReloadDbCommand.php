<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReloadDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reload:db
                                {--d|dev : Seed development data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload Database';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Importing ' . database_path('sql/table.sql'));
        $this->call('migrate:import', ['file' => 'table.sql']);

        $this->comment('Importing ' . database_path('sql/data.sql'));
        $this->call('migrate:import', ['file' => 'data.sql']);

        if ($this->option('dev')) {
            $this->call('seed:dev');
        }
    }
}
