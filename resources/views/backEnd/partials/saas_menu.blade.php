<?php
    if (Auth::user() == "") { header('location:' . url('/login')); exit(); }

    Session::put('permission', App\GlobalVariable::GlobarModuleLinks());

    if(Module::find('FeesCollection')){
        $module = Module::find('FeesCollection');
        $module_name = @$module->getName();
        $module_status = @$module->isDisabled();
    }else{
        $module_name =NULL;
        $module_status =TRUE;
    }

// dd($main_modules);
?>
<input type="hidden" name="url" id="url" value="{{url('/')}}">
<nav id="sidebar">
    <div class="sidebar-header update_sidebar">
        <a href="{{url('/')}}">
            <img  src="{{ file_exists(@generalSetting()->logo) ? asset(generalSetting()->logo) : asset('public/uploads/settings/logo.png') }}" alt="logo">
        </a>
        <a id="close_sidebar" class="d-lg-none">
            <i class="ti-close"></i>
        </a>
    </div>
    {{-- {{ Auth::user()->role_id }} --}}
    <ul class="list-unstyled components">
 

    <li>

        @if(Auth::user()->role_id == 1)
            <a href="{{route('superadmin-dashboard')}}" id="admin-dashboard">
        @else
            <a href="{{route('admin-dashboard')}}" id="admin-dashboard">
        @endif
        
            <span class="flaticon-speedometer"></span>
            @lang('lang.dashboard')
        </a>
    </li>


    <li>
        <a href="#subMenuStudentRegistration" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <span class="flaticon-reading"></span>
            @lang('lang.registration')
        </a>
        <ul class="collapse list-unstyled" id="subMenuStudentRegistration">
           
            <li>
                <a href="{{route('parentregistration/saas-student-list')}}"> @lang('lang.student_list')</a>
            </li>
            <li>
                <a href="{{route('parentregistration/settings')}}"> @lang('lang.settings')</a>
            </li>
        </ul>
    </li>


    <li>
        <a href="#subMenuAdministrator" data-toggle="collapse" aria-expanded="false"
            class="dropdown-toggle">
            <span class="flaticon-analytics"></span>
            @lang('lang.institution')
            
        </a>
        <ul class="collapse list-unstyled" id="subMenuAdministrator">
            <li>
                <a href="{{route('administrator/institution-list')}}">@lang('lang.institution') @lang('lang.list')</a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#subMenuSubscription" data-toggle="collapse" aria-expanded="false"
            class="dropdown-toggle">
            <span class="flaticon-analytics"></span>
            @lang('lang.subscription')
            
        </a>
        <ul class="collapse list-unstyled" id="subMenuSubscription">
            <li>
                <a href="{{route('subscription/packages')}}">@lang('lang.package')</a>
            </li>
            <li>
                <a href="{{route('subscription/payment-method')}}">@lang('lang.payment') @lang('lang.method')</a>
            </li>
            <li>
                <a href="{{route('subscription/payment-method-setting')}}">@lang('lang.payment') @lang('lang.method') @lang('lang.setting')</a>
            </li>
            <li>
                <a href="{{route('subscription/settings')}}"> @lang('lang.settings')</a>
            </li>


            <li>
                <a href="{{route('TrailInstitution')}}"> @lang('lang.trial') @lang('lang.institutions')</a>
            </li>


            <li>
                <a href="{{route('subscription/school-payments')}}">  @lang('lang.all') @lang('lang.payments')</a>
            </li>

            <li>
                <a href="{{route('subscription/payment-history')}}">  @lang('lang.payment') @lang('lang.history')</a>
            </li>
        </ul>
    </li>
    
    
    
    {{-- <li>
    <a href="#subMenuPackages" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-analytics"></span>
        @lang('lang.packages')
    </a>
    <ul class="collapse list-unstyled" id="subMenuPackages">
        <li>
            <a href="{{url('administrator/package-list')}}"> @lang('lang.package_list')</a>
        </li>
    </ul>
    </li>
    
    <li>
    <a href="#subMenuInfixInvoice" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-accounting"></span> Invoice </a>
    <ul class="collapse list-unstyled" id="subMenuInfixInvoice">
        <li><a href="{{url('infix/invoice-create')}}">Invoice Create</a></li>
        <li><a href="{{url('infix/invoice-list')}}">Invoice list</a></li>
        <li><a href="{{url('infix/invoice-category')}}">Invoice Category</a></li>
        <li><a href="{{url('infix/invoice-setting')}}">Invoice Setting</a></li>
    
    </ul>
    </li> --}}
    
    <li>
    <a href="#subMenuCommunicate" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-email"></span>
        @lang('lang.communicate')
    </a>
    <ul class="collapse list-unstyled" id="subMenuCommunicate">
        <li>
            <a href="{{route('administrator/send-mail')}}">@lang('lang.send') @lang('lang.mail')</a>
            <a href="{{route('administrator/send-sms')}}">@lang('lang.send') @lang('lang.sms')</a>
            <a href="{{route('administrator/send-notice')}}">@lang('lang.send') @lang('lang.notice')</a>
        </li>
    </ul>
    </li>
    
    <li>
    <a href="#subMenuInfixInvoice" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-accounting"></span> Reports </a>
    <ul class="collapse list-unstyled" id="subMenuInfixInvoice">
        <li><a href="{{route('administrator/student-list')}}">@lang('lang.student') @lang('lang.list')</a></li>
        <li><a href="{{route('administrator/income-expense')}}">@lang('lang.income')/@lang('lang.expense')</a></li>
        <li><a href="{{route('administrator/teacher-list')}}">@lang('lang.teacher') @lang('lang.list')</a></li>
        <li><a href="{{route('administrator/class-list')}}">@lang('lang.class') @lang('lang.list')</a></li>
        <li><a href="{{route('administrator/class-routine')}}">@lang('lang.class') @lang('lang.routine')</a></li>
        <li><a href="{{route('administrator/student-attendance')}}">@lang('lang.student') @lang('lang.attendance')</a></li>
        <li><a href="{{route('administrator/staff-attendance')}}">@lang('lang.staff') @lang('lang.attendance')</a></li>
        <li><a href="{{route('administrator/merit-list-report')}}">@lang('lang.merit_list_report')</a></li>
        <li><a href="{{route('saas_mark_sheet_report_student')}}">@lang('lang.mark_sheet_report')</a></li>
        
        <li><a href="{{url('administrator/tabulation-sheet-report')}}">@lang('lang.tabulation_sheet_report')</a></li>
    
        <li><a href="{{route('administrator/progress-card-report')}}">Progress Card Report</a></li>
    </ul>
    </li>
    <li>
    <a href="#subMenusystemSettings" data-toggle="collapse" aria-expanded="false"
        class="dropdown-toggle">
        <span class="flaticon-settings"></span>
        @lang('lang.system_settings')
    </a>
    <ul class="collapse list-unstyled" id="subMenusystemSettings">
        
            <li>
                <a href="{{route('manage-adons')}}">@lang('lang.module') @lang('lang.manager')</a>
            </li>
            <li>
                <a href="{{route('administrator/general-settings')}}"> @lang('lang.general_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/email-settings')}}">@lang('lang.email_settings')</a>
            </li>
            <li>
                <a href="{{route('sms-settings')}}">@lang('lang.sms_settings')</a>
            </li>
    
            <li>
                <a href="{{route('administrator/manage-currency')}}">@lang('lang.manage-currency')</a>
            </li>
            
        
            {{-- <li>
                <a href="{{url('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
            </li> --}}
        
        
            {{-- <li>
                <a href="{{url('role')}}">@lang('lang.role')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{ url('administrator/module-permission')}}">@lang('lang.module') @lang('lang.permission')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('login-access-control')}}">@lang('lang.login_permission')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('administrator/base-group')}}">@lang('lang.base_group')</a>
            </li> --}}
        
            <li>
                <a href="{{route('base_setup')}}">@lang('lang.base_setup')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('academic-year')}}">@lang('lang.academic_year')</a>
            </li> --}}
        
            {{-- <li>
                <a href="{{url('session')}}">@lang('lang.session')</a>
            </li> --}}
            {{-- <li>
                <a href="{{url('sms-settings')}}">@lang('lang.sms_settings')</a>
            </li> --}}

            @if(moduleStatusCheck(152)) 
                            <li>
                    <a href="{{route('payment_method')}}"> @lang('lang.payment_method')</a>
                </li>
            @endif

            <li>
                <a href="{{route('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
            </li>
            {{-- <li>
                <a href="{{url('email-settings')}}">@lang('lang.email_settings')</a>
            </li> --}}
            <li>
                <a href="{{route('language-list')}}">@lang('lang.language')</a>
            </li>
            <li>
                <a href="{{route('administrator/language-settings')}}">@lang('lang.language_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/backup-settings')}}">@lang('lang.backup_settings')</a>
            </li>
            <li>
                <a href="{{route('button-disable-enable')}}">@lang('lang.button') @lang('lang.manage') </a>
            </li>
            <li>
                <a href="{{route('templatesettings/email-template')}}">@lang('lang.email') @lang('lang.template')</a>
            </li>
            <li>
                <a href="{{route('sms-template')}}">@lang('lang.sms') @lang('lang.template')</a>
            </li>
            <li>
                <a href="{{route('about-system')}}">@lang('lang.about')</a>
            </li>
            <li>
                <a href="{{route('update-system')}}">@lang('lang.update')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('administrator/update-system')}}">@lang('lang.update_system')</a>
            </li> --}}
        
        {{-- @if(Auth::user()->role_id == 1)
            <li>
                <a href="{{url('administrator/admin-data-delete')}}">@lang('lang.SampleDataEmpty')</a>
            </li>
        @endif --}}
    
    </ul>
    </li>
    
    
    
    
                    <li>
                        <a href="#subMenusystemStyle" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-settings"></span>
                            @lang('lang.style')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenusystemStyle">
                                <li>
                                    <a href="{{route('background-setting')}}">@lang('lang.background_settings')</a>
                                    {{-- <a href="{{route('administrator/background-setting')}}">@lang('lang.background_settings')</a> --}}
                                </li>
                                <li>
                                    <a href="{{route('color-style')}}">@lang('lang.color') @lang('lang.theme')</a>
                                    {{-- <a href="{{url('administrator/color-style')}}">@lang('lang.color') @lang('lang.theme')</a> --}}
                                </li>
                        </ul>
                    </li>
    
                    <li>
                        <a href="#subMenuApi" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-settings"></span>
                            @lang('lang.api')
                            @lang('lang.permission')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenuApi">
                                <li>
                                    <a href="{{route('administrator/api/permission')}}">@lang('lang.api') @lang('lang.permission') </a>
                                </li>
                        </ul>
                    </li>
    
    
    
                    {{-- <li>
                        <a href="#subMenufrontEndSettings" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-software"></span>
                            @lang('lang.front_settings')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenufrontEndSettings">
                            <li>
                                <a href="{{url('admin-home-page')}}"> @lang('lang.home_page') </a>
                            </li>
    
                            <li>
                                <a href="{{url('news')}}">@lang('lang.news_list')</a>
                            </li>
                            <li>
                                <a href="{{url('news-category')}}">@lang('lang.news') @lang('lang.category')</a>
                            </li>
                            <li>
                                <a href="{{url('testimonial')}}">@lang('lang.testimonial')</a>
                            </li>
                            <li>
                                <a href="{{url('course-list')}}">@lang('lang.course_list')</a>
                            </li>
                            <li>
                                <a href="{{url('contact-page')}}">@lang('lang.contact') @lang('lang.page') </a>
                            </li>
                            <li>
                                <a href="{{url('contact-message')}}">@lang('lang.contact') @lang('lang.message')</a>
                            </li>
                            <li>
                                <a href="{{url('about-page')}}"> @lang('lang.about_us') </a>
                            </li>
                            <li>
                                <a href="{{url('custom-links')}}"> @lang('lang.custom_links') </a>
                            </li>
                        </ul>
                    </li> --}}
    
    
    <li>
    <a href="#Ticket" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-settings"></span>
        @lang('lang.ticket_system')
    </a>
    <ul class="collapse list-unstyled" id="Ticket">
        <li><a href="{{ route('ticket-category') }}"> @lang('lang.ticket_category')</a></li>
        <li><a href="{{ route('ticket-priority') }}">@lang('lang.ticket_priority')</a></li>
        <li><a href="{{ route('admin/ticket-view') }}">@lang('lang.ticket_list')</a> </li> 
    </ul>
    </li>
    
    {{-- SAAS -302 --}}


    
    
    
    

       
        
        
        
        
            


    </ul>
</nav>
