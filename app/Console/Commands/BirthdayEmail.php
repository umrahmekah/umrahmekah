<?php

namespace App\Console\Commands;

use App\Models\Owners;
use App\Models\Travellers;
use Carbon;
use Illuminate\Console\Command;
use Mail;
use Illuminate\Support\Facades\Log;

class BirthdayEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday email for travellers';

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
            $travellers = Travellers::where('owner_id', $owner->id)->whereRaw('DATE_FORMAT(dateofbirth, ?) = ?', ['%m-%d', Carbon::now()->format('m-d')])->get();
            foreach ($travellers as $key => $traveller) {
                Mail::send('emails.birthday', ['owner' => $owner, 'traveller' => $traveller], function ($message) use ($traveller, $owner) {
                    $message->to($traveller->email, $traveller->fullname)->subject('Selamat Hari Lahir');
                    $message->from($owner->email, $owner->name);
                });
            }
        }
    }
}
