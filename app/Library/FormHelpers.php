<?php

namespace App\Library;

use View;

class FormHelpers
{
    public static function render($ids)
    {
        //$row = $this->model->retrive($id);
        $sql = \DB::table('tb_forms')->where('formID', $ids)->get();
        if (count($sql) <= 0) {
            return ' Form does not exist';
        }

        $row           = $sql[0];
        $configuration = json_decode($row->configuration, true);
        $template      = base_path() . '/resources/views/core/forms/forms/';
        if (file_exists($template . '/form-' . $row->formID . '.blade.php')) {
            return view('core.forms.forms.form-' . $row->formID);
        } else {
            return 'Form does not exist';
        }
    }

    public static function validateForm($forms)
    {
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
                    if (! is_null(Input::file($form['field']))) {
                        if ('image' == $form['option']['upload_type']) {
                            $rules[$form['field']] = 'mimes:jpg,jpeg,png,gif,bmp';
                        } else {
                            if ('1' != $form['option']['image_multiple']) {
                                $rules[$form['field']] = 'mimes:zip,csv,xls,doc,docx,xlsx';
                            }
                        }
                    }
                }
            }
        }

        return $rules;
    }

    public static function validatePost($request, $str)
    {
        $data = [];
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
                    // Handle Text Editor
                    $content      = (isset($_POST[$field]) ? $_POST[$field] : '');
                    $data[$field] = $content;
                } else {
                    // Handle text Input
                    if (isset($_POST[$field])) {
                        $data[$field] = $_POST[$field];
                    }
                    // Handle FILE OR IMAGE Upload
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
                                if (! is_null(Input::file($field))) {
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
                    // Handle Date
                    if ('date' == $f['type']) {
                        $data[$field] = date('Y-m-d', strtotime($request->input($field)));
                    }

                    // Handle Date
                    if ('date_time' == $f['type']) {
                        $data[$field] = date('Y-m-d H:i:s', strtotime($request->input($field)));
                    }

                    // if post is seelct multiple
                    if ('select' == $f['type']) {
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

        /* Added for Compatibility laravel 5.2 */
        $values = [];
        foreach ($data as $key => $val) {
            if ('' != $val) {
                $values[$key] = $val;
            }
        }

        return $values;
    }

    public static function javascriptForms($forms)
    {
        $f = '';
        foreach ($forms as $form) {
            if (0 != $form['view']) {
                if (preg_match('/(select)/', $form['type'])) {
                    if ('external' == $form['option']['opt_type']) {
                        $table  = $form['option']['lookup_table'];
                        $val    = $form['option']['lookup_value'];
                        $key    = $form['option']['lookup_key'];
                        $lookey = '';
                        if ($form['option']['is_dependency']) {
                            $lookey .= $form['option']['lookup_dependency_key'];
                        }
                        $f .= self::createPreCombo($form['field'], $table, $key, $val, $lookey);
                    }
                }
            }
        }

        return $f;
    }

    public static function createPreCombo($field, $table, $key, $val, $lookey = null)
    {
        $parent       = null;
        $parent_field = null;
        if (null != $lookey) {
            $parent       = " parent: '#" . $lookey . "',";
            $parent_field = "&parent={$lookey}:";
        }
        $pre_jCombo = "
		\$(\"#{$field}\").jCombo(\"{!! url('post/comboselect?filter={$table}:{$key}:{$val}') !!}$parent_field\",
		{ " . $parent . " selected_value : '' });
		";

        return $pre_jCombo;
    }
}
