                    @if(userPermission(399))
                        <li>
                            <a href="{{route('manage-adons')}}">@lang('lang.module') @lang('lang.manager')</a>
                        </li>
                    @endif

                        @if(userPermission(401))
                                <li>
                                    <a href="{{route('manage-currency')}}">@lang('lang.manage') @lang('lang.currency')</a>
                                </li>
                        @endif

                       @if(userPermission(410))

                            <li>
                                <a href="{{route('email-settings')}}">@lang('lang.email_settings')</a>
                            </li>
                        @endif
                       {{--  @if(@in_array(152, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1)
                            <li>
                                <a href="{{route('payment_method')}}"> @lang('lang.payment_method')</a>
                            </li>
                        @endif
                        @if(@in_array(412, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1)

                            <li>
                                <a href="{{url('payment-method-settings')}}">@lang('lang.payment_method_settings')</a>
                            </li>
                        @endif --}}

                       @if(userPermission(428))

                                <li>
                                    <a href="{{route('base_setup')}}">@lang('lang.base_setup')</a>
                                </li>
                         @endif

                         @if(userPermission(549))

                            <li>
                                <a href="{{route('language-list')}}">@lang('lang.language')</a>
                            </li>
                        @endif

                        @if(userPermission(451))

                            <li>
                                <a href="{{route('language-settings')}}">@lang('lang.language_settings')</a>
                            </li>
                        @endif
                        @if(userPermission(456))

                            <li>
                                <a href="{{route('backup-settings')}}">@lang('lang.backup_settings')</a>
                            </li>
                        @endif
                        
                       @if(userPermission(444))

                            <li>
                                <a href="{{route('sms-settings')}}">@lang('lang.sms_settings')</a>
                            </li>
                        @endif
                       
                        @if(userPermission(463))
                            <li>
                                <a href="{{route('button-disable-enable')}}">@lang('lang.header') @lang('lang.option') </a>
                            </li>
                        @endif


                        @if(userPermission(477))

                            <li>
                                <a href="{{route('about-system')}}">@lang('lang.about')</a>
                            </li>
                        @endif

                        @if(userPermission(478))

                            <li>
                                <a href="{{route('update-system')}}">@lang('lang.update')</a>
                            </li>
                        @endif
                       
                        @if(userPermission(480))
                            <li>
                                <a href="{{route('templatesettings/email-template')}}">@lang('lang.email') @lang('lang.template')</a>
                            </li>
                            {{-- <li>
                                <a href="{{url('sms-template')}}">@lang('lang.sms') @lang('lang.template')</a>
                            </li> --}}
                        @endif
                        @if(userPermission(482))
                        <li>
                            <a href="{{route('api/permission')}}">@lang('lang.api') @lang('lang.permission') </a>
                        </li>
                    @endif
