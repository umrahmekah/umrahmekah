<?php

namespace App\Http\Controllers\core;

use App\Http\Controllers\controller;
use App\Models\Core\Groups;
use App\Models\Core\Users;
use App\Models\Travelagents;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Input;
use Mail;
use Redirect;
use Validator;

class UsersController extends Controller
{
    public $module          = 'users';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model  = new Users();
        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'core/users',
            'active'     => 'users',
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'id');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
        // End Filter sort and order for query
        // Filter Search for query
        $filter = (! is_null($request->input('search')) ? $this->buildSearch() : '');
        $filter .= " AND tb_users.group_id >= '" . \Session::get('gid') . "'";

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
        $pagination->setPath('users');

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
        return view('core.users.index', $this->data);
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

        //$row = $this->model->find($id);
        $row = $this->model->retrive($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            if ('' == $id) {
                $this->data['row'] = $this->model->getColumnTable('tb_users');
            } else {
                return Redirect::to('core/users')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        $this->data['id'] = $id;

        return view('core.users.form', $this->data);
    }

    public function getShow($id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            return Redirect::to('core/users')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            //$this->data['row'] = $this->model->getColumnTable('tb_users');
        }
        $this->data['id']     = $id;
        $this->data['access'] = $this->access;

        return view('core.users.view', $this->data);
    }

    public function postSave(Request $request, $id = 0)
    {
        $rules = $this->validateForm();
        if ('' == $request->input('id')) {
            $rules = [
            'username'              => 'required|alpha|between:3,12|unique:tb_users',
            'email'                 => 'required|email|unique:tb_users',
            'password'              => 'required|between:6,12|confirmed',
            'password_confirmation' => 'required|between:6,12',
            ];
        } else {
            if ('' != $request->input('password')) {
                $rules['password']              = 'required|between:6,12|confirmed';
                $rules['password_confirmation'] = 'required|between:6,12';
            }
        }
        if (! is_null(Input::file('avatar'))) {
            $rules['avatar'] = 'mimes:jpg,jpeg,png,gif,bmp';
        }

        $validator = Validator::make($request->all(), $rules);
        //dd($validator);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_users');
            if ('' == $request->input('id')) {
                $data['password'] = \Hash::make(Input::get('password'));
            } else {
                if ('' != Input::get('password')) {
                    $data['password'] = \Hash::make(Input::get('password'));
                } else {
                    unset($data['password']);
                }
            }

            $id = $this->model->insertRow($data, $request->input('id'));

            $user = Users::find($id);

            $ta = Travelagents::where('owner_id', CNF_OWNER)->where('email', $user->email)->get()->first();

            if (5 == $user->group_id && ! $ta) {
                DB::table('travel_agent')->insert(
                ['agency_name' => '',
                'legalname'    => $user->first_name . ' ' . $user->last_name,
                'email'        => $user->email,
                'agent_logo'   => '',
                'countryID'    => 129,
                'cityID'       => 0,
                'address'      => '',
                'status'       => 1,
                'owner_id'     => CNF_OWNER, ]);
            }

            if (! is_null(Input::file('avatar'))) {
                $updates         = [];
                $file            = $request->file('avatar');
                $destinationPath = './uploads/users/';
                $filename        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension(); //if you need extension of the file
                $newfilename     = $id . '.' . $extension;
                $uploadSuccess   = $request->file('avatar')->move($destinationPath, $newfilename);
                if ($uploadSuccess) {
                    $updates['avatar'] = $newfilename;
                }
                $this->model->insertRow($updates, $id);
            }

            if (! is_null($request->input('apply'))) {
                $return = 'core/users/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'core/users?return=' . self::returnUrl();
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/users/update/')->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
            //return Redirect::to('core/users/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')->withErrors($validator)->withInput();
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

            // redirect
            return Redirect::to('core/users')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/users')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public function getSearch($mode = 'native')
    {
        $this->data['tableForm']  = $this->info['config']['forms'];
        $this->data['tableGrid']  = $this->info['config']['grid'];
        $this->data['searchMode'] = 'native';
        $this->data['pageUrl']    = url('core/users');

        return view('mmb.module.utility.search', $this->data);
    }

    public function getBlast()
    {
        $this->data = [
            'groups'    => Groups::all(),
            'pageTitle' => 'Blast Email',
            'active'    => 'blast',
            'pageNote'  => 'Send email to users',
        ];

        return view('core.users.blast', $this->data);
    }

    public function postDoblast(Request $request)
    {
        $rules = [
            'subject' => 'required',
            'message' => 'required|min:10',
            'groups'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            if (! is_null($request->input('groups'))) {
                $count  = 0;
                $groups = $request->input('groups');
                for ($i = 0; $i < count($groups); ++$i) {
                    if ('all' == $request->input('uStatus')) {
                        $users = \DB::table('tb_users')->where('group_id', '=', $groups[$i])->get();
                    } else {
                        $users = \DB::table('tb_users')->where('active', '=', $request->input('uStatus'))->where('group_id', '=', $groups[$i])->get();
                    }

                    foreach ($users as $row) {
                        $data['note']    = $request->input('message');
                        $data['row']     = $row;
                        $data['to']      = $row->email;
                        $data['subject'] = $request->input('subject');

                        if (defined('CNF_MAIL') && CNF_MAIL == 'swift') {
                            \Mail::send('core.users.email', $data, function ($message) use ($data) {
                                $message->to($data['to'])->subject($data['subject']);
                            });
                        } else {
                            $message = view('core.users.email', $data);
                            $headers = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers .= 'From: ' . CNF_COMNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
                            mail($data['to'], $data['subject'], $message, $headers);
                        }

                        ++$count;
                    }
                }

                return Redirect::to('core/users/blast')->with('messagetext', $count . \Lang::get('core.messagehasbeensent'))->with('msgstatus', 'success');
            }

            return Redirect::to('core/users/blast')->with('messagetext', \Lang::get('core.nomessagehasbeensent'))->with('msgstatus', 'info');
        } else {
            return Redirect::to('core/users/blast')->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }
}
