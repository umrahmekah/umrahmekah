<?php

namespace App\Http\Controllers;

use App\Models\Bookroom;
use App\Models\Booktour;
use App\Models\Core\Users;
use App\Models\Countries;
use App\Models\Createbooking;
use App\Models\Invoice;
use App\Models\InvoicePaymentMethod;
use App\Models\Payments;
use App\Models\Tourdates;
use App\Models\Tours;
use App\Models\Travellers;
use App\User;
use Carbon;
use Cyvelnet\LaravelBillplz\Facades\Billplz;
use Cyvelnet\LaravelBillplz\Messages\BillMessage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Billplz\Client;
use Laravie\Codex\Discovery;
use Mail;
use Redirect;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as HttpClient;

class PackageBookingController extends Controller
{
    public $module          = 'travellers';
    public static $per_page = '10000';
    protected $layout       = 'layouts.main';
    protected $data         = [];
    protected $payment_gateway_id;
    protected $payment_gateway_data;

    public function __construct()
    {
        $this->payment_gateway_id = CNF_PAYMENT_GATEWAY_ID;
        $this->payment_gateway_data = json_decode(CNF_PAYMENT_GATEWAY_DATA);

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

        $this->client = Discovery::client();

        // if (CNF_BILLPLZAPIKEY) {
        //     $this->billplz = new Client($this->client, CNF_BILLPLZAPIKEY, CNF_BILLPLZSIGNATUREKEY);

        //     if (env('APP_DEBUG')) {
        //         $this->billplz->useSandbox();
        //     }
        // }

        if($this->payment_gateway_id == 1) {
            if (CNF_BILLPLZAPIKEY) {
                $this->billplz = new Client($this->client, CNF_BILLPLZAPIKEY, CNF_BILLPLZSIGNATUREKEY);

                if (env('APP_DEBUG')) {
                    $this->billplz->useSandbox();
                }
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
        $tour_list = \DB::table('tours')->get();
        $category  = \DB::table('def_tour_categories')
            ->join('tours', 'tours.tourcategoriesID', '=', 'def_tour_categories.tourcategoriesID')
            ->where('tours.status', '=', '1')
            ->where('tours.owner_id', '=', CNF_OWNER)
            ->groupBy('def_tour_categories.tourcategoriesID')
            ->get(['def_tour_categories.*', \DB::raw('count(*) as category_count')]);
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Tours();
        $info  = $model::makeInfo('tours');
        $data  = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];
        if ('view' == $mode) {
            $id = $_GET['view'];
            \DB::table('tours')->where('tourID', $_GET['view'])->update(['views' => \DB::raw('views+1')]);
            $tourdetail = \DB::table('tour_detail')->where('tourID', $id)->orderBy('day', 'ASC')->get();
            $tourdate   = \DB::table('tour_date')->where('tourID', $id)->where('status', '1')->orderBy('start', 'ASC')->get();
            $tourdate = Tourdates::where('tourID', $id)->where('status', 1)->orderBy('start', 'ASC')->get();
            $dayTree    = [];
            $first      = 0;
            $tourName   = '';
            foreach ($tourdetail as $td) {
                $dayTree[] = [
                    'tourdetailID'   => $td->tourdetailID,
                    'title'          => $td->title,
                    'day'            => $td->day,
                    'countryID'      => $td->countryID,
                    'cityID'         => $td->cityID,
                    'hotelID'        => $td->hotelID,
                    'siteID'         => $td->siteID,
                    'meal'           => $td->meal,
                    'optionaltourID' => $td->optionaltourID,
                    'description'    => $td->description,
                    'icon'           => $td->icon,
                    'image'          => $td->image,
                ];
                $tourName = $td->title;
                ++$first;
            }
            $data['dayTree'] = $dayTree;
            $tdate           = [];
            $sec             = 0;
            foreach ($tourdate as $trd) {
                $tdate[] = [
                    'tourdateID'         => $trd->tourdateID,
                    'tourID'             => $trd->tourID,
                    'tour_code'          => $trd->tour_code,
                    'start'              => $trd->start,
                    'end'                => $trd->end,
                    'featured'           => $trd->featured,
                    'definite_departure' => $trd->definite_departure,
                    'total_capacity'     => $trd->total_capacity,
                    'cost_single'        => $trd->cost_single,
                    'cost_double'        => $trd->cost_double,
                    'cost_triple'        => $trd->cost_triple,
                    'cost_quad'          => $trd->cost_quad,
                    'cost_child'         => $trd->cost_child,
                    'currencyID'         => $trd->currencyID,
                    'status'             => $trd->status,
                    'tourdate'           => $trd,
                    'discount'           => $trd->discount
                ];
                ++$sec;
            }
            $data['tdate'] = $tdate;
            Log::info($tdate);
            $row = $model::getRow($id);
            if ($row) {
                $data['pageTitle'] = $row->tour_name;
                $data['row']       = $row;
                $data['fields']    = \SiteHelpers::fieldLang($info['config']['grid']);
                $data['id']        = $id;

                return view('layouts.' . CNF_THEME . '.tour.view', $data);
            }
        } else {
            $sort   = ((isset($_GET['sort'])) ? $_GET['sort'] : 'tourID');
            $order  = ((isset($_GET['order'])) ? $_GET['order'] : 'asc');
            $filter = '';
            if (isset($_GET['search'])) {
                //			$search = $buildSearch('maps');
//			$filter = $search['param'];
//			$data['search_map'] = $search['maps'];
            }
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['limit'])) ? $_GET['limit'] : '10',
                'sort'   => $sort,
                'order'  => $order,
                'params' => (isset($_GET['cat']) ? 'AND tourcategoriesID =' . $_GET['cat'] : ''),
                'global' => 1,
            ];
            $result            = $model::getRows($params);
            $data['tableGrid'] = $info['config']['grid'];
            $data['rowData']   = $result['rows'];
            $page              = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
            $pagination        = new Paginator($result['rows'], $result['total'], $params['limit']);
            $pagination->setPath('');
            $data['i']          = ($page * $params['limit']) - $params['limit'];
            $data['pagination'] = $pagination;
            $data['category']   = $category;
            $data['tour_list']  = $tour_list;
            $data['sort']       = $sort;
            $data['order']      = $order;

            return view('layouts.' . CNF_THEME . '.tour.index', $data);
        }
    }

    public function getUpdate(Request $request, $id = null)
    {
    }

    public function getShow(Request $request, $id = null)
    {
    }

    public function postCopy(Request $request)
    {
    }

    public function postSave(Request $request)
    {
    }

    public function postDelete(Request $request)
    {
    }

    public function display()
    {
    }

    public function bookPackage(Request $request, $id = null)
    {
        $model = new Booktour();
        $info  = $model::makeInfo('booktour');
        $data  = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];
        $countries = DB::table('def_country')->get();
        if (Auth::check()) {
            $data['user'] = User::select('first_name', 'email')->where('id', Auth::user()->id)->first();
        }

        $data['roomprice'] = Tourdates::where('tourdateID', $request->get('tourdateID'))->first();
        $data['affiliate'] = Session::get('affiliate');
        //dd($data['affiliate']);
        $this->data['socialize'] = config('services');
        $data['tourdatedetail']  = Tourdates::where('tourdateID', $request->get('tourdateID'))->where('tourID', $request->get('tourID'))->first();
        $tourdetail              = Tours::where('tourId', $request->get('tourID'))->first();
        $data['category']        = DB::table('def_tour_categories')->where('tourcategoriesID', $tourdetail['tourcategoriesID'])->first();
        $data['no_of_person']    = $request->get('numOfPerson');
        $data['detail']          = $tourdetail;
        if (Auth::check()) {
            $data['user_id'] = Auth::user()->id;
        }
        //dd($data['category']);
        $data['countries'] = $countries;
        if (1 == $tourdetail->type) {
            return view('layouts.' . CNF_THEME . '.tour.form', $data);
        } else {
            return view('layouts.' . CNF_THEME . '.tour.boundform', $data);
        }
    }

    public function storeSessionData(Request $request)
    {
        $tourdate = Tourdates::find($request->tourdateID);
        $traveller = count($request->nameandsurname);

        if ($tourdate->capacity < $traveller) {
            return redirect()->back()->with('failed', 'Sorry, there\'s not enough capacity for the tour.');
        }

        $data = [
            'affiliateID'     => $request->affiliate,
            'tourID'          => $request->tourID,
            'tourdateID'      => $request->tourdateID,
            'bookingsID'      => $request->bookingsID,
            'userID'          => $request->userID,
            'type'            => $request->type,
            'nameansurname'   => $request->nameandsurname,
            'last_name'       => $request->last_name,
            'gender'          => $request->gender,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'NRIC'            => $request->NRIC,
            'nationality'     => $request->nationality,
            'address'         => $request->address,
            'city'            => $request->city,
            'countryID'       => $request->countryID,
            'room'            => $request->room,
            'roomtype1'       => $request->roomtype1,
            'roomtype2'       => $request->roomtype2,
            'roomtype3'       => $request->roomtype3,
            'roomtype4'       => $request->roomtype4,
            'roomtype5'       => $request->roomtype5,
            'roomtype6'       => $request->roomtype6,
            'roomtype7'       => $request->roomtype7,
            'roomtype8'       => $request->roomtype8,
            'roomtype9'       => $request->roomtype9,
            'passportno'      => $request->passportno,
            'dateofbirth'     => $request->dob,
            'passportissue'   => $request->issuedate,
            'passportexpiry'  => $request->expdate,
            'passport_place_made' => $request->place_made,
            'passportcountry' => $request->country,
            'totaldeposit'    => $request->totaldeposit,
            'balance'         => $request->balance,
        ];
        //dd($data);
        Session::put('bookings', $data);
        if (Auth::guest()) {
            return redirect('/reg/new');
        } else {
            return '
<html>
<head>
    <title>Activate Registration Oomrah </title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117911895-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());
  gtag("config", "UA-117911895-1");
</script>
</head>
<body>
<script>
window.location.replace("/bookPackage/getSession");
</script>
</body>
</html>
';
            // return redirect('/bookPackage/getSession');
        }
    }

    public function checkCredential()
    {
        //Log::info(request()->all());
        if (User::where('email', request('treveller_password'))->get()->first()->email == request('treveller_password')) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function checkLogin()
    {
        if (\Auth::attempt(['email' => request('email'), 'password' => request('password')])
            or \Auth::attempt(['username' => request('email'), 'password' => request('password')])
        ) {
            return 'true';
        } else {
            return 'false';
        }
    }

    //if user not register yet
    public function registerNewUser(Request $request)
    {
        $data = $this->SessionData($request);
        Log::info('reg new');
        Log::info($data);
        $last_id = DB::table('tb_users')->select('id')->orderby('id', 'desc')->first();
        //DB::table('tb_users')->lastInsertId()->get();
        $id     = $last_id->id + 1;
        $string = 'umrah';
        $phone  = substr($data['phone'][0], 6);
//        $check_email = DB::table('tb_users')->select('email')->where('email','=',$data['email'][0])->where('owner_id',CNF_OWNER)->get();
//        if (count($check_email)>0){
//            return redirect('/user/login')->with('message','You have existing account with oomrah.Kindly login with your email.');
//        }else{

        $code             = rand(10000, 10000000);
        $user             = new User();
        $user->username   = "{$string}{$id}{$phone}";
        $user->password   = Hash::make('password');
        $user->first_name = $data['nameansurname'][0];
        $user->last_name  = $data['last_name'][0];
        $user->email      = $data['email'][0];
        $user->activation = $code;
        if (CNF_ACTIVATION == 'auto') {
            $user->active = '1';
        } else {
            $user->active = '0';
        }
        $user->group_id = 6;
        $user->owner_id = CNF_OWNER;
        $user->save();
        //$user_id = User::find($ids);
        $password   = 'password';
        $email_data = ['username' => $user->email, 'name' => $user->first_name, 'password' => $password, 'activate' => $user->activation];
        Mail::send('layouts.'.CNF_THEME.'.tour.mail', ['email_data' => $email_data], function ($message) use ($data) {
            $message->to($data['email'][0], $data['nameansurname'][0])->subject('Maklumat Akaun Pengguna');
            $message->from('salam@oomrah.com', 'Oomrah');
        });

        return '
<html>
<head>
    <title>Activate Registration Oomrah </title>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117911895-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());
  gtag("config", "UA-117911895-1");
</script>
</head>
<body>
<script>
window.location.replace("/bookPackage/getSession");
</script>
</body>
</html>
';
        // return redirect('/bookPackage/getSession');
//        }
    }

    //if user login / after register
    public function accessSessionData(Request $request)
    {
        $data            = $this->SessionData($request);
        $user_id         = User::where('email', $data['email'][0])->first();
        $new_traveler    = '';
        $old_traveller   = '';
        $room1           = '';
        $room2           = '';
        $room3           = '';
        $room4           = '';
        $room5           = '';
        $room6           = '';
        $room7           = '';
        $room8           = '';
        $room9           = '';
        $totaltravellers = count($data['nameansurname']);
        if ('' != $data) {
            for ($i = 0; $i < $totaltravellers; ++$i) {
                $travellers = Travellers::where('NRIC', $data['NRIC'][$i])->where('owner_id', CNF_OWNER)->get()->first();

                if (null === $travellers) {
                    $travellers                  = new Travellers();
                    $travellers->nameandsurname  = $data['nameansurname'][$i];
                    $travellers->last_name       = $data['last_name'][$i];
                    $travellers->gender          = $data['gender'][$i];
                    $travellers->NRIC            = $data['NRIC'][$i];
                    
                    if (Auth::check()) {
                        $travellers->entry_by = Auth::User()->id;
                    } else {
                        $travellers->entry_by = $user_id->id;
                    }

                    $travellers->owner_id = CNF_OWNER;
                    

                    $new_traveler = $new_traveler . ',' . $travellers->travellerID;
                } else {
                    $old_traveller        = $old_traveller . ',' . $travellers->travellerID;
                    $travellers->roomtype = (isset($data['room'][$i]) && !is_null($data['room'][$i])) ? $data['room'][$i] : '';
                }

                $travellers->email           = $data['email'][$i] ?? '';
                $travellers->phone           = $data['phone'][$i] ?? '';
                $travellers->address         = $data['address'][$i];
                $travellers->city            = (isset($data['city'][$i]) && !is_null($data['city'][$i])) ? $data['city'][$i] : '';
                $travellers->roomtype        = (isset($data['room'][$i]) && !is_null($data['room'][$i])) ? $data['room'][$i] : '';
                $travellers->countryID       = $data['countryID'][$i];
                $travellers->passportno      = $data['passportno'][$i];
                $travellers->dateofbirth     = $data['dateofbirth'][$i];
                $travellers->passportissue   = $data['passportissue'][$i];
                $travellers->passportexpiry  = $data['passportexpiry'][$i];
                $travellers->passportcountry = $data['passportcountry'][$i];
                $travellers->passport_place_made = $data['passport_place_made'][$i];
                $travellers->nationality     = $data['nationality'][$i];

                $travellers->save();

                // dd($travellers);

                if (1 == $travellers->roomtype) {
                    $room1 = $room1 . ',' . $travellers->travellerID;
                } elseif (2 == $travellers->roomtype) {
                    $room2 = $room2 . ',' . $travellers->travellerID;
                } elseif (3 == $travellers->roomtype) {
                    $room3 = $room3 . ',' . $travellers->travellerID;
                } elseif (4 == $travellers->roomtype) {
                    $room4 = $room4 . ',' . $travellers->travellerID;
                } elseif (5 == $travellers->roomtype) {
                    $room5 = $room5 . ',' . $travellers->travellerID;
                } elseif (6 == $travellers->roomtype) {
                    $room6 = $room6 . ',' . $travellers->travellerID;
                } elseif (7 == $travellers->roomtype) {
                    $room7 = $room7 . ',' . $travellers->travellerID;
                } elseif (8 == $travellers->roomtype) {
                    $room8 = $room8 . ',' . $travellers->travellerID;
                } else {
                    $room9 = $room9 . ',' . $travellers->travellerID;
                }

                // dd($room7);
            }
            $filteremptytraveller = array_filter(explode(',', $old_traveller));
            $room1filter          = array_filter(explode(',', $room1));
            $room2filter          = array_filter(explode(',', $room2));
            $room3filter          = array_filter(explode(',', $room3));
            $room4filter          = array_filter(explode(',', $room4));
            $room5filter          = array_filter(explode(',', $room5));
            $room6filter          = array_filter(explode(',', $room6));
            $room7filter          = array_filter(explode(',', $room7));
            $room8filter          = array_filter(explode(',', $room8));
            $room9filter          = array_filter(explode(',', $room9));

            //check email if same with login email to get first travellers id
            if (Auth::check() && $data['email'][0] == Auth::user()->email) {
                $lastTID = DB::table('travellers')->select('travellerID')->where('email', Auth::user()->email)->where('owner_id', CNF_OWNER)->first();
            } elseif (Auth::check() && $data['email'][0] != Auth::user()->email) {
                $lastTID = DB::table('travellers')->select('travellerID')->where('email', $data['email'][0])->where('owner_id', CNF_OWNER)->first();
            } else {
                $lastTID = DB::table('travellers')->select('travellerID')->where('email', $data['email'][0])->where('owner_id', CNF_OWNER)->first();
            }
            $bookings                  = new Createbooking();
            $bookings->bookingno       = $data['bookingsID'];
            $bookings->travellerID     = $lastTID->travellerID;
            $bookings->tour            = 1;
            $bookings->type            = $data['type'];
            $bookings->totaltravellers = count($data['nameansurname']);
            $bookings->affiliatelink   = $data['affiliateID'];
            if (Auth::check()) {
                $bookings->entry_by = Auth::user()->id;
            } else {
                $bookings->entry_by = $user_id->id;
            }
            $bookings->owner_id      = CNF_OWNER;
            $bookings->balance       = $data['balance'];
            $bookings->new_traveler  = $new_traveler;
            $bookings->old_traveller = implode(',', $filteremptytraveller);
            $bookings->source_id = 9;
            $bookings->save();

            //get last inserted booking id
            if (Auth::check()) {
                $booking_id = DB::table('bookings')->where('travellerID', $lastTID->travellerID)->where('entry_by', Auth::User()->id)->orderBy('bookingsID', 'desc')->first();
            } else {
                $booking_id = DB::table('bookings')->where('travellerID', $lastTID->travellerID)->where('entry_by', $user_id->id)->orderBy('bookingsID', 'desc')->first();
            }

            $tourcategoriesID            = DB::table('tours')->where('tourID', $data['tourID'])->first();
            $bookTours                   = new Booktour();
            $bookTours->bookingID        = $booking_id->bookingsID;
            $bookTours->tourcategoriesID = $tourcategoriesID->tourcategoriesID;
            $bookTours->tourID           = $data['tourID'];
            $bookTours->tourdateID       = $data['tourdateID'];
            if (Auth::check()) {
                $bookTours->entry_by = Auth::user()->id;
            } else {
                $bookTours->entry_by = $user_id->id;
            }
            $bookTours->owner_id = CNF_OWNER;
            $bookTours->save();

            $parentRoom = null;

            if (count($room1filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 1;
                $bookroom->travellers = implode(',', $room1filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
                $parentRoom = $bookroom;
            }
            if (count($room2filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 2;
                $bookroom->travellers = implode(',', $room2filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
                $parentRoom = $bookroom;
            }
            if (count($room3filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 3;
                $bookroom->travellers = implode(',', $room3filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
                $parentRoom = $bookroom;
            }
            if (count($room4filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 4;
                $bookroom->travellers = implode(',', $room4filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
                $parentRoom = $bookroom;
            }
            if (count($room5filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 5;
                $bookroom->travellers = implode(',', $room5filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
                $parentRoom = $bookroom;
            }
            if (count($room6filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 6;
                $bookroom->travellers = implode(',', $room6filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
                $parentRoom = $bookroom;
            }
            if (count($room7filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 7;
                $bookroom->travellers = implode(',', $room7filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->parent_id = $parentRoom->roomID ?? null;
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
            }
            if (count($room8filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 8;
                $bookroom->travellers = implode(',', $room8filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->parent_id = $parentRoom->roomID ?? null;
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
            }
            if (count($room9filter) > 0) {
                $bookroom             = new Bookroom();
                $bookroom->bookingID  = $booking_id->bookingsID;
                $bookroom->roomtype   = 9;
                $bookroom->travellers = implode(',', $room9filter);
                if (Auth::check()) {
                    $bookroom->entry_by = Auth::user()->id;
                } else {
                    $bookroom->entry_by = $user_id->id;
                }
                $bookroom->status   = 1;
                $bookroom->remarks  = 'booking';
                $bookroom->parent_id = $parentRoom->roomID ?? null;
                $bookroom->owner_id = CNF_OWNER;
                $bookroom->save();
            }

            if($this->payment_gateway_id == 1) {
                return redirect('/bookpackage/deposit');
            } else {
                return redirect(route('bookpackage.paymentmethods'));
            }
        } else {
            return redirect('/dashboard')->with('message', 'no data');
        }
    }

    public function bookPackageSummary(Request $request)
    {
        $data         = $this->SessionData($request);
        $bookingno    = $data['bookingsID'];
        $id           = $data['tourID'];
        $tourdateID   = $data['tourdateID'];
        $totalreserve = count($data['nameansurname']);
        $model        = new Tours();
        $info         = $model::makeInfo('tours');
        $data         = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];
        $data['row']  = DB::table('tours')->where('tourID', $id)->first();
        $data['date'] = DB::table('tour_date')->where('tourdateID', $tourdateID)->first();
//        dd($bookingno);
        $bookids                  = DB::table('bookings')->select('bookingsID')->where('bookingno', '=', $bookingno)->get();
        $data['travellers']       = Travellers::where('entry_by', Auth::user()->id)->orderBy('travellerID', 'desc')->take($totalreserve)->get();
        $data['total_travellers'] = $totalreserve;
        $data['total_deposit']    = $totalreserve * $data['date']->cost_deposit;
        foreach ($bookids as $bookid) {
            $data['notpaiddepo'] = DB::table('book_tour')->where('bookingID', $bookid->bookingsID)->get();
            $data['bookid']      = $bookid->bookingsID;
            //Log::info($data['bookid']);
        }

        return view('layouts.' . CNF_THEME . '.tour.summary', $data);
    }

    public function payDeposit(Request $request)
    {
        $session = $this->SessionData($request);

        $bookingID       = Createbooking::where('bookingno', $session['bookingsID'])->first();

        $btour = Booktour::where('bookingID', $bookingID->bookingsID)->get()->first();

        $tour_date = Tourdates::find($btour->tourdateID);

        $username = User::where('email', $session['email'][0])->first();
        if (! $username) {
            $username = Auth::user();
        }

        // dd($tour_date);
        $bookrooms   = Bookroom::where('bookingID', $bookingID->bookingsID)->get();
        $total_price = 0;

        $invoice               = new Invoice();
        $invoice->travellerID  = $bookingID->travellerID;
        $invoice->bookingID    = $bookingID->bookingsID;
        $invoice->InvTotal     = 0;
        $invoice->Subtotal     = 0;
        $invoice->currency     = CNF_CURRENCY;
        $invoice->payment_type = 1;
        $invoice->notes        = 'Online Booking';
        $invoice->DateIssued   = Carbon::today();
        $datestring            = explode('-', $tour_date->start);
        $duedate               = Carbon::createFromDate($datestring[0], $datestring[1], $datestring[2]);
        $duedate->subDays(30);
        $invoice->DueDate  = $duedate;
        $invoice->discount = 0;
        $invoice->tax      = 0;
        $invoice->status   = 0;
        $invoice->entry_by = $username->id;
        $invoice->owner_id = CNF_OWNER;
        $invoice->save();

        $invoice = Invoice::find($invoice->invoiceID);

        foreach ($bookrooms as $bookroom) {
            $price = 0;
            if (1 == $bookroom->roomtype) { 
                $price = $tour_date->cost_single;
            }
            if (2 == $bookroom->roomtype) {
                $price = $tour_date->cost_double;
            }
            if (3 == $bookroom->roomtype) {
                $price = $tour_date->cost_triple;
            }
            if (4 == $bookroom->roomtype) {
                $price = $tour_date->cost_quad;
            }
            if (5 == $bookroom->roomtype) {
                $price = $tour_date->cost_quint;
            }
            if (6 == $bookroom->roomtype) {
                $price = $tour_date->cost_sext;
            }
            if (7 == $bookroom->roomtype) {
                $price = $tour_date->cost_child;
            }
            if (8 == $bookroom->roomtype) {
                $price = $tour_date->cost_child_wo_bed;
            }
            if (9 == $bookroom->roomtype) {
                $price = $tour_date->cost_infant_wo_bed;
            }

            DB::table('invoice_products')->insert(
                [
                    'InvID' => $invoice->invoiceID, 
                    'Code' => $bookingID->bookingno, 
                    'Items' => Tours::find($tour_date->tourID)->tour_name.' ('.$bookroom->roomTypeName.')', 
                    'Qty' => $bookroom->travellerList->count(), 
                    'Amount' => $price, 
                    'owner_id' => CNF_OWNER
                ]
            );
            
            $total_price += ($price * $bookroom->travellerList->count());
        }

        $invoice->InvTotal     = $total_price;
        $invoice->Subtotal     = $total_price;
        
        if($tour_date->discount)
        {   
            $invoice->discount = $tour_date->discount;
            $invoice->InvTotal -= ($invoice->discount*$bookroom->travellerList->count());
            
        }
        
        $invoice->save();

        Session::put('booking_id', $bookingID->bookingsID);

        //if total deposit == 0 will redirect to thanks page
        // EDITED
        // if (0 == $session['totaldeposit'] || !CNF_BILLPLZAPIKEY) {

        //     if (0 == $session['totaldeposit']) {
        //         $deposit_payment = Booktour::where('bookingID', $bookingID['bookingsID'])->update(['deposit_paid' => 1]);
        //     }

        //     return redirect('/bookpackage/paid');
        // }

        if (0 == $session['totaldeposit'] || $this->payment_gateway_id == 0) {

            if (0 == $session['totaldeposit']) {
                $deposit_payment = Booktour::where('bookingID', $bookingID['bookingsID'])->update(['deposit_paid' => 1]);
            }

            return redirect('/bookpackage/paid');
        }

        if($this->payment_gateway_id == 1) {

            $data = (object)[
                'type' => 'normalbooking',
                'session' => $session,
                'bookingID' => $bookingID
            ];

            return $this->payWithBillplz($data);
            /*
            $data['amount'] = request('payment_amount');
            $data['id']     = request('invoiceID');

            $bill = $this->billplz->bill();

            $url = 'http://' . CNF_DOMAIN;

            $data     = request()->all();

            // $resource = Billplz::issue(function (BillMessage $bill) use ($data,$session) {
            //     $bill->to($session['nameansurname'][0], $session['email'][0])
            //         ->amount($session['totaldeposit']) // will multiply with 100 automatically, so a RM500 bill, you just pass 500 instead of 50000
            //         ->callbackUrl('https://' . CNF_DOMAIN)
            //         ->redirectUrl('https://' . CNF_DOMAIN . '/bookpackage/paid')
            //         ->description('Deposit Payment')
            //         ->reference1($session['bookingsID']);
            // });
            // $array = $resource->toArray();

            $response = $bill->create(
                CNF_BILLPLZCOLLECTIONID, //collection id
                $session['email'][0], //email
                null, //given null. probably phone number
                $session['nameansurname'][0], //name
                $session['totaldeposit'] * 100, //payment amount
                [
                    'callback_url' => $url, 
                    'redirect_url' => 'http://' . CNF_DOMAIN . '/bookpackage/paid'
                ],
                'Deposit Payment', //description
                [
                    'reference_1' => $bookingID->bookingno, 
                    'reference_1_label' => 'Booking No.'
                ]
            );

            $array = $response->toArray();

            Log::info('Payment info');
            Log::info($array);

            return redirect($array['url']);
            */

        } else if($this->payment_gateway_id == 2) {

            $data = (object)[
                'type' => 'normalbooking',
                'session' => $session,
                'invoice' => $invoice,
                'bookingID' => $bookingID,
                'tour_date' => $tour_date,
                'bookroom' => $bookroom,
                'price' => $price,
            ];

            return $this->paymentMethod();
        }
        
    }

    public function payWithBillplz($transaction)
    {
        // dd($transaction->session);

        $data['amount'] = request('payment_amount');
        $data['id']     = request('invoiceID');

        $bill = $this->billplz->bill();

        $url = 'http://' . CNF_DOMAIN;

        $data     = request()->all();

        // $resource = Billplz::issue(function (BillMessage $bill) use ($data,$session) {
        //     $bill->to($session['nameansurname'][0], $session['email'][0])
        //         ->amount($session['totaldeposit']) // will multiply with 100 automatically, so a RM500 bill, you just pass 500 instead of 50000
        //         ->callbackUrl('https://' . CNF_DOMAIN)
        //         ->redirectUrl('https://' . CNF_DOMAIN . '/bookpackage/paid')
        //         ->description('Deposit Payment')
        //         ->reference1($session['bookingsID']);
        // });
        // $array = $resource->toArray();

        if($transaction->type == 'normalbooking') {
            $response = $bill->create(
                CNF_BILLPLZCOLLECTIONID, //collection id
                $transaction->session['email'][0], //email
                null, //given null. probably phone number
                $transaction->session['nameansurname'][0], //name
                $transaction->session['totaldeposit'] * 100, //payment amount
                [
                    'callback_url' => $url, 
                    'redirect_url' => 'http://' . CNF_DOMAIN . '/bookpackage/paid'
                ],
                'Deposit Payment', //description
                [
                    'reference_1' => $transaction->bookingID->bookingno, 
                    'reference_1_label' => 'Booking No.'
                ]
            );
        } else if($transaction->type == 'simplebooking') {
            $response = $bill->create(
                CNF_BILLPLZCOLLECTIONID, //collection id
                $transaction->session['email'], //email
                null, //given null. probably phone number
                $transaction->session['nameandsurname'], //name
                $transaction->session['totaldeposit'] * 100, //payment amount
                [
                    'callback_url' => $url, 
                    'redirect_url' => 'http://' . CNF_DOMAIN . '/bookpackage/paid'
                ],
                'Deposit Payment', //description
                [
                    'reference_1' => $transaction->bookingID->bookingno, 
                    'reference_1_label' => 'Booking No.'
                ]
            );
        }

        $array = $response->toArray();
        Log::info('Payment info');
        Log::info($array);

        return redirect($array['url']);
    }

    public function paymentMethod(Request $request)
    {
        $data = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
            'payment_methods' => json_decode(CNF_PAYMENT_GATEWAY_DATA),
            // 'payment_details' => json_encode($details),
        ];

        return view('layouts.' . CNF_THEME . '.tour.paymentmethod', $data);
    }

    public function simplePaymentMethod(Request $request)
    {
        $data = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
            'payment_methods' => json_decode(CNF_PAYMENT_GATEWAY_DATA),
        ];

        return view('layouts.' . CNF_THEME . '.tour.simplepaymentmethod', $data);
    }

    public function paymentMethodProcess(Request $request)
    {
        if($request->payment_method) {

            $getPaymentMethod = $request->payment_method;
            $paymentMethods = json_decode(CNF_PAYMENT_GATEWAY_DATA);
            $paymentMethod = $paymentMethods->{$getPaymentMethod};

            $session = $this->SessionData($request);

            $bookingID       = Createbooking::where('bookingno', $session['bookingsID'])->first();

            $btour = Booktour::where('bookingID', $bookingID->bookingsID)->get()->first();

            $tour_date = Tourdates::find($btour->tourdateID);

            $username = User::where('email', $session['email'][0])->first();
            if (! $username) {
                $username = Auth::user();
            }

            $bookrooms   = Bookroom::where('bookingID', $bookingID->bookingsID)->get();
            $total_price = 0;

            $invoice               = new Invoice();
            $invoice->travellerID  = $bookingID->travellerID;
            $invoice->bookingID    = $bookingID->bookingsID;
            $invoice->InvTotal     = 0;
            $invoice->Subtotal     = 0;
            $invoice->currency     = CNF_CURRENCY;
            $invoice->payment_type = 1;
            $invoice->notes        = 'Online Booking';
            $invoice->DateIssued   = Carbon::today();
            $datestring            = explode('-', $tour_date->start);
            $duedate               = Carbon::createFromDate($datestring[0], $datestring[1], $datestring[2]);
            $duedate->subDays(30);
            $invoice->DueDate  = $duedate;
            $invoice->discount = 0;
            $invoice->tax      = 0;
            $invoice->status   = 0;
            $invoice->entry_by = $username->id;
            $invoice->owner_id = CNF_OWNER;
            $invoice->save();

            $invoice = Invoice::find($invoice->invoiceID);

            foreach ($bookrooms as $bookroom) {
                $price = 0;
                if (1 == $bookroom->roomtype) { 
                    $price = $tour_date->cost_single;
                }
                if (2 == $bookroom->roomtype) {
                    $price = $tour_date->cost_double;
                }
                if (3 == $bookroom->roomtype) {
                    $price = $tour_date->cost_triple;
                }
                if (4 == $bookroom->roomtype) {
                    $price = $tour_date->cost_quad;
                }
                if (5 == $bookroom->roomtype) {
                    $price = $tour_date->cost_quint;
                }
                if (6 == $bookroom->roomtype) {
                    $price = $tour_date->cost_sext;
                }
                if (7 == $bookroom->roomtype) {
                    $price = $tour_date->cost_child;
                }
                if (8 == $bookroom->roomtype) {
                    $price = $tour_date->cost_child_wo_bed;
                }
                if (9 == $bookroom->roomtype) {
                    $price = $tour_date->cost_infant_wo_bed;
                }

                DB::table('invoice_products')->insert(
                    [
                        'InvID' => $invoice->invoiceID, 
                        'Code' => $bookingID->bookingno, 
                        'Items' => Tours::find($tour_date->tourID)->tour_name.' ('.$bookroom->roomTypeName.')', 
                        'Qty' => $bookroom->travellerList->count(), 
                        'Amount' => $price, 
                        'owner_id' => CNF_OWNER
                    ]
                );
                
                $total_price += ($price * $bookroom->travellerList->count());
            }

            $invoice->InvTotal     = $total_price;
            $invoice->Subtotal     = $total_price;
            
            if($tour_date->discount)
            {   
                $invoice->discount = $tour_date->discount;
                $invoice->InvTotal -= ($invoice->discount*$bookroom->travellerList->count());
                
            }
            
            $invoice->save();

            Session::put('booking_id', $bookingID->bookingsID);

            //if total deposit == 0 will redirect to thanks page
            // EDITED
            // if (0 == $session['totaldeposit'] || !CNF_BILLPLZAPIKEY) {

            //     if (0 == $session['totaldeposit']) {
            //         $deposit_payment = Booktour::where('bookingID', $bookingID['bookingsID'])->update(['deposit_paid' => 1]);
            //     }

            //     return redirect('/bookpackage/paid');
            // }

            if (0 == $session['totaldeposit'] || $this->payment_gateway_id == 0) {

                if (0 == $session['totaldeposit']) {
                    $deposit_payment = Booktour::where('bookingID', $bookingID['bookingsID'])->update(['deposit_paid' => 1]);
                }

                return redirect('/bookpackage/paid');
            }

            $data = (object)[
                'type' => 'normalbooking',
                'session' => $session,
                'invoice' => $invoice,
                'bookingID' => $bookingID,
                'tour_date' => $tour_date,
                'bookroom' => $bookroom,
                'price' => $price,
                'payment_method' => $getPaymentMethod,
                'payment_method_config' => $paymentMethod
            ];
            return $this->payWithBayarInd($data);

        } else {
            return redirect(route('bookpackage.paymentmethods'))->with('payment_method_error', 'No payment method selected');
        }
    }

    public function simplePaymentMethodProcess(Request $request)
    {
        if($request->payment_method) {
            $getPaymentMethod = $request->payment_method;
            $paymentMethods = json_decode(CNF_PAYMENT_GATEWAY_DATA);
            $paymentMethod = $paymentMethods->{$getPaymentMethod};

            $no_adult = 0;
            $no_child = 0;
            $no_infant = 0;
            if ($request->adult_number) {
                $no_adult = $request->adult_number;
            }
            if ($request->child_number) {
                $no_child = $request->child_number;
            }
            if ($request->infant_number) {
                $no_infant = $request->infant_number;
            }

            $amount = $no_adult+$no_child+$no_infant;
            $tourdate = Tourdates::find($request->tourdateID);

            if ($tourdate->capacity < $amount) {
                return redirect()->back()->with('failed', 'Sorry, there\'s not enough capacity for the tour.');
            }
            DB::beginTransaction();

            if (Auth::guest()) {
                $user = $this->simpleRegNewUser($request);
            }else{
                $user = Auth::user();
            }

            $traveller = Travellers::where('owner_id', CNF_OWNER)->where('NRIC', $request->NRIC)->get()->first();

            if (!$traveller) {
                $traveller = new Travellers;
                $traveller->NRIC = $request->NRIC;
                $traveller->nameandsurname = $request->nameandsurname;
                $traveller->last_name = $request->last_name;
                $traveller->gender = $request->gender;
                $traveller->owner_id = CNF_OWNER;
                $traveller->entry_by = $user->id;
            }

            $traveller->email = $request->email;
            $traveller->phone = $request->phone;
            $traveller->address = $request->address;
            $traveller->countryID = $request->countryID;
            $traveller->save();

            $booking = new Createbooking;
            $booking->bookingno = $request->bookingsID;
            $booking->travellerID = $traveller->travellerID;
            $booking->tour = 1;
            $booking->type = 1;
            $booking->affiliatelink = $request->affiliate;
            if ($request->adult_number) {
                $booking->adult_number = $request->adult_number;
            }
            if ($request->child_number) {
                $booking->child_number = $request->child_number;
            }
            if ($request->infant_number) {
                $booking->infant_number = $request->infant_number;
            }
            $booking->entry_by = $user->id;
            $booking->owner_id = CNF_OWNER;
            $booking->save();

            $tour_date = Tourdates::where('tourdateID', $request->tourdateID)->where('owner_id', CNF_OWNER)->get()->first();

            if (!$tour_date) {
                abort(404);
            }

            $booktour = new Booktour;
            $booktour->bookingID = $booking->bookingsID;
            $booktour->tourcategoriesID = $tour_date->tourcategory->tourcategoriesID;
            $booktour->tourID = $tour_date->tourID;
            $booktour->tourdateID = $tour_date->tourdateID;
            $booktour->deposit_paid = 0;
            $booktour->status = 2;
            $booktour->entry_by = $user->id;
            $booktour->owner_id = CNF_OWNER;
            $booktour->admin_read = 0;
            $booktour->save();

            $duedate = Carbon::parse($tour_date->start)->subDays(30);

            $invoice = new Invoice;
            $invoice->travellerID = $traveller->travellerID;
            $invoice->bookingID = $booking->bookingsID;
            $invoice->InvTotal = 0;
            $invoice->Subtotal = 0;
            $invoice->currency = CNF_CURRENCY;
            $invoice->payment_type = 1;
            $invoice->notes = 'Simple Online Booking';
            $invoice->DateIssued = Carbon::today();
            $invoice->DueDate = $duedate;
            $invoice->discount = 0;
            $invoice->tax = 0;
            $invoice->status = 0;
            $invoice->entry_by = $user->id;
            $invoice->owner_id = CNF_OWNER;
            
            if($booking->booktour->tourdate->discount)
            {
                $invoice->discount = $booking->booktour->tourdate->discount;
            }
            
            $invoice->save();

            if (0 == $request->totaldeposit || $this->payment_gateway_id == 0) {

                if (0 == $request->totaldeposit) {
                    $deposit_payment = Booktour::where('bookingID', $booking['bookingsID'])->update(['deposit_paid' => 1]);
                }

                return redirect('/bookpackage/paid');
            }

            $data = (object)[
                'type' => 'simplebooking',
                'session' => (array)$request->all(),
                'invoice' => $invoice,
                'bookingID' => $booking,
                'tour_date' => $tourdate,
                // 'bookroom' => $bookroom,
                'qty' => $amount,
                // 'price' => $amount,
                'payment_method' => $getPaymentMethod,
                'payment_method_config' => $paymentMethod
            ];

            return $this->payWithBayarInd($data);

        } else {
            return redirect(route('bookpackage.simplepaymentmethods', request()->all()))->with('payment_method_error', 'No payment method selected');
        }
    }

    public function payWithBayarInd($transaction)
    {
        if($transaction->payment_method == 'gpn') {
            
            if($transaction->type == 'normalbooking') {

                try {
                    $header = [
                        'typ' => 'JWT',
                        'alg' => 'HS256',
                    ];

                    $customerName = $transaction->session['nameansurname'] ? $transaction->session['nameansurname'][0] : '';
                    $customerEmail = $transaction->session['email'] ? $transaction->session['email'][0] : '';
                    $customerMobile = $transaction->session['phone'] ? $transaction->session['phone'][0] : '';

                    $authRequest = [
                        'jti' => date('Ymd_His'),
                        'iss' => $transaction->payment_method_config->man,
                        'aud' => url($transaction->payment_method_config->audience),
                        'iat' => strtotime(date('Y-m-d H:i:s')),
                    ];
                    
                    $txnRequest = [
                        'merReference' => date('Ymd_His'),
                        'txnAmount' => number_format($transaction->price, 2, '.', ''),
                        'txnDescription' => $transaction->invoice->notes,
                        'tfpMerchantName' => 'DGA',
                    ];

                    $payload = [
                        'txnType' => 1,
                        'authRequest' => $authRequest,
                        'txnRequest' => $txnRequest,
                        'customer' => [
                            'cusReference' => date('Ymd_His'),
                            'cusName' => $customerName,
                            'cusEmail' => $customerEmail,
                            'cusMobile' => $customerMobile,
                        ],
                        'respURL' => url('/bookpackage/complete-sales'),
                        'callBackURL' => url('/bookpackage/receive-request-action'),
                    ];

                    $headerJson = json_encode($header);
                    $payloadJson = json_encode($payload);

                    $signature = hash_hmac('sha256', $this->base64URLEncode($headerJson) . '.' . $this->base64URLEncode($payloadJson), $transaction->payment_method_config->secret_key, true);

                    $txnData = $this->base64URLEncode($headerJson) . '.' . $this->base64URLEncode($payloadJson) . '.' . $this->base64URLEncode($signature);
                    // $paymentRegisterURL = 'https://bpgdev.bersama.id/ArtajasaPG/payment/api/transaction/sales';
                    // $paymentSalesRedirect = 'https://bpgdev.bersama.id/ArtajasaPG/payment/salesRedirect';

                    $paymentRegisterURL = 'https://bpg.bersama.id/ArtajasaPG/payment/api/transaction/sales';
                    $paymentSalesRedirect = 'https://bpg.bersama.id/ArtajasaPG/payment/salesRedirect';

                    $httpClient = new HttpClient();
                    $paymentRegister = $httpClient->post($paymentRegisterURL, [
                        'body' => $txnData
                    ]);
                    $paymentRegistered = $paymentRegister->getBody();
                    $paymentRegisteredData = explode('.', $paymentRegistered);
                    $paymentRegisteredDecode = json_decode($this->base64URLDecode($paymentRegisteredData[1]));

                    $paymentMethod = new InvoicePaymentMethod;
                    $paymentMethod->invoiceID = $transaction->invoice->invoiceID;
                    $paymentMethod->meta = json_encode([
                        'payment_method' => $transaction->payment_method,
                        'channel' => $transaction->payment_method_config->channel,
                        'token' => $paymentRegisteredDecode->txnToken,
                        'tokenExp' => $paymentRegisteredDecode->txnTokenExp,
                        'price' => $transaction->price,
                        'authRequest' => $authRequest,
                        'txnRequest' => $txnRequest
                    ]);
                    $paymentMethod->insertId = $paymentRegisteredDecode->txnReference;
                    $paymentMethod->owner_id = CNF_OWNER;
                    $paymentMethod->payment_status = 'awaiting';
                    $paymentMethod->payment_status_message = 'Please Complete Your Payment';
                    $paymentMethod->created_at = Carbon::now();
                    $paymentMethod->save();
                    ?>
                    <form id="salesRedirect" action="<?php echo $paymentSalesRedirect; ?>" method="POST">
                        <input name="txnToken" value="<?php echo $paymentRegisteredDecode->txnToken; ?>" type="hidden"> 
                        <input name="txnReference" value="<?php echo $paymentRegisteredDecode->txnReference; ?>" type="hidden">
                    </form>
                    <script type="text/javascript">
                        document.getElementById('salesRedirect').submit();
                    </script>
                    <?php 
                } catch (\Exception $e) {

                    Log::debug($e->getMessage());

                    $data           = [
                        'pageTitle'    => \Lang::get('core.packages'),
                        'pageMetakey'  => CNF_METAKEY,
                        'pageMetadesc' => CNF_METADESC,
                        'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
                        'homepage'     => 0,
                        'pageImage'    => CNF_HEADERIMAGE,
                        'response' => \Lang::get('core.something_went_wrong')
                    ];

                    return view('layouts.' . CNF_THEME . '.tour.paymentfailed', $data);
                }
                
            }
        } else {
            $vaNumber = 0;
            if($transaction->type == 'normalbooking') {
                $authCode = hash('sha256', 
                    $transaction->invoice->invoiceID . 
                    $transaction->price . 
                    $transaction->payment_method_config->channel_id . 
                    $transaction->payment_method_config->secret_key
                );

                $itemDetails[] = [
                    'itemName' => Tours::find($transaction->tour_date->tourID)->tour_name . ' ('.$transaction->bookroom->roomTypeName.')',
                    'quantity' => $transaction->bookroom->travellerList->count(),
                    'price' => $transaction->price,
                ];

                $vaNumber = '';
                $customerName = '';
                $customerName = preg_replace('/[^\p{L}\p{N}\s]/u', '', CNF_COMNAME);
                $customerEmail = isset($transaction->session['email'][0]) ? $transaction->session['email'][0] : '';
                $customerPhone = isset($transaction->session['phone'][0]) ? $transaction->session['phone'][0] : '';

                if(!isset($transaction->payment_method_config->callback)) {
                    $companyCode = isset($transaction->payment_method_config->bin) ? $transaction->payment_method_config->bin : $transaction->payment_method_config->company_code;
                    $vaNumber = $this->generateVirtualAccountNumber($companyCode, $transaction->session['phone'][0]);
                }

                $insertData = array(
                    "channelId"         => $transaction->payment_method_config->channel_id,
                    "currency"          => 'IDR',
                    "customerAccount"   => $vaNumber,
                    "customerName"      => $customerName,
                    "customerEmail"     => $customerEmail,
                    "customerPhone"     => $customerPhone,
                    "transactionNo"     => $transaction->invoice->invoiceID,
                    "transactionAmount" => $transaction->price,
                    "transactionFee"    => 0,
                    "transactionDate"   => date('Y-m-d H:i:s', strtotime(Carbon::today())),
                    "transactionExpire" => date('Y-m-d H:i:s', strtotime(Carbon::tomorrow())),
                    "callbackURL"       => isset($transaction->payment_method_config->callback) ? url($transaction->payment_method_config->callback) . '?id=' . $transaction->invoice->invoiceID : "", // Fill with your callback URL
                    "description"       => 'Booking No. ' . $transaction->bookingID->bookingno, 
                    "itemDetails"       => $itemDetails,
                    "authCode"          => $authCode,
                    "additionalData"    => "",
                );

            } else if($transaction->type == 'simplebooking') {

                $authCode = hash('sha256', 
                    $transaction->invoice->invoiceID . 
                    $transaction->invoice->Subtotal . 
                    $transaction->payment_method_config->channel_id . 
                    $transaction->payment_method_config->secret_key
                );

                $itemDetails[] = [
                    'itemName' => Tours::find($transaction->tour_date->tourID)->tour_name,
                    'quantity' => $transaction->qty,
                    'price' => $transaction->invoice->Subtotal,
                ];
                $vaNumber = '';
                $customerName = '';
                $customerName = preg_replace('/[^\p{L}\p{N}\s]/u', '', CNF_COMNAME);
                $customerEmail = isset($transaction->session['email'][0]) ? $transaction->session['email'][0] : '';
                $customerPhone = isset($transaction->session['phone'][0]) ? $transaction->session['phone'][0] : '';
                if(!isset($transaction->payment_method_config->callback)) {
                    $companyCode = isset($transaction->payment_method_config->company_code) ? $transaction->payment_method_config->company_code : $transaction->payment_method_config->bin;
                    $vaNumber = $this->generateVirtualAccountNumber($companyCode, $transaction->session['phone']);
                    
                }
                $insertData = array(
                    "channelId"         => $transaction->payment_method_config->channel_id,
                    "currency"          => 'IDR',
                    "customerAccount"   => $vaNumber,
                    "customerName"      => $customerName,
                    "customerEmail"     => $customerEmail,
                    "customerPhone"     => $customerPhone,
                    "transactionNo"     => $transaction->invoice->invoiceID,
                    "transactionAmount" => $transaction->invoice->Subtotal,
                    "transactionFee"    => 0,
                    "transactionDate"   => date('Y-m-d H:i:s', strtotime(Carbon::today())),
                    "transactionExpire" => date('Y-m-d H:i:s', strtotime(Carbon::tomorrow())),
                    "callbackURL"       => isset($transaction->payment_method_config->callback) ? url($transaction->payment_method_config->callback) . '?id=' . $transaction->invoice->invoiceID : '', // Fill with your callback URL
                    "description"       => 'Booking No. ' . $transaction->bookingID->bookingno, 
                    "itemDetails"       => $itemDetails,
                    "authCode"          => $authCode,
                    "additionalData"    => "",
                );
            }

            $URL_insert = "https://simpg.sprintasia.net/PaymentRegister"; // Development
            
            //$URL_insert = "https://pay.sprintasia.net:8899/PaymentRegister"; //Staging
            // $URL_insert = "https://pay.sprintasia.net/PaymentRegister"; //Production

            $OPT        = http_build_query($insertData);

            // cURL setting
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL_insert);     // provide the URL to use
            curl_setopt($ch, CURLOPT_POSTFIELDS, $OPT);     // specify data to POST to server
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    // FALSE, blindly accept any server certificate, without doing any verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    // FALSE, not verify the certificate's name against host
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // TRUE to return the transfer as a string of the return value of curl_exec()
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);    // TRUE to return the raw output when CURLOPT_RETURNTRANSFER is used
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);   // The number of seconds to wait while trying to connect
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);          // The maximum number of seconds to allow cURL functions to execute

            // cURL execute
            $raw_response = curl_exec($ch);
            if(curl_error($ch)){
              die("CURL Error ::". curl_error($ch));
            }
            curl_close($ch);


            // Decode json to array
            $response   = json_decode($raw_response);

            if($response->insertStatus == '00') {

                if((isset($response->redirectURL) && $response->redirectURL != '') && (isset($response->redirectData) && count((array)$response->redirectData) > 0)) {
                    if(isset($response->redirectData->klikPayCode)) {
                        $invoice = Invoice::find($transaction->invoice->invoiceID);

                        $paymentMethod = new InvoicePaymentMethod;
                        $paymentMethod->invoiceID = $transaction->invoice->invoiceID;
                        $paymentMethod->meta = json_encode([
                            'payment_method' => $transaction->payment_method,
                            'channel' => $transaction->payment_method_config->channel,
                            'klikPayCode' => $response->redirectData->klikPayCode,
                            'transactionNo' => $response->redirectData->transactionNo,
                            'transactionDate' => $response->redirectData->transactionDate,
                            'signature' => $response->redirectData->signature,
                        ]);
                        $paymentMethod->insertId = $response->insertId;
                        $paymentMethod->owner_id = CNF_OWNER;
                        $paymentMethod->payment_status = 'awaiting';
                        $paymentMethod->payment_status_message = 'Please Complete Your Payment';
                        $paymentMethod->created_at = Carbon::now();
                        $paymentMethod->save();
                    } else {
                        $invoice = Invoice::find($transaction->invoice->invoiceID);

                        $paymentMethod = new InvoicePaymentMethod;
                        $paymentMethod->invoiceID = $transaction->invoice->invoiceID;
                        $paymentMethod->meta = json_encode([
                            'payment_method' => $transaction->payment_method,
                            'channel' => $transaction->payment_method_config->channel,
                            'insertId' => $response->insertId,
                            'payment_code' => $response->redirectData->payment_code,
                        ]);
                        $paymentMethod->insertId = $response->insertId;
                        $paymentMethod->owner_id = CNF_OWNER;
                        $paymentMethod->payment_status = 'awaiting';
                        $paymentMethod->payment_status_message = 'Please Complete Your Payment';
                        $paymentMethod->created_at = Carbon::now();
                        $paymentMethod->save();
                    }

                    $forms = '';
                    $forms .= '<form method="POST" action="'. $response->redirectURL .'" id="dataPost">';

                    foreach($response->redirectData as $key => $value) {
                        $forms .= '<input type="hidden" name="'. $key .'" value="'. $value .'">';
                    }

                    $forms .= '</form>';
                    $forms .= '<script type="text/javascript">document.getElementById("dataPost").submit();</script>';

                    return $forms;
                } else {
                    $invoice = Invoice::find($transaction->invoice->invoiceID);

                    $paymentMethod = new InvoicePaymentMethod;
                    $paymentMethod->invoiceID = $transaction->invoice->invoiceID;
                    $paymentMethod->meta = json_encode([
                        'payment_method' => $transaction->payment_method,
                        'channel' => $transaction->payment_method_config->channel,
                        'account_number' => $vaNumber,
                        'account_name' => preg_replace('/[^\p{L}\p{N}\s]/u', '', CNF_COMNAME)
                    ]);
                    $paymentMethod->insertId = $response->insertId;
                    $paymentMethod->owner_id = CNF_OWNER;
                    $paymentMethod->created_at = Carbon::now();
                    $paymentMethod->payment_status = 'awaiting';
                    $paymentMethod->payment_status_message = 'Please Complete Your Payment';
                    $paymentMethod->save();

                    $data           = [
                        'pageTitle'    => \Lang::get('core.packages'),
                        'pageMetakey'  => CNF_METAKEY,
                        'pageMetadesc' => CNF_METADESC,
                        'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
                        'homepage'     => 0,
                        'pageImage'    => CNF_HEADERIMAGE,
                        'invoice'      => $invoice,
                        'payment_method' => json_decode($paymentMethod->meta),
                        'items'        => \DB::table('invoice_products')->where('InvID', $transaction->invoice->invoiceID)->get()
                    ];

                    return view('layouts.' . CNF_THEME . '.tour.paymentinformation', $data);
                }
                
            } else {
                $data           = [
                    'pageTitle'    => \Lang::get('core.packages'),
                    'pageMetakey'  => CNF_METAKEY,
                    'pageMetadesc' => CNF_METADESC,
                    'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
                    'homepage'     => 0,
                    'pageImage'    => CNF_HEADERIMAGE,
                    'response' => $response
                ];

                return view('layouts.' . CNF_THEME . '.tour.paymentfailed', $data);
            }
        }
    }

    public function bayarindSuccessPay(Request $request)
    {
        $owner = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();
        $invoice = [];

        if($request->id) {
            $invoice = Invoice::find($request->id);

            if($invoice) {
                $getInvoicePaymentMethod = InvoicePaymentMethod::where('invoiceID', $invoice->invoiceID)->first();

                if($getInvoicePaymentMethod->payment_status == 'awaiting') {
                    $getInvoicePaymentMethod->payment_status = 'timeout';
                    $getInvoicePaymentMethod->payment_status_message = 'Your transaction timed out';
                    $getInvoicePaymentMethod->save();
                }
            }
        }

        $data           = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
            'owner' => $owner,
            'invoice' => $invoice,
        ];

        return view('layouts.' . CNF_THEME . '.tour.bayarind_success_pay', $data);
    }

    public function successPay(Request $request)
    {
        $transaction_id = $request->all();
        $model          = new Tours();
        $info           = $model::makeInfo('tours');
        $data           = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];
        //if deposit != 0
        if ($transaction_id) {
            if ('false' == $transaction_id['billplz']['paid']) {
                // $bill    = Billplz::get($transaction_id['billplz']['id'])->toArray();
                $bills = $this->billplz->bill();
                $bill = $bills->get($transaction_id['billplz']['id'])->toArray();

                $booking = Createbooking::where('bookingno', $bill['reference_1'])->get()->first();
                if (null != $booking->new_traveler) {
                    $new_traveler = explode(',', $booking->new_traveler);
                    unset($new_traveler[0]);
                    Travellers::destroy($new_traveler);
                }

                $booktours = Booktour::where('bookingID', $booking->bookingsID)->get()->first();
                $booktours->delete();
                $bookrooms = Bookroom::where('bookingID', $booking->bookingsID)->get();
                foreach ($bookrooms as $bookroom) {
                    $bookroom->delete();
                }

                $invoice = Invoice::where('bookingID', $booking->bookingsID)->get()->first();

                DB::table('invoice_products')->where('InvID', $invoice->invoiceID)->delete();

                $invoice->delete();

                $booking->delete();

                $data['booking']     = $bill['reference_1'];
                $data['email_owner'] = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();

                return view('layouts.' . CNF_THEME . '.tour.cancelnote', $data);
            } else {
                // $bill    = Billplz::get($transaction_id['billplz']['id'])->toArray();
                $bills = $this->billplz->bill();
                $bill = $bills->get($transaction_id['billplz']['id'])->toArray();

                $amount = $bill['paid_amount']->getMoney()->getAmount();

                $amount/=100;

                $booking = Createbooking::where('bookingno', $bill['reference_1'])->get()->first();

                //calculate balance payment and update booking table
                // dd($booking);
                $total_balance = $booking->balance - $amount;
                $bookings      = Createbooking::where('bookingno', $bill['reference_1'])->update(['balance' => $total_balance]);

                $username = User::where('email', $bill['email'])->first();
                if (! $username) {
                    $username = Auth::user();
                }

                $password = 'password';

                $btour           = Booktour::where('bookingID', $booking->bookingsID)->get()->first();
                $deposit_payment = Booktour::where('bookingID', $booking->bookingsID)->update(['deposit_paid' => 1]);

                $tour_date = Tourdates::find($btour->tourdateID);

                // dd($tour_date);
                // $bookrooms   = Bookroom::where('bookingID', $booking->bookingsID)->get();
                // $total_price = 0;
                // foreach ($bookrooms as $bookroom) {
                //     if (1 == $bookroom->roomtype) {
                //         $total_price += $tour_date->cost_single;
                //     }
                //     if (2 == $bookroom->roomtype) {
                //         $total_price += $tour_date->cost_double;
                //     }
                //     if (3 == $bookroom->roomtype) {
                //         $total_price += $tour_date->cost_triple;
                //     }
                //     if (4 == $bookroom->roomtype) {
                //         $total_price += $tour_date->cost_quad;
                //     }
                // }
                // $invoice               = new Invoice();
                // $invoice->travellerID  = $booking->travellerID;
                // $invoice->bookingID    = $booking->bookingsID;
                // $invoice->InvTotal     = $total_price;
                // $invoice->Subtotal     = $total_price;
                // $invoice->currency     = 125;
                // $invoice->payment_type = 1;
                // $invoice->notes        = 'Online Booking';
                // $invoice->DateIssued   = Carbon::today();
                // $datestring            = explode('-', $tour_date->start);
                // $duedate               = Carbon::createFromDate($datestring[0], $datestring[1], $datestring[2]);
                // $duedate->subDays(30);
                // $invoice->DueDate  = $duedate;
                // $invoice->discount = 0;
                // $invoice->tax      = 0;
                // $invoice->status   = 0;
                // $invoice->entry_by = $username->id;
                // $invoice->owner_id = CNF_OWNER;
                // $invoice->save();
                // DB::table('invoice_products')->insert(
                //     ['InvID' => $invoice->invoiceID, 'Code' => $booking->bookingno, 'Items' => Tours::find($tour_date->tourID)->tour_name, 'Qty' => 1, 'Amount' => $total_price, 'owner_id' => CNF_OWNER]
                // );

                $invoice = Invoice::where('bookingID', $booking->bookingsID)->get()->first();

                $payment                = new Payments();
                $payment->travellerID   = $booking->travellerID;
                $payment->invoiceID     = $invoice->invoiceID;
                $payment->amount        = $bill['paid_amount']->getMoney()->getAmount() / 100;
                $payment->currency      = CNF_CURRENCY;
                $payment->payment_type  = 1;
                $payment->payment_date  = Carbon::today();
                $payment->payment_prove = $bill['id'];
                $payment->notes         = 'Online Payment';
                $payment->entry_by      = $username->id;
                $payment->received      = 1;
                $payment->owner_id      = CNF_OWNER;
                $payment->save();
                //$data['email_owner'] = DB::table('tb_owners')->select('email','telephone')->where('id',CNF_OWNER)->first();
                // dd($bill['due_at']->format('d M y'));
                $email_data = [
                    'username'     => $username->username,
                    'password'     => $password,
                    'state'        => $bill['state'],
                    'amount_paid'  => $bill['paid_amount']->getMoney()->getAmount() / 100,
                    'paid_at'      => $bill['due_at']->format('d M y'),
                    'bookingno'    => $bill['reference_1'],
                    'color'        => CNF_TEMPCOLOR,
                    'logo'         => CNF_LOGO,
                    'supportemail' => CNF_EMAIL,
                    'supportphone' => CNF_TEL,
                ];
                Log::info($bill);

                // dd($bill);

                Mail::send('layouts.'.CNF_THEME.'.tour.mailsuccesspay', ['email_data' => $email_data], function ($message) use ($bill) {
                    $message->to($bill['email'], $bill['name'])->subject('Pembayaran Deposit Berjaya');
                    $message->from('salam@oomrah.com', 'Oomrah');
                });

            }

            $booking = $booking ?? Createbooking::find(Session::get('booking_id'));
            $traveller = $booking->traveller;

            Mail::send('createbooking.newbookingmail', ['booking' => $booking], function ($message) use ($traveller) {
                $message->to($traveller->email, $traveller->fullname)->subject('Maklumat Tempahan');
                $message->from(CNF_EMAIL, CNF_COMNAME);
            });

            $data['request'] = $request;
            $data['email_owner'] = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();

            return view('layouts.' . CNF_THEME . '.tour.successnote', $data);
        } else {
            $data['email_owner'] = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();

            return view('layouts.' . CNF_THEME . '.tour.successnote', $data);
        }
    }

    public function summaryBillplz(Request $request)
    {
        // $bill = Billplz::get(isset($_POST['id']) ? $_POST['id'] : '')->toArray();
        $bills = $this->billplz->bill();
        $bill = $bills->get(isset($_POST['id']) ? $_POST['id'] : '')->toArray();

        Log::info($bill);
        $bookingID = $bill['reference_1'];
        Log::info($bookingID);
        $status = isset($_POST['state']) ? $_POST['state'] : '';
        Log::info($status);
        if ('paid' == $status) {
            $deposit_payment = Booktour::where('bookingID', $bookingID)->update(['deposit_paid' => 1]);
            Log::info($deposit_payment);
        }
    }

    public function countryName($id)
    {
        $name = Countries::find($id)['attributes'];

        return $name;
    }

    public function deleteSessionData(Request $request)
    {
        $request->session()->forget('my_name');
        echo 'Data has been removed from session.';
    }

    public function bookingpay(Request $request)
    {
        $model = new Tours();
        $info  = $model::makeInfo('tours');
        $data  = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];
        $transaction_id = $request->all();
        // $bill           = Billplz::get($transaction_id['billplz']['id'])->toArray();
        $bills = $this->billplz->bill();
        $bill = $bills->get($transaction_id['billplz']['id'])->toArray();

        if ('true' == $transaction_id['billplz']['paid']) {
            $payment               = new Payments();
            $payment->travellerID  = Travellers::where('email', $bill['email'])->get()->first()->travellerID;
            $payment->invoiceID    = $bill['reference_1'];
            $payment->amount       = $bill['paid_amount']->getMoney()->getAmount() / 100;
            $payment->currency     = CNF_CURRENCY;
            $payment->payment_type = 1;
            $payment->payment_date = Carbon::today();
            $payment->notes        = 'Online Payment';
            $payment->entry_by     = User::where('email', $bill['email'])->get()->first()->id;
            $payment->received     = 1;
            $payment->owner_id     = CNF_OWNER;
            $payment->save();
        }
        $data['email_owner'] = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();

        $username = User::where('email', $bill['email'])->first();

        $email_data = [
            'username'     => $username->username,
            'state'        => $bill['state'],
            'amount_paid'  => $bill['paid_amount']->getMoney()->getAmount() / 100,
            'paid_at'      => $bill['due_at']->format('d M y'),
            'bookingno'    => $bill['reference_1'],
            'color'        => CNF_TEMPCOLOR,
            'logo'         => CNF_LOGO,
            'supportemail' => CNF_EMAIL,
            'supportphone' => CNF_TEL,
        ];

        Mail::send('layouts.'.CNF_THEME.'.tour.mailsuccesspay', ['email_data' => $email_data], function ($message) use ($bill) {
            $message->to($bill['email'], $bill['name'])->subject('Pembayaran Berjaya');
            $message->from('salam@oomrah.com', 'Oomrah');
        });

        return view('layouts.' . CNF_THEME . '.tour.successnote', $data);
    }

    private function SessionData(Request $request)
    {
        $data = Session::get('bookings');

        return $data;
    }

    public function simpleBooking(Request $request)
    {
        $model = new Booktour();
        $info  = $model::makeInfo('booktour');

        $user = Auth::user();
        $tour = Tours::where('tourId', $request->tourID)->where('owner_id', CNF_OWNER)->first();
        $tourdate = Tourdates::where('tourdateId', $request->tourdateID)->where('owner_id', CNF_OWNER)->first();
        $countries = DB::table('def_country')->get();

        $data = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
            'detail' => $tour,
            'tourdatedetail' => $tourdate,
            'user' => $user,
            'countries' => $countries,
            'affiliate' => Session::get('affiliate'),
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::user()->id;
        }

        return view('layouts.' . CNF_THEME . '.tour.simple-form', $data);
    }

    public function simpleSubmit(Request $request)
    {
        $no_adult = 0;
        $no_child = 0;
        $no_infant = 0;
        if ($request->adult_number) {
            $no_adult = $request->adult_number;
        }
        if ($request->child_number) {
            $no_child = $request->child_number;
        }
        if ($request->infant_number) {
            $no_infant = $request->infant_number;
        }

        $amount = $no_adult+$no_child+$no_infant;
        $tourdate = Tourdates::find($request->tourdateID);

        if ($tourdate->capacity < $amount) {
            return redirect()->back()->with('failed', 'Sorry, there\'s not enough capacity for the tour.');
        }
        DB::beginTransaction();

        if (Auth::guest()) {
            $user = $this->simpleRegNewUser($request);
        }else{
            $user = Auth::user();
        }

        $traveller = Travellers::where('owner_id', CNF_OWNER)->where('NRIC', $request->NRIC)->get()->first();

        if (!$traveller) {
            $traveller = new Travellers;
            $traveller->NRIC = $request->NRIC;
            $traveller->nameandsurname = $request->nameandsurname;
            $traveller->last_name = $request->last_name;
            $traveller->gender = $request->gender;
            $traveller->owner_id = CNF_OWNER;
            $traveller->entry_by = $user->id;
        }

        $traveller->email = $request->email;
        $traveller->phone = $request->phone;
        $traveller->address = $request->address;
        $traveller->countryID = $request->countryID;
        $traveller->save();

        $booking = new Createbooking;
        $booking->bookingno = $request->bookingsID;
        $booking->travellerID = $traveller->travellerID;
        $booking->tour = 1;
        $booking->type = 1;
        $booking->affiliatelink = $request->affiliate;
        if ($request->adult_number) {
            $booking->adult_number = $request->adult_number;
        }
        if ($request->child_number) {
            $booking->child_number = $request->child_number;
        }
        if ($request->infant_number) {
            $booking->infant_number = $request->infant_number;
        }
        $booking->entry_by = $user->id;
        $booking->owner_id = CNF_OWNER;
        $booking->save();

        $tour_date = Tourdates::where('tourdateID', $request->tourdateID)->where('owner_id', CNF_OWNER)->get()->first();

        if (!$tour_date) {
            abort(404);
        }

        $booktour = new Booktour;
        $booktour->bookingID = $booking->bookingsID;
        $booktour->tourcategoriesID = $tour_date->tourcategory->tourcategoriesID;
        $booktour->tourID = $tour_date->tourID;
        $booktour->tourdateID = $tour_date->tourdateID;
        $booktour->deposit_paid = 0;
        $booktour->status = 2;
        $booktour->entry_by = $user->id;
        $booktour->owner_id = CNF_OWNER;
        $booktour->admin_read = 0;
        $booktour->save();

        $duedate = Carbon::parse($tour_date->start)->subDays(30);

        $invoice = new Invoice;
        $invoice->travellerID = $traveller->travellerID;
        $invoice->bookingID = $booking->bookingsID;
        $invoice->InvTotal = 0;
        $invoice->Subtotal = 0;
        $invoice->currency = CNF_CURRENCY;
        $invoice->payment_type = 1;
        $invoice->notes = 'Simple Online Booking';
        $invoice->DateIssued = Carbon::today();
        $invoice->DueDate = $duedate;
        $invoice->discount = 0;
        $invoice->tax = 0;
        $invoice->status = 0;
        $invoice->entry_by = $user->id;
        $invoice->owner_id = CNF_OWNER;
        
        if($booking->booktour->tourdate->discount)
        {
            $invoice->discount = $booking->booktour->tourdate->discount;
        }
        
        $invoice->save();

        //if total deposit == 0 will redirect to thanks page
        // if (0 == $request->totaldeposit || !CNF_BILLPLZAPIKEY) {

        //     if (0 == $request->totaldeposit) {
        //         $deposit_payment = Booktour::where('bookingID', $booking->bookingsID)->update(['deposit_paid' => 1]);
        //     }
        //     DB::commit();
        //     return redirect('/simplebooking/paid');
        // }

        // $bill = $this->billplz->bill();

        // $url = 'http://' . CNF_DOMAIN . '/simplebooking/callback';

        // $response = $bill->create(
        //     CNF_BILLPLZCOLLECTIONID, //collection id
        //     $traveller->email, //email
        //     null, //given null. probably phone number
        //     $traveller->nameandsurname, //name
        //     $request->totaldeposit * 100, //payment amount
        //     ['callback_url' => $url, 'redirect_url' => 'http://' . CNF_DOMAIN . '/simplebooking/paid'],
        //     'Deposit Payment', //description
        //     ['reference_1' => $booking->bookingno, 'reference_1_label' => 'Booking No.']
        // );

        // $array = $response->toArray();
        // DB::commit();

        // Mail::send('createbooking.newbookingmail', ['booking' => $booking, 'booktour' => $booktour], function ($message) use ($traveller) {
        //     $message->to($traveller->email, $traveller->fullname)->subject('Maklumat Tempahan');
        //     $message->from(CNF_EMAIL, CNF_COMNAME);
        // });

        // return redirect($array['url']);

        if (0 == $request->totaldeposit || $this->payment_gateway_id == 0) {

            if (0 == $request->totaldeposit) {
                $deposit_payment = Booktour::where('bookingID', $booking['bookingsID'])->update(['deposit_paid' => 1]);
            }

            return redirect('/bookpackage/paid');
        }

        if($this->payment_gateway_id == 1) {

            $data = (object)[
                'type' => 'simplebooking',
                'session' => (array)$request->all(),
                'bookingID' => $booking
            ];

            return $this->payWithBillplz($data);

        } else if($this->payment_gateway_id == 2) {
            
            $bookroom   = Bookroom::where('bookingID', $request->bookingsID)->get();

            // $data = (object)[
            //     'type' => 'simplebooking',
            //     'session' => (array)$request->all(),
            //     'invoice' => $invoice,
            //     'bookingID' => $booking,
            //     'tour_date' => $tourdate,
            //     // 'bookroom' => $bookroom,
            //     'qty' => $amount,
            //     // 'price' => $amount,
            // ];

            $data = [
                'adult_number' => $request->adult_number,
                'child_number' => $request->child_number,
                'infant_number' => $request->infant_number,
                'tourdateID' => $request->tourdateID,
                'NRIC' => $request->NRIC,
                'nameandsurname' => $request->nameandsurname,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'countryID' => $request->countryID,
                'bookingsID' => $request->bookingsID,
                'affiliate' => $request->affiliate,
                'tourdateID' => $request->tourdateID,
                'totaldeposit' => $request->totaldeposit,
            ];

            return redirect(route('bookpackage.simplepaymentmethods', $data));

            // session(['simplebooking' => $data]);
            // // ;
            // // dd(Session::get('simplebooking'));
            // return redirect('bookpackage/payment-method')->with('simplebooking', $data);

            // // return $this->payWithBayarInd($data);
        }
    }

    public function simpleRegNewUser(Request $request)
    {
        // $data = $this->SessionData($request);
        $data = $request->all();
        Log::info('reg new');
        Log::info($data);
        $last_id = DB::table('tb_users')->select('id')->orderby('id', 'desc')->first();
        $id     = $last_id->id + 1;
        $string = 'umrah';
        $phone  = substr($data['phone'], 6);

        $code             = rand(10000, 10000000);
        $user             = new User();
        $user->username   = "{$string}{$id}{$phone}";
        $user->password   = Hash::make('password');
        $user->first_name = $data['nameandsurname'];
        $user->last_name  = $data['last_name'];
        $user->email      = $data['email'];
        $user->activation = $code;
        if (CNF_ACTIVATION == 'auto') {
            $user->active = '1';
        } else {
            $user->active = '0';
        }
        $user->group_id = 6;
        $user->owner_id = CNF_OWNER;
        $user->save();
        $password   = 'password';
        $email_data = ['username' => $user->email, 'name' => $user->first_name, 'password' => $password, 'activate' => $user->activation];
        Mail::send('layouts.'.CNF_THEME.'.tour.mail', ['email_data' => $email_data], function ($message) use ($user) {
            $message->to($user->email, $user->nameansurname)->subject('Maklumat Akaun Pengguna');
            $message->from('salam@oomrah.com', 'Oomrah');
        });

        return $user;
    }

    public function simpleCallback(Request $request)
    {
        $transaction_id = $request->all();
        $bills = $this->billplz->bill();
        $bill = $bills->get($transaction_id['id'])->toArray();
        $booking = Createbooking::where('bookingno', $bill['reference_1'])->get()->first();
        $invoice = $booking->invoice;

        if ('true' == $transaction_id['paid']) {
            $payment               = new Payments();
            $payment->travellerID  = Travellers::where('email', $bill['email'])->get()->first()->travellerID;
            $payment->invoiceID    = $invoice->invoiceID;
            $payment->amount       = $bill['paid_amount']->getMoney()->getAmount() / 100;
            $payment->currency     = CNF_CURRENCY;
            $payment->payment_type = 1;
            $payment->payment_date = Carbon::today();
            $payment->notes        = 'Simple Online Payment';
            $payment->entry_by     = User::where('email', $bill['email'])->get()->first()->id;
            $payment->received     = 1;
            $payment->owner_id     = CNF_OWNER;
            $payment->save();
        }

        $username = User::where('email', $bill['email'])->first();

        $email_data = [
            'username'     => $username->username,
            'state'        => $bill['state'],
            'amount_paid'  => $bill['paid_amount']->getMoney()->getAmount() / 100,
            'paid_at'      => $bill['due_at']->format('d M y'),
            'bookingno'    => $bill['reference_1'],
            'color'        => CNF_TEMPCOLOR,
            'logo'         => CNF_LOGO,
            'supportemail' => CNF_EMAIL,
            'supportphone' => CNF_TEL,
        ];

        Mail::send('layouts.'.CNF_THEME.'.tour.mailsuccesspay', ['email_data' => $email_data], function ($message) use ($bill) {
            $message->to($bill['email'], $bill['name'])->subject('Pembayaran Berjaya');
            $message->from('salam@oomrah.com', 'Oomrah');
        });
    }

    public function simpleRedirect(Request $request)
    {
        $model = new Tours();
        $info  = $model::makeInfo('tours');
        $data  = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];

        $data['email_owner'] = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();

        return view('layouts.' . CNF_THEME . '.tour.successnote', $data);
    }

    public function generateVirtualAccountNumber($companyCode = 10000, $phoneNumber = 0)
    {
        // echo preg_replace('/[^\p{L}\p{N}\s]/u', '', str_replace(' ', '', CNF_TEL));
        $userPhoneNumber = preg_replace('/[^\p{L}\p{N}\s]/u', '', str_replace(' ', '', (int)$phoneNumber));
        $setVANumber = $companyCode . $userPhoneNumber;
        $randomDate = (int)date('YmdHis');
        $vaNumber = 0;

        if(strlen($setVANumber) == 16) {
            $vaNumber = $setVANumber;
        } else {
            if(strlen($setVANumber) < 16) {
                $randomNumber = $setVANumber . $randomDate;
                $calculateLength = strlen($randomNumber) - 16;
                $mixDatePhonenumber = substr($randomDate . $userPhoneNumber, $calculateLength);
                $vaNumber = $companyCode . $mixDatePhonenumber;
            } else if(strlen($setVANumber) > 16) {
                $calculateLength = strlen($userPhoneNumber) - 11;
                $cutPhonenumber = substr($userPhoneNumber, $calculateLength);
                $vaNumber = $companyCode . $cutPhonenumber;
            }
        }

        if(strlen($vaNumber) > 16) {
            $vaNumber = substr($vaNumber, 0, -1);
        }

        return $vaNumber;
    }

    public function postPaymentFlag(Request $request) 
    {
        $invoice = Invoice::find($request->transactionNo);
        $invoicePayment = Payments::where('invoiceID', $request->transactionNo)->first();

        $dataSprint = array(
          "channelId"           => $request->channelId,
          "currency"            => $request->currency,
          "transactionNo"       => $request->transactionNo,
          "transactionAmount"   => $request->transactionAmount,
          "transactionDate"     => $request->transactionDate,
          "transactionStatus"   => $request->transactionStatus,
          "transactionMessage"  => $request->transactionMessage,
          "flagType"            => $request->flagType,
          "insertId"            => $request->insertId,
          "paymentReffId"       => $request->paymentReffId,
          "authCode"            => $request->authCode,
          "additionalData"      => $request->additionalData,
          "customerAccount"     => $request->customerAccount,
        );

        // Prepare response data
        $response   = array(
          "channelId"           => $request->channelId,      // Channel ID sent by Sprint
          "currency"            => $request->currency,       // Currency sent by Sprint (IDR)
          "paymentStatus"       => "",                          // Payment Status ( 00 => Success , 01,03 => Failed , 02 => isPaid , 04 => isExpired , 05 => isCancelled )
          "paymentMessage"      => "",                          // Payment Message
          "flagType"            => $request->flagType,       // Flag Type sent by Sprint
          "paymentReffId"       => $request->paymentReffId,  // Payment Referrence ID sent by Sprint
        );

        if($invoice) {

            $getInvoicePaymentMethod = InvoicePaymentMethod::where('invoiceID', $invoice->invoiceID)->first();

            $payment_status = '';
            $payment_status_message = '';

            if(!$invoicePayment) {
                $paymentMethodMeta = json_decode($getInvoicePaymentMethod->meta);

                $getPaymentMethod = $paymentMethodMeta->payment_method;
                $paymentMethods = json_decode(CNF_PAYMENT_GATEWAY_DATA);
                $paymentMethod = $paymentMethods->{$getPaymentMethod};

                $authCode = hash('sha256', 
                    $dataSprint['transactionNo'] . 
                    $dataSprint['transactionAmount'] . 
                    $dataSprint['channelId'] . 
                    $dataSprint['transactionStatus'] .
                    $dataSprint['insertId'] .
                    $paymentMethod->secret_key
                );

                if($dataSprint['channelId'] != $paymentMethod->channel_id) {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid Channel ID';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($dataSprint['currency'] != 'IDR') {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid Currency';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($dataSprint['transactionNo'] != $invoice->invoiceID) {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid Transaction Number';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($dataSprint['transactionAmount'] != $invoice->InvTotal) {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid Transaction Amount';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($dataSprint['transactionStatus'] != '00') {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid Transaction Status';
                    $payment_status = 'failed';
                    $payment_status_message = 'Failed Transaction';

                } else if($dataSprint['flagType'] != '11' && $dataSprint['flagType'] != '12' && $dataSprint['flagType'] != '13') {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid Flagtype';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($dataSprint['insertId'] != $getInvoicePaymentMethod->insertId) {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid InsertId';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($dataSprint['authCode'] != $authCode) {
                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid authCode';
                    $payment_status = 'failed';
                    $payment_status_message = 'Invalid Auth Code';

                } else if($invoice->booking->bookTour->status == '0') {
                    $response['paymentStatus'] = '05';
                    $response['paymentMessage'] = 'Transaction has been cancelled';
                    $payment_status = 'canceled';
                    $payment_status_message = 'Transaction has been canceled';
                // } else if($invoice->booking->bookTour->status == '2') {
                //     $response['paymentStatus'] = '05';
                //     $response['paymentMessage'] = 'Pending';
                } else if((
                        isset($paymentMethodMeta->account_number) && 
                        isset($dataSprint['customerAccount'])) && 
                        ($dataSprint['customerAccount'] != $paymentMethodMeta->account_number)) {

                    $response['paymentStatus'] = '01';
                    $response['paymentMessage'] = 'Invalid VA Number';
                    $payment_status = 'failed';
                    $payment_status_message = $response['paymentMessage'];

                } else if($invoice->pay_status == 'paid') {
                    $response['paymentStatus'] = '02';
                    $response['paymentMessage'] = 'Transaction has been paid';
                } else {

                    $amount = $dataSprint['transactionAmount'];

                    $totalBalance = $invoice->booking->balance - $amount;

                    Booktour::where('bookingID', $invoice->bookingID)->update(['deposit_paid' => 1]);

                    $user = User::where('email', $invoice->booking->traveller->email)->first();

                    $invoicePayment = new Payments;
                    $invoicePayment->travellerID = $invoice->bookingID;
                    $invoicePayment->invoiceID = $invoice->invoiceID;
                    $invoicePayment->amount = $amount;
                    $invoicePayment->currency = CNF_CURRENCY;
                    $invoicePayment->payment_type = 1;
                    $invoicePayment->payment_date = Carbon::now();
                    $invoicePayment->payment_prove = '';
                    $invoicePayment->notes = 'Online Payment';
                    $invoicePayment->entry_by = $user ? $user->id : 0;
                    $invoicePayment->received = 1;
                    $invoicePayment->owner_id = $invoice->owner_id;
                    $invoicePayment->save();

                    if($user) {
                        $email_data = [
                            'username'     => $user->username,
                            'amount_paid'  => $amount,
                            'paid_at'      => date('d M y', strtotime($invoicePayment->payment_date)),
                            'bookingno'    => $invoice->booking->bookingno,
                            'color'        => CNF_TEMPCOLOR,
                            'logo'         => CNF_LOGO,
                            'supportemail' => CNF_EMAIL,
                            'supportphone' => CNF_TEL,
                        ];

                        Mail::send('layouts.'.CNF_THEME.'.tour.mailsuccesspay', ['email_data' => $email_data], function ($message) use ($user) {
                            $message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Pembayaran Deposit Berjaya');
                            $message->from('salam@oomrah.com', 'Oomrah');
                        });
                    }
                    
                    $response['paymentStatus'] = '00';
                    $response['paymentMessage'] = 'Success';
                    $payment_status = 'success';
                    $payment_status_message = 'Transaction Success';

                }
            } else {
                $response['paymentStatus'] = '02';
                $response['paymentMessage'] = 'Transaction has been paid';

            }

            if($response['paymentStatus'] != '02') {
                $getInvoicePaymentMethod->payment_status = $payment_status;
                $getInvoicePaymentMethod->payment_status_message = $payment_status_message;
                $getInvoicePaymentMethod->save();
            }

        } else {
            $response['paymentStatus'] = '01';
            $response['paymentMessage'] = 'Invalid Transaction';
        }

        return response(json_encode($response))->header('Content-Type', 'application/json');
    }

    public function getBayarindRTO(Request $request)
    {
        $owner = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();
        $invoice = [];

        if($request->id) {
            $invoice = Invoice::find($request->id);

            if($invoice) {
                $getInvoicePaymentMethod = InvoicePaymentMethod::where('invoiceID', $invoice->invoiceID)->first();
                $getInvoicePaymentMethod->payment_status = 'timeout';
                $getInvoicePaymentMethod->payment_status_message = 'Your payment timed out';
                $getInvoicePaymentMethod->save();
            }
        }

        $data           = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
            'owner' => $owner,
            'invoice' => $invoice,
        ];

        return view('layouts.' . CNF_THEME . '.tour.payment_information_bayarind_rto', $data);
    }

    public function postCompleteSales(Request $request)
    {
        if($request->txnReference) {
            $getInvoicePaymentMethod = InvoicePaymentMethod::where('insertId', $request->txnReference)->first();
            
            if($getInvoicePaymentMethod) {

                $invoice = Invoice::find($getInvoicePaymentMethod->invoiceID);

                $owner = DB::table('tb_owners')->select('email', 'telephone')->where('id', CNF_OWNER)->first();

                $data           = [
                    'pageTitle'    => \Lang::get('core.packages'),
                    'pageMetakey'  => CNF_METAKEY,
                    'pageMetadesc' => CNF_METADESC,
                    'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
                    'homepage'     => 0,
                    'pageImage'    => CNF_HEADERIMAGE,
                    'owner' => $owner,
                    'invoice' => $invoice,
                ];

                return view('layouts.' . CNF_THEME . '.tour.bayarind_success_pay_gpn', $data);
            }
        }
    }

    public function postReceiveRequestAction(Request $request) 
    {
        $content = explode('.', $request->getContent());
        $response = json_decode($this->base64URLDecode($content[1]));

        Log::debug($this->base64URLDecode($content[1]));

        $getInvoicePaymentMethod = InvoicePaymentMethod::where('insertId', $response->txnResponse->txnReference)->first();
        
        if($getInvoicePaymentMethod) {

            $invoiceMethod = json_decode($getInvoicePaymentMethod->meta);
            $invoice = Invoice::find($getInvoicePaymentMethod->invoiceID);
            $invoicePayment = Payments::where('invoiceID', $getInvoicePaymentMethod->invoiceID)->first();
            $user = User::where('email', $invoice->booking->traveller->email)->first();

            if($response->txnResponse->txnStatus == 'Completed') {

                Booktour::where('bookingID', $invoice->bookingID)->update(['deposit_paid' => 1]);

                $invoicePayment = new Payments;
                $invoicePayment->travellerID = $invoice->bookingID;
                $invoicePayment->invoiceID = $invoice->invoiceID;
                $invoicePayment->amount = $invoiceMethod->price;
                $invoicePayment->currency = CNF_CURRENCY;
                $invoicePayment->payment_type = 1;
                $invoicePayment->payment_date = Carbon::now();
                $invoicePayment->payment_prove = '';
                $invoicePayment->notes = 'Online Payment';
                $invoicePayment->entry_by = $user ? $user->id : 0;
                $invoicePayment->received = 1;
                $invoicePayment->owner_id = $invoice->owner_id;
                $invoicePayment->save();

                $getInvoicePaymentMethod->payment_status = 'completed';
                $getInvoicePaymentMethod->payment_status_message = 'Your transaction has been completed';
                $getInvoicePaymentMethod->save();

                if($user) {
                    $email_data = [
                        'username'     => $user->username,
                        'amount_paid'  => $invoicePayment->amount,
                        'paid_at'      => date('d M y', strtotime($invoicePayment->payment_date)),
                        'bookingno'    => $invoice->booking->bookingno,
                        'color'        => CNF_TEMPCOLOR,
                        'logo'         => CNF_LOGO,
                        'supportemail' => CNF_EMAIL,
                        'supportphone' => CNF_TEL,
                    ];

                    Mail::send('layouts.'.CNF_THEME.'.tour.mailsuccesspay', ['email_data' => $email_data], function ($message) use ($user) {
                        $message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Pembayaran Deposit Berjaya');
                        $message->from('salam@oomrah.com', 'Oomrah');
                    });
                }
            } else {
                $getInvoicePaymentMethod->payment_status = 'failed';
                $getInvoicePaymentMethod->payment_status_message = 'Your transaction has been failed';
                $getInvoicePaymentMethod->save();
            }
        } else {
            return 'no records found';
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
