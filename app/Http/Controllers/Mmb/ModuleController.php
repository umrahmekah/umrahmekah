<?php

namespace App\Http\Controllers\Mmb;

use App\Http\Controllers\controller;
use App\Models\Mmb\Module;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Validator;

class ModuleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $driver   = config('database.default');
        $database = config('database.connections');

        $this->db     = $database[$driver]['database'];
        $this->dbuser = $database[$driver]['username'];
        $this->dbpass = $database[$driver]['password'];
        $this->dbhost = $database[$driver]['host'];
        $this->model  = new Module();

        $this->data = [
            'pageTitle' => 'Module',
            'pageNote'  => 'Manage All Module',
        ];
    }

    public function getIndex(Request $request)
    {
        if (! is_null($request->input('t'))) {
            $rowData = \DB::table('tb_module')->where('module_type', '=', 'core')
                    ->orderby('module_title', 'asc')->get();
            $type = 'core';
        } else {
            $rowData = \DB::table('tb_module')->where('module_type', '!=', 'core')
                        ->orderby('module_title', 'asc')->get();
            $type = 'addon';
        }

        $this->data['type']    = $type;
        $this->data['rowData'] = $rowData;

        return view('mmb.module.index', $this->data);
    }

    public function getCreate()
    {
        $this->data = [
            'pageTitle' => 'Create New Module',
            'pageNote'  => 'Create Quick Module ',
        ];
        $this->data['tables'] = Module::getTableList($this->db);
        $this->data['cruds']  = \SiteHelpers::crudOption();

        return view('mmb.module.create', $this->data);
    }

    public function postCreate(Request $request)
    {
        $rules = [
            'module_name'  => 'required|alpha|min:2|unique:tb_module',
            'module_title' => 'required',
            'module_note'  => 'required',
            'module_db'    => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $table   = $request->input('module_db');
            $primary = self::findPrimarykey($request->input('module_db'));

            $select = $request->input('sql_select');
            $where  = $request->input('sql_where');
            $group  = $request->input('sql_group');

            if ('manual' == $request->input('creation')) {
                if ('' == $where) {
                    return Redirect::to('mmb/module/create')
                    ->withErrors($validator)->withInput()->with('messagetext', 'SQL WHERE REQUIRED')->with('msgstatus', 'error');
                }

                try {
                    \DB::select($select . ' ' . $where . ' ' . $group);
                } catch (Exception $e) {
                    // Do something when query fails.
                    $error = 'Error : ' . $select . ' ' . $where . ' ' . $group;

                    return Redirect::to('mmb/module/create')
                    ->withErrors($validator)->withInput()->with('messagetext', SiteHelpers::alert('error', $error))->with('msgstatus', 'error');
                }
                $columns       = [];
                $results       = $this->model->getColoumnInfo($select . ' ' . $where . ' ' . $group);
                $primary_exits = '';
                foreach ($results as $r) {
                    $Key = (isset($r['flags'][1]) && 'primary_key' == $r['flags'][1] ? 'PRI' : '');
                    if ('' != $Key) {
                        $primary_exits = $r['name'];
                    }
                    $columns[] = (object) ['Field' => $r['name'], 'Table' => $r['table'], 'Type' => $r['native_type'], 'Key' => $Key];
                }
                $primary = ('' != $primary_exits ? $primary_exits : '');
            } else {
                $columns = \DB::select('SHOW COLUMNS FROM ' . $request->input('module_db'));
                $select  = " SELECT {$table}.* FROM {$table} ";
                $where   = ' WHERE ' . $table . '.' . $primary . ' IS NOT NULL';
                if ('' != $primary) {
                    $where = ' WHERE ' . $table . '.' . $primary . ' IS NOT NULL';
                } else {
                    $where = '';
                }
            }

            $i       = 0;
            $rowGrid = [];
            $rowForm = [];
            foreach ($columns as $column) {
                if (! isset($column->Table)) {
                    $column->Table = $table;
                }
                if ('PRI' == $column->Key) {
                    $column->Type = 'hidden';
                }
                if ($column->Table == $table) {
                    $form_creator = self::configForm($column->Field, $column->Table, $column->Type, $i);
                    $relation     = self::buildRelation($table, $column->Field);
                    foreach ($relation as $row) {
                        $array        = ['external', $row['table'], $row['column']];
                        $form_creator = self::configForm($column->Field, $table, 'select', $i, $array);
                    }
                    $rowForm[] = $form_creator;
                }

                $rowGrid[] = self::configGrid($column->Field, $column->Table, $column->Type, $i);
                ++$i;
            }

            $json_data['sql_select']  = $select;
            $json_data['sql_where']   = $where;
            $json_data['sql_group']   = $group;
            $json_data['table_db']    = $table;
            $json_data['primary_key'] = $primary;
            $json_data['grid']        = $rowGrid;
            $json_data['forms']       = $rowForm;

            $module_type = '' == $primary ? 'report' : $request->input('module_template');

            $data = [
                'module_name'    => strtolower(trim($request->input('module_name'))),
                'module_title'   => $request->input('module_title'),
                'module_note'    => $request->input('module_note'),
                'module_db'      => $request->input('module_db'),
                'module_db_key'  => $primary,
                'module_type'    => $module_type,
                'module_created' => date('Y-m-d H:i:s'),
                'module_config'  => \SiteHelpers::CF_encode_json($json_data),
            ];

            \DB::table('tb_module')->insert($data);

            // Add Default permission
            $tasks = [
                'is_global' => 'Global',
                'is_view'   => 'View ',
                'is_detail' => 'Detail',
                'is_add'    => 'Add ',
                'is_clone'  => 'Clone ',
                'is_edit'   => 'Edit ',
                'is_remove' => 'Remove ',
                'is_excel'  => 'Export ',
            ];
            $groups = \DB::table('tb_groups')->get();
            $row    = \DB::table('tb_module')->where('module_name', $request->input('module_name'))->get();
            if (count($row) >= 1) {
                $id = $row[0];

                foreach ($groups as $g) {
                    $arr = [];
                    foreach ($tasks as $t => $v) {
                        if ('1' == $g->group_id) {
                            $arr[$t] = '1';
                        } else {
                            $arr[$t] = '0';
                        }
                    }
                    $data = [
                        'access_data' => json_encode($arr),
                        'module_id'   => $id->module_id,
                        'group_id'    => $g->group_id,
                    ];
                    \DB::table('tb_groups_access')->insert($data);
                }

                return Redirect::to('mmb/module/rebuild/' . $id->module_id);
            } else {
                return Redirect::to('mmb/module');
            }
        } else {
            return Redirect::to('mmb/module/create')
            ->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getDestroy($id = null)
    {
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row   = $row[0];
        $path  = $row->module_name;
        $class = ucwords($row->module_name);

        if ('core' != $row->module_type) {
            if ('' != $class) {
                \DB::table('tb_module')->where('module_id', '=', $row->module_id)->delete();
                \DB::table('tb_groups_access')->where('module_id', '=', $row->module_id)->delete();
                self::createRouters();

                if (file_exists(app_path() . "/Http/Controllers/{$class}Controller.php")) {
                    unlink(app_path() . "/Http/Controllers/{$class}Controller.php");
                }

                if (file_exists(app_path() . "/Models/{$class}.php")) {
                    unlink(app_path() . "/Models/{$class}.php");
                }

                self::removeDir(base_path() . "/resources/views/{$path}");

                return Redirect::to('mmb/module')
                ->with('messagetext', 'Module has been removed successfull')->with('msgstatus', 'success');
            }
        }

        return Redirect::to($this->module)
        ->with('messagetext', 'No Module removed !')->with('msgstatus', 'success');
    }

    public function removeDir($dir)
    {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                self::removeDir($file);
            } else {
                unlink($file);
            }
        }
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    public function getConfig($id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row = $row[0];

        $this->data['row'] = $row;

        $fp                   = base_path() . '/resources/views/mmb/module/template/' . $this->getTemplateName($row->module_type) . '/config/info.json';
        $fp                   = json_decode(file_get_contents($fp));
        $this->data['config'] = $fp;
        $this->data['cruds']  = \SiteHelpers::crudOption();

        $this->data['module']      = 'module';
        $this->data['module_lang'] = json_decode($row->module_lang, true);
        $this->data['module_name'] = $row->module_name;
        $config                    = \SiteHelpers::CF_decode_json($row->module_config, true);
        //var_dump($config);die();
        $this->data['tables']  = $config['grid'];
        $this->data['type']    = ('ajax' == $row->module_type ? 'addon' : $row->module_type);
        $this->data['setting'] = [
            'gridtype'    => (isset($config['setting']) ? $config['setting']['gridtype'] : 'native'),
            'orderby'     => (isset($config['setting']) ? $config['setting']['orderby'] : $row->module_db_key),
            'ordertype'   => (isset($config['setting']) ? $config['setting']['ordertype'] : 'asc'),
            'perpage'     => (isset($config['setting']) ? $config['setting']['perpage'] : '10'),
            'frozen'      => (isset($config['setting']['frozen']) ? $config['setting']['frozen'] : 'false'),
            'form-method' => (isset($config['setting']['form-method']) ? $config['setting']['form-method'] : 'native'),
            'view-method' => (isset($config['setting']['view-method']) ? $config['setting']['view-method'] : 'native'),
            'inline'      => (isset($config['setting']['inline']) ? $config['setting']['inline'] : 'false'),
        ];

        return view('mmb.module.config', $this->data);
    }

    public function postSaveconfig(Request $request)
    {
        $rules = [
            'module_title' => 'required',
            'module_id'    => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = [
                'module_title' => $request->input('module_title'),
                'module_note'  => $request->input('module_note'),
            ];
            $lang     = \SiteHelpers::langOption();
            $language = [];
            foreach ($lang as $l) {
                if ('en' != $l['folder']) {
                    $label_lang = (isset($_POST['language_title'][$l['folder']]) ? $_POST['language_title'][$l['folder']] : '');
                    $note_lang  = (isset($_POST['language_note'][$l['folder']]) ? $_POST['language_note'][$l['folder']] : '');

                    $language['title'][$l['folder']] = $label_lang;
                    $language['note'][$l['folder']]  = $note_lang;
                }
            }

            $data['module_lang'] = json_encode($language);
            $id                  = $request->input('module_id');
            \DB::table('tb_module')->where('module_id', '=', $id)->update($data);

            if (true == $request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Module Info Has Been updated  Successfull']);
            } else {
                return redirect('mmb/module/config/' . $request->input('module_name'))
                    ->with('messagetext', 'Module Info Has Been updated  Successfull')->with('msgstatus', 'success');
            }
        } else {
            if (true == $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'The following errors occurred']);
            } else {
                return Redirect::to('mmb/module/config/' . $request->input('module_name'))
                    ->with('messagetext', 'The following errors occurred')->with('msgstatus', 'error')
                    ->withErrors($validator)->withInput();
            }
        }
    }

    public function postSavesetting(Request $request)
    {
        $this->beforeFilter('csrf', ['on' => 'post']);

        $id  = $request->input('module_id');
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row     = $row[0];
        $config  = \SiteHelpers::CF_decode_json($row->module_config);
        $setting = [
            'gridtype'    => '',
            'orderby'     => $request->input('orderby'),
            'ordertype'   => $request->input('ordertype'),
            'perpage'     => $request->input('perpage'),
            'frozen'      => (! is_null($request->input('frozen')) ? 'true' : 'false'),
            'form-method' => (! is_null($request->input('form-method')) ? $request->input('form-method') : 'native'),
            'view-method' => (! is_null($request->input('view-method')) ? $request->input('view-method') : 'native'),
            'inline'      => (! is_null($request->input('inline')) ? 'true' : 'false'),
        ];
        if (isset($config['setting'])) {
            unset($config['setting']);
        }

        $new_config            = array_merge($config, ['setting' => $setting]);
        $data['module_config'] = \SiteHelpers::CF_encode_json($new_config);
        $data['module_type']   = $request->input('module_type');

        \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config), 'module_type' => $request->input('module_type')]);

        return Redirect::to('mmb/module/config/' . $row->module_name)
        ->with('messagetext', 'Module Setting Has Been Saved Successfully')->with('msgstatus', 'success');
    }

    public function getJson(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                        = $row[0];
        $this->data['row']          = $row;
        $config                     = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['json_encoded'] = json_encode($config);
        $this->data['json_decoded'] = $row->module_config;
        $this->data['sql_select']   = $config['sql_where'];
        $this->data['module_name']  = $row->module_name;
        $this->data['module']       = 'module';
        $this->data['type']         = ('ajax' == $row->module_type ? 'addon' : $row->module_type);

        return view('mmb.module.json', $this->data);
    }

    public function postSavejson(Request $request, $id)
    {
        $select = $request->input('sql_select');
        $where  = $request->input('sql_where');
        $group  = $request->input('sql_group');

        try {
            \DB::select($select . ' ' . $where . ' ' . $group);
        } catch (Exception $e) {
            // Do something when query fails.
            $error = 'Error : ' . $select . ' ' . $where . ' ' . $group;

            return Redirect::to('mmb/module/sql/' . $request->input('module_name'))
            ->with('messagetext', $error)->with('msgstatus', 'error');
        }

        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to($this->module)
                ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }

        $row    = $row[0];
        $config = \SiteHelpers::CF_decode_json($row->module_config);

        $this->data['row'] = $row;

        $pdo     = \DB::getPdo();
        $columns = Module::getColoumnInfo($select . ' ' . $where . ' ' . $group);
        $i       = 0;
        $form    = [];
        $grid    = [];
        foreach ($columns as $field) {
            $name  = $field['name'];
            $alias = $field['table'];
            $grids = self::configGrid($name, $alias, '', $i);

            foreach ($config['grid'] as $g) {
                if (! isset($g['type'])) {
                    $g['type'] = 'text';
                }
                if ($g['field'] == $name && $g['alias'] == $alias) {
                    $grids = $g;
                }
            }
            $grid[] = $grids;

            if ($row->module_db == $alias) {
                $forms = self::configForm($name, $alias, 'text', $i);
                foreach ($config['forms'] as $f) {
                    if ($f['field'] == $name && $f['alias'] == $alias) {
                        $forms = $f;
                    }
                }
                $form[] = $forms;
            }

            ++$i;
        }

        // Remove Old Grid
        unset($config['forms']);
        // Remove Old Form
        unset($config['grid']);
        // Remove Old Query
        unset($config['sql_group']);
        unset($config['sql_select']);
        unset($config['sql_where']);

        // Inject New Grid
        $new_config = [
                'sql_select' => $select,
                'sql_where'  => $where,
                'sql_group'  => $group,
                'grid'       => $grid,
                'forms'      => $form,
            ];

        $config = array_merge($config, $new_config);

        \DB::table('tb_module')
           ->where('module_id', '=', $row->module_id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($config)]);

        return Redirect::to('mmb/module/sql/' . $row->module_name)
            ->with('messagetext', 'SQL Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function getSql(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                       = $row[0];
        $this->data['row']         = $row;
        $config                    = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['sql_select']  = $config['sql_select'];
        $this->data['sql_where']   = $config['sql_where'];
        $this->data['sql_group']   = $config['sql_group'];
        $this->data['module_name'] = $row->module_name;
        $this->data['module']      = 'module';
        $this->data['type']        = ('ajax' == $row->module_type ? 'addon' : $row->module_type);

        return view('mmb.module.sql', $this->data);
    }

    public function postSavesql(Request $request, $id)
    {
        $select = $request->input('sql_select');
        $where  = $request->input('sql_where');
        $group  = $request->input('sql_group');

        try {
            \DB::select($select . ' ' . $where . ' ' . $group);
        } catch (Exception $e) {
            // Do something when query fails.
            $error = 'Error : ' . $select . ' ' . $where . ' ' . $group;

            return Redirect::to('mmb/module/sql/' . $request->input('module_name'))
            ->with('messagetext', $error)->with('msgstatus', 'error');
        }

        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to($this->module)
                ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }

        $row    = $row[0];
        $config = \SiteHelpers::CF_decode_json($row->module_config);

        $this->data['row'] = $row;

        $pdo     = \DB::getPdo();
        $columns = Module::getColoumnInfo($select . ' ' . $where . ' ' . $group);
        $i       = 0;
        $form    = [];
        $grid    = [];
        foreach ($columns as $field) {
            $name  = $field['name'];
            $alias = $field['table'];
            $grids = self::configGrid($name, $alias, '', $i);

            foreach ($config['grid'] as $g) {
                if (! isset($g['type'])) {
                    $g['type'] = 'text';
                }
                if ($g['field'] == $name && $g['alias'] == $alias) {
                    $grids = $g;
                }
            }
            $grid[] = $grids;

            if ($row->module_db == $alias) {
                $forms = self::configForm($name, $alias, 'text', $i);
                foreach ($config['forms'] as $f) {
                    if ($f['field'] == $name && $f['alias'] == $alias) {
                        $forms = $f;
                    }
                }
                $form[] = $forms;
            }

            ++$i;
        }

        // Remove Old Grid
        unset($config['forms']);
        // Remove Old Form
        unset($config['grid']);
        // Remove Old Query
        unset($config['sql_group']);
        unset($config['sql_select']);
        unset($config['sql_where']);

        // Inject New Grid
        $new_config = [
                'sql_select' => $select,
                'sql_where'  => $where,
                'sql_group'  => $group,
                'grid'       => $grid,
                'forms'      => $form,
            ];

        $config = array_merge($config, $new_config);

        \DB::table('tb_module')
           ->where('module_id', '=', $row->module_id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($config)]);

        return Redirect::to('mmb/module/sql/' . $row->module_name)
            ->with('messagetext', 'SQL Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function getTable($id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                  = $row[0];
        $this->data['row']    = $row;
        $fp                   = base_path() . '/resources/views/mmb/module/template/' . $this->getTemplateName($row->module_type) . '/config/info.json';
        $fp                   = json_decode(file_get_contents($fp));
        $this->data['config'] = $fp;
        $config               = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['tables'] = $config['grid'];

        $this->data['module']      = 'module';
        $this->data['module_name'] = $row->module_name;
        $this->data['type']        = ('ajax' == $row->module_type ? 'addon' : $row->module_type);

        return view('mmb.module.table', $this->data);
    }

    public function getForm($id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);
        $column_table      = \DB::getSchemaBuilder()->getColumnListing($row->module_db);
        $column_config     = [];
        $column_item;
        foreach ($config['forms'] as $forms_col) {
            $column_config[] = $forms_col['field'];
            $column_item     = $forms_col;
        }
        $column_diff = array_diff($column_table, $column_config);
        foreach ($column_diff as $cf) {
            $column_item['field'] = $cf;
            $column_item['label'] = $cf;
            $config['forms'][]    = $column_item;
            /*$config['forms'][] = array(
                "field"         => $cf,
                "alias"         => $column_item['alias'],
                "language"      => (isset($column_item['language'])?$column_item['language']:'en'),
                "label"         => $cf,
                'form_group'    => $column_item['form_group'],
                'required'      => $column_item['required'],
                'view'          => $column_item['view'],
                'type'          => $column_item['type'],
                'add'           => $column_item['add'],
                'size'          => $column_item['size'],
                'edit'          => $column_item['edit'],
                'search'        => $column_item['search'],
                "sortlist"      => $column_item['sortlist'],
                'limited'       => $column_item['limited'],
                'option'        => $column_item['option'],
                );*/
        }

        $fp                   = base_path() . '/resources/views/mmb/module/template/' . $this->getTemplateName($row->module_type) . '/config/info.json';
        $fp                   = json_decode(file_get_contents($fp));
        $this->data['config'] = $fp;
//        $this->data['cruds'] = \SiteHelpers::crudOption();

        $this->data['forms']       = $config['forms'];
        $this->data['form_column'] = (isset($config['form_column']) ? $config['form_column'] : 1);
        $this->data['module']      = 'module';
        $this->data['module_name'] = $row->module_name;
        $this->data['type']        = ('ajax' == $row->module_type ? 'addon' : $row->module_type);

        //dd($config['forms']);

        return view('mmb.module.form', $this->data);
    }

    public function postSaveform(Request $request)
    {
        $id  = $request->input('module_id');
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row = $row[0];

        $this->data['row']    = $row;
        $config               = \SiteHelpers::CF_decode_json($row->module_config);
        $lang                 = \SiteHelpers::langOption();
        $this->data['tables'] = $config['grid'];
        $total                = count($_POST['field']);
        extract($_POST);
        $f = [];
        for ($i = 1; $i <= $total; ++$i) {
            $language = [];
            foreach ($lang as $l) {
                if ('en' != $l['folder']) {
                    $label_lang             = (isset($_POST['language'][$i][$l['folder']]) ? $_POST['language'][$i][$l['folder']] : '');
                    $language[$l['folder']] = $label_lang;
                }
            }
            $f[] = [
                'field'      => $field[$i],
                'alias'      => $alias[$i],
                'language'   => $language,
                'label'      => $label[$i],
                'form_group' => $form_group[$i],
                'required'   => (isset($required[$i]) ? $required[$i] : 0),
                'view'       => (isset($view[$i]) ? 1 : 0),
                'type'       => $type[$i],
                'add'        => 1,
                'size'       => '0',
                'edit'       => 1,
                'search'     => (isset($search[$i]) ? $search[$i] : 0),
                'sortlist'   => $sortlist[$i],
                'limited'    => (isset($limited[$i]) ? $limited[$i] : ''),
                'option'     => [
                    'opt_type'              => $opt_type[$i],
                    'lookup_query'          => $lookup_query[$i],
                    'lookup_table'          => $lookup_table[$i],
                    'lookup_key'            => $lookup_key[$i],
                    'lookup_value'          => $lookup_value[$i],
                    'is_dependency'         => $is_dependency[$i],
                    'select_multiple'       => (isset($select_multiple[$i]) ? $select_multiple[$i] : 0),
                    'image_multiple'        => (isset($image_multiple[$i]) ? $image_multiple[$i] : 0),
                    'lookup_dependency_key' => $lookup_dependency_key[$i],
                    'path_to_upload'        => $path_to_upload[$i],
                    'resize_width'          => $resize_width[$i],
                    'resize_height'         => $resize_height[$i],
                    'upload_type'           => $upload_type[$i],
                    'tooltip'               => $tooltip[$i],
                    'attribute'             => $attribute[$i],
                    'extend_class'          => $extend_class[$i],
                    ],
                ];
        }

        unset($config['forms']);
        $new_config            = array_merge($config, ['forms' => $f]);
        $data['module_config'] = \SiteHelpers::CF_encode_json($new_config);

        \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

        return Redirect::to('mmb/module/form/' . $row->module_name)
        ->with('messagetext', 'Module Forms Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function getEditform(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);

        $module_id = $id;
        $field_id  = $request->input('field');
        $alias     = $request->input('alias');

        $f = [];
        foreach ($config['forms'] as $form) {
            $tooltip   = '';
            $attribute = '';
            if (isset($form['option']['tooltip'])) {
                $tooltip = $form['option']['tooltip'];
            }
            if (isset($form['option']['attribute'])) {
                $attribute = $form['option']['attribute'];
            }
            $size = isset($form['size']) ? $form['size'] : 'span12';
            if ($form['field'] == $field_id && $form['alias'] == $alias) {
                //$multiVal = explode(":",$form['option']['lookup_value']);
                $f = [
                    'field'      => $form['field'],
                    'alias'      => $form['alias'],
                    'label'      => $form['label'],
                    'form_group' => $form['form_group'],
                    'required'   => $form['required'],
                    'view'       => $form['view'],
                    'type'       => $form['type'],
                    'add'        => $form['add'],
                    'size'       => $size,
                    'edit'       => $form['edit'],
                    'search'     => $form['search'],
                    'sortlist'   => $form['sortlist'],
                    'limited'    => isset($form['limited']) ? $form['limited'] : '',
                    'option'     => [
                        'opt_type'              => $form['option']['opt_type'],
                        'lookup_query'          => $form['option']['lookup_query'],
                        'lookup_table'          => $form['option']['lookup_table'],
                        'lookup_key'            => $form['option']['lookup_key'],
                        'lookup_value'          => $form['option']['lookup_value'],
                        'is_dependency'         => $form['option']['is_dependency'],
                        'select_multiple'       => (isset($form['option']['select_multiple']) ? $form['option']['select_multiple'] : 0),
                        'image_multiple'        => (isset($form['option']['image_multiple']) ? $form['option']['image_multiple'] : 0),
                        'lookup_dependency_key' => $form['option']['lookup_dependency_key'],
                        'path_to_upload'        => $form['option']['path_to_upload'],
                        'upload_type'           => $form['option']['upload_type'],
                        'resize_width'          => isset($form['option']['resize_width']) ? $form['option']['resize_width'] : '',
                        'resize_height'         => isset($form['option']['resize_height']) ? $form['option']['resize_height'] : '',
                        'extend_class'          => isset($form['option']['extend_class']) ? $form['option']['extend_class'] : '',
                        'tooltip'               => $tooltip,
                        'attribute'             => $attribute,
                        'extend_class'          => isset($form['option']['extend_class']) ? $form['option']['extend_class'] : '',
                        'prefix'                => isset($form['option']['prefix']) ? $form['option']['prefix'] : '',
                        'sufix'                 => isset($form['option']['sufix']) ? $form['option']['sufix'] : '',
                        ],
                    ];
            }
        }

        $this->data['field_type_opt'] = [
            'text'            => 'Text',
            'text_date'       => 'Date',
            'text_datetime'   => 'Date & Time',
            'textarea'        => 'Textarea',
            'textarea_editor' => 'Textarea With Editor ',
            'select'          => 'Select Option',
            'radio'           => 'Radio',
            'checkbox'        => 'Checkbox',
            'file'            => 'Upload File',
            'hidden'          => 'Hidden',
        ];

        $this->data['tables']    = Module::getTableList($this->db);
        $this->data['f']         = $f;
        $this->data['module_id'] = $id;

        $this->data['module']      = 'module';
        $this->data['module_name'] = $row->module_name;

        return view('mmb.module.field', $this->data);
    }

    public function postSaveformfield(Request $request)
    {
        $lookup_value = (is_array($request->input('lookup_value')) ? implode('|', array_filter($request->input('lookup_value'))) : '');
        $id           = $request->input('module_id');
        $row          = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);

        $view   = 0;
        $search = 0;
        if (! is_null($request->input('view'))) {
            $view = 1;
        }
        if (! is_null($request->input('search'))) {
            $search = 1;
        }

        if (preg_match('/(select|radio|checkbox)/', $request->input('type'))) {
            if ('datalist' == $request->input('opt_type')) {
                $datalist   = '';
                $cf_val     = $request->input('custom_field_val');
                $cf_display = $request->input('custom_field_display');
                for ($i = 0; $i < count($cf_val); ++$i) {
                    $value = $cf_val[$i];
                    if (isset($cf_display[$i])) {
                        $display = $cf_display[$i];
                    } else {
                        $display = 'none';
                    }
                    $datalist .= $value . ':' . $display . '|';
                }
                $datalist = substr($datalist, 0, strlen($datalist) - 1);
            } else {
                $datalist = '';
            }
        } else {
            $datalist = '';
        }

        $new_field = [
            'field'      => $request->input('field'),
            'alias'      => $request->input('alias'),
            'label'      => $request->input('label'),
            'form_group' => $request->input('form_group'),
            'required'   => $request->input('required'),
            'view'       => $view,
            'type'       => $request->input('type'),
            'add'        => 1,
            'edit'       => 1,
            'search'     => $request->input('search'),
            'size'       => '',
            'sortlist'   => $request->input('sortlist'),
            'limited'    => $request->input('limited'),
            'option'     => [
                'opt_type'              => $request->input('opt_type'),
                'lookup_query'          => $datalist,
                'lookup_table'          => $request->input('lookup_table'),
                'lookup_key'            => $request->input('lookup_key'),
                'lookup_value'          => $lookup_value,
                'is_dependency'         => $request->input('is_dependency'),
                'select_multiple'       => (! is_null($request->input('select_multiple')) ? '1' : '0'),
                'image_multiple'        => (! is_null($request->input('image_multiple')) ? '1' : '0'),
                'lookup_dependency_key' => $request->input('lookup_dependency_key'),
                'path_to_upload'        => $request->input('path_to_upload'),
                'upload_type'           => $request->input('upload_type'),
                'resize_width'          => $request->input('resize_width'),
                'resize_height'         => $request->input('resize_height'),
                'tooltip'               => $request->input('tooltip'),
                'attribute'             => $request->input('attribute'),
                'extend_class'          => $request->input('extend_class'),
                'prefix'                => $request->input('prefix'),
                'sufix'                 => $request->input('sufix'),
                ],
        ];

        $forms = [];
        foreach ($config['forms'] as $form_view) {
            if ($form_view['field'] == $request->input('field') && $form_view['alias'] == $request->input('alias')) {
                $new_form = $new_field;
            } else {
                $new_form = $form_view;
            }
            $forms[] = $new_form;
        }

        unset($config['forms']);
        $new_config = array_merge($config, ['forms' => $forms]);

        \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

        return Redirect::to('mmb/module/form/' . $row->module_name)
        ->with('messagetext', 'Forms Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function postSavetable(Request $request)
    {
        //$this->beforeFilter('csrf', array('on'=>'post'));

        $id  = $request->input('module_id');
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row    = $row[0];
        $config = \SiteHelpers::CF_decode_json($row->module_config);
        $lang   = \SiteHelpers::langOption();
        $grid   = [];
        $total  = count($_POST['field']);
        extract($_POST);
        for ($i = 1; $i <= $total; ++$i) {
            $language = [];
            foreach ($lang as $l) {
                if ('en' != $l['folder']) {
                    $label_lang             = (isset($_POST['language'][$i][$l['folder']]) ? $_POST['language'][$i][$l['folder']] : '');
                    $language[$l['folder']] = $label_lang;
                }
            }

            $grid[] = [
                'field'    => $field[$i],
                'alias'    => $alias[$i],
                'language' => $language,
                'label'    => $label[$i],
                'view'     => (isset($view[$i]) ? 1 : 0),
                'detail'   => (isset($detail[$i]) ? 1 : 0),
                'sortable' => (isset($sortable[$i]) ? 1 : 0),
                'search'   => (isset($search[$i]) ? 1 : 0),
                'download' => (isset($download[$i]) ? 1 : 0),
                'frozen'   => (isset($frozen[$i]) ? 1 : 0),
                'limited'  => (isset($limited[$i]) ? $limited[$i] : ''),
                'width'    => $width[$i],
                'align'    => $align[$i],
                'sortlist' => $sortlist[$i],
                'conn'     => [
                            'valid'   => $conn_valid[$i],
                            'db'      => $conn_db[$i],
                            'key'     => $conn_key[$i],
                            'display' => $conn_display[$i],
                ],
                'format_as'    => (isset($format_as[$i]) ? $format_as[$i] : ''),
                'format_value' => (isset($format_value[$i]) ? $format_value[$i] : ''),
            ];
        }

        unset($config['grid']);
        $new_config            = array_merge($config, ['grid' => $grid]);
        $data['module_config'] = \SiteHelpers::CF_encode_json($new_config);

        \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

        return Redirect::to('mmb/module/table/' . $row->module_name)
        ->with('messagetext', 'Module Table Has Been Saved Successfully')->with('msgstatus', 'success');
    }

    public function getPermission($id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                    ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                       = $row[0];
        $this->data['row']         = $row;
        $this->data['module']      = 'module';
        $this->data['module_name'] = $row->module_name;
        $config                    = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['type']        = $row->module_type;

        $tasks = [
                'is_global' => 'Global',
                'is_view'   => 'View ',
                'is_detail' => 'Detail',
                'is_add'    => 'Add ',
                'is_clone'  => 'Clone ',
                'is_edit'   => 'Edit ',
                'is_remove' => 'Remove ',
                'is_excel'  => 'Export ',
            ];
        /* Update permission global / own access new ver 1.1
           Adding new param is_global
           End Update permission global / own access new ver 1.1
        */
        if (isset($config['tasks'])) {
            foreach ($config['tasks'] as $row) {
                $tasks[$row['item']] = $row['title'];
            }
        }
        $this->data['tasks']  = $tasks;
        $this->data['groups'] = \DB::table('tb_groups')->get();

        $access = [];
        foreach ($this->data['groups'] as $r) {
            //    $GA = $this->model->gAccessss($this->uri->rsegment(3),$row['group_id']);
            $group = (null != $r->group_id ? "and group_id ='" . $r->group_id . "'" : '');
            $GA    = \DB::select("SELECT * FROM tb_groups_access where module_id ='" . $row->module_id . "' $group");
            if (count($GA) >= 1) {
                $GA = $GA[0];
            }

            $access_data = (isset($GA->access_data) ? json_decode($GA->access_data, true) : []);
            $rows        = [];
            //$access_data = json_decode($AD,true);
            $rows['group_id']   = $r->group_id;
            $rows['group_name'] = $r->name;
            foreach ($tasks as $item => $val) {
                $rows[$item] = (isset($access_data[$item]) && 1 == $access_data[$item] ? 1 : 0);
            }
            $access[$r->name] = $rows;
        }
        $this->data['access']        = $access;
        $this->data['groups_access'] = \DB::select("select * from tb_groups_access where module_id ='" . $row->module_id . "'");
        $this->data['type']          = ('ajax' == $row->module_type ? 'addon' : $row->module_type);

        return view('mmb.module.permission', $this->data);
    }

    public function postSavepermission(Request $request)
    {
        $id  = $request->input('module_id');
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);
        $tasks             = [
                'is_global' => 'Global',
                'is_view'   => 'View ',
                'is_detail' => 'Detail',
                'is_add'    => 'Add ',
                'is_clone'  => 'Clone ',
                'is_edit'   => 'Edit ',
                'is_remove' => 'Remove ',
                'is_excel'  => 'Export ',
        ];
        /* Update permission global / own access new ver 1.1
           Adding new param is_global
           End Update permission global / own access new ver 1.1
        */
        if (isset($config['tasks'])) {
            foreach ($config['tasks'] as $row) {
                $tasks[$row['item']] = $row['title'];
            }
        }

        $permission = [];
        $groupID    = $request->input('group_id');
        for ($i = 0; $i < count($groupID); ++$i) {
            // remove current group_access
            $group_id = $groupID[$i];
            \DB::table('tb_groups_access')
                              ->where('module_id', '=', $request->input('module_id'))
                              ->where('group_id', '=', $group_id)
                              ->delete();

            $arr = [];
            $id  = $groupID[$i];
            foreach ($tasks as $t => $v) {
                $arr[$t] = (isset($_POST[$t][$id]) ? '1' : '0');
            }
            $permissions = json_encode($arr);

            $data = [
                'access_data' => $permissions,
                'module_id'   => $request->input('module_id'),
                'group_id'    => $groupID[$i],
            ];
            \DB::table('tb_groups_access')->insert($data);
        }

        return Redirect::to('mmb/module/permission/' . $row->module_name)
        ->with('messagetext', 'Permission Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function getBuild($id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row = $row[0];

        $this->data['module']      = 'module';
        $this->data['module_name'] = $id;
        $this->data['module_id']   = $row->module_id;

        return view('mmb.module.build', $this->data);
    }

    public function getFormdesign(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to($this->module)
                 ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                       = $row[0];
        $this->data['row']         = $row;
        $config                    = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['forms']       = $config['forms'];
        $this->data['module']      = 'module';
        $this->data['form_column'] = (isset($config['form_column']) ? $config['form_column'] : 1);
        if (! is_null($request->input('block'))) {
            $this->data['form_column'] = $request->input('block');
        }

        if (! isset($config['form_layout'])) {
            $this->data['title']   = [$row->module_name];
            $this->data['format']  = 'grid';
            $this->data['display'] = 'horizontal';
        } else {
            $this->data['title']   = explode(',', $config['form_layout']['title']);
            $this->data['format']  = $config['form_layout']['format'];
            $this->data['display'] = (isset($config['form_layout']['display']) ? $config['form_layout']['display'] : 'horizontal');
        }
        $this->data['module_name'] = $row->module_name;
        $this->data['type']        = $row->module_type;

        return view('mmb.module.formdesign', $this->data);
    }

    public function postFormdesign(Request $request)
    {
        $id  = $request->input('module_id');
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row = $row[0];

        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);
        $data              = $_POST['reordering'];
        $data              = explode('|', $data);
        $currForm          = $config['forms'];

        foreach ($currForm as $f) {
            $cform[$f['field']] = $f;
        }

        $i     = 0;
        $order = 0;
        $f     = [];
        foreach ($data as $dat) {
            $forms = explode(',', $dat);
            foreach ($forms as $form) {
                if (isset($cform[$form])) {
                    $cform[$form]['form_group'] = $i;
                    $cform[$form]['sortlist']   = $order;
                    $f[]                        = $cform[$form];
                }
                ++$order;
            }
            ++$i;
        }

        $config['form_column'] = count($data);
        $config['form_layout'] = [
            'column'  => count($data),
            'title'   => implode(',', $request->input('title')),
            'format'  => $request->input('format'),
            'display' => $request->input('display'),
        ];

        unset($config['forms']);
        $new_config            = array_merge($config, ['forms' => $f]);
        $data['module_config'] = \SiteHelpers::CF_encode_json($new_config);

        \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

        return Redirect::to('mmb/module/formdesign/' . $row->module_name)
        ->with('messagetext', ' Forms Design Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function getSub(Request $request, $id = '')
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                       = $row[0];
        $config                    = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['row']         = $row;
        $this->data['fields']      = $config['grid'];
        $this->data['subs']        = (isset($config['subgrid']) ? $config['subgrid'] : []);
        $this->data['tables']      = Module::getTableList($this->db);
        $this->data['module']      = $row->module_name;
        $this->data['module_name'] = $id;
        $this->data['type']        = ('ajax' == $row->module_type ? 'addon' : $row->module_type);
        $this->data['modules']     = Module::all();

        return view('mmb.module.sub', $this->data);
    }

    public function postSavesub(Request $request)
    {
        $rules = [
            'title'      => 'required',
            'master'     => 'required',
            'master_key' => 'required',
            'module'     => 'required',
            'key'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $id  = $request->get('module_id');
            $row = \DB::table('tb_module')->where('module_id', $id)
                                    ->get();
            if (count($row) <= 0) {
                return Redirect::to('mmb/module')
                    ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
            }
            $row               = $row[0];
            $this->data['row'] = $row;
            $config            = \SiteHelpers::CF_decode_json($row->module_config);

            $newData[] = [
                'title'      => $request->get('title'),
                'master'     => $request->get('master'),
                'master_key' => $request->get('master_key'),
                'module'     => $request->get('module'),
                'table'      => $request->get('table'),
                'key'        => $request->get('key'),
            ];

            $subgrid = [];
            if (isset($config['subgrid'])) {
                foreach ($config['subgrid'] as $sb) {
                    $subgrid[] = $sb;
                }
            }
            $subgrid = array_merge($subgrid, $newData);

            if (isset($config['subgrid'])) {
                unset($config['subgrid']);
            }
            $new_config = array_merge($config, ['subgrid' => $subgrid]);

            $affected = \DB::table('tb_module')
                ->where('module_id', '=', $id)
                ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

            return Redirect::to('mmb/module/sub/' . $row->module_name)
            ->with('messagetext', 'Master Has beed added Successful.')->with('msgstatus', 'success');
        } else {
            return Redirect::to('mmb/module/sub/' . $request->get('module_name'))
            ->with('message', 'The following errors occurred')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getRemovesub(Request $request)
    {
        $id     = $request->get('id');
        $module = $request->get('mod');
        $row    = \DB::table('tb_module')->where('module_id', $id)->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')
                 ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;

        $config  = \SiteHelpers::CF_decode_json($row->module_config);
        $subgrid = [];

        foreach ($config['subgrid'] as $sb) {
            if ($sb['module'] != $module) {
                $subgrid[] = $sb;
            }
        }
        unset($config['subgrid']);
        $new_config = array_merge($config, ['subgrid' => $subgrid]);

        $affected = \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

        return Redirect::to('mmb/module/sub/' . $row->module_name)
        ->with('messagetext', 'Master Has removed Successful.')->with('msgstatus', 'success');
    }

    public function getConn(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);

        $module_id = $id;
        $field_id  = $request->input('field');
        $alias     = $request->input('alias');
        $f         = [];
        foreach ($config['grid'] as $form) {
            if ($form['field'] == $field_id) {
                $f = [
                    'db'      => (isset($form['conn']['db']) ? $form['conn']['db'] : ''),
                    'key'     => (isset($form['conn']['key']) ? $form['conn']['key'] : ''),
                    'display' => (isset($form['conn']['display']) ? $form['conn']['display'] : ''),
                    ];
            }
        }

        $this->data['module_id']   = $id;
        $this->data['f']           = $f;
        $this->data['module']      = 'module';
        $this->data['module_name'] = $row->module_name;
        $this->data['field_id']    = $field_id;
        $this->data['alias']       = $alias;

        return view('mmb.module.connection', $this->data);
    }

    public function postConn(Request $request)
    {
        $id       = $request->input('module_id');
        $field_id = $request->input('field_id');
        $alias    = $request->input('alias');
        $row      = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to($this->module)
                ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row = $row[0];

        $this->data['row'] = $row;
        $fr                = [];
        $config            = \SiteHelpers::CF_decode_json($row->module_config);
        foreach ($config['grid'] as $form) {
            if ($form['field'] == $field_id && $form['alias'] == $alias) {
                if ('' != $request->input('db')) {
                    $value        = implode('|', $request->input('display'));
                    $form['conn'] = [
                        'valid'   => '1',
                        'db'      => $request->input('db'),
                        'key'     => $request->input('key'),
                        'display' => implode('|', array_filter($request->input('display'))),
                        ];
                } else {
                    $form['conn'] = [
                        'valid'   => '0',
                        'db'      => '',
                        'key'     => '',
                        'display' => '',
                        ];
                }
                $fr[] = $form;
            } else {
                $fr[] = $form;
            }
        }
        unset($config['grid']);
        $new_config = array_merge($config, ['grid' => $fr]);

        $affected = \DB::table('tb_module')
            ->where('module_id', '=', $id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

        return Redirect::to('mmb/module/table/' . $row->module_name)
        ->with('messagetext', 'Module Forms Has Changed Successful.')->with('msgstatus', 'success');
    }

    public function configGrid($field, $alias, $type, $sort)
    {
        $grid = [
            'field'        => $field,
            'alias'        => $alias,
            'label'        => ucwords(str_replace('_', ' ', $field)),
            'language'     => [],
            'search'       => '1',
            'download'     => '1',
            'align'        => 'left',
            'view'         => '1',
            'detail'       => '1',
            'sortable'     => '1',
            'frozen'       => '0',
            'hidden'       => '0',
            'sortlist'     => $sort,
            'width'        => '100',
            'conn'         => ['valid' => '0', 'db' => '', 'key' => '', 'display' => ''],
            'format_as'    => '',
            'format_value' => '',
        ];

        return $grid;
    }

    public function configForm($field, $alias, $type, $sort, $opt = [])
    {
        $opt_type     = '';
        $lookup_table = '';
        $lookup_key   = '';
        if (count($opt) >= 1) {
            $opt_type     = $opt[0];
            $lookup_table = $opt[1];
            $lookup_key   = $opt[2];
        }

        $forms = [
            'field'    => $field,
            'alias'    => $alias,
            'label'    => ucwords(str_replace('_', ' ', $field)),
            'language' => [],
            'required' => '0',
            'view'     => '1',
            'type'     => self::configFieldType($type),
            'add'      => '1',
            'edit'     => '1',
            'search'   => '1',

            'size'       => 'span12',
            'sortlist'   => $sort,
            'form_group' => '',
            'option'     => [
                'opt_type'              => $opt_type,
                'lookup_query'          => '',
                'lookup_table'          => $lookup_table,
                'lookup_key'            => $lookup_key,
                'lookup_value'          => $lookup_key,
                'is_dependency'         => '',
                'select_multiple'       => '0',
                'image_multiple'        => '0',
                'lookup_dependency_key' => '',
                'path_to_upload'        => '',
                'upload_type'           => '',
                'tooltip'               => '',
                'attribute'             => '',
                'extend_class'          => '',
                ],
            ];

        return $forms;
    }

    public function configFieldType($type)
    {
        switch ($type) {
            default: $type          = 'text'; break;
            case 'timestamp': $type = 'text_datetime'; break;
            case 'datetime': $type  = 'text_datetime'; break;
            case 'string': $type    = 'text'; break;
            case 'int': $type       = 'text'; break;
            case 'text': $type      = 'textarea'; break;
            case 'blob': $type      = 'textarea'; break;
            case 'select': $type    = 'select'; break;
        }

        return $type;
    }

    public function getCombotable(Request $request)
    {
        if (true == $request->ajax() && true == \Auth::check()) {
            $rows  = Module::getTableList($this->db);
            $items = [];
            foreach ($rows as $row) {
                $items[] = [$row, $row];
            }

            return json_encode($items);
        } else {
            return json_encode(['OMG' => '  Ops .. Cant access the page !']);
        }
    }

    public function getCombotablefield(Request $request)
    {
        if ('' == $request->input('table')) {
            return json_encode([]);
        }
        if (true == $request->ajax() && true == \Auth::check()) {
            $items = [];
            $table = $request->input('table');
            if ('' != $table) {
                $rows = Module::getTableField($request->input('table'));
                foreach ($rows as $row) {
                    $items[] = [$row, $row];
                }
            }

            return json_encode($items);
        } else {
            return json_encode(['OMG' => '  Ops .. Cant access the page !']);
        }
    }

    public function postDobuild(Request $request, $id)
    {
        $id = $request->input('module_id');
        $c  = (isset($_POST['controller']) ? 'y' : 'n');
        $m  = (isset($_POST['model']) ? 'y' : 'n');
        $g  = (isset($_POST['grid']) ? 'y' : 'n');
        $f  = (isset($_POST['form']) ? 'y' : 'n');
        $v  = (isset($_POST['view']) ? 'y' : 'n');
        $fg = (isset($_POST['frontgrid']) ? 'y' : 'n');
        $fv = (isset($_POST['frontview']) ? 'y' : 'n');
        $ff = (isset($_POST['frontform']) ? 'y' : 'n');

        //return redirect('')

        $url = 'mmb/module/rebuild/' . $id . "?rebuild=y&c={$c}&m={$m}&g={$g}&f={$f}&v={$v}&fg={$fg}&fv={$fv}&ff={$ff}";

        if ('ajax' == \Request::ajax()) {
            return response()->json(['status' => 'success', 'url' => url($url)]);
        } else {
            return Redirect::to($url);
        }
    }

    public function getRebuild(Request $request, $id = 0)
    {
        $row = \DB::table('tb_module')->where('module_id', $id)->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;
        $config            = \SiteHelpers::CF_decode_json($row->module_config);
        $class             = $row->module_name;
        $ctr               = ucwords($row->module_name);
        $path              = $row->module_name;
        // build Field entry
        $f   = '';
        $req = '';

        // End Build Field Entry

        $codes = [
                'controller' => ucwords($class),
                'class'      => $class,
                'fields'     => $f,
                'required'   => $req,
                'table'      => $row->module_db,
                'title'      => $row->module_title,
                'note'       => $row->module_note,
                'key'        => $row->module_db_key,
                'sql_select' => $config['sql_select'],
                'sql_where'  => $config['sql_where'],
                'sql_group'  => $config['sql_group'],
            ];
        if (! isset($config['form_layout'])) {
            $config['form_layout'] = ['column' => 1, 'title' => $row->module_title, 'format' => 'grid', 'display' => 'horizontal'];
        }

        $codes['form_javascript'] = \SiteHelpers::toJavascript($config['forms'], $path, $class);
        $codes['form_entry']      = \SiteHelpers::toForm($config['forms'], $config['form_layout']);
        $codes['form_display']    = (isset($config['form_layout']['display']) ? $config['form_layout']['display'] : 'horizontal');
        $codes['form_view']       = \SiteHelpers::toView($config['grid']);

        $codes['masterdetailmodel']   = '';
        $codes['masterdetailinfo']    = '';
        $codes['masterdetailgrid']    = '';
        $codes['masterdetailsave']    = '';
        $codes['masterdetailform']    = '';
        $codes['masterdetailsubform'] = '';
        $codes['masterdetailview']    = '';
        $codes['masterdetailjs']      = '';
        $codes['masterdetaildelete']  = '';

        /* Subform */
        if (isset($config['subform'])) {
            $md                           = \SiteHelpers::toMasterDetail($config['subform']);
            $codes['masterdetailmodel']   = $md['masterdetailmodel'];
            $codes['masterdetailinfo']    = $md['masterdetailinfo'];
            $codes['masterdetailsave']    = $md['masterdetailsave'];
            $codes['masterdetailsubform'] = $md['masterdetailsubform'];
            $codes['masterdetailform']    = $md['masterdetailform'];
            $codes['masterdetaildelete']  = $md['masterdetaildelete'];
            $codes['masterdetailjs']      = $md['masterdetailjs'];
        }

        /* End Master Detail */
        $dir       = base_path() . '/resources/views/' . $class;
        $dirPublic = base_path() . '/resources/views/' . $class . '/public';
        $dirC      = app_path() . '/Http/Controllers/';
        $dirM      = app_path() . '/Models/';

        if (! is_dir($dir)) {
            mkdir($dir, 0777);
        }
        if (! is_dir($dirPublic)) {
            mkdir($dirPublic, 0777);
        }

        // BLANK TEMPLATE
        if ('generic' == $row->module_type) {
            $template   = base_path() . '/resources/views/mmb/module/template/blank/';
            $controller = file_get_contents($template . 'controller.tpl');
            $grid       = file_get_contents($template . 'grid.tpl');
            $view       = file_get_contents($template . 'view.tpl');
            $form       = file_get_contents($template . 'form.tpl');
            $model      = file_get_contents($template . 'model.tpl');

            $build_controller = \SiteHelpers::blend($controller, $codes);
            $build_view       = \SiteHelpers::blend($view, $codes);
            $build_form       = \SiteHelpers::blend($form, $codes);
            $build_grid       = \SiteHelpers::blend($grid, $codes);
            $build_model      = \SiteHelpers::blend($model, $codes);

            file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
            file_put_contents($dirM . "{$ctr}.php", $build_model);
            file_put_contents($dir . '/index.blade.php', $build_grid);
            file_put_contents($dir . '/form.blade.php', $build_form);
            file_put_contents($dir . '/view.blade.php', $build_view);
        }

        if ('report' == $row->module_type) {
            $template   = base_path() . '/resources/views/mmb/module/template/report/';
            $controller = file_get_contents($template . 'controller.tpl');
            $grid       = file_get_contents($template . 'grid.tpl');
            $model      = file_get_contents($template . 'model.tpl');

            $build_controller = \SiteHelpers::blend($controller, $codes);
            $build_grid       = \SiteHelpers::blend($grid, $codes);
            $build_model      = \SiteHelpers::blend($model, $codes);

            file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
            file_put_contents($dirM . "{$ctr}.php", $build_model);
            file_put_contents($dir . '/index.blade.php', $build_grid);
        }

        if ('addon' == $row->module_type) {
            $template   = base_path() . '/resources/views/mmb/module/template/native/';
            $controller = file_get_contents($template . 'controller.tpl');
            $grid       = file_get_contents($template . 'grid.tpl');
            $view       = file_get_contents($template . 'view.tpl');
            $form       = file_get_contents($template . 'form.tpl');
            $model      = file_get_contents($template . 'model.tpl');
            $front      = file_get_contents($template . 'frontend.tpl');
            $frontview  = file_get_contents($template . 'frontendview.tpl');
            $frontform  = file_get_contents($template . 'frontform.tpl');

            if (isset($config['subgrid']) && count($config['subgrid']) >= 1) {
                $view = file_get_contents($template . 'view_detail.tpl');
            } else {
                $view = file_get_contents($template . 'view.tpl');
            }

            $build_controller = \SiteHelpers::blend($controller, $codes);
            $build_view       = \SiteHelpers::blend($view, $codes);
            $build_form       = \SiteHelpers::blend($form, $codes);
            $build_grid       = \SiteHelpers::blend($grid, $codes);
            $build_model      = \SiteHelpers::blend($model, $codes);
            $build_front      = \SiteHelpers::blend($front, $codes);
            $build_frontview  = \SiteHelpers::blend($frontview, $codes);
            $build_frontform  = \SiteHelpers::blend($frontform, $codes);

            if (! is_null($request->input('rebuild'))) {
                // rebuild spesific files
                if ('y' == $request->input('c')) {
                    file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
                }
                if ('y' == $request->input('m')) {
                    file_put_contents($dirM . "{$ctr}.php", $build_model);
                }

                if ('y' == $request->input('g')) {
                    file_put_contents($dir . '/index.blade.php', $build_grid);
                }
                if ('' != $row->module_db_key) {
                    if ('y' == $request->input('f')) {
                        file_put_contents($dir . '/form.blade.php', $build_form);
                    }

                    if ('y' == $request->input('v')) {
                        file_put_contents($dir . '/view.blade.php', $build_view);
                    }

                    // Frontend Grid
                    if ('y' == $request->input('fg')) {
                        file_put_contents($dir . '/public/index.blade.php', $build_front);
                    }
                    // Frontend View
                    if ('y' == $request->input('fv')) {
                        file_put_contents($dir . '/public/view.blade.php', $build_frontview);
                    }
                    // Frontend Form
                    if ('y' == $request->input('ff')) {
                        file_put_contents($dir . '/public/form.blade.php', $build_frontform);
                    }
                }
            } else {
                file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
                file_put_contents($dirM . "{$ctr}.php", $build_model);
                file_put_contents($dir . '/index.blade.php', $build_grid);
                file_put_contents($dir . '/form.blade.php', $build_form);
                file_put_contents($dir . '/view.blade.php', $build_view);
                file_put_contents($dir . '/public/index.blade.php', $build_front);
                file_put_contents($dir . '/public/view.blade.php', $build_frontview);
                file_put_contents($dir . '/public/form.blade.php', $build_frontform);
            }
        }

        if ('ajax' == $row->module_type) {
            // Do CRUD using Ajax
            $template   = base_path() . '/resources/views/mmb/module/template/ajax/';
            $controller = file_get_contents($template . 'controller.tpl');
            $grid       = file_get_contents($template . 'grid.tpl');
            if (isset($config['subgrid']) && count($config['subgrid']) >= 1) {
                $view = file_get_contents($template . 'view_detail.tpl');
            } else {
                $view = file_get_contents($template . 'view.tpl');
            }

            $form      = file_get_contents($template . 'form.tpl');
            $model     = file_get_contents($template . 'model.tpl');
            $table     = file_get_contents($template . 'table.tpl');
            $toolbar   = file_get_contents($template . 'toolbar.tpl');
            $front     = file_get_contents($template . 'frontend.tpl');
            $frontview = file_get_contents($template . 'frontendview.tpl');
            $frontform = file_get_contents($template . 'frontform.tpl');

            if ('' == $row->module_db_key) {
                $controller = file_get_contents($template . 'controller_view.tpl');
                $grid       = file_get_contents($template . 'grid_view.tpl');
                $toolbar    = file_get_contents($template . 'toolbar_view.tpl');
                $table      = file_get_contents($template . 'table_view.tpl');
            }

            $build_controller = \SiteHelpers::blend($controller, $codes);
            $build_view       = \SiteHelpers::blend($view, $codes);
            $build_form       = \SiteHelpers::blend($form, $codes);
            $build_grid       = \SiteHelpers::blend($grid, $codes);
            $build_table      = \SiteHelpers::blend($table, $codes);
            $build_model      = \SiteHelpers::blend($model, $codes);
            $build_toolbar    = \SiteHelpers::blend($toolbar, $codes);
            $build_front      = \SiteHelpers::blend($front, $codes);
            $build_frontview  = \SiteHelpers::blend($frontview, $codes);
            $build_frontform  = \SiteHelpers::blend($frontform, $codes);

            if (! is_null($request->get('rebuild'))) {
                file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
                // rebuild spesific files
                if ('y' == $request->input('c')) {
                    file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
                }
                if ('y' == $request->input('m')) {
                    file_put_contents($dirM . "{$ctr}.php", $build_model);
                }
                if ('y' == $request->input('g')) {
                    file_put_contents($dir . '/index.blade.php', $build_grid);
                }
                if ('y' == $request->input('g')) {
                    file_put_contents($dir . '/table.blade.php', $build_grid);
                }
                if ('' != $row->module_db_key) {
                    if ('y' == $request->input('f')) {
                        file_put_contents($dir . '/form.blade.php', $build_form);
                    }
                    if ('y' == $request->input('v')) {
                        if (isset($config['subgrid']) && count($config['subgrid']) >= 1) {
                            file_put_contents($dir . '/view_detail.blade.php', $build_view);
                        } else {
                            file_put_contents($dir . '/view.blade.php', $build_view);
                        }
                    }
                    // Frontend Grid
                    if ('y' == $request->input('fg')) {
                        file_put_contents($dir . '/public/index.blade.php', $build_front);
                    }
                    // Frontend View
                    if ('y' == $request->input('fv')) {
                        file_put_contents($dir . '/public/view.blade.php', $build_frontview);
                    }
                    // Frontend Form
                    if ('y' == $request->input('ff')) {
                        file_put_contents($dir . '/public/form.blade.php', $build_frontform);
                    }
                }
            } else {
                file_put_contents($dirC . "{$ctr}Controller.php", $build_controller);
                file_put_contents($dirM . "{$ctr}.php", $build_model);
                file_put_contents($dir . '/index.blade.php', $build_grid);
                file_put_contents($dir . '/form.blade.php', $build_form);
                file_put_contents($dir . '/view.blade.php', $build_view);
                file_put_contents($dir . '/table.blade.php', $build_table);
                file_put_contents($dir . '/toolbar.blade.php', $build_toolbar);
                file_put_contents($dir . '/public/index.blade.php', $build_front);
                file_put_contents($dir . '/public/view.blade.php', $build_frontview);
                file_put_contents($dir . '/public/form.blade.php', $build_frontform);
            }
        }

        self::createRouters();

        if (true == $request->ajax() && true == \Auth::check()) {
            return response()->json(['status' => 'success', 'message' => 'Code Script has been replaced successfull']);
        } else {
            return Redirect::to('mmb/module')->with('messagetext', 'Code Script has been replaced successfull')->with('msgstatus', 'success');
        }
    }

    public function findPrimarykey($table)
    {
        //  show columns from members where extra like '%auto_increment%'"
        $query      = "SHOW columns FROM `{$table}` WHERE extra LIKE '%auto_increment%'";
        $primaryKey = '';
        foreach (\DB::select($query) as $key) {
            $primaryKey = $key->Field;
            // print_r($key);
        }

        return $primaryKey;
    }

    public function buildRelation($table, $field)
    {
        $pdo = \DB::getPdo();
        $sql = "
        SELECT
            referenced_table_name AS 'table',
            referenced_column_name AS 'column'
        FROM
            information_schema.key_column_usage
        WHERE
            referenced_table_name IS NOT NULL
            AND table_schema = '" . $this->db . "'  AND table_name = '{$table}' AND column_name = '{$field}' ";
        $Q    = $pdo->query($sql);
        $rows = [];
        while ($row = $Q->fetch()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function createRouters()
    {
        $rows = \DB::table('tb_module')->where('module_type', '!=', 'core')->get();
        $val  = '<?php
        ';
        foreach ($rows as $row) {
            $class      = $row->module_name;
            $controller = ucwords($row->module_name) . 'Controller';
            $val .= "Route::controller('{$class}', '{$controller}');
                    ";
        }
        $val .= '?>';
        $filename = base_path('routes/web/modules.php');
        $fp       = fopen($filename, 'w+');
        fwrite($fp, $val);
        fclose($fp);

        return true;
    }

    public function postPackage(Request $request)
    {
        if (count($id = $request->input('id')) < 1) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }

        //$id = explode(',', $id );

        $_id = [];
        foreach ($id as $k => $v) {
            if (! is_numeric($v)) {
                continue;
            }
            $_id[] = $v;
        }

        $ids = implode(',', $_id);

        $sql = '  
            SELECT * FROM tb_module 
            WHERE module_id IN (' . $ids . ') 
            ORDER by module_id 
            ';

        $rows = \DB::select($sql);

        $this->data['zip_content'] = [];
        $app_info                  = [];
        $inc_tables                = [];

        foreach ($rows as $k => $row) {
            $zip_content[] = [
            'module_id'   => $row->module_id,
            'module_name' => $row->module_name,
            'module_db'   => $row->module_db,
            'module_type' => $row->module_type,
          ];
        }

        // encrypt info
        $this->data['enc_module'] = base64_encode(serialize($zip_content));
        $this->data['enc_id']     = base64_encode(serialize($id));

        // module info
        $this->data['zip_content'] = $zip_content;

        /* CHANGE START HERE */
        $app_path = base_path();

        // file helper list
        $_path_inc = ['app/Library', 'resources/lang/en'];

        foreach ($_path_inc as $path) {
            $file_inc[$path] = scandir($app_path . '/' . $path);
            foreach ($file_inc[$path] as $k => $v) {
                if ('.' == $v || '..' == $v) {
                    unset($file_inc[$path][$k]);
                }
                if (! preg_match('/.php$/i', $v)) {
                    unset($file_inc[$path][$k]);
                }
            }
        }

        $this->data['file_inc'] = $file_inc;

        /* CHANGE END HERE */

        return view('mmb.module.package', $this->data);
    }

    public function postDopackage(Request $request)
    {
        // app name
        $app_name = $request->input('app_name');

        // encrypt info
        $enc_module = $request->input('enc_module');
        $enc_id     = $request->input('enc_id');

        // query command || file
        $sql_cmd = $request->input('sql_cmd');

        if (! ($_FILES['sql_cmd_upload']['error'])) {
            $sql_path = input::file('sql_cmd_upload')->getrealpath();
            if ($sql_content = file_get_contents($sql_path)) {
                $sql_cmd = $sql_content;
            }
        }

        /* CHANGE START */

        // file to include
        $file_library = $request->input('file_library');
        $file_lang    = $request->input('file_lang');

        /* CHANGE END */

        // create app name
        $tapp_code = preg_replace('/([s[:punct:]]+)/', ' ', $app_name);
        $app_code  = str_replace(' ', '_', trim($tapp_code));

        $module_id   = unserialize(base64_decode($enc_id));
        $modules     = unserialize(base64_decode($enc_module));
        $c_module_id = implode(',', $module_id);

        $zip_file = "./uploads/zip/{$app_code}.zip";

        $cf_zip = new \ZipHelpers();

        $app_path = app_path();

        $cf_zip->add_data('.mysql', $sql_cmd);

        // App ID Name
        $ain = $module_id;
        $cf_zip->add_data('.ain', base64_encode(serialize($ain)));

        // setting
        $sql = " select * from tb_module where module_id in ( {$c_module_id} )";

        $_modules = \DB::select($sql);

        foreach ($_modules as $n => $_module) {
            $_modules[$n]->module_id = '';
        }

        $setting['tb_module'] = $_modules;

        $cf_zip->add_data('.setting', base64_encode(serialize($setting)));

        unset($_module);

        foreach ($_modules as $n => $_module) {
            $file = $_module->module_name;
            $cf_zip->add_data('app/Http/Controllers/' . ucwords($file) . 'Controller.php',
                              file_get_contents($app_path . '/Http/Controllers/' . ucwords($file) . 'Controller.php'));
            $cf_zip->add_data('app/Models/' . ucwords($file) . '.php', file_get_contents($app_path . '/Models/' . ucwords($file) . '.php'));
            $cf_zip->get_files_from_folder("../resources/views/{$file}/", "resources/views/{$file}/");
        }

        // CHANGE START

        // push library files
        if (! empty($file_library)) {
            foreach ($file_library as $k => $file) {
                $cf_zip->add_data('app/Library/' . $file,
                             file_get_contents($app_path . '/Library/' . $file));
            }
        }

        // push language files

        if (! empty($file_lang)) {
            $lang_path = scandir(base_path() . '/resources/lang/');
            foreach ($lang_path as $k => $path) {
                if ('.' == $path || '..' == $path) {
                    continue;
                }
                if (is_file($app_path . '/' . $path)) {
                    continue;
                }

                foreach ($file_lang as $k => $file) {
                    $cf_zip->add_data('resources/lang/' . $path . '/' . $file,
                   file_get_contents(base_path() . '/resources/lang/' . $path . '/' . $file));
                }
            }
            $this->data['lang_path'] = $lang_path;
        }

        // CHANGE END

        $_zip = $cf_zip->archive($zip_file);

        $cf_zip->clear_data();

        $this->data['download_link'] = link_to("uploads/zip/{$app_name}.zip", 'download here', ['target' => '_new']);

        $this->data['module_title'] = 'ZIP Packager';
        $this->data['app_name']     = $app_name;

        return Redirect::to('mmb/module')
        ->with('messagetext', ' Module(s) zipped successful ! ')->with('msgstatus', 'success');
    }

    public function postInstall(Request $request, $id = 0)
    {
        $rules = [
      //    'file'    => 'required'
    ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $path = $_FILES['installer']['tmp_name'];
            $data = \MmbHelpers::cf_unpackage($path);

            $msg = '.';
            if (isset($data['sql_error'])) {
                $msg = ', with SQL error ' . $data['sql_error'];
            }

            self::createRouters();

            return Redirect::to('mmb/module')->with('messagetext', 'Module Installed' . $msg)->with('msgstatus', 'success');
        } else {
            return Redirect::to('mmb/module')->with('messagetext', 'Please select file to upload !')->with('msgstatus', 'error');
        }
    }

    public function getSubform(Request $request, $id = 0)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                   = $row[0];
        $config                = \SiteHelpers::CF_decode_json($row->module_config);
        $this->data['row']     = $row;
        $this->data['fields']  = $config['grid'];
        $this->data['subform'] = (isset($config['subform']) ? $config['subform'] : []);
        //  print_r($this->data['subform']);
        $this->data['tables']      = Module::getTableList($this->db);
        $this->data['module']      = $row->module_name;
        $this->data['module_name'] = $id;
        $this->data['type']        = $row->module_type;
        $this->data['modules']     = Module::all();

        return view('mmb.module.subform', $this->data);
    }

    public function postSavesubform(Request $request)
    {
        $rules = [
            'title'      => 'required',
            'master'     => 'required',
            'master_key' => 'required',
            'module'     => 'required',
            'key'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $id  = $request->get('module_id');
            $row = \DB::table('tb_module')->where('module_id', $id)
                                    ->get();
            if (count($row) <= 0) {
                return Redirect::to('mmb/module/subform/' . $request->get('module_name'))
                    ->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
            }
            $row               = $row[0];
            $this->data['row'] = $row;
            $config            = \SiteHelpers::CF_decode_json($row->module_config);

            $subform = [
                'title'      => $request->get('title'),
                'master'     => $request->get('master'),
                'master_key' => $request->get('master_key'),
                'module'     => $request->get('module'),
                'table'      => $request->get('table'),
                'key'        => $request->get('key'),
            ];
            /*
            $subform = array();
            if(isset($config["subform"]))
            {
                foreach($config['subform'] as $sb)
                {
                    $subgrid[] =$sb;
                }

            }
            $subform = array_merge($subform,$newData);
            */

            if (isset($config['subform'])) {
                unset($config['subform']);
            }
            $new_config = array_merge($config, ['subform' => $subform]);

            $affected = \DB::table('tb_module')
                ->where('module_id', '=', $id)
                ->update(['module_config' => \SiteHelpers::CF_encode_json($new_config)]);

            return Redirect::to('mmb/module/subform/' . $row->module_name)
            ->with('messagetext', 'Subform has beed added Successful.')->with('msgstatus', 'success');
        } else {
            return Redirect::to('mmb/module/subform/' . $request->get('module_name'))
            ->with('message', 'The following errors occurred')->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function getSubformremove(Request $request, $id = 0)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row    = $row[0];
        $config = \SiteHelpers::CF_decode_json($row->module_config);

        unset($config['subform']);

        // echo '<pre>'; print_r($config); echo '</pre>'; exit;
        //  $new_config =     array_merge($config,array("subform" => array()));
        $affected = \DB::table('tb_module')
            ->where('module_id', '=', $row->module_id)
            ->update(['module_config' => \SiteHelpers::CF_encode_json($config)]);

        return Redirect::to('mmb/module/subform/' . $row->module_name)
            ->with('messagetext', 'Subform has been removed successfully.')->with('msgstatus', 'success');
    }

    public function getSource(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_name', $id)->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row                       = $row[0];
        $this->data['row']         = $row;
        $this->data['module']      = 'module';
        $this->data['module_lang'] = json_decode($row->module_lang, true);
        $this->data['module_name'] = $row->module_name;
        $config                    = \SiteHelpers::CF_decode_json($row->module_config, true);
        $this->data['tables']      = $config['grid'];
        $this->data['type']        = $row->module_type;

        return view('mmb.module.source', $this->data);
    }

    public function postSource(Request $request)
    {
        $_POST['dir'] = urldecode($_POST['dir']);
        $root         = base_path() . '/resources/views';
        $res          = '';

        if (file_exists($root . $_POST['dir'])) {
            $files = scandir($root . $_POST['dir']);
            natcasesort($files);
            if (count($files) > 2) { /* The 2 accounts for . and .. */
                $res .= '<ul class="jqueryFileTree" style="display: none;">';
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($root . $_POST['dir'] . $file) && '.' != $file && '..' != $file && is_dir($root . $_POST['dir'] . $file)) {
                        $res .= '<li class="directory collapsed"><a href="#" rel="' . htmlentities($_POST['dir'] . $file) . '/">' . htmlentities($file) . '</a></li>';
                    }
                }
                // All files
                foreach ($files as $file) {
                    if (file_exists($root . $_POST['dir'] . $file) && '.' != $file && '..' != $file && ! is_dir($root . $_POST['dir'] . $file)) {
                        $ext = preg_replace('/^.*\./', '', $file);
                        $res .= "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . '">' . htmlentities($file) . '</a></li>';
                    }
                }
                $res .= '</ul>';
            }

            return $res;
        } else {
            return 'Folder does not exist';
        }
    }

    public function getCode(Request $request)
    {
        $path = $request->input('path');
        $file = base_path() . '/resources/views' . $path;
        if (file_exists($file)) {
            return [
                    'path'    => 'resources/views' . $path,
                    'content' => file_get_contents($file),
                ];
        } else {
            return 'error';
        }
    }

    public function postCode(Request $request, $id)
    {
        $content  = $request->input('content_html');
        $filename = base_path() . '/' . $request->input('path');
        if (file_exists($filename)) {
            $fp = fopen($filename, 'w+');
            fwrite($fp, $content);
            fclose($fp);

            return response()->json(['status' => 'success', 'message' => \SiteHelpers::alert('success', 'File has been changed')]);
        // Return return json_encode(array());
        } else {
            return response()->json(['status' => 'error', 'message' => \SiteHelpers::alert('success', 'Error while saving changes')]);
        }
    }

    public function getDuplicate(Request $request, $id)
    {
        $row = \DB::table('tb_module')->where('module_id', $id)
                                ->get();
        if (count($row) <= 0) {
            return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
        }
        $row               = $row[0];
        $this->data['row'] = $row;

        $this->data['module']      = 'module';
        $this->data['module_lang'] = json_decode($row->module_lang, true);
        $this->data['module_name'] = $row->module_name;

        $config = \SiteHelpers::CF_decode_json($row->module_config, true);

//       echo '<pre>'; print_r($config); echo '</pre>';

        $this->data['tables'] = $config['grid'];
        $this->data['type']   = $row->module_type;

        return view('mmb.module.duplicate', $this->data);
    }

    public function postDuplicate(Request $request, $id)
    {
        $rules = [
            'module_name'  => 'required|alpha|min:2|unique:tb_module',
            'module_title' => 'required',
            'module_note'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $row = \DB::table('tb_module')->where('module_id', $id)
                                    ->get();
            if (count($row) <= 0) {
                return Redirect::to('mmb/module')->with('messagetext', 'Can not find module')->with('msgstatus', 'error');
            }
            $row               = $row[0];
            $this->data['row'] = $row;
            $config            = \SiteHelpers::CF_decode_json($row->module_config, true);

            foreach (\DB::select('SHOW COLUMNS FROM tb_module ') as $column) {
                if ('module_id' != $column->Field) {
                    $columns[] = $column->Field;
                }
            }

            $sql = 'INSERT INTO tb_module (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . " FROM tb_module WHERE module_id = '" . $id . "'";
            \DB::select($sql);

            $res = \DB::select('select * from tb_module order by module_id desc limit 1');
            if (count($res) >= 1) {
                $row = $res[0];
                // echo $row->module_id ; exit;
                $data = [
                    'module_title'  => trim($request->module_title),
                    'module_name'   => trim($request->module_name),
                    'module_note'   => trim($request->module_note),
                    'module_author' => \Session::get('fid'),
                ];
                \DB::table('tb_module')->where('module_id', $row->module_id)->update($data);

                // Add Default permission
                $tasks = [
                'is_global' => 'Global',
                'is_view'   => 'View ',
                'is_detail' => 'Detail',
                'is_add'    => 'Add ',
                'is_clone'  => 'Clone ',
                'is_edit'   => 'Edit ',
                'is_remove' => 'Remove ',
                'is_excel'  => 'Export ',
                ];
                $groups = \DB::table('tb_groups')->get();
                $rows   = \DB::table('tb_module')->where('module_id', $row->module_id)->get();
                if (count($rows) >= 1) {
                    $id = $rows[0];

                    foreach ($groups as $g) {
                        $arr = [];
                        foreach ($tasks as $t => $v) {
                            if ('1' == $g->group_id) {
                                $arr[$t] = '1';
                            } else {
                                $arr[$t] = '0';
                            }
                        }
                        $data = [
                            'access_data' => json_encode($arr),
                            'module_id'   => $id->module_id,
                            'group_id'    => $g->group_id,
                        ];
                        \DB::table('tb_groups_access')->insert($data);
                    }
                }

                return Redirect::to('mmb/module/rebuild/' . $row->module_id . '?mode=duplicate');
            } else {
                return Redirect::to('mmb/module')->with('messagetext', 'Failed to Duplicate Module !')->with('msgstatus', 'error');
            }
        }
    }

    public function getTemplateName($file)
    {
        if ('addon' == $file or 'core' == $file) {
            return 'native';
        } else {
            return $file;
        }
    }
}
