@php
$school_config = schoolConfig();
@endphp
<nav id="sidebar">

    <div class="sidebar-header update_sidebar">
        <a href="{{route('/')}}">
            @if(! is_null($school_config->logo))
            <img  src="{{ asset($school_config->logo)}}" alt="logo">
            @else
            <img  src="{{ asset('public/uploads/settings/logo.png')}}" alt="logo">
            @endif
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>
    </div>

    @if(Auth::user()->is_saas == 0)

        <ul class="list-unstyled components">
            @if(Auth::user()->role_id != 2 && Auth::user()->role_id != 3 )
                {{-- @dd(userPermission(1)); --}}

                @if(userPermission(1))

                    <li>
                        @if(moduleStatusCheck('Saas')== TRUE && Auth::user()->is_administrator=="yes" && Session::get('isSchoolAdmin')==FALSE && Auth::user()->role_id == 1)

                            <a href="{{route('superadmin-dashboard')}}" id="superadmin-dashboard">
                                @else
                                    {{-- @dd('yes'); --}}
                                    <a href="{{route('admin-dashboard')}}" id="admin-dashboard">
                                        @endif
                                        <span class="flaticon-speedometer"></span>
                                        @lang('lang.dashboard')
                                    </a>

                    </li>
                @endif
            @endif

            
            @if(moduleStatusCheck('InfixBiometrics')== TRUE && Auth::user()->role_id == 1)
                @include('infixbiometrics::menu.InfixBiometrics')
            @endif

            {{-- Parent Registration Menu --}}
            @if(moduleStatusCheck('ParentRegistration')== TRUE && Auth::user()->role_id ==1 && moduleStatusCheck('Saas')==False )
                @include('parentregistration::menu.ParentRegistration')
            @endif

            {{-- Saas Subscription Menu --}}
            @if(moduleStatusCheck('SaasSubscription')== TRUE && Auth::user()->is_administrator != "yes")
                @include('saassubscription::menu.SaasSubscriptionSchool')
            @endif
                 
            {{-- Saas Module Menu --}}
            @if(moduleStatusCheck('Saas')== TRUE && Auth::user()->is_administrator =="yes" && Session::get('isSchoolAdmin')==FALSE && Auth::user()->role_id == 1 )

                @include('saas::menu.Saas')
            @else

                @if(Auth::user()->role_id != 2 && Auth::user()->role_id != 3 )

                    {{-- admin_section --}}
                    @if(userPermission(11))
                        <li>
                            <a href="#subMenuAdmin" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-analytics"></span>
                                @lang('lang.admin_section')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuAdmin">
                                @if(userPermission(12))
                                    <li>
                                        <a href="{{route('admission_query')}}">@lang('lang.admission_query')</a>
                                    </li>
                                @endif
                                @if(userPermission(16))
                                    <li>
                                        <a href="{{route('visitor')}}">@lang('lang.visitor_book') </a>
                                    </li>
                                @endif
                                @if(userPermission(21))
                                    <li>
                                        <a href="{{route('complaint')}}">@lang('lang.complaint')</a>
                                    </li>
                                @endif
                                @if(userPermission(27))
                                    <li>
                                        <a href="{{route('postal-receive')}}">@lang('lang.postal_receive')</a>
                                    </li>
                                @endif
                                @if(userPermission(32))
                                    <li>
                                        <a href="{{route('postal-dispatch')}}">@lang('lang.postal_dispatch')</a>
                                    </li>
                                @endif
                                @if(userPermission(36))
                                    <li>
                                        <a href="{{route('phone-call')}}">@lang('lang.phone_call_log')</a>
                                    </li>
                                @endif
                                @if(userPermission(41))
                                    <li>
                                        <a href="{{route('setup-admin')}}">@lang('lang.admin_setup')</a>
                                    </li>
                                @endif
                                @if(userPermission(49))
                                    <li>
                                        <a href="{{route('student-certificate')}}">@lang('lang.student_certificate')</a>
                                    </li>
                                @endif
                                @if(userPermission(53))
                                    <li>
                                        <a href="{{route('generate_certificate')}}">@lang('lang.generate_certificate')</a>
                                    </li>
                                @endif
                                @if(userPermission(45))
                                    <li>
                                        <a href="{{route('student-id-card')}}">@lang('lang.student_id_card')</a>
                                    </li>
                                @endif
                                @if(userPermission(57))
                                    <li>
                                        <a href="{{route('generate_id_card')}}">@lang('lang.generate_id_card')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                    @endif


                    {{-- student_information --}}
                    @if(userPermission(61))
                        <li>
                            <a href="#subMenuStudent" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-reading"></span>
                                @lang('lang.student_information')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuStudent">

                                @if(userPermission(71))
                                    <li>
                                        <a href="{{route('student_category')}}"> @lang('lang.student_category')</a>
                                    </li>
                                @endif
                                @if(userPermission(62))
                                    <li>
                                        <a href="{{route('student_admission')}}">@lang('lang.add') @lang('lang.student')</a>
                                    </li>
                                @endif

                                @if(userPermission(64))
                                    <li>
                                        <a href="{{route('student_list')}}"> @lang('lang.student_list')</a>
                                    </li>
                                @endif


                                @if(userPermission(68))
                                    <li>
                                        <a href="{{route('student_attendance')}}"> @lang('lang.student_attendance')</a>
                                    </li>
                                @endif

                                @if(userPermission(70))
                                    <li>
                                        <a href="{{route('student_attendance_report')}}"> @lang('lang.student_attendance_report')</a>
                                    </li>
                                @endif

                                @if(userPermission(533))
                                    <li>
                                        <a href="{{route('subject-wise-attendance')}}"> @lang('lang.subject') @lang('lang.wise') @lang('lang.attendance') </a>
                                    </li>
                                @endif

                                @if(userPermission(535))

                                    <li>
                                        <a href="{{route('subject-attendance-report')}}"> @lang('lang.subject_attendance_report') </a>
                                    </li>
                                @endif

                                @if(userPermission(76))
                                    <li>
                                        <a href="{{route('student_group')}}">@lang('lang.student_group')</a>
                                    </li>
                                @endif

                                @if(userPermission(81))
                                    <li>
                                        <a href="{{route('student_promote')}}">@lang('lang.student_promote')</a>
                                    </li>
                                @endif

                                @if(userPermission(83))
                                    <li>
                                        <a href="{{route('disabled_student')}}">@lang('lang.disabled_student')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {{-- academics --}}
                    @if(userPermission(245))
                        <li>
                            <a href="#subMenuAcademic" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-graduated-student"></span>
                                @lang('lang.academics')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuAcademic">


                                @if(userPermission(537))
                                    <li>
                                        <a href="{{route('optional-subject')}}"> @lang('lang.optional') @lang('lang.subject') </a>
                                    </li>
                                @endif
                                @if(userPermission(265))
                                    <li>
                                        <a href="{{route('section')}}"> @lang('lang.section')</a>
                                    </li>
                                @endif
                                @if(userPermission(261))
                                    <li>
                                        <a href="{{route('class')}}"> @lang('lang.class')</a>
                                    </li>
                                @endif
                                @if(userPermission(257))
                                    <li>
                                        <a href="{{route('subject')}}"> @lang('lang.subjects')</a>
                                    </li>
                                @endif

                                @if(userPermission(253))
                                    <li>
                                        <a href="{{route('assign-class-teacher')}}"> @lang('lang.assign_class_teacher')</a>
                                    </li>
                                @endif
                                @if(userPermission(250))
                                    <li>
                                        <a href="{{route('assign_subject')}}"> @lang('lang.assign_subject')</a>
                                    </li>
                                @endif
                                @if(userPermission(269))
                                    <li>
                                        <a href="{{route('class-room')}}"> @lang('lang.class_room')</a>
                                    </li>
                                @endif
                                @if(userPermission(273))
                                    <li>
                                        <a href="{{route('class-time')}}"> @lang('lang.class_time_setup')</a>
                                    </li>
                                @endif
                                @if(userPermission(246))
                                    <li>
                                        <a href="{{route('class_routine_new')}}"> @lang('lang.class_routine')</a>

                                    </li>
                                @endif



                            <!-- only for teacher -->
                                @if(Auth::user()->role_id == 4)
                                    <li>
                                        <a href="{{route('view-teacher-routine')}}">@lang('lang.view') @lang('lang.class_routine')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif


                    {{-- study_material --}}
                    @if(userPermission(87))
                        <li>
                            <a href="#subMenuTeacher" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-professor"></span>
                                @lang('lang.study_material')
                            </a>

                            <ul class="collapse list-unstyled" id="subMenuTeacher">
                                @if(userPermission(88))
                                    <li>
                                        <a href="{{route('upload-content')}}"> @lang('lang.upload_content')</a>
                                    </li>
                                @endif

                                @if(userPermission(92))
                                    <li>
                                        <a href="{{route('assignment-list')}}">@lang('lang.assignment')</a>
                                    </li>
                                @endif


                                @if(userPermission(100))
                                    <li>
                                        <a href="{{route('syllabus-list')}}">@lang('lang.syllabus')</a>
                                    </li>
                                @endif

                                @if(userPermission(105))
                                    <li>
                                        <a href="{{route('other-download-list')}}">@lang('lang.other_download')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    {{-- fees_collection --}}

                    {{-- FeesCollection --}}
                    @if(moduleStatusCheck('FeesCollection')== TRUE)
                        @include('feescollection::menu.FeesCollection')
                    @else
                        @if(userPermission(108))
                            <li>
                                <a href="#subMenuFeesCollection" data-toggle="collapse" aria-expanded="false"
                                   class="dropdown-toggle">
                                    <span class="flaticon-wallet"></span>
                                    @lang('lang.fees_collection')
                                </a>
                                <ul class="collapse list-unstyled" id="subMenuFeesCollection">
                                    @if(userPermission(123))
                                        <li>
                                            <a href="{{route('fees_group')}}"> @lang('lang.fees_group')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(127))
                                        <li>
                                            <a href="{{route('fees_type')}}"> @lang('lang.fees_type')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(131))
                                        <li>
                                            <a href="{{route('fees-master')}}"> @lang('lang.fees_master')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(118))
                                        <li>
                                            <a href="{{route('fees_discount')}}"> @lang('lang.fees_discount')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(109))
                                        <li>
                                            <a href="{{route('collect_fees')}}"> @lang('lang.collect_fees')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(113))
                                        <li>
                                            <a href="{{route('search_fees_payment')}}"> @lang('lang.search_fees_payment')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(116))
                                        <li>
                                            <a href="{{route('search_fees_due')}}"> @lang('lang.search_fees_due')</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{route('bank-payment-slip')}}"> @lang('lang.bank')  @lang('lang.payment')</a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                    @endif
                    {{-- check module enble or not --}}

                    {{-- check module link permission --}}
                    {{-- accounts --}}
                    @if(userPermission(137))
                        <li>
                            <a href="#subMenuAccount" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-accounting"></span>
                                @lang('lang.accounts')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuAccount">
                                @if(userPermission(148))
                                    <li>
                                        <a href="{{route('chart-of-account')}}"> @lang('lang.chart_of_account')</a>
                                    </li>
                                @endif

                                @if(userPermission(156))
                                    <li>
                                        <a href="{{route('bank-account')}}"> @lang('lang.bank_account')</a>
                                    </li>
                                @endif
                                @if(userPermission(139))
                                    <li>
                                        <a href="{{route('add_income')}}"> @lang('lang.income')</a>
                                    </li>
                                @endif
                                @if(userPermission(138))
                                    <li>
                                        <a href="{{route('profit')}}"> @lang('lang.profit')</a>
                                    </li>
                                @endif

                                @if(userPermission(143))
                                    <li>
                                        <a href="{{route('add-expense')}}"> @lang('lang.expense')</a>
                                    </li>
                                @endif
                                @if(userPermission(147))
                                    <li>
                                        <a href="{{route('search_account')}}"> @lang('lang.search')</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif


                    {{-- human_resource --}}
                    @if(userPermission(160))
                        <li>
                            <a href="#subMenuHumanResource" data-toggle="collapse" aria-expanded="false"
                               class="dropdown-toggle">
                                <span class="flaticon-consultation"></span>
                                @lang('lang.human_resource')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuHumanResource">
                                @if(userPermission(180))
                                    <li>
                                        <a href="{{route('designation')}}"> @lang('lang.designation')</a>
                                    </li>
                                @endif
                                @if(userPermission(184))
                                    <li>
                                        <a href="{{route('department')}}"> @lang('lang.department')</a>
                                    </li>
                                @endif
                                @if(userPermission(161))
                                    <li>
                                        <a href="{{route('addStaff')}}"> @lang('lang.add')  @lang('lang.staff') </a>
                                    </li>
                                @endif
                                @if(userPermission(161))
                                    <li>
                                        <a href="{{route('staff_directory')}}"> @lang('lang.staff_directory')</a>
                                    </li>
                                @endif
                                @if(userPermission(165))
                                    <li>
                                        <a href="{{route('staff_attendance')}}"> @lang('lang.staff_attendance')</a>
                                    </li>
                                @endif
                                @if(userPermission(169))
                                    <li>
                                        <a href="{{route('staff_attendance_report')}}"> @lang('lang.staff_attendance_report')</a>
                                    </li>
                                @endif


                                @if(userPermission(170))
                                    <li>
                                        <a href="{{route('payroll')}}"> @lang('lang.payroll')</a>
                                    </li>
                                @endif
                                @if(userPermission(178))
                                    <li>
                                        <a href="{{route('payroll-report')}}"> @lang('lang.payroll_report')</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif



                    {{-- leave --}}
                    @if(userPermission(188))
                        <li>
                            <a href="#subMenuLeaveManagement" data-toggle="collapse" aria-expanded="false"
                               class="dropdown-toggle">
                                <span class="flaticon-slumber"></span>
                                @lang('lang.leave')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuLeaveManagement">
                                @if(userPermission(203))
                                    <li>
                                        <a href="{{route('leave-type')}}"> @lang('lang.leave_type')</a>
                                    </li>
                                @endif
                                @if(userPermission(199))
                                    <li>
                                        <a href="{{route('leave-define')}}"> @lang('lang.leave_define')</a>
                                    </li>
                                @endif
                                @if(userPermission(189))
                                    <li>
                                        <a href="{{route('approve-leave')}}">@lang('lang.approve_leave_request')</a>
                                    </li>
                                @endif
                                @if(userPermission(196))
                                    <li>
                                        <a href="{{route('pending-leave')}}">@lang('lang.pending_leave_request')</a>
                                    </li>
                                @endif
                                @if (Auth::user()->role_id!=1)
                                    
                                    @if(userPermission(193))
                                        <li>
                                            <a href="{{route('apply-leave')}}">@lang('lang.apply_leave')</a>
                                        </li>
                                    @endif
                                @endif


                            </ul>
                        </li>
                    @endif

                    @if(userPermission(207))
                        <li>
                            <a href="#subMenuExam" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-test"></span>
                                @lang('lang.examination')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuExam">
                                @if(userPermission(225))
                                    <li>
                                        <a href="{{route('marks-grade')}}"> @lang('lang.marks_grade')</a>
                                    </li>
                                @endif
                                @if(userPermission(208))
                                    <li>
                                        <a href="{{route('exam-time')}}"> @lang('lang.exam_time')</a>
                                    </li>
                                    <li>
                                        <a href="{{route('exam-type')}}"> @lang('lang.add_exam_type')</a>
                                    </li>
                                @endif
                                @if(userPermission(214))
                                    <li>
                                        <a href="{{route('exam')}}"> @lang('lang.exam_setup')</a>
                                    </li>
                                @endif


                                @if(userPermission(217))
                                    <li>
                                        <a href="{{route('exam_schedule')}}"> @lang('lang.exam_schedule')</a>
                                    </li>
                                @endif



                                @if(userPermission(225))
                                    <li>
                                        <a href="{{route('exam_attendance')}}"> @lang('lang.exam_attendance')</a>
                                    </li>
                                @endif

                                @if(userPermission(225))
                                    <li>
                                        <a href="{{route('marks_register')}}"> @lang('lang.marks_register')</a>
                                    </li>
                                @endif


                                @if(userPermission(229))
                                    <li>
                                        <a href="{{route('send_marks_by_sms')}}"> @lang('lang.send_marks_by_sms')</a>
                                    </li>
                                @endif
                                @if(userPermission(230))
                                    <li>
                                        <a href="{{route('question-group')}}">@lang('lang.question_group')</a>
                                    </li>
                                @endif
                                @if(userPermission(234))
                                    <li>
                                        <a href="{{route('question-bank')}}">@lang('lang.question_bank')</a>
                                    </li>
                                @endif
                                @if(userPermission(238))
                                    <li>
                                        <a href="{{route('online-exam')}}">@lang('lang.online_exam')</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif



                    @if(userPermission(277))

                        <li>
                            <a href="#subMenuHomework" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-book"></span>
                                @lang('lang.home_work')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuHomework">
                                @if(userPermission(278))
                                    <li>
                                        <a href="{{route('add-homeworks')}}"> @lang('lang.add_homework')</a>
                                    </li>
                                @endif
                                @if(userPermission(280))
                                    <li>
                                        <a href="{{route('homework-list')}}"> @lang('lang.homework_list')</a>
                                    </li>
                                @endif
                                @if(userPermission(284))
                                    <li>
                                        <a href="{{route('evaluation-report')}}"> @lang('lang.evaluation_report')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                    @endif

                    @if(userPermission(286))
                        <li>
                            <a href="#subMenuCommunicate" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-email"></span>
                                @lang('lang.communicate')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuCommunicate">
                                @if(userPermission(287))
                                    <li>
                                        <a href="{{route('notice-list')}}">@lang('lang.notice_board')</a>
                                    </li>
                                @endif

                                @if (@$config->Saas == 1 && Auth::user()->is_administrator != "yes" )

                                    <li>
                                        <a href="{{route('administrator-notice')}}">@lang('lang.administrator') @lang('lang.notice')</a>
                                    </li>

                                @endif

                                @if(userPermission(291))
                                    <li>
                                        <a href="{{route('send-email-sms-view')}}">@lang('lang.send_email')</a>
                                    </li>
                                @endif
                                @if(userPermission(293))
                                    <li>
                                        <a href="{{route('email-sms-log')}}">@lang('lang.email_sms_log')</a>
                                    </li>
                                @endif
                                @if(userPermission(294))
                                    <li>
                                        <a href="{{route('event')}}">@lang('lang.event')</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{route('sms-template-new')}}">@lang('lang.sms_template')</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(userPermission(298))
                        <li>
                            <a href="#subMenulibrary" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-book-1"></span>
                                @lang('lang.library')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenulibrary">
                                @if(userPermission(304))
                                    <li>
                                        <a href="{{route('book-category-list')}}"> @lang('lang.book_category')</a>
                                    </li>
                                @endif
                                {{--                            @if(userPermission(258))--}}

                                <li>
                                    <a href="{{route('library_subject')}}"> @lang('lang.subject')</a>
                                </li>
                                {{--                            @endif--}}
                                @if(userPermission(299))
                                    <li>
                                        <a href="{{route('add-book')}}"> @lang('lang.add_book')</a>
                                    </li>
                                @endif
                                @if(userPermission(301))
                                    <li>
                                        <a href="{{route('book-list')}}"> @lang('lang.book_list')</a>
                                    </li>
                                @endif

                                @if(userPermission(308))
                                    <li>
                                        <a href="{{route('library-member')}}"> @lang('lang.library_member')</a>
                                    </li>
                                @endif
                                @if(userPermission(311))
                                    <li>
                                        <a href="{{route('member-list')}}"> @lang('lang.member_list')</a>
                                    </li>
                                @endif
                                @if(userPermission(314))
                                    <li>
                                        <a href="{{route('all-issed-book')}}"> @lang('lang.all_issued_book')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(userPermission(315))
                        <li>
                            <a href="#subMenuInventory" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-inventory"></span>
                                @lang('lang.inventory')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuInventory">
                                @if(userPermission(316))
                                    <li>
                                        <a href="{{route('item-category')}}"> @lang('lang.item_category')</a>
                                    </li>
                                @endif
                                @if(userPermission(320))
                                    <li>
                                        <a href="{{route('item-list')}}"> @lang('lang.item_list')</a>
                                    </li>
                                @endif
                                @if(userPermission(324))
                                    <li>
                                        <a href="{{route('item-store')}}"> @lang('lang.item_store')</a>
                                    </li>
                                @endif
                                @if(userPermission(328))
                                    <li>
                                        <a href="{{route('suppliers')}}"> @lang('lang.supplier')</a>
                                    </li>
                                @endif
                                @if(userPermission(332))
                                    <li>
                                        <a href="{{route('item-receive')}}"> @lang('lang.item_receive')</a>
                                    </li>
                                @endif
                                @if(userPermission(334))
                                    <li>
                                        <a href="{{route('item-receive-list')}}"> @lang('lang.item_receive_list')</a>
                                    </li>
                                @endif
                                @if(userPermission(339))
                                    <li>
                                        <a href="{{route('item-sell-list')}}"> @lang('lang.item_sell')</a>
                                    </li>
                                @endif
                                @if(userPermission(345))
                                    <li>
                                        <a href="{{route('item-issue')}}"> @lang('lang.item_issue')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(userPermission(348))
                        <li>
                            <a href="#subMenuTransport" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-bus"></span>
                                @lang('lang.transport')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuTransport">
                                @if(userPermission(349))
                                    <li>
                                        <a href="{{route('transport-route')}}"> @lang('lang.routes')</a>
                                    </li>
                                @endif
                                @if(userPermission(353))
                                    <li>
                                        <a href="{{route('vehicle')}}"> @lang('lang.vehicle')</a>
                                    </li>
                                @endif
                                @if(userPermission(357))
                                    <li>
                                        <a href="{{route('assign-vehicle')}}"> @lang('lang.assign_vehicle')</a>
                                    </li>
                                @endif
                                @if(userPermission(361))
                                    <li>
                                        <a href="{{route('student_transport_report')}}"> @lang('lang.student_transport_report')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(userPermission(362))
                        <li>
                            <a href="#subMenuDormitory" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-hotel"></span>
                                @lang('lang.dormitory')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenuDormitory">
                                @if(userPermission(371))
                                    <li>
                                        <a href="{{route('room-type')}}"> @lang('lang.room_type')</a>
                                    </li>
                                @endif
                                @if(userPermission(367))
                                    <li>
                                        <a href="{{route('dormitory-list')}}"> @lang('lang.dormitory')</a>
                                    </li>
                                @endif
                                @if(userPermission(363))
                                    <li>
                                        <a href="{{route('room-list')}}"> @lang('lang.dormitory_rooms')</a>
                                    </li>
                                @endif


                                @if(userPermission(375))
                                    <li>
                                        <a href="{{route('student_dormitory_report')}}"> @lang('lang.student_dormitory_report')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(userPermission(376))
                        <li>
                            <a href="#subMenusystemReports" data-toggle="collapse" aria-expanded="false"
                               class="dropdown-toggle">
                                <span class="flaticon-analysis"></span>
                                @lang('lang.reports')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenusystemReports">
                                @if(userPermission(538))

                                    <li>
                                        <a href="{{route('student_report')}}">@lang('lang.student_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(377))
                                    <li>
                                        <a href="{{route('guardian_report')}}">@lang('lang.guardian_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(378))
                                    <li>
                                        <a href="{{route('student_history')}}">@lang('lang.student_history')</a>
                                    </li>
                                @endif
                                @if(userPermission(379))
                                    <li>
                                        <a href="{{route('student_login_report')}}">@lang('lang.student_login_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(381))
                                    <li>
                                        <a href="{{route('fees_statement')}}">@lang('lang.fees_statement')</a>
                                    </li>
                                @endif
                                @if(userPermission(382))
                                    <li>
                                        <a href="{{route('balance_fees_report')}}">@lang('lang.balance_fees_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(383))
                                    <li>
                                        <a href="{{route('transaction_report')}}">@lang('lang.transaction_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(384))
                                    <li>
                                        <a href="{{route('class_report')}}">@lang('lang.class_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(385))
                                    <li>
                                        <a href="{{route('class_routine_report')}}">@lang('lang.class_routine')</a>
                                    </li>
                                @endif
                                @if(userPermission(386))
                                    <li>
                                        <a href="{{route('exam_routine_report')}}">@lang('lang.exam_routine')</a>
                                    </li>
                                @endif
                                @if(userPermission(387))
                                    <li>
                                        <a href="{{route('teacher_class_routine_report')}}">@lang('lang.teacher') @lang('lang.class_routine')</a>
                                    </li>
                                @endif
                                @if(userPermission(388))
                                    <li>
                                        <a href="{{route('merit_list_report')}}">@lang('lang.merit_list_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(388))
                                    <li>
                                        <a href="{{route('custom-merit-list')}}">@lang('custom') @lang('lang.merit_list_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(389))
                                    <li>
                                        <a href="{{route('online_exam_report')}}">@lang('lang.online_exam_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(390))
                                    <li>
                                        <a href="{{route('mark_sheet_report_student')}}">@lang('lang.mark_sheet_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(391))
                                    <li>
                                        <a href="{{route('tabulation_sheet_report')}}">@lang('lang.tabulation_sheet_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(392))
                                    <li>
                                        <a href="{{route('progress_card_report')}}">@lang('lang.progress_card_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(392))
                                    <li>
                                        <a href="{{route('custom-progress-card')}}"> @lang('lang.custom') @lang('lang.progress_card_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(393))
                                    <li>
                                        <a href="{{route('student_fine_report')}}">@lang('lang.student_fine_report')</a>
                                    </li>
                                @endif
                                @if(userPermission(394))
                                    <li>
                                        <a href="{{route('user_log')}}">@lang('lang.user_log')</a>
                                    </li>
                                @endif
                                @if(userPermission(539))
                                    <li>
                                        <a href="{{route('previous-class-results')}}">@lang('lang.previous') @lang('lang.result') </a>
                                    </li>
                                @endif
                                @if(userPermission(540))
                                    <li>
                                        <a href="{{route('previous-record')}}">@lang('lang.previous') @lang('lang.record') </a>
                                    </li>
                                @endif
                                {{-- New Client report start --}}


                                @if(Auth::user()->role_id == 1)
                                    @if(moduleStatusCheck('ResultReports')== TRUE)
                                        {{-- ResultReports --}}
                                        <li>
                                            <a href="{{route('resultreports/cumulative-sheet-report')}}">@lang('lang.cumulative') @lang('lang.sheet') @lang('lang.report')</a>
                                        </li>

                                        <li>
                                            <a href="{{route('resultreports/continuous-assessment-report')}}">@lang('lang.contonuous') @lang('lang.assessment') @lang('lang.report')</a>
                                        </li>
                                        <li>

                                            <a href="{{route('resultreports/termly-academic-report')}}">@lang('lang.termly') @lang('lang.academic') @lang('lang.report')</a>
                                        </li>
                                        <li>

                                            <a href="{{route('resultreports/academic-performance-report')}}">@lang('lang.academic') @lang('lang.performance') @lang('lang.report')</a>
                                        </li>
                                        <li>

                                            <a href="{{route('resultreports/terminal-report-sheet')}}">@lang('lang.terminal') @lang('lang.report') @lang('lang.sheet')</a>
                                        </li>
                                        <li>

                                            <a href="{{route('resultreports/continuous-assessment-sheet')}}">@lang('lang.continuous') @lang('lang.assessment') @lang('lang.sheet')</a>
                                        </li>
                                        <li>

                                            <a href="{{route('resultreports/result-version-two')}}">@lang('lang.result') @lang('lang.version') V2</a>
                                        </li>
                                        <li>

                                            <a href="{{route('resultreports/result-version-three')}}">@lang('lang.result') @lang('lang.version') V3</a>
                                        </li>
                                        {{--End New result result report --}}
                                    @endif
                                @endif


                            </ul>
                        </li>
                    @endif
                    {{-- @if(moduleStatusCheck('Saas')== TRUE)

                    @else

                    @endif --}}

                    @if(userPermission(398))
                        <li>
                            <a href="#subMenusystemSettings" data-toggle="collapse" aria-expanded="false"
                               class="dropdown-toggle">
                                <span class="flaticon-settings"></span>
                                @lang('lang.system_settings')
                            </a>
                            <ul class="collapse list-unstyled" id="subMenusystemSettings">

                                @if((moduleStatusCheck('Saas')== TRUE) && (auth()->user()->is_administrator=="yes"))
                                    <li>
                                        <a href="{{route('school-general-settings')}}"> @lang('lang.general_settings')</a>
                                    </li>
                                @else
                                    @if(userPermission(405))

                                        <li>
                                            <a href="{{route('general-settings')}}"> @lang('lang.general_settings')</a>
                                        </li>
                                    @endif
                                @endif




                                @if(userPermission(417))

                                    <li>
                                        <a href="{{route('rolepermission/role')}}">@lang('lang.role')</a>
                                    </li>
                                @endif


                                @if(userPermission(421))

                                    <li>
                                        <a href="{{route('login-access-control')}}">@lang('lang.login_permission')</a>
                                    </li>
                                @endif
                                @if(userPermission(424))
                                    <li>
                                        <a href="{{route('class_optional')}}">@lang('lang.optional') @lang('lang.subject')</a>
                                    </li>
                                @endif


                                @if(userPermission(121))
                                    {{--    <li> <a href="{{route('base_group')}}">@lang('lang.base_group')</a> </li>--}}
                                @endif


                                @if(userPermission(432))
                                    <li>
                                        <a href="{{route('academic-year')}}">@lang('lang.academic_year')</a>
                                    </li>
                                @endif


                                @if(userPermission(436))
                                    <li>
                                        <a href="{{route('custom-result-setting')}}">@lang('lang.custom_result_setting')</a>
                                    </li>
                                @endif

                                @if(userPermission(440))

                                    <li>
                                        <a href="{{route('holiday')}}">@lang('lang.holiday')</a>
                                    </li>
                                @endif


                                @if(userPermission(448))

                                    <li>
                                        <a href="{{route('weekend')}}">@lang('lang.weekend')</a>
                                    </li>
                                @endif

                                @if(userPermission(412))

                                    <li>
                                        <a href="{{route('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
                                    </li>
                                @endif





                                {{-- SAAS DISABLE --}}

                                @if(moduleStatusCheck('Saas')== FALSE   )

                                    @include('backEnd/partials/without_saas_school_admin_menu')

                                @endif



                            </ul>
                        </li>
                    @endif

                    @if(moduleStatusCheck('Saas')== FALSE)
                        @if(userPermission(485))
                            <li>
                                <a href="#subMenusystemStyle" data-toggle="collapse" aria-expanded="false"
                                   class="dropdown-toggle">
                                    <span class="flaticon-settings"></span>
                                    @lang('lang.style')
                                </a>
                                <ul class="collapse list-unstyled" id="subMenusystemStyle">
                                    @if(userPermission(486))
                                        <li>
                                            <a href="{{route('background-setting')}}">@lang('lang.background_settings')</a>
                                        </li>
                                    @endif
                                    @if(userPermission(490))
                                        <li>
                                            <a href="{{route('color-style')}}">@lang('lang.color') @lang('lang.theme')</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif


                    @if(moduleStatusCheck('Saas')== FALSE)
                        @if(userPermission(492))

                            <li>
                                <a href="#subMenufrontEndSettings" data-toggle="collapse" aria-expanded="false"
                                   class="dropdown-toggle">
                                    <span class="flaticon-software"></span>
                                    @lang('lang.front_settings')
                                </a>
                                <ul class="collapse list-unstyled" id="subMenufrontEndSettings">
                                    @if(userPermission(493))
                                        <li>
                                            <a href="{{route('admin-home-page')}}"> @lang('lang.home_page') </a>
                                        </li>
                                    @endif
                                    @if(userPermission(795))
                                        <li>
                                            <a href="{{route('news_index')}}">@lang('lang.news_list')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(500))
                                        <li>
                                            <a href="{{route('news-category')}}">@lang('lang.news') @lang('lang.category')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(504))
                                        <li>
                                            <a href="{{route('testimonial_index')}}">@lang('lang.testimonial')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(509))
                                        <li>
                                            <a href="{{route('course-list')}}">@lang('lang.course_list')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(514))
                                        <li>
                                            <a href="{{route('conpactPage')}}">@lang('lang.contact') @lang('lang.page') </a>
                                        </li>
                                    @endif

                                    @if(userPermission(517))
                                        <li>
                                            <a href="{{route('contactMessage')}}">@lang('lang.contact') @lang('lang.message')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(520))
                                        <li>
                                            <a href="{{route('about-page')}}"> @lang('lang.about_us') </a>
                                        </li>
                                    @endif

                                    @if(userPermission(523))
                                        <li>
                                            <a href="{{route('news-heading-update')}}">@lang('lang.news_heading')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(525))
                                        <li>
                                            <a href="{{route('course-heading-update')}}">@lang('lang.course_heading')</a>
                                        </li>
                                    @endif

                                    @if(userPermission(527))
                                    <li>
                                        <a href="{{route('custom-links')}}"> @lang('lang.footer_widget') </a>
                                    </li>
                                    @endif

                                    @if(userPermission(529))
                                        <li>
                                            <a href="{{route('social-media')}}"> @lang('lang.social_media') </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if(moduleStatusCheck('Saas')== TRUE  && Auth::user()->is_administrator != "yes" )

                        <li>
                            <a href="#Ticket" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <span class="flaticon-settings"></span>
                                @lang('lang.ticket_system')
                            </a>
                            <ul class="collapse list-unstyled" id="Ticket">
                                <li><a href="{{ route('school/ticket-view') }}">@lang('lang.ticket_list')</a>
                                </li>
                            </ul>
                        </li>

                    @endif
                @endif

            <!-- Student Panel -->
                @if(Auth::user()->role_id == 2)
                <!-- Zoom Menu -->
                    {{-- @if(moduleStatusCheck('Zoom') == TRUE)
                        @include('zoom::menu.Zoom')
                    @endif --}}
                    @include('backEnd/partials/student_sidebar')
                @endif
            <!-- End student panel -->

                <!-- Parents Panel Menu -->
                @if(Auth::user()->role_id == 3)
               
                    @include('backEnd/partials/parents_sidebar')
                @endif
            <!-- End Parents Panel Menu -->

                <!-- Zoom Menu -->
                @if(moduleStatusCheck('Zoom') == TRUE)
                    @include('zoom::menu.Zoom')
                @endif
            <!-- End Zoom Menu -->

            @endif
        </ul>
    @endif
       
    @if(Auth::user()->is_saas == 1)
        
        @include('saasrolepermission::menu.SaasAdminMenu')
    @endif

    @if(Auth::user()->is_saas == 1 && Auth::user()->role_id != 1)
        <ul class="list-unstyled components">
            <li>
                <a href="{{route('saas/institution-list')}}" id="superadmin-dashboard">
                    <span class="flaticon-analytics"></span>
                    institution List
                </a>
            </li>
        </ul>
    @endif


</nav>
