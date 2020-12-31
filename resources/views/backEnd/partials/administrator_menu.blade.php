

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
                <a href="{{route('administrator/general-settings')}}"> @lang('lang.general_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/email-settings')}}">@lang('lang.email_settings')</a>
            </li>
    
            <li>
                <a href="{{route('administrator/manage-currency')}}">@lang('lang.manage-currency')</a>
            </li>
            
        
            {{-- <li>
                <a href="{{url('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
            </li> --}}
        
        
            {{-- <li>
                <a href="{{route('role')}}">@lang('lang.role')</a>
            </li> --}}
        
            <li>
                <a href="{{ route('administrator/module-permission')}}">@lang('lang.module') @lang('lang.permission')</a>
            </li>
        
            {{-- <li>
                <a href="{{url('login-access-control')}}">@lang('lang.login_permission')</a>
            </li> --}}
        
            <li>
                <a href="{{route('base_group')}}">@lang('lang.base_group')</a>
            </li>
        
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
            <li>
                <a href="{{route('administrator/language-settings')}}">@lang('lang.language_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/backup-settings')}}">@lang('lang.backup_settings')</a>
            </li>
        
            <li>
                <a href="{{route('administrator/update-system')}}">@lang('lang.update_system')</a>
            </li>
        
        @if(Auth::user()->role_id == 1)
            <li>
                <a href="{{route('administrator/admin-data-delete')}}">@lang('lang.SampleDataEmpty')</a>
            </li>
        @endif
    
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
                                    <a href="{{route('administrator/background-setting')}}">@lang('lang.background_settings')</a>
                                </li>
                                <li>
                                    <a href="{{route('administrator/color-style')}}">@lang('lang.color') @lang('lang.theme')</a>
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
    
    
    
                    <li>
                        <a href="#subMenufrontEndSettings" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">
                            <span class="flaticon-software"></span>
                            @lang('lang.front_settings')
                        </a>
                        <ul class="collapse list-unstyled" id="subMenufrontEndSettings">
                            <li>
                                <a href="{{route('admin-home-page')}}"> @lang('lang.home_page') </a>
                            </li>
    
                            <li>
                                <a href="{{route('news')}}">@lang('lang.news_list')</a>
                            </li>
                            <li>
                                <a href="{{route('news-category')}}">@lang('lang.news') @lang('lang.category')</a>
                            </li>
                            <li>
                                <a href="{{route('testimonial')}}">@lang('lang.testimonial')</a>
                            </li>
                            <li>
                                <a href="{{route('course-list')}}">@lang('lang.course_list')</a>
                            </li>
                            <li>
                                <a href="{{route('conpactPage')}}">@lang('lang.contact') @lang('lang.page') </a>
                            </li>
                            <li>
                                <a href="{{route('contactMessage')}}">@lang('lang.contact') @lang('lang.message')</a>
                            </li>
                            <li>
                                <a href="{{route('about-page')}}"> @lang('lang.about_us') </a>
                            </li>
                            <li>
                                <a href="{{route('custom-links')}}"> @lang('lang.custom_links') </a>
                            </li>
                        </ul>
                    </li>
    
    
    <li>
    <a href="#Ticket" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        <span class="flaticon-settings"></span>
        @lang('lang.ticket_system')
    </a>
    <ul class="collapse list-unstyled" id="Ticket">
        <li><a href="{{ route('ticket.category') }}"> @lang('lang.ticket_category')</a></li>
        <li><a href="{{ route('ticket.priority') }}">@lang('lang.ticket_priority')</a></li>
        <li><a href="{{ route('admin.ticket_list') }}">@lang('lang.ticket_list')</a>
        </li>
    </ul>
    </li>
    
    {{-- SAAS -302 --}}
    
    
    
    

       
        
        
        
        
            