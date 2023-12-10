<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Travellers;
use App\User;
use App\Models\Tourdates;
use App\Models\Owners;
use App\Models\Tours;
use App\Models\Tasks;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Validator;
use DB, PDF;
use Response;

class TasksController extends Controller
{
    public $module          = 'travellers';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Travellers();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => 'Tasks',
            'pageNote'   => $this->info['note'],
            'pageModule' => 'travellers',
            'return'     => self::returnUrl(),
        ];

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {   
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'travellerID');
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

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('travellers');

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
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['grid']);
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template
        
        $tasks = Tasks::where('owner_id','=', CNF_OWNER)->orderBy('id', 'desc')->get();
        $this->data['tasks'] = $tasks;
        
        $status = [
            0 => '',
            2 => ''
        ];
        
        $users = User::get();
        $this->data['users'] = $users;
        
        $tours = Tours::get();
        $this->data['tours'] = $tours;
        
        $tourdates = Tourdates::where('owner_id','=', CNF_OWNER)->get();
        $this->data['tourdates'] = $tourdates;
        
        foreach($tasks as $task){
            if($task->status == 0)
                $status = "Ongoing";
            else if ($task->status == 2)
                $status = "Completed";
        }
        
        $completed_task = \DB::table('task')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('status', 2)
                ->count();
        
        $ongoing_task = \DB::table('task')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('status', 0)
                ->count();
        
        $owner = Owners::where('id', '=', CNF_OWNER)->get()->first();
        
        $this->data['completed_task'] = $completed_task;
        $this->data['ongoing_task']   = $ongoing_task;
        $this->data['owner']          = $owner;
        
        $this->data['status'] = $status;

        $departureDate   = Tasks::join('tour_date', 'tour_date.tourdateID', '=', 'task.tour_date_id')->where('task.owner_id', '=', CNF_OWNER)
                ->first();

        $tours = Tours::where('owner_id', '=', CNF_OWNER)->get();

        $this->data['tours'] = $tours;
        $this->data['departureDate'] = $departureDate;
        
        return view('tasks.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate(Request $request)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        $tourdates = Tourdates::where('owner_id', CNF_OWNER)->get();
        $users = User::where('owner_id', CNF_OWNER)->whereIn('group_id', [2,3,4,5])->get();
        
        $tours = Tours::get();
        $this->data['tours'] = $tours;
        
        $this->data['tourdates'] = $tourdates;
        $this->data['users'] = $users;
        
        return view('tasks.form', $this->data);
    }
    
    public function display()
    {   
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postStore(Request $request)
    {   
        $tourdates = Tourdates::where('owner_id', CNF_OWNER)->get();
        $users = User::where('owner_id', CNF_OWNER)->get();
        $task = new Tasks();
        
        $task->task_name = $request->task_name;
        $task->description = $request->description;
        $task->owner_id = CNF_OWNER;
        $task->due_date = $request->due_date;
        $task->assigner_id = Auth::user()->id;
        $task->assigned_id = $request->assigned_id;
        $task->entry_by = Auth::user()->id;
        $task->tour_date_id = $request->tourdateID;
        $task->status = 0;
        
        $task->save();
        
        return redirect('tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request, $id)
    {   
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        
        $task = Tasks::findOrFail($id);
        $this->data['task'] = $task;
        
        $created_by = User::where('tb_users.id', '=', $task->entry_by)
                      ->get()->first();
        $assigned_to = User::where('tb_users.id', '=', $task->assigned_id)
                      ->get()->first();
        
        $assigned_by = User::where('tb_users.id', '=', $task->assigner_id)
                      ->get()->first();
        
        $owner       = Owners::where('tb_owners.id', '=', $task->owner_id)
                      ->get()->first();


        $departureDate   = Tasks::join('tour_date', 'tour_date.tourdateID', '=', 'task.tour_date_id')->where('task.owner_id', '=', CNF_OWNER)
                ->first();

        $tours = Tours::where('owner_id', '=', CNF_OWNER)->get();

        $tourdates = Tourdates::where('owner_id','=', CNF_OWNER)->get();
        $this->data['tourdates'] = $tourdates;
        
        $this->data['owner']         = $owner;
        $this->data['assigned_by']   = $assigned_by;
        $this->data['assigned_to']   = $assigned_to;
        $this->data['created_by']    = $created_by;
        $this->data['tours'] = $tours;
        $this->data['departureDate'] = $departureDate;
        
        
        return view('tasks.view', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {   
        $tours = Tours::get();
        $this->data['tours'] = $tours;
        $task = Tasks::findOrFail($id);
        $users = User::where('owner_id', CNF_OWNER)->whereIn('group_id', [2,3,4,5])->get();
        $tourdates = Tourdates::where('owner_id', CNF_OWNER)->get();
        $this->data['url'] = '/tasks/update/'.$task->id;
        $this->data['task'] = $task;
        $this->data['users'] = $users;
        $this->data['tourdates'] = $tourdates;
        
        return view('tasks.edit', $this->data);
    }
    
    public function getDuplicate($id)
    {   
        $tours = Tours::get();
        $this->data['tours'] = $tours;
        $task = Tasks::findOrFail($id);
        $users = User::where('owner_id', CNF_OWNER)->whereIn('group_id', [2,3,4,5])->get();
        $tourdates = Tourdates::where('owner_id', CNF_OWNER)->get();
        $this->data['url'] = 'tasks/store';
        $this->data['task'] = $task;
        $this->data['users'] = $users;
        $this->data['tourdates'] = $tourdates;
        
        return view('tasks.edit', $this->data);
    }
    
    public function getCompletestatus(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);
        $users = User::where('owner_id', CNF_OWNER)->get();
        $this->data['task'] = $task;
        $this->data['users'] = $users;
        
        $task->status = 2;
        
        $task->save();
        
        return redirect('tasks');
    }
        
    public function postUpdate(Request $request, $id)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
            ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        
        $tours = Tours::get();
        $this->data['tours'] = $tours;
        $task = Tasks::findOrFail($id);
        $users = User::where('owner_id', CNF_OWNER)->get();
        
        $task->task_name = $request->task_name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->assigner_id = Auth::user()->id;
        $task->assigned_id = $request->assigned_id;
        $task->entry_by = Auth::user()->id;
        $task->tour_date_id = $request->tourdateID;
        $task->status = $request->status;
        
        $task->save();
        
        
        return redirect('tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request, $id)
    {
        $task = Tasks::findOrFail($id);

        $task->delete();

        return Redirect::to('tasks');
    }
    
    public function postMassdelete(Request $request)
    {
        $gotids = $request->input('ids');

        DB::beginTransaction();
        
        if($gotids){
            foreach($gotids as $id){
                $task = Tasks::findOrFail($id);
                $task->delete();
            }
        }
            
        DB::commit();
            
        return redirect('tasks');
    }

    public function postMassduplicate(Request $request)
    {
        $ids = $request->input('ids');

        DB::beginTransaction();

        if ($ids) {
            foreach ($ids as $key => $id) {
                $task = Tasks::findOrFail($id);
                $copy = new Tasks;
                $copy->task_name = $task->task_name;
                $copy->description = $task->description;
                $copy->owner_id = $task->owner_id;
                $copy->due_date = $task->due_date;
                $copy->assigner_id = $task->assigner_id;
                $copy->assigned_id = $task->assigned_id;
                $copy->entry_by = $task->entry_by;
                $copy->tour_date_id = $task->tour_date_id;
                $copy->status = 0;
                $copy->save();
            }
        }

        DB::commit();

        return redirect('tasks');
    }
}
