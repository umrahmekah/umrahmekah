<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Groups;
use App\Models\Core\Pages;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Input;
use Redirect;
use Validator;

class PagesController extends Controller
{
    public $module          = 'pages';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model  = new Pages();
        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'core/pages',
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'pageID');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
        // End Filter sort and order for query
        // Filter Search for query
        $filter = (! is_null($request->input('search')) ? '' : '');
        $filter .= " AND pagetype != 'post' OR pagetype IS NULL ";

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
        $pagination->setPath('pages');

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

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template
        return view('core.pages.index', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('tb_pages');
        }

        if ('' != $this->data['row']['access']) {
            $access = json_decode($this->data['row']['access'], true);
        } else {
            $access = [];
        }

        $path                       = base_path() . '/resources/views/layouts/' . CNF_THEME . '/info.json';
        $this->data['pagetemplate'] = json_decode(file_get_contents($path), true);
        //echo '<pre>';print_r($this->data['pagetemplate']);echo '</pre>'; exit;

        $groups = Groups::all();
        $group  = [];
        foreach ($groups as $g) {
            $group_id = $g['group_id'];
            $a        = (isset($access[$group_id]) && 1 == $access[$group_id] ? 1 : 0);
            $group[]  = ['id' => $g->group_id, 'name' => $g->name, 'access' => $a];
        }

        $this->data['groups'] = $group;

        $this->data['id'] = $id;

        return view('core.pages.form', $this->data);
    }

    public function getShow($id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('tb_pages');
        }
        $this->data['id']     = $id;
        $this->data['access'] = $this->access;

        return view('core.pages.view', $this->data);
    }

    public function postSave(Request $request, $id = 0)
    {
        $rules = [
                'title'    => 'required',
                'alias'    => 'required|alpha_dash',
                'filename' => 'required|alpha',
                'status'   => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $content = $request->input('content');
            $data    = $this->validatePost('tb_pages');

            if (isset($data['default']) && 1 == $data['default']) {
                // Update default homepage if checked
                \DB::table('tb_pages')->where('owner_id', '=', CNF_OWNER)->update(['default' => '0']);
            }
            /*
            if($request->input('pageID') ==1)
            {
                $filename = base_path() ."/resources/views/pages/home.blade.php";
            } else {
                $filename = base_path() ."/resources/views/pages/".$request->input('filename').".blade.php";
            }
            $fp=fopen($filename,"w+");
            fwrite($fp,$content);
            fclose($fp);
            */

            $groups = Groups::all();
            $access = [];
            foreach ($groups as $group) {
                $access[$group->group_id] = (isset($_POST['group_id'][$group->group_id]) ? '1' : '0');
            }

            $data['access'] = json_encode($access);
            $data['note']   = $request->note;

            $data['allow_guest'] = $request->input('allow_guest');
            $data['template']    = $request->input('template');

            if ('' == $request->input('pageID')) {
                $data['created'] = date('Y-m-d H:i:s');
            } else {
                $data['updated'] = date('Y-m-d H:i:s');
            }

            $id = $this->model->insertRow($data, $request->input('pageID'));
            self::createRouters();

            if ('' != ! is_null($request->input('apply'))) {
                return Redirect::to('core/pages/update/' . $id)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
            } else {
                return Redirect::to('core/pages?return=' . self::returnUrl())->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
            }
        } else {
            return Redirect::to('core/pages/update/' . $id)->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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
        if (count($request->input('id')) >= 1) {
            // Check page file and remove it before remove row on database
            $sql = \DB::table('tb_pages')->whereIn('pageID', $request->input('id'))->get();
            foreach ($sql as $row) {
                if (file_exists(base_path() . '/resources/views/pages/' . $row->filename . '.blade.php')) {
                    unlink(base_path() . '/resources/views/pages/' . $row->filename . '.blade.php');
                }
            }

            $this->model->destroy($request->input('id'));

            self::createRouters();

            return Redirect::to('core/pages')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/pages')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public function createRouters()
    {
        $rows = \DB::table('tb_pages')->where('pageID', '!=', '1')->get();
        $val  = "<?php \n";
        $val .= "Route::group(['middleware' => 'domain'], function () {";
        foreach ($rows as $row) {
            $slug = $row->alias;
            $val .= "Route::get('{$slug}', 'HomeController@index');\n";
        }
        $val .= "});";
        $val .= '?>';
        $filename = base_path('routes/web/pages.php');
        $fp       = fopen($filename, 'w+');
        fwrite($fp, $val);
        fclose($fp);

        return true;
    }
}
