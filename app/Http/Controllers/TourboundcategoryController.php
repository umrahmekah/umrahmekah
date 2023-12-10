<?php

namespace App\Http\Controllers;

use App\Models\Tourboundcategory;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;

class TourboundcategoryController extends Controller
{
    public $module          = 'tourboundcategory';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model  = new Tourboundcategory();
        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'tourboundcategory',
            'pageUrl'    => url('tourboundcategory'),
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $this->data['access'] = $this->access;

        return view('tourboundcategory.index', $this->data);
    }

    public function postData(Request $request)
    {
        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']);
        $order = (! is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
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
            'limit'  => (! is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : $this->info['setting']['perpage']),
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
        $pagination->setPath('tourboundcategory/data');

        $this->data['param']   = $params;
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
        $this->data['setting'] = $this->info['setting'];

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template
        return view('tourboundcategory.table', $this->data);
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

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('def_tour_categories');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields']  = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('tourboundcategory.form', $this->data);
    }

    public function getShow($id = null)
    {
        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $this->data['access'] = $this->access;

        return view('tourboundcategory.view', $this->data);
    }

    public function postSave(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('def_tour_categories');

            $id = $this->model->insertRow($data, $request->input('tourcategoriesID'));

            DB::table('def_tour_categories')->where('tourcategoriesID', $id)->update(['type' => $request->input('type')]);

            return response()->json([
                'status'  => 'success',
                'message' => \Lang::get('core.note_success'),
                ]);
        } else {
            $message = $this->validateListError($validator->getMessageBag()->toArray());

            return response()->json([
                'message' => $message,
                'status'  => 'error',
            ]);
        }
    }

    public function postDelete(Request $request)
    {
        if (0 == $this->access['is_remove']) {
            return response()->json([
                'status'  => 'error',
                'message' => \Lang::get('core.note_restric'),
            ]);
            die;
        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            return response()->json([
                'status'  => 'success',
                'message' => \Lang::get('core.note_success_delete'),
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => \Lang::get('core.note_error'),
            ]);
        }
    }
}
