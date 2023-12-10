<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Tours;
use App\Models\Invoice;
use App\Models\Payments;
use App\Models\Booktour;
use App\Models\Bookroom;
use App\Models\Tourdates;
use App\Models\Travellers;
use Illuminate\Http\Request;
use App\Models\Createbooking;
use App\Models\Tourcategories;
use App\Models\BookingChecklist;
use Illuminate\Support\Facades\Hash;
use DB, App, PDF, Auth, Carbon, Redirect, Validator, Mail;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class CreatebookingController extends Controller
{
    public $module          = 'createbooking';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Createbooking();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'createbooking',
            'return'     => self::returnUrl(),
        ];

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
    }

    public function getIndex(Request $request)
    {
        $tourdatefilter = session()->get('tourdatefilter');

        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'bookingsID');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'desc');
        // End Filter sort and order for query
        // Filter Search for query
        $filter = '';
        if (! is_null($request->input('search'))) {
            $search                   = $this->buildSearch('maps');
            $filter                   = $search['param'];
            $this->data['search_map'] = $search['maps'];
        }

        $page   = $request->input('page', 1);
        $params = [
            'page'   => $page,
            'limit'  => (! is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : static::$per_page),
            'sort'   => $sort,
            'order'  => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
        ];
        // Get Query
        $results = $this->model->getRows($params);

        $user_list = User::all();

        for ($i = 0; $i < sizeof($results['rows']); ++$i) {
            $agent = $user_list->find($results['rows'][$i]->entry_by);
            if (! empty($agent) && in_array($agent->group_id, [1, 5])) {
                $results['rows'][$i]->agent = ucwords($agent->first_name);
            } elseif (! empty($agent) && in_array($agent->group_id, [2, 4])) {
                $results['rows'][$i]->agent = 'Staff ' . ucwords($agent->first_name);
            } else {
                $results['rows'][$i]->agent = 'Online Booking';
            }
        }

        $anotherrow = [];

        foreach ($results['rows'] as $key => $row) {
            $tempbook = Createbooking::find($row->bookingsID);
            $results['rows'][$key]->settled = Createbooking::find($row->bookingsID)->settled;
            $results['rows'][$key]->traveller = Travellers::where('owner_id', CNF_OWNER)->where('travellerID', $row->travellerID)->get()->first();
            $results['rows'][$key]->invoice = Createbooking::find($row->bookingsID)->invoice;

            $temprow = $results['rows'][$key];

            if ($tourdatefilter) {
                if ($tempbook->bookTour && $tempbook->bookTour->tourdate && $tempbook->bookTour->tourdate->tourdateID == $tourdatefilter) {
                    $anotherrow[] = $temprow;
                }
            } else {
                $anotherrow[] = $temprow;
            }
        }

        $this->data['tourdates'] = Tourdates::where('owner_id', CNF_OWNER)->where('start', '>=', Carbon::today())->where('type', 1)->where('status', 1)->get();

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($anotherrow, count($anotherrow), $params['limit']);
        $pagination->setPath('createbooking');

        // dd($results['rows']);

        $this->data['rowData'] = $anotherrow;
        // Build Pagination
        $this->data['pagination'] = $pagination;
        // Build pager number and append current param GET
        $this->data['pager'] = $this->injectPaginate();
        // Row grid Number
        $this->data['i'] = ($page * $params['limit']) - $params['limit'];
        // Grid Configuration
        $this->data['tableGrid'] = $this->info['config']['grid'];
        $this->data['tableForm'] = $this->info['config']['forms'];
        $this->data['colspan']   = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['grid']);
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template
        return view('createbooking.index', $this->data);
    }

    public function getUpdate(Request $request, $id = null)
    {
        if ('' == $id) {
            if (0 == $this->access['is_add']) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        if ('' != $id) {
            if (0 == $this->access['is_edit']) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        $this->data['sources'] = Createbooking::SOURCE_TYPE_MAP;

        $row = $this->model->retrive($id);
        if ($row) {
            $this->data['row'] = $row;
            $this->data['booktour'] = Createbooking::find($id)->bookTour;
        } else {
            if ('' == $id) {
                $this->data['row'] = $this->model->getColumnTable('bookings');
            } else {
                return Redirect::to('createbooking')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        $this->data['tourcategories'] = Tourcategories::where('owner_id', CNF_OWNER)->where('type', 1)->where('status', 1)->get();

        return view('createbooking.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row']      = Createbooking::find($id);
            if(!$this->data['row']->bookTour){
                //if there's not booktour, redirect to update
                return redirect('createbooking/update/'.$id);
            }
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext'] = $this->model->prevNext($id);

            $tours = \DB::table('book_tour')->where('bookingID', $id)->orderBy('status', 'desc')->get();
            $trs   = [];
            $first = 0;
            foreach ($tours as $ts) {
                $trs[] = [
                'booktourID'       => $ts->booktourID,
                'bookingID'        => $ts->bookingID,
                'tourcategoriesID' => $ts->tourcategoriesID,
                'tourID'           => $ts->tourID,
                'tourdateID'       => $ts->tourdateID,
                'updated_at'       => $ts->updated_at,
                'created_at'       => $ts->created_at,
                'entry_by'         => $ts->entry_by,
                'status'           => $ts->status,
            ];
                ++$first;
            }
            $this->data['trs'] = $trs;

            $rooms  = \DB::table('book_room')->where('bookingID', $id)->orderBy('status', 'desc')->get();
            $rms    = [];
            $trvs  = [];
            $second = 0;
            foreach ($rooms as $rs) {
                $trvs = array_merge($trvs, explode(',', $rs->travellers));
                $rms[] = [
                'roomID'     => $rs->roomID,
                'bookingID'  => $rs->bookingID,
                'roomtype'   => $rs->roomtype,
                'travellers' => $rs->travellers,
                'updated_at' => $rs->updated_at,
                'created_at' => $rs->created_at,
                'entry_by'   => $rs->entry_by,
                'status'     => $rs->status,
            ];
                ++$second;
            }
            $this->data['rms'] = $rms;

            $hotels = \DB::table('book_hotel')->where('bookingID', $id)->orderBy('status', 'desc')->get();
            $hotel  = [];
            $third  = 0;
            foreach ($hotels as $hot) {
                $hotel[] = [
                'bookhotelID' => $hot->bookhotelID,
                'bookingID'   => $hot->bookingID,
                'countryID'   => $hot->countryID,
                'cityID'      => $hot->cityID,
                'hotelID'     => $hot->hotelID,
                'checkin'     => $hot->checkin,
                'checkout'    => $hot->checkout,
                'updated_at'  => $hot->updated_at,
                'created_at'  => $hot->created_at,
                'status'      => $hot->status,
            ];
                ++$third;
            }
            $this->data['hotel'] = $hotel;

            $flights = \DB::table('book_flight')->where('bookingID', $id)->orderBy('status', 'desc')->get();
            $flight  = [];
            $fourth  = 0;
            foreach ($flights as $fl) {
                $flight[] = [
                'bookflightID' => $fl->bookflightID,
                'bookingID'    => $fl->bookingID,
                'travellersID' => $fl->travellersID,
                'airlineID'    => $fl->airlineID,
                'class'        => $fl->class,
                'return'       => $fl->return,
                'depairportID' => $fl->depairportID,
                'arrairportID' => $fl->arrairportID,
                'departing'    => $fl->departing,
                'returning'    => $fl->returning,
                'updated_at'   => $fl->updated_at,
                'created_at'   => $fl->created_at,
                'status'       => $fl->status,
            ];
                ++$fourth;
            }
            $this->data['flight'] = $flight;

            $cars  = \DB::table('book_car')->where('bookingID', $id)->orderBy('status', 'desc')->get();
            $car   = [];
            $fifth = 0;
            foreach ($cars as $cr) {
                $car[] = [
                'bookcarID'  => $cr->bookcarID,
                'bookingID'  => $cr->bookingID,
                'carbrandID' => $cr->carbrandID,
                'carsID'     => $cr->carsID,
                'start'      => $cr->start,
                'end'        => $cr->end,
                'pickup'     => $cr->pickup,
                'dropoff'    => $cr->dropoff,
                'updated_at' => $cr->updated_at,
                'created_at' => $cr->created_at,
                'status'     => $cr->status,
            ];
                ++$fifth;
            }
            $this->data['car'] = $car;

            $extras = \DB::table('book_extra')->where('bookingID', $id)->orderBy('status', 'desc')->get();
            $extra  = [];
            $sixth  = 0;
            foreach ($extras as $ex) {
                $extra[] = [
                'bookextraID'    => $ex->bookextraID,
                'bookingID'      => $ex->bookingID,
                'extraserviceID' => $ex->extraserviceID,
                'updated_at'     => $ex->updated_at,
                'created_at'     => $ex->created_at,
                'status'         => $ex->status,
            ];
                ++$sixth;
            }
            $this->data['extra'] = $extra;

            if (! is_null($request->input('pdf'))) {
                $pdf  = App::make('dompdf.wrapper');
                $html = view('createbooking.pdf', $this->data)->render();
                $pdf  = PDF::setOptions(['dpi' => 100, 'defaultFont' => 'sans-serif']);
                $pdf->loadHTML($html)->setPaper('A4', 'portrait');
                $pdf = PDF::loadView('createbooking.bookingpdf', $this->data);

                // return view('createbooking.bookingpdf', $this->data);
                return $pdf->stream($this->data['row']->bookingno.'.pdf');
            }

            $invoice = Invoice::where('bookingID', $id)->get()->first();

            if (!$invoice) {
                DB::beginTransaction();
                $invoice = $this->autoCreateNewInvoice($this->data['row']);
                DB::commit();
                $invoice = Invoice::find($invoice->invoiceID);
            }

            if ($invoice) {
                $payments = Payments::where('invoiceID', $invoice->invoiceID)->get();

                $paid        = 0;
                $balance_due = $invoice->InvTotal;

                foreach ($payments as $payment) {
                    $paid += $payment->amount;
                }

                $balance_due -= $paid;

                $this->data['paid']        = $paid;
                $this->data['balance_due'] = $balance_due;

                $date = $invoice->DueDate;

                $this->data['due'] = date('d-m-Y', strtotime($date));
            }

            $this->data['invoice'] = $invoice;

            $trvs = Travellers::whereIn('travellerID', $trvs)->where('owner_id', CNF_OWNER)->get();

            $this->data['trvs'] = $trvs;

            $this->data['travellers'] = Travellers::where('owner_id', CNF_OWNER)->get();
            $this->data['children'] = Travellers::where('owner_id', CNF_OWNER)->get()->filter( function ($query) {
                $age = Carbon::parse($query->dateofbirth)->age;
                return $age >= 2 && $age <= 12;
            });
            $this->data['infants'] = Travellers::where('owner_id', CNF_OWNER)->get()->filter( function ($query) {
                $age = Carbon::parse($query->dateofbirth)->age;
                return $age < 2;
            });

            $this->data['main'] = Travellers::where('owner_id', CNF_OWNER)->where('travellerID', $row->travellerID)->get()->first();

            $this->data['checklist']= $this->data['row']->checklist;

            if (!$this->data['checklist']) {
                $this->data['checklist'] = new BookingChecklist;
                $this->data['checklist']->booking_id = $this->data['row']->bookingsID;
                $this->data['checklist']->owner_id = CNF_OWNER;
                $this->data['checklist']->save();
            }

            return view('createbooking.view', $this->data);
        } else {
            return Redirect::to('createbooking')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM bookings ') as $column) {
            if ('bookingsID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO bookings (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM bookings WHERE bookingsID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return Redirect::to('createbooking')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('createbooking')->with('messagetext', \Lang::get('core.note_selectrow'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $user = Auth::user();
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_createbooking');

            DB::beginTransaction();

            $id = $this->model->insertRow($data, $request->input('bookingsID'));

            $booking = Createbooking::find($id);
            $booking->source_id = $request->source_id;
            $booking->save();

            $booktour = $booking->bookTour;

            if(!$booktour){
                $booktour = new Booktour;
                $booktour->bookingID = $id;
                $booktour->entry_by = $user->id;
                $booktour->owner_id = CNF_OWNER;
            }

            
            $booktour->tourcategoriesID = $request->tourcategoriesID;
            $booktour->tourID = $request->tourID;
            $booktour->tourdateID = $request->tourdateID;
            $booktour->status = $request->status;
            $booktour->save();

            $invoice = $booking->invoice;

            if (!$invoice) {
                $invoice = $this->autoCreateNewInvoice($booking);
            }else{
                foreach ($booking->bookRoom as $key => $room) {
                    $this->adjustInvoice($room, 1);
                    $this->adjustInvoice($room, 2);
                }
            }

            if (! is_null($request->input('apply'))) {
                $return = 'createbooking/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'createbooking/show/' . $id . '?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('bookingsID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            $traveller = $booking->traveller;

            if ($traveller->email) {
                if ($traveller->user) {
                    Mail::send('createbooking.newbookingmail', ['booking' => $booking, 'booktour' => $booktour], function ($message) use ($traveller) {
                        $message->to($traveller->email, $traveller->fullname)->subject('Maklumat Tempahan');
                        $message->from(CNF_EMAIL, CNF_COMNAME);
                    });
                }else{
                    $user = new User;
                    $last_id = DB::table('tb_users')->select('id')->orderby('id', 'desc')->first();
                    $id     = $last_id->id + 1;
                    $string = 'umrah';
                    $phone  = substr($traveller->phone, 6);
                    $code             = rand(10000, 10000000);
                    $user             = new User();
                    $user->username   = "{$string}{$id}{$phone}";
                    $user->password   = Hash::make('password');
                    $user->first_name = $traveller->nameandsurname;
                    $user->last_name  = $traveller->last_name;
                    $user->email      = $traveller->email;
                    $user->activation = $code;
                    if (CNF_ACTIVATION == 'auto') {
                        $user->active = '1';
                    } else {
                        $user->active = '0';
                    }
                    $user->group_id = 6;
                    $user->owner_id = CNF_OWNER;
                    $user->save();
                    $password   = 'password';
                    $email_data = ['username' => $user->email, 'name' => $user->first_name.' '.$user->last_name, 'password' => $password, 'activate' => $user->activation];
                   Mail::send('createbooking.newusermail', ['email_data' => $email_data, 'booking' => $booking, 'booktour' => $booktour], function ($message) use ($user) {
                        $message->to($user->email, $user->first_name.' '.$user->last_name)->subject('Maklumat Akaun Pengguna');
                        $message->from(CNF_EMAIL, CNF_COMNAME);
                    });
                }
            }

            DB::commit();

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('createbooking/update/' . $request->input('bookingsID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postDelete(Request $request)
    {
        if (0 == $this->access['is_remove']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $invoices = Invoice::where('owner_id', CNF_OWNER)->whereIn('bookingID', $request->input('ids'));
            foreach ($invoices as $key => $invoice) {
                foreach ($invoice->products as $key => $product) {
                    $product->delete();
                }
                $invoice->delete();
            }
            $this->model->destroy($request->input('ids'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfully');
            // redirect
            return Redirect::to('createbooking')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('createbooking')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Createbooking();
        $info  = $model::makeInfo('createbooking');

        $data = [
            'pageTitle' => $info['title'],
            'pageNote'  => $info['note'],
        ];

        if ('view' == $mode) {
            $id  = $_GET['view'];
            $row = $model::getRow($id);
            if ($row) {
                $data['row']    = $row;
                $data['fields'] = \SiteHelpers::fieldLang($info['config']['grid']);
                $data['id']     = $id;

                return view('createbooking.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'bookingsID',
                'order'  => 'asc',
                'params' => '',
                'global' => 1,
            ];

            $result            = $model::getRows($params);
            $data['tableGrid'] = $info['config']['grid'];
            $data['rowData']   = $result['rows'];

            $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
            $pagination = new Paginator($result['rows'], $result['total'], $params['limit']);
            $pagination->setPath('');
            $data['i']          = ($page * $params['limit']) - $params['limit'];
            $data['pagination'] = $pagination;

            return view('createbooking.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('bookings');
            $this->model->insertRow($data, $request->input('bookingsID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postDepositletter(Request $request)
    {
        $booking = Createbooking::find($request->booking_id);
        $traveller = Travellers::find($request->traveller_id);

        

        $tourdate = $booking->bookTour->tourdate;
        
        $flight_booking = $tourdate->flight->where('status', 2)->first();

        if ($flight_booking) {
        }else{
            $flight_booking = $tourdate->flight->where('status', 1)->first();
        }
        
        $flight = false;

        if ($flight_booking) {
            $flight = true;
        }

        $data = [
            'booking' => $booking,
            'traveller' => $traveller,
            'flight' => $flight,
            'flight_booking' => $flight_booking
        ];

        $pdf = PDF::loadView('createbooking.depositletter', $data);
        return $pdf->stream('DepositLetter.pdf');
    }

    public function postTourlist(Request $request)
    {
        $tours = Tours::where('owner_id', CNF_OWNER)
            ->where('status', 1)
            ->where('type', 1)
            ->where('tourcategoriesID', $request->tourcategoriesID)
            ->get();

        return $tours;
    }

    public function postTourdatelist(Request $request)
    {
        $tourdates = Tourdates::where('owner_id', CNF_OWNER)
            ->where('status', 1)
            ->where('type', 1)
            ->where('tourID', $request->tourID)
            ->get()->filter(function($query) {
                return $query->capacity > 0;
            });

        return $tourdates;
    }

    public function postAddroom(Request $request)
    {
        $user = Auth::user();
        $booking = Createbooking::find($request->booking_id);
        if (isset($request->roomID)) {
            DB::beginTransaction();
            $room = Bookroom::where('owner_id', CNF_OWNER)->where('roomID', $request->roomID)->get()->first();
            if (!$room) {
                abort(404);
            }
            $this->adjustInvoice($room, 2);
        }else{
            $adt = count($request->travellers);
            if ($request->child_check == 'on') {
                $adt += count($request->children);
            }
            if ($request->infant_check == 'on') {
                $adt += count($request->infants);
            }
            if ($booking->bookTour->tourdate->capacity < $adt) {
                return Redirect::to('createbooking/show/'.$request->booking_id)->with('messagetext', 'There\'s not enough capacity')->with('msgstatus', 'error');
            }
            DB::beginTransaction();
            $room = new Bookroom;
        }
        
        $room->bookingID = $request->booking_id;
        $room->roomtype = $request->roomtype;
        $room->travellers = implode(',', $request->travellers);
        $room->entry_by = $user->id;
        $room->status = 1;
        $room->owner_id = CNF_OWNER;
        $room->save();

        $this->adjustInvoice($room, 1);

        foreach ($room->childRoom as $childroom) {
            $this->adjustInvoice($childroom, 2);
            $childroom->delete();
        }

        if ($request->child_check == 'on') {

            $childData = [
                'bookingID' => $room->bookingID,
                'roomtype' => $request->child_room,
                'travellers' => implode(',', $request->children),
                'entry_by' => $room->entry_by,
                'remarks' => $room->remarks,
                'owner_id' => $room->owner_id,
                'parent_id' => $room->roomID,
                'status' => $room->status
            ];
            $roomId = DB::table('book_room')->insertGetId($childData);
            $childroom = Bookroom::find($roomId);
            $this->adjustInvoice($childroom, 1);
        }

        if ($request->infant_check == 'on') {

            $infantData = [
                'bookingID' => $room->bookingID,
                'roomtype' => 9,
                'travellers' => implode(',', $request->infants),
                'entry_by' => $room->entry_by,
                'remarks' => $room->remarks,
                'owner_id' => $room->owner_id,
                'parent_id' => $room->roomID,
                'status' => $room->status
            ];
            $roomId = DB::table('book_room')->insertGetId($infantData);
            $childroom = Bookroom::find($roomId);
            $this->adjustInvoice($childroom, 1);
        }

        DB::commit();

        return redirect('/createbooking/show/'.$room->bookingID);
    }

    public function postDeleteroom(Request $request, $id)
    {
        $room = Bookroom::where('owner_id', CNF_OWNER)->where('roomID', $id)->get()->first();
        if (!$room) {
            abort(404);
        }

        $bookingID = $room->bookingID;

        foreach ($room->childRoom as $childroom) {
            $this->adjustInvoice($childroom, 2);
            $childroom->delete();
        }

        $this->adjustInvoice($room, 2);
        $room->delete();

        return redirect('/createbooking/show/'.$bookingID);
    }

    public function adjustInvoice(Bookroom $room, $type)
    {
        //type 1 add room, type 2 delete room
        $booking = $room->booking;
        $invoice = $booking->invoice;
        $booktour = $booking->bookTour;
        $tourdate = $booktour->tourdate;
        $pax = count(explode(',',$room->travellers));

        switch ($room->roomtype) {
            case 1:
                $price = $tourdate->cost_single ?? 0;
                break;
            case 2:
                $price = $tourdate->cost_double ?? 0;
                break;
            case 3:
                $price = $tourdate->cost_triple ?? 0;
                break;
            case 4:
                $price = $tourdate->cost_quad ?? 0;
                break;
            case 5:
                $price = $tourdate->cost_quint ?? 0;
                break;
            case 6:
                $price = $tourdate->cost_sext ?? 0;
                break;
            case 7:
                $price = $tourdate->cost_child ?? 0;
                break;
            case 8:
                $price = $tourdate->cost_child_wo_bed ?? 0;
                break;
            case 9:
                $price = $tourdate->cost_infant_wo_bed ?? 0;
                break;
            
            default:
                $price = 0;
                break;
        }

        switch ($type) {
            case 1:
                $invData = [
                    'InvID' => $invoice->invoiceID,
                    'Code' => $tourdate->tour_code,
                    'Items' => $tourdate->tour->tour_name.' ('.$room->roomTypeName.')',
                    'Qty' => $pax,
                    'Amount' => $price,
                    'owner_id' => CNF_OWNER
                ];

                DB::table('invoice_products')->insert($invData);

                $invoice->Subtotal += ($price*$pax);
                $invoice->InvTotal = $invoice->Subtotal;
                if ($invoice->discount) {
                    $invoice->InvTotal -= $invoice->discount*$booking->pax;
                }

                $invoice->save();
                break;
            case 2:
                $product = $invoice->products->filter( function ($query) use ($price, $pax) {
                    return $query->Amount == $price && $query->Qty == $pax;
                })->first();

                if ($product) {
                    $product->delete();

                    $invoice->Subtotal -= ($price*$pax);
                    $invoice->InvTotal = $invoice->Subtotal;
                    if ($invoice->discount) {
                        $invoice->InvTotal -= $invoice->discount*$booking->pax;
                    }
                    $invoice->save();
                }
                break;
            
            default:
                # code...
                break;
        }
    }

    public function autoCreateNewInvoice(Createbooking $booking)
    {
        $user = Auth::user();

        $invoice = new Invoice;
        $invoice->travellerID = $booking->travellerID;
        $invoice->bookingID = $booking->bookingsID;
        $invoice->InvTotal = 0;
        $invoice->Subtotal = 0;
        $invoice->currency = 125;
        $invoice->payment_type = 1;
        $invoice->notes = 'Auto Create';
        $invoice->DateIssued = Carbon::today();
        $invoice->DueDate = Carbon::parse($booking->booktour->tourdate->start)->subDays(30);
        $invoice->entry_by = $user->id;
        $invoice->owner_id = CNF_OWNER;
        
        if($booking->booktour->tourdate->discount)
        {
            $invoice->discount = $booking->booktour->tourdate->discount;
        }
        
        $invoice->save();

        foreach ($booking->bookRoom as $key => $room) {
            $this->adjustInvoice($room, 1);
        }

        return $invoice;
    }

    public function getDismiss($id)
    {
        $booking = Createbooking::where('owner_id', CNF_OWNER)->where('bookingsID', $id)->get()->first();

        if (!$booking) {
            return redirect()->back()->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }

        DB::beginTransaction();

        $booking->dismissed = 1;
        $booking->save();

        DB::commit();

        return redirect()->back();
    }

    public function postSetdiscount(Request $request)
    {
        $user = Auth::user();
        $booking = Createbooking::where('owner_id', CNF_OWNER)->where('bookingsID', $request->booking_id)->get()->first();

        if (!$booking) {
            abort(404);
        }

        $discountTotal = $request->discount * $booking->pax;

        $invoice = $booking->invoice;

        $invoice->discount = $request->discount;
        $invoice->InvTotal -= $discountTotal;
        $invoice->discount_by = $user->id;
        $invoice->discount_at = Carbon::now();
        $invoice->save();

        return redirect('createbooking/show/'.$request->booking_id);
    }
    
    public function postChecklist(Request $request, $id)
    {
        // return 'true';
        $booking = Createbooking::where('owner_id', CNF_OWNER)->where('bookingsID', $id)->get()->first();

        if (!$booking) {
            return 'false';
        }

        $column = $request->column;
        $checklist = $booking->checklist;
        $checklist->$column = $request->type;
        $checklist->save();

        return 'true';
    }
}
