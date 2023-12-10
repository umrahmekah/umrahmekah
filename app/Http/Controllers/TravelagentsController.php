<?php

namespace App\Http\Controllers;

use App\Models\Bookroom;
use App\Models\Booktour;
use App\Models\Core\Users;
use App\Models\Createbooking;
use App\Models\Invoice;
use App\Models\Payments;
use App\Models\Tours;
use App\Models\Travelagents;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;

class TravelagentsController extends Controller
{
    public $module          = 'travelagents';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Travelagents();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'travelagents',
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

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'travelagentID');
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
        $results = $this->model->getRows($params);

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('travelagents');

        //enter extra information for table
        for ($i = 0; $i < sizeof($results['rows']); ++$i) {
            $travelagent = Travelagents::where('email', $results['rows'][$i]->email)->get()->first();
            $user        = Users::where('email', $results['rows'][$i]->email)->get()->first();

            if ($user) {
                $offsales        = 0;
                $offcount        = 0;
                $offline_booking = Createbooking::where('owner_id', CNF_OWNER)->where('entry_by', $user->id)->get();
                $bookid          = [];
                foreach ($offline_booking as $book) {
                    array_push($bookid, $book->bookingsID);
                }
                $invoices = Invoice::whereIn('bookingID', $bookid)->where('owner_id', CNF_OWNER)->get();
                foreach ($invoices as $inv) {
                    $offsales += $inv->Subtotal;
                }
                $rooms = Bookroom::whereIn('bookingID', $bookid)->get();
                foreach ($rooms as $room) {
                    $offcount += count(explode(',', $room->travellers));
                }
            } else {
                $offsales = 0;
                $offcount = 0;
            }

            $onsales = 0;
            $oncount = 0;

            $affiliate_booking = Createbooking::where('owner_id', CNF_OWNER)->whereNotIn('affiliatelink', [''])->where('affiliatelink', $travelagent->affiliatelink)->get();
            $bookid            = [];
            foreach ($affiliate_booking as $book) {
                array_push($bookid, $book->bookingsID);
            }
            $invoices = Invoice::whereIn('bookingID', $bookid)->where('owner_id', CNF_OWNER)->get();
            foreach ($invoices as $inv) {
                $onsales += $inv->Subtotal;
            }
            $rooms = Bookroom::whereIn('bookingID', $bookid)->get();
            foreach ($rooms as $room) {
                $oncount += count(explode(',', $room->travellers));
            }

            $counts = $offcount + $oncount;

            $total_sales      = $offsales + $onsales;
            $total_commission = $counts * $travelagent->commissionrate;

            $results['rows'][$i]->total_sales      = $total_sales;
            $results['rows'][$i]->total_commission = $total_commission;
        }

        $agent = [];

        if (5 == Auth::user()->group_id) {
            foreach ($results['rows'] as $result) {
                if (Auth::user()->email == $result->email) {
                    array_push($agent, $result);
                }
            }
        }
        $this->data['rowData'] = $results['rows'];
        if (5 == Auth::user()->group_id) {
            $this->data['rowData'] = $agent;
        }
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
        return view('travelagents.index', $this->data);
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

        $row = $this->model->retrive($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            if ('' == $id) {
                $this->data['row'] = $this->model->getColumnTable('travel_agent');
            } else {
                return Redirect::to('travelagents')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('travelagents.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row']      = $row;
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext'] = $this->model->prevNext($id);

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
                'params' => $filter,
                'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
            ];

            $agent = Users::where('email', $row->email)->get()->first();

            $affiliate_bookings = Createbooking::select('bookingsID')->where('owner_id', CNF_OWNER)->whereNotIn('affiliatelink', [''])->where('affiliatelink', $row->affiliatelink)->get();

            $bookingsID = [];

            foreach ($affiliate_bookings as $affiliate) {
                array_push($bookingsID, $affiliate->bookingsID);
            }

            if ($agent) {
                $bookings = DB::table('bookings')->where('owner_id', CNF_OWNER)->where('entry_by', $agent->id)->orWhereIn('bookingsID', $bookingsID)->get();
            } else {
                $bookings = DB::table('bookings')->where('owner_id', CNF_OWNER)->whereIn('bookingsID', $bookingsID)->get();
            }

            // dd($bookings);
            $total = sizeof($bookings);

            // Build pagination setting
            $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
            $pagination = new Paginator($bookings, $total, $params['limit']);
            $pagination->setPath('travelagents/show/' . $id);

            foreach ($bookings as $i => $temp) {
                $booktour = Booktour::where('bookingID', $bookings[$i]->bookingsID)->get()->first();
                if ($booktour) {
                    $tour = Tours::find($booktour->tourID);
                    if ($tour) {
                        $package = $tour->tour_name;
                    } else {
                        $package = 'Package not found';
                    }
                } else {
                    $package = 'No package is selected';
                }
                $invoice = Invoice::where('bookingID', $bookings[$i]->bookingsID)->get()->first();
                if ($invoice) {
                    $sub_total = $invoice->InvTotal;
                    $payments  = Payments::where('invoiceID', $invoice->invoiceID)->get();

                    $total = $sub_total;

                    foreach ($payments as $payment) {
                        $total -= $payment->amount;
                    }

                    if ($total == $sub_total && 0 != $total) {
                        $payment_status = 'Awaiting Payment';
                    } elseif ($total < $sub_total && 0 != $total) {
                        $payment_status = 'Partially Paid';
                    } else {
                        $payment_status = 'Paid';
                    }
                } else {
                    $sub_total      = 'No Invoice';
                    $payment_status = 'No Invoice';
                }

                $counts = 0;

                $rooms = Bookroom::where('bookingID', $bookings[$i]->bookingsID)->get();
                foreach ($rooms as $room) {
                    $counts += count(explode(',', $room->travellers));
                }

                $bookings[$i]->package        = $package;
                $bookings[$i]->total_sales    = $sub_total;
                $bookings[$i]->payment_status = $payment_status;
                $bookings[$i]->commission     = $counts * $row->commissionrate;
            }

            // dd($bookings);

            // Build Pagination
            $this->data['pagination'] = $pagination;
            // Build pager number and append current param GET
            $this->data['pager'] = $this->injectPaginate();
            // Row grid Number
            $this->data['i']        = ($page * $params['limit']) - $params['limit'];
            $this->data['bookings'] = $bookings;

            return view('travelagents.view', $this->data);
        } else {
            return Redirect::to('travelagents')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM travel_agent ') as $column) {
            if ('travelagentID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO travel_agent (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM travel_agent WHERE travelagentID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return Redirect::to('travelagents')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('travelagents')->with('messagetext', \Lang::get('core.note_selectrow'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $rules = $this->validateForm();
        // dd($rules);
        unset($rules['agency_name']);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_travelagents');

            /***check for duplicate email before save***/
            // $check_email = travelagents::where('email',$data['email'])->get()->count();

            // if($check_email>0){
            //     return redirect()->back()->with('messagetext',\Lang::get('core.errortravelagentemail'))->with('msgstatus','error')
            //         ->withInput();
            // }

            $id = $this->model->insertRow($data, $request->input('travelagentID'));

            DB::table('travel_agent')->where('travelagentID', $id)->update(['affiliatelink' => $request->affiliatelink]);

            // $check_affiliate=Travelagents::where('travelagentID',$id)->first();
            // if(!isset($check_affiliate['affiliatelink'])){
            //              $affiliate['affiliatelink'] = str_random(15);
            //              $this->model->insertRow($affiliate , $id );
            //          }

            if (! is_null($request->input('apply'))) {
                $return = 'travelagents/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'travelagents?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('travelagentID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('travelagents/update/' . $request->input('travelagentID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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
            $this->model->destroy($request->input('ids'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfull');
            // redirect
            return Redirect::to('travelagents')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('travelagents')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Travelagents();
        $info  = $model::makeInfo('travelagents');

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

                return view('travelagents.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'travelagentID',
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

            return view('travelagents.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('travel_agent');
            $this->model->insertRow($data, $request->input('travelagentID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }
}
