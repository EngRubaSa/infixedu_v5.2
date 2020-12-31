<?php

use App\SmEmailSetting;
use Illuminate\Support\Facades\DB;
use MacsiDigital\Zoom\Facades\Zoom;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;

Route::get('addColumn', function () {
    $newColumnType = 'string';
    $newColumnName = 'file_size';
 
    return $s = DB::table('sm_general_settings')->update(['file_size'=>'99999']);   

    Schema::table('sm_general_settings', function (Blueprint $table) use ($newColumnType, $newColumnName) {
        $table->$newColumnType($newColumnName);
    });
});

Route::get('testMail', function () {
    try{ 

        $settings = SmEmailSetting::first();
        $reciver_email = "spn11@spondonit.com";
        $receiver_name =  $settings->from_name;
        $subject= "New Student Registration0";
        $view ="parentregistration::new_reg_email";
        $compact['compact'] = array('email' => "spn11@spondonit.com", 'id' => 600, 'slug' => 'student');
        send_mail($reciver_email, $receiver_name, $subject , $view ,$compact);
     
} catch (\Exception $e) {
     dd($e);
  
}
});

Route::get('sendmail', function () {
    return Zoom::user()->where('status', 'active')->get()->toArray();
});
Route::get('/academic_years','HomeController@academicUpdate');
Route::get('/class_updates','HomeController@classUpdate');
Route::get('/section_updates','HomeController@sectionUpdate');
Route::get('/class_section_updates','HomeController@sectionClassUpdate');
Route::get('/new_updates','HomeController@classSectionAllUpdate');
Route::get('/db_update_new','HomeController@dbUpdate');
Route::get('/student_update','HomeController@studentUpdate');
Route::get('/class_update_new','HomeController@classUpdateNew');


if (Config::get('app.app_sync')) {
    Route::get('/', 'LandingController@index');
    // Route::get('/',  'SmFrontendController@index');
} else {
    Route::get('/',  'SmFrontendController@index');
}



Route::group(['middleware' => ['XSS']], function () {
    Route::get('update-system', 'SmSystemSettingController@UpdateSystem');
    Route::get('/verified-code', 'InstallController@verifiedCode');
    Route::post('system-verify', 'InstallController@systemVerifyPurchases')->name('systemVerifyPurchases');
    Route::get('module-verify', 'InstallController@ModuleVerify')->name('ModuleVerify');
    Route::post('module-verify-purchase', 'InstallController@ModuleverifyPurchases')->name('ModuleverifyPurchases');
    Route::get('institution-privacy-policy', 'SmFrontendController@institutionPrivacyPolicy')->name('institution-privacy-policy');
    Route::get('institution-terms-service', 'SmFrontendController@institutionTermServices')->name('institution-terms-service');
    Route::get('/',  'SmFrontendController@index')->name('/');
    // if (Config::get('app.app_sync')) {
    //     Route::get('/', 'LandingController@index');
    //     // Route::get('/',  'SmFrontendController@index');
    // } else {
    //     Route::get('/',  'SmFrontendController@index');
    // }

    Route::get('db-upgrade',  'SmSystemSettingController@DbUpgrade');
    Route::get('academicIdUpdated',  'SmSystemSettingController@academicIdUpdated');
    

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('home',  'SmFrontendController@index');
    Route::get('about', 'SmFrontendController@about');
    Route::get('course', 'SmFrontendController@course');
    Route::get('course-Details/{id}', 'SmFrontendController@courseDetails')->where('id', '[0-9]+');
    Route::get('news-page', 'SmFrontendController@newsPage');
    Route::get('news-details/{id}', 'SmFrontendController@newsDetails')->where('id', '[0-9]+');
    Route::get('contact', 'SmFrontendController@contact');

    //USER REGISTER SECTION
    Route::get('register', 'SmFrontendController@register')->name('register');
    Route::post('register', 'SmFrontendController@customer_register')->name('customer_register');


    Route::get('error-404', function () {
        return view('auth.error');
    })->name('error-404');
    Route::get('notification-api', 'SmSystemSettingController@notificationApi')->name('notificationApi');
    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | contains the "web" middleware group. Now create something great!
    |
     */


    /* ::::::::::::::::::::::::: START SEARCH ROUTES :::::::::::::::::::::::::: */

    Route::get('moduleAddOnsEnable/{name}', 'SmAddOnsController@moduleAddOnsEnable')->name('moduleAddOnsEnable');
    Route::post('/search', 'SmSearchController@search')->name('search');

    /* ::::::::::::::::::::::::: START SEARCH ROUTES :::::::::::::::::::::::::: */


    Route::group(['middleware' => ['CheckUserMiddleware']], function () {

        Route::get('login', 'Auth\LoginController@loginFormTwo')->name('login');  // for demo version
        //Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');   //for codecanoyon

        Route::post('login', 'Auth\LoginController@login')->name('login');

        //forget password
        Route::get('recovery/passord', 'SmAuthController@recoveryPassord')->name('recoveryPassord');

        Route::post('email/verify', 'SmAuthController@emailVerify')->name('email/verify');

        Route::get('/reset/password/{email}/{code}', 'SmAuthController@resetEmailConfirtmation')->name('resetEmailConfirtmation');

        Route::post('/store/new/password', 'SmAuthController@storeNewPassword')->name('storeNewPassword');

        Route::get('login-2', 'Auth\LoginController@loginFormTwo')->name('loginFormTwo');

        Route::get('news', 'SmSystemSettingController@news')->name('news')->middleware('userRolePermission:795');
    });

    Route::get('/after-login', 'HomeController@dashboard');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('ajax-get-login-access', 'SmAuthController@getLoginAccess');

    // Auth::routes();

    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');

    Route::get('/after-login', 'HomeController@dashboard');

    // Route::get('/dashboard', 'HomeController@dashboard');


    Route::get('view/single/notification/{id}', 'SmNotificationController@viewSingleNotification')->where('id', '[0-9]+');

    Route::get('view/all/notification/{id}', 'SmNotificationController@viewAllNotification')->name('view/all/notification')->where('id', '[0-9]+');
    Route::get('notification-show/{id}', 'SmNotificationController@udpateNotification')->name('notification-show');

    Route::get('view/notice/{id}', 'HomeController@viewNotice')->where('id', '[0-9]+')->where('id', '[0-9]+')->name('view-notice');
    // update password

    Route::get('change-password', 'HomeController@updatePassowrd')->name('updatePassowrd');
    Route::post('admin-change-password', 'HomeController@updatePassowrdStore')->name('updatePassowrdStore'); //InfixPro Version
    // Route::post('change-password', 'HomeController@updatePassowrdStore'); //InfixPro Version
    // Route::post('change-password', 'HomeController@dashboard'); //InfixPro Version
    Route::get('download-uploaded-content/{id}/{student_id}', 'Student\SmStudentPanelController@downloadHomeWorkContent')->name('downloadHomeWorkContent');

    Route::get('class-routine/print/{class_id}/{section_id}',  'SmClassRoutineNewController@classRoutinePrint')->name('classRoutinePrint');



    Route::post('/pay-with-paystack', 'Student\SmFeesController@redirectToGateway')->name('pay-with-paystack');

    Route::get('/payment/callback', 'Student\SmFeesController@handleGatewayCallback')->name('handleGatewayCallback');

    //customer panel

    Route::group(['middleware' => ['CustomerMiddleware']], function () {
        Route::get('customer-dashboard', ['as' => 'customer_dashboard', 'uses' => 'Customer\SmCustomerPanelController@customerDashboard']);
        Route::get('customer-purchases', 'Customer\SmCustomerPanelController@customerPurchases');
    });
    Route::get('student-transport-view-modal/{r_id}/{v_id}', ['as' => 'student_transport_view_modal', 'uses' => 'Student\SmStudentPanelController@studentTransportViewModal']);




    //Install for Demo
    Route::post('/verified-code', 'InstallController@verifiedCodeStore')->name('verifiedCodeStore');


    Route::get('install', 'InstallController@index');
    Route::get('system-config', 'InstallController@verify')->name('system.config');
    Route::get('Moduleverify/{name}', 'InstallController@Moduleverify')->name('Moduleverify');
    Route::get('check-purchase-verification', 'InstallController@CheckPurchaseVerificationPage');
    Route::post('check-verified-input', 'InstallController@CheckVerifiedInput');
    Route::get('check-environment', 'InstallController@checkEnvironmentPage');
    Route::any('checking-environment', 'InstallController@checkEnvironment');
    Route::get('system-setup-page', 'InstallController@systemSetupPage');
    Route::post('confirm-installing', 'InstallController@confirmInstalling');
    Route::get('confirmation', 'InstallController@confirmation');



    //for localization
    Route::get('locale/{locale}', 'SmSystemSettingController@changeLocale');
    Route::get('change-language/{id}', 'SmSystemSettingController@changeLanguage')->name('change-language');


    /************* Verify Routes *************/
    Route::get('verify/', 'VerifyController@index');
    Route::put('/verify/storePurchasecode/{id}', 'VerifyController@storePurchasecode');
    Route::put('/verify/storePurchasecode/{id}', 'VerifyController@storePurchasecode');


    /************* Front End Settings *************/
    Route::get('/news', 'SmNewsController@index')->name('news_index');
    Route::post('/news-store', 'SmNewsController@store')->name('store_news')->middleware('userRolePermission:497');
    Route::post('/news-update', 'SmNewsController@update')->name('update_news')->middleware('userRolePermission:498');
    Route::get('newsDetails/{id}', 'SmNewsController@newsDetails')->name('newsDetails')->middleware('userRolePermission:496');
    Route::get('for-delete-news/{id}', 'SmNewsController@forDeleteNews')->name('for-delete-news')->middleware('userRolePermission:499');
    Route::get('delete-news/{id}', 'SmNewsController@delete')->name('delete-news');
    Route::get('edit-news/{id}', 'SmNewsController@edit')->name('edit-news')->middleware('userRolePermission:498');


    Route::get('news-category', 'SmNewsController@newsCategory')->name('news-category')->middleware('userRolePermission:500');
    Route::post('/news-category-store', 'SmNewsController@storeCategory')->name('store_news_category')->middleware('userRolePermission:501');
    Route::post('/news-category-update', 'SmNewsController@updateCategory')->name('update_news_category')->middleware('userRolePermission:502');
    Route::get('for-delete-news-category/{id}', 'SmNewsController@forDeleteNewsCategory')->name('for-delete-news-category')->middleware('userRolePermission:503');
    Route::get('delete-news-category/{id}', 'SmNewsController@deleteCategory')->name('delete-news-category');
    Route::get('edit-news-category/{id}', 'SmNewsController@editCategory')->name('edit-news-category')->middleware('userRolePermission:502');


    //For course module
    Route::get('course-list', 'SmCourseController@index')->name('course-list')->middleware('userRolePermission:509');
    Route::post('/course-store', 'SmCourseController@store')->name('store_course')->middleware('userRolePermission:511');
    Route::post('/course-update', 'SmCourseController@update')->name('update_course')->middleware('userRolePermission:512');
    Route::get('for-delete-course/{id}', 'SmCourseController@forDeleteCourse')->name('for-delete-course')->middleware('userRolePermission:513');
    Route::get('delete-course/{id}', 'SmCourseController@destroy')->name('delete-course')->middleware('userRolePermission:509');
    Route::get('edit-course/{id}', 'SmCourseController@edit')->name('edit-course')->middleware('userRolePermission:512');
    Route::get('course-Details-admin/{id}', 'SmCourseController@courseDetails')->name('course-Details-admin')->middleware('userRolePermission:510');


    //for testimonial

    Route::get('/testimonial', 'SmTestimonialController@index')->name('testimonial_index')->middleware('userRolePermission:504');
    Route::post('/testimonial-store', 'SmTestimonialController@store')->name('store_testimonial')->middleware('userRolePermission:506');
    Route::post('/testimonial-update', 'SmTestimonialController@update')->name('update_testimonial')->middleware('userRolePermission:507');
    Route::get('testimonial-details/{id}', 'SmTestimonialController@testimonialDetails')->name('testimonial-details')->middleware('userRolePermission:505');
    Route::get('for-delete-testimonial/{id}', 'SmTestimonialController@forDeleteTestimonial')->name('for-delete-testimonial')->middleware('userRolePermission:508');
    Route::get('delete-testimonial/{id}', 'SmTestimonialController@delete')->name('delete-testimonial');
    Route::get('edit-testimonial/{id}', 'SmTestimonialController@edit')->name('edit-testimonial')->middleware('userRolePermission:507');


    // Contact us
    Route::get('contact-page', 'SmFrontendController@conpactPage')->name('conpactPage')->middleware('userRolePermission:514');
    Route::get('contact-page/edit', 'SmFrontendController@contactPageEdit')->name('contactPageEdit');
    Route::post('contact-page/update', 'SmFrontendController@contactPageStore')->name('contactPageStore');

    // contact message
    Route::get('contact-message', 'SmFrontendController@contactMessage')->middleware('userRolePermission:517')->name('contactMessage');;


    // News route start
    Route::get('news-heading-update', 'SmFrontendController@newsHeading')->name('news-heading-update')->middleware('userRolePermission:523');
    Route::post('news-heading-update', 'SmFrontendController@newsHeadingUpdate')->name('news-heading-update')->middleware('userRolePermission:524');


    // Course route start
    Route::get('course-heading-update', 'SmFrontendController@courseHeading')->name('course-heading-update')->middleware('userRolePermission:525');
    Route::post('course-heading-update', 'SmFrontendController@courseHeadingUpdate')->name('course-heading-update')->middleware('userRolePermission:526');


    Route::get('about-page', 'SmFrontendController@aboutPage')->name('about-page')->middleware('userRolePermission:520');
    Route::get('about-page/edit', 'SmFrontendController@aboutPageEdit')->name('about-page/edit');
    Route::post('about-page/update', 'SmFrontendController@aboutPageStore')->name('about-page/update');

    Route::post('send-message', 'SmFrontendController@sendMessage');


    Route::get('custom-links', 'SmSystemSettingController@customLinks')->name('custom-links')->middleware('userRolePermission:527');
    Route::post('custom-links-update', 'SmSystemSettingController@customLinksUpdate')->name('custom-links-update')->middleware('userRolePermission:528');


    // admin-home-page
    Route::get('admin-home-page', 'SmSystemSettingController@homePageBackend')->name('admin-home-page')->middleware('userRolePermission:493');
    Route::post('admin-home-page-update', 'SmSystemSettingController@homePageUpdate')->name('admin-home-page-update')->middleware('userRolePermission:494');


    // social media
    Route::get('social-media', 'SmFrontendController@socialMedia')->name('social-media')->middleware('userRolePermission:529');
    Route::post('social-media-store', 'SmFrontendController@socialMediaStore')->name('social-media-store');
    Route::get('social-media-edit/{id}', 'SmFrontendController@socialMediaEdit')->name('social-media-edit');
    Route::post('social-media-update', 'SmFrontendController@socialMediaUpdate')->name('social-media-update');
    Route::get('social-media-delete/{id}', 'SmFrontendController@socialMediaDelete')->name('social-media-delete');


    // admin-home-page
    Route::get('admin-data-delete', 'SmSystemSettingController@tableEmpty');
    Route::post('database-delete', 'SmSystemSettingController@databaseDelete')->name('database-delete');
    Route::get('database-restore', 'SmSystemSettingController@databaseRestory');
    Route::post('database-restore', 'SmSystemSettingController@databaseRestory');

    Route::get('change-website-btn-status', 'SmSystemSettingController@changeWebsiteBtnStatus');
    Route::get('change-dashboard-btn-status', 'SmSystemSettingController@changeDashboardBtnStatus');
    Route::get('change-report-btn-status', 'SmSystemSettingController@changeReportBtnStatus');

    Route::get('change-style-btn-status', 'SmSystemSettingController@changeStyleBtnStatus');
    Route::get('change-ltl_rtl-btn-status', 'SmSystemSettingController@changeLtlRtlBtnStatus');
    Route::get('change-language-btn-status', 'SmSystemSettingController@changeLanguageBtnStatus');
    Route::post('update-website-url', 'SmSystemSettingController@updateWebsiteUrl')->name('update-website-url')->middleware('userRolePermission:464');


    Route::get('update-created-date', 'SmSystemSettingController@updateCreatedDate');


    // manage currency

    Route::get('manage-currency', 'SmSystemSettingController@manageCurrency')->name('manage-currency')->middleware('userRolePermission:401');
    Route::post('currency-store', 'SmSystemSettingController@storeCurrency')->name('currency-store')->middleware('userRolePermission:402');
    Route::post('currency-update', 'SmSystemSettingController@storeCurrencyUpdate')->name('currency-update')->middleware('userRolePermission:403');
    Route::get('manage-currency/edit/{id}', 'SmSystemSettingController@manageCurrencyEdit')->name('currency_edit')->middleware('userRolePermission:403');
    Route::get('manage-currency/delete/{id}', 'SmSystemSettingController@manageCurrencyDelete')->name('currency_delete')->middleware('userRolePermission:404');
    Route::get('system-destroyed-by-authorized', 'SmSystemSettingController@systemDestroyedByAuthorized')->name('systemDestroyedByAuthorized');

    Route::post('student-update-pic/{id}', ['as' => 'student_update_pic', 'uses' => 'SmStudentAdmissionController@studentUpdatePic']);
    Route::post('student-document-delete', ['as' => 'student_document_delete', 'uses' => 'SmStudentAdmissionController@deleteStudentDocument']);
});