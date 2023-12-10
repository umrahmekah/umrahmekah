<?php

namespace App\Http\Controllers;

use App\Models\booktour;
use App\Models\createbooking;
use App\Models\Travellers;
//use Illuminate\Contracts\Logging\Log;
use Cyvelnet\LaravelBillplz\Facades\Billplz;
use Cyvelnet\LaravelBillplz\Messages\BillMessage;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Redirect;

class PendingDepositController extends Controller
{
    public $module          = 'travellers';
    public static $per_page = '10000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Travellers();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'travellers',
            'return'     => self::returnUrl(),
        ];

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
    }

    public function getIndex()
    {
        $model = new booktour();
        $info  = $model::makeInfo('book_tour');
        $data  = [
            'pageTitle'    => \Lang::get('core.unpaiddeposit'),
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];

        $listpending = DB::table('book_tour')->select('tours.tour_name', 'tours.tourimage', 'bookings.bookingno', 'bookings.totaltravellers', 'bookings.created_at', 'bookings.balance', 'tour_date.cost_deposit', 'tour_date.tour_code', 'tour_date.start', 'tour_date.end')
            ->join('tours', 'tours.tourID', '=', 'book_tour.tourID')
            ->join('bookings', 'book_tour.bookingID', '=', 'bookings.bookingsID')
            ->join('tour_date', 'tour_date.tourdateID', '=', 'book_tour.tourdateID')
            ->where('book_tour.entry_by', Auth::user()->id)
            ->where('book_tour.deposit_paid', 0)
            ->where('book_tour.owner_id', CNF_OWNER)
            ->get();

        //dd($listpending);
        $data['listpending'] = $listpending;

        return view('pendingdepo.index', $data);
    }

    public function postData(Request $request)
    {
    }

    public function getUpdate(Request $request, $id = null)
    {
    }

    public function getShow(Request $request, $id = null)
    {
        $model = new booktour();
        $info  = $model::makeInfo('booktour');
        $data  = [
            'pageTitle'    => \Lang::get('core.detailunpaid'),
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];

        $booking               = createbooking::where('bookingno', $request->id)->first();
        $data['bookingno']     = $booking->bookingno;
        $data['bookingdetail'] = DB::table('bookings')->where('bookingno', $request->id)->first();
        $data['tourdetail']    = booktour::select(DB::raw('book_tour.*,tours.*,tour_date.cost_deposit,def_tour_categories.tourcategoryname,tour_date.start,tour_date.end'))
            ->join('tours', 'tours.tourID', '=', 'book_tour.tourID')
            ->join('tour_date', 'tour_date.tourID', '=', 'book_tour.tourID')
            ->join('def_tour_categories', 'def_tour_categories.tourcategoriesID', '=', 'tours.tourcategoriesID')
            ->where('book_tour.bookingID', $booking->bookingsID)
            ->first();
        $data['roomdetail']          = DB::table('book_room')->where('bookingID', $booking->bookingsID)->get();
        $data['travellerdetail']     = travellers::whereIN('travellerID', array_filter(explode(',', $booking->new_traveler)))->get();
        $data['totalpendingdeposit'] = $data['bookingdetail']->totaltravellers * $data['tourdetail']->cost_deposit;

        //dd($data['travellerdetail']);

        return view('pendingdepo.view', $data);
    }

    public function postCopy(Request $request)
    {
    }

    public function postSave(Request $request, $id = 0)
    {
    }

    public function postDelete(Request $request)
    {
        Log::info($request->id);
        //delete travelers booking info on multiple table
        $travellers = createbooking::where('bookingno', $request->id)->first();
        //dd($travellers->bookingsID);
        $bookingdelete = DB::table('bookings')->where('bookingno', $request->id)->delete();
        $tourdelete    = DB::table('book_tour')->where('bookingID', $travellers->bookingsID)->delete();
        $roomdelete    = DB::table('book_room')->where('bookingID', $travellers->bookingsID)->delete();
        if (null != $travellers->new_traveler) {
            $new_traveler = explode(',', $travellers->new_traveler);
            unset($new_traveler[0]);
            Travellers::destroy($new_traveler);
//            $traveldelete = travellers::whereIN('travellerID',$travellers->new_traveler)->delete();
        }

        return response()->json([
            'url' => url('/dashboard'),
        ]);
    }

    public static function display()
    {
    }

    public function postSavepublic(Request $request)
    {
    }

    public function payDeposit(Request $request)
    {
        $booking = createbooking::select(DB::raw('bookings.bookingsID,bookings.totaltravellers,bookings.travellerID,tour_date.cost_deposit'))
            ->join('book_tour', 'book_tour.bookingID', '=', 'bookings.bookingsID')
            ->join('tour_date', 'tour_date.tourID', '=', 'book_tour.tourID')
            ->where('bookings.bookingno', $request->id)
            ->first();
        $travellers = travellers::where('travellerID', $booking->travellerID)->first();

        $resource = Billplz::issue(function (BillMessage $bill) use ($booking,$travellers,$request) {
            $bill->to($travellers->nameandsurname, $travellers->email)
                ->amount($booking->cost_deposit * $booking->totaltravellers) // will multiply with 100 automatically, so a RM500 bill, you just pass 500 instead of 50000
                ->callbackUrl('https://' . CNF_DOMAIN)
                ->redirectUrl('https://' . CNF_DOMAIN . '/bookpackage/paid')
                ->description('Deposit Payment')
                ->reference1($request->id);
        });
        $array = $resource->toArray();

        return redirect($array['url']);
    }
}
