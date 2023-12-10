<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Models\Suppliertypes;
use App\Models\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
use Redirect;
use Validator;
use Auth;

class SuppliersController extends Controller
{
    public $module          = 'suppliers';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Suppliers();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'suppliers',
            'pageUrl'    => url('suppliers'),
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex()
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $this->data['access'] = $this->access;

        return view('suppliers.index', $this->data);
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

        foreach ($results['rows'] as $key => $row) {
            $row->supplier = Suppliers::find($row->supplierID);
        }

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('suppliers/data');

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
        // dd($this->data);
        return view('suppliers.table', $this->data);
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
            $this->data['row'] = $this->model->getColumnTable('def_supplier');
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields']  = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('suppliers.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);

        $supplier_type = Suppliertypes::select('supplier_type')->where('suppliertypeID', $row->suppliertypeID)->first();
        if ($row) {
            $this->data['row']           = $row;
            $this->data['supplier_type'] = $supplier_type;

            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['setting']  = $this->info['setting'];
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['prevnext'] = $this->model->prevNext($id);

            $print = (! is_null($request->input('print')) ? 'true' : 'false');
            if ('true' == $print) {
                $data['html'] = view('suppliers.view', $this->data)->render();

                return view('layouts.blank', $data);
            } else {
                return view('suppliers.view', $this->data);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => \Lang::get('core.note_error'),
            ]);
        }
    }

    public function getShow2(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);

        $currency = DB::table('def_currency')->select('symbol')->where('currencyID',CNF_CURRENCY)->get();
        if(empty($currency))
            $symbol = '';
        else
            $symbol = $currency[0]->symbol; 

        $this->data['symbol'] = $symbol;

        $supplier_type = Suppliertypes::select('supplier_type')->where('suppliertypeID', $row->suppliertypeID)->first();
        if ($row) {
            $this->data['row']           = $row;
            $this->data['supplier']      = Suppliers::find($row->supplierID);
            $this->data['supplier_type'] = $supplier_type;

            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['setting']  = $this->info['setting'];
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['prevnext'] = $this->model->prevNext($id);

            return view('suppliers.view2', $this->data);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => \Lang::get('core.note_error'),
            ]);
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM def_supplier ') as $column) {
            if ('supplierID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }
        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));

            $sql = 'INSERT INTO def_supplier (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM def_supplier WHERE supplierID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return response()->json([
                'status'  => 'success',
                'message' => \Lang::get('core.note_success'),
            ]);
        } else {
            return response()->json([
                'status'  => 'success',
                'message' => \Lang::get('core.note_selectrow'),
            ]);
        }
    }

    public function postSave(Request $request, $id = 0)
    {
//        $rules = $this->validateForm();
//        $validator = Validator::make($request->all(), $rules);
//        if ($validator->passes()) {
//            $data = $this->validatePost('def_supplier');
//
//            $id = $this->model->insertRow($data , $request->input('supplierID'));

//            $documents = Suppliers::find($id);
        //$current = $documents->document;

//            $owner = DB::table('tb_owners')->where('id', $documents['owner_id'])->first();
//            $type = DB::table('def_supplier_type')->where('suppliertypeID', $documents['suppliertypeID'])->first();
//            $current = DB::table('def_supplier')->where('supplierID',$id)->first();

//            $d = explode(",",$current->document);
//
//            $files = $request->file('document');
//            //if ($current = '') {
//                //insert new document
//                if ($request->hasFile('document')) {
//                    foreach ($files as $file) {
//                        $name = $file->getClientOriginalName();
//                        $location = $file->move(public_path() . '/files/suppliers/' . $owner->name . '/' . $type->supplier_type . '/' . $id . '/', $name);
//                        $s_l[] = '/files/suppliers/' . $owner->name . '/' . $type->supplier_type . '/' . $id . '/' . $name;
//                        $f = implode(",", $s_l);
//                        $documents->document = $f;
//
//                    }
//                }
//            } else {
//                //to update document
//                if ($request->hasFile('document')) {
//                    foreach ($files as $file) {
//                        $name = $file->getClientOriginalName();
//                        $location = $file->move(public_path() . '/files/suppliers/' . $owner->name . '/' . $type->supplier_type . '/' . $id . '/', $name);
//                        $s_l[] = '/files/suppliers/' . $owner->name . '/' . $type->supplier_type . '/' . $id . '/' . $name;
//                        $merge = array_merge($d, $s_l);
//                        Log::info($merge);
//                        $f = implode(",", $merge);
//                        $documents->document = $f;
//
//                    }
//                }
//
//            }
//            $documents->start_date = $request->input('start_date');
//            $documents->expired_date = $request->input('expired_date');
//            $documents->save();

//            return response()->json(array(
//                'status'=>'success',
//                'message'=> \Lang::get('core.note_success')
//            ));
//
//        } else {
//
//            $message = $this->validateListError(  $validator->getMessageBag()->toArray() );
//            return response()->json(array(
//                'message'	=> $message,
//                'status'	=> 'error'
//            ));
//        }

        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $data = $this->validatePost($request);

            if ('' == $request->input('supplierID')) {
                $id = $this->model->insertRow($data, $request->input('supplierID'));
            } else {
                $supplier                 = Suppliers::find($request->input('supplierID'));
                $supplier->suppliertypeID = $data['suppliertypeID'];
                $supplier->name           = $data['name'];
                $supplier->email          = $data['email'];
                $supplier->phone          = $data['phone'];
                $supplier->address        = $data['address'];
                $supplier->countryID      = $data['countryID'];
                $supplier->cityID         = $data['cityID'];
                $supplier->status         = $data['status'];
                $supplier->start_date     = $data['start_date'];
                $supplier->expired_date   = $data['expired_date'];
                if ($request->hasFile('document')) {
                    $supplier->document = $data['document'];
                } elseif (isset($request['currdocument'])) {
                    $supplier->document = implode(',', $request['currdocument']);
                } else {
                    $supplier->document = null;
                }
                $supplier->save();
            }

            if (! is_null($request->input('apply'))) {
                $return = 'supplierscontroller/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'supplierscontroller?return=' . self::returnUrl();
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
        $model = new Suppliers();
        $info  = $model::makeInfo('suppliers');

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

                return view('suppliers.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'supplierID',
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

            return view('suppliers.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('def_supplier');
            $this->model->insertRow($data, $request->input('supplierID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }
    }

    public function postAddservice(Request $request, $id)
    {
        $user = Auth::user();

        $filename = null;

        $supplier = Suppliers::where('supplierID', $id)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$supplier) {
            return abort(404);
        }

        DB::beginTransaction();

        if ($request->hasFile('document')) {
            // cache the file
            $file = $request->file('document');

            // generate a new filename. getClientOriginalExtension() for the file extension
            $filename = 'service-file-' . time() . '.' . $file->getClientOriginalExtension();

            // save to storage/app/{owner_id}/service as the new $filename
            $file->move( storage_path('app/'.CNF_OWNER.'/service'), $filename );
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'price' => $request->price,
            'min_quantity' => $request->min_quantity,
            'max_quantity' => $request->max_quantity,
            'document' => $filename,
            'supplier_id' => $id,
            'status' => $request->status,
            'entry_by' => $user->id,
            'owner_id' => CNF_OWNER
        ];

        $service = SupplierService::create($data);

        DB::commit();

        return redirect('/suppliers/show2/'.$id)->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
    }

    public function getDownloaddocument(Request $request, $id)
    {
        $service = SupplierService::where('id', $id)->where('document', $request->file)->where('supplier_id', $request->an_id)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$service) {
            abort(404);
        }

        $path = storage_path('app/'.CNF_OWNER.'/service/'.$service->document);

        return response()->download($path);
    }

    public function postEditservice(Request $request, $id)
    {
        $user = Auth::user();

        $supplier = Suppliers::where('supplierID', $id)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$supplier) {
            return abort(404);
        }

        $service = SupplierService::where('supplier_id', $supplier->supplierID)->where('id', $request->service_id)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$service) {
            return abort(404);
        }

        $filename = $service->document;

        DB::beginTransaction();

        if ($request->hasFile('document')) {
            // cache the file
            $file = $request->file('document');

            // generate a new filename. getClientOriginalExtension() for the file extension
            $filename = 'service-file-' . time() . '.' . $file->getClientOriginalExtension();

            // save to storage/app/{owner_id}/service as the new $filename
            $file->move( storage_path('app/'.CNF_OWNER.'/service'), $filename );

            $path = storage_path('app/'.CNF_OWNER.'/service/'.$service->document);
            unlink($path);
        }

        $service->name = $request->name;
        $service->description = $request->description;
        $service->start_date = $request->start_date;
        $service->end_date = $request->end_date;
        $service->price = $request->price;
        $service->min_quantity = $request->min_quantity;
        $service->max_quantity = $request->max_quantity;
        $service->document = $filename;
        $service->supplier_id = $id;
        $service->status = $request->status;
        $service->entry_by = $user->id;
        $service->save();

        // $service = SupplierService::create($data);

        DB::commit();

        return redirect('/suppliers/show2/'.$id)->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
    }

    public function getDeleteservice(Request $request, $id)
    {
        $service = SupplierService::where('id', $request->an_id)->where('supplier_id', $id)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$service) {
            return abort(404);
        }

        DB::beginTransaction();

        if ($service->document) {
            $path = storage_path('app/'.CNF_OWNER.'/service/'.$service->document);
            unlink($path);
        }

        $service->delete();

        DB::commit();

        return redirect('/suppliers/show2/'.$id)->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
    }
}
