<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tours;
use App\Models\Tourdates;
use Input;
use Mail;
use Redirect;
use Validator;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->data['pageLang'] = 'en';
        if ('' != \Session::get('lang')) {
            $this->data['pageLang'] = \Session::get('lang');
        }
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (CNF_FRONT == 'false' && '' == $request->segment(1)) :
            return Redirect::to('dashboard');
        endif;

        if (CNF_MAINTENANCE == 'ON') :
            return view('errors.maintenance');
        endif;

        $page = $request->segment(1);

        if ('' != $page) :
            $content = \DB::table('tb_pages')->where('owner_id', '=', CNF_OWNER)->where('alias', '=', $page)->where('status', '=', 'enable')->get();
            \DB::table('tb_pages')->where('owner_id', '=', CNF_OWNER)->where('alias', '=', $page)->update(['views' => \DB::raw('views+1')]);

            if (count($content) >= 1) {
                $row                        = $content[0];
                $this->data['pageTitle']    = $row->title;
                $this->data['pageImage']    = ('' != $row->image ? $row->image : CNF_HEADERIMAGE);
                $this->data['pageNote']     = $row->note;
                $this->data['pageMetakey']  = ('' != $row->metakey ? $row->metakey : CNF_METAKEY);
                $this->data['pageMetadesc'] = ('' != $row->metadesc ? $row->metadesc : CNF_METADESC);
                $this->data['homepage']     = $row->default;
                $this->data['breadcrumb']   = 'active';

                if ('' != $row->access) {
                    $access = json_decode($row->access, true);
                } else {
                    $access = [];
                }

                // If guest not allowed
                if (1 != $row->allow_guest) {
                    $group_id = \Session::get('gid');
                    $isValid  = (isset($access[$group_id]) && 1 == $access[$group_id] ? 1 : 0);
                    if (0 == $isValid) {
                        return Redirect::to('')
                                ->with('message', \SiteHelpers::alert('error', \Lang::get('core.note_restric')));
                    }
                }

                if (file_exists(base_path() . '/resources/views/layouts/' . CNF_THEME . '/template/' . $row->filename . '.blade.php') && '' != $row->filename) {
                    $page_template = 'layouts.' . CNF_THEME . '.template.' . $row->filename;
                } else {
                    $page_template = 'layouts.' . CNF_THEME . '.template.page';
                }

                $this->data['content']  = \PostHelpers::formatContent($row->note);
                $this->data['filename'] = $row->filename;

                if ('backend' == $row->template) {
                    $this->data['pageNote'] = 'View';

                    return view($page_template, $this->data);
                } else {
                    $this->data['pages'] = $page_template;
                    $page                = 'layouts.' . CNF_THEME . '.index';

                    return view($page, $this->data);
                }
            } else {
                return Redirect::to('')
                        ->with('message', \SiteHelpers::alert('error', \Lang::get('core.note_noexists')));
            } 
        else :

            $sql = \DB::table('tb_pages')->where('owner_id', '=', CNF_OWNER)->where('default', 1)->get();
            if (count($sql) < 1) {
                $sql = \DB::table('tb_pages')->where('owner_id', '=', 0)->where('default', 1)->get();
                $row = $sql[0];

            //     //get banner header
            //     $banners = \DB::table('banners')->where('owner_id', '=', 0)->where('position_name', $row->header)->orderby('sort')->get();
            } else {
                $row = $sql[0];}
            //     //get banner header
            //     if (empty($row->header)) {
            //         $banners = null;
            //     } else {
                    $banners = \DB::table('banners')->where('owner_id', '=', CNF_OWNER)->orderby('sort')->get();
            //     }
            // }

            //Featured Package
            if (CNF_SHOWTOUR == 'ON') {
                $packages = Tours::where('owner_id', '=', CNF_OWNER)
                                    ->where('status', '=', 1)
                                    ->where('featured', '=', 1)
                                    ->get()
                                    ->filter(function ($query) {
                                        return $query->b2b != 1;
                                    });
                
                $tourdates = \DB::table('tour_date')
                                ->where('owner_id', '=', CNF_OWNER)
                                ->where('status', '=', 1)
                                ->where('start', '>', \Carbon::today())
                                ->get();
            
                
            } else {
                $packages = null;
            }

            //Testimonial
            if (CNF_SHOWTESTIMONIAL == 'ON') {
                $testimonials = \DB::table('testimonials')->
                                    where('testimonials.owner_id', '=', CNF_OWNER)->where('testimonials.status', '=', 1)
                                    ->leftJoin('tours', 'testimonials.tour_name', '=', 'tours.tourID')
                                    ->get();
            } else {
                $testimonials = null;
            }

            $this->data['pageTitle']    = $row->title;
            $this->data['pageImage']    = ('' != $row->image ? $row->image : CNF_HEADERIMAGE);
            $this->data['pageNote']     = $row->note;
            $this->data['breadcrumb']   = 'inactive';
            $this->data['pageMetakey']  = $row->metakey;
            $this->data['pageMetadesc'] = $row->metadesc;
            $this->data['filename']     = $row->filename;
            $this->data['homepage']     = $row->default;
            $this->data['banners']      = $banners;
            $this->data['packages']     = $packages;
            $this->data['tourdates']    = $tourdates;
            $this->data['testimonials'] = $testimonials;
            $this->data['isMobile'] = $this->isMobile();

            if (file_exists(base_path() . '/resources/views/layouts/' . CNF_THEME . '/template/' . $row->filename . '.blade.php') && '' != $row->filename) {
                $page_template = 'layouts.' . CNF_THEME . '.template.' . $row->filename;
            } else {
                $page_template = 'layouts.' . CNF_THEME . '.template.page';
            }

            $this->data['pages']   = $page_template;
            $this->data['content'] = \PostHelpers::formatContent($row->note);
            $page                  = 'layouts.' . CNF_THEME . '.index';
            // dd($this->data);
            return view($page, $this->data);

        endif;
    }

    public function getLang($lang = 'en')
    {
        \Session::put('lang', $lang);

        return  Redirect::back();
    }

    public function getSkin($skin = 'mmb')
    {
        \Session::put('themes', $skin);

        return  Redirect::back();
    }

    public function postContact(Request $request)
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $rules = [
                'name'    => 'required',
                'subject' => 'required',
                'message' => 'required|min:20',
                'sender'  => 'required|email',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $data       = ['name' => $request->input('name'), 'sender' => $request->input('sender'), 'subject' => $request->input('subject'), 'notes' => $request->input('message')];
            $message    = view('emails.contact', $data);
            $data['to'] = CNF_EMAIL;
            if (defined('CNF_MAIL') && CNF_MAIL == 'swift') {
                Mail::send('user.emails.contact', $data, function ($message) use ($data) {
                    $message->to($data['to'])->subject($data['subject']);
                });
            } else {
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: ' . $request->input('name') . ' <' . $request->input('sender') . '>' . "\r\n";
                //mail($data['to'],$data['subject'], $message, $headers);
            }

            return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('success', 'Thank You , Your message has been sent !'));
        } else {
            return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('error', 'The following errors occurred'))
            ->withErrors($validator)->withInput();
        }
    }

    public function postProccess(Request $request, $formID)
    {
        //$row = $this->model->retrive($formID);
        $sql = \DB::table('tb_forms')->where('formID', $formID)->get();
        if (count($sql) <= 0) {
            return Redirect::back()->with('message', \SiteHelpers::alert('error', 'Form not Found !'));
        }

        $row           = $sql[0];
        $configuration = json_decode($row->configuration, true);

        $rules     = \FormHelpers::validateForm($configuration);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = \FormHelpers::validatePost($request, $configuration);
            if ('table' == $row->method) {
                \DB::table($row->tablename)->insert($data);
                if ('' != $row->redirect) {
                    echo '<script> window.location.href= "' . $row->redirect . '" </script>';
                } else {
                    return Redirect::back()->with('message', \SiteHelpers::alert('success', $row->success));
                }
            } else {
                $message = '';
                foreach ($configuration as $conf) {
                    $message .= '
						<b>' . $conf['label'] . '</b> : ' . $request->input($conf['field']) . ' <br />
					';
                }

                $data            = ['email' => $row->email, 'name' => $row->name];
                $data['message'] = $message;

                $message = view('user.emails.form', $data);

                if (defined('CNF_MAIL') && CNF_MAIL == 'swift') {
                    $data['message'] = $message;
                    \Mail::send('user.emails.form', $data, function ($message) use ($row) {
                        $message->to($row->email)->subject('Submitted Form :  ' . $row->name);
                    });
                } else {
                    $message = view('user.emails.form', $data);
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: ' . CNF_COMNAME . ' <' . CNF_EMAIL . '>' . "\r\n";
                    mail($row->email, 'Submitted Form :  ' . $row->name, $message, $headers);
                }
                /*
                                if ($row->sendcopy==1)
                                {
                                            $message = view('user.emails.form',$data);
                                            $headers  = 'MIME-Version: 1.0' . "\r\n";
                                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                            $headers .= 'From: '.CNF_COMNAME.' <'.CNF_EMAIL.'>' . "\r\n";
                                            mail($request->input($conf['eposta']), 'Submitted Form :  '. $row->name, $message, $headers);

                                }
                */

                if ('' != $row->redirect) {
                    echo '<script> window.location.href= "' . $row->redirect . '" </script>';
                } else {
                    return Redirect::back()->with('message', \SiteHelpers::alert('success', $row->success));
                }
            }
        } else {
            //Redirect::back();
            return Redirect::back()->with('message', \SiteHelpers::alert('error', 'The following errors occurred'))
            ->withErrors($validator)->withInput();
        }
    }

    public function isMobile()
    {
        $uaFull = strtolower($_SERVER['HTTP_USER_AGENT']);
        $uaStart = substr($uaFull, 0, 4);

        $uaPhone = [ // use `= array(` if PHP<5.4
            '(android|bb\d+|meego).+mobile',
            'avantgo',
            'bada\/',
            'blackberry',
            'blazer',
            'compal',
            'elaine',
            'fennec',
            'hiptop',
            'iemobile',
            'ip(hone|od)',
            'iris',
            'kindle',
            'lge ',
            'maemo',
            'midp',
            'mmp',
            'mobile.+firefox',
            'netfront',
            'opera m(ob|in)i',
            'palm( os)?',
            'phone',
            'p(ixi|re)\/',
            'plucker',
            'pocket',
            'psp',
            'series(4|6)0',
            'symbian',
            'treo',
            'up\.(browser|link)',
            'vodafone',
            'wap',
            'windows ce',
            'xda',
            'xiino'
        ]; // use `);` if PHP<5.4

        $uaMobile = [ // use `= array(` if PHP<5.4
            '1207', 
            '6310', 
            '6590', 
            '3gso', 
            '4thp', 
            '50[1-6]i', 
            '770s', 
            '802s', 
            'a wa', 
            'abac|ac(er|oo|s\-)', 
            'ai(ko|rn)', 
            'al(av|ca|co)', 
            'amoi', 
            'an(ex|ny|yw)', 
            'aptu', 
            'ar(ch|go)', 
            'as(te|us)', 
            'attw', 
            'au(di|\-m|r |s )', 
            'avan', 
            'be(ck|ll|nq)', 
            'bi(lb|rd)', 
            'bl(ac|az)', 
            'br(e|v)w', 
            'bumb', 
            'bw\-(n|u)', 
            'c55\/', 
            'capi', 
            'ccwa', 
            'cdm\-', 
            'cell', 
            'chtm', 
            'cldc', 
            'cmd\-', 
            'co(mp|nd)', 
            'craw', 
            'da(it|ll|ng)', 
            'dbte', 
            'dc\-s', 
            'devi', 
            'dica', 
            'dmob', 
            'do(c|p)o', 
            'ds(12|\-d)', 
            'el(49|ai)', 
            'em(l2|ul)', 
            'er(ic|k0)', 
            'esl8', 
            'ez([4-7]0|os|wa|ze)', 
            'fetc', 
            'fly(\-|_)', 
            'g1 u', 
            'g560', 
            'gene', 
            'gf\-5', 
            'g\-mo', 
            'go(\.w|od)', 
            'gr(ad|un)', 
            'haie', 
            'hcit', 
            'hd\-(m|p|t)', 
            'hei\-', 
            'hi(pt|ta)', 
            'hp( i|ip)', 
            'hs\-c', 
            'ht(c(\-| |_|a|g|p|s|t)|tp)', 
            'hu(aw|tc)', 
            'i\-(20|go|ma)', 
            'i230', 
            'iac( |\-|\/)', 
            'ibro', 
            'idea', 
            'ig01', 
            'ikom', 
            'im1k', 
            'inno', 
            'ipaq', 
            'iris', 
            'ja(t|v)a', 
            'jbro', 
            'jemu', 
            'jigs', 
            'kddi', 
            'keji', 
            'kgt( |\/)', 
            'klon', 
            'kpt ', 
            'kwc\-', 
            'kyo(c|k)', 
            'le(no|xi)', 
            'lg( g|\/(k|l|u)|50|54|\-[a-w])', 
            'libw', 
            'lynx', 
            'm1\-w', 
            'm3ga', 
            'm50\/', 
            'ma(te|ui|xo)', 
            'mc(01|21|ca)', 
            'm\-cr', 
            'me(rc|ri)', 
            'mi(o8|oa|ts)', 
            'mmef', 
            'mo(01|02|bi|de|do|t(\-| |o|v)|zz)', 
            'mt(50|p1|v )', 
            'mwbp', 
            'mywa', 
            'n10[0-2]', 
            'n20[2-3]', 
            'n30(0|2)', 
            'n50(0|2|5)', 
            'n7(0(0|1)|10)', 
            'ne((c|m)\-|on|tf|wf|wg|wt)', 
            'nok(6|i)', 
            'nzph', 
            'o2im', 
            'op(ti|wv)', 
            'oran', 
            'owg1', 
            'p800', 
            'pan(a|d|t)', 
            'pdxg', 
            'pg(13|\-([1-8]|c))', 
            'phil', 
            'pire', 
            'pl(ay|uc)', 
            'pn\-2', 
            'po(ck|rt|se)', 
            'prox', 
            'psio', 
            'pt\-g', 
            'qa\-a', 
            'qc(07|12|21|32|60|\-[2-7]|i\-)', 
            'qtek', 
            'r380', 
            'r600', 
            'raks', 
            'rim9', 
            'ro(ve|zo)', 
            's55\/', 
            'sa(ge|ma|mm|ms|ny|va)', 
            'sc(01|h\-|oo|p\-)', 
            'sdk\/', 
            'se(c(\-|0|1)|47|mc|nd|ri)', 
            'sgh\-', 
            'shar', 
            'sie(\-|m)', 
            'sk\-0', 
            'sl(45|id)', 
            'sm(al|ar|b3|it|t5)', 
            'so(ft|ny)', 
            'sp(01|h\-|v\-|v )', 
            'sy(01|mb)', 
            't2(18|50)', 
            't6(00|10|18)', 
            'ta(gt|lk)', 
            'tcl\-', 
            'tdg\-', 
            'tel(i|m)', 
            'tim\-', 
            't\-mo', 
            'to(pl|sh)', 
            'ts(70|m\-|m3|m5)', 
            'tx\-9', 
            'up(\.b|g1|si)', 
            'utst', 
            'v400', 
            'v750', 
            'veri', 
            'vi(rg|te)', 
            'vk(40|5[0-3]|\-v)', 
            'vm40', 
            'voda', 
            'vulc', 
            'vx(52|53|60|61|70|80|81|83|85|98)', 
            'w3c(\-| )', 
            'webc', 
            'whit', 
            'wi(g |nc|nw)', 
            'wmlb', 
            'wonu', 
            'x700', 
            'yas\-', 
            'your', 
            'zeto', 
            'zte\-'
        ]; // use `);` if PHP<5.4

        $isPhone = preg_match('/' . implode('|', $uaPhone) . '/i', $uaFull);
        $isMobile = preg_match('/' . implode('|', $uaMobile) . '/i', $uaStart);

        if($isPhone || $isMobile) {
            return true;
        } else {
            return false;
        }
    }
}
