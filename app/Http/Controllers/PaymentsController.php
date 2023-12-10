<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Owners;
use App\Models\InvoicePaymentMethod;
use App\Models\PaymentVoid;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;
use Mail;
use Auth;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public $module          = 'payments';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Payments();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'payments',
            'pageUrl'    => url('payments'),
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex()
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $this->data['access'] = $this->access;

        return view('payments.index', $this->data);
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
        $pagination->setPath('payments/data');

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
        return view('payments.table', $this->data);
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
                $this->data['row'] = $this->model->getColumnTable('invoice_payments');
            } else {
                return Redirect::to('payments')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $this->data['setting'] = $this->info['setting'];
        $this->data['fields']  = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('payments.form', $this->data);
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
                $data['html'] = view('payments.view', $this->data)->render();

                return view('layouts.blank', $data);
            } else {
                return view('payments.view', $this->data);
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
        foreach (\DB::select('SHOW COLUMNS FROM invoice_payments ') as $column) {
            if ('invoicePaymentID' != $column->Field) {
                $columns[] = $column->Field;
            }
        }
        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));

            $sql = 'INSERT INTO invoice_payments (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM invoice_payments WHERE invoicePaymentID IN (' . $toCopy . ')';
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
        $user = Auth::user();
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('invoice_payments');

            $id = $this->model->insertRow($data, $request->input('invoicePaymentID'));
            $payment = Payments::find($id);
            $payment->entry_by = $user->id;
            $payment->save();

            Mail::send('invoice.paymentmail', ['payment' => $payment], function ($message) use ($payment) {
                $message->to($payment->traveller->email, $payment->traveller->Fullname)->subject('Pembayaran Untuk Booking '.$payment->invoice->booking->bookingno);
                $owner = Owners::find(CNF_OWNER);
                $admins = $owner->admins;
                foreach ($admins as $key => $admin) {
                    $message->cc($admin->email);
                }
                $message->from(CNF_EMAIL, CNF_COMNAME);
            });

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
        if (count($request->input('ids')) >= 1) {

            $paymentGateway = json_decode(CNF_PAYMENT_GATEWAY_DATA);
            $paymentGatewayGPN = $paymentGateway->gpn;

            $ids = $request->input('ids');

            if(count($ids)) {
                foreach($ids as $id) {

                    $payment = Payments::where('invoicePaymentID', $id)->first();
                    $getInvoicePaymentMethod = InvoicePaymentMethod::where('invoiceID', $payment->invoiceID)->first();

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
                                //     'merReference' => '20201223_121708'
                                //     'txnAmount' => 550000.00
                                //     'txnDescription' => 'Online Booking'
                                //     'tfpMerchantName' => 'DGA'
                                // ];

                                $payload = [
                                    'txnType' => 2,
                                    'authRequest' => $authRequest,
                                    // 'authRequest' => (array)$getPaymentMethod->authRequest,
                                    'txnRequest' => (array)$getPaymentMethod->txnRequest
                                ];

                                $headerJson = json_encode($header);
                                $payloadJson = json_encode($payload);
                                $signature = hash_hmac('sha256', $this->base64URLEncode($headerJson) . '.' . $this->base64URLEncode($payloadJson), $paymentGatewayGPN->secret_key, true);
                                $txnData = $this->base64URLEncode($headerJson) . '.' . $this->base64URLEncode($payloadJson) . '.' . $this->base64URLEncode($signature);

                                // dev endpoint
                                // $paymentRegisterURL = 'https://bpgdev.bersama.id/ArtajasaPG/payment/api/transaction/void';
                                $paymentRegisterURL = 'https://bpg.bersama.id/ArtajasaPG/payment/api/transaction/void';

                                $httpClient = new HttpClient();
                                $voidPayment = $httpClient->post($paymentRegisterURL, [
                                    'body' => $txnData
                                ]);
                                $voidPaymentResposeJson = explode('.', $voidPayment->getBody());
                                $voidPaymentRespose = json_decode($this->base64URLDecode($voidPaymentResposeJson[1]));

                                $paymentVoid = new PaymentVoid;
                                $paymentVoid->invoiceID = $getInvoicePaymentMethod->invoiceID;
                                $paymentVoid->owner_id = $getInvoicePaymentMethod->owner_id;
                                $paymentVoid->status = $voidPaymentRespose->status;
                                $paymentVoid->message = $voidPaymentRespose->description;
                                $paymentVoid->response = $this->base64URLDecode($voidPaymentResposeJson[1]);
                                $paymentVoid->save();

                                if($voidPaymentRespose->status == 'Completed') {
                                    $this->model->destroy($id);
                                }
                            } catch (\Exception $e) {
                                // Log::debug($e->getMessage());
                                // echo $e->getMessage();
                            }
                        } else {
                            $this->model->destroy($id);
                        }
                    } else {
                        $this->model->destroy($id);
                    }
                }
            }

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

        /*
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
        */
    }

    public function getDelete($id)
    {
        if (0 == $this->access['is_remove']) {
            return redirect()->back()->with('error', "You're not allowed to do this action");
        }

        $payment = Payments::where('owner_id', CNF_OWNER)->where('invoicePaymentID', $id)->get()->first();

        if (!$payment) {
            abort(404);
        }

        $payment->delete();

        return redirect()->back()->with('success', 'Payment deleted');
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Payments();
        $info  = $model::makeInfo('payments');

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

                return view('payments.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'invoicePaymentID',
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

            return view('payments.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('invoice_payments');
            $this->model->insertRow($data, $request->input('invoicePaymentID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function base64URLEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    }

    public function base64URLDecode($data) { 
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
    } 
}
