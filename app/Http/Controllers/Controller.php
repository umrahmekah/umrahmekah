<?php

namespace App\Http\Controllers;

use App;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Input;
use Redirect;
use Validator;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('ipblocked');

        $driver   = config('database.default');
        $database = config('database.connections');

        $this->db     = $database[$driver]['database'];
        $this->dbuser = $database[$driver]['username'];
        $this->dbpass = $database[$driver]['password'];
        $this->dbhost = $database[$driver]['host'];

        if (true == \Auth::check()) {
            if (! \Session::get('gid')) {
                \Session::put('uid', \Auth::user()->id);
                \Session::put('gid', \Auth::user()->group_id);
                \Session::put('eid', \Auth::user()->email);
                \Session::put('ll', \Auth::user()->last_login);
                \Session::put('fid', \Auth::user()->first_name . ' ' . \Auth::user()->last_name);
                \Session::put('themes', 'mmb-light-blue');
            }
        }

        if (! \Session::get('themes')) {
            \Session::put('themes', 'mmb');
        }

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
        $data = [
                'last_activity' => strtotime(Carbon::now()),
            ];
        \DB::table('tb_users')->where('id', \Session::get('uid'))->update($data);
    }

    public function getComboselect(Request $request)
    {
        if (true == $request->ajax() && true == \Auth::check()) {
            $param  = explode(':', $request->input('filter'));
            $parent = (! is_null($request->input('parent')) ? $request->input('parent') : null);
            $limit  = (! is_null($request->input('limit')) ? $request->input('limit') : null);
            $rows   = $this->model->getComboselect($param, $limit, $parent);
            $items  = [];

            $fields = explode('|', $param[2]);

            foreach ($rows as $row) {
                $value = '';
                foreach ($fields as $item => $val) {
                    if ('' != $val) {
                        $value .= $row->{$val} . ' ';
                    }
                }
                $items[] = [$row->{$param['1']}, $value];
            }

            return json_encode($items);
        } else {
            return json_encode(['OMG' => ' Ops .. Cant access the page !']);
        }
    }

    public function getCombotable(Request $request)
    {
        if (true == Request::ajax() && true == Auth::check()) {
            $rows  = $this->model->getTableList($this->db);
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
        if (true == Request::ajax() && true == Auth::check()) {
            $items = [];
            $table = $request->input('table');
            if ('' != $table) {
                $rows = $this->model->getTableField($request->input('table'));
                foreach ($rows as $row) {
                    $items[] = [$row, $row];
                }
            }

            return json_encode($items);
        } else {
            return json_encode(['OMG' => '  Ops .. Cant access the page !']);
        }
    }

    public function postMultisearch(Request $request)
    {
        $post  = $_POST;
        $items = '';
        foreach ($post as $item => $val):
            if ('' != $_POST[$item] and '_token' != $item and 'md' != $item && 'id' != $item):
                $items .= $item . ':' . trim($val) . '|';
        endif;

        endforeach;

        return Redirect::to($this->module . '?search=' . substr($items, 0, strlen($items) - 1) . '&md=' . Input::get('md'));
    }

    public function buildSearch($map = false)
    {
        $keywords    = '';
        $fields      = '';
        $param       = '';
        $allowsearch = $this->info['config']['forms'];
        foreach ($allowsearch as $as) {
            $arr[$as['field']] = $as;
        }
        $mapping = '';
        if ('' != $_GET['search']) {
            $type = explode('|', $_GET['search']);
            if (count($type) >= 1) {
                foreach ($type as $t) {
                    $keys = explode(':', $t);
                    if (in_array($keys[0], array_keys($arr))):
                            if ('select' == $arr[$keys[0]]['type'] || 'radio' == $arr[$keys[0]]['type']) {
                                $param   .= ' AND ' . $arr[$keys[0]]['alias'] . '.' . $keys[0] . ' ' . self::searchOperation($keys[1]) . " '" . $keys[2] . "' ";
                                $mapping .= $keys[0] . ' ' . self::searchOperation($keys[1]) . ' ' . $keys[2] . '<br />';
                            } else {
                                $operate = self::searchOperation($keys[1]);
                                if ('like' == $operate) {
                                    $param   .= ' AND ' . $arr[$keys[0]]['alias'] . '.' . $keys[0] . " LIKE '%" . $keys[2] . "%%' ";
                                    $mapping .= $keys[0] . ' LIKE ' . $keys[2] . '<br />';
                                } elseif ('is_null' == $operate) {
                                    $param   .= ' AND ' . $arr[$keys[0]]['alias'] . '.' . $keys[0] . ' IS NULL ';
                                    $mapping .= $keys[0] . ' IS NULL <br />';
                                } elseif ('not_null' == $operate) {
                                    $param   .= ' AND ' . $arr[$keys[0]]['alias'] . '.' . $keys[0] . ' IS NOT NULL ';
                                    $mapping .= $keys[0] . ' IS NOT NULL <br />';
                                } elseif ('between' == $operate) {
                                    $param   .= ' AND (' . $arr[$keys[0]]['alias'] . '.' . $keys[0] . " BETWEEN '" . $keys[2] . "' AND '" . $keys[3] . "' ) ";
                                    $mapping .= $keys[0] . ' BETWEEN ' . $keys[2] . ' - ' . $keys[3] . '<br />';
                                } else {
                                    $param   .= ' AND ' . $arr[$keys[0]]['alias'] . '.' . $keys[0] . ' ' . self::searchOperation($keys[1]) . " '" . $keys[2] . "' ";
                                    $mapping .= $keys[0] . ' ' . self::searchOperation($keys[1]) . ' ' . $keys[2] . '<br />';
                                }
                            }
                    endif;
                }
            }
        }

        if (true == $map) {
            return $param = [
                    'param' => $param,
                    'maps'  => '
					<div class="infobox infobox-info fade in" style="font-size:14px;">
						<button data-dismiss="alert" class="close" type="button"> x </button>  
						<b class="text-danger"> Search Result </b> :  <br /> ' . $mapping . '
					</div>
					',
                ];
        } else {
            return $param;
        }
    }

    public function onSearch($params)
    {
        // Used for extracting URL GET search
        $psearch       = explode('|', $params);
        $currentSearch = [];
        foreach ($psearch as $ps) {
            $tosearch = explode(':', $ps);
            if (count($tosearch) >= 2) {
                $currentSearch[$tosearch[0]] = $tosearch[2];
            }
        }

        return $currentSearch;
    }

    public function searchOperation($operate)
    {
        $val = '';
        switch ($operate) {
            case 'equal':
                $val = '=';
                break;
            case 'bigger_equal':
                $val = '>=';
                break;
            case 'smaller_equal':
                $val = '<=';
                break;
            case 'smaller':
                $val = '<';
                break;
            case 'bigger':
                $val = '>';
                break;
            case 'not_null':
                $val = 'not_null';
                break;

            case 'is_null':
                $val = 'is_null';
                break;

            case 'like':
                $val = 'like';
                break;

            case 'between':
                $val = 'between';
                break;

            default:
                $val = '=';
                break;
        }

        return $val;
    }

    public function inputLogs(Request $request, $note = null)
    {
        $data = [
            'module'    => $request->segment(1),
            'task'      => $request->segment(2),
            'user_id'   => Session::get('uid'),
            'ipaddress' => $request->getClientIp(),
            'owner_id'  => CNF_OWNER,
            'note'      => $note,
        ];
        \DB::table('tb_logs')->insert($data);
    }

    public function validateForm($forms = [])
    {
        if (count($forms) <= 0) {
            $forms = $this->info['config']['forms'];
        }

        $rules = [];
        foreach ($forms as $form) {
            if ('' == $form['required'] || '0' != $form['required']) {
                $rules[$form['field']] = 'required';
            } elseif ('alpa' == $form['required']) {
                $rules[$form['field']] = 'required|alpa';
            } elseif ('alpa_num' == $form['required']) {
                $rules[$form['field']] = 'required|alpa_num';
            } elseif ('alpa_dash' == $form['required']) {
                $rules[$form['field']] = 'required|alpa_dash';
            } elseif ('email' == $form['required']) {
                $rules[$form['field']] = 'required|email';
            } elseif ('numeric' == $form['required']) {
                $rules[$form['field']] = 'required|numeric';
            } elseif ('date' == $form['required']) {
                $rules[$form['field']] = 'required|date';
            } elseif ('url' == $form['required']) {
                $rules[$form['field']] = 'required|active_url';
            } else {
                if ('file' == $form['type']) {
                    if ('required' == $form['required']) {
                        $validation = 'required';
                        if ('image' == $form['option']['upload_type']) {
                            $validation = '|mimes:jpg,jpeg,png,gif,bmp';
                        } else {
                            $validation = '|mimes:jpg,jpeg,png,gif,bmp,pdf,zip,csv,xls,doc,docx,xlsx';
                        }

                        if ('1' != $form['option']['image_multiple']) {
                            // IF SINGLE UPLOAD FILE OR IMAGE
                            $rules[$form['field']] = $validation;
                        } else {
                            // IF MULTIPLE UPLOAD FILE OR IMAGE
                            $FilesArray = [];
                            if (count($_FILES[$form['field']]) >= 1) {
                                $nbr = count($_FILES[$form['field']]) - 1;
                                foreach (range(0, $nbr) as $index) {
                                    // $imagesArray['images.' . $index] = 'required|image';
                                    $rules[$form['field'] . '.' . $index] = $validation;
                                }
                            }
                        }
                    } else {
                        $validation = '';
                        if ('image' == $form['option']['upload_type']) {
                            $validation = '|mimes:jpg,jpeg,png,gif,bmp';
                        } else {
                            $validation = '|mimes:jpg,jpeg,png,gif,bmp,pdf,zip,csv,xls,doc,docx,xlsx';
                        }

                        if ('1' != $form['option']['image_multiple']) {
                            // IF SINGLE UPLOAD FILE OR IMAGE
                            $rules[$form['field']] = $validation;
                        } else {
                            // IF MULTIPLE UPLOAD FILE OR IMAGE
                            if (isset($_FILES[$form['field']])) {
                                $FilesArray = [];

                                if (count($_FILES[$form['field']]) >= 1) {
                                    $nbr = count($_FILES[$form['field']]) - 1;
                                    foreach (range(0, $nbr) as $index) {
                                        // $imagesArray['images.' . $index] = 'required|image';
                                        $rules[$form['field'] . '.' . $index] = $validation;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $rules;
    }

    public function validateListError($rules)
    {
        $errMsg = \Lang::get('core.note_error');
        $errMsg .= '<hr /> <ul>';
        foreach ($rules as $key => $val) {
            $errMsg .= '<li>' . $key . ' : ' . $val[0] . '</li>';
        }
        $errMsg .= '</li>';

        return $errMsg;
    }

    public function validatePost($table)
    {
        $request = new Request();
        $str     = $this->info['config']['forms'];
        $data    = [];
        foreach ($str as $f) {
            $field = $f['field'];
            // Update for V5.1.5 issue on Autofilled createOn and updatedOn fields
            if ('createdOn' == $field) {
                $data['createdOn'] = date('Y-m-d H:i:s');
            }
            if ('updatedOn' == $field) {
                $data['updatedOn'] = date('Y-m-d H:i:s');
            }
            if (1 == $f['view']) {
                if ('textarea_editor' == $f['type'] || 'textarea' == $f['type']) {
                    $content      = (isset($_POST[$field]) ? $_POST[$field] : '');
                    $data[$field] = $content;
                } else {
                    if (isset($_POST[$field])) {
                        $data[$field] = $_POST[$field];
                    }
                    // if post is file or image

                    if ('file' == $f['type']) {
                        $files                         = '';
                        $f['option']['path_to_upload'] = $f['option']['path_to_upload'] . CNF_OWNER . '';
                        //dd($f['option']['path_to_upload']);
                        if ('file' == $f['option']['upload_type']) {
                            if (isset($f['option']['image_multiple']) && 1 == $f['option']['image_multiple']) {
                                if (isset($_POST['curr' . $field])) {
                                    $curr = '';
                                    for ($i = 0; $i < count($_POST['curr' . $field]); ++$i) {
                                        $files .= $_POST['curr' . $field][$i] . ',';
                                    }
                                }

                                if (! is_null(Input::file($field))) {
                                    $destinationPath = '.' . $f['option']['path_to_upload'];
                                    foreach ($_FILES[$field]['tmp_name'] as $key => $tmp_name) {
                                        $file_name = $_FILES[$field]['name'][$key];
                                        $file_tmp  = $_FILES[$field]['tmp_name'][$key];
                                        if ('' != $file_name) {
                                            $file        = Input::file($field)[$key];
                                            $filename    = $file->getClientOriginalName();
                                            $extension   = $file->getClientOriginalExtension(); //if you need extension of the file
                                            $rand        = rand(100, 1000000);
                                            $newfilename = $rand . '-' . $filename;
                                            $files .= $newfilename . ',';

                                            if (! is_dir($destinationPath)) {
                                                mkdir($destinationPath);
                                            }
                                            move_uploaded_file($file_tmp, $destinationPath . '/' . $newfilename);
                                        }
                                    }

                                    if ('' != $files) {
                                        $files = substr($files, 0, strlen($files) - 1);
                                    }
                                    $data[$field] = $files;
                                } else {
                                    unset($data[$field]);
                                }
                            } else {
                                if (! is_null(Input::file($field))) {
                                    $file            = Input::file($field);
                                    $destinationPath = '.' . $f['option']['path_to_upload'];
                                    $filename        = $file->getClientOriginalName();
                                    $extension       = $file->getClientOriginalExtension(); //if you need extension of the file
                                    $rand            = rand(100, 1000000);
                                    $newfilename     = $rand . '-' . $filename;
                                    $uploadSuccess   = $file->move($destinationPath, $newfilename);
                                    if ($uploadSuccess) {
                                        $data[$field] = $newfilename;
                                    }
                                }
                            }
                        } else {
                            if (isset($f['option']['image_multiple']) && 1 == $f['option']['image_multiple']) {
                                $files = '';
                                if (isset($_POST['curr' . $field])) {
                                    $curr = '';
                                    for ($i = 0; $i < count($_POST['curr' . $field]); ++$i) {
                                        $files .= $_POST['curr' . $field][$i] . ',';
                                    }
                                }

                                $destinationPath = '.' . $f['option']['path_to_upload'];
                                if (Input::file($f['field'])) {
                                    if (count(Input::file($f['field'])) >= 1) {
                                        $destinationPath = '.' . $f['option']['path_to_upload'];
                                        foreach ($_FILES[$field]['tmp_name'] as $key => $tmp_name) {
                                            $file_name = $_FILES[$field]['name'][$key];
                                            $file_tmp  = $_FILES[$field]['tmp_name'][$key];
                                            if ('' != $file_name) {
                                                //move_uploaded_file($file_tmp,$destinationPath.'/'.$file_name);
                                                //echo  $file_name.'<br />';
                                                $file        = Input::file($field)[$key];
                                                $filename    = $file->getClientOriginalName();
                                                $extension   = $file->getClientOriginalExtension(); //if you need extension of the file
                                                $rand        = rand(100, 1000000);
                                                $newfilename = $rand . '-' . $filename;
                                                $files .= $newfilename . ',';

                                                $uploadSuccess = $file->move($destinationPath, $newfilename);

                                                if ('0' != $f['option']['resize_width'] && '' != $f['option']['resize_width']) {
                                                    if (0 == $f['option']['resize_height']) {
                                                        $f['option']['resize_height'] = $f['option']['resize_width'];
                                                    }
                                                    $orgFile = $destinationPath . '/' . $newfilename;
                                                    \SiteHelpers::cropImage($f['option']['resize_width'], $f['option']['resize_height'], $orgFile, $extension, $orgFile);
                                                }
                                            }
                                        }
                                    }
                                }
                                if ('' != $files) {
                                    $files = substr($files, 0, strlen($files) - 1);
                                }
                                $data[$field] = $files;
                            } else {
                                if (! is_null(Input::file($field))) {
                                    $file            = Input::file($field);
                                    $destinationPath = '.' . $f['option']['path_to_upload'];
                                    $filename        = $file->getClientOriginalName();
                                    $extension       = $file->getClientOriginalExtension(); //if you need extension of the file
                                    $rand            = rand(100, 1000000);
                                    $newfilename     = $rand . '-' . $filename;

                                    $uploadSuccess = $file->move($destinationPath, $newfilename);

                                    if ('0' != $f['option']['resize_width'] && '' != $f['option']['resize_width']) {
                                        if (0 == $f['option']['resize_height']) {
                                            $f['option']['resize_height'] = $f['option']['resize_width'];
                                        }
                                        $orgFile = $destinationPath . '/' . $newfilename;
                                        \SiteHelpers::cropImage($f['option']['resize_width'], $f['option']['resize_height'], $orgFile, $extension, $orgFile);
                                    }

                                    if ($uploadSuccess) {
                                        $data[$field] = $newfilename;
                                    }
                                }
                            }
                        }
                    }

                    // Handle Checkbox input
                    if ('checkbox' == $f['type']) {
                        if (isset($_POST[$field])) {
                            $data[$field] = implode(',', $_POST[$field]);
                        } else {
                            $data[$field] = '0';
                        }
                    }
                    // if post is date
                    if ('date' == $f['type']) {
                        $data[$field] = date('Y-m-d', strtotime($request->input($field)));
                    }

                    // if post is select multiple
                    if ('select' == $f['type']) {
                        //echo '<pre>'; print_r( $_POST[$field] ); echo '</pre>';
                        if (isset($f['option']['select_multiple']) && 1 == $f['option']['select_multiple']) {
                            if (isset($_POST[$field])) {
                                $multival     = (is_array($_POST[$field]) ? implode(',', $_POST[$field]) : $_POST[$field]);
                                $data[$field] = $multival;
                            }
                        } else {
                            $data[$field] = $_POST[$field];
                        }
                    }
                }
            }
        }
        $global = (isset($this->access['is_global']) ? $this->access['is_global'] : 0);

        if (0 == $global) {
            $data['entry_by'] = \Session::get('uid');
        }
        /* Added for Compatibility laravel 5.2 */
        $values = [];
        foreach ($data as $key => $val) {
            if ('' != $val) {
                $values[$key] = $val;
            }
        }

        return $values;
    }

    public function postFilter(Request $request)
    {
        $module = $this->module;
        $sort   = (! is_null($request->input('sort')) ? $request->input('sort') : '');
        $order  = (! is_null($request->input('order')) ? $request->input('order') : '');
        $rows   = (! is_null($request->input('rows')) ? $request->input('rows') : '');
        $md     = (! is_null($request->input('md')) ? $request->input('md') : '');

        $filter = '?';
        if ('' != $sort) {
            $filter .= '&sort=' . $sort;
        }
        if ('' != $order) {
            $filter .= '&order=' . $order;
        }
        if ('' != $rows) {
            $filter .= '&rows=' . $rows;
        }
        if ('' != $md) {
            $filter .= '&md=' . $md;
        }

        return Redirect::to($this->data['pageModule'] . $filter);
    }

    public function injectPaginate()
    {
        $sort   = (isset($_GET['sort']) ? $_GET['sort'] : '');
        $order  = (isset($_GET['order']) ? $_GET['order'] : '');
        $rows   = (isset($_GET['rows']) ? $_GET['rows'] : '');
        $search = (isset($_GET['search']) ? $_GET['search'] : '');

        $appends = [];
        if ('' != $sort) {
            $appends['sort'] = $sort;
        }
        if ('' != $order) {
            $appends['order'] = $order;
        }
        if ('' != $rows) {
            $appends['rows'] = $rows;
        }
        if ('' != $search) {
            $appends['search'] = $search;
        }

        return $appends;
    }

    public function returnUrl()
    {
        $pages  = (isset($_GET['page']) ? $_GET['page'] : '');
        $sort   = (isset($_GET['sort']) ? $_GET['sort'] : '');
        $order  = (isset($_GET['order']) ? $_GET['order'] : '');
        $rows   = (isset($_GET['rows']) ? $_GET['rows'] : '');
        $search = (isset($_GET['search']) ? $_GET['search'] : '');

        $appends = [];
        if ('' != $pages) {
            $appends['page'] = $pages;
        }
        if ('' != $sort) {
            $appends['sort'] = $sort;
        }
        if ('' != $order) {
            $appends['order'] = $order;
        }
        if ('' != $rows) {
            $appends['rows'] = $rows;
        }
        if ('' != $search) {
            $appends['search'] = $search;
        }

        $url = '';
        foreach ($appends as $key => $val) {
            $url .= "&$key=$val";
        }

        return $url;
    }

    public function getRemovecurrentfiles(Request $request)
    {
        $id    = $request->input('id');
        $field = $request->input('field');
        $file  = $request->input('file');
        if (file_exists('./' . $file) && '' != $file) {
            if (unlink('.' . $file)) {
                \DB::table($this->info['table'])->where($this->info['key'], $id)->update([$field => '']);
            }

            return Response::json(['status' => 'success']);
        } else {
            return Response::json(['status' => 'error']);
        }
    }

    public function getRemovefiles(Request $request)
    {
        $files = '.' . $request->input('file');
        if (file_exists($files) && '' != $files) {
            unlink($files);
        }
    }

    public function getSearch($mode = 'native')
    {
        $this->data['tableForm']  = $this->info['config']['forms'];
        $this->data['tableGrid']  = $this->info['config']['grid'];
        $this->data['searchMode'] = $mode;
        $this->data['pageUrl']    = url($this->module);

        return view('mmb.module.utility.search', $this->data);
    }

    public function getDownload(Request $request)
    {
        if (0 == $this->access['is_excel']) {
            return Redirect::to('')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $info = $this->model->makeInfo($this->module);
        // Take param master detail if any
        $filter = (! is_null($request->input('search')) ? $this->buildSearch() : '');
        $params = [
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
        ];

        $results = $this->model->getRows($params);
        $fields  = $info['config']['grid'];
        $rows    = $results['rows'];

        $content = $this->data['pageTitle'];
        $content .= '<table border="1">';
        $content .= '<tr>';
        foreach ($fields as $f) {
            if ('1' == $f['download']) {
                $content .= '<th style="background:#f9f9f9;">' . $f['label'] . '</th>';
            }
        }
        $content .= '</tr>';

        foreach ($rows as $row) {
            $content .= '<tr>';
            foreach ($fields as $f) {
                if ('1' == $f['download']):
                    $conn = (isset($f['conn']) ? $f['conn'] : []);
                $content .= '<td>' . \SiteHelpers::gridDisplay($row->$f['field'], $f['field'], $conn) . '</td>';
                endif;
            }
            $content .= '</tr>';
        }
        $content .= '</table>';

        @header('Content-Type: application/ms-excel');
        @header('Content-Length: ' . strlen($content));
        @header('Content-disposition: inline; filename="' . $title . ' ' . date('d/m/Y') . '.xls"');

        echo $content;
        exit;
    }

    public function getExpotion()
    {
        $this->data['pageUrl'] = url($this->data['pageModule']);

        return view('mmb.module.utility.export', $this->data);
    }

    public function getExport(Request $request, $t = 'excel')
    {
        $info   = $this->model->makeInfo($this->module);
        $filter = '';
        if (! is_null($request->input('search'))) {
            $search                   = $this->buildSearch('maps');
            $filter                   = $search['param'];
            $this->data['search_map'] = $search['maps'];
        }

        $params = [
                    'params' => $filter,
                    'fstart' => $request->input('fstart'),
                    'flimit' => $request->input('flimit'),
        ];

        $results = $this->model->getRows($params);
        $fields  = $info['config']['grid'];
        $rows    = $results['rows'];
        $content = [
                        'fields' => $fields,
                        'rows'   => $rows,
                        'title'  => $this->data['pageTitle'],
                    ];

        if ('word' == $t) {
            return view('mmb.module.utility.word', $content);
        } elseif ('pdf' == $t) {
            $pdf  = App::make('dompdf.wrapper');
            $html = view('mmb.module.utility.pdf', $content)->render();
            $pdf->loadHTML($html)->setPaper('A4', 'landscape');

            return $pdf->stream();
        } elseif ('csv' == $t) {
            return view('mmb.module.utility.csv', $content);
        } elseif ('print' == $t) {
            //return view('mmb.module.utility.print',$content);
            $data['html'] = view('mmb.module.utility.print', $content)->render();

            return view('layouts.blank', $data);
        } else {
            return view('mmb.module.utility.excel', $content);
        }
    }

    public function getLookup(Request $request, $id)
    {
        $args = explode('-', $id);
        if (count($args) >= 2) {
            $model             = '\\App\\Models\\' . ucwords($args['3']);
            $model             = new $model();
            $info              = $model->makeInfo($args['3']);
            $data['pageTitle'] = $info['title'];
            $data['pageNote']  = $info['note'];
            $params            = [
                'params' => ' And ' . $args['4'] . '.' . $args['5'] . " ='" . $args['6'] . "'",
                //'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
            ];
            $results                = $model->getRows($params);
            $data['access']         = $model->validAccess($info['id']);
            $data['rowData']        = $results['rows'];
            $data['tableGrid']      = $info['config']['grid'];
            $data['tableForm']      = $info['config']['forms'];
            $data['colspan']        = \SiteHelpers::viewColSpan($info['config']['grid']);
            $data['nested_subgrid'] = (isset($info['config']['subgrid']) ? $info['config']['subgrid'] : []);
            //print_r($data['nested_subgrid']);exit;
            $data['id']  = $args[6];
            $data['key'] = $info['key'];
            //$data['ids']		= 'md'-$info['id'];
            return view('mmb.module.utility.masterdetail', $data);
        } else {
            return 'Invalid Argument';
        }
    }

    public function detailview($model, $detail, $id)
    {
        $info   = $model->makeInfo($detail['module']);
        $params = [
            'params' => ' And `' . $detail['key'] . "` ='" . $id . "'",
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
        ];
        $results           = $model->getRows($params);
        $data['rowData']   = $results['rows'];
        $data['tableGrid'] = $info['config']['grid'];
        $data['tableForm'] = $info['config']['forms'];

        return $data;
    }

    public function detailviewsave($model, $request, $detail, $id)
    {
        //\DB::table($detail['table'])->where($detail['key'],$request[$detail['key']])->delete();

        $info         = $model->makeInfo($detail['module']);
        $relation_key = $info['key'];
        $access       = $model->validAccess($info['id']);

        if ('1' == $access['is_add'] && '1' == $access['is_edit']) {
            $str               = $info['config']['forms'];
            $global            = (isset($access['is_global']) ? $access['is_global'] : 0);
            $total             = count($request['counter']);
            $mkeys             = [];
            $getAllCurrentData = \DB::table($detail['table'])->where($detail['master_key'], $id)->get();

            $pkeys = [];
            for ($i = 0; $i < $total; ++$i) {
                $pkeys[] = $request['bulk_' . $relation_key][$i];
            }

            foreach ($getAllCurrentData as $keys) {
                if (! in_array($keys->{$relation_key}, $pkeys)) {
                    // Remove If items is not resubmited
                    \DB::table($detail['table'])->where($relation_key, $keys->{$relation_key})->delete();
                }
            }

            for ($i = 0; $i < $total; ++$i) {
                $data = [];
                foreach ($str as $f) {
                    $field = $f['field'];
                    if (1 == $f['view']) {
                        if (isset($request['bulk_' . $field][$i]) && '' != $request['bulk_' . $field][$i]) {
                            $data[$f['field']] = $request['bulk_' . $field][$i];
                        }
                    }
                }

                $rules     = self::validateForm($str);
                $validator = Validator::make($data, $rules);
                if ($validator->passes()) {
                    $data[$detail['key']] = $id;
                    if (0 == $global) {
                        $data['entry_by'] = \Session::get('uid');
                    }

                    // Check if data currentry exist
                    $check = \DB::table($detail['table'])->where($relation_key, $request['bulk_' . $relation_key][$i])->get();
                    if (count($check) >= 1) {
                        \DB::table($detail['table'])->where($relation_key, $request['bulk_' . $relation_key][$i])->update($data);
                    } else {
                        unset($data[$relation_key]);
                        \DB::table($detail['table'])->insert($data);
                    }
                }
            }
        }
    }
}
