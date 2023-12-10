<?php

namespace App\Http\Controllers\mmb;

use App\Http\Controllers\controller;
use App\Models\Mmb\Menu;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class MenuController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model  = new Menu();
        $this->info   = $this->model->makeInfo('menu');
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle' => 'Navigation',
            'pageNote'  => 'Manage All Side,Top Menu and Setting Bar',
            'active'    => 'menu',
        ];
    }

    public function getIndex(Request $request, $id = null)
    {
        if (1 == \Session::get('gid')) {
            $pos = (! is_null($request->input('pos')) ? $request->input('pos') : 'top');

            if ('top' == $pos) {
                $row = \DB::table('tb_menu')->where('owner_id', '=', CNF_OWNER)->where('menu_id', $id)->get();
            } else {
                $row = \DB::table('tb_menu')->where('menu_id', $id)->get();
            }
        } else {
            $pos = 'top';
            $row = \DB::table('tb_menu')->where('owner_id', '=', CNF_OWNER)->where('menu_id', $id)->get();
        }

        if (count($row) >= 1) {
            $rows              = $row[0];
            $this->data['row'] = (array) $rows;

            $this->data['menu_lang'] = json_decode($rows->menu_lang, true);
        } else {
            if ($id) {
                return Redirect::to('core/menu')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            } else {
                $this->data['row'] = [
                        'menu_id'     => '',
                        'parent_id'   => '',
                        'menu_name'   => '',
                        'menu_type'   => '',
                        'url'         => '',
                        'module'      => '',
                        'position'    => '',
                        'menu_icons'  => '',
                        'active'      => '',
                        'allow_guest' => '',
                        'access_data' => '',
                    ];
                $this->data['menu_lang'] = [];
            }
        }
        //echo '<pre>';print_r($this->data);echo '</pre>';  exit;
        $this->data['menus'] = \SiteHelpers::menus($pos, 'all');

        if (1 == \Session::get('gid')) {
            $this->data['modules'] = \DB::table('tb_module')->where('module_type', '!=', 'core')->orderBy('module_title', 'ASC')->get();
        }
        if (1 == \Session::get('gid')) {
            $this->data['groups'] = \DB::select(' SELECT * FROM tb_groups ');
        } else {
            $this->data['groups'] = \DB::select(' SELECT * FROM tb_groups WHERE group_id != 1');
        }

        $this->data['pages']  = \DB::select(" SELECT * FROM tb_pages WHERE `owner_id` = '" . CNF_OWNER . "' AND (pagetype != 'post' OR pagetype IS NULL) ");
        $this->data['active'] = $pos;

        return view('mmb.menu.index', $this->data);
    }

    public function postSaveorder(Request $request, $id = 0)
    {
        $rules = [
            'reorder' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $menus = json_decode($request->input('reorder'), true);
            $child = [];
            $a     = 0;
            foreach ($menus as $m) {
                if (isset($m['children'])) {
                    $b = 0;
                    foreach ($m['children'] as $l) {
                        if (isset($l['children'])) {
                            $c = 0;
                            foreach ($l['children'] as $l2) {
                                $level3[] = $l2['id'];
                                \DB::table('tb_menu')->where('menu_id', '=', $l2['id'])
                                    ->update(['parent_id' => $l['id'], 'ordering' => $c]);
                                ++$c;
                            }
                        }
                        \DB::table('tb_menu')->where('menu_id', '=', $l['id'])
                            ->update(['parent_id' => $m['id'], 'ordering' => $b]);
                        ++$b;
                    }
                }
                \DB::table('tb_menu')->where('menu_id', '=', $m['id'])
                    ->update(['parent_id' => '0', 'ordering' => $a]);
                ++$a;
            }

            return Redirect::to('core/menu')
                ->with('messagetext', 'Data Has Been Saved Successfully')->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/menu')
                ->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request, $id = 0)
    {
        $rules = [
            'menu_name' => 'required',
            'active'    => 'required',
            'menu_type' => 'required',
            'position'  => 'required',
        ];

        $pos = $request->input('position');

        if (! isset($pos)) {
            $pos = 'top';
        }

        //limit menu to 5 menu only
        if ($pos = 'top') {
            $check = \DB::table('tb_menu')->where('parent_id', '0')->where('position', 'top')->where('owner_id', CNF_OWNER)->count('menu_id');

            if ($check > 5) {
                return Redirect::to('core/menu')
                    ->with('messagetext', 'Top menu cannot more than 5 parent item')->with('msgstatus', 'error');
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_menu');

            //echo '<pre>'; print_r($data); echo '</pre>'; exit;
            if (CNF_MULTILANG == 1) {
                $lang     = \SiteHelpers::langOption();
                $language = [];
                foreach ($lang as $l) {
                    if ('en' != $l['folder']) {
                        $menu_lang                       = (isset($_POST['language_title'][$l['folder']]) ? $_POST['language_title'][$l['folder']] : '');
                        $language['title'][$l['folder']] = $menu_lang;
                    }
                }

                $data['menu_lang'] = json_encode($language);
            }

            $arr    = [];
            $groups = \DB::table('tb_groups')->get();
            foreach ($groups as $g) {
                $arr[$g->group_id] = (isset($_POST['groups'][$g->group_id]) ? '1' : '0');
            }
            $data['access_data'] = json_encode($arr);
            $data['allow_guest'] = $request->input('allow_guest');
            $this->model->insertRow($data, $request->input('menu_id'));

            return Redirect::to('core/menu?pos=' . $pos)
                ->with('messagetext', 'Data Has Been Saved Successfully')->with('msgstatus', 'success');
        } else {
            return Redirect::to('core/menu?pos=' . $pos)
                ->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')->withErrors($validator)->withInput();
        }
    }

    public function getDestroy(Request $request, $id)
    {
        // delete multipe rows

        $menus = \DB::table('tb_menu')->where('parent_id', '=', $id)->get();
        foreach ($menus as $row) {
            $this->model->destroy($row->menu_id);
        }

        $this->model->destroy($id);

        return Redirect::to('core/menu?pos=' . $request->input('pos'))
                ->with('messagetext', 'Successfully deleted row!')->with('msgstatus', 'success');
    }
}
