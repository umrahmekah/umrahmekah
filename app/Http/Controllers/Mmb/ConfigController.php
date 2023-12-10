<?php

namespace App\Http\Controllers\mmb;

use App\Http\Controllers\controller;
use App\Models\Core\Groups;
use App\Models\Core\Owners;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Validator;
use DB;

class ConfigController extends Controller
{
    public $module = 'owners';

    public function __construct()
    {
        parent::__construct();
        $this->model  = new Owners();
        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle' => \Lang::get('core.t_generalsetting'),
            'pageNote'  => \Lang::get('core.t_generalsettingsmall'),
            'active'    => 'menu',
        ];
    }

    public function getIndex()
    {
        $this->data = [
            'pageTitle' => \Lang::get('core.t_generalsetting'),
            'pageNote'  => \Lang::get('core.t_generalsettingsmall'),
        ];
        $this->data['active'] = '';

        return view('mmb.config.index', $this->data);
    }

    public function postSave(Request $request)
    {
        $rules = [
            'name'  => 'required|min:2',
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules);
        if (! $validator->fails()) {
            $logo = '';
            $data = $this->validatePost('tb_owners');
            if (! is_null(Input::file('logo'))) {
                $file            = Input::file('logo');
                $destinationPath = public_path() . '/uploads/images/' . CNF_OWNER . '/';
                $filename        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension();
                $logo            = 'logo-'. time() . '.' . $extension;
                $uploadSuccess   = $file->move($destinationPath, $logo);
                $data['logo']    = $logo;
            }

            $headerimage = '';
            if (! is_null(Input::file('headerimage'))) {
                $file                 = Input::file('headerimage');
                $destinationPath      = public_path() . '/uploads/images/' . CNF_OWNER . '/';
                $filename             = $file->getClientOriginalName();
                $extension            = $file->getClientOriginalExtension();
                $headerimage          = 'header.' . $extension;
                $uploadSuccess        = $file->move($destinationPath, $headerimage);
                $data['header_image'] = $headerimage;
            }

            $this->model->insertRow($data, CNF_OWNER);

            return Redirect::to('core/config')->with('messagetext', \Lang::get('core.settingsaved'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/config')->with('messagetext', \Lang::get('core.followingerror'))->with('msgstatus', 'success')
            ->withErrors($validator)->withInput();
        }
    }

    public function getEmail()
    {
        $regEmail   = base_path() . '/resources/views/user/emails/registration.blade.php';
        $resetEmail = base_path() . '/resources/views/user/emails/auth/reminder.blade.php';
        $formEmail  = base_path() . '/resources/views/user/emails/form.blade.php';
        $this->data = [
            'groups'     => Groups::all(),
            'pageTitle'  => \Lang::get('core.t_emailtemplate'),
            'pageNote'   => \Lang::get('core.t_emailtemplatesmall'),
            'regEmail'   => file_get_contents($regEmail),
            'resetEmail' => file_get_contents($resetEmail),
            'formEmail'  => file_get_contents($formEmail),
            'active'     => 'email',
        ];

        return view('mmb.config.email', $this->data);
    }

    public function postEmail(Request $request)
    {
        //print_r($_POST);exit;
        $rules = [
            'regEmail'   => 'required|min:10',
            'resetEmail' => 'required|min:10',
            'formEmail'  => 'required|min:10',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $regEmailFile   = base_path() . '/resources/views/user/emails/registration.blade.php';
            $resetEmailFile = base_path() . '/resources/views/user/emails/auth/reminder.blade.php';
            $formEmailFile  = base_path() . '/resources/views/user/emails/form.blade.php';

            $fp = fopen($regEmailFile, 'w+');
            fwrite($fp, $_POST['regEmail']);
            fclose($fp);

            $fp = fopen($resetEmailFile, 'w+');
            fwrite($fp, $_POST['resetEmail']);
            fclose($fp);

            $fp = fopen($formEmailFile, 'w+');
            fwrite($fp, $_POST['formEmail']);
            fclose($fp);

            return Redirect::to('core/config/email')->with('messagetext', \Lang::get('core.emailupdated'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/config/email')->with('messagetext', \Lang::get('core.followingerror'))->with('msgstatus', 'success')
            ->withErrors($validator)->withInput();
        }
    }

    public function getSecurity()
    {
        $this->data = [
            'groups'    => Groups::all(),
            'pageTitle' => \Lang::get('core.t_loginsecurity'),
            'pageNote'  => \Lang::get('core.t_loginsecuritysmall'),
            'active'    => 'security',
        ];

        return view('mmb.config.security', $this->data);
    }

    public function postLogin(Request $request)
    {
        $rules = [
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data                     = $this->validatePost('tb_owners');
            $data['mode']             = (isset($data['mode']) ? 'production' : 'development');
            $data['multi_language']   = (isset($data['multi_language']) ? 1 : 0);
            $data['registration']     = (isset($data['registration']) ? 'true' : 'false');
            $data['front']            = (isset($data['front']) ? 'true' : 'false');
            $data['captcha']          = (isset($data['captcha']) ? 'true' : 'false');
            $data['maintenance']      = (isset($data['maintenance']) ? 'ON' : '');
            $data['show_help']        = (isset($data['show_help']) ? 'ON' : '');
            $data['show_testimonial'] = (isset($data['show_testimonial']) ? 'ON' : '');
            $data['show_tour']        = (isset($data['show_tour']) ? 'ON' : '');
            $data['booking_form']     = $request->booking_form;
            $data['date']             = (! is_null($data['date']) ? $data['date'] : 'd M Y');

            $this->model->insertRow($data, CNF_OWNER);

            return Redirect::to('core/config/security')->with('messagetext', \Lang::get('core.settingsaved'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/config/security')->with('messagetext', \Lang::get('core.followingerror'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getBillplz()
    {
        $this->data = [
            'groups'    => Groups::all(),
            'pageTitle' => \Lang::get('core.billplz_integration'),
            'pageNote'  => \Lang::get('core.billplz_integration'),
            'active'    => 'billplz',
        ];

        return view('mmb.config.billplz', $this->data);
    }

    public function postBillplz(Request $request)
    {
        $rules = [
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = [
                'billplz_api_key' => $request->billplz_api_key,
                'billplz_signature_key' => $request->billplz_signature_key,
                'billplz_collection_id' => $request->billplz_collection_id
            ];

            DB::table('tb_owners')->where('id', CNF_OWNER)->update($data);

            return Redirect::to('core/config/billplz')->with('messagetext', \Lang::get('core.settingsaved'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/config/billplz')->with('messagetext', \Lang::get('core.followingerror'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getPaymentIntegration()
    {
        $payments = PaymentGateway::where('status', 1)->get();

        $this->data = [
            'groups'    => Groups::all(),
            'pageTitle' => \Lang::get('core.payment_integration'),
            'pageNote'  => \Lang::get('core.payment_integration'),
            'active'    => 'payment-integration',
            'payments' => $payments,
            'payment_config' => json_decode(CNF_PAYMENT_GATEWAY_DATA)
        ];

        return view('mmb.config.payment_integration', $this->data);
    }

    public function postPaymentIntegration(Request $request)
    {
        $payment_gateway_data = isset($request->payment_gateway_data[$request->payment_gateway_id]) ? $request->payment_gateway_data[$request->payment_gateway_id] : NULL;

        if($request->payment_gateway_id == 1) {
            $data = [
                'billplz_api_key' => $request->billplz_api_key,
                'billplz_signature_key' => $request->billplz_signature_key,
                'billplz_collection_id' => $request->billplz_collection_id,
                'payment_gateway_id' => $request->payment_gateway_id,
                'payment_gateway_data' => NULL,
            ];

            DB::table('tb_owners')->where('id', CNF_OWNER)->update($data);
        } else {
            $update_data = [
                'billplz_api_key' => NULL,
                'billplz_signature_key' => NULL,
                'billplz_collection_id' => NULL,
                'payment_gateway_id' => $request->payment_gateway_id,
                'payment_gateway_data' => $payment_gateway_data ? json_encode($payment_gateway_data) : NULL
            ];

            DB::table('tb_owners')->where('id', CNF_OWNER)->update($update_data);
        }    

        return Redirect::to('core/config/payment-integration')->with('messagetext', \Lang::get('core.settingsaved'))->with('msgstatus', 'success');
    }

    public function getLog($type = null)
    {
        $this->data = [
            'pageTitle' => \Lang::get('core.m_clearcache'),
            'pageNote'  => \Lang::get('core.dash_clearcache'),
            'active'    => 'log',
        ];

        return view('mmb.config.log', $this->data);
    }

    public function getClearlog()
    {
        $dir = base_path() . '/storage/logs';
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                //removedir($file);
            } else {
                unlink($file);
            }
        }

        $dir = base_path() . '/storage/framework/views';
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                //removedir($file);
            } else {
                unlink($file);
            }
        }

        return response()->json([
            'status'  => \Lang::get('core.note_t_success'),
            'message' => \Lang::get('core.note_success_action'),
        ]);

        //return Redirect::to('mmb/config/log')->with('messagetext','Cache has been cleared !')->with('msgstatus','success');
    }

    public function removeDir($dir)
    {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                removedir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

    public function getTranslation(Request $request, $type = null)
    {
        if (! is_null($request->input('edit'))) {
            $file  = (! is_null($request->input('file')) ? $request->input('file') : 'core.php');
            $files = scandir(base_path() . '/resources/lang/' . $request->input('edit') . '/');

            //$str = serialize(file_get_contents('./protected/app/lang/'.$request->input('edit').'/core.php'));
            $str = \File::getRequire(base_path() . '/resources/lang/' . $request->input('edit') . '/' . $file);

            $this->data = [
                'pageTitle'  => 'Translation',
                'pageNote'   => 'Add Multilanguage Option',
                'stringLang' => $str,
                'lang'       => $request->input('edit'),
                'files'      => $files,
                'file'       => $file,
                'active'     => 'translation',
            ];
            $template = 'edit';
        } else {
            $this->data = [
                'pageTitle' => 'Translation',
                'pageNote'  => 'Add Multilangues Option',
                'active'    => 'translation',
            ];
            $template = 'index';
        }

        return view('mmb.config.translation.' . $template, $this->data);
    }

    public function getAddtranslation()
    {
        return view('mmb.config.translation.create');
    }

    public function postAddtranslation(Request $request)
    {
        $rules = [
            'name'   => 'required',
            'folder' => 'required|alpha',
            'author' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $template = base_path();

            $folder = $request->input('folder');
            mkdir($template . '/resources/lang/' . $folder, 0777);

            $info = json_encode(['name' => $request->input('name'), 'folder' => $folder, 'author' => $request->input('author')]);
            $fp   = fopen($template . '/resources/lang/' . $folder . '/info.json', 'w+');
            fwrite($fp, $info);
            fclose($fp);

            $files = scandir($template . '/resources/lang/en/');
            foreach ($files as $f) {
                if ('.' != $f and '..' != $f and 'info.json' != $f) {
                    copy($template . '/resources/lang/en/' . $f, $template . '/resources/lang/' . $folder . '/' . $f);
                }
            }

            return Redirect::to('core/config/translation')->with('messagetext', \Lang::get('core.translationadded'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/config/translation')->with('messagetext', \Lang::get('core.translationfailed'))->with('msgstatus', 'error')->withErrors($validator)->withInput();
        }
    }

    public function postSavetranslation(Request $request)
    {
        $template = base_path();

        $form = "<?php \n";
        $form .= "return array( \n";
        foreach ($_POST as $key => $val) {
            if ('_token' != $key && 'lang' != $key && 'file' != $key) {
                if (! is_array($val)) {
                    $form .= '"' . $key . '"=> "' . strip_tags($val) . '", ' . " \n ";
                } else {
                    $form .= '"' . $key . '"=> array( ' . " \n ";
                    foreach ($val as $k => $v) {
                        $form .= '      "' . $k . '"=> "' . strip_tags($v) . '", ' . " \n ";
                    }
                    $form .= "), \n";
                }
            }
        }
        $form .= ');';
        //echo $form; exit;
        $lang     = $request->input('lang');
        $file     = $request->input('file');
        $filename = $template . '/resources/lang/' . $lang . '/' . $file;
        //	$filename = 'lang.php';
        $fp = fopen($filename, 'w+');
        fwrite($fp, $form);
        fclose($fp);

        return Redirect::to('core/config/translation?edit=' . $lang . '&file=' . $file)
        ->with('messagetext', \Lang::get('core.translationsaved'))->with('msgstatus', 'success');
    }

    public function getRemovetranslation($folder)
    {
        self::removeDir(base_path() . '/resources/lang/' . $folder);

        return Redirect::to('core/config/translation')->with('messagetext', \Lang::get('core.translationremoved'))->with('msgstatus', 'success');
    }
}
