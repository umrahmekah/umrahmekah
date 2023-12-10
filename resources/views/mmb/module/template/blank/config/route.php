<?php

            $val .= '
// Start Routes for ' . $row->module_name . " 
Route::get('{$class}','{$controller}@getIndex');
Route::get('{$class}/show/{any?}','{$controller}@getShow');
Route::get('{$class}/update/{any?}','{$controller}@getUpdate');
Route::get('{$class}/comboselect','{$controller}@getComboselect');
Route::get('{$class}/download','{$controller}@getDownload');
Route::get('{$class}/search/{any?}','{$controller}@getSearch');
Route::get('{$class}/export/{any?}','{$controller}@getExport');
Route::get('{$class}/expotion','{$controller}@getExpotion');
Route::get('{$class}/lookup/{id?}/{id2?}','{$controller}@getLookup');
Route::get('{$class}/data','{$controller}@postData');
Route::get('{$class}/import','{$controller}@getImport');
// -- Post Method --
Route::post('{$class}/data','{$controller}@postData');
Route::post('{$class}/save/{any?}','{$controller}@postSave');
Route::post('{$class}/copy','{$controller}@postCopy');
Route::post('{$class}/filter','{$controller}@postFilter');
Route::post('{$class}/delete/{any?}','{$controller}@postDelete');
Route::post('{$class}/savepublic','{$controller}@postSavepublic');
Route::post('{$class}/import','{$controller}@postImport');
// End Routes for " . $row->module_name . ' 

                    ';

?>                    