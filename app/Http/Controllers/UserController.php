<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Input;
use Mail;
use Redirect;
use Session;
use Socialize;
use Validator;

class UserController extends Controller
{
    protected $layout = 'layouts.main';

    public function __construct()
    {
        parent::__construct();
    }

    public function getRegister()
    {
        if (CNF_REGIST == 'false') :
                if (\Auth::check()):
                    return Redirect::to('')->with('message', \SiteHelpers::alert('success', 'Youre already login')); else:
                    return Redirect::to('user/login');
        endif; else :

                    return view('user.register');
        endif;
    }

    public function postCreate(Request $request)
    {
        $rules = [
            'username'              => 'required|alpha|between:3,12|unique:tb_users',
            'firstname'             => 'required|alpha_num|min:2',
            'lastname'              => 'required|alpha_num|min:2',
            'email'                 => 'required|email|unique:tb_users',
            'password'              => 'required|between:6,12|confirmed',
            'password_confirmation' => 'required|between:6,12',
            ];
        if (CNF_RECAPTCHA == 'true') {
            $rules['recaptcha_response_field'] = 'required|recaptcha';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $code = rand(10000, 10000000);

            $authen             = new User();
            $authen->username   = $request->input('username');
            $authen->first_name = $request->input('firstname');
            $authen->last_name  = $request->input('lastname');
            $authen->email      = trim($request->input('email'));
            $authen->activation = $code;
            $authen->group_id   = 6;
            $authen->password   = \Hash::make($request->input('password'));
            if (CNF_ACTIVATION == 'auto') {
                $authen->active = '1';
            } else {
                $authen->active = '0';
            }
            $authen->owner_id = CNF_OWNER;
            $authen->save();

            $data = [
                'username'  => $request->input('username'),
                'firstname' => $request->input('firstname'),
                'lastname'  => $request->input('lastname'),
                'email'     => $request->input('email'),
                'password'  => $request->input('password'),
                'code'      => $code,
                'subject'   => '[ ' . CNF_COMNAME . ' ] REGISTRATION ',
            ];
            if (CNF_ACTIVATION == 'confirmation') {
                $to      = $request->input('email');
                $subject = '[ ' . CNF_COMNAME . ' ] REGISTRATION ';

                if (defined('CNF_MAIL') && CNF_MAIL == 'swift') {
                    \Mail::send('user.emails.registration', $data, function ($message) use ($data) {
                        $message->to($data['email'])->subject($data['subject']);
                    });
                } else {
                    $message = view('user.emails.registration', $data);
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: ' . CNF_COMNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
                    mail($to, $subject, $message, $headers);
                }

                $message = 'Thanks for registering! . Please check your inbox and follow activation link';
            } elseif (CNF_ACTIVATION == 'manual') {
                $message = 'Thanks for registering! . We will validate your account before your account is activated';
            } else {
                $message = 'Thanks for registering! . Your account is active now ';
            }

            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success', $message));
        } else {
            return Redirect::to('user/register')->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }
    }

    public function getActivationre(Request $request)
    {
        return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success', 'Your account is active now!'));
    }

    public function getActivation(Request $request)
    {
        $num = $request->input('code');
        if ('' == $num) {
            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Invalid Code Activation!'));
        }

        $user = User::where('activation', '=', $num)->get();
        if (count($user) >= 1) {
            \DB::table('tb_users')->where('activation', $num)->update(['active' => 1, 'activation' => '']);

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
window.location.replace("/user/activationre");
</script>
</body>
</html>
';
        // return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Your account is active now!'));
        } else {
            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Invalid Code Activation!'));
        }
    }

    public function getLogin()
    {
        if (\Auth::check()) {
            return Redirect::to('')->with('message', \SiteHelpers::alert('success', 'You are already logged in'));
        } else {
            $this->data['socialize'] = config('services');

            return View('user.login', $this->data);
        }
    }

    public function postSignin(Request $request)
    {
        $rules = [
            'email'    => 'required',
            'password' => 'required',
        ];
        if (CNF_RECAPTCHA == 'true') {
            $rules['captcha'] = 'required|captcha';
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $remember = (! is_null($request->get('remember')) ? 'true' : 'false');

            if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember)
                           or \Auth::attempt(['username' => $request->input('email'), 'password' => $request->input('password')], $remember)
        ) {
                if (\Auth::check()) {
                    $row = User::find(\Auth::user()->id);

                    //check superadmin or owner
                    if (! (CNF_OWNER == $row->owner_id or 1 == $row->group_id)) {
                        if (true == $request->ajax()) {
                            return response()->json(['status' => 'error', 'message' => 'Your username/password combination was incorrect']);
                        } else {
                            return Redirect::to('user/login')
                                ->with('message', \SiteHelpers::alert('error', 'Your username/password combination was incorrect'))
                                ->withInput();
                        }
                    }

                    //check account status
                    if ('0' == $row->active) {
                        // inactive
                        if (true == $request->ajax()) {
                            return response()->json(['status' => 'error', 'message' => 'Your Account is not active']);
                        } else {
                            \Auth::logout();

                            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Your Account is not active'));
                        }
                    } elseif ('2' == $row->active) {
                        if (true == $request->ajax()) {
                            return response()->json(['status' => 'error', 'message' => 'Your Account is BLocked']);
                        } else {
                            // BLocked users
                            \Auth::logout();

                            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Your Account is blocked'));
                        }
                    } else {
                        \DB::table('tb_users')->where('id', '=', $row->id)->update(['last_login' => date('Y-m-d H:i:s')]);
                        \Session::put('uid', $row->id);
                        \Session::put('gid', $row->group_id);
                        \Session::put('eid', $row->email);
                        \Session::put('ll', $row->last_login);
                        \Session::put('fid', $row->first_name . ' ' . $row->last_name);
                        \Session::put('username', $row->username);
                        \Session::put('join', $row->created_at);
                        /* Set Lang if available */
                        if (! is_null($request->input('language'))) {
                            \Session::put('lang', $request->input('language'));
                        } else {
                            \Session::put('lang', CNF_LANG);
                        }

                        \SiteHelpers::auditTrail($request,  $row->id . ' - ' . $row->first_name . ' Has logged in !');

                        if (true == $request->ajax()) {
                            if (CNF_FRONT == 'false') :
                                return response()->json(['status' => 'success', 'url' => url(Session::get('previous_path'))]); 
                            elseif (1 == $row->group_id || 2 == $row->group_id) : //access to dashboard for superadmin/admin
                                return response()->json(['status' => 'success', 'url' => url('dashboard')]); 
                            elseif (3 == $row->group_id) :
                                return response()->json(['status' => 'success', 'url' => url('invoice')]); 
                            elseif (4 == $row->group_id || 5 == $row->group_id) :
                                return response()->json(['status' => 'success', 'url' => url('createbooking')]); 
                            elseif (6 == $row->group_id) :
                                return response()->json(['status' => 'success', 'url' => url('user/profile')]);
                            endif;
                        } else {
                            if (CNF_FRONT == 'false') :
                                return Redirect::to('dashboard'); 
                            elseif (1 == $row->group_id || 2 == $row->group_id) : //access to dashboard for superadmin/admin
                                return Redirect::to('dashboard'); 
                            else :
                                return Redirect::to('user/profile');
                            endif;
                        }
                    }
                }
            } else {
                if (true == $request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Your username/password combination was incorrect']);
                } else {
                    return Redirect::to('user/login')
                        ->with('message', \SiteHelpers::alert('error', 'Your username/password combination was incorrect'))
                        ->withInput();
                }
            }
        } else {
            if (true == $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'The following  errors occurred']);
            } else {
                return Redirect::to('user/login')
                        ->with('message', \SiteHelpers::alert('error', 'The following  errors occurred'))
                        ->withErrors($validator)->withInput();
            }
        }
    }

    public function getProfile()
    {
        if (! \Auth::check()) {
            return redirect('user/login');
        }

        $info       = User::find(\Auth::user()->id);
        $this->data = [
            'pageTitle' => 'My Profile',
            'pageNote'  => 'View Detail My Info',
            'info'      => $info,
        ];

        return view('user.profile', $this->data);
    }

    public function postSaveprofile(Request $request)
    {
        if (! \Auth::check()) {
            return Redirect::to('user/login');
        }
        $rules = [
            'first_name' => 'required|alpha_num|min:2',
            'last_name'  => 'required|alpha_num|min:2',
            ];

        if ($request->input('email') != \Session::get('eid')) {
            $rules['email'] = 'required|email|unique:tb_users';
        }

        if (! is_null(Input::file('avatar'))) {
            $rules['avatar'] = 'mimes:jpg,jpeg,png,gif,bmp';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            if (! is_null(Input::file('avatar'))) {
                $file            = $request->file('avatar');
                $destinationPath = './uploads/users/';
                $filename        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension(); //if you need extension of the file
                $newfilename     = \Session::get('uid') . '.' . $extension;
                $uploadSuccess   = $request->file('avatar')->move($destinationPath, $newfilename);
                if ($uploadSuccess) {
                    $data['avatar'] = $newfilename;
                }
            }

            $user             = User::find(\Session::get('uid'));
            $user->first_name = $request->input('first_name');
            $user->last_name  = $request->input('last_name');
            $user->email      = $request->input('email');
            if (isset($data['avatar'])) {
                $user->avatar = $newfilename;
            }
            $user->save();

            $newUser = User::find(\Session::get('uid'));

            \Session::put('fid', $newUser->first_name . ' ' . $newUser->last_name);

            return Redirect::to('user/profile')->with('messagetext', 'Profile has been saved!')->with('msgstatus', 'success');
        } else {
            return Redirect::to('user/profile')->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postSavepassword(Request $request)
    {
        $rules = [
            'password'              => 'required|between:6,12|confirmed',
            'password_confirmation' => 'required|between:6,12',
            ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user           = User::find(\Session::get('uid'));
            $user->password = \Hash::make($request->input('password'));
            $user->save();

            return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success', 'Password has been saved!'));
        } else {
            return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }
    }

    public function getReminder()
    {
        return view('user.remind');
    }

    public function postRequest(Request $request)
    {
        $rules = [
            'credit_email' => 'required|email',
        ];

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $user = User::where('email', '=', $request->input('credit_email'));
            if ($user->count() >= 1) {
                $user    = $user->get();
                $user    = $user[0];
                $to      = $request->input('credit_email');
                $subject = '[ ' . CNF_COMNAME . ' ] REQUEST PASSWORD RESET ';
                $data    = [
                    'token'   => $request->input('_token'),
                    'email'   => $to,
                    'subject' => $subject,
                    ];

                if (defined('CNF_MAIL') && CNF_MAIL == 'swift') {
                    Mail::send('user.emails.auth.reminder', $data, function ($message) use ($data) {
                        $message->to($data['email'])->subject($data['subject']);
                    });
                } else {
                    $message = view('user.emails.auth.reminder', $data);
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: ' . CNF_COMNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
                    mail($to, $subject, $message, $headers);
                }

                $affectedRows = User::where('email', '=', $user->email)
                                ->update(['reminder' => $request->input('_token')]);

                return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success', 'Please check your email'));
            } else {
                return Redirect::to('user/login?reset')->with('message', \SiteHelpers::alert('error', 'Cant find email address'));
            }
        } else {
            return Redirect::to('user/login?reset')->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }
    }

    public function getReset($token = '')
    {
        if (\Auth::check()) {
            return Redirect::to('dashboard');
        }

        $user = User::where('reminder', '=', $token);
        if ($user->count() >= 1) {
            $this->data['verCode'] = $token;

            return view('user.remind', $this->data);
        } else {
            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Cant find your reset code'));
        }
    }

    public function postDoreset(Request $request, $token = '')
    {
        $rules = [
            'password'              => 'required|alpha_num|between:6,12|confirmed',
            'password_confirmation' => 'required|alpha_num|between:6,12',
            ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $user = User::where('reminder', '=', $token);
            if ($user->count() >= 1) {
                $data           = $user->get();
                $user           = User::find($data[0]->id);
                $user->reminder = '';
                $user->password = \Hash::make($request->input('password'));
                $user->save();
            }

            return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success', 'Password has been saved!'));
        } else {
            return Redirect::to('user/reset/' . $token)->with('message', \SiteHelpers::alert('error', 'The following errors occurred')
            )->withErrors($validator)->withInput();
        }
    }

    public function getLogout()
    {
        $currentLang = \Session::get('lang');
        \Auth::logout();
        \Session::flush();
        \Session::put('lang', $currentLang);

        return Redirect::to('')->with('message', \SiteHelpers::alert('info', 'Your are now logged out!'));
    }

    public function getSocialize($social)
    {
        return Socialize::with($social)->redirect();
    }

    public function getAutosocial($social)
    {
        $user = Socialize::with($social)->user();
        $user = User::where('email', $user->email)->first();

        return self::autoSignin($user);
    }

    public function autoSignin($user)
    {
        if (is_null($user)) {
            return Redirect::to('user/login')
                ->with('message', \SiteHelpers::alert('error', 'You have not registered yet '))
                ->withInput();
        } else {
            Auth::login($user);
            if (Auth::check()) {
                $row = User::find(\Auth::user()->id);

                if ('0' == $row->active) {
                    // inactive
                    Auth::logout();

                    return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Your Account is not active'));
                } elseif ('2' == $row->active) {
                    // BLocked users
                    Auth::logout();

                    return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error', 'Your Account is BLocked'));
                } else {
                    Session::put('uid', $row->id);
                    Session::put('gid', $row->group_id);
                    Session::put('eid', $row->group_email);
                    Session::put('fid', $row->first_name . ' ' . $row->last_name);
                    if (CNF_FRONT == 'false') :
                        return Redirect::to('dashboard'); else :
                        return Redirect::to('');
                    endif;
                }
            }
        }
    }
}
