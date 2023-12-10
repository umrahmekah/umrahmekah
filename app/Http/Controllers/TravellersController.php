<?php

namespace App\Http\Controllers;

use App\Models\Travellers;
use App\Models\Countries;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Validator;
use DB, PDF;
use Response;

class TravellersController extends Controller
{
    public $module          = 'travellers';
    public static $per_page = '10000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Travellers();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
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

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'travellerID');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
        // End Filter sort and order for query
        // Filter Search for query
        $filter = '';
        if (! is_null($request->input('search'))) {
            $search                   = $this->buildSearch('maps');
            $filter                   = $search['param'];
            $this->data['search_map'] = $search['maps'];
        }

        $page   = $request->input('pages', 1);
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

        $ids = [];
        foreach ($results['rows'] as $key => $row) {
            $ids[] = $row->travellerID;
        }

        $search = $request->search;

        $travellers = Travellers::whereIn('travellerID', $ids)->orderBy('travellerID', 'desc');

        if ($search) {
            $travellers = $travellers->where(function ($queryy) use ($search) {
                return $queryy->where('nameandsurname', 'LIKE', '%'.$search.'%')
                                ->orWhere('last_name', 'LIKE', '%'.$search.'%')
                                ->orWhere('email', 'LIKE', '%'.$search.'%')
                                ->orWhere('phone', 'LIKE', '%'.$search.'%')
                                ->orWhere('NRIC', 'LIKE', '%'.$search.'%');
            });
        }

        $travellers = $travellers->paginate(25);

        // // Build pagination setting
        // $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        // $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        // $pagination->setPath('travellers');

        $this->data['rowData'] = $results['rows'];
        $this->data['travellers'] = $travellers;
        // Build Pagination
        // $this->data['pagination'] = $pagination;
        // Build pager number and append current param GET
        // $this->data['pager'] = $this->injectPaginate();
        // Row grid Number
        $this->data['i'] = (($request->page ?? 1) * 25) - 25;
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
        return view('travellers.index', $this->data);
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
        //dd($row);
        if ($row) {
            $this->data['row']         = $row;
            $mahram_list               = \DB::table('travellers')->select('travellerID', 'nameandsurname')->where('owner_id', CNF_OWNER)->where('entry_by', $row->entry_by)->get();
            $this->data['mahram_list'] = $mahram_list;
            $this->data['traveller'] = Travellers::find($row['travellerID']);
        } else {
            $this->data['row'] = $this->model->getColumnTable('travellers');
            $mahram_list       = \DB::table('travellers')->select('travellerID', 'nameandsurname')->where('owner_id', CNF_OWNER)->where('entry_by', Auth::user()->id)->get();
            //dd($mahram_list);
            $this->data['mahram_list'] = $mahram_list;
            $this->data['traveller'] = new Travellers;
        }
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('travellers.form', $this->data);
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
            
            $nationality = \DB::table('def_country')->select('country_name')->where('countryID', $row->nationality)->first();
            $this->data['nationality'] = $nationality;
            
            $travellerCountry = \DB::table('def_country')->select('country_name')->where('countryID', $row->countryID)->first();
            $this->data['travellerCountry'] = $travellerCountry;
            
            $passportCountry = \DB::table('def_country')->select('country_name')->where('countryID', $row->passportcountry)->first();
            $this->data['passportCountry'] = $passportCountry;
            
            $mahram = \DB::table('travellers')->select('nameandsurname')->where('travellerID', $row->mahram_id)->first();

            if ($mahram) {
                $this->data['mahram'] = $mahram;
            }

            $mahram_list = \DB::table('travellers')->select('travellerID', 'nameandsurname', 'mahram_relation')->where('mahram_id', $row->travellerID)->get();

            $this->data['mahram_list'] = $mahram_list;
//            dd($this->data['mahram_list']);
            $bookingdetail = \DB::table('bookings')->where('travellerID', $id)->orderBy('bookingsID', 'ASC')->get();
            $book          = [];
            $first         = 0;
            foreach ($bookingdetail as $bd) {
                $book[] = [
                'bookingsID'    => $bd->bookingsID,
                'bookingno'     => $bd->bookingno,
                'travellerID'   => $bd->travellerID,
                'tour'          => $bd->tour,
                'hotel'         => $bd->hotel,
                'flight'        => $bd->flight,
                'car'           => $bd->car,
                'extraservices' => $bd->extraservices,
                'updated_at'    => $bd->updated_at,
                'created_at'    => $bd->created_at,
                'entry_by'      => $bd->entry_by,
            ];
                ++$first;
            }
            $this->data['book'] = $book;

            $payments = \DB::table('invoice_payments')->where('travellerID', $id)->orderBy('payment_date', 'ASC')->get();
            $pay      = [];
            $second   = 0;
            foreach ($payments as $pt) {
                $pay[] = [
                'invoicePaymentID' => $pt->invoicePaymentID,
                'travellerID'      => $pt->travellerID,
                'invoiceID'        => $pt->invoiceID,
                'amount'           => $pt->amount,
                'currency'         => $pt->currency,
                'payment_type'     => $pt->payment_type,
                'payment_date'     => $pt->payment_date,
                'notes'            => $pt->notes,
                'updated_at'       => $pt->updated_at,
                'created_at'       => $pt->created_at,
                'entry_by'         => $pt->entry_by,
            ];
                ++$second;
            }
            $this->data['pay'] = $pay;

            $travellersnotes = \DB::table('travellers_note')->where('travellerID', $id)->orderBy('created_at', 'ASC')->get();
            $tnotes          = [];
            $third           = 0;
            foreach ($travellersnotes as $tn) {
                $tnotes[] = [
                'travellers_noteID' => $tn->travellers_noteID,
                'travellerID'       => $tn->travellerID,
                'title'             => $tn->title,
                'note'              => $tn->note,
                'style'             => $tn->style,
                'updated_at'        => $tn->updated_at,
                'created_at'        => $tn->created_at,
                'entry_by'          => $tn->entry_by,
            ];
                ++$third;
            }
            $this->data['tnotes'] = $tnotes;

            $invoices = \DB::table('invoice')->where('travellerID', $id)->orderBy('DateIssued', 'ASC')->get();
            $invo     = [];
            $fourth   = 0;
            foreach ($invoices as $in) {
                $invo[] = [
                'invoiceID'   => $in->invoiceID,
                'status'      => $in->status,
                'travellerID' => $in->travellerID,
                'bookingID'   => $in->bookingID,
                'InvTotal'    => $in->InvTotal,
                'currency'    => $in->currency,
                'DateIssued'  => $in->DateIssued,
                'DueDate'     => $in->DueDate,
            ];
                ++$fourth;
            }
            $this->data['invo'] = $invo;

            $files = \DB::table('travellers_files')->where('travellerID', $id)->orderBy('fileID', 'ASC')->get();
            $file  = [];
            $fifth = 0;
            foreach ($files as $fl) {
                $file[] = [
                'fileID'      => $fl->fileID,
                'travellerID' => $fl->travellerID,
                'file_type'   => $fl->file_type,
                'file'        => $fl->file,
                'remarks'     => $fl->remarks,
                'created_at'  => $fl->created_at,
                'updated_at'  => $fl->updated_at,
            ];
                ++$fifth;
            }
            $this->data['file'] = $file;

            return view('travellers.view', $this->data);
        } else {
            return Redirect::to('travellers')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }
    
    public function getPdf(Request $request, $id)
    {   $traveller               = Travellers::join('def_country', 'def_country.countryID', '=', 'travellers.countryID')
                                   ->where('travellerID', $id)->get()->first();
        $this->data['traveller'] = $traveller;
     
        $nationality               = Travellers::join('def_country', 'def_country.countryID', '=', 'travellers.nationality')
                                   ->where('travellerID', $id)->get()->first();
        $this->data['nationality'] = $nationality;
     
        $passportCountry = Travellers::join('def_country', 'def_country.countryID', '=', 'travellers.passportcountry')
                                   ->where('travellerID', $id)->get()->first();
        $this->data['passportCountry'] = $passportCountry;
     
        $mahram = \DB::table('travellers')->select('nameandsurname')->where('travellerID', $traveller->mahram_id)->first();

            if ($mahram) {
                $this->data['mahram'] = $mahram;
            }
     
        $pdf = PDF::loadView('travellers.travellerpdf', $this->data);
        
        return $pdf->stream($this->data['traveller']->$id.'.pdf');
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM travellers ') as $column) {
            if ('travellerID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO travellers (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM travellers WHERE travellerID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return Redirect::to('travellers')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('travellers')->with('messagetext', \Lang::get('core.note_selectrow'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data              = $this->validatePost('tb_travellers');
            $data['last_name'] = $request->last_name;
            $data['gender']    = $request->gender;

            $id = $this->model->insertRow($data, $request->input('travellerID'));

            $traveller = Travellers::find($id);
            $traveller->nric = $request->nric;
            $traveller->nationality = $request->nationality;
            $traveller->passport_place_made = $request->passport_place_made;
            $traveller->save();

            if (! is_null($request->input('apply'))) {
                $return = 'travellers/update/' . $id . '?return=' . self::returnUrl();
            } elseif (! is_null($request->input('applynew'))) {
                $return = 'travellers/update/?return=';
            } else {
                $return = 'travellers?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('travellerID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('travellers/update/' . $request->input('travellerID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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
            return Redirect::to('travellers')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('travellers')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Travellers();
        $info  = $model::makeInfo('travellers');

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

                return view('travellers.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'travellerID',
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

            return view('travellers.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('travellers');
            $this->model->insertRow($data, $request->input('travellerID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getNotedelete(Request $request, $travellerID, $travellers_noteID = 0)
    {
        \DB::table('travellers_note')->where('travellers_noteID', $travellers_noteID)->delete();

        return Redirect::to('travellers/show/' . $travellerID)
                ->with('messagetext', 'Note has been deleted !')->with('msgstatus', 'success');
    }

    public function getFiledelete(Request $request, $travellerID, $fileID = 0)
    {
        \DB::table('travellers_files')->where('fileID', $fileID)->delete();

        return Redirect::to('travellers/show/' . $travellerID)
                ->with('messagetext', 'File has been deleted !')->with('msgstatus', 'success');
    }

    public function postSavefromcsv(Request $request)
    {
        $this->validate($request, [
          'traveller_lists' => 'required|mimes:csv,txt',
        ]);

        $user = Auth::user();

        if ($request->hasFile('traveller_lists')) {
            DB::transaction(function () use ($request, $user) {

                $file = $request->file('traveller_lists');

                $travellersArr = $this->csvToArray($file);

                // dd($travellersArr);

                for ($i = 0; $i < count($travellersArr); ++$i) {
                    $traveller = Travellers::where('owner_id', CNF_OWNER)->where('NRIC', $travellersArr[$i]['nric'])->get()->first();
                    if (!$traveller) {
                        $traveller = new Travellers;
                        $traveller->NRIC = $travellersArr[$i]['nric'];
                        $traveller->entry_by = $user->id;
                        $traveller->owner_id = CNF_OWNER;
                    }
                    
                    if ($travellersArr[$i]['first_name']) {
                        $traveller->nameandsurname = $travellersArr[$i]['first_name'];
                    }
                    if ($travellersArr[$i]['last_name']) {
                        $traveller->last_name = $travellersArr[$i]['last_name'];
                    }
                    if ($travellersArr[$i]['gender']) {
                        $traveller->gender = $travellersArr[$i]['gender'];
                    }
                    if ($travellersArr[$i]['email']) {
                        $traveller->email = $travellersArr[$i]['email'];
                    }
                    if ($travellersArr[$i]['date_of_birth']) {
                        $date = explode('/', $travellersArr[$i]['date_of_birth']);
                        $traveller->dateofbirth = $date[2].'-'.$date[1].'-'.$date[0];
                    }
                    if ($travellersArr[$i]['phone']) {
                        $traveller->phone = $travellersArr[$i]['phone'];
                    }
                    if ($travellersArr[$i]['address']) {
                        $traveller->address = $travellersArr[$i]['address'];
                    }
                    if ($travellersArr[$i]['state']) {
                        $traveller->city = $travellersArr[$i]['state'];
                    }
                    $country = Countries::where('country_name', 'LIKE', '%'.$travellersArr[$i]['country'].'%')->get()->first();
                    if ($country) {
                        $traveller->countryID = $country->countryID;
                    }
                    $nationality = Countries::where('country_name', 'LIKE', '%'.$travellersArr[$i]['nationality'].'%')->get()->first();
                    if ($nationality) {
                        $traveller->nationality = $nationality->countryID;
                    }
                    
                    if ($travellersArr[$i]['passport_no']) {
                        $traveller->passportno = $travellersArr[$i]['passport_no'];
                    }
                    if ($travellersArr[$i]['passport_issue_date']) {
                        $date = explode('/', $travellersArr[$i]['passport_issue_date']);
                        $traveller->passportissue = $date[2].'-'.$date[1].'-'.$date[0];
                    }
                    if ($travellersArr[$i]['passport_expiry_date']) {
                        $date = explode('/', $travellersArr[$i]['passport_expiry_date']);
                        $traveller->passportexpiry = $date[2].'-'.$date[1].'-'.$date[0];
                    }
                    $country = Countries::where('country_name', 'LIKE', '%'.$travellersArr[$i]['passport_country'].'%')->get()->first();
                    if ($country) {
                        $traveller->passportcountry = $country->countryID;
                    }
                    if ($travellersArr[$i]['passport_place_issue']) {
                        $traveller->passport_place_made = $travellersArr[$i]['passport_place_issue'];
                    }

                    // dd($traveller);

                    $traveller->save();
                }
            });

            $return = 'travellers';

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return redirect()->back()->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error');
        }
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            return false;
        }

        $header = null;
        $data   = [];
        ini_set('auto_detect_line_endings', true);
        if (false !== ($handle = fopen($filename, 'r'))) {
            while (false !== ($row = fgetcsv($handle, 1000, $delimiter))) {
                if (! $header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        ini_set('auto_detect_line_endings', false);

        return $data;
    }

    public function getDownloadtemplate()
    {
        $file    = public_path() . '/traveller_list_template.xlsx';
        $headers = ['Content-Type: application/xlsx'];

        return Response::download($file, 'traveller_list_template.xlsx', $headers);
    }
    
}
