<?php

namespace App\Models;
use App\User;

class travelagents extends Mmb
{
    protected $table      = 'travel_agent';
    protected $primaryKey = 'travelagentID';
    protected $dates = ['created_at', 'updated_at'];

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT travel_agent.* FROM travel_agent  ';
    }

    public static function queryWhere()
    {
        return '  WHERE travel_agent.owner_id = ' . CNF_OWNER . ' AND travel_agent.travelagentID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function getBookingsAttribute()
    {
        $user = User::where('email', $this->email)->where('owner_id', CNF_OWNER)->get()->first();
        $affiliate_bookings = Createbooking::select('bookingsID')->where('owner_id', CNF_OWNER)->whereNotIn('affiliatelink', [''])->where('affiliatelink', $this->affiliatelink)->get();
        $bookingsID = [];

        foreach ($affiliate_bookings as $affiliate) {
            array_push($bookingsID, $affiliate->bookingsID);
        }
        if ($user) {
            $bookings = CreateBooking::where('owner_id', CNF_OWNER)->where('entry_by', $user->id)->orWhereIn('bookingsID', $bookingsID)->get();
        } else {
            $bookings = CreateBooking::where('owner_id', CNF_OWNER)->whereIn('bookingsID', $bookingsID)->get();
        }

        return $bookings;
    }

    public function getTotalSalesAttribute()
    {
        $sales = 0;
        foreach ($this->bookings as $key => $booking) {
            $invoice = $booking->invoice;
            if ($invoice) {
                $sales += $invoice->InvTotal ?? 0;
            }
        }
        return $sales;
    }

    public function getTotalPaymentsAttribute()
    {
        $payments = 0;
        foreach ($this->bookings as $key => $booking) {
            $invoice = $booking->invoice;
            if ($invoice) {
                foreach ($invoice->payments as $key => $payment) {
                    $payments += $payment->amount;
                }
            }
        }
        return $payments;
    }
}
