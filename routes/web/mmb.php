<?php

//-------------------------------------------------------------------------
/* Start Module Routes */
Route::group(['middleware' => 'domain'], function () {
    Route::group(['namespace' => 'Mmb', 'middleware' => 'auth'], function () {
        Route::get('mmb/module', 'ModuleController@getindex');
        Route::get('mmb/module/create', 'ModuleController@getCreate');
        Route::get('mmb/module/rebuild/{any}', 'ModuleController@getRebuild');
        Route::get('mmb/module/build/{any}', 'ModuleController@getBuild');
        Route::get('mmb/module/config/{any}', 'ModuleController@getConfig');
        Route::get('mmb/module/sql/{any}', 'ModuleController@getSql');
        Route::get('mmb/module/json/{any}', 'ModuleController@getJson');
        Route::get('mmb/module/table/{any}', 'ModuleController@getTable');
        Route::get('mmb/module/form/{any}', 'ModuleController@getForm');
        Route::get('mmb/module/formdesign/{any}', 'ModuleController@getFormdesign');
        Route::get('mmb/module/subform/{any}', 'ModuleController@getSubform');
        Route::get('mmb/module/subformremove/{any}', 'ModuleController@getSubformremove');
        Route::get('mmb/module/sub/{any}', 'ModuleController@getSub');
        Route::get('mmb/module/removesub', 'ModuleController@getRemovesub');
        Route::get('mmb/module/permission/{any}', 'ModuleController@getPermission');
        Route::get('mmb/module/source/{any}', 'ModuleController@getSource');
        Route::get('mmb/module/combotable', 'ModuleController@getCombotable');
        Route::get('mmb/module/combotablefield', 'ModuleController@getCombotablefield');
        Route::get('mmb/module/editform/{any?}', 'ModuleController@getEditform');
        Route::get('mmb/module/destroy/{any?}', 'ModuleController@getDestroy');
        Route::get('mmb/module/conn/{any?}', 'ModuleController@getConn');
        Route::get('mmb/module/code/{any?}', 'ModuleController@getCode');
        Route::get('mmb/module/duplicate/{any?}', 'ModuleController@getDuplicate');

        /* POST METHODE */
        Route::post('mmb/module/create', 'ModuleController@postCreate');
        Route::post('mmb/module/saveconfig/{any}', 'ModuleController@postSaveconfig');
        Route::post('mmb/module/savesetting/{any}', 'ModuleController@postSavesetting');
        Route::post('mmb/module/savesql/{any}', 'ModuleController@postSavesql');
        Route::post('mmb/module/savejson/{any}', 'ModuleController@postSavejson');
        Route::post('mmb/module/savetable/{any}', 'ModuleController@postSavetable');
        Route::post('mmb/module/saveform/{any}', 'ModuleController@postSaveForm');
        Route::post('mmb/module/savesubform/{any}', 'ModuleController@postSavesubform');
        Route::post('mmb/module/formdesign/{any}', 'ModuleController@postFormdesign');
        Route::post('mmb/module/savepermission/{any}', 'ModuleController@postSavePermission');
        Route::post('mmb/module/savesub/{any}', 'ModuleController@postSaveSub');
        Route::post('mmb/module/dobuild/{any}', 'ModuleController@postDobuild');
        Route::post('mmb/module/source/{any}', 'ModuleController@postSource');
        Route::post('mmb/module/install', 'ModuleController@postInstall');
        Route::post('mmb/module/package', 'ModuleController@postPackage');
        Route::post('mmb/module/dopackage', 'ModuleController@postDopackage');
        Route::post('mmb/module/saveformfield/{any?}', 'ModuleController@postSaveformfield');
        Route::post('mmb/module/conn/{any?}', 'ModuleController@postConn');
        Route::post('mmb/module/code/{any?}', 'ModuleController@postCode');
        Route::post('mmb/module/duplicate/{any?}', 'ModuleController@postDuplicate');

        /* End  Module Routes */
        //-------------------------------------------------------------------------

        /* Start  Code Routes */
        Route::get('mmb/code', 'CodeController@index');
        Route::get('mmb/code/edit', 'CodeController@getEdit');
        Route::post('mmb/code/source/{any?}', 'CodeController@PostSource');
        Route::post('mmb/code/save', 'CodeController@PostSave');

        Route::get('mmb/config/email', 'ConfigController@getEmail');
        Route::get('mmb/config/security', 'ConfigController@getSecurity');
        Route::post('mmb/code/source/:any', 'ConfigController@postSource');
        /* End  Code Routes */

        //-------------------------------------------------------------------------
        /* Start  Config Routes */
        Route::get('mmb/config', 'ConfigController@getIndex');
        Route::get('mmb/config/email', 'ConfigController@getEmail');
        Route::get('mmb/config/security', 'ConfigController@getSecurity');
        Route::get('mmb/config/translation', 'ConfigController@getTranslation');
        Route::get('mmb/config/log', 'ConfigController@getLog');
        Route::get('mmb/config/clearlog', 'ConfigController@getClearlog');
        Route::get('mmb/config/addtranslation', 'ConfigController@getAddtranslation');
        Route::get('mmb/config/removetranslation/{any}', 'ConfigController@getRemovetranslation');
        // POST METHOD
        Route::post('mmb/config/save', 'ConfigController@postSave');
        Route::post('mmb/config/email', 'ConfigController@postEmail');
        Route::post('mmb/config/login', 'ConfigController@postLogin');
        Route::post('mmb/config/email', 'ConfigController@postEmail');
        Route::post('mmb/config/addtranslation', 'ConfigController@postAddtranslation');
        Route::post('mmb/config/savetranslation', 'ConfigController@postSavetranslation');
        /* End  Config Routes */

        //-------------------------------------------------------------------------
        /* Start  Menu Routes */
        Route::get('mmb/menu/', 'MenuController@getIndex');
        Route::get('mmb/menu/index/{any?}', 'MenuController@getIndex');
        Route::get('mmb/menu/destroy/{any?}', 'MenuController@getDestroy');
        Route::get('mmb/menu/icon', 'MenuController@getIcons');

        Route::post('mmb/menu/save', 'MenuController@postSave');
        Route::post('mmb/menu/saveorder', 'MenuController@postSaveorder');
        /* End  Config Routes */

        //-------------------------------------------------------------------------
        /* Start  Tables Routes */
        Route::get('mmb/tables', 'TablesController@getIndex');
        Route::get('mmb/tables/tableconfig/{any}', 'TablesController@getTableconfig');
        Route::get('mmb/tables/mysqleditor', 'TablesController@getMysqleditor');
        Route::get('mmb/tables/tableconfig', 'TablesController@getTableconfig');
        Route::get('mmb/tables/tablefieldedit/{any}', 'TablesController@getTablefieldedit');
        Route::get('mmb/tables/tablefieldremove/{id?}/{id2?}', 'TablesController@getTablefieldremove');
        // POST METHOD
        Route::post('mmb/tables/tableremove', 'TablesController@postTableremove');
        Route::post('mmb/tables/tableinfo/{any}', 'TablesController@postTableinfo');
        Route::post('mmb/tables/mysqleditor', 'TablesController@postMysqleditor');
        Route::post('mmb/tables/tablefieldsave/{any?}', 'TablesController@postTablefieldsave');
        Route::post('mmb/tables/tables', 'TablesController@postTables');
        /* End  Tables Routes */

        //-------------------------------------------------------------------------
        /* Start Logs Routes */
        // -- Get Method --
        Route::get('mmb/rac', 'RacController@getIndex');
        Route::get('mmb/rac/show/{any}', 'RacController@getShow');
        Route::get('mmb/rac/update/{any?}', 'RacController@getUpdate');
        Route::get('mmb/rac/comboselect', 'RacController@getComboselect');
        Route::get('mmb/rac/download', 'RacController@getDownload');
        Route::get('mmb/rac/search', 'RacController@getSearch');

        // -- Post Method --
        Route::post('mmb/rac/save', 'RacController@postSave');
        Route::post('mmb/rac/filter', 'RacController@postFilter');
        Route::post('mmb/rac/delete/{any?}', 'RacController@postDelete');
        /* End  Tables Routes */
    });
});
