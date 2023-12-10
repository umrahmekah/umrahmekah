<?php

namespace App\Http\Controllers;

use App\Models\Tourcategories;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;

class TourcategoriesController extends Controller
{
    public $module          = 'tourcategories';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Tourcategories();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'tourcategories',
            'pageUrl'    => url('tourcategories'),
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex()
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $this->data['access'] = $this->access;

        return view('tourcategories.index', $this->data);
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
        $pagination->setPath('tourcategories/data');

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
        return view('tourcategories.table', $this->data);
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

        return view('tourcategories.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;

            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['setting']  = $this->info['setting'];
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['prevnext'] = $this->model->prevNext($id);

            $print = (! is_null($request->input('print')) ? 'true' : 'false');
            if ('true' == $print) {
                $data['html'] = view('tourcategories.view', $this->data)->render();

                return view('layouts.blank', $data);
            } else {
                return view('tourcategories.view', $this->data);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => \Lang::get('core.note_error'),
            ]);
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM def_tour_categories ') as $column) {
            if ('tourcategoriesID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }
        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));

            $sql = 'INSERT INTO def_tour_categories (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM def_tour_categories WHERE tourcategoriesID IN (' . $toCopy . ')';
            \DB::select($sql);

            return response()->json([
                'status'  => 'success',
                'message' => \Lang::get('core.note_success'),
            ]);
        } else {
            return response()->json([
                'status'  => 'success',
                'message' => 'Please select row to copy',
            ]);
        }
    }

    public function postSave(Request $request, $id = 0)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('def_tour_categories');

            $id = $this->model->insertRow($data, $request->input('tourcategoriesID'));

            $cat = Tourcategories::find($id);

            if ($cat->status == 0) {
                foreach ($cat->tours as $tour) {
                    $tour->status = 0;
                    $tour->save();
                }

                foreach ($cat->tourdates as $tourdate) {
                    $tourdate->status = 0;
                    $tourdate->save();
                }
            }

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

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Tourcategories();
        $info  = $model::makeInfo('tourcategories');

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

                return view('tourcategories.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'tourcategoriesID',
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

            return view('tourcategories.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('def_tour_categories');
            $this->model->insertRow($data, $request->input('tourcategoriesID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }
}
