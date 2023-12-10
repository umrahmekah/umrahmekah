<?php

namespace App\Http\Controllers;

use App\Models\Bookroom;
use App\Models\Booktour;
use App\Models\Flightbooking;
use App\Models\Flightdates;
use App\Models\Flightmatching;
use App\Models\Tours;
use App\Models\Travellers;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Mail;
use PDF;
use Redirect;
use Validator;
use Excel;

class FlightbookingController extends Controller
{
    public $module          = 'flightbooking';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Flightbooking();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = [];

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'flightbooking',
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_view']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'id');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
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
        $results = $this->model->getRows($params, session('uid'));

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('flightbooking');

        for ($i = 0; $i < count($results['rows']); ++$i) {
            $results['rows'][$i]->flight_matching = Flightmatching::with(['flightDate'])->where('id', $results['rows'][$i]->flight_match_depart_id)->get()->first();
        }

        $this->data['rowData'] = $results['rows'];
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
        $this->data['fields'] = \SiteHelpers::fieldLang($this->info['config']['grid']);
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);

        $this->data['insort']  = $sort;
        $this->data['inorder'] = $order;

        // Render into template
        return view('flightbooking.index', $this->data);
    }

    public function getUpdate(Request $request, $id = null)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if ('' == $id) {
            if (0 == $this->access['is_add']) {
                return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        if ('' != $id) {
            if (0 == $this->access['is_edit']) {
                return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('flight_booking');
        }
        $this->data['fields'] = \SiteHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('flightbooking.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_detail']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $tours = Tours::where('owner_id', CNF_OWNER)->where('status', 1)->get();

        $row = $this->model->getRow($id);
        if ($row) {
            $tour_books  = Booktour::where('tourdateID', $row->tourdates_id)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
            $booking_ids = [];
            foreach ($tour_books as $tour_book) {
                array_push($booking_ids, $tour_book->bookingID);
            }
            $room_books    = Bookroom::whereIn('bookingID', $booking_ids)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
            $traveller_ids = [];
            foreach ($room_books as $room_book) {
                $travellerslist = explode(',', $room_book->travellers);
                $traveller_ids  = array_merge($traveller_ids, $travellerslist);
            }
            $travellers = Travellers::whereIn('travellerID', $traveller_ids)->where('owner_id', CNF_OWNER)->get();

            // dd($travellers);

            $row->flight_matching = Flightmatching::with(['flightDate'])->where('id', $row->flight_match_depart_id)->get()->first();
            $row->flight_depart   = Flightmatching::with(['flightDate'])->where('id', $row->flight_match_depart_id)->get()->first();
            $row->flight_return   = Flightmatching::with(['flightDate'])->where('id', $row->flight_match_return_id)->get()->first();

            $this->data['row']        = $row;
            $this->data['travellers'] = $travellers;
            $this->data['tours']      = $tours;
            $this->data['fields']     = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']         = $id;
            $this->data['access']     = $this->access;
            $this->data['subgrid']    = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']     = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext']   = $this->model->prevNext($id);

            return view('flightbooking.view', $this->data);
        } else {
            return Redirect::to('flightbooking')->with('messagetext', 'Record Not Found !')->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_add']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        foreach (\DB::select('SHOW COLUMNS FROM flight_booking ') as $column) {
            if ('id' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO flight_booking (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM flight_booking WHERE id IN (' . $toCopy . ')';
            \DB::select($sql);

            return Redirect::to('flightbooking')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('flightbooking')->with('messagetext', 'Please select row to copy')->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        // Make Sure users Logged
        // dd($request->all());
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $user = Auth::user();

        $rules = $this->validateForm();
        $rules = [
            'tour'     => 'required',
            'tourdate' => 'required',
            'email'    => 'required',
            'message'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            // $data = $this->validatePost( $request );

            // $id = $this->model->insertRow($data , $request->input('id'));

            $data = [
                'departure_date'         => $request->departure_date,
                'return_date'            => $request->return_date,
                'email'                  => $request->email,
                'pax'                    => $request->pax,
                'tourdates_id'           => $request->tourdate,
                'flight_match_depart_id' => $request->depart_flight,
                'flight_match_return_id' => $request->return_flight,
                'entry_by'               => $user->id,
                'owner_id'               => CNF_OWNER,
            ];

            DB::table('flight_booking')->insert($data);

            // if(!is_null($request->input('apply')))
            // {
            // 	$return = 'flightbooking/update/'.$id.'?return='.self::returnUrl();
            // } else {
            // 	$return = 'flightbooking?return='.self::returnUrl();
            // }

            $return = 'flightbooking?return=' . self::returnUrl();

            // Insert logs into database
            // if($request->input('id') =='')
            // {
            // 	\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
            // } else {
            // 	\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
            // }

            $flightdate = Flightdates::find($request->id);

            $email_data = [
                'depart_flight' => Flightmatching::find($request->depart_flight),
                'return_flight' => Flightmatching::find($request->return_flight),
                'pax'           => $request->pax,
                'bodyMessage'   => $request->message,
            ];

            $cc = $request->cc;

            Mail::send('flightbooking.bookingmail', $email_data, function ($message) use ($data, $user, $flightdate, $cc) {
                $message->to($data['email'], $flightdate->flight_company)->subject('Booking Flight');
                if ('on' == $cc) {
                    $message->cc($user->email);
                }
                $message->from($user->email, $user->first_name);
            });

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('flightdates/show/' . $request->input('id'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postDelete(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_remove']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfull');
            // redirect
            return Redirect::to('flightbooking')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('flightbooking')
                ->with('messagetext', 'No Item Deleted')->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Flightbooking();
        $info  = $model::makeInfo('flightbooking');

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

                return view('flightbooking.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'id',
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

            return view('flightbooking.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost($request);
            $this->model->insertRow($data, $request->input('id'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getTravellerpdf(Request $request)
    {
        $row         = Flightbooking::find($request->id);
        $departMatch = Flightmatching::find($row->flight_match_depart_id);
        $returnMatch = Flightmatching::find($row->flight_match_return_id);
        $tour_books  = Booktour::where('tourdateID', $row->tourdates_id)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
        $booking_ids = [];
        foreach ($tour_books as $tour_book) {
            array_push($booking_ids, $tour_book->bookingID);
        }
        $room_books    = Bookroom::whereIn('bookingID', $booking_ids)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
        $traveller_ids = [];
        foreach ($room_books as $room_book) {
            $travellerslist = explode(',', $room_book->travellers);
            $traveller_ids  = array_merge($traveller_ids, $travellerslist);
        }
        $travellers = Travellers::whereIn('travellerID', $traveller_ids)->where('owner_id', CNF_OWNER)->get();

        $data = [
            'travellers'  => $travellers,
            'departMatch' => $departMatch,
            'returnMatch' => $returnMatch,
            'row'         => $row,
        ];

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('flightbooking.travellerlistpdf', $data);

        return $pdf->stream('Traveller List.pdf');
    }

    public function postUpdatebooking(Request $request)
    {
        $data = [
            'status'      => $request->status,
            'pnr'         => $request->pnr,
            'payment_due' => $request->payment_due,
        ];

        DB::table('flight_booking')->where('id', $request->id)->update($data);

        return back();
    }

    public function postEmailpassenger(Request $request)
    {
        $user        = Auth::user();
        $row         = Flightbooking::find($request->id);
        $departMatch = Flightmatching::find($row->flight_match_depart_id);
        $returnMatch = Flightmatching::find($row->flight_match_return_id);
        $tour_books  = Booktour::where('tourdateID', $row->tourdates_id)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
        $booking_ids = [];
        foreach ($tour_books as $tour_book) {
            array_push($booking_ids, $tour_book->bookingID);
        }
        $room_books    = Bookroom::whereIn('bookingID', $booking_ids)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
        $traveller_ids = [];
        foreach ($room_books as $room_book) {
            $travellerslist = explode(',', $room_book->travellers);
            $traveller_ids  = array_merge($traveller_ids, $travellerslist);
        }
        $travellers = Travellers::whereIn('travellerID', $traveller_ids)->where('owner_id', CNF_OWNER)->get();

        $data = [
            'travellers'  => $travellers,
            'departMatch' => $departMatch,
            'returnMatch' => $returnMatch,
            'row'         => $row,
            'email'       => $request->email,
        ];

        $name = 'Passenger APIS' . time() . '.pdf';

        $pdf = PDF::loadView('flightbooking.travellerlistpdf', $data);
        $pdf->save(public_path(sprintf('%s%s', 'uploads/', $name)));

        $flightdate = Flightdates::find($departMatch->flight_date);

        $email_data = [
            'bodyMessage' => $request->message,
        ];

        $cc = $request->cc;

        Mail::send('flightbooking.passengermail', $email_data, function ($message) use ($data, $user, $flightdate, $cc, $name) {
            $message->to($data['email'], $flightdate->flight_company)->subject('Booking Flight');
            if ('on' == $cc) {
                $message->cc($user->email);
            }
            $message->from($user->email, $user->first_name);
            $message->attach(public_path(sprintf('%s%s', 'uploads/', $name)));
        });

        unlink(public_path(sprintf('%s%s', 'uploads/', $name)));

        return back();
    }

    public function getApisexcel(Request $request, $id)
    {
        $row         = Flightbooking::find($id);
        $departMatch = Flightmatching::find($row->flight_match_depart_id);
        $returnMatch = Flightmatching::find($row->flight_match_return_id);
        $tour_books  = Booktour::where('tourdateID', $row->tourdates_id)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
        $booking_ids = [];
        foreach ($tour_books as $tour_book) {
            array_push($booking_ids, $tour_book->bookingID);
        }
        $room_books    = Bookroom::whereIn('bookingID', $booking_ids)->where('owner_id', CNF_OWNER)->where('status', 1)->get();
        $traveller_ids = [];
        foreach ($room_books as $room_book) {
            $travellerslist = explode(',', $room_book->travellers);
            $traveller_ids  = array_merge($traveller_ids, $travellerslist);
        }
        $travellers = Travellers::whereIn('travellerID', $traveller_ids)->where('owner_id', CNF_OWNER)->get();

        $num = 1;

        $traveller_list[] = [ CNF_COMNAME ];
        $traveller_list[] = [ CNF_ADDRESS ];
        $traveller_list[] = [ 'Tel: '.CNF_TEL.' EMAIL: '.CNF_EMAIL ];
        $traveller_list[] = [];
        $traveller_list[] = [ 'PNR : '.$row->pnr ];
        $traveller_list[] = [];
        $traveller_list[] = [$departMatch->sector.'   '.strtoupper(\Carbon::parse($row->departure_date)->format('d M')).'   '.$departMatch->flight_number.'   '.$departMatch->dep_time.'/'.$departMatch->arr_time];
        $traveller_list[] = [$returnMatch->sector.'   '.strtoupper(\Carbon::parse($row->departure_date)->format('d M')).'   '.$returnMatch->flight_number.'   '.$returnMatch->dep_time.'/'.$returnMatch->arr_time];
        $traveller_list[] = [];
        $traveller_list[] = [ 'No.', 'APIS Data' ];

        foreach($travellers as $traveller){
            $apis = 'SRDOCSSVHK1-P-MAS-';

            if($traveller->passportno){
                $apis.=$traveller->passportno;
            }else{
                $apis.='Please Update Passport';
            }

            $apis.='-MAS-';

            if($traveller->dateofbirth){
                $apis.=strtoupper(\Carbon::parse($traveller->dateofbirth)->format('dMy'));
            }else{
                $apis.='Please Update Date of Birth';
            }

            $apis.='-';

            if($traveller->gender){
                $apis.=$traveller->gender;
            }else{
                $apis.='Please Update Gender';
            }

            $apis.='-';

            if($traveller->passportexpiry){
                $apis.=strtoupper(\Carbon::parse($traveller->passportexpiry)->format('dMy'));
            }else{
                $apis.='Please Update Passport Expiry Date';
            }

            $apis.='-';

            if($traveller->nameandsurname){
                $apis.=str_replace(' ', '', strtoupper($traveller->nameandsurname));
            }else{
                $apis.='Please Update First Name';
            }

            $apis.='/';

            if($traveller->last_name){
                $apis.=str_replace(' ', '', strtoupper($traveller->last_name));
            }else{
                $apis.='Please Update Last Name';
            }

            $apis.='/P'.$num;

            $traveller_list[] = [ 'No.' => $num++, 'APIS Data' => $apis ];
        }

        Excel::create('Traveller List', function($excel) use ($traveller_list){
            $excel->setTitle('Traveller List');
            $excel->sheet('Traveller List', function($sheet) use ($traveller_list){
                $sheet->fromArray($traveller_list, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
