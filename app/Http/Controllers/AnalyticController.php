<?php

namespace App\Http\Controllers;

use Analytics;
use App\Models\Booktour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Analytics\Period;
use App\Models\Tourdates;
use App\Models\Tours;
use App\Models\Travelagents;
use App\Models\Travellers;
use App\Models\Createbooking;
use App\Models\Bookroom;
use DB, HijriDate, Hijri;

class AnalyticController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data = [
            'pageTitle' => CNF_COMNAME,
            'pageNote'  => 'Welcome to Dashboard',
        ];
    }
    

    public function getIndex(Request $request)
    {   
        //Remove comment to enable dashboard
       /* if ((1 == Auth::User()->group_id || 2 == Auth::User()->group_id || 4 == Auth::User()->group_id || 5 == Auth::User()->group_id || 6 == Auth::User()->group_id)) {
            return redirect('/createbooking');
        } elseif ((3 == Auth::User()->group_id)) {
            return redirect('/invoice');
        } else {
            return redirect('/');
        }

        $checkdepost = booktour::where('entry_by', Auth::user()->id)->where('deposit_paid', 0)->where('owner_id', CNF_OWNER)->count();
        if ($checkdepost > 0) {
            return redirect('pendingdepo');
        } */ 
        
        
        $graph = \DB::table('bookings')
        ->select(\DB::raw('MONTHNAME(created_at) as month'), \DB::raw("DATE_FORMAT(created_At,'%M %Y') as monthNum"), \DB::raw('count(*) as totalbook'))
        ->where('owner_id', '=', CNF_OWNER)
        ->groupBy('monthNum')
        ->orderBy('created_at', 'asc')
        ->get();
        
        $currentHijri = HijriDate::today();

        $hijriYear = $currentHijri->year;

        if ($request->year) {
            $hijriYear = $request->year;
        }

        $startDate = Hijri::convertToGregorian(1, 1, $hijriYear);
        $endDate = Hijri::convertToGregorian(30, 12, $hijriYear);

        $currentHijri = Hijri::convertToHijri($startDate);
       
        $this->data['currentHijri'] = $currentHijri;

        $today     = Carbon::today();
        $lastweek  = Carbon::today()->subDays(7);
        $lastmonth = Carbon::today()->subDays(30);
        $lastyear  = Carbon::today()->subDays(300);
        

        $monthlybookingreport = \DB::table('bookings')->select(\DB::raw('MONTHNAME(created_at) as month'),
                                \DB::raw("DATE_FORMAT(created_At,'%M %Y') as monthNum"),
                                \DB::raw('count(*) as totalbook'))
                                ->where('owner_id', '=', CNF_OWNER)->groupBy('monthNum')->orderBy('created_at', 'asc')->get();

        $bookings = Createbooking::where('owner_id', CNF_OWNER)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->get();

        $tempDate = $startDate->copy()->startOfMonth();

        $months = [];

        while ($tempDate->format('F Y') !== $endDate->format('F Y')) {
            $months[$tempDate->copy()->format('F Y')] = 0;
            $tempDate->addMonth();
        }

        $months[$tempDate->copy()->format('F Y')] = 0;

        foreach ($bookings as $key => $booking) {
            $months[$booking->created_at->format('F Y')]++;
        }

        $this->data['bookingMonths'] = $months;
                                
        $touryear             = Tourdates::where('start', '>=', $startDate)->where('start', '<=', $endDate)->where('owner_id', '=',CNF_OWNER)->get()
                                ->filter(function($query) {
                                    $tour = $query->tour;
                                    if ($tour) {
                                        return $tour->type == 1;
                                    }
                                    return false;
                                });
        
        $male_count = 0;
        $female_count = 0;
        
        $ages = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0,
            10 => 0
        ];

        $source = [
            0 => 0 ,
            1 => 0 ,
            2 => 0 ,
            3 => 0 ,
            4 => 0 ,
            5 => 0 ,
            6 => 0 ,
            7 => 0 ,
            8 => 0 ,
            9 => 0
        ];
        
        foreach($touryear as $bookyear){
            $booktour = $bookyear->booktours;
                if($booktour){
                    foreach($booktour as $bt){
                        $book = $bt->booking;
                        if($book){

                            $source[$book->source_id]++;

                            foreach($book->bookRoom as $bookrooms){
                                $travellers = $bookrooms->travellerList;
                                foreach($travellers as $traveller){
                                    if($traveller->gender=='M'){
                                        $male_count++;
                                    }
                                    elseif($traveller->gender=='F'){
                                        $female_count++;
                                    }
                                    else{}
                                    
                                    if($traveller->age < 2){
                                        $ages[0]++;
                                    }
                                    else if($traveller->age >= 2 && $traveller->age <= 12){
                                        $ages[1]++;
                                    
                                    }
                                    else if($traveller->age >12 && $traveller->age <= 17){
                                        $ages[2]++;
                                    
                                    }
                                    else if($traveller->age >17 && $traveller->age <= 25){
                                        $ages[3]++;
                                    
                                    }
                                    else if($traveller->age >25 && $traveller->age <= 31){
                                        $ages[4]++;
                                    
                                    }
                                    else if($traveller->age > 31 && $traveller->age <= 36){
                                        $ages[5]++;
                                    
                                    }
                                    else if($traveller->age >36 && $traveller->age <= 41){
                                        $ages[6]++;
                                    
                                    }
                                    else if($traveller->age >41 && $traveller->age <= 46){
                                        $ages[7]++;
                                    
                                    }
                                    else if($traveller->age >46 && $traveller->age <= 51){
                                        $ages[8]++;
                                    
                                    }
                                    else if($traveller->age >51 && $traveller->age <= 56){
                                        $ages[9]++;
                                    
                                    }
                                    else if($traveller->age >61){
                                        $ages[10]++;
                                    
                                    }
                                }
                            }
                        }
                    }
                }
        }

        $this->data['source'] = $source;
        $this->data['sourceType'] = Createbooking::SOURCE_TYPE_MAP;

        $this->data['online_users']         = \DB::table('tb_users')->where('owner_id', '=', CNF_OWNER)->orderBy('last_activity', 'desc')->limit(10)->get();
        $this->data['active']               = '';
        $this->data['activeagents']         = \DB::table('travel_agent')->where('owner_id', '=', CNF_OWNER)->where('status', '1')->count('travelagentID');
        $this->data['activeguides']         = \DB::table('guides')->where('owner_id', '=', CNF_OWNER)->where('status', '1')->count('guideID');
        $this->data['activesupplier']       = \DB::table('def_supplier')->where('owner_id', '=', CNF_OWNER)->where('status', '1')->count('supplierID');
        $this->data['activehotels']         = \DB::table('hotels')->where('owner_id', '=', CNF_OWNER)->where('status', '1')->count('hotelID');
        $this->data['totalbookings']        = \DB::table('bookings')->where('owner_id', '=', CNF_OWNER)->count('bookingsID');
        $this->data['todaysbookings']       = \DB::table('bookings')->where('owner_id', '=', CNF_OWNER)->where('created_at', '>', $today)->count('bookingsID');
        $this->data['lastweeksbookings']    = \DB::table('bookings')->where('owner_id', '=', CNF_OWNER)->where('created_at', '>', $lastweek)->count('bookingsID');
        $this->data['lastmonthssbookings']  = \DB::table('bookings')->where('owner_id', '=', CNF_OWNER)->where('created_at', '>', $lastmonth)->count('bookingsID');
        $this->data['running_tours']        = \DB::table('tour_date')->where('owner_id', '=', CNF_OWNER)->where('start', '<=', $today)->where('end', '>', $today)->where('status', 1)->count('tourID');
        $this->data['upcoming_tours']       = \DB::table('tour_date')->where('owner_id', '=', CNF_OWNER)->where('start', '>', $today)->where('status', 1)->count('tourID');
        $this->data['old_tours']            = \DB::table('tour_date')->where('owner_id', '=', CNF_OWNER)->where('start', '<', $today)->where('end', '<', $today)->where('status', 1)->count('tourID');
        $this->data['monthlybookingreport'] = $monthlybookingreport;
        $this->data['graph']                = $graph;
        $this->data['touryear']             = $touryear;
        $this->data['male_count']           = $male_count;
        $this->data['female_count']         = $female_count;
        $this->data['ages']                 = $ages; 
        
        
        $analyticsData = null;
        /*try {
            $analyticsData = Analytics::performQuery(
                Period::days(7),
                'ga:sessions',
                [
                    'metrics' => 'ga:sessions, ga:goal1ConversionRate, ga:goal2ConversionRate, ga:goal3ConversionRate'
                ]
            );
        }
        catch (Exception $e){}
        */

        if (isset($analyticsData)) {
            $this->data['analyticsData'] = $analyticsData->rows[0];
        } else {
            $this->data['analyticsData'] = null;
        }
        
        
      // dd($this->data['$male_count']   );
    

        return view('analytic.index', $this->data);
    }
    
    
    public function getDashboard()
    {
    }
    
}
