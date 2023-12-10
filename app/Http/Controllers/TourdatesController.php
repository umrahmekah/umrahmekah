<?php

namespace App\Http\Controllers;

use App\Models\Booktour;
use App\Models\Bookroom;
use App\Models\Tourcategories;
use App\Models\Tourdates;
use App\Models\Tours;
use App\User;
use App\Models\Tasks;
use App\Models\Travellers;
use App\Models\Createbooking;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\RoomArrangement;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use SiteHelpers;
use Validator;
use Carbon;
use DB;
use Auth;
use PDF;
use Excel;

class TourdatesController extends Controller
{
    public $module          = 'tourdates';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Tourdates();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'tourdates',
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
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'tourdateID');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
        // End Filter sort and order for query
        // Filter Search for query
        $filter = '';
        if (! is_null($request->input('search'))) {
            $search                   = $this->buildSearch('maps');
            $filter                   = $search['param'];
            $this->data['search_map'] = $search['maps'];
        }

        $today         = date('Y-m-d');
        $running_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('start', '<=', $today)
                ->where('end', '>=', $today)
                ->where('status', 1)
                ->where('type', 1)
                ->count();
        $upcoming_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('start', '>', $today)
                ->where('status', 1)
                ->where('type', 1)
                ->count();
        $old_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('end', '<', $today)
                ->where('status', 1)
                ->where('type', 1)
                ->count();
        $cancelled_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('status', 2)
                ->where('type', 1)
                ->count();

        // dd($running_tours);

        $page   = $request->input('page', 1);
        $params = [
            'page'   => $page,
            'limit'  => (! is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : static::$per_page),
            'sort'   => $sort,
            'order'  => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
        ];
        $results = $this->model->getRows($params);

        $paid = [];

        foreach ($results['rows'] as $row) {
            $tourbooks = Booktour::where('tourdateID', $row->tourdateID)->get();
            $row->tourdate = Tourdates::find($row->tourdateID);

            $num     = 0;
            $paidnum = 0;

            foreach ($tourbooks as $tourbook) {
                ++$num;
                if (1 == $tourbook->fullpaid) {
                    ++$paidnum;
                }
            }

            array_push($paid, $paidnum . '/' . $num);
        }

        // dd($results['rows']);

        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('tourdates');
        $this->data['running_tours']   = $running_tours;
        $this->data['upcoming_tours']  = $upcoming_tours;
        $this->data['old_tours']       = $old_tours;
        $this->data['cancelled_tours'] = $cancelled_tours;
        $this->data['today']           = $today;

        $this->data['rowData']    = $results['rows'];
        $this->data['paid']       = $paid;
        $this->data['pagination'] = $pagination;
        $this->data['pager']      = $this->injectPaginate();
        $this->data['i']          = ($page * $params['limit']) - $params['limit'];
        $this->data['tableGrid']  = $this->info['config']['grid'];
        $this->data['tableForm']  = $this->info['config']['forms'];
        $this->data['colspan']    = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        $this->data['access']     = $this->access;
        $this->data['fields']     = \AjaxHelpers::fieldLang($this->info['config']['grid']);
        $this->data['subgrid']    = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);

        return view('tourdates.index', $this->data);
    }

    public function getUpdate(Request $request, $id = null)
    {   $tour_id = $request->tour_id;
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

        $row = $this->model->retrive($id);
        if ($row) {
            $this->data['tourdate'] = Tourdates::find($id);
            $this->data['row'] = $row;
        } else {
            if ('' == $id) {
                $this->data['tourdate'] = new Tourdates;
                $this->data['row'] = $this->model->getColumnTable('tour_date');
                foreach ($this->data['row'] as $key => $old) {
                    if (gettype( old($key) ) === 'array') {
                        $this->data['tourdate'][$key] = implode(old($key));
                        $this->data['row'][$key] = implode(old($key));
                    }else{
                         $this->data['tourdate'][$key] = old($key) ?? "";
                        $this->data['row'][$key] = old($key) ?? "";
                    }
                }
            } else {
                return Redirect::to('tourdates')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        
        $tours                    = Tours::where('tourID',$tour_id)->get()->first();
        $this->data['tours']      = $tours;
        $categories               = Tourcategories::where('owner_id', CNF_OWNER)->where('type', 1)->where('status', 1)->get();
        $this->data['categories'] = $categories;
        $this->data['fields']     = \AjaxHelpers::fieldLang($this->info['config']['forms']);
     
        $this->data['tour_id']    = $tour_id;
        $this->data['id'] = $id;
       //dd($this->data['tours'] );

        return view('tourdates.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $tourdate = Tourdates::with(['piform'])->where('tourdateID', $row->tourdateID)->get()->first();
            $this->data['row']      = $row;
            $this->data['tourdate'] = $tourdate;
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext'] = $this->model->prevNext($id);
            $this->data['piform']   = $tourdate->piform;

            $bookinglist = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('book_room.status', '=', 1)
            ->orderBy('roomtype', 'ASC')
            ->get();

            // dd($bookinglist);

            $book_tours = $tourdate->booktours;

            $room_type = [
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0
            ];

            $pax = 0;

            foreach ($book_tours as $booktour) {
                $booking = $booktour->booking;
                if ($booking) {
                    $a_rooms = $booking->bookRoom;
                    foreach ($a_rooms as $a_room) {
                        $room_type[$a_room->roomtype] += 1;
                    }
                    $pax += $booking->pax;
                }
            }

            $this->data['room_type'] = $room_type;

            $total = $pax;
            $this->data['total']       = $total;
            
            $i = 0;
            $this->data['i'] = $i;
            $tasks = Tasks::where('tour_date_id', '=', $id)->get();
            $this->data['tasks'] = $tasks;
            $users = User::get();
            $this->data['users'] = $users;
            //dd($tasks);

            // if (! is_null($request->input('bookinglist'))) {
            //     // $html = view('tourdates.pdfbookinglist', $this->data)->render();
            //     // return \PDF::load($html)->filename('BookingList-'.$id.'.pdf')->show();
            //     return \PDF::loadView('tourdates.pdfbookinglist', $this->data)->stream();
            // }

            if (! is_null($request->input('passportlist'))) {
                // $html = view('tourdates.pdfpassportlist', $this->data)->render();
                // return \PDF::load($html)->filename('PassportList-'.$id.'.pdf')->show();
                return \PDF::loadView('tourdates.pdfpassportlist', $this->data)->setPaper('a4', 'landscape')->stream();
            }

            if (! is_null($request->input('emergencylist'))) {
                // $html = view('tourdates.pdfemergencylist', $this->data)->render();
                // return \PDF::load($html, $size = 'A4', $orientation = 'landscape')->filename('PassportList-'.$id.'.pdf')->show();
                return \PDF::loadView('tourdates.pdfemergencylist', $this->data)->setPaper('a4', 'landscape')->stream();
            }

            return view('tourdates.view', $this->data);
        } else {
            return Redirect::to('tourdates')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM tour_date ') as $column) {
            if ('tourdateID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO tour_date (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM tour_date WHERE tourdateID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return Redirect::to('tourdates')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tourdates')->with('messagetext', \Lang::get('core.note_selectrow'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $temp = Tourdates::where('tour_code', 'LIKE', $request->tour_code)->where('owner_id', CNF_OWNER)->where('tourdateID', '<>', $request->tourdateID)->get()->first();
        if ($temp) {
            return redirect()->back()
                ->with('messagetext', 'The tour code already exist')->with('msgstatus', 'error')->withInput($request->input());
        }

        $rules     = $this->validateForm();
        $temp = ($request->all());
        $tour = Tours::find($temp['tourID']);
        $date = Carbon::parse($temp['start']);
        $date->addDays($tour->total_days);
        $temp['end'] = $date;
        $validator = Validator::make($temp, $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_tourdates');

            $data['end'] = $date;
            $data['cost_deposit'] = $temp['cost_deposit'];
            $data['cost_quint'] = $temp['cost_quint'];
            $data['cost_sext'] = $temp['cost_sext'];
            $data['cost_child_wo_bed'] = $temp['cost_child_wo_bed'];
            $data['cost_infant_wo_bed'] = $temp['cost_infant_wo_bed'];
            $data['discount'] = $temp['discount'];

            $id = $this->model->insertRow($data, $request->input('tourdateID'));

            // $tdate = Tourdates::find($id);

            // // $tdate->cost_deposit = $temp['cost_deposit'];
            // // $tdate->cost_quint = $temp['cost_quint'];
            // // $tdate->cost_sext = $temp['cost_sext'];

            // $tdate->save();

            if (! is_null($request->input('apply'))) {
                $return = 'tourdates/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'tourdates?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('tourdateID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tourdates/update/' . $request->input('tourdateID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postTdDiscount(Request $request, $id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();
        
        $tourdate->discount = $request->discount;
        $tourdate->save();
        
        return redirect()->back();
    }
    
    public function postDelete(Request $request)
    {
        if (0 == $this->access['is_remove']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfully');
            // redirect
            return Redirect::to('tourdates')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tourdates')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Tourdates();
        $info  = $model::makeInfo('tourdates');

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

                return view('tourdates.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'tourdateID',
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

            return view('tourdates.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tour_date');
            $this->model->insertRow($data, $request->input('tourdateID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public static function travelersDetail($traveler = '')
    {
        $travelersDetail = '';
        if ('' != $traveler) {
            $sqltrv = \DB::table('travellers')->whereIn('travellerID', explode(',', $traveler))->get();

            foreach ($sqltrv as $v2) {
                $travelersDetail .= "<div class='col-md-6'><a href='" . url('travellers/show') . '/' . $v2->travellerID . "'>" . $v2->nameandsurname . "</a></div><div class='col-md-2'>" . SiteHelpers::formatLookUp($v2->countryID, 'countryID', '1:def_country:countryID:country_code') . '</div>';
            }
        }

        return $travelersDetail;
    }

    public static function travelersDetailpdf($traveler = '')
    {
        $travelersDetail = '';
        if ('' != $traveler) {
            $sqltrv = \DB::table('travellers')->whereIn('travellerID', explode(',', $traveler))->get();

            foreach ($sqltrv as $v2) {
                $travelersDetail .= "<tr><td style='border:0px;'> " . $v2->nameandsurname . "</td><td style='width:5%;'> " . SiteHelpers::formatLookUp($v2->countryID, 'countryID', '1:def_country:countryID:country_code') . '</td></tr>';
            }
        }

        return $travelersDetail;
    }

    public static function travelersDetailpassport($travelerpass = '')
    {
        $travelersDetailpassport = '';
        if ('' != $travelerpass) {
            $sqltrvpass = \DB::table('travellers')->whereIn('travellerID', explode(',', $travelerpass))->get();

            foreach ($sqltrvpass as $v3) {
                if ($v3->gender === 'M') {
                    $sex = 'Male';
                }elseif ($v3->gender === 'F') {
                    $sex = 'Female';
                }else{
                    $sex = 'Not Found';
                }

                $nationality_country = \DB::table('def_country')->where('countryID', $v3->nationality)->first();

                $n_name = $nationality_country->country_name ?? null;

                $travelersDetailpassport .= "<tr>
                <td style='width:15%'> " . $v3->nameandsurname . "</td>
                <td style='width:15%'> " . $sex . "</td>
                <td style='width:12%'> " . $v3->passportno . "</td>
                <td style='width:15%'> " . $v3->passport_place_made . "</td>
                <td style='width:20%'> " . $n_name . "</td>
                <td style='width:12%'> " . SiteHelpers::TarihFormat($v3->dateofbirth) . "</td>
                <td style='width:12%'> " . SiteHelpers::TarihFormat($v3->passportissue) . "</td>
                <td style='width:12%'> " . SiteHelpers::TarihFormat($v3->passportexpiry) . "</td>
                </tr>";
            }
        }

        return $travelersDetailpassport;
    }

    public static function travelersDetailemergency($traveleremr = '')
    {
        $travelersDetailemergency = '';
        if ('' != $traveleremr) {
            $sqltrvemr = \DB::table('travellers')->whereIn('travellerID', explode(',', $traveleremr))->get();

            foreach ($sqltrvemr as $v4) {
                $travelersDetailemergency .= '<tr>
                <td> ' . $v4->nameandsurname . '</td>
                <td> ' . $v4->emergencycontactname . '</td>
                <td> ' . $v4->emergencycontactemail . '</td>
                <td> ' . $v4->emergencycontanphone . '</td>
                <td> ' . $v4->insurancecompany . '</td>
                <td> ' . $v4->insurancepolicyno . '</td>
                <td> ' . $v4->insurancecompanyphone . '</td>
                <td> ' . $v4->bedconfiguration . '</td>
                <td> ' . $v4->dietaryrequirements . '</td>
                </tr>';
            }
        }

        return $travelersDetailemergency;
    }

    public function postTours(Request $request)
    {
        return Tourcategories::find($request->id)->tours->where('status', 1);
    }

    public function getBookinglist(Request $request, $id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();
        if ($tourdate) {
            $this->data['tourdate'] = $tourdate;
            $this->data['travellers'] = Travellers::where('owner_id', CNF_OWNER)->where('status', 1)->get();
            $travellers = Travellers::where('owner_id', CNF_OWNER)->where('status', 1)->get();
            $this->data['children'] = [];
            $this->data['infants'] = [];
            foreach ($travellers as $traveller) {
                if (Carbon::parse($traveller->dateofbirth)->age <= 12 && Carbon::parse($traveller->dateofbirth)->age >= 2) {
                    array_push($this->data['children'], $traveller);
                }
                if (Carbon::parse($traveller->dateofbirth)->age < 2) {
                    array_push($this->data['infants'], $traveller);
                }
            }

            return view('tourdates.bookinglist.index', $this->data);
        }else{
            return Redirect::to('tourdates')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postBookinglist(Request $request, $id)
    {
        // dd($request->all());
        $user = Auth::user();
        $num = 0;

        while (true) {
            $bookingno1 = substr(str_shuffle(str_repeat("ABCDEFGHJKLMNPQRSTUVWYZ", 2)), 0, 2);
            $bookingno2 = substr(str_shuffle(str_repeat("123456789", 4)), 0, 4);

            $bookingno = $bookingno1.$bookingno2;

            $booking = Createbooking::where('owner_id', CNF_OWNER)->where('bookingno', $bookingno)->get()->first();

            if (!$booking) {
                break;
            }
        }

        DB::beginTransaction();

        $booking = new Createbooking;
        $booking->bookingno = $bookingno;
        $booking->travellerID = $request->primary_contact;
        $booking->tour = 1;
        $booking->type = 1;
        $booking->entry_by = $user->id;
        $booking->owner_id = CNF_OWNER;
        $booking->save();

        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $request->tourdate_id)->get()->first();

        $booktour = new Booktour;
        $booktour->bookingId = $booking->bookingsID;
        $booktour->tourcategoriesID = $tourdate->tour->tourcategoriesID;
        $booktour->tourID = $tourdate->tour->tourID;
        $booktour->tourdateID = $tourdate->tourdateID;
        $booktour->entry_by = $user->id;
        $booktour->owner_id = CNF_OWNER;
        $booktour->save();

        $booking = Createbooking::find($booking->bookingsID);

        $invoice = new Invoice;
        $invoice->travellerID = $booking->travellerID;
        $invoice->bookingID = $booking->bookingsID;
        $invoice->currency = 125;
        $invoice->payment_type = 1;
        $invoice->notes = "Bulk Booking";
        $invoice->DateIssued = Carbon::today();
        $invoice->DueDate = Carbon::parse($tourdate->start)->subDays(30);
        $invoice->entry_by = $user->id;
        $invoice->owner_id = CNF_OWNER;
        $invoice->InvTotal = 0;
        $invoice->Subtotal = $invoice->InvTotal;
        $invoice->save();

        $invoice = Invoice::find($invoice->invoiceID);

        foreach ($request->rooms as $key => $room) {
            $bookroom = new Bookroom;
            $invoice_product = [];

            $bookroom->bookingID = $booking->bookingsID;
            $bookroom->roomtype = $room['type'];
            $bookroom->travellers = implode(',', $room['travellers']);
            $bookroom->entry_by = $user->id;
            $bookroom->owner_id = CNF_OWNER;
            $bookroom->save();

            $count = count($room['travellers']);

            $invoice_product['InvID'] = $invoice->invoiceID;
            $invoice_product['Code'] = $tourdate->tour_code;
            $invoice_product['Items'] = $tourdate->tour->tour_name.' ('.Bookroom::ROOM_TYPE_MAP[$room['type']].')';
            $invoice_product['Qty'] = $count;
            $invoice_product['owner_id'] = CNF_OWNER;

            if ($room['type'] == 1) {
                $invoice->InvTotal += $tourdate->cost_single*$count;
                $booking->balance += $tourdate->cost_single*$count;
                $invoice_product['Amount'] = $tourdate->cost_single;
            }elseif ($room['type'] == 2) {
                $invoice->InvTotal += $tourdate->cost_double*$count;
                $booking->balance += $tourdate->cost_double*$count;
                $invoice_product['Amount'] = $tourdate->cost_double;
            }elseif ($room['type'] == 3) {
                $invoice->InvTotal += $tourdate->cost_triple*$count;
                $booking->balance += $tourdate->cost_triple*$count;
                $invoice_product['Amount'] = $tourdate->cost_triple;
            }elseif ($room['type'] == 4) {
                $invoice->InvTotal += $tourdate->cost_quad*$count;
                $booking->balance += $tourdate->cost_quad*$count;
                $invoice_product['Amount'] = $tourdate->cost_quad;
            }elseif ($room['type'] == 5) {
                $invoice->InvTotal += $tourdate->cost_quint*$count;
                $booking->balance += $tourdate->cost_quint*$count;
                $invoice_product['Amount'] = $tourdate->cost_quint;
            }elseif ($room['type'] == 6) {
                $invoice->InvTotal += $tourdate->cost_sext*$count;
                $booking->balance += $tourdate->cost_sext*$count;
                $invoice_product['Amount'] = $tourdate->cost_sext;
            }

            DB::table('invoice_products')->insert($invoice_product);

            if ($room['childcheck'] === "on") {
                $invoice_product = [];
                $childroom = new Bookroom;

                $childroom->bookingID = $booking->bookingsID;
                $childroom->roomtype = $room['child_room'];
                $childroom->travellers = implode(',', $room['children']);
                $childroom->entry_by = $user->id;
                $childroom->parent_id = $bookroom->roomID;
                $childroom->owner_id = CNF_OWNER;
                $childroom->save();

                $count = count($room['children']);

                $invoice_product['InvID'] = $invoice->invoiceID;
                $invoice_product['Code'] = $tourdate->tour_code;
                $invoice_product['Items'] = $tourdate->tour->tour_name.' ('.Bookroom::ROOM_TYPE_MAP[$room['child_room']].')';
                $invoice_product['Qty'] = $count;
                $invoice_product['owner_id'] = CNF_OWNER;

                if ($room['child_room'] == 7) {
                    $invoice->InvTotal += $tourdate->cost_child*$count;
                    $booking->balance += $tourdate->cost_child*$count;
                    $invoice_product['Amount'] = $tourdate->cost_child;
                }elseif ($room['child_room'] == 8) {
                    $invoice->InvTotal += $tourdate->cost_child_wo_bed*$count;
                    $booking->balance += $tourdate->cost_child_wo_bed*$count;
                    $invoice_product['Amount'] = $tourdate->cost_child_wo_bed;
                }

                DB::table('invoice_products')->insert($invoice_product);
            }

            if ($room['infantcheck'] === "on") {
                $invoice_product = [];
                $childroom = new Bookroom;

                $childroom->bookingID = $booking->bookingsID;
                $childroom->roomtype = $room['infant_room'];
                $childroom->travellers = implode(',', $room['infants']);
                $childroom->entry_by = $user->id;
                $childroom->parent_id = $bookroom->roomID;
                $childroom->owner_id = CNF_OWNER;
                $childroom->save();

                $count = count($room['infants']);

                $invoice_product['InvID'] = $invoice->invoiceID;
                $invoice_product['Code'] = $tourdate->tour_code;
                $invoice_product['Items'] = $tourdate->tour->tour_name.' ('.Bookroom::ROOM_TYPE_MAP[9].')';
                $invoice_product['Qty'] = $count;
                $invoice_product['owner_id'] = CNF_OWNER;

                $invoice->InvTotal += $tourdate->cost_infant_wo_bed*$count;
                $booking->balance += $tourdate->cost_infant_wo_bed*$count;
                $invoice_product['Amount'] = $tourdate->cost_infant_wo_bed;

                DB::table('invoice_products')->insert($invoice_product);
            }
        }

        $invoice->Subtotal = $invoice->InvTotal;
        $invoice->save();
        $booking->save();

        DB::commit();

        return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
    }

    public function getBookinglistpdf(Request $request, $id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $data = [
            'tourdate' => $tourdate
        ];

        return PDF::loadView('tourdates.bookinglist.pdf', $data)->stream();
    }

    public function getExportroomlist(Request $request, $id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $room_list[] = [ 'Booking No.', 'Room No./Type', 'Name', 'Gender' ];

        $num = 1;

        foreach ($tourdate->booktours as $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                foreach ($booking->bookRoom as $room) {
                    if ($room->parent_id) {
                        continue;
                    }
                    switch ($room->roomtype) {
                        case 1:
                            $room_type = 'Sigle';
                            break;
                        case 2:
                            $room_type = 'Double';
                            break;
                        case 3:
                            $room_type = 'Triple';
                            break;
                        case 4:
                            $room_type = 'Quad';
                            break;
                        case 5:
                            $room_type = 'Quint';
                            break;
                        case 6:
                            $room_type = 'Sext';
                            break;
                        
                        default:
                            $room_type = '';
                            break;
                    }
                    foreach ($room->travellerListWithChild as $key => $traveller) {
                        switch ($traveller->gender) {
                            case 'M':
                                $gender = 'Male';
                                break;
                            case 'F':
                                $gender = 'Female';
                                break;
                            
                            default:
                                $gender = 'Gender Not Found';
                                break;
                        }
                        if ($key == 0) {
                            $room_list[] = [ 
                                'Booking No.' =>  $booking->bookingno,
                                'Room No./Type' =>  'Room '.$num++.' ('.$room_type.')',
                                'Name' => $traveller->Fullname,
                                'Gender' => $gender
                            ];
                        }else{
                            $room_list[] = [ 
                                'Booking No.' =>  '',
                                'Room No./Type' =>  '',
                                'Name' => $traveller->Fullname,
                                'Gender' => $gender
                            ];
                        }
                    }
                    $room_list[] = [ 
                        'Booking No.' =>  '',
                        'Room No./Type' =>  '',
                        'Name' => '',
                        'Gender' => ''
                    ];
                }
            }
        }

        Excel::create('Room List', function($excel) use ($room_list){
            $excel->setTitle('Room List');
            $excel->sheet('Room List', function($sheet) use ($room_list){
                $sheet->fromArray($room_list, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

    public function getMasterlist($id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $data = [
            'tourdate' => $tourdate
        ];

        return PDF::loadView('tourdates.pdfmasterlist', $data)->setPaper('a4', 'landscape')->stream();
    }

    public function getMasterlistexcel($id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $data = [
            'tourdate' => $tourdate,
            'excel' => 1,
        ];

        Excel::create('Master List', function($excel) use ($data) {
            $excel->setTitle('Master List');
            $excel->sheet('Master List', function($sheet) use ($data) {
                $sheet->loadView('tourdates.pdfmasterlist', $data);
            });
        })->download('xlsx');

        // return PDF::loadView('tourdates.pdfmasterlist', $data)->setPaper('a4', 'landscape')->stream();
    }

    public function getVisalist($id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $data = [
            'tourdate' => $tourdate
        ];

        return PDF::loadView('tourdates.pdfvisalist', $data)->stream();
    }

    public function getRoomarrange($id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $this->data['tourdate'] = $tourdate;

        $rooms = [];

        foreach ($this->data['tourdate']->booktours as $key => $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                foreach ($booking->bookRoom->filter( function ($query) {return !in_array($query->roomtype, [7,8,9]);}) as $key => $room) {
                    $travellers = $room->travellerListWithChild;
                    foreach ($travellers as $key => $traveller) {
                        $traveller->tempBooking = $booking;
                    }
                    array_push($rooms, ['room_type' => $room->roomtype, 'travellers' => $travellers]);
                }
            }
        }

        usort($rooms, function($a, $b) {
            return $a['room_type'] <=> $b['room_type'];
        });

        $roomArr = [];

        foreach ($rooms as $key => $room) {
            if (array_key_exists($room['room_type'], $roomArr)) {
                array_push($roomArr[$room['room_type']], $room['travellers']);
            }else{
                $roomArr[$room['room_type']] = [$room['travellers']];
            }
        }

        // dd($roomArr);

        foreach ($roomArr as $key => $traArr) {
            foreach ($traArr as $keyA => $travellers) {
                $count = $this->travellersCount($travellers);
                while ($count > $key) {
                    $index = 0;
                    foreach ($roomArr[$key] as $inTra) {
                        $setted = false;
                        $in_count = $this->travellersCount($inTra);
                        if ($in_count < $key) {
                            $setted = true;
                            array_push($inTra, reset($travellers));
                            break;
                        }
                    }
                    if (!$setted) {
                        array_push($roomArr[$key], [reset($travellers)]);
                    }
                    unset($travellers[$index++]);
                    // dd($roomArr);
                    $count--;
                }
                $roomArr[$key][$keyA] = $travellers;
            }
        }

        // for ($i=1; $i <= count($roomArr); $i++) { 
        //     for ($j=0; $j < count($roomArr[$i]); $j++) { 
        //         $loop = true;
        //         $count = $this->travellersCount($roomArr[$i][$j]);
        //         while ($count < $i && $loop) {
        //             // dd($keyA);
        //             $tempKey = $j + 1;
        //             while(array_key_exists($tempKey, $roomArr[$i])){
        //                 if (count($roomArr[$i][$tempKey]) > 0) {
        //                     array_push($roomArr[$i][$j], reset($roomArr[$i][$tempKey]));
        //                     $firstKey = key($roomArr[$i][$tempKey]);
        //                     unset($roomArr[$i][$tempKey][$firstKey]);
        //                     $count++;
        //                     break;
        //                 }else{
        //                     $tempKey++;
        //                 }
        //             }
        //             if (!array_key_exists($tempKey, $roomArr[$i])) {
        //                 $loop = false;
        //             }
        //         }
        //     }
        // }

        foreach ($roomArr as $key => $tempRoom) {
            foreach ($roomArr[$key] as $keyA => $tempTra) {
                $loop = true;
                $count = $this->travellersCount($roomArr[$key][$keyA]);
                while ($count < $key && $loop) {
                    // dd($keyA);
                    $tempKey = $keyA + 1;
                    while(array_key_exists($tempKey, $roomArr[$key])){
                        if (count($roomArr[$key][$tempKey]) > 0) {
                            array_push($roomArr[$key][$keyA], reset($roomArr[$key][$tempKey]));
                            $firstKey = key($roomArr[$key][$tempKey]);
                            unset($roomArr[$key][$tempKey][$firstKey]);
                            $count++;
                            break;
                        }else{
                            $tempKey++;
                        }
                    }
                    if (!array_key_exists($tempKey, $roomArr[$key])) {
                        $loop = false;
                    }
                }
            }
        }

        $rooms = [];

        foreach ($roomArr as $key => $traArr) {
            foreach ($traArr as $keyA => $travellers) {
                array_push($rooms, ['room_type' => $key, 'travellers' => $travellers]);
            }
        }

        foreach ($rooms as $key => $room) {
            if (count($rooms[$key]['travellers']) == 0) {
                unset($rooms[$key]);
            }
        }

        // dd($rooms);

        $room_count = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
        ];

        foreach ($rooms as $key => $room) {
            $room_count[$room['room_type']]++;
        }

        $room_arrangements = RoomArrangement::where('owner_id', CNF_OWNER)->where('tourdate_id', $id)->get();

        $this->data['rooms'] = $rooms;
        $this->data['room_count'] = $room_count;
        $this->data['room_arrangements'] = $room_arrangements;

        return view('tourdates.room-arrange', $this->data);
    }

    public function travellersCount($travellers)
    {
        $count = 0;
        foreach ($travellers as $key => $traveller) {
            if (!in_array($traveller->tempRoom->roomtype, [8,9])) {
                $count++;
            }
        }
        return $count;
    }

    public function postRoomarrange(Request $request, $id)
    {
        $user = Auth::user();
        // dd(json_decode($request->rooms));
        DB::beginTransaction();
        $room_arrangement = new RoomArrangement;
        $room_arrangement->tourdate_id = $id;
        $room_arrangement->room_arrangement = $request->rooms;
        $room_arrangement->entry_by = $user->id;
        $room_arrangement->owner_id = CNF_OWNER;
        $room_arrangement->save();
        DB::commit();

        return redirect()->back();

        // $roomsArr = json_decode($request->rooms);

        // $rooms = [];

        // foreach ($roomsArr as $key => $room) {
        //     $roomtype = $room->room_type;
        //     $travellers = [];
        //     foreach ($room->travellers as $key => $traveller) {
        //         $tempTraveller = Travellers::find($traveller->traveller);
        //         $tempTraveller->tempBooking = Createbooking::find($traveller->booking);
        //         array_push($travellers, $tempTraveller);
        //     }
        //     array_push($rooms, ['room_type' => $roomtype, 'travellers' => $travellers]);
        // }
        // dd($rooms);
    }

    public function getRoomarrangement(Request $request, $id)
    {
        $room_arrangement = RoomArrangement::where('owner_id', CNF_OWNER)->where('id', $id)->get()->first();

        if (!$room_arrangement) {
            abort(404);
        }

        $roomsArr = json_decode($room_arrangement->room_arrangement);

        $rooms = [];

        for ($i=1; $i <= 6; $i++) { 
            foreach ($roomsArr as $key => $room) {
                $roomtype = $room->room_type;
                if ($roomtype == $i) {
                    $travellers = [];
                    foreach ($room->travellers as $key => $traveller) {
                        $tempTraveller = Travellers::find($traveller->traveller);
                        if (!$tempTraveller) {
                            $tempTraveller = new Travellers;
                            $tempTraveller->nameandsurname = "Traveller Not Found.";
                        }
                        $tempTraveller->tempBooking = Createbooking::find($traveller->booking);
                        array_push($travellers, $tempTraveller);
                    }
                    array_push($rooms, ['room_type' => $roomtype, 'travellers' => $travellers]);
                }
            }
        }

        for ($i=0; $i < count($rooms); $i++) { 
            if (count($rooms[$i]['travellers']) == 0) {
                unset($rooms[$i]);
            }
        }

        $this->data['rooms'] = $rooms;
        $this->data['tourdate'] = $room_arrangement->tourdate;

        return PDF::loadView('tourdates.room-arrangement-pdf', $this->data)->stream();
    }

    public function getInsurancelist($id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $data = [
            'tourdate' => $tourdate
        ];

        return PDF::loadView('tourdates.insurance-list-pdf', $data)->stream();
    }

    public function getInsuranceexcel(Request $request, $id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $num = 1;

        $insurance_list[] = [ CNF_COMNAME ];
        $insurance_list[] = [ CNF_ADDRESS ];
        $insurance_list[] = [ 'Tel: '.CNF_TEL.' EMAIL: '.CNF_EMAIL ];
        $insurance_list[] = [];
        $insurance_list[] = [ 'Insurance List for : '.$tourdate->tour->tour_name.' ('.$tourdate->tour_code.')' ];
        $insurance_list[] = [ 'Package Category : '.$tourdate->tourcategory->tourcategoryname ];
        $insurance_list[] = [ 'Package Date : '.Carbon::parse($tourdate->start)->format('d M Y').' - '.Carbon::parse($tourdate->end)->format('d M Y') ];
        $insurance_list[] = [];
        $insurance_list[] = [ 'No.', 'Pilgrim Name', 'NRIC' ];

        foreach($tourdate->booktours as $booktour){
            $booking = $booktour->booking;

            if($booking){
                foreach($booking->bookRoom as $room){
                    foreach($room->travellerList as $traveller){
                        $insurance_list[] = [ 'No.' => $num++, 'Pilgrim Name' => $traveller->fullname, 'NRIC' => $traveller->NRIC ?? 'No NRIC Found' ];
                    }
                }
            }
        }

        Excel::create('Insurance List', function($excel) use ($insurance_list){
            $excel->setTitle('Insurance List');
            $excel->sheet('Insurance List', function($sheet) use ($insurance_list){
                $sheet->fromArray($insurance_list, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

    public function postUploadpolicy(Request $request, $id)
    {
        $filename = '';

        $tourdate = Tourdates::where('tourdateID', $id)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        if ($request->hasFile('policy')) {
            // cache the file
            $file = $request->file('policy');

            // generate a new filename. getClientOriginalExtension() for the file extension
            $filename = 'tourdate-document-' . time() . '.' . $file->getClientOriginalExtension();

            // save to storage/app/{org_id}/policy as the new $filename
            // $path = $file->storeAs(CNF_OWNER.'/policy', $filename);
            $file->move( storage_path('app/'.CNF_OWNER.'/policy'), $filename );
        }else{
            dd('there is no file');
        }

        $tourdate->policy = $filename;
        $tourdate->save();

        return redirect()->back()->with('success', 'Attachment is successfully uploaded.');
    }

    public function getDownloadpolicy($id)
    {
        $tourdate = Tourdates::where('tourdateID', $id)->where('owner_id', CNF_OWNER)->get()->first();
        if (!$tourdate||!$tourdate->policy) {
            abort(404);
        }

        $path = storage_path('app/'.CNF_OWNER.'/policy/'.$tourdate->policy);

        return response()->download($path);
    }

    public function getTourdatefilter($id)
    {
        session()->put('tourdatefilter', $id);

        return 'success';
    }

    public function getTourdatefilterclear()
    {
        session()->forget('tourdatefilter');

        return 'success';
    }

    public function getExportticketmanifest(Request $request, $id)
    {
        $tourdate = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $id)->get()->first();

        if (!$tourdate) {
            abort(404);
        }

        $room_list[] = [ 'Booking No.', 'Room No./Type', 'Name', 'Gender' ];

        $num = 1;

        $ticket_manifest = [];

        foreach ($tourdate->booktours as $key => $booktour) {
            $booking = $booktour->booking;

            if ($booking) {
                foreach ($booking->bookRoom as $key2 => $room) {
                    foreach ($room->travellerList as $key3 => $traveller) {

                        $gender = '';

                        if($traveller->gender === 'M')
                            $gender = 'MALE';

                        if($traveller->gender === 'F')
                            $gender = 'FEMALE';

                        $traveller_data = [
                            '', // seat no
                            ''.$num++, // no
                            '', // status
                            '', // title
                            $traveller->nameandsurname, // first name
                            $traveller->last_name, // last name
                            $gender, // gender
                            $traveller->dateofbirth ? Carbon::parse($traveller->dateofbirth)->format('d F, Y') : '', // date of birth
                            $traveller->nationalityCountry->country_name ?? '', // nationality
                            $traveller->passportno, // passport no
                            $traveller->passportexpiry ? Carbon::parse($traveller->passportexpiry)->format('d F, Y') : '', // passport expiry date
                            $traveller->email, // personal email address
                            $traveller->phone, // passenger contact no
                            '', // seat no
                            '', // wheel chair
                            '', // special meal
                            '', // access to klia golden lounge
                            CNF_COMNAME, // travel agent/charterer company name
                            CNF_TEL, // travel agent/charterer contact no
                            CNF_EMAIL, // travel agent/charterer email address
                            '', // pnr
                            '', // remarks
                        ];

                        $ticket_manifest[] = $traveller_data;
                    }
                }
            }
        }

        Excel::create('Ticket Manifest', function($excel) use ($ticket_manifest, $tourdate){
            $excel->setTitle('Ticket Manifest');
            $excel->sheet('Ticket Manifest', function($sheet) use ($ticket_manifest, $tourdate){

                $sheet->setWidth([
                    'B' => 10,
                    'C' => 10,
                    'E' => 50,
                    'F' => 50,
                    'H' => 20,
                    'I' => 15,
                    'J' => 15,
                    'K' => 20,
                    'L' => 30,
                    'M' => 20,
                    'O' => 40,
                    'P' => 20,
                    'Q' => 40,
                    'R' => 40,
                    'S' => 40,
                    'T' => 40,
                ]);

                $sheet->mergeCells('C3:M3');

                $sheet->cells('C3', function($cell) use ($tourdate) {
                    $cell->setValue('SENARAI NAMA '.strtoupper($tourdate->tour->tour_name));
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('C4:M4');

                $sheet->cells('C4', function($cell) use ($tourdate) {
                    $cell->setValue(Carbon::parse($tourdate->start)->format('d M').' '.Carbon::parse($tourdate->end)->format('d M Y'));
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('C5:M5');

                $sheet->cells('C5', function($cell) use ($tourdate) {
                    $cell->setValue(CNF_COMNAME);
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('A7:B7');

                $sheet->cells('A7', function($cell) use ($tourdate) {
                    $cell->setValue('MUTAWWIF');
                });

                $sheet->cells('C7', function($cell) use ($tourdate) {
                    $cell->setValue(':');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('D7:E7');

                if ($tourdate->guide) {
                    $sheet->cells('D7', function($cell) use ($tourdate) {
                        $cell->setValue($tourdate->guide->name);
                    });
                }

                $sheet->mergeCells('A8:B8');

                $sheet->cells('A8', function($cell) use ($tourdate) {
                    $cell->setValue('TEL.NO (MUTAWWIF)');
                });

                $sheet->cells('C8', function($cell) use ($tourdate) {
                    $cell->setValue(':');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('D8:E8');

                if ($tourdate->guide) {
                    $sheet->cells('D8', function($cell) use ($tourdate) {
                        $cell->setValue($tourdate->guide->name);
                    });
                }

                $sheet->mergeCells('A9:B9');

                $sheet->cells('A9', function($cell) use ($tourdate) {
                    $cell->setValue('TOTAL PAX');
                });

                $sheet->cells('C9', function($cell) use ($tourdate) {
                    $cell->setValue(':');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('D9:E9');

                $sheet->cells('D9', function($cell) use ($ticket_manifest) {
                    $cell->setValue(''.count($ticket_manifest));
                });

                $sheet->mergeCells('A10:B10');

                $sheet->cells('A10', function($cell) use ($tourdate) {
                    $cell->setValue('FLIGHT SCHEDULE');
                });

                $sheet->cells('C10', function($cell) use ($tourdate) {
                    $cell->setValue(':');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                });

                $sheet->mergeCells('D10:E10');

                $sheet->mergeCells('A14:A15');

                $sheet->cells('A14', function($cell) {
                    $cell->setValue('SEAT');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->mergeCells('B14:B15');

                $sheet->cells('B14', function($cell) {
                    $cell->setValue('NO');
                    $cell->setBackground('#548135');
                    $cell->setFontColor('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->mergeCells('C14:C15');

                $sheet->cells('C14', function($cell) {
                    $cell->setValue('Status');
                    $cell->setBackground('#548135');
                    $cell->setFontColor('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->mergeCells('D14:M14');

                $sheet->cells('D14', function($cell) {
                    $cell->setValue('PAX DETAILS');
                    $cell->setBackground('#548135');
                    $cell->setFontColor('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('D15', function($cell) {
                    $cell->setValue('TITLE');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('E15', function($cell) {
                    $cell->setValue('FIRST NAME');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('F15', function($cell) {
                    $cell->setValue('LAST NAME');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('G15', function($cell) {
                    $cell->setValue('GENDER');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('H15', function($cell) {
                    $cell->setValue('DATE OF BIRTH');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('I15', function($cell) {
                    $cell->setValue('NATIONALITY');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('J15', function($cell) {
                    $cell->setValue('PASSPORT NO.');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('K15', function($cell) {
                    $cell->setValue('PASSPORT EXPIRY DATE');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('L15', function($cell) {
                    $cell->setValue('PERSONAL EMAIL ADDRESS');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('M15', function($cell) {
                    $cell->setValue('PASSENGER CONTACT NBR');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->mergeCells('N14:Q14');

                $sheet->cells('N14', function($cell) {
                    $cell->setValue('AIRCRAFT & SERVICE');
                    $cell->setBackground('#548135');
                    $cell->setFontColor('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('N15', function($cell) {
                    $cell->setValue('SEAT NO');
                    $cell->setBackground('#bbe0a1');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('O15', function($cell) {
                    $cell->setValue('WHEEL CHAIR REQUIREMENT (YES OR NO)');
                    $cell->setBackground('#bbe0a1');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('P15', function($cell) {
                    $cell->setValue('SPECIAL MEAL');
                    $cell->setBackground('#bbe0a1');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('Q15', function($cell) {
                    $cell->setValue('ACESS TO KLIA GOLDEN LOUNGE');
                    $cell->setBackground('#bbe0a1');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->mergeCells('R14:T14');

                $sheet->cells('R14', function($cell) {
                    $cell->setValue('TRAVEL AGENT');
                    $cell->setBackground('#548135');
                    $cell->setFontColor('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('R15', function($cell) {
                    $cell->setValue('TRAVEL AGENT/ CHARTERER COMPANY NAME');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('S15', function($cell) {
                    $cell->setValue('TRAVEL AGENT/ CHARTERER CONTACT NBR');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('T15', function($cell) {
                    $cell->setValue('TRAVEL AGENT/ CHARTERER EMAIL ADDRESS');
                    $cell->setBackground('#A8D08D');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->mergeCells('U14:V14');

                $sheet->cells('U14', function($cell) {
                    $cell->setValue('OFFICE');
                    $cell->setBackground('#548135');
                    $cell->setFontColor('#ffffff');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('U15', function($cell) {
                    $cell->setValue('PNR');
                    $cell->setBackground('#bbe0a1');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->cells('V15', function($cell) {
                    $cell->setValue('REMARKS');
                    $cell->setBackground('#bbe0a1');
                    $cell->setAlignment('center');
                    $cell->setValignment('center');
                    $cell->setBorder('solid', 'solid', 'solid', 'solid');
                });

                $sheet->fromArray($ticket_manifest, null, 'A16', false, false);
            });
        })->download('xlsx');
    }
    
}
