<?php

            $val .= '
// Start Routes for ' . $row->module_name . " 
Route::get('{$class}','{$controller}@getIndex');
Route::get('{$class}/comboselect','{$controller}@getComboselect');
Route::get('{$class}/export/{any}','{$controller}@getExport');
// -- Post Method --

// End Routes for " . $row->module_name . ' 

                    ';

?>                    