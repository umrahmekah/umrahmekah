<?php

Route::group(['namespace' => 'TemplateSetting', 'prefix' => 'template-settings', 'as' => 'template-settings.', 'middleware' => 'domain'], function () {
    Route::group(['namespace' => 'BlueOcean', 'prefix' => 'blue-ocean', 'as' => 'blue-ocean.'], function () {
        Route::post('section', 'SectionController@update')->name('section');
        Route::post('section-reset', 'SectionController@reset')->name('section-reset');
    });
});
