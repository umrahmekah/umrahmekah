<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MmbapiController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct();
        $model       = ucwords($request->input('module', 'mmb'));
        $model       = '\\App\\Models\\' . $model;
        $this->model = new $model();
    }

    public function index(Request $request)
    {
        $check = self::authentication($request);
        if (is_array($check)) {
            return \Response::json($check);
        }

        $class  = ucwords($request->input('module', 'mmb'));
        $config = $this->model->makeInfo($class);

        $tables = $config['config']['grid'];

        $page  = (! is_null($request->input('page')) or 0 != $request->input('page')) ? $request->input('page') : 1;
        $param = ['page' => $page, 'sort' => '', 'order' => 'asc', 'limit' => ''];
        if (! is_null($request->input('limit')) or 0 != $request->input('limit')) {
            $param['limit'] = $request->input('limit');
        }
        if (! is_null($request->input('order'))) {
            $param['order'] = $request->input('order');
        }
        if (! is_null($request->input('sort'))) {
            $param['sort'] = $request->input('sort');
        }

        $results = $this->model->getRows($param);

        $json = [];
        foreach ($results['rows'] as $row) {
            $rows = [];
            foreach ($tables as $table) {
                $rows[$table['field']] = \SiteHelpers::formatRows($row->{$table['field']}, $table, $row);
            }
            $json[] = $rows;
        }

        $jsonData = [
                'total'   => $results['total'],
                'rows'    => $json,
                'control' => $param,
                'key'     => $config['key'],
            ];

        if (! is_null($request->input('option')) && 'true' == $request->input('option')) {
            $label = [];
            foreach ($tables as $table) {
                $label[] = $table['label'];
            }

            $field = [];
            foreach ($tables as $table) {
                $field[] = $table['field'];
            }
            $jsonData['option'] = [
                        'label' => $label,
                        'field' => $field,
                    ];
        }

        return \Response::json($jsonData, 200);
    }

    public function show(Request $request, $id)
    {
        $check = self::authentication($request);
        if (is_array($check)) {
            return \Response::json($check);
        }

        $class    = ucwords($request->input('module', 'mmb'));
        $config   = $this->model->makeInfo($class);
        $tables   = $config['config']['grid'];
        $jsonData = $this->model->getRow($id);

        return \Response::json($jsonData, 200);
    }

    public function store(Request $request)
    {
        $check = self::authentication($request);
        if (is_array($check)) {
            return \Response::json($check);
        }

        $class      = ucwords($request->input('module', 'mmb'));
        $this->info = $this->model->makeInfo($class);
        $data       = $this->validatePost($this->info['table']);
        exit;
        unset($data['entry_by']);
        $id = $this->model->insertRow($data, '');

        return \Response::json(['data' => 'success'], 200);
    }

    public function update(Request $request, $id)
    {
        $check = self::authentication($request);
        if (is_array($check)) {
            return \Response::json($check);
        }

        $class      = ucwords($request->input('module', 'mmb'));
        $this->info = $this->model->makeInfo($class);
        $data       = self::getPost($request->input());

        unset($data['entry_by']);
        $id = $this->model->insertRow($data, $id);

        return \Response::json(['data' => 'success'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $check = self::authentication($request);
        if (is_array($check)) {
            return \Response::json($check);
        }

        $class   = ucwords($request->input('module', 'mmb'));
        $results = $this->model->retrive($id);
        if (is_null($results)) {
            return \Response::json('not found', 404);
        }

        $success = $results->delete();

        if (! $success) {
            return \Response::json('error deleting', 500);
        }

        return \Response::json('success', 200);
    }

    public static function authentication($request)
    {
        if (is_null($request->input('module', 'mmb'))) {
            return [['status' => 'error', 'message' => ' Please Define Module Name to accessed '], 400];
        }

        if (! isset($_SERVER['PHP_AUTH_USER']) && ! isset($_SERVER['PHP_AUTH_PW'])) {
            return [[
                    'error'   => true,
                    'message' => 'Not authenticated',
                    'code'    => 401, ], 401,
                ];
        } else {
            $user = $_SERVER['PHP_AUTH_USER'];
            $key  = $_SERVER['PHP_AUTH_PW'];

            $auth = \DB::table('tb_restapi')
                        ->join('tb_users', 'tb_users.id', '=', 'tb_restapi.apiuser')
                        ->where('apikey', "$key")->where('email', "$user")->get();

            if (count($auth) <= 0) {
                return [[
                        'error'   => true,
                        'message' => 'Invalid authenticated params !',
                        'code'    => 401, ], 401,
                    ];
            } else {
                $row     = $auth[0];
                $modules = explode(',', str_replace(' ', '', $row->modules));
                if (! in_array($request->input('module', 'mmb'), $modules)) {
                    return [[
                            'error'   => true,
                            'message' => 'You Dont Have Authorization Access!',
                            'code'    => 401, ], 401,
                        ];
                }
            }
        }
    }

    public function getPost($request)
    {
        $_POST = $request;
        $str   = $this->info['config']['forms'];
        $data  = [];
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
                        $files = '';
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
                                            if (! is_dir($destinationPath)) {
                                                mkdir($destinationPath);
                                            }
                                            move_uploaded_file($file_tmp, $destinationPath . '/' . $file_name);
                                            $files .= $file_name . ',';
                                        }
                                    }

                                    if ('' != $files) {
                                        $files = substr($files, 0, strlen($files) - 1);
                                    }
                                }
                                $data[$field] = $files;
                            } else {
                                $file            = Input::file($field);
                                $destinationPath = '.' . $f['option']['path_to_upload'];
                                $filename        = $file->getClientOriginalName();
                                $extension       = $file->getClientOriginalExtension(); //if you need extension of the file
                                $rand            = rand(1000, 100000000);
                                $newfilename     = strtotime(date('Y-m-d H:i:s')) . '-' . $rand . '.' . $extension;
                                $uploadSuccess   = $file->move($destinationPath, $newfilename);
                                if ($uploadSuccess) {
                                    $data[$field] = $newfilename;
                                }
                            }
                        } else {
                            if (! is_null(Input::file($field))) {
                                $file            = Input::file($field);
                                $destinationPath = '.' . $f['option']['path_to_upload'];
                                $filename        = $file->getClientOriginalName();
                                $extension       = $file->getClientOriginalExtension(); //if you need extension of the file
                                $rand            = rand(1000, 100000000);
                                $newfilename     = strtotime(date('Y-m-d H:i:s')) . '-' . $rand . '.' . $extension;

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
                            } else {
                                unset($data[$field]);
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

                    // if post is seelct multiple
                    if ('select' == $f['type']) {
                        //echo '<pre>'; print_r( $_POST[$field] ); echo '</pre>';
                        if (isset($f['option']['select_multiple']) && 1 == $f['option']['select_multiple']) {
                            $multival     = (is_array($_POST[$field]) ? implode(',', $_POST[$field]) : $_POST[$field]);
                            $data[$field] = $multival;
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
}
