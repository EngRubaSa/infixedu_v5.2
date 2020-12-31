<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::get('checkForeignKey', 'HomeController@checkForeignKey')->name('checkForeignKey');

//ADMIN
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('reg', function () {

    return view('auth.register');
});

Route::group(['middleware' => ['XSS','subscriptionAccessUrl']], function () {

    // User Auth Routes
    Route::group(['middleware' => ['CheckDashboardMiddleware']], function () {

        Route::get('staff-download-timeline-doc/{file_name}', function ($file_name = null) {
            // return "Timeline";
            $file = public_path() . '/uploads/student/timeline/' . $file_name;
            // echo $file;
            // exit();
            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        })->name('staff-download-timeline-doc');

        Route::get('download-holiday-document/{file_name}', function ($file_name = null) {
            // return "Timeline";
            $file = public_path() . '/uploads/holidays/' . $file_name;

            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        })->name('download-holiday-document');


        /* ******************* Dashboard Setting ***************************** */
        Route::get('dashboard/display-setting', 'SmSystemSettingController@displaySetting');
        Route::post('dashboard/display-setting-update', 'SmSystemSettingController@displaySettingUpdate');


        /* ******************* Dashboard Setting ***************************** */
        Route::get('api/permission', 'SmSystemSettingController@apiPermission')->name('api/permission')->middleware('userRolePermission:482');
        Route::get('api-permission-update', 'SmSystemSettingController@apiPermissionUpdate');
        /* ******************* Dashboard Setting ***************************** */

        Route::get('delete-student-document/{id}', ['as' => 'delete-student-document', 'uses' => 'SmStudentAdmissionController@deleteDocument']);


        Route::view('/admin-setup', 'frontEnd.admin_setup');
        Route::view('/general-setting', 'frontEnd.general_setting');
        Route::view('/student-id', 'frontEnd.student_id');
        Route::view('/add-homework', 'frontEnd.add_homework');
        // Route::view('/fees-collection-invoice', 'frontEnd.fees_collection_invoice');
        Route::view('/exam-promotion-naim', 'frontEnd.exam_promotion');
        Route::view('/front-cms-gallery', 'frontEnd.front_cms_gallery');
        Route::view('/front-cms-media-manager', 'frontEnd.front_cms_media_manager');
        Route::view('/reports-class', 'frontEnd.reports_class');
        Route::view('/human-resource-payroll-generate', 'frontEnd.human_resource_payroll_generate');
        // Route::view('/fees-collection-collect-fees', 'frontEnd.fees_collection_collect_fees');
        Route::view('/calendar', 'frontEnd.calendar');
        Route::view('/design', 'frontEnd.design');
        Route::view('/loginn', 'frontEnd.login');
        Route::view('/dash-board/super-admin', 'frontEnd.dashBoard.super_admin');
        Route::view('/admit-card-report', 'frontEnd.admit_card_report');
        Route::view('/reports-terminal-report2', 'frontEnd.reports_terminal_report');
        // Route::view('/reports-tabulation-sheet', 'frontEnd.reports_tabulation_sheet');
        Route::view('/system-settings-sms', 'frontEnd.system_settings_sms');
        Route::view('/front-cms-setting', 'frontEnd.front_cms_setting');
        Route::view('/base_setup_naim', 'frontEnd.base_setup');
        Route::view('/dark-home', 'frontEnd.home.dark_home');
        Route::view('/dark-about', 'frontEnd.home.dark_about');
        Route::view('/dark-news', 'frontEnd.home.dark_news');
        Route::view('/dark-news-details', 'frontEnd.home.dark_news_details');
        Route::view('/dark-course', 'frontEnd.home.dark_course');
        Route::view('/dark-course-details', 'frontEnd.home.dark_course_details');
        Route::view('/dark-department', 'frontEnd.home.dark_department');
        Route::view('/dark-contact', 'frontEnd.home.dark_contact');
        Route::view('/light-home', 'frontEnd.home.light_home');
        Route::view('/light-about', 'frontEnd.home.light_about');
        Route::view('/light-news', 'frontEnd.home.light_news');
        Route::view('/light-news-details', 'frontEnd.home.light_news_details');
        Route::view('/light-course', 'frontEnd.home.light_course');
        Route::view('/light-course-details', 'frontEnd.home.light_course_details');
        Route::view('/light-department', 'frontEnd.home.light_department');
        Route::view('/light-contact', 'frontEnd.home.light_contact');
        Route::view('/color-home', 'frontEnd.home.color_home');
        Route::view('/id-card', 'frontEnd.home.id_card');


        Route::get('/dashboard', 'HomeController@index')->name('dashboard');
        Route::get('add-toDo', 'HomeController@addToDo');
        Route::post('saveToDoData', 'HomeController@saveToDoData')->name('saveToDoData');
        Route::get('view-toDo/{id}', 'HomeController@viewToDo')->where('id', '[0-9]+');
        Route::get('edit-toDo/{id}', 'HomeController@editToDo')->where('id', '[0-9]+');
        Route::post('update-to-do', 'HomeController@updateToDo');
        Route::get('remove-to-do', 'HomeController@removeToDo');
        Route::get('get-to-do-list', 'HomeController@getToDoList');

        Route::get('admin-dashboard', 'HomeController@index')->name('admin-dashboard');

        //Role Setup
        Route::get('role', ['as' => 'role', 'uses' => 'RoleController@index']);
        Route::post('role-store', ['as' => 'role_store', 'uses' => 'RoleController@store']);
        Route::get('role-edit/{id}', ['as' => 'role_edit', 'uses' => 'RoleController@edit'])->where('id', '[0-9]+');
        Route::post('role-update', ['as' => 'role_update', 'uses' => 'RoleController@update']);
        Route::post('role-delete', ['as' => 'role_delete', 'uses' => 'RoleController@delete']);


        // Role Permission
        Route::get('assign-permission/{id}', ['as' => 'assign_permission', 'uses' => 'SmRolePermissionController@assignPermission']);
        Route::post('role-permission-store', ['as' => 'role_permission_store', 'uses' => 'SmRolePermissionController@rolePermissionStore']);


        // Module Permission

        Route::get('module-permission', 'RoleController@modulePermission')->name('module-permission');


        Route::get('assign-module-permission/{id}', 'RoleController@assignModulePermission')->name('assign-module-permission');
        Route::post('module-permission-store', 'RoleController@assignModulePermissionStore')->name('module-permission-store');


        //User Route
        Route::get('user', ['as' => 'user', 'uses' => 'UserController@index']);
        Route::get('user-create', ['as' => 'user_create', 'uses' => 'UserController@create']);

        // Base group
        Route::get('base-group', ['as' => 'base_group', 'uses' => 'SmBaseGroupController@index']);
        Route::post('base-group-store', ['as' => 'base_group_store', 'uses' => 'SmBaseGroupController@store']);
        Route::get('base-group-edit/{id}', ['as' => 'base_group_edit', 'uses' => 'SmBaseGroupController@edit']);
        Route::post('base-group-update', ['as' => 'base_group_update', 'uses' => 'SmBaseGroupController@update']);
        Route::get('base-group-delete/{id}', ['as' => 'base_group_delete', 'uses' => 'SmBaseGroupController@delete']);

        // Base setup
        Route::get('base-setup', ['as' => 'base_setup', 'uses' => 'SmBaseSetupController@index'])->middleware('userRolePermission:428');
        Route::post('base-setup-store', ['as' => 'base_setup_store', 'uses' => 'SmBaseSetupController@store'])->middleware('userRolePermission:429');
        Route::get('base-setup-edit/{id}', ['as' => 'base_setup_edit', 'uses' => 'SmBaseSetupController@edit'])->middleware('userRolePermission:430');
        Route::post('base-setup-update', ['as' => 'base_setup_update', 'uses' => 'SmBaseSetupController@update'])->middleware('userRolePermission:430');
        Route::post('base-setup-delete', ['as' => 'base_setup_delete', 'uses' => 'SmBaseSetupController@delete'])->middleware('userRolePermission:431');

        //// Academics Routing

        // Class route
        Route::get('class', ['as' => 'class', 'uses' => 'SmClassController@index'])->middleware('userRolePermission:261');
        Route::post('class-store', ['as' => 'class_store', 'uses' => 'SmClassController@store'])->middleware('userRolePermission:266');
        Route::get('class-edit/{id}', ['as' => 'class_edit', 'uses' => 'SmClassController@edit'])->middleware('userRolePermission:263');
        Route::post('class-update', ['as' => 'class_update', 'uses' => 'SmClassController@update'])->middleware('userRolePermission:263');
        Route::get('class-delete/{id}', ['as' => 'class_delete', 'uses' => 'SmClassController@delete'])->middleware('userRolePermission:264');


        //*********************************************** START SUBJECT WISE ATTENDANCE ****************************************************** */
        Route::get('subject-wise-attendance',  'SmSubjectAttendanceController@index')->name('subject-wise-attendance')->middleware('userRolePermission:533');
        Route::post('subject-attendance-search',  'SmSubjectAttendanceController@search')->name('subject-attendance-search');
        Route::post('subject-attendance-store',  'SmSubjectAttendanceController@storeAttendance')->name('subject-attendance-store')->middleware('userRolePermission:69');


        // Student Attendance Report
        Route::get('subject-attendance-report', 'SmSubjectAttendanceController@subjectAttendanceReport')->name('subject-attendance-report')->middleware('userRolePermission:535');
        Route::post('subject-attendance-report-search', 'SmSubjectAttendanceController@subjectAttendanceReportSearch')->name('subject-attendance-report-search');
        Route::get('subject-attendance-report-search', 'SmSubjectAttendanceController@subjectAttendanceReport');
        // Route::get('subject-attendance-report/print/{class_id}/{section_id}/{month}/{year}', 'SmSubjectAttendanceController@subjectAttendanceReportPrint');
        Route::get('subject-attendance/print/{class_id}/{section_id}/{subject_id}/{month}/{year}', 'SmSubjectAttendanceController@subjectAttendanceReportPrint')->name('subject-attendance/print')->middleware('userRolePermission:536');
        //*********************************************** END SUBJECT WISE ATTENDANCE ****************************************************** */



        // Student Attendance Report
        Route::get('student-attendance-report', ['as' => 'student_attendance_report', 'uses' => 'SmStudentAdmissionController@studentAttendanceReport'])->middleware('userRolePermission:70');
        Route::post('student-attendance-report-search', ['as' => 'student_attendance_report_search', 'uses' => 'SmStudentAdmissionController@studentAttendanceReportSearch']);
        Route::get('student-attendance-report-search', 'SmStudentAdmissionController@studentAttendanceReport');
        Route::get('student-attendance/print/{class_id}/{section_id}/{month}/{year}', 'SmStudentAdmissionController@studentAttendanceReportPrint')->name('student-attendance-print');


        //Class Section routes
        Route::get('optional-subject',  'SmOptionalSubjectAssignController@index')->name('optional-subject')->middleware('userRolePermission:537');

        Route::post('assign-optional-subject',  'SmOptionalSubjectAssignController@assignOptionalSubjectSearch')->name('assign_optional_subject_search');
        Route::post('assign-optional-subject-search',  'SmOptionalSubjectAssignController@assignOptionalSubject');
        Route::post('assign-optional-subject-store',  'SmOptionalSubjectAssignController@assignOptionalSubjectStore')->name('assign-optional-subject-store')->middleware('userRolePermission:251');


        Route::get('section', ['as' => 'section', 'uses' => 'SmSectionController@index'])->middleware('userRolePermission:265');

        Route::post('section-store', ['as' => 'section_store', 'uses' => 'SmSectionController@store'])->middleware('userRolePermission:566');
        Route::get('section-edit/{id}', ['as' => 'section_edit', 'uses' => 'SmSectionController@edit'])->middleware('userRolePermission:567');
        Route::post('section-update', ['as' => 'section_update', 'uses' => 'SmSectionController@update'])->middleware('userRolePermission:567');
        Route::get('section-delete/{id}', ['as' => 'section_delete', 'uses' => 'SmSectionController@delete'])->middleware('userRolePermission:568');

        // Subject routes
        Route::get('subject', ['as' => 'subject', 'uses' => 'SmSubjectController@index'])->middleware('userRolePermission:257');
        Route::post('subject-store', ['as' => 'subject_store', 'uses' => 'SmSubjectController@store'])->middleware('userRolePermission:258');
        Route::get('subject-edit/{id}', ['as' => 'subject_edit', 'uses' => 'SmSubjectController@edit'])->middleware('userRolePermission:259');
        Route::post('subject-update', ['as' => 'subject_update', 'uses' => 'SmSubjectController@update'])->middleware('userRolePermission:259');
        Route::get('subject-delete/{id}', ['as' => 'subject_delete', 'uses' => 'SmSubjectController@delete'])->middleware('userRolePermission:260');

        //Class Routine
        // Route::get('class-routine', ['as' => 'class_routine', 'uses' => 'SmAcademicsController@classRoutine']);
        // Route::get('class-routine-create', ['as' => 'class_routine_create', 'uses' => 'SmAcademicsController@classRoutineCreate']);
        Route::get('ajaxSelectSubject', 'SmAcademicsController@ajaxSelectSubject');
        Route::get('ajaxSelectCurrency', 'SmSystemSettingController@ajaxSelectCurrency');

        // Route::post('assign-routine-search', 'SmAcademicsController@assignRoutineSearch');
        // Route::get('assign-routine-search', 'SmAcademicsController@classRoutine');
        // Route::post('assign-routine-store', 'SmAcademicsController@assignRoutineStore');
        // Route::post('class-routine-report-search', 'SmAcademicsController@classRoutineReportSearch');
        // Route::get('class-routine-report-search', 'SmAcademicsController@classRoutineReportSearch');


        // class routine new

        Route::get('class-routine-new', ['as' => 'class_routine_new', 'uses' => 'SmClassRoutineNewController@classRoutine'])->middleware('userRolePermission:246');



        Route::post('class-routine-new', 'SmClassRoutineNewController@classRoutineSearch')->name('class_routine_new');
        Route::get('add-new-routine/{class_time_id}/{day}/{class_id}/{section_id}', 'SmClassRoutineNewController@addNewClassRoutine')->name('add-new-routine')->middleware('userRolePermission:247');

        Route::post('add-new-class-routine-store', 'SmClassRoutineNewController@addNewClassRoutineStore')->name('add-new-class-routine-store');


        Route::get('get-class-teacher-ajax', 'SmClassRoutineNewController@getClassTeacherAjax');
        Route::get('add-new-class-routine-store', 'SmClassRoutineNewController@classRoutineSearch');

        Route::get('edit-class-routine/{class_time_id}/{day}/{class_id}/{section_id}/{subject_id}/{room_id}/{assigned_id}/{teacher_id}', 'SmClassRoutineNewController@addNewClassRoutineEdit')->name('edit-class-routine')->middleware('userRolePermission:248');

        Route::get('delete-class-routine-modal/{id}', 'SmClassRoutineNewController@deleteClassRoutineModal')->name('delete-class-routine-modal')->middleware('userRolePermission:249');
        Route::get('delete-class-routine/{id}', 'SmClassRoutineNewController@deleteClassRoutine')->name('delete-class-routine')->middleware('userRolePermission:249');
        Route::get('class-routine-new/{class_id}/{section_id}', 'SmClassRoutineNewController@classRoutineRedirect');

        //Student Panel

        Route::get('view-teacher-routine', 'teacher\SmAcademicsController@viewTeacherRoutine')->name('view-teacher-routine');

        //assign subject
        Route::get('assign-subject', ['as' => 'assign_subject', 'uses' => 'AcademicController@assignSubject'])->middleware('userRolePermission:250');

        Route::get('assign-subject-create', ['as' => 'assign_subject_create', 'uses' => 'AcademicController@assigSubjectCreate'])->middleware('userRolePermission:251');

        Route::post('assign-subject-search', ['as' => 'assign_subject_search', 'uses' => 'AcademicController@assignSubjectSearch']);
        Route::get('assign-subject-search', 'AcademicController@assigSubjectCreate');
        Route::post('assign-subject-store', 'AcademicController@assignSubjectStore')->name('assign-subject-store')->middleware('userRolePermission:251');
        Route::get('assign-subject-store', 'AcademicController@assigSubjectCreate');
        Route::post('assign-subject', 'AcademicController@assignSubjectFind')->name('assign-subject');
        Route::get('assign-subject-get-by-ajax', 'AcademicController@assignSubjectAjax');

        //Assign Class Teacher
        // Route::resource('assign-class-teacher', 'SmAssignClassTeacherControler')->middleware('userRolePermission:253');
        Route::get('assign-class-teacher', 'SmAssignClassTeacherControler@index')->name('assign-class-teacher')->middleware('userRolePermission:253');
        Route::post('assign-class-teacher', 'SmAssignClassTeacherControler@store')->name('assign-class-teacher')->middleware('userRolePermission:254');
        Route::get('assign-class-teacher/{id}', 'SmAssignClassTeacherControler@edit')->name('assign-class-teacher-edit')->middleware('userRolePermission:255');
        Route::put('assign-class-teacher/{id}', 'SmAssignClassTeacherControler@update')->name('assign-class-teacher-update')->middleware('userRolePermission:255');
        Route::delete('assign-class-teacher/{id}', 'SmAssignClassTeacherControler@destroy')->name('assign-class-teacher-delete')->middleware('userRolePermission:256');
        // Class room
        // Route::resource('class-room', 'SmClassRoomController')->middleware('userRolePermission:269');
        Route::get('class-room', 'SmClassRoomController@index')->name('class-room')->middleware('userRolePermission:269');
        Route::post('class-room', 'SmClassRoomController@store')->name('class-room')->middleware('userRolePermission:270');
        Route::get('class-room/{id}', 'SmClassRoomController@edit')->name('class-room-edit')->middleware('userRolePermission:271');
        Route::put('class-room/{id}', 'SmClassRoomController@update')->name('class-room-update')->middleware('userRolePermission:271');
        Route::delete('class-room/{id}', 'SmClassRoomController@destroy')->name('class-room-delete')->middleware('userRolePermission:272');

        // Route::resource('class-time', 'SmClassTimeController')->middleware('userRolePermission:273');
        Route::get('class-time', 'SmClassTimeController@index')->name('class-time')->middleware('userRolePermission:273');
        Route::post('class-time', 'SmClassTimeController@store')->name('class-time')->middleware('userRolePermission:274');
        Route::get('class-time/{id}', 'SmClassTimeController@edit')->name('class-time-edit')->middleware('userRolePermission:275');
        Route::put('class-time/{id}', 'SmClassTimeController@update')->name('class-time-update')->middleware('userRolePermission:275');
        Route::delete('class-time/{id}', 'SmClassTimeController@destroy')->name('class-time-delete')->middleware('userRolePermission:276');
        
        Route::get('exam-time', 'SmClassTimeController@examTime')->name('exam-time')->middleware('userRolePermission:208');
        Route::post('exam-timeSave', 'SmClassTimeController@examtimeSave')->name('examtimeSave')->middleware('userRolePermission:274');
        Route::get('exam-time/{id}/edit', 'SmClassTimeController@examTimeEdit')->name('examTimeEdit')->middleware('userRolePermission:275');
        Route::put('exam-time/{id}/', 'SmClassTimeController@examTimeUpdate')->name('examTimeUpdate')->middleware('userRolePermission:275');


        //Admission Query
        Route::get('admission-query', ['as' => 'admission_query', 'uses' => 'SmAdmissionQueryController@index'])->middleware('userRolePermission:12');

        Route::post('admission-query-store-a', ['as' => 'admission_query_store_a', 'uses' => 'SmAdmissionQueryController@admissionQueryStore']);

        Route::get('admission-query-edit/{id}', ['as' => 'admission_query_edit', 'uses' => 'SmAdmissionQueryController@admissionQueryEdit'])->middleware('userRolePermission:14');
        Route::post('admission-query-update', ['as' => 'admission_query_update', 'uses' => 'SmAdmissionQueryController@admissionQueryUpdate']);
        Route::get('add-query/{id}', ['as' => 'add_query', 'uses' => 'SmAdmissionQueryController@addQuery'])->middleware('userRolePermission:13');
        Route::post('query-followup-store', ['as' => 'query_followup_store', 'uses' => 'SmAdmissionQueryController@queryFollowupStore']);
        Route::get('delete-follow-up/{id}', ['as' => 'delete_follow_up', 'uses' => 'SmAdmissionQueryController@deleteFollowUp']);
        Route::post('admission-query-delete', ['as' => 'admission_query_delete', 'uses' => 'SmAdmissionQueryController@admissionQueryDelete'])->middleware('userRolePermission:15');

        Route::post('admission-query-search', 'SmAdmissionQueryController@admissionQuerySearch')->name('admission-query-search');
        Route::get('admission-query-search', 'SmAdmissionQueryController@index');

        // Visitor routes

        Route::get('visitor', ['as' => 'visitor', 'uses' => 'SmVisitorController@index'])->middleware('userRolePermission:16');
        Route::post('visitor-store', ['as' => 'visitor_store', 'uses' => 'SmVisitorController@store'])->middleware('userRolePermission:17');
        Route::get('visitor-edit/{id}', ['as' => 'visitor_edit', 'uses' => 'SmVisitorController@edit'])->middleware('userRolePermission:18');
        Route::post('visitor-update', ['as' => 'visitor_update', 'uses' => 'SmVisitorController@update'])->middleware('userRolePermission:18');
        Route::get('visitor-delete/{id}', ['as' => 'visitor_delete', 'uses' => 'SmVisitorController@delete'])->middleware('userRolePermission:19');
        Route::get('download-visitor-document/{file_name}', ['as' => 'visitor_download', 'uses' => 'SmVisitorController@download_files'])->middleware('userRolePermission:20');

        // Route::get('download-visitor-document/{file_name}', function ($file_name = null) {

        //     $file = public_path() . '/uploads/visitor/' . $file_name;
        //     if (file_exists($file)) {
        //         return Response::download($file);
        //     }
        // });

        // Fees Group routes
        Route::get('fees-group', ['as' => 'fees_group', 'uses' => 'SmFeesGroupController@index'])->middleware('userRolePermission:123');
        Route::post('fees-group-store', ['as' => 'fees_group_store', 'uses' => 'SmFeesGroupController@store'])->middleware('userRolePermission:124');
        Route::get('fees-group-edit/{id}', ['as' => 'fees_group_edit', 'uses' => 'SmFeesGroupController@edit'])->middleware('userRolePermission:125');
        Route::post('fees-group-update', ['as' => 'fees_group_update', 'uses' => 'SmFeesGroupController@update'])->middleware('userRolePermission:125');
        Route::post('fees-group-delete', ['as' => 'fees_group_delete', 'uses' => 'SmFeesGroupController@deleteGroup'])->middleware('userRolePermission:126');

        // Fees type routes
        Route::get('fees-type', ['as' => 'fees_type', 'uses' => 'SmFeesTypeController@index'])->middleware('userRolePermission:127');
        Route::post('fees-type-store', ['as' => 'fees_type_store', 'uses' => 'SmFeesTypeController@store'])->middleware('userRolePermission:128');
        Route::get('fees-type-edit/{id}', ['as' => 'fees_type_edit', 'uses' => 'SmFeesTypeController@edit'])->middleware('userRolePermission:129');
        Route::post('fees-type-update', ['as' => 'fees_type_update', 'uses' => 'SmFeesTypeController@update'])->middleware('userRolePermission:129');
        Route::get('fees-type-delete/{id}', ['as' => 'fees_type_delete', 'uses' => 'SmFeesTypeController@delete'])->middleware('userRolePermission:130');

        // Fees Discount routes
        Route::get('fees-discount', ['as' => 'fees_discount', 'uses' => 'SmFeesDiscountController@index'])->middleware('userRolePermission:131');
        Route::post('fees-discount-store', ['as' => 'fees_discount_store', 'uses' => 'SmFeesDiscountController@store'])->middleware('userRolePermission:132');
        Route::get('fees-discount-edit/{id}', ['as' => 'fees_discount_edit', 'uses' => 'SmFeesDiscountController@edit'])->middleware('userRolePermission:133');
        Route::post('fees-discount-update', ['as' => 'fees_discount_update', 'uses' => 'SmFeesDiscountController@update'])->middleware('userRolePermission:133');
        Route::get('fees-discount-delete/{id}', ['as' => 'fees_discount_delete', 'uses' => 'SmFeesDiscountController@delete'])->middleware('userRolePermission:134');
        Route::get('fees-discount-assign/{id}', ['as' => 'fees_discount_assign', 'uses' => 'SmFeesDiscountController@feesDiscountAssign'])->middleware('userRolePermission:135');
        Route::post('fees-discount-assign-search', 'SmFeesDiscountController@feesDiscountAssignSearch')->name('fees-discount-assign-search');
        Route::get('fees-discount-assign-store', 'SmFeesDiscountController@feesDiscountAssignStore');

        Route::get('fees-generate-modal/{amount}/{student_id}/{type}/{master}', 'SmFeesController@feesGenerateModal')->name('fees-generate-modal')->middleware('userRolePermission:111');
        Route::get('fees-discount-amount-search', 'SmFeesDiscountController@feesDiscountAmountSearch');
        // delete fees payment
        Route::post('fees-payment-delete', 'SmFeesController@feesPaymentDelete')->name('fees-payment-delete');

        // Fees carry forward
        Route::get('fees-forward', ['as' => 'fees_forward', 'uses' => 'SmFeesController@feesForward']);
        Route::post('fees-forward-search', 'SmFeesController@feesForwardSearch')->name('fees-forward-search');
        Route::get('fees-forward-search', 'SmFeesController@feesForward');

        Route::post('fees-forward-store', 'SmFeesController@feesForwardStore')->name('fees-forward-store');
        Route::get('fees-forward-store', 'SmFeesController@feesForward');

        //fees payment store
        Route::post('fees-payment-store', 'SmFeesController@feesPaymentStore')->name('fees-payment-store');

         Route::get('bank-slip-view/{file_name}', function ($file_name = null) {

            $file = public_path() . '/uploads/bankSlip/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('bank-slip-view');

        // Collect Fees
        Route::get('collect-fees', ['as' => 'collect_fees', 'uses' => 'SmFeesController@collectFees'])->middleware('userRolePermission:109');
        Route::get('fees-collect-student-wise/{id}', ['as' => 'fees_collect_student_wise', 'uses' => 'SmFeesController@collectFeesStudent'])->where('id', '[0-9]+')->middleware('userRolePermission:110');

        Route::post('collect-fees', ['as' => 'collect_fees', 'uses' => 'SmFeesController@collectFeesSearch']);


        // fees print
        Route::get('fees-payment-print/{id}/{group}', ['as' => 'fees_payment_print', 'uses' => 'SmFeesController@feesPaymentPrint']);

        Route::get('fees-payment-invoice-print/{id}/{group}', ['as' => 'fees_payment_invoice_print', 'uses' => 'SmFeesController@feesPaymentInvoicePrint']);

        Route::get('fees-group-print/{id}', ['as' => 'fees_group_print', 'uses' => 'SmFeesController@feesGroupPrint'])->where('id', '[0-9]+');

        Route::get('fees-groups-print/{id}/{s_id}', 'SmFeesController@feesGroupsPrint');

        //Search Fees Payment
        Route::get('search-fees-payment', ['as' => 'search_fees_payment', 'uses' => 'SmFeesController@searchFeesPayment'])->middleware('userRolePermission:113');
        Route::post('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'SmFeesController@feesPaymentSearch']);
        Route::get('fees-payment-search', ['as' => 'fees_payment_search', 'uses' => 'SmFeesController@searchFeesPayment']);

        //Fees Search due
        Route::get('search-fees-due', ['as' => 'search_fees_due', 'uses' => 'SmFeesController@searchFeesDue'])->middleware('userRolePermission:116');
        Route::post('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'SmFeesController@feesDueSearch']);
        Route::get('fees-due-search', ['as' => 'fees_due_search', 'uses' => 'SmFeesController@searchFeesDue']);


        Route::post('send-dues-fees-email', 'SmFeesController@sendDuesFeesEmail')->name('send-dues-fees-email');

        // fees bank slip approve
        Route::get('bank-payment-slip', 'SmFeesController@bankPaymentSlip')->name('bank-payment-slip');
        Route::post('bank-payment-slip', 'SmFeesController@bankPaymentSlipSearch')->name('bank-payment-slip');
        Route::post('approve-fees-payment', 'SmFeesController@approveFeesPayment')->name('approve-fees-payment');



        //Fees Statement
        Route::get('fees-statement', ['as' => 'fees_statement', 'uses' => 'SmFeesController@feesStatemnt'])->middleware('userRolePermission:381');
        Route::post('fees-statement-search', ['as' => 'fees_statement_search', 'uses' => 'SmFeesController@feesStatementSearch']);

        // Balance fees report
        Route::get('balance-fees-report', ['as' => 'balance_fees_report', 'uses' => 'SmFeesController@balanceFeesReport'])->middleware('userRolePermission:382');
        Route::post('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'SmFeesController@balanceFeesSearch']);
        Route::get('balance-fees-search', ['as' => 'balance_fees_search', 'uses' => 'SmFeesController@balanceFeesReport']);

        // Transaction Report
        Route::get('transaction-report', ['as' => 'transaction_report', 'uses' => 'SmFeesController@transactionReport'])->middleware('userRolePermission:383');
        Route::post('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'SmFeesController@transactionReportSearch']);
        Route::get('transaction-report-search', ['as' => 'transaction_report_search', 'uses' => 'SmFeesController@transactionReport']);

        // Class Report
        Route::get('class-report', ['as' => 'class_report', 'uses' => 'SmAcademicsController@classReport'])->middleware('userRolePermission:384');
        Route::post('class-report', ['as' => 'class_report', 'uses' => 'SmAcademicsController@classReportSearch']);


        // merit list Report
        Route::get('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'SmExaminationController@meritListReport'])->middleware('userRolePermission:388');
        Route::post('merit-list-report', ['as' => 'merit_list_report', 'uses' => 'SmExaminationController@meritListReportSearch']);
        Route::get('merit-list/print/{exam_id}/{class_id}/{section_id}',  'SmExaminationController@meritListPrint')->name('merit-list/print');


        //tabulation sheet report
        Route::get('reports-tabulation-sheet', ['as' => 'reports_tabulation_sheet', 'uses' => 'SmExaminationController@reportsTabulationSheet']);
        Route::post('reports-tabulation-sheet', ['as' => 'reports_tabulation_sheet', 'uses' => 'SmExaminationController@reportsTabulationSheetSearch']);


        //results-archive report resultsArchive
        Route::get('results-archive', 'SmExaminationController@resultsArchiveView')->name('results-archive');
        Route::get('get-archive-class', 'SmExaminationController@getArchiveClass');
        Route::post('results-archive',  'SmExaminationController@resultsArchiveSearch');

        //Previous Record
        Route::get('previous-record', 'SmStudentAdmissionController@previousRecord')->name('previous-record')->middleware('userRolePermission:540');
        Route::post('previous-record',  'SmStudentAdmissionController@previousRecordSearch')->name('previous-record');

        //previous-class-results
        Route::get('previous-class-results', 'SmExaminationController@previousClassResults')->name('previous-class-results')->middleware('userRolePermission:539');
        Route::post('previous-class-results-view', 'SmExaminationController@previousClassResultsViewPost')->name('previous-class-results-view');

        Route::post('session-student', 'SmExaminationController@sessionStudentGet')->name('session_student');

        Route::post('previous-class-results/print', 'SmExaminationController@sessionStudentPrint')->name('session_student_print');

        // merit list Report
        Route::get('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'SmOnlineExamController@onlineExamReport'])->middleware('userRolePermission:389');
        Route::post('online-exam-report', ['as' => 'online_exam_report', 'uses' => 'SmOnlineExamController@onlineExamReportSearch']);


        // class routine report

        Route::get('class-routine-report', ['as' => 'class_routine_report', 'uses' => 'SmClassRoutineNewController@classRoutineReport'])->middleware('userRolePermission:385');
        Route::post('class-routine-report', 'SmClassRoutineNewController@classRoutineReportSearch')->name('class_routine_report');


        // exam routine report
        Route::get('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'SmExamRoutineController@examRoutineReport'])->middleware('userRolePermission:386');
        Route::post('exam-routine-report', ['as' => 'exam_routine_report', 'uses' => 'SmExamRoutineController@examRoutineReportSearch']);


        Route::get('exam-routine/print/{exam_id}', 'SmExamRoutineController@examRoutineReportSearchPrint')->name('exam-routine/print');

        Route::get('teacher-class-routine-report', ['as' => 'teacher_class_routine_report', 'uses' => 'SmClassRoutineNewController@teacherClassRoutineReport'])->middleware('userRolePermission:387');
        Route::post('teacher-class-routine-report', 'SmClassRoutineNewController@teacherClassRoutineReportSearch')->name('teacher-class-routine-report');


        // mark sheet Report
        Route::get('mark-sheet-report', ['as' => 'mark_sheet_report', 'uses' => 'SmExaminationController@markSheetReport']);
        Route::post('mark-sheet-report', ['as' => 'mark_sheet_report', 'uses' => 'SmExaminationController@markSheetReportSearch']);
        Route::get('mark-sheet-report/print/{exam_id}/{class_id}/{section_id}/{student_id}', ['as' => 'mark_sheet_report_print', 'uses' => 'SmExaminationController@markSheetReportStudentPrint']);


        //mark sheet report student
        Route::get('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmExaminationController@markSheetReportStudent'])->middleware('userRolePermission:390');
        Route::post('mark-sheet-report-student', ['as' => 'mark_sheet_report_student', 'uses' => 'SmExaminationController@markSheetReportStudentSearch']);


        //user log
        Route::get('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'SmFeesController@studentFineReport'])->middleware('userRolePermission:393');
        Route::post('student-fine-report', ['as' => 'student_fine_report', 'uses' => 'SmFeesController@studentFineReportSearch']);


        //user log
        Route::get('user-log', ['as' => 'user_log', 'uses' => 'UserController@userLog'])->middleware('userRolePermission:394');


        // income head routes
        Route::get('income-head', ['as' => 'income_head', 'uses' => 'SmIncomeHeadController@index']);
        Route::post('income-head-store', ['as' => 'income_head_store', 'uses' => 'SmIncomeHeadController@store']);
        Route::get('income-head-edit/{id}', ['as' => 'income_head_edit', 'uses' => 'SmIncomeHeadController@edit']);
        Route::post('income-head-update', ['as' => 'income_head_update', 'uses' => 'SmIncomeHeadController@update']);
        Route::get('income-head-delete/{id}', ['as' => 'income_head_delete', 'uses' => 'SmIncomeHeadController@delete']);

        // Search account
        Route::get('search-account', ['as' => 'search_account', 'uses' => 'SmAccountsController@searchAccount'])->middleware('userRolePermission:147');
        Route::post('search-account', ['as' => 'search_account', 'uses' => 'SmAccountsController@searchAccountReportByDate']);


        // // Search Expense
        // Route::get('search-expense', ['as' => 'search_expense', 'uses' => 'SmAccountsController@searchExpense']);
        // Route::post('search-expense-report-by-date', ['as' => 'search_expense_report_by_date', 'uses' => 'SmAccountsController@searchExpenseReportByDate']);
        // Route::get('search-expense-report-by-date', ['as' => 'search_expense_report_by_date', 'uses' => 'SmAccountsController@searchExpense']);
        // Route::post('search-expense-report-by-income', ['as' => 'search_expense_report_by_income', 'uses' => 'SmAccountsController@searchExpenseReportByIncome']);


        // add income routes
        Route::get('add-income', ['as' => 'add_income', 'uses' => 'SmAddIncomeController@index'])->middleware('userRolePermission:139');
        Route::post('add-income-store', ['as' => 'add_income_store', 'uses' => 'SmAddIncomeController@store'])->middleware('userRolePermission:140');
        Route::get('add-income-edit/{id}', ['as' => 'add_income_edit', 'uses' => 'SmAddIncomeController@edit'])->middleware('userRolePermission:141');
        Route::post('add-income-update', ['as' => 'add_income_update', 'uses' => 'SmAddIncomeController@update'])->middleware('userRolePermission:141');
        Route::post('add-income-delete', ['as' => 'add_income_delete', 'uses' => 'SmAddIncomeController@delete'])->middleware('userRolePermission:142');


        // Profit of account
        Route::get('profit', ['as' => 'profit', 'uses' => 'SmAccountsController@profit'])->middleware('userRolePermission:138');
        Route::post('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'SmAccountsController@searchProfitByDate']);
        Route::get('search-profit-by-date', ['as' => 'search_profit_by_date', 'uses' => 'SmAccountsController@profit']);

        // Student Type Routes

        Route::get('student-category', ['as' => 'student_category', 'uses' => 'SmStudentCategoryController@index'])->middleware('userRolePermission:71');
        Route::post('student-category-store', ['as' => 'student_category_store', 'uses' => 'SmStudentCategoryController@store'])->middleware('userRolePermission:72');
        Route::get('student-category-edit/{id}', ['as' => 'student_category_edit', 'uses' => 'SmStudentCategoryController@edit'])->middleware('userRolePermission:73');
        Route::post('student-category-update', ['as' => 'student_category_update', 'uses' => 'SmStudentCategoryController@update'])->middleware('userRolePermission:73');
        Route::get('student-category-delete/{id}', ['as' => 'student_category_delete', 'uses' => 'SmStudentCategoryController@delete'])->middleware('userRolePermission:74');

        // Student Group Routes

        Route::get('student-group', ['as' => 'student_group', 'uses' => 'SmStudentGroupController@index'])->middleware('userRolePermission:76');
        Route::post('student-group-store', ['as' => 'student_group_store', 'uses' => 'SmStudentGroupController@store'])->middleware('userRolePermission:77');
        Route::get('student-group-edit/{id}', ['as' => 'student_group_edit', 'uses' => 'SmStudentGroupController@edit'])->middleware('userRolePermission:79');
        Route::post('student-group-update', ['as' => 'student_group_update', 'uses' => 'SmStudentGroupController@update'])->middleware('userRolePermission:79');
        Route::get('student-group-delete/{id}', ['as' => 'student_group_delete', 'uses' => 'SmStudentGroupController@delete'])->middleware('userRolePermission:80');

        // Student Group Routes

        Route::get('payment-method', ['as' => 'payment_method', 'uses' => 'SmPaymentMethodController@index'])->middleware('userRolePermission:153');
        Route::post('payment-method-store', ['as' => 'payment_method_store', 'uses' => 'SmPaymentMethodController@store'])->middleware('userRolePermission:153');
        Route::get('payment-method-settings-edit/{id}', ['as' => 'payment_method_edit', 'uses' => 'SmPaymentMethodController@edit'])->middleware('userRolePermission:154');
        Route::post('payment-method-update', ['as' => 'payment_method_update', 'uses' => 'SmPaymentMethodController@update'])->middleware('userRolePermission:154');
        Route::get('delete-payment-method/{id}', ['as' => 'payment_method_delete', 'uses' => 'SmPaymentMethodController@delete'])->middleware('userRolePermission:155');

        //academic year
        // Route::resource('academic-year', 'SmAcademicYearController')->middleware('userRolePermission:432');
        Route::get('academic-year', 'SmAcademicYearController@index')->name('academic-year')->middleware('userRolePermission:432');
        Route::post('academic-year', 'SmAcademicYearController@store')->name('academic-year')->middleware('userRolePermission:433');
        Route::get('academic-year/{id}', 'SmAcademicYearController@show')->name('academic-year-edit')->middleware('userRolePermission:434');
        Route::put('academic-year/{id}', 'SmAcademicYearController@update')->name('academic-year-update')->middleware('userRolePermission:434');
        Route::delete('academic-year/{id}', 'SmAcademicYearController@destroy')->name('academic-year-delete')->middleware('userRolePermission:435');

        //Session
        Route::resource('session', 'SmSessionController');


        // exam

        Route::get('exam-reset', 'SmExamController@exam_reset');

        // Route::resource('exam', 'SmExamController')->middleware('userRolePermission:214');
        Route::get('exam', 'SmExamController@index')->name('exam')->middleware('userRolePermission:214');
        Route::post('exam', 'SmExamController@store')->name('exam')->middleware('userRolePermission:215');
        Route::get('exam/{id}', 'SmExamController@show')->name('exam-edit')->middleware('userRolePermission:397');
        Route::put('exam/{id}', 'SmExamController@update')->name('exam-update')->middleware('userRolePermission:397');
        Route::delete('exam/{id}', 'SmExamController@destroy')->name('exam-delete')->middleware('userRolePermission:216');
        
        Route::get('exam-marks-setup/{id}', 'SmExamController@exam_setup')->name('exam-marks-setup')->where('id', '[0-9]+');
        Route::get('get-class-subjects', 'SmExamController@getClassSubjects');
        Route::get('subject-assign-check', 'SmExamController@subjectAssignCheck');


        // Dormitory Module
        //Dormitory List
        // Route::resource('dormitory-list', 'SmDormitoryListController')->middleware('userRolePermission:367');
        Route::get('dormitory-list', 'SmDormitoryListController@index')->name('dormitory-list')->middleware('userRolePermission:367');
        Route::post('dormitory-list', 'SmDormitoryListController@store')->name('dormitory-list')->middleware('userRolePermission:368');
        Route::get('dormitory-list/{id}', 'SmDormitoryListController@show')->name('dormitory-list-edit')->middleware('userRolePermission:369');
        Route::put('dormitory-list/{id}', 'SmDormitoryListController@update')->name('dormitory-list-update')->middleware('userRolePermission:369');
        Route::delete('dormitory-list/{id}', 'SmDormitoryListController@destroy')->name('dormitory-list-delete')->middleware('userRolePermission:370');

        //Room Type
        // Route::resource('room-type', 'SmRoomTypeController@')->middleware('userRolePermission:371');
        Route::get('room-type', 'SmRoomTypeController@index')->name('room-type')->middleware('userRolePermission:371');
        Route::post('room-type', 'SmRoomTypeController@store')->name('room-type')->middleware('userRolePermission:372');
        Route::get('room-type/{id}', 'SmRoomTypeController@show')->name('room-type-edit')->middleware('userRolePermission:373');
        Route::put('room-type/{id}', 'SmRoomTypeController@update')->name('room-type-update')->middleware('userRolePermission:373');
        Route::delete('room-type/{id}', 'SmRoomTypeController@destroy')->name('room-type-delete')->middleware('userRolePermission:374');

        //Room Type
        // Route::resource('room-list', 'SmRoomListController')->middleware('userRolePermission:363');
        Route::get('room-list', 'SmRoomListController@index')->name('room-list')->middleware('userRolePermission:363');
        Route::post('room-list', 'SmRoomListController@store')->name('room-list')->middleware('userRolePermission:364');
        Route::get('room-list/{id}', 'SmRoomListController@show')->name('room-list-edit')->middleware('userRolePermission:353');
        Route::put('room-list/{id}', 'SmRoomListController@update')->name('room-list-update')->middleware('userRolePermission:365');
        Route::delete('room-list/{id}', 'SmRoomListController@destroy')->name('room-list-delete')->middleware('userRolePermission:366');
        // Student Dormitory Report
        Route::get('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'SmDormitoryController@studentDormitoryReport'])->middleware('userRolePermission:375');

        Route::post('student-dormitory-report', ['as' => 'student_dormitory_report', 'uses' => 'SmDormitoryController@studentDormitoryReportSearch']);


        // Transport Module Start
        //Vehicle
        // Route::resource('vehicle', 'SmVehicleController')->middleware('userRolePermission:353');
        Route::get('vehicle', 'SmVehicleController@index')->name('vehicle')->middleware('userRolePermission:353');
        Route::post('vehicle', 'SmVehicleController@store')->name('vehicle')->middleware('userRolePermission:354');
        Route::get('vehicle/{id}', 'SmVehicleController@show')->name('vehicle-edit')->middleware('userRolePermission:355');
        Route::put('vehicle/{id}', 'SmVehicleController@update')->name('vehicle-update')->middleware('userRolePermission:355');
        Route::delete('vehicle/{id}', 'SmVehicleController@destroy')->name('vehicle-delete')->middleware('userRolePermission:356');

        //Assign Vehicle
        // Route::resource('assign-vehicle', 'SmAssignVehicleController')->middleware('userRolePermission:357');
        Route::get('assign-vehicle', 'SmAssignVehicleController@index')->name('assign-vehicle')->middleware('userRolePermission:357');
        Route::post('assign-vehicle', 'SmAssignVehicleController@store')->name('assign-vehicle')->middleware('userRolePermission:358');
        Route::get('assign-vehicle/{id}/edit', 'SmAssignVehicleController@edit')->name('assign-vehicle-edit')->middleware('userRolePermission:359');
        Route::put('assign-vehicle/{id}', 'SmAssignVehicleController@update')->name('assign-vehicle-update')->middleware('userRolePermission:359');
        // Route::delete('assign-vehicle/{id}', 'SmAssignVehicleController@delete')->name('assign-vehicle-delete')->middleware('userRolePermission:360');
        
        Route::post('assign-vehicle-delete', 'SmAssignVehicleController@delete')->name('assign-vehicle-delete')->middleware('userRolePermission:360');

        // student transport report

        Route::get('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'SmTransportController@studentTransportReport'])->middleware('userRolePermission:361');



        Route::post('student-transport-report', ['as' => 'student_transport_report', 'uses' => 'SmTransportController@studentTransportReportSearch']);


        // Route transport
        // Route::resource('transport-route', 'SmRouteController')->middleware('userRolePermission:349');
        Route::get('transport-route', 'SmRouteController@index')->name('transport-route')->middleware('userRolePermission:349');
        Route::post('transport-route', 'SmRouteController@store')->name('transport-route')->middleware('userRolePermission:350');
        Route::get('transport-route/{id}', 'SmRouteController@show')->name('transport-route-edit')->middleware('userRolePermission:351');
        Route::put('transport-route/{id}', 'SmRouteController@update')->name('transport-route-update')->middleware('userRolePermission:351');
        Route::delete('transport-route/{id}', 'SmRouteController@destroy')->name('transport-route-delete')->middleware('userRolePermission:352');

        //// Examination
        // instruction Routes
        Route::get('instruction', 'SmInstructionController@index')->name('instruction');
        Route::post('instruction', 'SmInstructionController@store')->name('instruction');
        Route::get('instruction/{id}', 'SmInstructionController@show')->name('instruction-edit');
        Route::put('instruction/{id}', 'SmInstructionController@update')->name('instruction-update');
        Route::delete('instruction/{id}', 'SmInstructionController@destroy')->name('instruction-delete');

        // Question Level
        Route::get('question-level', 'SmQuestionLevelController@index')->name('question-level');
        Route::post('question-level', 'SmQuestionLevelController@store')->name('question-level');
        Route::get('question-level/{id}', 'SmQuestionLevelController@show')->name('question-level-edit');
        Route::put('question-level/{id}', 'SmQuestionLevelController@update')->name('question-level-update');
        Route::delete('question-level/{id}', 'SmQuestionLevelController@destroy')->name('question-level-delete');

        // Question group
        // Route::resource('question-group', 'SmQuestionGroupController')->middleware('userRolePermission:230');
        Route::get('question-group', 'SmQuestionGroupController@index')->name('question-group')->middleware('userRolePermission:230');
        Route::post('question-group', 'SmQuestionGroupController@store')->name('question-group')->middleware('userRolePermission:231');
        Route::get('question-group/{id}', 'SmQuestionGroupController@show')->name('question-group-edit')->middleware('userRolePermission:232');
        Route::put('question-group/{id}', 'SmQuestionGroupController@update')->name('question-group-update')->middleware('userRolePermission:232');
        Route::delete('question-group/{id}', 'SmQuestionGroupController@destroy')->name('question-group-delete')->middleware('userRolePermission:233');

        // Question bank
        // Route::resource('question-bank', 'SmQuestionBankController')->middleware('userRolePermission:234');
        Route::get('question-bank', 'SmQuestionBankController@index')->name('question-bank')->middleware('userRolePermission:234');
        Route::post('question-bank', 'SmQuestionBankController@store')->name('question-bank')->middleware('userRolePermission:235');
        Route::get('question-bank/{id}', 'SmQuestionBankController@show')->name('question-bank-edit')->middleware('userRolePermission:236');
        Route::put('question-bank/{id}', 'SmQuestionBankController@update')->name('question-bank-update')->middleware('userRolePermission:236');
        Route::delete('question-bank/{id}', 'SmQuestionBankController@destroy')->name('question-bank-delete')->middleware('userRolePermission:237');


        // Marks Grade
        // Route::resource('marks-grade', 'SmMarksGradeController')->middleware('userRolePermission:225');
        Route::get('marks-grade', 'SmMarksGradeController@index')->name('marks-grade')->middleware('userRolePermission:225');
        Route::post('marks-grade', 'SmMarksGradeController@store')->name('marks-grade')->middleware('userRolePermission:226');
        Route::get('marks-grade/{id}', 'SmMarksGradeController@show')->name('marks-grade-edit')->middleware('userRolePermission:227');
        Route::put('marks-grade/{id}', 'SmMarksGradeController@update')->name('marks-grade-update')->middleware('userRolePermission:227');
        Route::delete('marks-grade/{id}', 'SmMarksGradeController@destroy')->name('marks-grade-delete')->middleware('userRolePermission:228');


        // exam
        // Route::resource('exam', 'SmExamController');

        Route::get('exam-type', 'SmExaminationController@exam_type')->name('exam-type')->middleware('userRolePermission:209');
        Route::get('exam-type-edit/{id}', ['as' => 'exam_type_edit', 'uses' => 'SmExaminationController@exam_type_edit'])->middleware('userRolePermission:210');
        Route::post('exam-type-store', ['as' => 'exam_type_store', 'uses' => 'SmExaminationController@exam_type_store'])->middleware('userRolePermission:209');
        Route::post('exam-type-update', ['as' => 'exam_type_update', 'uses' => 'SmExaminationController@exam_type_update'])->middleware('userRolePermission:210');
        Route::get('exam-type-delete/{id}', ['as' => 'exam_type_delete', 'uses' => 'SmExaminationController@exam_type_delete'])->middleware('userRolePermission:211');


        Route::get('exam-setup/{id}', 'SmExamController@examSetup');
        Route::post('exam-setup-store', 'SmExamController@examSetupStore')->name('exam-setup-store');


        // exam
        // Route::resource('department', 'SmHumanDepartmentController')->middleware('userRolePermission:184');
        Route::get('department', 'SmHumanDepartmentController@index')->name('department')->middleware('userRolePermission:184');
        Route::post('department', 'SmHumanDepartmentController@store')->name('department')->middleware('userRolePermission:185');
        Route::get('department/{id}', 'SmHumanDepartmentController@show')->name('department-edit')->middleware('userRolePermission:186');
        Route::put('department/{id}', 'SmHumanDepartmentController@update')->name('department-update')->middleware('userRolePermission:186');
        Route::delete('department/{id}', 'SmHumanDepartmentController@destroy')->name('department-delete')->middleware('userRolePermission:187');

        Route::post('exam-schedule-store', ['as' => 'exam_schedule_store', 'uses' => 'SmExaminationController@examScheduleStore']);
        Route::get('exam-schedule-store', ['as' => 'exam_schedule_store', 'uses' => 'SmExaminationController@examScheduleCreate']);

        //Exam Schedule
        Route::get('exam-schedule', ['as' => 'exam_schedule', 'uses' => 'SmExamRoutineController@examSchedule'])->middleware('userRolePermission:217');

        Route::post('exam-schedule-report-search', ['as' => 'exam_schedule_report_search', 'uses' => 'SmExamRoutineController@examScheduleReportSearch']);

        Route::get('exam-schedule-report-search', ['as' => 'exam_schedule_report_search', 'uses' => 'SmExaminationController@examSchedule']);
        Route::get('exam-schedule/print/{exam_id}/{class_id}/{section_id}', ['as' => 'exam_schedule_print', 'uses' => 'SmExamRoutineController@examSchedulePrint']);
        Route::get('view-exam-schedule/{class_id}/{section_id}/{exam_id}', ['as' => 'view_exam_schedule', 'uses' => 'SmExaminationController@viewExamSchedule']);


        //Exam Schedule create
        Route::get('exam-schedule-create', ['as' => 'exam_schedule_create', 'uses' => 'SmExamRoutineController@examScheduleCreate'])->middleware('userRolePermission:218');
        Route::post('exam-schedule-create', ['as' => 'exam_schedule_create', 'uses' => 'SmExamRoutineController@examScheduleSearch'])->middleware('userRolePermission:218');


        Route::get('add-exam-routine-modal/{subject_id}/{exam_period_id}/{class_id}/{section_id}/{exam_term_id}', 'SmExamRoutineController@addExamRoutineModal')->name('add-exam-routine-modal')->middleware('userRolePermission:219');


        Route::get('check-exam-routine-period', 'SmExamRoutineController@checkExamRoutinePeriod');

        Route::get('edit-exam-routine-modal/{subject_id}/{exam_period_id}/{class_id}/{section_id}/{exam_term_id}/{assigned_id}', 'SmExamRoutineController@EditExamRoutineModal')->name('edit-exam-routine-modal');

        Route::get('delete-exam-routine-modal/{assigned_id}', 'SmExamRoutineController@deleteExamRoutineModal')->name('delete-exam-routine-modal');
        Route::get('delete-exam-routine/{assigned_id}', 'SmExamRoutineController@deleteExamRoutine')->name('delete-exam-routine');

        Route::post('add-exam-routine-store', 'SmExamRoutineController@addExamRoutineStore')->name('add-exam-routine-store');

        Route::get('check-exam-routine-date', 'SmExamRoutineController@checkExamRoutineDate');

        Route::get('exam-routine-view/{class_id}/{section_id}/{exam_period_id}', 'SmExamRoutineController@examRoutineView');


        //view exam status
        Route::get('view-exam-status/{exam_id}', ['as' => 'view_exam_status', 'uses' => 'SmExaminationController@viewExamStatus']);

        // marks register
        Route::get('marks-register', ['as' => 'marks_register', 'uses' => 'SmExaminationController@marksRegister']);
        Route::post('marks-register', ['as' => 'marks_register', 'uses' => 'SmExaminationController@marksRegisterReportSearch']);

        Route::get('marks-register-create', ['as' => 'marks_register_create', 'uses' => 'SmExaminationController@marksRegisterCreate']);


        Route::post('marks-register-create', ['as' => 'marks_register_create', 'uses' => 'SmExaminationController@marksRegisterSearch']);

        Route::post('marks_register_store', ['as' => 'marks_register_store', 'uses' => 'SmExaminationController@marksRegisterStore']);


        //Seat Plan
        Route::get('seat-plan', ['as' => 'seat_plan', 'uses' => 'SmExaminationController@seatPlan']);
        Route::post('seat-plan-report-search', ['as' => 'seat_plan_report_search', 'uses' => 'SmExaminationController@seatPlanReportSearch']);
        Route::get('seat-plan-report-search', ['as' => 'seat_plan_report_search', 'uses' => 'SmExaminationController@seatPlan']);

        Route::get('seat-plan-create', ['as' => 'seat_plan_create', 'uses' => 'SmExaminationController@seatPlanCreate']);

        Route::post('seat-plan-store', ['as' => 'seat_plan_store', 'uses' => 'SmExaminationController@seatPlanStore']);
        Route::get('seat-plan-store', ['as' => 'seat_plan_store', 'uses' => 'SmExaminationController@seatPlanCreate']);

        Route::post('seat-plan-search', ['as' => 'seat_plan_search', 'uses' => 'SmExaminationController@seatPlanSearch']);
        Route::get('seat-plan-search', ['as' => 'seat_plan_search', 'uses' => 'SmExaminationController@seatPlanCreate']);
        Route::get('assign-exam-room-get-by-ajax', ['as' => 'assign-exam-room-get-by-ajax', 'uses' => 'SmExaminationController@getExamRoomByAjax']);
        Route::get('get-room-capacity', ['as' => 'get-room-capacity', 'uses' => 'SmExaminationController@getRoomCapacity']);


        // Exam Attendance
        Route::get('exam-attendance', ['as' => 'exam_attendance', 'uses' => 'SmExaminationController@examAttendance']);
        Route::post('exam-attendance', ['as' => 'exam_attendance', 'uses' => 'SmExaminationController@examAttendanceAeportSearch']);


        Route::get('exam-attendance-create', ['as' => 'exam_attendance_create', 'uses' => 'SmExaminationController@examAttendanceCreate']);
        Route::post('exam-attendance-create', ['as' => 'exam_attendance_create', 'uses' => 'SmExaminationController@examAttendanceSearch']);


        Route::post('exam-attendance-store', 'SmExaminationController@examAttendanceStore')->name('exam-attendance-store');

        // Send Marks By SmS
        Route::get('send-marks-by-sms', ['as' => 'send_marks_by_sms', 'uses' => 'SmExaminationController@sendMarksBySms'])->middleware('userRolePermission:229');
        Route::post('send-marks-by-sms-store', ['as' => 'send_marks_by_sms_store', 'uses' => 'SmExaminationController@sendMarksBySmsStore'])->middleware('userRolePermission:227');

        // Online Exam
        // Route::resource('online-exam', 'SmOnlineExamController')->middleware('userRolePermission:238');
        Route::get('online-exam', 'SmOnlineExamController@index')->name('online-exam')->middleware('userRolePermission:238');
        Route::post('online-exam', 'SmOnlineExamController@store')->name('online-exam')->middleware('userRolePermission:239');
        Route::get('online-exam/{id}', 'SmOnlineExamController@edit')->name('online-exam-edit')->middleware('userRolePermission:240');
        Route::put('online-exam/{id}', 'SmOnlineExamController@update')->name('online-exam-update')->middleware('userRolePermission:240');
        // Route::delete('online-exam/{id}', 'SmOnlineExamController@delete')->name('online-exam-delete')->middleware('userRolePermission:241');

        Route::post('online-exam-delete', 'SmOnlineExamController@delete')->name('online-exam-delete')->middleware('userRolePermission:241');
        Route::get('manage-online-exam-question/{id}', ['as' => 'manage_online_exam_question', 'uses' => 'SmOnlineExamController@manageOnlineExamQuestion'])->middleware('userRolePermission:242');
        Route::post('online_exam_question_store', ['as' => 'online_exam_question_store', 'uses' => 'SmOnlineExamController@manageOnlineExamQuestionStore']);

        Route::get('online-exam-publish/{id}', ['as' => 'online_exam_publish', 'uses' => 'SmOnlineExamController@onlineExamPublish']);
        Route::get('online-exam-publish-cancel/{id}', ['as' => 'online_exam_publish_cancel', 'uses' => 'SmOnlineExamController@onlineExamPublishCancel']);

        Route::get('online-question-edit/{id}/{type}/{examId}', 'SmOnlineExamController@onlineQuestionEdit');
        Route::post('online-exam-question-edit', ['as' => 'online_exam_question_edit', 'uses' => 'SmOnlineExamController@onlineExamQuestionEdit']);
        Route::post('online-exam-question-delete', 'SmOnlineExamController@onlineExamQuestionDelete')->name('online-exam-question-delete');

        // store online exam question
        Route::post('online-exam-question-assign', ['as' => 'online_exam_question_assign', 'uses' => 'SmOnlineExamController@onlineExamQuestionAssign']);

        Route::get('view_online_question_modal/{id}', ['as' => 'view_online_question_modal', 'uses' => 'SmOnlineExamController@viewOnlineQuestionModal']);


        // Online exam marks
        Route::get('online-exam-marks-register/{id}', ['as' => 'online_exam_marks_register', 'uses' => 'SmOnlineExamController@onlineExamMarksRegister'])->middleware('userRolePermission:243');

        Route::post('online-exam-marks-store', ['as' => 'online_exam_marks_store', 'uses' => 'SmOnlineExamController@onlineExamMarksStore']);
        Route::get('online-exam-result/{id}', ['as' => 'online_exam_result', 'uses' => 'SmOnlineExamController@onlineExamResult'])->middleware('userRolePermission:244');

        Route::get('online-exam-marking/{exam_id}/{s_id}', ['as' => 'online_exam_marking', 'uses' => 'SmOnlineExamController@onlineExamMarking']);
        Route::post('online-exam-marks-store', ['as' => 'online_exam_marks_store', 'uses' => 'SmOnlineExamController@onlineExamMarkingStore']);


        // Staff Hourly rate
        Route::get('hourly-rate', 'SmHourlyRateController@index')->name('hourly-rate');
        Route::post('hourly-rate', 'SmHourlyRateController@store')->name('hourly-rate');
        Route::get('hourly-rate', 'SmHourlyRateController@show')->name('hourly-rate');
        Route::put('hourly-rate', 'SmHourlyRateController@update')->name('hourly-rate');
        Route::delete('hourly-rate', 'SmHourlyRateController@destroy')->name('hourly-rate');

        // Staff leave type
        // Route::resource('leave-type', 'SmLeaveTypeController')->middleware('userRolePermission:203');
        Route::get('leave-type', 'SmLeaveTypeController@index')->name('leave-type')->middleware('userRolePermission:203');
        Route::post('leave-type', 'SmLeaveTypeController@store')->name('leave-type')->middleware('userRolePermission:204');
        Route::get('leave-type/{id}', 'SmLeaveTypeController@show')->name('leave-type-edit')->middleware('userRolePermission:205');
        Route::put('leave-type/{id}', 'SmLeaveTypeController@update')->name('leave-type-update')->middleware('userRolePermission:205');
        Route::delete('leave-type/{id}', 'SmLeaveTypeController@destroy')->name('leave-type-delete')->middleware('userRolePermission:206');

        // Staff leave define
        // Route::resource('leave-define', 'SmLeaveDefineController')->middleware('userRolePermission:199');
        Route::get('leave-define', 'SmLeaveDefineController@index')->name('leave-define')->middleware('userRolePermission:199');
        Route::post('leave-define', 'SmLeaveDefineController@store')->name('leave-define')->middleware('userRolePermission:200');
        Route::get('leave-define/{id}', 'SmLeaveDefineController@show')->name('leave-define-edit')->middleware('userRolePermission:201');
        Route::put('leave-define/{id}', 'SmLeaveDefineController@update')->name('leave-define-update')->middleware('userRolePermission:201');
        Route::delete('leave-define/{id}', 'SmLeaveDefineController@destroy')->name('leave-define-delete')->middleware('userRolePermission:202');

        // Staff leave define
        // Route::resource('apply-leave', 'SmLeaveRequestController')->middleware('userRolePermission:193');
        Route::get('apply-leave', 'SmLeaveRequestController@index')->name('apply-leave')->middleware('userRolePermission:193');
        Route::post('apply-leave', 'SmLeaveRequestController@store')->name('apply-leave')->middleware('userRolePermission:553');
        Route::get('apply-leave/{id}', 'SmLeaveRequestController@show')->name('apply-leave-edit')->middleware('userRolePermission:396');
        Route::put('apply-leave/{id}', 'SmLeaveRequestController@update')->name('apply-leave-update')->middleware('userRolePermission:396');
        Route::delete('apply-leave/{id}', 'SmLeaveRequestController@destroy')->name('apply-leave-delete')->middleware('userRolePermission:195');

        // Staff designation
        // Route::resource('designation', 'SmDesignationController')->middleware('userRolePermission:180');
        Route::get('designation', 'SmDesignationController@index')->name('designation')->middleware('userRolePermission:180');
        Route::post('designation', 'SmDesignationController@store')->name('designation')->middleware('userRolePermission:181');
        Route::get('designation/{id}', 'SmDesignationController@show')->name('designation-edit')->middleware('userRolePermission:182');
        Route::put('designation/{id}', 'SmDesignationController@update')->name('designation-update')->middleware('userRolePermission:182');
        Route::delete('designation/{id}', 'SmDesignationController@destroy')->name('designation-delete')->middleware('userRolePermission:183');

        // Route::resource('approve-leave', 'SmApproveLeaveController')->middleware('userRolePermission:189');
        Route::get('approve-leave', 'SmApproveLeaveController@index')->name('approve-leave')->middleware('userRolePermission:189');
        Route::post('approve-leave', 'SmApproveLeaveController@store')->name('approve-leave');
        Route::get('approve-leave/{id}', 'SmApproveLeaveController@show')->name('approve-leave-edit');
        Route::put('approve-leave/{id}', 'SmApproveLeaveController@update')->name('approve-leave-update');
        Route::delete('approve-leave/{id}','SmApproveLeaveController@destroy')->name('approve-leave-delete')->middleware('userRolePermission:192');

        Route::get('pending-leave', 'SmApproveLeaveController@pendingLeave')->name('pending-leave')->middleware('userRolePermission:196');

        Route::post('update-approve-leave', 'SmApproveLeaveController@updateApproveLeave')->name('update-approve-leave');

        Route::get('/staffNameByRole', 'SmApproveLeaveController@staffNameByRole');

        Route::get('view-leave-details-approve/{id}', 'SmApproveLeaveController@viewLeaveDetails')->name('view-leave-details-approve')->middleware('userRolePermission:191');
        Route::get('view-leave-details-apply/{id}', 'SmLeaveRequestController@viewLeaveDetails')->name('view-leave-details-apply')->middleware('userRolePermission:93');

        // Bank Account
        // Route::resource('bank-account', 'SmBankAccountController')->middleware('userRolePermission:156');
        Route::get('bank-account', 'SmBankAccountController@index')->name('bank-account')->middleware('userRolePermission:156');
        Route::post('bank-account', 'SmBankAccountController@store')->name('bank-account')->middleware('userRolePermission:157');
        Route::get('bank-account/{id}', 'SmBankAccountController@show')->name('bank-account-edit')->middleware('userRolePermission:158');
        Route::put('bank-account/{id}', 'SmBankAccountController@update')->name('bank-account-update')->middleware('userRolePermission:158');
        Route::delete('bank-account/{id}', 'SmBankAccountController@destroy')->name('bank-account-delete')->middleware('userRolePermission:159');

        // Expense head
        // Route::resource('expense-head', 'SmExpenseHeadController');   //not used

        // Chart Of Account
        // Route::resource('chart-of-account', 'SmChartOfAccountController')->middleware('userRolePermission:148');
        Route::get('chart-of-account', 'SmChartOfAccountController@index')->name('chart-of-account')->middleware('userRolePermission:148');
        Route::post('chart-of-account', 'SmChartOfAccountController@store')->name('chart-of-account')->middleware('userRolePermission:149');
        Route::get('chart-of-account/{id}', 'SmChartOfAccountController@show')->name('chart-of-account-edit')->middleware('userRolePermission:150');
        Route::put('chart-of-account/{id}', 'SmChartOfAccountController@update')->name('chart-of-account-update')->middleware('userRolePermission:150');
        Route::delete('chart-of-account/{id}', 'SmChartOfAccountController@destroy')->name('chart-of-account-delete')->middleware('userRolePermission:151');

        // Add Expense
        // Route::resource('add-expense', 'SmAddExpenseController')->middleware('userRolePermission:143');
        Route::get('add-expense', 'SmAddExpenseController@index')->name('add-expense')->middleware('userRolePermission:143');
        Route::post('add-expense', 'SmAddExpenseController@store')->name('add-expense')->middleware('userRolePermission:144');
        Route::get('add-expense/{id}', 'SmAddExpenseController@show')->name('add-expense-edit')->middleware('userRolePermission:145');
        Route::put('add-expense/{id}', 'SmAddExpenseController@update')->name('add-expense-update')->middleware('userRolePermission:145');
        Route::delete('add-expense/{id}', 'SmAddExpenseController@destroy')->name('add-expense-delete')->middleware('userRolePermission:146');

        // Fees Master
        // Route::resource('fees-master', 'SmFeesMasterController')->middleware('userRolePermission:118');
        Route::get('fees-master', 'SmFeesMasterController@index')->name('fees-master')->middleware('userRolePermission:118');
        Route::post('fees-master', 'SmFeesMasterController@store')->name('fees-master')->middleware('userRolePermission:119');
        Route::get('fees-master/{id}', 'SmFeesMasterController@show')->name('fees-master-edit')->middleware('userRolePermission:120');
        Route::put('fees-master/{id}', 'SmFeesMasterController@update')->name('fees-master-update')->middleware('userRolePermission:120');
        Route::delete('fees-master/{id}', 'SmFeesMasterController@destroy')->name('fees-master-delete')->middleware('userRolePermission:121');

        Route::post('fees-master-single-delete', 'SmFeesMasterController@deleteSingle')->name('fees-master-single-delete')->middleware('userRolePermission:121');
        Route::post('fees-master-group-delete', 'SmFeesMasterController@deleteGroup')->name('fees-master-group-delete');
        Route::get('fees-assign/{id}', ['as' => 'fees_assign', 'uses' => 'SmFeesMasterController@feesAssign']);
        Route::post('fees-assign-search', 'SmFeesMasterController@feesAssignSearch')->name('fees-assign-search');

        Route::get('btn-assign-fees-group', 'SmFeesMasterController@feesAssignStore');

        // Complaint
        // Route::resource('complaint', 'SmComplaintController')->middleware('userRolePermission:21'); 
        Route::get('complaint', 'SmComplaintController@index')->name('complaint')->middleware('userRolePermission:21'); 
        Route::post('complaint', 'SmComplaintController@store')->name('complaint_store')->middleware('userRolePermission:22'); 
        Route::get('complaint/{id}', 'SmComplaintController@show')->name('complaint_show')->middleware('userRolePermission:26'); 
        Route::get('complaint/{id}/edit', 'SmComplaintController@edit')->name('complaint_edit')->middleware('userRolePermission:23'); 
        Route::put('complaint/{id}', 'SmComplaintController@update')->name('complaint_update')->middleware('userRolePermission:23'); 
        Route::delete('complaint/{id}', 'SmComplaintController@destroy')->name('complaint_delete')->middleware('userRolePermission:24'); 

        Route::get('download-complaint-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/complaint/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-complaint-document')->middleware('userRolePermission:25');


        // Complaint
  
        Route::get('setup-admin', 'SmSetupAdminController@index')->name('setup-admin')->middleware('userRolePermission:41');
        Route::post('setup-admin', 'SmSetupAdminController@store')->name('setup-admin')->middleware('userRolePermission:42');
        Route::get('setup-admin/{id}', 'SmSetupAdminController@show')->name('setup-admin-edit')->middleware('userRolePermission:43');
        Route::put('setup-admin/{id}', 'SmSetupAdminController@update')->name('setup-admin-update')->middleware('userRolePermission:43');
        Route::get('setup-admin-delete/{id}', 'SmSetupAdminController@destroy')->name('setup-admin-delete')->middleware('userRolePermission:44');


        // Postal Receive
        // Route::resource('postal-receive', 'SmPostalReceiveController');
        Route::get('postal-receive', 'SmPostalReceiveController@index')->name('postal-receive')->middleware('userRolePermission:27');
        Route::post('postal-receive', 'SmPostalReceiveController@store')->name('postal-receive')->middleware('userRolePermission:28');
        Route::get('postal-receive/{id}', 'SmPostalReceiveController@show')->name('postal-receive_edit')->middleware('userRolePermission:29');
        Route::put('postal-receive/{id}', 'SmPostalReceiveController@update')->name('postal-receive_update')->middleware('userRolePermission:29');
        Route::delete('postal-receive/{id}', 'SmPostalReceiveController@destroy')->name('postal-receive_delete')->middleware('userRolePermission:30');

        Route::get('postal-receive-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/postal/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('postal-receive-document')->middleware('userRolePermission:31');


        // Postal Dispatch
        // Route::resource('postal-dispatch', 'SmPostalDispatchController');
        Route::get('postal-dispatch', 'SmPostalDispatchController@index')->name('postal-dispatch')->middleware('userRolePermission:32');
        Route::post('postal-dispatch', 'SmPostalDispatchController@store')->name('postal-dispatch')->middleware('userRolePermission:33');
        Route::get('postal-dispatch/{id}', 'SmPostalDispatchController@show')->name('postal-dispatch_edit')->middleware('userRolePermission:34');
        Route::put('postal-dispatch/{id}', 'SmPostalDispatchController@update')->name('postal-dispatch_update')->middleware('userRolePermission:35');
        Route::delete('postal-dispatch/{id}', 'SmPostalDispatchController@destroy')->name('postal-dispatch_delete')->middleware('userRolePermission:35');

        Route::get('postal-dispatch-document/{file_name}', function ($file_name = null) {

            $file = public_path() . '/uploads/postal/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            } else {
                redirect()->back();
            }
        })->name('postal-dispatch-document')->middleware('userRolePermission:40');

        // Phone Call Log
        // Route::resource('phone-call', 'SmPhoneCallLogController');
        Route::get('phone-call', 'SmPhoneCallLogController@index')->name('phone-call')->middleware('userRolePermission:36');
        Route::post('phone-call', 'SmPhoneCallLogController@store')->name('phone-call')->middleware('userRolePermission:37');
        Route::get('phone-call/{id}', 'SmPhoneCallLogController@show')->name('phone-call_edit')->middleware('userRolePermission:38');
        Route::put('phone-call/{id}', 'SmPhoneCallLogController@update')->name('phone-call_update')->middleware('userRolePermission:38');
        Route::delete('phone-call/{id}', 'SmPhoneCallLogController@destroy')->name('phone-call_delete')->middleware('userRolePermission:39');

        // Student Certificate
        // Route::resource('student-certificate', 'SmStudentCertificateController');
        Route::get('student-certificate', 'SmStudentCertificateController@index')->name('student-certificate')->middleware('userRolePermission:49');
        Route::post('student-certificate', 'SmStudentCertificateController@store')->name('student-certificate')->middleware('userRolePermission:50');
        Route::get('student-certificate/{id}', 'SmStudentCertificateController@edit')->name('student-certificate-edit')->middleware('userRolePermission:51');
        Route::put('student-certificate/{id}', 'SmStudentCertificateController@update')->name('student-certificate-update')->middleware('userRolePermission:51');
        Route::delete('student-certificate/{id}', 'SmStudentCertificateController@destroy')->name('student-certificate-delete')->middleware('userRolePermission:52');

        // Generate certificate
        Route::get('generate-certificate', ['as' => 'generate_certificate', 'uses' => 'SmStudentCertificateController@generateCertificate'])->middleware('userRolePermission:53');
        Route::post('generate-certificate', ['as' => 'generate_certificate', 'uses' => 'SmStudentCertificateController@generateCertificateSearch'])->middleware('userRolePermission:53');
        // print certificate
        Route::get('generate-certificate-print/{s_id}/{c_id}', ['as' => 'student_certificate_generate', 'uses' => 'SmStudentCertificateController@generateCertificateGenerate']);


        // Student ID Card
        // Route::resource('student-id-card', 'SmStudentIdCardController');
        Route::get('student-id-card', 'SmStudentIdCardController@index')->name('student-id-card')->middleware('userRolePermission:45');
        Route::post('student-id-card', 'SmStudentIdCardController@store')->name('student-id-card')->middleware('userRolePermission:46');
        Route::get('student-id-card/{id}', 'SmStudentIdCardController@edit')->name('student-id-card-edit')->middleware('userRolePermission:47');
        Route::put('student-id-card/{id}', 'SmStudentIdCardController@update')->name('student-id-card-update')->middleware('userRolePermission:47');
        Route::delete('student-id-card/{id}', 'SmStudentIdCardController@destroy')->name('student-id-card-delete')->middleware('userRolePermission:48');

        Route::get('generate-id-card', ['as' => 'generate_id_card', 'uses' => 'SmStudentIdCardController@generateIdCard'])->middleware('userRolePermission:57');


        Route::post('generate-id-card-search', ['as' => 'generate_id_card_search', 'uses' => 'SmStudentIdCardController@generateIdCardSearch']);
        Route::get('generate-id-card-search', ['as' => 'generate_id_card_search', 'uses' => 'SmStudentIdCardController@generateIdCard']);
        Route::get('generate-id-card-print/{s_id}/{c_id}', 'SmStudentIdCardController@generateIdCardPrint');


        // Student Module /Student Admission
        Route::get('student-admission', ['as' => 'student_admission', 'uses' => 'SmStudentAdmissionController@admission'])->middleware('userRolePermission:62');
        Route::get('student-admission-check/{id}', ['as' => 'student_admission_check', 'uses' => 'SmStudentAdmissionController@admissionCheck']);
        Route::get('student-admission-update-check/{val}/{id}', ['as' => 'student_admission_check_update', 'uses' => 'SmStudentAdmissionController@admissionCheckUpdate']);
        Route::post('student-admission-pic', ['as' => 'student_admission_pic', 'uses' => 'SmStudentAdmissionController@admissionPic']);

        // Ajax get vehicle
        Route::get('/academic-year-get-class', 'SmStudentAdmissionController@academicYearGetClass');

        // Ajax get vehicle
        Route::get('/ajaxGetVehicle', 'SmStudentAdmissionController@ajaxGetVehicle');

        // Ajax Section
        Route::get('/ajaxVehicleInfo', 'SmStudentAdmissionController@ajaxVehicleInfo');

        // Ajax Roll No
        Route::get('/ajax-get-roll-id', 'SmStudentAdmissionController@ajaxGetRollId');

        // Ajax Roll exist check
        Route::get('/ajax-get-roll-id-check', 'SmStudentAdmissionController@ajaxGetRollIdCheck');

        // Ajax Section
        Route::get('/ajaxSectionStudent', 'SmStudentAdmissionController@ajaxSectionStudent');

        // Ajax room details
        Route::get('/ajaxRoomDetails', 'SmStudentAdmissionController@ajaxRoomDetails');
        //student store
        Route::post('student-store', ['as' => 'student_store', 'uses' => 'SmStudentAdmissionController@studentStore'])->middleware('userRolePermission:65');

        //Student details document

        Route::get('delete-document/{id}', ['as' => 'delete_document', 'uses' => 'SmStudentAdmissionController@deleteDocument'])->middleware('userRolePermission:18');
        Route::post('upload-document', ['as' => 'upload_document', 'uses' => 'SmStudentAdmissionController@uploadDocument']);



        Route::get('download-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/student/document/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-document');





        // Student timeline upload
        Route::post('student-timeline-store', ['as' => 'student_timeline_store', 'uses' => 'SmStudentAdmissionController@studentTimelineStore']);
        //parent
        Route::get('parent-download-timeline-doc/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/student/timeline/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
            return redirect()->back();
        })->name('parent-download-timeline-doc');

        Route::get('delete-timeline/{id}', ['as' => 'delete_timeline', 'uses' => 'SmStudentAdmissionController@deleteTimeline']);


        //student import
        Route::get('import-student', ['as' => 'import_student', 'uses' => 'SmStudentAdmissionController@importStudent'])->middleware('userRolePermission:63');
        Route::get('download_student_file', ['as' => 'download_student_file', 'uses' => 'SmStudentAdmissionController@downloadStudentFile']);
        Route::post('student-bulk-store', ['as' => 'student_bulk_store', 'uses' => 'SmStudentAdmissionController@studentBulkStore']);

        //Ajax Sibling section
        Route::get('ajaxSectionSibling', 'SmStudentAdmissionController@ajaxSectionSibling');

        //Ajax Sibling info
        Route::get('ajaxSiblingInfo', 'SmStudentAdmissionController@ajaxSiblingInfo');

        //Ajax Sibling info detail
        Route::get('ajaxSiblingInfoDetail', 'SmStudentAdmissionController@ajaxSiblingInfoDetail');

        
        // student list
        Route::get('student-list', ['as' => 'student_list', 'uses' => 'SmStudentAdmissionController@studentDetails'])->middleware('userRolePermission:64');
        // student search

        Route::post('student-list-search', 'SmStudentAdmissionController@studentDetailsSearch')->name('student-list-search');

        Route::get('student-list-search', 'SmStudentAdmissionController@studentDetails');

        // student list

        Route::get('student-view/{id}', ['as' => 'student_view', 'uses' => 'SmStudentAdmissionController@studentView']);

        // student delete
        Route::post('student-delete', 'SmStudentAdmissionController@studentDelete')->name('student-delete')->middleware('userRolePermission:67');


        // student edit
        Route::get('student-edit/{id}', ['as' => 'student_edit', 'uses' => 'SmStudentAdmissionController@studentEdit'])->middleware('userRolePermission:66');
        // Student Update
        Route::post('student-update', ['as' => 'student_update', 'uses' => 'SmStudentAdmissionController@studentUpdate']);
        // Route::post('student-update-pic/{id}', ['as' => 'student_update_pic', 'uses' => 'SmStudentAdmissionController@studentUpdatePic']);

        // Student Promote search
        Route::get('student-promote', ['as' => 'student_promote', 'uses' => 'SmStudentAdmissionController@studentPromote'])->middleware('userRolePermission:81');

        Route::get('student-current-search', 'SmStudentAdmissionController@studentPromote');
        Route::post('student-current-search', 'SmStudentAdmissionController@studentCurrentSearch')->name('student-current-search');

        Route::get('student-current-search-custom', 'SmStudentAdmissionController@studentPromoteCustom');
        Route::post('student-current-search-custom', 'SmStudentAdmissionController@studentCurrentSearchCustom')->name('student-current-search-custom');

        Route::get('view-academic-performance/{id}', 'SmStudentAdmissionController@view_academic_performance');


        // // Student Promote Store
        Route::get('student-promote-store', 'SmStudentAdmissionController@studentPromote');
        Route::post('student-promote-store', 'SmStudentAdmissionController@studentPromoteStore')->name('student-promote-store')->middleware('userRolePermission:82');

        // // Student Promote Store Custom
        Route::get('student-promote-store-custom', 'SmStudentAdmissionController@studentPromoteCustom');
        Route::post('student-promote-store-custom', 'SmStudentAdmissionController@studentPromoteCustomStore')->name('student-promote-store-custom')->middleware('userRolePermission:82');

        //Ajax Student Promote Section
        Route::get('ajaxStudentPromoteSection', 'SmStudentAdmissionController@ajaxStudentPromoteSection');
        Route::get('ajax-get-class', 'SmStudentAdmissionController@ajaxGetClass');
        Route::get('SearchMultipleSection', 'SmStudentAdmissionController@SearchMultipleSection');
        //Ajax Student Select
        Route::get('ajaxSelectStudent', 'SmStudentAdmissionController@ajaxSelectStudent');

        Route::get('promote-year/{id?}', 'SmStudentAdmissionController@ajaxPromoteYear');

        // Student Attendance
        Route::get('student-attendance', ['as' => 'student_attendance', 'uses' => 'SmStudentAttendanceController@index'])->middleware('userRolePermission:68');
        Route::post('student-search', 'SmStudentAttendanceController@studentSearch')->name('student-search');
        Route::get('student-search', 'SmStudentAttendanceController@index');

        Route::post('student-attendance-store', 'SmStudentAttendanceController@studentAttendanceStore')->name('student-attendance-store')->middleware('userRolePermission:69');
        Route::post('student-attendance-holiday', 'SmStudentAttendanceController@studentAttendanceHoliday')->name('student-attendance-holiday');


        Route::get('student-attendance-import', 'SmStudentAttendanceController@studentAttendanceImport')->name('student-attendance-import');
        Route::get('download-student-attendance-file', 'SmStudentAttendanceController@downloadStudentAtendanceFile');
        Route::post('student-attendance-bulk-store', 'SmStudentAttendanceController@studentAttendanceBulkStore')->name('student-attendance-bulk-store');

        //Student Report
        Route::get('student-report', ['as' => 'student_report', 'uses' => 'SmStudentAdmissionController@studentReport'])->middleware('userRolePermission:538');
        Route::post('student-report', ['as' => 'student_report', 'uses' => 'SmStudentAdmissionController@studentReportSearch']);


        //guardian report
        Route::get('guardian-report', ['as' => 'guardian_report', 'uses' => 'SmStudentAdmissionController@guardianReport'])->middleware('userRolePermission:377');
        Route::post('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'SmStudentAdmissionController@guardianReportSearch']);
        Route::get('guardian-report-search', ['as' => 'guardian_report_search', 'uses' => 'SmStudentAdmissionController@guardianReport']);

        Route::get('student-history', ['as' => 'student_history', 'uses' => 'SmStudentAdmissionController@studentHistory'])->middleware('userRolePermission:378');
        Route::post('student-history-search', ['as' => 'student_history_search', 'uses' => 'SmStudentAdmissionController@studentHistorySearch']);
        Route::get('student-history-search', ['as' => 'student_history_search', 'uses' => 'SmStudentAdmissionController@studentHistory']);


        // student login report
        Route::get('student-login-report', ['as' => 'student_login_report', 'uses' => 'SmStudentAdmissionController@studentLoginReport'])->middleware('userRolePermission:379');
        Route::post('student-login-search', ['as' => 'student_login_search', 'uses' => 'SmStudentAdmissionController@studentLoginSearch']);
        Route::get('student-login-search', ['as' => 'student_login_search', 'uses' => 'SmStudentAdmissionController@studentLoginReport']);

        // student & parent reset password
        Route::post('reset-student-password', 'SmResetPasswordController@resetStudentPassword')->name('reset-student-password');


        // Disabled Student
        Route::get('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmStudentAdmissionController@disabledStudent'])->middleware('userRolePermission:83');

        Route::post('disabled-student', ['as' => 'disabled_student', 'uses' => 'SmStudentAdmissionController@disabledStudentSearch']);
        Route::post('disabled-student-delete', ['as' => 'disable_student_delete', 'uses' => 'SmStudentAdmissionController@disabledStudentDelete'])->middleware('userRolePermission:86');
        Route::post('enable-student', ['as' => 'enable_student', 'uses' => 'SmStudentAdmissionController@enableStudent'])->middleware('userRolePermission:86');


        Route::get('student-report-search', 'SmStudentAdmissionController@studentReport');

        Route::get('language-list', 'LanguageController@index')->name('language-list')->middleware('userRolePermission:549');
        Route::get('language-list/{id}', 'LanguageController@show')->name('language_edit')->middleware('userRolePermission:551');
        Route::post('language-list/update', 'LanguageController@update')->name('language_update')->middleware('userRolePermission:551');
        Route::post('language-list/store', 'LanguageController@store')->name('language_store')->middleware('userRolePermission:550');



        // Tabulation Sheet Report
        Route::get('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'SmReportController@tabulationSheetReport'])->middleware('userRolePermission:391');
        Route::post('tabulation-sheet-report', ['as' => 'tabulation_sheet_report', 'uses' => 'SmReportController@tabulationSheetReportSearch']);
        Route::post('tabulation-sheet/print', 'SmReportController@tabulationSheetReportPrint')->name('tabulation-sheet/print');

        Route::get('optional-subject-setup/delete/{id}', 'SmOptionalSubjectAssignController@optionalSetupDelete')->name('delete_optional_subject')->middleware('userRolePermission:427');
        Route::get('optional-subject-setup/edit/{id}', 'SmOptionalSubjectAssignController@optionalSetupEdit')->name('class_optional_edit')->middleware('userRolePermission:426');
        Route::get('optional-subject-setup', 'SmOptionalSubjectAssignController@optionalSetup')->name('class_optional')->middleware('userRolePermission:424');
        Route::post('optional-subject-setup', 'SmOptionalSubjectAssignController@optionalSetupStore')->name('optional_subject_setup_post')->middleware('userRolePermission:425');

        // progress card report
        Route::get('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'SmReportController@progressCardReport'])->middleware('userRolePermission:392');
        Route::post('progress-card-report', ['as' => 'progress_card_report', 'uses' => 'SmReportController@progressCardReportSearch']);


        Route::post('progress-card/print', 'SmReportController@progressCardPrint')->name('progress-card/print');


        // staff directory
        Route::get('staff-directory', ['as' => 'staff_directory', 'uses' => 'SmStaffController@staffList'])->middleware('userRolePermission:161');


        Route::post('search-staff', ['as' => 'searchStaff', 'uses' => 'SmStaffController@searchStaff']);

        Route::get('add-staff', ['as' => 'addStaff', 'uses' => 'SmStaffController@addStaff'])->middleware('userRolePermission:161');
        Route::post('staff-store', ['as' => 'staffStore', 'uses' => 'SmStaffController@staffStore']);
        Route::post('staff-pic-store', ['as' => 'staffPicStore', 'uses' => 'SmStaffController@staffPicStore'])->middleware('userRolePermission:163');


        Route::get('edit-staff/{id}', ['as' => 'editStaff', 'uses' => 'SmStaffController@editStaff'])->middleware('userRolePermission:163');
        Route::post('update-staff', ['as' => 'staffUpdate', 'uses' => 'SmStaffController@staffUpdate']);
        Route::post('staff-profile-update/{id}', ['as' => 'staffProfileUpdate', 'uses' => 'SmStaffController@staffProfileUpdate']);

        // Route::get('staff-roles', ['as' => 'viewStaff', 'uses' => 'SmStaffController@staffRoles']);
        Route::get('view-staff/{id}', ['as' => 'viewStaff', 'uses' => 'SmStaffController@viewStaff']);
        Route::get('delete-staff-view/{id}', ['as' => 'deleteStaffView', 'uses' => 'SmStaffController@deleteStaffView']);

        Route::get('deleteStaff/{id}', 'SmStaffController@deleteStaff')->name('deleteStaff')->middleware('userRolePermission:164');
        Route::get('staff-disable-enable', 'SmStaffController@staffDisableEnable');

        Route::get('upload-staff-documents/{id}', 'SmStaffController@uploadStaffDocuments');
        Route::post('save_upload_document', 'SmStaffController@saveUploadDocument')->name('save_upload_document');
        Route::get('download-staff-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/staff/document/' . $file_name;
            // dd($file);
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-staff-document');

        Route::get('download-staff-joining-letter/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/staff_joining_letter/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-staff-joining-letter');

        Route::get('download-resume/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/resume/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-resume');

        Route::get('download-other-document/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/others_documents/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-other-document');

        Route::get('download-staff-timeline-doc/{file_name}', function ($file_name = null) {
            $file = public_path() . '/uploads/staff/timeline/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-staff-timeline-doc');

        Route::get('delete-staff-document-view/{id}', 'SmStaffController@deleteStaffDocumentView')->name('delete-staff-document-view');
        Route::get('delete-staff-document/{id}', 'SmStaffController@deleteStaffDocument')->name('delete-staff-document');

        // staff timeline
        Route::get('add-staff-timeline/{id}', 'SmStaffController@addStaffTimeline');
        Route::post('staff_timeline_store', 'SmStaffController@storeStaffTimeline')->name('staff_timeline_store');
        Route::get('delete-staff-timeline-view/{id}', 'SmStaffController@deleteStaffTimelineView')->name('delete-staff-timeline-view');
        Route::get('delete-staff-timeline/{id}', 'SmStaffController@deleteStaffTimeline')->name('delete-staff-timeline');


        //Staff Attendance
        Route::get('staff-attendance', ['as' => 'staff_attendance', 'uses' => 'SmStaffAttendanceController@staffAttendance'])->middleware('userRolePermission:165');
        Route::post('staff-attendance-search', 'SmStaffAttendanceController@staffAttendanceSearch')->name('staff-attendance-search');
        Route::post('staff-attendance-store', 'SmStaffAttendanceController@staffAttendanceStore')->name('staff-attendance-store')->middleware('userRolePermission:166');

        Route::get('staff-attendance-report', ['as' => 'staff_attendance_report', 'uses' => 'SmStaffAttendanceController@staffAttendanceReport'])->middleware('userRolePermission:169');
        Route::post('staff-attendance-report-search', ['as' => 'staff_attendance_report_search', 'uses' => 'SmStaffAttendanceController@staffAttendanceReportSearch']);

        Route::get('staff-attendance/print/{role_id}/{month}/{year}/', 'SmStaffAttendanceController@staffAttendancePrint')->name('staff-attendance/print');


        // Biometric attendance
        Route::post('attendance', 'SmStaffAttendanceController@attendanceData')->name('attendanceData');



        Route::get('staff-attendance-import', 'SmStaffAttendanceController@staffAttendanceImport')->name('staff-attendance-import');
        Route::get('download-staff-attendance-file', 'SmStaffAttendanceController@downloadStaffAttendanceFile');
        Route::post('staff-attendance-bulk-store', 'SmStaffAttendanceController@staffAttendanceBulkStore')->name('staff-attendance-bulk-store');

        //payroll
        Route::get('payroll', ['as' => 'payroll', 'uses' => 'SmPayrollController@index'])->middleware('userRolePermission:170');

        Route::post('payroll', ['as' => 'payroll', 'uses' => 'SmPayrollController@searchStaffPayr'])->middleware('userRolePermission:173');

        Route::get('generate-Payroll/{id}/{month}/{year}', 'SmPayrollController@generatePayroll')->name('generate-Payroll')->middleware('userRolePermission:174');
        Route::post('save-payroll-data', ['as' => 'savePayrollData', 'uses' => 'SmPayrollController@savePayrollData'])->middleware('userRolePermission:175');

        Route::get('pay-payroll/{id}/{role_id}', 'SmPayrollController@paymentPayroll')->name('pay-payroll')->middleware('userRolePermission:176');
        Route::post('savePayrollPaymentData', ['as' => 'savePayrollPaymentData', 'uses' => 'SmPayrollController@savePayrollPaymentData']);
        Route::get('view-payslip/{id}', 'SmPayrollController@viewPayslip')->name('view-payslip')->middleware('userRolePermission:177');

        //payroll Report
        Route::get('payroll-report', 'SmPayrollController@payrollReport')->name('payroll-report')->middleware('userRolePermission:178');
        Route::post('search-payroll-report', ['as' => 'searchPayrollReport', 'uses' => 'SmPayrollController@searchPayrollReport']);
        Route::get('search-payroll-report', 'SmPayrollController@searchPayrollReport');

        //Homework
        Route::get('homework-list', ['as' => 'homework-list', 'uses' => 'SmHomeworkController@homeworkList'])->middleware('userRolePermission:280');

        Route::post('homework-list', ['as' => 'homework-list', 'uses' => 'SmHomeworkController@searchHomework'])->middleware('userRolePermission:280');
        Route::get('homework-edit/{id}', ['as' => 'homework_edit', 'uses' => 'SmHomeworkController@homeworkEdit'])->middleware('userRolePermission:282');
        Route::post('homework-update', ['as' => 'homework_update', 'uses' => 'SmHomeworkController@homeworkUpdate'])->middleware('userRolePermission:282');
        Route::get('homework-delete/{id}', ['as' => 'homework_delete', 'uses' => 'SmHomeworkController@homeworkDelete'])->middleware('userRolePermission:283');

        Route::get('add-homeworks', ['as' => 'add-homeworks', 'uses' => 'SmHomeworkController@addHomework'])->middleware('userRolePermission:278');
        Route::post('save-homework-data', ['as' => 'saveHomeworkData', 'uses' => 'SmHomeworkController@saveHomeworkData'])->middleware('userRolePermission:279');

        //Route::get('evaluation-homework/{class_id}/{section_id}', 'SmHomeworkController@evaluationHomework');

        Route::get('evaluation-homework/{class_id}/{section_id}/{homework_id}', 'SmHomeworkController@evaluationHomework')->name('evaluation-homework')->middleware('userRolePermission:281');
        Route::post('save-homework-evaluation-data', ['as' => 'save-homework-evaluation-data', 'uses' => 'SmHomeworkController@saveHomeworkEvaluationData']);
        Route::get('evaluation-report', 'SmHomeworkController@EvaluationReport')->name('evaluation-report')->middleware('userRolePermission:284');

        Route::get('evaluation-document/{file_name}', function ($file_name = null) {

            $file = public_path() . 'public/uploads/homework/' . $file_name;
            return $file;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('evaluation-document');

        Route::post('search-evaluation', ['as' => 'search-evaluation', 'uses' => 'SmHomeworkController@searchEvaluation']);
        // Route::get('search-evaluation', 'SmHomeworkController@EvaluationReport');
        Route::get('view-evaluation-report/{homework_id}', 'SmHomeworkController@viewEvaluationReport')->name('view-evaluation-report')->middleware('userRolePermission:285');

        //teacher
        Route::get('upload-content', 'SmTeacherController@uploadContentList')->name('upload-content')->middleware('userRolePermission:88');
        //
        Route::get('upload-content-edit/{id}', 'SmTeacherController@uploadContentEdit')->name('upload-content-edit');
        Route::get('upload-content-view/{id}', 'SmTeacherController@uploadContentView')->name('upload-content-view');
        //
        Route::post('save-upload-content', 'SmTeacherController@saveUploadContent')->name('save-upload-content')->middleware('userRolePermission:89');
        Route::post('update-upload-content', 'SmTeacherController@updateUploadContent')->name('update-upload-content');
        Route::get('delete-upload-content/{id}', 'SmTeacherController@deleteUploadContent')->name('delete-upload-content')->middleware('userRolePermission:95');

        Route::get('download-content-document/{file_name}', function ($file_name = null) {

            $file = public_path() . '/uploads/upload_contents/' . $file_name;
            if (file_exists($file)) {
                return Response::download($file);
            }
        })->name('download-content-document')->middleware('userRolePermission:94');

        Route::get('assignment-list', 'SmTeacherController@assignmentList')->name('assignment-list')->middleware('userRolePermission:92');
        Route::get('study-metarial-list', 'SmTeacherController@studyMetarialList');
        Route::get('syllabus-list', 'SmTeacherController@syllabusList')->name('syllabus-list')->middleware('userRolePermission:100');
        Route::get('other-download-list', 'SmTeacherController@otherDownloadList')->name('other-download-list')->middleware('userRolePermission:105');

        // Communicate
        Route::get('notice-list', 'SmCommunicateController@noticeList')->name('notice-list')->middleware('userRolePermission:287');
        Route::get('administrator-notice', 'SmCommunicateController@administratorNotice')->name('administrator-notice');
        Route::get('add-notice', 'SmCommunicateController@sendMessage')->name('add-notice');
        Route::post('save-notice-data', 'SmCommunicateController@saveNoticeData')->name('save-notice-data');
        Route::get('edit-notice/{id}', 'SmCommunicateController@editNotice')->name('edit-notice');
        Route::post('update-notice-data', 'SmCommunicateController@updateNoticeData')->name('update-notice-data');
        Route::get('delete-notice-view/{id}', 'SmCommunicateController@deleteNoticeView')->name('delete-notice-view')->middleware('userRolePermission:290');
        Route::get('send-email-sms-view', 'SmCommunicateController@sendEmailSmsView')->name('send-email-sms-view')->middleware('userRolePermission:291');
        Route::post('send-email-sms', 'SmCommunicateController@sendEmailSms')->name('send-email-sms')->middleware('userRolePermission:292');
        Route::get('email-sms-log', 'SmCommunicateController@emailSmsLog')->name('email-sms-log')->middleware('userRolePermission:293');
        Route::get('delete-notice/{id}', 'SmCommunicateController@deleteNotice')->name('delete-notice');

        Route::get('studStaffByRole', 'SmCommunicateController@studStaffByRole');


        //Event
        // Route::resource('event', 'SmEventController');
        Route::get('event', 'SmEventController@index')->name('event')->middleware('userRolePermission:294');
        Route::post('event', 'SmEventController@store')->name('event')->middleware('userRolePermission:295');
        Route::get('event/{id}', 'SmEventController@edit')->name('event-edit')->middleware('userRolePermission:296');
        Route::put('event/{id}', 'SmEventController@update')->name('event-update')->middleware('userRolePermission:296');

        Route::get('delete-event-view/{id}', 'SmEventController@deleteEventView')->name('delete-event-view')->middleware('userRolePermission:297');
        Route::get('delete-event/{id}', 'SmEventController@deleteEvent')->name('delete-event')->middleware('userRolePermission:297');

        //Holiday
        // Route::resource('holiday', 'SmHolidayController');
        Route::get('holiday', 'SmHolidayController@index')->name('holiday')->middleware('userRolePermission:440');
        Route::post('holiday', 'SmHolidayController@store')->name('holiday')->middleware('userRolePermission:441');
        Route::get('holiday/{id}/edit', 'SmHolidayController@edit')->name('holiday-edit')->middleware('userRolePermission:442');
        Route::put('holiday/{id}', 'SmHolidayController@update')->name('holiday-update')->middleware('userRolePermission:442');
        Route::get('delete-holiday-view/{id}', 'SmHolidayController@deleteHolidayView')->name('delete-holiday-view')->middleware('userRolePermission:442');
        Route::get('delete-holiday/{id}', 'SmHolidayController@deleteHoliday')->name('delete-holiday')->middleware('userRolePermission:442');

        // Route::resource('weekend', 'SmWeekendController');
        Route::get('weekend', 'SmWeekendController@index')->name('weekend')->middleware('userRolePermission:448');
        Route::post('weekend/switch', 'SmWeekendController@store')->name('weekend')->middleware('userRolePermission:448');
        Route::get('weekend/{id}', 'SmWeekendController@edit')->name('weekend-edit')->middleware('userRolePermission:449');
        Route::put('weekend/{id}', 'SmWeekendController@update')->name('weekend-update')->middleware('userRolePermission:450');

        //Book Category
        // Route::resource('book-category-list', 'SmBookCategoryController');
        Route::get('book-category-list', 'SmBookCategoryController@index')->name('book-category-list')->middleware('userRolePermission:304');
        Route::post('book-category-list', 'SmBookCategoryController@store')->name('book-category-list')->middleware('userRolePermission:305');
        Route::get('book-category-list/{id}', 'SmBookCategoryController@edit')->name('book-category-list-edit')->middleware('userRolePermission:306');
        Route::put('book-category-list/{id}', 'SmBookCategoryController@update')->name('book-category-list-update')->middleware('userRolePermission:306');
        Route::delete('book-category-list/{id}', 'SmBookCategoryController@destroy')->name('book-category-list-delete')->middleware('userRolePermission:3047');
        
        Route::get('delete-book-category-view/{id}', 'SmBookCategoryController@deleteBookCategoryView');
        Route::get('delete-book-category/{id}', 'SmBookCategoryController@deleteBookCategory')->name('delete-book-category');

        // Book
        Route::get('book-list', 'SmBookController@index')->name('book-list')->middleware('userRolePermission:301');
        Route::get('add-book', 'SmBookController@addBook')->name('add-book')->middleware('userRolePermission:299');
        Route::post('save-book-data', 'SmBookController@saveBookData')->name('save-book-data')->middleware('userRolePermission:300');
        Route::get('edit-book/{id}', 'SmBookController@editBook')->name('edit-book');
        Route::post('update-book-data/{id}', 'SmBookController@updateBookData')->name('update-book-data');
        Route::get('delete-book-view/{id}', 'SmBookController@deleteBookView')->name('delete-book-view')->middleware('userRolePermission:303');
        Route::get('delete-book/{id}', 'SmBookController@deleteBook');
        Route::get('member-list', 'SmBookController@memberList')->name('member-list')->middleware('userRolePermission:311');
        Route::get('issue-books/{member_type}/{id}', 'SmBookController@issueBooks')->name('issue-books');
        Route::post('save-issue-book-data', 'SmBookController@saveIssueBookData')->name('save-issue-book-data');
        Route::get('return-book-view/{id}', 'SmBookController@returnBookView')->name('return-book-view')->middleware('userRolePermission:313');
        Route::get('return-book/{id}', 'SmBookController@returnBook')->name('return-book');
        Route::get('all-issed-book', 'SmBookController@allIssuedBook')->name('all-issed-book')->middleware('userRolePermission:314');
        Route::post('search-issued-book', 'SmBookController@searchIssuedBook')->name('search-issued-book');
        Route::get('search-issued-book', 'p@allIssuedBook');


          // Library Subject routes
          Route::get('library-subject', ['as' => 'library_subject', 'uses' => 'SmBookController@subjectList']);
          Route::post('library-subject-store', ['as' => 'library_subject_store', 'uses' => 'SmBookController@store']);
          Route::get('library-subject-edit/{id}', ['as' => 'library_subject_edit', 'uses' => 'SmBookController@edit']);
          Route::post('library-subject-update', ['as' => 'library_subject_update', 'uses' => 'SmBookController@update']);
          Route::get('library-subject-delete/{id}', ['as' => 'library_subject_delete', 'uses' => 'SmBookController@delete']);
        //library member
        // Route::resource('library-member', 'SmLibraryMemberController');
        Route::get('library-member', 'SmLibraryMemberController@index')->name('library-member')->middleware('userRolePermission:308');
        Route::post('library-member', 'SmLibraryMemberController@store')->name('library-member')->middleware('userRolePermission:309');

        Route::get('cancel-membership/{id}', 'SmLibraryMemberController@cancelMembership')->name('cancel-membership')->middleware('userRolePermission:310');


        // Ajax Subject in dropdown by section change
        Route::get('ajaxSubjectDropdown', 'AcademicController@ajaxSubjectDropdown');
        Route::post('/language-change', 'SmSystemSettingController@ajaxLanguageChange');

        // Route::get('localization/{locale}','SmLocalizationController@index');


        //inventory
        // Route::resource('item-category', 'SmItemCategoryController');
        Route::get('item-category', 'SmItemCategoryController@index')->name('item-category')->middleware('userRolePermission:316');
        Route::post('item-category', 'SmItemCategoryController@store')->name('item-category')->middleware('userRolePermission:317');
        Route::get('item-category/{id}', 'SmItemCategoryController@edit')->name('item-category-edit')->middleware('userRolePermission:318');
        Route::put('item-category/{id}', 'SmItemCategoryController@update')->name('item-category-update')->middleware('userRolePermission:318');
        
        Route::get('delete-item-category-view/{id}', 'SmItemCategoryController@deleteItemCategoryView')->name('delete-item-category-view')->middleware('userRolePermission:319');
        Route::get('delete-item-category/{id}', 'SmItemCategoryController@deleteItemCategory')->name('delete-item-category')->middleware('userRolePermission:319');
        
        // Route::resource('item-list', 'SmItemController');
        Route::get('item-list', 'SmItemController@index')->name('item-list')->middleware('userRolePermission:320');
        Route::post('item-list', 'SmItemController@store')->name('item-list')->middleware('userRolePermission:321');
        Route::get('item-list/{id}', 'SmItemController@edit')->name('item-list-edit')->middleware('userRolePermission:322');
        Route::put('item-list/{id}', 'SmItemController@update')->name('item-list-update')->middleware('userRolePermission:322');

        Route::get('delete-item-view/{id}', 'SmItemController@deleteItemView')->name('delete-item-view')->middleware('userRolePermission:323');
        Route::get('delete-item/{id}', 'SmItemController@deleteItem')->name('delete-item')->middleware('userRolePermission:323');
        // Route::resource('item-store', 'SmItemStoreController');
        Route::get('item-store', 'SmItemStoreController@index')->name('item-store')->middleware('userRolePermission:324');
        Route::post('item-store', 'SmItemStoreController@store')->name('item-store')->middleware('userRolePermission:325');
        Route::get('item-store/{id}', 'SmItemStoreController@edit')->name('item-store-edit')->middleware('userRolePermission:326');
        Route::put('item-store/{id}', 'SmItemStoreController@update')->name('item-store-update')->middleware('userRolePermission:326');

        Route::get('delete-store-view/{id}', 'SmItemStoreController@deleteStoreView')->name('delete-store-view')->middleware('userRolePermission:327');
        Route::get('delete-store/{id}', 'SmItemStoreController@deleteStore')->name('delete-store')->middleware('userRolePermission:327');
        
        Route::get('item-receive', 'SmItemReceiveController@itemReceive')->name('item-receive')->middleware('userRolePermission:332');
        Route::post('get-receive-item', 'SmItemReceiveController@getReceiveItem');
        Route::post('save-item-receive-data', 'SmItemReceiveController@saveItemReceiveData')->name('save-item-receive-data')->middleware('userRolePermission:333');
        Route::get('item-receive-list', 'SmItemReceiveController@itemReceiveList')->name('item-receive-list')->middleware('userRolePermission:334');
        Route::get('edit-item-receive/{id}', 'SmItemReceiveController@editItemReceive')->name('edit-item-receive')->middleware('userRolePermission:336');
        Route::post('update-edit-item-receive-data/{id}', 'SmItemReceiveController@updateItemReceiveData')->name('update-edit-item-receive-data')->middleware('userRolePermission:336');
        Route::post('delete-receive-item', 'SmItemReceiveController@deleteReceiveItem');
        Route::get('view-item-receive/{id}', 'SmItemReceiveController@viewItemReceive')->name('view-item-receive');
        Route::get('add-payment/{id}', 'SmItemReceiveController@itemReceivePayment')->name('add-payment');
        Route::post('save-item-receive-payment', 'SmItemReceiveController@saveItemReceivePayment')->name('save-item-receive-payment');
        Route::get('view-receive-payments/{id}', 'SmItemReceiveController@viewReceivePayments')->name('view-receive-payments')->middleware('userRolePermission:337');
        Route::post('delete-receive-payment', 'SmItemReceiveController@deleteReceivePayment');
        Route::get('delete-item-receive-view/{id}', 'SmItemReceiveController@deleteItemReceiveView')->name('delete-item-receive-view')->middleware('userRolePermission:338');
        Route::get('delete-item-receive/{id}', 'SmItemReceiveController@deleteItemReceive')->name('delete-item-receive');
        Route::get('delete-item-sale-view/{id}', 'SmItemReceiveController@deleteItemSaleView')->name('delete-item-sale-view')->middleware('userRolePermission:342');
        Route::get('delete-item-sale/{id}', 'SmItemReceiveController@deleteItemSale');
        Route::get('cancel-item-receive-view/{id}', 'SmItemReceiveController@cancelItemReceiveView')->name('cancel-item-receive-view');
        Route::get('cancel-item-receive/{id}', 'SmItemReceiveController@cancelItemReceive')->name('cancel-item-receive');

        // Item Sell in inventory
        Route::get('item-sell-list', 'SmItemSellController@itemSellList')->name('item-sell-list')->middleware('userRolePermission:339');
        Route::get('item-sell', 'SmItemSellController@itemSell')->name('item-sell')->middleware('userRolePermission:340');
        Route::post('save-item-sell-data', 'SmItemSellController@saveItemSellData')->name('save-item-sell-data');

        Route::post('check-product-quantity', 'SmItemSellController@checkProductQuantity');
        Route::get('edit-item-sell/{id}', 'SmItemSellController@editItemSell')->name('edit-item-sell')->middleware('userRolePermission:341');

        Route::post('update-item-sell-data', 'SmItemSellController@UpdateItemSellData')->name('update-item-sell-data');



        Route::get('item-issue', 'SmItemSellController@itemIssueList')->name('item-issue')->middleware('userRolePermission:345');
        Route::post('save-item-issue-data', 'SmItemSellController@saveItemIssueData')->name('save-item-issue-data')->middleware('userRolePermission:346');
        Route::get('getItemByCategory', 'SmItemSellController@getItemByCategory');
        Route::get('return-item-view/{id}', 'SmItemSellController@returnItemView')->name('return-item-view')->middleware('userRolePermission:347');
        Route::get('return-item/{id}', 'SmItemSellController@returnItem')->name('return-item');

        Route::get('view-item-sell/{id}', 'SmItemSellController@viewItemSell')->name('view-item-sell');
        Route::get('view-item-sell-print/{id}', 'SmItemSellController@viewItemSellPrint')->name('view-item-sell-print');

        Route::get('add-payment-sell/{id}', 'SmItemSellController@itemSellPayment')->name('add-payment-sell')->middleware('userRolePermission:343');
        Route::post('save-item-sell-payment', 'SmItemSellController@saveItemSellPayment')->name('save-item-sell-payment');


        //Supplier
        // Route::resource('suppliers', 'SmSupplierController');
        Route::get('suppliers', 'SmSupplierController@index')->name('suppliers')->middleware('userRolePermission:328');
        Route::post('suppliers', 'SmSupplierController@store')->name('suppliers')->middleware('userRolePermission:329');
        Route::get('suppliers/{id}', 'SmSupplierController@edit')->name('suppliers-edit')->middleware('userRolePermission:330');
        Route::put('suppliers/{id}', 'SmSupplierController@update')->name('suppliers-update')->middleware('userRolePermission:330');
        Route::get('delete-supplier-view/{id}', 'SmSupplierController@deleteSupplierView')->name('delete-supplier-view')->middleware('userRolePermission:331');
        Route::get('delete-supplier/{id}', 'SmSupplierController@deleteSupplier')->name('delete-supplier')->middleware('userRolePermission:331');


        Route::get('view-sell-payments/{id}', 'SmItemSellController@viewSellPayments')->name('view-sell-payments')->middleware('userRolePermission:344');


        Route::post('delete-sell-payment', 'SmItemSellController@deleteSellPayment');
        Route::get('cancel-item-sell-view/{id}', 'SmItemSellController@cancelItemSellView')->name('cancel-item-sell-view');
        Route::get('cancel-item-sell/{id}', 'SmItemSellController@cancelItemSell')->name('cancel-item-sell');


        //library member
        // Route::resource('library-member', 'SmLibraryMemberController');
        // Route::get('cancel-membership/{id}', 'SmLibraryMemberController@cancelMembership');


        //ajax theme change
        Route::get('theme-style-active', 'SmSystemSettingController@themeStyleActive');
        Route::get('theme-style-rtl', 'SmSystemSettingController@themeStyleRTL');
        Route::get('change-academic-year', 'SmSystemSettingController@sessionChange');

        // Sms Settings
        Route::get('sms-settings', 'SmSystemSettingController@smsSettings')->name('sms-settings')->middleware('userRolePermission:444');
        Route::post('update-clickatell-data', 'SmSystemSettingController@updateClickatellData')->name('update-clickatell-data');
        Route::post('update-twilio-data', 'SmSystemSettingController@updateTwilioData')->name('update-twilio-data')->middleware('userRolePermission:446');
        Route::post('update-msg91-data', 'SmSystemSettingController@updateMsg91Data')->name('update-msg91-data')->middleware('userRolePermission:447');
        Route::post('activeSmsService', 'SmSystemSettingController@activeSmsService');

        Route::post('update-textlocal-data', 'SmSystemSettingController@updateTextlocalData')->name('update-textlocal-data')->middleware('userRolePermission:446');

        Route::post('update-africatalking-data', 'SmSystemSettingController@updateAfricaTalkingData')->name('update-africatalking-data')->middleware('userRolePermission:446');


        //Language Setting
        Route::get('language-setup/{id}', 'SmSystemSettingController@languageSetup')->name('language-setup')->middleware('userRolePermission:453');
        Route::get('language-settings', 'SmSystemSettingController@languageSettings')->name('language-settings')->middleware('userRolePermission:451');
        Route::post('language-add', 'SmSystemSettingController@languageAdd')->name('language-add')->middleware('userRolePermission:452');

        Route::get('language-edit/{id}', 'SmSystemSettingController@languageEdit');
        Route::post('language-update', 'SmSystemSettingController@languageUpdate')->name('language-update');

        Route::post('language-delete', 'SmSystemSettingController@languageDelete')->name('language-delete')->middleware('userRolePermission:455');

        Route::get('get-translation-terms', 'SmSystemSettingController@getTranslationTerms');
        Route::post('translation-term-update', 'SmSystemSettingController@translationTermUpdate')->name('translation-term-update');


        //Backup Setting
        Route::post('backup-store', 'SmSystemSettingController@BackupStore')->name('backup-store')->middleware('userRolePermission:457');
        Route::get('backup-settings', 'SmSystemSettingController@backupSettings')->name('backup-settings')->middleware('userRolePermission:456');
        Route::get('get-backup-files/{id}', 'SmSystemSettingController@getfilesBackup')->name('get-backup-files')->middleware('userRolePermission:460');
        Route::get('get-backup-db', 'SmSystemSettingController@getDatabaseBackup')->name('get-backup-db')->middleware('userRolePermission:462');
        Route::get('download-database/{id}', 'SmSystemSettingController@downloadDatabase');
        Route::get('download-files/{id}', 'SmSystemSettingController@downloadFiles')->name('download-files')->middleware('userRolePermission:458');
        Route::get('restore-database/{id}', 'SmSystemSettingController@restoreDatabase')->name('restore-database');
        Route::get('delete-database/{id}', 'SmSystemSettingController@deleteDatabase')->name('delete_database')->middleware('userRolePermission:459');

        //Update System
        Route::get('about-system', 'SmSystemSettingController@AboutSystem')->name('about-system')->middleware('userRolePermission:477');


        Route::get('database-upgrade', 'SmSystemSettingController@databaseUpgrade')->name('database-upgrade');
        Route::get('update-system', 'SmSystemSettingController@UpdateSystem')->name('update-system')->middleware('userRolePermission:478');
        Route::post('admin/update-system', 'SmSystemSettingController@admin_UpdateSystem')->name('admin/update-system')->middleware('userRolePermission:479');
        Route::any('upgrade-settings', 'SmSystemSettingController@UpgradeSettings');


        //Route::get('sendSms','SmSmsTestController@sendSms');
        //Route::get('sendSmsMsg91','SmSmsTestController@sendSmsMsg91');
        //Route::get('sendSmsClickatell','SmSmsTestController@sendSmsClickatell');

        //Settings
        Route::get('general-settings', 'SmSystemSettingController@generalSettingsView')->name('general-settings')->middleware('userRolePermission:405');
        Route::get('update-general-settings', 'SmSystemSettingController@updateGeneralSettings')->name('update-general-settings')->middleware('userRolePermission:408');
        Route::post('update-general-settings-data', 'SmSystemSettingController@updateGeneralSettingsData')->name('update-general-settings-data')->middleware('userRolePermission:409');
        Route::post('update-school-logo', 'SmSystemSettingController@updateSchoolLogo')->name('update-school-logo')->middleware('userRolePermission:406');

        // payment Method Settings
        Route::get('payment-method-settings', 'SmSystemSettingController@paymentMethodSettings')->name('payment-method-settings')->middleware('userRolePermission:412');
        Route::post('update-paypal-data', 'SmSystemSettingController@updatePaypalData');
        Route::post('update-stripe-data', 'SmSystemSettingController@updateStripeData');
        Route::post('update-payumoney-data', 'SmSystemSettingController@updatePayumoneyData');
        Route::post('active-payment-gateway', 'SmSystemSettingController@activePaymentGateway');

        //Email Settings
        Route::get('email-settings', 'SmSystemSettingController@emailSettings')->name('email-settings')->middleware('userRolePermission:410');
        Route::post('update-email-settings-data', 'SmSystemSettingController@updateEmailSettingsData')->name('update-email-settings-data')->middleware('userRolePermission:411');


        Route::post('send-test-mail', 'SmSystemSettingController@sendTestMail')->name('send-test-mail');

        // payment Method Settings
        // Route::get('payment-method-settings', 'SmSystemSettingController@paymentMethodSettings');
       
        Route::post('is-active-payment', 'SmSystemSettingController@isActivePayment')->name('is-active-payment')->middleware('userRolePermission:413');
        //Route::get('stripeTest', 'SmSmsTestController@stripeTest');
        //Route::post('stripe_post', 'SmSmsTestController@stripePost');

        //Collect fees By Online Payment Gateway(Paypal)
        Route::get('collect-fees-gateway/{amount}/{student_id}/{type}', 'SmCollectFeesByPaymentGateway@collectFeesByGateway');
        Route::post('payByPaypal', 'SmCollectFeesByPaymentGateway@payByPaypal')->name('payByPaypal');
        Route::get('paypal-return-status', 'SmCollectFeesByPaymentGateway@getPaymentStatus');

        //Collect fees By Online Payment Gateway(Stripe)
        Route::get('collect-fees-stripe/{amount}/{student_id}/{type}', 'SmCollectFeesByPaymentGateway@collectFeesStripe');
        Route::post('collect-fees-stripe-strore', 'SmCollectFeesByPaymentGateway@stripeStore')->name('collect-fees-stripe-strore');

        // To Do list

        //Route::get('stripeTest', 'SmSmsTestController@stripeTest');
        //Route::post('stripe_post', 'SmSmsTestController@stripePost');


        // background setting
        Route::get('background-setting', 'SmBackgroundController@index')->name('background-setting')->middleware('userRolePermission:486');
        Route::post('background-settings-update', 'SmBackgroundController@backgroundSettingsUpdate')->name('background-settings-update');
        Route::post('background-settings-store', 'SmBackgroundController@backgroundSettingsStore')->name('background-settings-store')->middleware('userRolePermission:487');
        Route::get('background-setting-delete/{id}', 'SmBackgroundController@backgroundSettingsDelete')->name('background-setting-delete')->middleware('userRolePermission:488');
        Route::get('background_setting-status/{id}', 'SmBackgroundController@backgroundSettingsStatus')->name('background_setting-status')->middleware('userRolePermission:489');

        //color theme change
        Route::get('color-style', 'SmBackgroundController@colorTheme')->name('color-style')->middleware('userRolePermission:490');
        Route::get('make-default-theme/{id}', 'SmBackgroundController@colorThemeSet')->name('make-default-theme')->middleware('userRolePermission:491');


        Route::get('custom-result-setting', 'CustomResultSettingController@index')->name('custom-result-setting')->middleware('userRolePermission:436');
        Route::get('custom-result-setting/edit/{id}', 'CustomResultSettingController@edit')->name('custom-result-setting-edit')->middleware('userRolePermission:438');
        Route::DELETE('custom-result-setting/{id}', 'CustomResultSettingController@delete')->name('custom-result-setting-delete')->middleware('userRolePermission:438');
        Route::put('custom-result-setting/update/{id}', 'CustomResultSettingController@update')->name('custom-result-setting/update')->middleware('userRolePermission:438');
        Route::post('custom-result-setting/store', 'CustomResultSettingController@store')->name('custom-result-setting/store')->middleware('userRolePermission:437');

        //Custom Result
        Route::get('custom-merit-list', 'CustomResultSettingController@meritListReportIndex')->name('custom-merit-list')->middleware('userRolePermission:388');
        Route::get('custom-merit-list/print/{class}/{section}', 'CustomResultSettingController@meritListReportPrint')->name('custom-merit-list-print');
        Route::post('custom-merit-list', 'CustomResultSettingController@meritListReport')->name('custom-merit-list');

        Route::get('custom-progress-card', 'CustomResultSettingController@progressCardReportIndex')->name('custom-progress-card')->middleware('userRolePermission:392');
        Route::post('custom-progress-card', 'CustomResultSettingController@progressCardReport')->name('custom-progress-card');
        Route::post('custom-progress-card/print', 'CustomResultSettingController@progressCardReportPrint')->name('custom-progress-card-print');



        // login access control
        Route::get('login-access-control', 'SmLoginAccessControlController@loginAccessControl')->name('login-access-control')->middleware('userRolePermission:421');
        Route::post('login-access-control', 'SmLoginAccessControlController@searchUser')->name('login-access-control');
        Route::get('login-access-permission', 'SmLoginAccessControlController@loginAccessPermission');
        Route::get('login-password-reset', 'SmLoginAccessControlController@loginPasswordDefault');

        Route::get('button-disable-enable', 'SmSystemSettingController@buttonDisableEnable')->name('button-disable-enable')->middleware('userRolePermission:463');

        Route::get('manage-adons', 'SmAddOnsController@ManageAddOns')->name('manage-adons')->middleware('userRolePermission:399');
        Route::get('manage-adons-delete/{name}', 'SmAddOnsController@ManageAddOns')->name('deleteModule');
        Route::get('manage-adons-enable/{name}', 'SmAddOnsController@moduleAddOnsEnable')->name('moduleAddOnsEnable');
        Route::get('manage-adons-disable/{name}', 'SmAddOnsController@moduleAddOnsDisable')->name('moduleAddOnsDisable');

        Route::post('manage-adons-validation', 'SmAddOnsController@ManageAddOnsValidation')->name('ManageAddOnsValidation')->middleware('userRolePermission:400');
        Route::get('ModuleRefresh', 'SmAddOnsController@ModuleRefresh')->name('ModuleRefresh');
        Route::get('view-as-superadmin', 'SmSystemSettingController@viewAsSuperadmin')->name('viewAsSuperadmin');



        Route::get('/sms-template', 'SmSystemSettingController@SmsTemplate')->name('sms-template');
        Route::post('/sms-template/{id}', 'SmSystemSettingController@SmsTemplateStore')->name('sms-template-store')->middleware('userRolePermission:50');

        Route::get('/sms-template-new', 'SmSystemSettingController@SmsTemplateNew')->name('sms-template-new');
        Route::post('/sms-template-new', 'SmSystemSettingController@SmsTemplateNewStore')->name('sms-template-new');
    });
    Route::post('update-payment-gateway', 'SmSystemSettingController@updatePaymentGateway')->name('update-payment-gateway')->middleware('userRolePermission:414');
});