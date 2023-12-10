<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'domain'], function () {
    $active_multilang = defined('CNF_MULTILANG') ? CNF_LANG : 'en';
    \App::setLocale($active_multilang);
    if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
        $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
        \App::setLocale($lang);
    }

    Route::get('/', 'HomeController@index');
    Route::controller('home', 'HomeController');
    Route::controller('blog', 'PostController');
    Route::controller('post', 'PostController');
    Route::controller('report', 'ReportController');

    //Analytic
    Route::controller('/analytic', 'AnalyticController');
    
    Route::post('/tourdates/TdDiscount/{id}', 'TourdatesController@postTdDiscount');
    
    //Task
    Route::controller('tasks', 'TasksController');
    // Route::get('/tasks', 'TasksController@getIndex');
    // Route::get('/tasks/show/{id}', 'TasksController@getShow');
    // Route::get('/tasks/create', 'TasksController@getCreate');
    // Route::post('/tasks/update/{id}', 'TasksController@update');
    // Route::post('/tasks/edit/{id}', 'TasksController@edit');
    // Route::post('/tasks/delete/{id}', 'TasksController@destroy');
    // Route::post('/tasks/store', 'TasksController@store');
    // Route::post('/tasks/completeStatus/{id}', 'TasksController@completeStatus');
    // Route::post('/tasks/cancelStatus/{id}', 'TasksController@cancelStatus');
    // Route::get('/tasks/massDelete/', 'TasksController@massDelete');
    
    //Tours
    
    Route::get('package', 'ToursController@display');
    Route::get('package-bound', 'TourboundController@display');
    Route::get('tnc', 'ToursController@tnc');

    //Route::get('bookPackage/{id}', 'PackageBookingController@bookPackage');
    Route::get('booknow', 'PackageBookingController@bookPackage');
    Route::get('booknowsimple', 'PackageBookingController@simpleBooking');
    Route::post('booknowsimple', 'PackageBookingController@simpleSubmit');
    Route::post('/simplebooking/callback', 'PackageBookingController@simpleCallback');
    Route::get('/simplebooking/paid', 'PackageBookingController@simpleRedirect');
    Route::post('bookPackage/ajaxsession', 'PackageBookingController@postAjaxsession');
    Route::post('/bookPackage/setSession', 'PackageBookingController@storeSessionData');
    Route::post('/bookpackage/checkcredential', 'PackageBookingController@checkCredential');
    Route::post('/bookpackage/checklogin', 'PackageBookingController@checkLogin');
    Route::get('/bookPackage/getSession', 'PackageBookingController@accessSessionData');
    Route::get('country_name/{id}', 'PackageBookingController@countryName');
    Route::get('/reg/new', 'PackageBookingController@registerNewUser');
    Route::get('/bookpackage/summary', 'PackageBookingController@bookPackageSummary');
    Route::get('/bookpackage/deposit', 'PackageBookingController@payDeposit');
    Route::get('/bookpackage/paid', 'PackageBookingController@successPay');
    Route::get('/bookpackage/bookingpay', 'PackageBookingController@bookingpay');
    Route::post('/bookpackage/payment-flag', 'PackageBookingController@postPaymentFlag');
    Route::get('/bookpackage/payment-method', 'PackageBookingController@paymentMethod')->name('bookpackage.paymentmethods');
    Route::post('/bookpackage/payment-method-process', 'PackageBookingController@paymentMethodProcess')->name('bookpackage.paymentmethodprocess');
    Route::get('/bookpackage/payment-complete', 'PackageBookingController@bayarindSuccessPay');
    Route::get('/bookpackage/simple-payment-method', 'PackageBookingController@simplePaymentMethod')->name('bookpackage.simplepaymentmethods');
    Route::post('/bookpackage/simple-payment-method-process', 'PackageBookingController@simplePaymentMethodProcess')->name('bookpackage.simplepaymentmethodprocess');
    Route::get('/bookpackage/payment-time-out', 'PackageBookingController@getBayarindRTO');
    Route::post('/bookpackage/complete-sales', 'PackageBookingController@postCompleteSales');
    Route::post('/bookpackage/receive-request-action', 'PackageBookingController@postReceiveRequestAction');

    Route::post('saveTravellerInfo', 'PackageBookingController@postsaveTravellerInfo');

    Route::controller('/user', 'UserController');

    Route::get('/restric', 'RestrictController@show');

    Route::resource('mmbapi', 'MmbapiController');

    Route::post('/summary/billplz', 'PackageBookingController@summaryBillplz');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/pendingdepo', 'PendingDepositController@getIndex');
        Route::post('/pendingdepo/delete', 'PendingDepositController@postDelete');
        Route::get('/pendingdepo/view/{id}', 'PendingDepositController@getShow');
        Route::get('/pendingdepo/pay/deposit/{id}', 'PendingDepositController@payDeposit');

        // Route::get('core/elfinder', 'Core\ElfinderController@getIndex');
        // Route::post('core/elfinder', 'Core\ElfinderController@getIndex');
        Route::controller('/dashboard', 'DashboardController');
        //Route::resource('post', 'DashboardController@show');
        
        Route::controllers([
            'core/users'             => 'Core\UsersController',
            'notification'           => 'NotificationController',
            'core/logs'              => 'Core\LogsController',
            'core/banners'           => 'Core\BannersController',
            'core/pages'             => 'Core\PagesController',
            'core/groups'            => 'Core\GroupsController',
            'core/template'          => 'Core\TemplateController',
            'core/template-settings' => 'Core\TemplateSettingController',
            'core/posts'             => 'Core\PostsController',
            'core/forms'             => 'Core\FormsController',
            'core/credit'            => 'Core\CreditController',
        ]);

        Route::post(
            'settings/blue-ocean/section', 
            'TemplateSetting\BlueOcean\SectionController@update'
        )->name('blue-ocean.setting.section');
    });

    Route::group(['middleware' => 'auth', 'middleware' => 'mmbauth'], function () {
        Route::controllers([
            'core/menu'   => 'Mmb\MenuController',
            'core/config' => 'Mmb\ConfigController',
            //'mmb/module' 		=> 'Mmb\ModuleController',
            'core/tables' => 'Mmb\TablesController',
            'core/code'   => 'Mmb\CodeController',
            'core/rac'    => 'Mmb\RacController',
        ]);
    });

    // Route::get('/superuseronly/ramadhanblast', function () {
    //     $user = \Auth::user();

    //     if (!$user || $user->email !== 'superadmin@oomrah.com') {
    //         return redirect(url('/'));
    //     }

    //     $owners = App\Models\Owners::get();

    //     foreach ($owners as $key => $owner) {
    //         $travellers = App\Models\Travellers::where('owner_id', $owner->id)->get();
    //         foreach ($travellers as $key => $traveller) {
    //             if ($traveller->email) {
    //                 \Mail::send('emails.ramadhan', ['owner' => $owner], function ($message) use ($traveller, $owner) {
    //                     $message->to($traveller->email, $traveller->fullname)->subject('Selamat Menyambut Ramadhan Al-Mubarak');
    //                     $message->from($owner->email, $owner->name);
    //                 });
    //             }
                
    //         }
    //     }

    //     return redirect(url('/'));
    // });
});
