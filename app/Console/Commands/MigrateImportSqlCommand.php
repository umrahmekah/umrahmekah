<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateImportSqlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:import {file : File name located in database/sql directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SQL file into database';

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
        $file = database_path('sql/' . $this->argument('file'));

        if (! file_exists($file)) {
            $this->error('SQL File not exist.');

            return;
        }

        DB::unprepared(file_get_contents($file));
        $this->info('Done Import SQL File');
    }
}
