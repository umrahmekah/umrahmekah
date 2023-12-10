<?php

namespace App\Http\Controllers;

use App\Models\Booktour;
use App\Models\Tourbounddates;
use App\Models\Tourbound;
use App\Models\tourcategories;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;
use Carbon;

class TourbounddatesController extends Controller
{
    public $module          = 'tourbounddates';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Tourbounddates();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'tourbounddates',
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
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        if (0 == $this->access['is_view']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
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
                ->where('type', 2)
                ->count();
        $upcoming_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('start', '>', $today)
                ->where('status', 1)
                ->where('type', 2)
                ->count();
        $old_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('end', '<', $today)
                ->where('status', 1)
                ->where('type', 2)
                ->count();
        $cancelled_tours = \DB::table('tour_date')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('status', 2)
                ->where('type', 2)
                ->count();

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

        $paid = [];

        foreach ($results['rows'] as $row) {
            $tourbooks = Booktour::where('tourdateID', $row->tourdateID)->get();

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

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('tourbounddates');

        $this->data['running_tours']   = $running_tours;
        $this->data['upcoming_tours']  = $upcoming_tours;
        $this->data['old_tours']       = $old_tours;
        $this->data['cancelled_tours'] = $cancelled_tours;
        $this->data['today']           = $today;

        $this->data['rowData'] = $results['rows'];

        $this->data['paid'] = $paid;
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

        // Render into template
        return view('tourbounddates.index', $this->data);
    }

    public function getUpdate(Request $request, $id = null)
    {
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

        $row = $this->model->retrive($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            if ('' == $id) {
                $this->data['row'] = $this->model->getColumnTable('tour_date');
            } else {
                return Redirect::to('tourdates')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $categories               = tourcategories::where('owner_id', CNF_OWNER)->where('type', 2)->get();
        $this->data['categories'] = $categories;
        $this->data['fields']     = \SiteHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('tourbounddates.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row']      = $row;
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext'] = $this->model->prevNext($id);

            $bookinglist = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('book_room.status', '=', 1)
            ->orderBy('roomtype', 'ASC')
            ->get();

            $bkList = [];
            $first  = 0;
            foreach ($bookinglist as $bl) {
                $bkList[] = [
                'travellers' => $bl->travellers,
                'remarks'    => $bl->remarks,
            ];
                ++$first;
            }
            $this->data['bkList'] = $bkList;

            $room_single = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('roomtype', '=', 1)
            ->where('book_room.status', '=', 1)
            ->count();

            $room_double = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('roomtype', '=', 2)
            ->where('book_room.status', '=', 1)
            ->count();

            $room_triple = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('roomtype', '=', 3)
            ->where('book_room.status', '=', 1)
            ->count();

            $room_quad = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('roomtype', '=', 4)
            ->where('book_room.status', '=', 1)
            ->count();

            $rooms = \DB::table('book_room')
            ->leftJoin('book_tour', 'book_room.bookingID', '=', 'book_tour.bookingID')
            ->where('tourdateID', '=', $id)
            ->where('book_room.status', '=', 1)
            ->get();

            $pax = 0;

            foreach ($rooms as $room) {
                $pax += count(explode(',', $room->travellers));
            }

            // $total = $room_single + ($room_double * 2) + ($room_triple * 3) + ($room_quad * 4);
            $total = $pax;

            $this->data['room_single'] = $room_single;
            $this->data['room_double'] = $room_double;
            $this->data['room_triple'] = $room_triple;
            $this->data['room_quad']   = $room_quad;
            $this->data['total']       = $total;

            if (! is_null($request->input('bookinglist'))) {
                // $html = view('tourdates.pdfbookinglist', $this->data)->render();
                // return \PDF::load($html)->filename('BookingList-'.$id.'.pdf')->show();
                return \PDF::loadView('tourbounddates.pdfbookinglist', $this->data)->stream();
            }

            if (! is_null($request->input('passportlist'))) {
                // $html = view('tourdates.pdfpassportlist', $this->data)->render();
                // return \PDF::load($html)->filename('PassportList-'.$id.'.pdf')->show();
                return \PDF::loadView('tourbounddates.pdfpassportlist', $this->data)->stream();
            }

            if (! is_null($request->input('emergencylist'))) {
                // $html = view('tourdates.pdfemergencylist', $this->data)->render();
                // return \PDF::load($html, $size = 'A4', $orientation = 'landscape')->filename('PassportList-'.$id.'.pdf')->show();
                return \PDF::loadView('tourdates.pdfemergencylist', $this->data)->stream();
            }

            return view('tourbounddates.view', $this->data);
        } else {
            return Redirect::to('tourbounddates')->with('messagetext', 'Record Not Found !')->with('msgstatus', 'error');
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

        foreach (\DB::select('SHOW COLUMNS FROM tour_date ') as $column) {
            if ('tourdateID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO tour_date (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM tour_date WHERE tourdateID IN (' . $toCopy . ')';
            \DB::select($sql);

            return Redirect::to('tourbounddates')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tourbounddates')->with('messagetext', 'Please select row to copy')->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $rules     = $this->validateForm();
        $temp = ($request->all());
        $tour = Tourbound::find($temp['tourID']);
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
            $data['type'] = 2;

            $id = $this->model->insertRow($data, $request->input('tourdateID'));

            // $tdate = Tourbounddates::find($id);

            // $tdate->cost_deposit = $request->input('cost_deposit');
            // $tdate->type         = 2;

            // $tdate->save();

            if (! is_null($request->input('apply'))) {
                $return = 'tourbounddates/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'tourbounddates?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('tourdateID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tourbounddates/update/' . $request->input('tourdateID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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
            return Redirect::to('tourbounddates')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tourbounddates')
                ->with('messagetext', 'No Item Deleted')->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Tourbounddates();
        $info  = $model::makeInfo('tourbounddates');

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

                return view('tourbounddates.public.view', $data);
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

            return view('tourbounddates.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost($request);
            $this->model->insertRow($data, $request->input('tourdateID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }
}
