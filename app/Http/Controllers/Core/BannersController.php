<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\controller;
use App\Models\Core\Banners;
use App\Models\Core\Groups;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;

class BannersController extends Controller
{
    public $module          = 'banners';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Banners();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'core/banners',
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'sort');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'desc');
        // End Filter sort and order for query
        // Filter Search for query
        $filter = '  ';
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
        $pagination->setPath('banners');

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
        // Group users permission
        $this->data['access'] = $this->access;

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template
        return view('core.banners.index', $this->data);
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
                $this->data['row'] = $this->model->getColumnTable('banners');
            } else {
                return Redirect::to('core/banners')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $this->data['fields'] = \SiteHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        $groups = Groups::all();
        $group  = [];
        foreach ($groups as $g) {
            $group_id = $g['group_id'];
            $a        = (isset($access[$group_id]) && 1 == $access[$group_id] ? 1 : 0);
            if ((1 == \Session::get('gid') && 1 == $g->group_id) || 1 != $g->group_id) {
                $group[] = ['id' => $g->group_id, 'name' => $g->name, 'access' => $a];
            }
        }

        $this->data['groups'] = $group;

        return view('core.banners.form', $this->data);
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
            $this->data['prevnext'] = $this->model->prevNext($id);

            return view('core.banners.view', $this->data);
        } else {
            return Redirect::to('banners')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('banners');

            if ('' == $request->input('bannerID')) {
                $data['created_at'] = date('Y-m-d H:i:s');
            } else {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }

            $id = $this->model->insertRow($data, $request->input('bannerID'));

            if (! is_null($request->input('apply'))) {
                $return = 'core/banners/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'core/banners?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('bannerID')) {
                \SiteHelpers::auditTrail($request, 'New banner with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Banner with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/banners/update/' . $request->input('bannerID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfully');
            // redirect
            return Redirect::to('core/banners?return=' . self::returnUrl())
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/banners?return=' . self::returnUrl())
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Banners();
        $info  = $model::makeInfo('banners');

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

                return view('core.banners.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'bannerID',
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

            return view('core.banners.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('banners');
            $this->model->insertRow($data, $request->input('bannerID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getList(Request $request)
    {
        $page   = isset($_GET['page']) ? $_GET['page'] : 1;
        $params = [
            'page'   => $page,
            'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
            'sort'   => 'bannerID',
            'order'  => 'asc',
            'params' => '  ',
            'global' => 1,
        ];

        return self::articles($params, $page, 'all');
    }

    public function getView(Request $request, $id)
    {
        $row = $this->model->getRow($id);
        if ($row) {
            $data['pageLang'] = 'en';
            if ('' != \Session::get('lang')) {
                $data['pageLang'] = \Session::get('lang');
            }

            $data['pageTitle']    = $row->title;
            $data['pageNote']     = 'View All';
            $data['breadcrumb']   = 'inactive';
            $data['pageMetakey']  = $row->metakey;
            $data['pageMetadesc'] = $row->metadesc;
            $data['filename']     = '';

            $data['row']      = $row;
            $data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $data['id']       = $id;
            $data['access']   = $this->access;
            $data['prevnext'] = $this->model->prevNext($id);
            $data['labels']   = self::splitLabels($row->labels);
            $page             = 'layouts.' . CNF_THEME . '.index';
            $data['pages']    = 'banners.public.view';

            return view($page, $data);
        } else {
            return Redirect::to('banners')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public static function splitLabels($value = '')
    {
        $value = explode(',', $value);
        $vals  = '';
        foreach ($value as $val) {
            $vals .= '<a href="' . url('banners/label/' . trim($val)) . '" class="btn btn-xs btn-default"> ' . trim($val) . ' </a> ';
        }

        return $vals;
    }

    public function getSearch($mode = 'native')
    {
        $this->data['tableForm']  = $this->info['config']['forms'];
        $this->data['tableGrid']  = $this->info['config']['grid'];
        $this->data['searchMode'] = 'native';
        $this->data['pageUrl']    = url('core/banners');

        return view('mmb.module.utility.search', $this->data);
    }
}
