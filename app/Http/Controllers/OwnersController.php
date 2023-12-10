<?php

namespace App\Http\Controllers;

use App\Models\Core\Banners;
use App\Models\Core\Pages;
use App\Models\Creditpackage;
use App\Models\Credittotals;
use App\Models\Credittransactions;
use App\Models\Mmb\Menu;
use App\Models\Owners;
use App\Models\Tourcategories;
use App\Models\Tourdates;
use App\Models\Tours;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Redirect;
use Validator;

class OwnersController extends Controller
{
    public $module          = 'owners';
    public static $per_page = '100';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        parent::__construct();
        $this->model = new Owners();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = [];

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'owners',
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_view']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'id');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
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
        $results = $this->model->getRows($params, session('uid'));

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('owners');

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
        $this->data['fields'] = \SiteHelpers::fieldLang($this->info['config']['grid']);
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);

        $this->data['insort']  = $sort;
        $this->data['inorder'] = $order;

        // Render into template
        return view('owners.index', $this->data);
    }

    public function getUpdate(Request $request, $id = null)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if ('' == $id) {
            if (0 == $this->access['is_add']) {
                return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        if ('' != $id) {
            if (0 == $this->access['is_edit']) {
                return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        $row = $this->model->find($id);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('tb_owners');
        }
        $this->data['fields'] = \SiteHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('owners.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_detail']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $this->data['row']      = $row;
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext'] = $this->model->prevNext($id);

            return view('owners.view', $this->data);
        } else {
            return Redirect::to('owners')->with('messagetext', 'Record Not Found !')->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_add']) {
            return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        foreach (\DB::select('SHOW COLUMNS FROM tb_owners ') as $column) {
            if ('id' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO tb_owners (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM tb_owners WHERE id IN (' . $toCopy . ')';
            \DB::select($sql);

            return Redirect::to('owners')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('owners')->with('messagetext', 'Please select row to copy')->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $domain = \DB::table('tb_owners')->where('domain', $request->get('domain'))->get();
        if (count($domain) > 0) {
            if ($request->get('domain') != $request->get('current_domain')) {
                return redirect('owners/update?return=')->with('msgstatus', 'error')->with('messagetext', 'You are have register the domain');
            }
        }
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            //$data = $this->validatePost( $request );

            $id = $request->input('id');
            if ('' == $id) {
                $id = null;
            }

            if (isset($id)) {
                $id = $this->model->insertRow($request->except('_token', 'submit', 'return', 'apply', 'current_domain', 'password'), $request->input('id'));
            } else {
                $id = $this->model->insertNewRow($request->except('_token', 'submit', 'return', 'apply', 'current_domain', 'password'), $request->input('id'));
                //dd($id);

                $owner                   = Owners::find($id);
                $owner->show_tour        = 'ON';
                $owner->multi_language   = '0';
                $owner->default_language = 'my';
                $owner->default_currency = '125';
                $owner->front            = 'true';
                $owner->save();

                $name               = explode('.', $owner->subdomain);
                $initialCredit      = 300;
                $initialCreditPrice = 999;

                //Create Default Category
                $dataUmrahCategoriesA                   = new Tourcategories();
                $dataUmrahCategoriesA->tourcategoryname = 'Pakej Ekonomi';
                $dataUmrahCategoriesA->status           = 1;
                $dataUmrahCategoriesA->owner_id         = $owner->id;
                $dataUmrahCategoriesA->save();
                $dataUmrahCategoriesB                   = new Tourcategories();
                $dataUmrahCategoriesB->tourcategoryname = 'Pakej Standard';
                $dataUmrahCategoriesB->status           = 1;
                $dataUmrahCategoriesB->owner_id         = $owner->id;
                $dataUmrahCategoriesB->save();
                $dataUmrahCategoriesC                   = new Tourcategories();
                $dataUmrahCategoriesC->tourcategoryname = 'Pakej Premium';
                $dataUmrahCategoriesC->status           = 1;
                $dataUmrahCategoriesC->owner_id         = $owner->id;
                $dataUmrahCategoriesC->save();

                //Create Default Tour
                $categories = Tourcategories::where('owner_id', $owner->id)->get();
                foreach ($categories as $category) {
                    $dataUmrahPackage                   = new Tours();
                    $dataUmrahPackage->tour_name        = $category->tourcategoryname;
                    $dataUmrahPackage->tourcategoriesID = $category->tourcategoriesID;
                    $dataUmrahPackage->tour_description = "<h4 class='color-blue-2'>PAKEJ UMRAH 12 HARI 10 MALAM</h4><p>4 malam di Madinah &amp; 6 malam di Makkah</p><h4 class='color-blue-2'>PAKEJ TERMASUK</h4><ul style='color:#111'><li>Tiket Penerbangan pergi/balik</li><li>Hidangan penuh (3 kali sehari)</li><li>Visa Umrah</li><li>Ziarah Madinah (Ziarah Dalam/Luar)</li><li>Ziarah Makkah (Hudaibiyah/Manasik Haji)</li><li>Ziarah Tambahan ke Taif, Medan Peperangan Badar &amp; Makan Malam di kebun kurma</li><li>30 kg elaun bagasi</li></ul><h4 class='color-blue-2'>PERCUMA</h4><ul style='color:#111'><li>1 unit Beg Kembara 24”</li><li>1 unit Beg Selimpang</li><li>Air Zam Zam 5 Liter</li><li>Buku Doa &amp; Umrah</li></ul><h4 class='color-blue-2'>PAKEJ TIDAK TERMASUK</h4><ul style='color:#111'><li>Khidmat troli di lapangan terbang</li><li>Lebihan timbangan bagasi</li><li>Rawatan hospital dan dialysis</li><li>Perbelanjaan peribadi</li><li>Cucian pakaian</li><li>Kad sim</li></ul>";
                    $dataUmrahPackage->total_days       = 12;
                    $dataUmrahPackage->total_nights     = 10;
                    $dataUmrahPackage->departs          = 3;
                    $dataUmrahPackage->featured         = 1;
                    $dataUmrahPackage->flight           = 'AK';
                    $dataUmrahPackage->baggage_limit    = 20;
                    $dataUmrahPackage->sector           = 'KUL-MED-JED-KUL';
                    $dataUmrahPackage->multicountry     = 0;
                    $dataUmrahPackage->countryID        = 184;
                    $dataUmrahPackage->status           = 1;
                    $dataUmrahPackage->payment_options  = '1,2,3';
                    $dataUmrahPackage->inclusions       = '17,18,19,20,21,22,23';
                    $dataUmrahPackage->owner_id         = $owner->id;
                    $dataUmrahPackage->save();
                }

                //Create Default Tours Date
                $tours = Tours::where('owner_id', $owner->id)->get();
                foreach ($tours as $tour) {
                    $date                   = new Tourdates();
                    $date->tourcategoriesID = $tour->tourcategoriesID;
                    $date->tourID           = $tour->tourID;
                    $date->tour_code        = str_replace(' ', '_', strtoupper($tour->tour_name)) . '_01';
                    $date->start            = date('Y-m-d', strtotime('+30 days'));
                    $date->end              = date('Y-m-d', strtotime('+42 days'));
                    $date->featured         = 1;
                    $date->total_capacity   = 40;
                    $date->cost_single      = 8000;
                    $date->cost_double      = 7000;
                    $date->cost_triple      = 6000;
                    $date->cost_quad        = 5000;
                    $date->cost_child       = 2000;
                    $date->cost_deposit     = 500;
                    $date->owner_id         = $owner->id;
                    $date->save();
                }

                //Create Default Credit
                /*
                $creditpackage = new Creditpackage;
                $creditpackage->package_name = $name[0].'300';
                $creditpackage->credit = $initialCredit;
                $creditpackage->amount = $initialCreditPrice;
                $creditpackage->currency = 125;
                $creditpackage->active = 1;
                $creditpackage->entry_by = 1;
                $creditpackage->owner_id = $owner->id;
                $creditpackage->save();
                */

                /*
                $creditpackage = new Creditpackage;
                $creditpackage->package_name = $name[0].'200';
                $creditpackage->credit = 200;
                $creditpackage->amount = 1000;
                $creditpackage->currency = 0;
                $creditpackage->active = 1;
                $creditpackage->entry_by = 1;
                $creditpackage->owner = $owner->id;
                $creditpackage = new Creditpackage;
                $creditpackage->package_name = $name[0].'500';
                $creditpackage->credit = 500;
                $creditpackage->amount = 2500;
                $creditpackage->currency = 0;
                $creditpackage->active = 1;
                $creditpackage->entry_by = 1;
                $creditpackage->owner = $owner->id;
                */

                //Create Credit Total Default
                $credittotal               = new Credittotals();
                $credittotal->total_credit = $initialCredit;
                $credittotal->entry_by     = 1;
                $credittotal->owner_id     = $owner->id;
                $credittotal->save();

                //Create Credit Transactions
                $credittransaction                     = new Credittransactions();
                $credittransaction->entry_by           = 1;
                $credittransaction->owner_id           = $owner->id;
                $credittransaction->agency             = 0;
                $credittransaction->transaction_id     = 'MAN-' . strtoupper($name[0]) . '-01';
                $credittransaction->amount_paid        = $initialCreditPrice;
                $credittransaction->credit_request     = $initialCredit;
                $credittransaction->status             = 'paid';
                $credittransaction->transaction_date   = date('Y-m-d');
                $credittransaction->payment_gateway_id = 0;
                $credittransaction->credit             = $initialCredit;
                $credittransaction->currency           = 125;
                $credittransaction->save();

                //Create Default Admin Users
                $code                  = rand(10000, 10000000);
                $userAdmin             = new User();
                $userAdmin->username   = strtolower($name[0]) . 'admin';
                $userAdmin->first_name = ucfirst(strtolower($name[0]));
                $userAdmin->last_name  = 'Admin';
                $userAdmin->email      = trim($request->input('email'));
                $userAdmin->activation = $code;
                $userAdmin->group_id   = 2;
                $userAdmin->password   = \Hash::make($request->input('password'));
                $userAdmin->active     = '1';
                $userAdmin->owner_id   = $owner->id;
                $userAdmin->save();

                //Create Default Headers
                $bannerCounter              = 0;
                $bannerIntro                = new Banners();
                $bannerIntro->title         = 'Assalamualaikum';
                $bannerIntro->position_name = 'home';
                $bannerIntro->content       = "<div class='container'><div class='row'><div class='col-md-12 col-xs-12'><div class='main-title'><h4 class='category color-white-light'>Ahlan wa Sahlan</h4><h1>UMRAH bersama <span class='color-red'>" . strtoupper($request->input('name')) . "</span></h1><h4 class='category color-black'>Kembara Ibadah di Bumi Ambiya'</h4><p class='color-white-light'>Rancanglah ibadah umrah anda dengan lebih sempurna.</p><a href='/package' class='c-button b-60 bg-red hv-red-o delay-2'><span> Lihat Semua Pakej </span></a> <a href='/hubungi-kami' class='c-button b-60 bg-white hv-white-o delay-2'><span> Hubungi Kami </span></a></div></div></div></div>";
                $bannerIntro->sort          = ++$bannerCounter;
                $bannerIntro->status        = 'enable';
                $bannerIntro->owner_id      = $owner->id;
                $bannerIntro->save();

                foreach ($categories as $category) {
                    $bannerStar = "<div class='rate'>";
                    for ($x = 0; $x < (2 + $bannerCounter); ++$x) {
                        $bannerStar = $bannerStar . "<span class='fa fa-star color-yellow'></span>";
                    }
                    $bannerStar = $bannerStar . '</div>';

                    $bannerCategory                = new Banners();
                    $bannerCategory->title         = $category->tourcategoryname;
                    $bannerCategory->position_name = 'home';
                    $bannerCategory->content       = "<div class='vertical-align'><div class='item-block style-4'><div class='vertical-align'><h3 class='hover-it color-blue-2'>" . $category->tourcategoryname . '</h3><h4>dari <b>RM ' . (4100 + ($bannerCounter * 500)) . "</b></h4><br><br><br><br>$bannerStar<div class='main-date'>Keselesaan dalam Kesempurnaan Beribadah</div><p>Hotel " . (2 + $bannerCounter) . ' bintang berdekatan dengan Masjidil Haram dan Masjid Nabawi</p></div></div></div>';
                    $bannerCategory->link          = '/package?cat=' . $category->tourcategoriesID;
                    $bannerCategory->link_button   = 'Lihat Senarai Penuh ' . $category->tourcategoryname;
                    $bannerCategory->sort          = ++$bannerCounter;
                    $bannerCategory->status        = 'enable';
                    $bannerCategory->owner_id      = $owner->id;
                    $bannerCategory->save();
                }

                $bannerContact                = new Banners();
                $bannerContact->title         = 'Hubungi Kami';
                $bannerContact->position_name = 'home';
                $bannerContact->content       = "<h2><span class='color-red'>Jika ada sebarang pertanyaan</span><br>kami sedia membantu</h2><h4><span style='color:#111 !important'> </span> </h4>";
                $bannerContact->link          = '/contact';
                $bannerContact->link_button   = 'Hubungi Kami Di Sini';
                $bannerContact->sort          = ++$bannerCounter;
                $bannerContact->status        = 'enable';
                $bannerContact->owner_id      = $owner->id;
                $bannerContact->save();

                //Create Default Pages
                $homePage              = new Pages();
                $homePage->title       = 'home';
                $homePage->alias       = 'home';
                $homePage->note        = "<h4 style='text-align: center;'>Mari bersama-sama " . $request->input('name') . " mengerjakan ibadat umrah. </h4><h5 style='text-align: center;'>Kami menawarkan pelbagai pakej umrah yang sesuai dengan pilihan anda. </h5><p style='text-align: center;'><span class='c-button b-60 bg-red hv-red-o delay-2'>Tempah pakej umrah anda bersama kami sekarang</span></p>";
                $homePage->filename    = 'page';
                $homePage->status      = 'enable';
                $homePage->access      = '{"1":"1","2":"1","3":"0","4":"1","5":"0","6":"0","7":"0"}';
                $homePage->allow_guest = '1';
                $homePage->template    = 'frontend';
                $homePage->default     = '1';
                $homePage->header      = 'home';
                $homePage->owner_id    = $owner->id;
                $homePage->save();

                //Create Default Menu
                $packageMenu              = new Menu();
                $packageMenu->parent_id   = '0';
                $packageMenu->url         = '/package';
                $packageMenu->menu_name   = 'Pakej';
                $packageMenu->menu_type   = 'external';
                $packageMenu->ordering    = '0';
                $packageMenu->position    = 'top';
                $packageMenu->active      = '1';
                $packageMenu->access_data = '{"1":"1","2":"1","3":"0","4":"1","5":"0","6":"0","7":"0"}';
                $packageMenu->allow_guest = '1';
                $packageMenu->menu_lang   = '{"title":{"en":"Package","id":"Paket","my":"Pakej"}}';
                $packageMenu->owner_id    = $owner->id;
                $packageMenu->save();

                $contactMenu              = new Menu();
                $contactMenu->parent_id   = '0';
                $contactMenu->url         = '/contact';
                $contactMenu->menu_name   = 'Hubungi Kami';
                $contactMenu->menu_type   = 'external';
                $contactMenu->ordering    = '1';
                $contactMenu->position    = 'top';
                $contactMenu->active      = '1';
                $contactMenu->access_data = '{"1":"1","2":"1","3":"0","4":"1","5":"0","6":"0","7":"0"}';
                $contactMenu->allow_guest = '1';
                $contactMenu->menu_lang   = '{"title":{"en":"Contact Us","id":"Hubungi Kami","my":"Hubungi Kami"}}';
                $contactMenu->owner_id    = $owner->id;
                $contactMenu->save();
            }

            if (! is_null($request->input('apply'))) {
                $return = 'owners/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'owners?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('id')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('owners/update/' . $request->input('id'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postDelete(Request $request)
    {
        // Make Sure users Logged
        if (! \Auth::check()) {
            return redirect('user/login')->with('msgstatus', 'error')->with('messagetext', 'You are not login');
        }

        $this->access = $this->model->validAccess($this->info['id'], session('gid'));
        if (0 == $this->access['is_remove']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfull');
            // redirect
            return Redirect::to('owners')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('owners')
                ->with('messagetext', 'No Item Deleted')->with('msgstatus', 'error');
        }
    }

    public static function display()
    {
        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Owners();
        $info  = $model::makeInfo('owners');

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

                return view('owners.public.view', $data);
            }
        } else {
            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['rows']) ? filter_var($_GET['rows'], FILTER_VALIDATE_INT) : 10),
                'sort'   => 'id',
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

            return view('owners.public.index', $data);
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost($request);
            $this->model->insertRow($data, $request->input('id'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }
}
