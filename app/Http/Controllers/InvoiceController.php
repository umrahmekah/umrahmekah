<?php

namespace App\Http\Controllers;

use App;
use App\Models\Createbooking;
use App\Models\Invoice;
use App\Models\Payments;
use App\Models\Tourdates;
use App\Models\Owners;
use App\Models\InvoicePaymentMethod;
use App\Models\Travellers;
use App\User;
use Auth;
use Cyvelnet\LaravelBillplz\Facades\Billplz;
use Cyvelnet\LaravelBillplz\Messages\BillMessage;
use DB;
use Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Mail;
use PDF;
use Redirect;
use Response;
use Validator;
use ZipArchive;
use Billplz\Client;
use Laravie\Codex\Discovery;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;


class InvoiceController extends Controller
{
    public $module          = 'invoice';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Invoice();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'invoice',
            'return'     => self::returnUrl(),
        ];

        $this->http = Discovery::client();

        if (CNF_BILLPLZAPIKEY) {
            $this->billplz = new Client($this->http, CNF_BILLPLZAPIKEY, CNF_BILLPLZSIGNATUREKEY);
            if (env('APP_DEBUG')) {
                $this->billplz->useSandbox();
            }
        }

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
    }

    public function getIndex(Request $request)
    {
        $tourdatefilter = session()->get('tourdatefilter');

        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'invoiceID');
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

        $anotherrow = [];

        foreach ($results['rows'] as $key => $row) {
            $row->booking = Createbooking::find($row->bookingID);
            if ($tourdatefilter) {
                if ($row->booking && $row->booking->bookTour && $row->booking->bookTour->tourdate && $row->booking->bookTour->tourdate->tourdateID == $tourdatefilter) {
                    $anotherrow[] = $row;
                }
            } else {
                $anotherrow[] = $row;
            }
        }

        $this->data['tourdates'] = Tourdates::where('owner_id', CNF_OWNER)->where('start', '>=', Carbon::today())->where('type', 1)->where('status', 1)->get();

        // dd($results);

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($anotherrow, count($anotherrow), $params['limit']);
        $pagination->setPath('invoice');

        $this->data['rowData'] = $anotherrow;
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
        return view('invoice.index', $this->data);
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
                $this->data['row'] = $this->model->getColumnTable('invoice');
            } else {
                return Redirect::to('invoice')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['items'] = \DB::table('invoice_products')->where('InvID', $this->data['row']['invoiceID'])->get();

        $this->data['id'] = $id;

        return view('invoice.form', $this->data);
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
            $this->data['invoice']  = Invoice::find($id);
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['items']    = \DB::table('invoice_products')->where('InvID', $id)->get();
            $this->data['prevnext'] = $this->model->prevNext($id);
            $this->data['traveller']= Travellers::find($row->travellerID);
            $this->data['booking']  = Createbooking::find($row->bookingID);
            $this->data['gpn'] = [];

            if (! is_null($request->input('pdf'))) {
                // $pdf  = App::make('dompdf.wrapper');
                // $html = view('invoice.pdf', $this->data)->render();
                // $pdf  = PDF::setOptions(['dpi' => 100, 'defaultFont' => 'sans-serif']);
                // $pdf->loadHTML($html);
                $pdf = PDF::loadView('invoice.pdf', $this->data);

                return $pdf->stream();
            }

            $paymentGateway = json_decode(CNF_PAYMENT_GATEWAY_DATA);
            $paymentGatewayGPN = $paymentGateway->gpn;
            $getInvoicePaymentMethod = InvoicePaymentMethod::where('invoiceID', $id)->first();

            if($getInvoicePaymentMethod && $getInvoicePaymentMethod->payment_status == 'completed') {

                $getPaymentMethod = json_decode($getInvoicePaymentMethod->meta);

                if($getPaymentMethod->payment_method == 'gpn') {
                    try {
                        $header = [
                            'typ' => 'JWT',
                            'alg' => 'HS256',
                        ];

                        $authRequest = [
                            'jti' => date('Ymd_His'),
                            'iss' => $paymentGatewayGPN->man,
                            'aud' => url('/bookpackage/void'),
                            'iat' => strtotime(date('Y-m-d H:i:s')),
                        ];

                        // $txnRequest = [
                        //     'merReference' => '20201224_104132',
                        //     'txnAmount' => '1000000.00',
                        //     'txnDescription' => 'Online Booking',
                        //     'tfpMerchantName' => 'DGA'
                        // ];

                        $payload = [
                            'txnType' => 2,
                            'authRequest' => $authRequest,
                            'txnRequest' => (array)$getPaymentMethod->txnRequest
                            // 'txnRequest' => $txnRequest
                        ];

                        $headerJson = json_encode($header);
                        $payloadJson = json_encode($payload);
                        $signature = hash_hmac('sha256', $this->base64URLEncode($headerJson) . '.' . $this->base64URLEncode($payloadJson), $paymentGatewayGPN->secret_key, true);
                        $txnData = $this->base64URLEncode($headerJson) . '.' . $this->base64URLEncode($payloadJson) . '.' . $this->base64URLEncode($signature);

                        // $paymentRegisterURL = 'https://bpgdev.bersama.id/ArtajasaPG/payment/api/transaction/query';
                        $paymentRegisterURL = 'https://bpg.bersama.id/ArtajasaPG/payment/api/transaction/query';

                        $httpClient = new HttpClient();
                        $voidPayment = $httpClient->post($paymentRegisterURL, [
                            'body' => $txnData
                        ]);
                        $paymentQueryResponseJson = explode('.', $voidPayment->getBody());
                        $paymentQueryResponse = json_decode($this->base64URLDecode($paymentQueryResponseJson[1]));

                        // dd($paymentQueryResponse);
                        if($paymentQueryResponse->status == 'Completed') {
                            $this->data['gpn'] = $paymentQueryResponse;
                        }
                        
                    } catch (\Exception $e) {
                        // Log::debug($e->getMessage());
                        // echo $e->getMessage();
                    }
                }
            }
            
            return view('invoice.view', $this->data);
        } else {
            return Redirect::to('invoice')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM invoice ') as $column) {
            if ('invoiceID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO invoice (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM invoice WHERE invoiceID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return Redirect::to('invoice')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('invoice')->with('messagetext', \Lang::get('core.note_selectrow'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_invoice');

            $id = $this->model->insertRow($data, $request->input('invoiceID'));

            // Subt Item Save
            if (isset($_POST['Items'])) {
                \DB::table('invoice_products')->where('InvID', $id)->delete();
                $Items = $_POST['Items'];
                for ($i = 0; $i < count($Items); ++$i) {
                    $dataItems = [
                        'Code'   => $_POST['Code'][$i],
                        'Items'  => $_POST['Items'][$i],
                        'Qty'    => $_POST['Qty'][$i],
                        'Amount' => $_POST['Amount'][$i],
                        'InvID'  => $id,
                    ];

                    \DB::table('invoice_products')->insert($dataItems);
                }
            }

            if (! is_null($request->input('apply'))) {
                $return = 'invoice/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'invoice?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('invoiceID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('invoice/update/' . $request->input('invoiceID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
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
            \DB::table('invoice_products')->whereIn('InvID', $request->input('ids'))->delete();

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfully');
            // redirect
            return Redirect::to('invoice')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('invoice')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Invoice();
        $info  = $model::makeInfo('invoice');

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

                return view('invoice.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'invoiceID',
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

            return view('invoice.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('invoice');
            $this->model->insertRow($data, $request->input('invoiceID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getPayment()
    {
        $this->data['invoiceID'] = request('invoiceid');
        $this->data['amount']    = request('amount');
        $this->data['paid']      = request('paid');
        $this->data['balance']   = request('balance');
        $this->data['setting']   = $this->info['setting'];
        $this->data['fields']    = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['payment_type'] = DB::table('def_payment_types')->get();

        return view('invoice.payment', $this->data);
    }

    public function getHistory()
    {
        $this->data['invoiceID'] = request('invoiceid');
        $this->data['payments']  = Payments::where('invoiceID', request('invoiceid'))->get();
        $this->data['setting']   = $this->info['setting'];
        $this->data['fields']    = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['payment_type'] = DB::table('def_payment_types')->get();

        return view('invoice.history', $this->data);
    }

    public function postPay()
    {
        $data['amount'] = request('payment_amount');
        $data['id']     = request('invoiceID');

        $bill = $this->billplz->bill();

        $url = 'http://' . CNF_DOMAIN;

        // $resource = Billplz::issue(function (BillMessage $bill) use ($data) {
        //     $bill->to(Auth::User()->first_name, Auth::User()->email)
        //         ->amount($data['amount']) // will multiply with 100 automatically, so a RM500 bill, you just pass 500 instead of 50000
        //         ->callbackUrl('http://' . CNF_DOMAIN)
        //         ->redirectUrl('http://' . CNF_DOMAIN . '/bookpackage/bookingpay')
        //         ->description('Booking Payment')
        //         ->reference1($data['id']);
        // });

        $response = $bill->create(
            CNF_BILLPLZCOLLECTIONID,
            Auth::User()->email,
            null,
            Auth::User()->first_name,
            $data['amount'] * 100,
            ['callback_url' => $url, 'redirect_url' => 'http://' . CNF_DOMAIN . '/bookpackage/bookingpay'],
            'Booking Payment',
            ['reference_1' => $data['id'], 'reference_1_label' => 'Booking ID']
        );

        $array = $response->toArray();

        return redirect($array['url']);
    }

    public function postSubmitpay(Request $request)
    {
        DB::beginTransaction();

        $file = $request->file('file');
        if ($file) {
            $file->move(public_path('/uploads/files/' . CNF_OWNER), time() . $file->getClientOriginalName());
        }
        $payment                = new Payments();
        $payment->travellerID   = Invoice::find(request('invoiceID'))->travellerID;
        $payment->invoiceID     = request('invoiceID');
        $payment->amount        = request('payment_amount');
        $payment->currency      = 125;
        $payment->payment_type  = request('payment_type');
        $payment->payment_date  = request('payment_date');
        $payment->payment_prove = request('payment_prove');
        $payment->notes         = 'Online Payment';
        $payment->entry_by      = Auth::User()->id;
        if ($file) {
            $payment->file          = $file->time() . getClientOriginalName();
        }
        if (request('received')) {
            $payment->received = 1;
        }
        $payment->owner_id = CNF_OWNER;
        $payment->save();

        $traveller = $payment->traveller;

        Mail::send('invoice.paymentmail', ['payment' => $payment], function ($message) use ($payment) {
            $message->to($payment->traveller->email, $payment->traveller->Fullname)->subject('Pembayaran Untuk Booking '.$payment->invoice->booking->bookingno);
            $owner = Owners::find(CNF_OWNER);
            $admins = $owner->admins;
            foreach ($admins as $key => $admin) {
                $message->cc($admin->email);
            }
            $message->from(CNF_EMAIL, CNF_COMNAME);
        });

        DB::commit();

        return back()->with('submit_payment_message', 'Payment have been submitted');
    }

    public function getReceiptmail()
    {
        $user = User::find(Auth::user()->id);

        $payment = Payments::find(request('payment_id'));

        $invoice = Invoice::find($payment->invoiceID);
        $booking = Createbooking::find($invoice->bookingID);

        $data['name']    = CNF_COMNAME;
        $data['payment'] = $payment;
        $data['user']    = $user;
        $data['address'] = CNF_ADDRESS;
        $data['email']   = CNF_EMAIL;
        $data['booking'] = $booking;

        $name = 'Receipt' . time() . '.pdf';

        $pdf = PDF::loadView('invoice.receiptmail', $data);
        $pdf->save(public_path(sprintf('%s%s', 'uploads/', $name)));

        Mail::send('invoice.receiptmail', $data, function ($message) use ($user,$name) {
            $message->to($user['email'], $user['first_name'])->subject('Resit Bayaran');
            $message->from('salam@oomrah.com', 'Oomrah');
            $message->attach(public_path(sprintf('%s%s', 'uploads/', $name)));
        });

        unlink(public_path(sprintf('%s%s', 'uploads/', $name)));

        return back();
    }

    public function getDownloadfile()
    {
        $zipname = time() . 'file.zip';
        $zip     = new ZipArchive();
        $zip->open($zipname, ZipArchive::CREATE);
        $zip->addFile(public_path() . '/uploads/files/' . CNF_OWNER . '/' . request('file'));
        $zip->close();

        return Response::download(public_path() . '\\' . $zipname, $zipname, ['Content-Type: application/octet-stream', 'Content-Length: ' . filesize(public_path() . '\\' . $zipname)])->deleteFileAfterSend(true);
    }

    public function postSetdiscount(Request $request)
    {
        $user = Auth::user();
        $invoice = Invoice::where('owner_id', CNF_OWNER)->where('invoiceID', $request->invoice_id)->get()->first();

        if (!$invoice) {
            abort(404);
        }

        $booking = $invoice->booking;

        $discountTotal = $request->discount * $booking->pax;

        $invoice->discount = $request->discount;
        $invoice->InvTotal -= $discountTotal;
        $invoice->discount_by = $user->id;
        $invoice->discount_at = Carbon::now();
        $invoice->save();

        return redirect('invoice/show/'.$request->booking_id);
    }

    public function base64URLEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    }

    public function base64URLDecode($data) { 
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    }
}
