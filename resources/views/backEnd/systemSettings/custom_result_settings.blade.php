@extends('backEnd.master')
@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.custom_result_setting')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="#">@lang('lang.custom_result_setting')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($result_setting))
            @if(userPermission(437))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('academic-year')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('lang.add')
                    </a>
                </div>
            </div>
            @endif   
        @endif   
        <div class="row">
            @php
                @$system_setting=App\SmGeneralSettings::find(1);
                @$system_setting=@$system_setting->session_id;

                @$check_exist=App\CustomResultSetting::where('academic_year','=',@$system_setting)->first();
            @endphp
          
                
            
         
           
            <div class="col-lg-12 mt-20">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">  @lang('lang.custom_result_setting')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                                @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="6">
                                        @if(session()->has('message-success-delete'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success-delete') }}
                                        </div>
                                        @elseif(session()->has('message-danger-delete'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger-delete') }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>@lang('lang.exam') @lang('lang.term')</th>
                                    <th>@lang('lang.percentage')</th>
                                    <th>@lang('lang.exam') @lang('lang.term')</th>
                                    <th>@lang('lang.percentage')</th>
                                    <th>@lang('lang.exam') @lang('lang.term')</th>
                                    <th>@lang('lang.percentage')</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($custom_settings as $custom_setting)
                                <tr>
                                    <td>{{@$custom_setting->exam_1}}</td>
                                    <td>{{@$custom_setting->percentage1}}%</td>
                                    <td>{{@$custom_setting->exam_2}}</td>
                                    <td>{{@$custom_setting->percentage2}}%</td>
                                    <td>{{@$custom_setting->exam_3}}</td>
                                    <td>{{@$custom_setting->percentage3}}%</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission(438))
                                                <a class="dropdown-item" href="{{route('custom-result-setting-edit', [@$custom_setting->id])}}">@lang('lang.edit')</a>
                                                @endif
                                                @if(userPermission(438))
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteAcademicYearModal{{@$custom_setting->id}}"
                                                    href="#">@lang('lang.delete')</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               <!--  -->

                                <div class="modal fade admin-query" id="deleteAcademicYearModal{{@$custom_setting->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('lang.delete') @lang('lang.custom_result_setting')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                                                     
                                                    {{ Form::open(['route' => array('custom-result-setting-delete',@$custom_setting->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                 <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                                                 {{ Form::close() }}
                                                     
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
