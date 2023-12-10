<?php

namespace App\Console\Commands;

use App\Models\Owners;
use App\Models\Travellers;
use Carbon;
use Illuminate\Console\Command;
use Mail;
use Illuminate\Support\Facades\Log;

class RamadhanBlast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:ramadhan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send ramadhan email for travellers';

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
        $owners = Owners::get();

        foreach ($owners as $key => $owner) {
            $travellers = Travellers::where('owner_id', $owner->id)->get();
            foreach ($travellers as $key => $traveller) {
                if ($traveller->email) {
                    Mail::send('emails.ramadhan', ['owner' => $owner], function ($message) use ($traveller, $owner) {
                        $message->to($traveller->email, $traveller->fullname)->subject('Selamat Menyambut Ramadhan Al-Mubarak');
                        $message->from($owner->email, $owner->name);
                    });
                }
                
            }
        }
    }
}
