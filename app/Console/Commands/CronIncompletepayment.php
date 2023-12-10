<?php

namespace App\Console\Commands;

use App\Models\Createbooking;
use App\Models\Invoice;
use App\Models\Owners;
use App\Models\Travellers;
use Carbon;
use Illuminate\Console\Command;
use Mail;

class CronIncompletepayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:incomplete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email if the payment for booking is not complete';

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
        $bookings = Createbooking::where('balance', '>', 0)->get();

        foreach ($bookings as $booking) {
            $owner     = Owners::find($booking->owner_id);
            $traveller = Travellers::find($booking->travellerID);
            $invoice   = Invoice::where('bookingID', $booking->bookingsID)->get()->first();
            if (!$invoice) {
                continue;
            }

            $data['owner']     = $owner;
            $data['booking']   = $booking;
            $data['traveller'] = $traveller;
            $data['invoice']   = $invoice;

            $today   = Carbon::today();
            $arr     = explode('-', $invoice->DueDate);
            $duedate = Carbon::createFromDate($arr[0], $arr[1], $arr[2]);
            $days    = $today->diffInDays($duedate);

            if (60 == $days || 45 == $days || 30 == $days) {
                Mail::send('invoice.incompletepaymentmail', $data, function ($message) use ($traveller) {
                    $message->to($traveller->email, $traveller->nameandsurname)->subject('Incomplete payment.');
                    $message->from('salam@oomrah.com', 'Oomrah');
                });
            }
        }
    }
}
