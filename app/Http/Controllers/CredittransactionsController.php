<?php

namespace App\Http\Controllers;

use App\Models\Credittotals;
use App\Models\Credittransactions;
use App\Models\currency;
use App\Models\paymentgateways;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;

class CredittransactionsController extends Controller
{
    public $module          = 'credittransactions';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Credittransactions();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = [];

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'credittransactions',
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
        $pagination->setPath('credittransactions');
        //		$currency= currency::select('symbol')->where('currencyID',$results['currency'])->get();
        //		dd($currency);

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
        return view('credittransactions.index', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('credit_transactions');
        }
        $this->data['fields'] = \SiteHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('credittransactions.form', $this->data);
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

        $row           = $this->model->getRow($id);
        $currency_name = currency::select('currency_name')->where('currencyID', $row->currency)->first();
        $gateway_name  = paymentgateways::select('gateway_name')->where('id', $row->payment_gateway_id)->first();

        if ($row) {
            $this->data['gateway_name']  = $gateway_name;
            $this->data['currency_name'] = $currency_name;
            $this->data['row']           = $row;
            $this->data['fields']        = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']            = $id;
            $this->data['access']        = $this->access;
            $this->data['subgrid']       = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']        = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext']      = $this->model->prevNext($id);
            //dd($this->data);
            return view('credittransactions.view', $this->data);
        } else {
            return Redirect::to('credittransactions')->with('messagetext', 'Record Not Found !')->with('msgstatus', 'error');
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

        foreach (\DB::select('SHOW COLUMNS FROM credit_transactions ') as $column) {
            if ('id' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO credit_transactions (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM credit_transactions WHERE id IN (' . $toCopy . ')';
            \DB::select($sql);

            return Redirect::to('credittransactions')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('credittransactions')->with('messagetext', 'Please select row to copy')->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost($request);

            $id = $this->model->insertRow($data, $request->input('id'));

            if (! is_null($request->input('apply'))) {
                $return = 'credittransactions/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'credittransactions?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('id')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('credittransactions/update/' . $request->input('id'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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
            return Redirect::to('credittransactions')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('credittransactions')
                ->with('messagetext', 'No Item Deleted')->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Credittransactions();
        $info  = $model::makeInfo('credittransactions');

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

                return view('credittransactions.public.view', $data);
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

            return view('credittransactions.public.index', $data);
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

    public function postBillplz()
    {
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        $credittransactions = Credittransactions::where('transaction_id', '=', isset($_POST['id']) ? $_POST['id'] : '')->get()->first();

        if (null != $credittransactions) {
            $credittransactions->transaction_date = isset($_POST['paid_at']) ? $_POST['paid_at'] : '';
            $credittransactions->status           = isset($_POST['state']) ? $_POST['state'] : '';

            $credittransactions->save();

            if ('paid' == $credittransactions->status) {
                $creditexist = Credittotals::where('owner_id', '=', CNF_OWNER)->get()->first();

                if (null == $creditexist) {
                    $credittotals = new Credittotals();

                    $credittotals->owner_id     = CNF_OWNER;
                    $credittotals->total_credit = $credittransactions->credit_request;
                    $credittotals->entry_by     = User::where('email', '=', $email)->get()->first()->id;

                    $credittotals->save();
                } else {
                    $credittotals = Credittotals::where('owner_id', '=', CNF_OWNER)->get()->first();

                    $credittotals->total_credit += $credittransactions->credit_request;

                    $credittotals->save();
                }
            }
        }
    }
}
