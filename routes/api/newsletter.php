<?php

Route::group(['middleware' => 'domain'], function () {
    Route::post('subscribe-newsletter', 'NewsletterController@store')->name('subscribe-newsletter');
});
