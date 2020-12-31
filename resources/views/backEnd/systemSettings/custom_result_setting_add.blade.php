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
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($result_setting))
                                    @lang('lang.edit')
                                @else
                                    @lang('lang.add')
                                @endif
                                @lang('lang.custom_result_setting')
                            </h3>
                        </div>
                        @if(isset($result_setting))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('custom-result-setting/update',@$result_setting->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                            @if(userPermission(437))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'custom-result-setting/store','method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        @if(session()->has('message-success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('message-success') }}
                                        </div>
                                        @elseif(session()->has('message-danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('message-danger') }}
                                        </div>
                                        @endif
                                       
                                        
                                    </div>
                                </div>

                                <div class="row ">
                                    
                                    <div class="col-lg-6 ">
                                    <select class="w-100 bb niceSelect form-control{{ $errors->has('exam_term1') ? ' is-invalid' : '' }}" name="exam_term1">
                                        <option data-display="@lang('lang.select') @lang('lang.first_term')*" value="">@lang('lang.select') @lang('lang.first_term') *</option>
                                        @foreach($exams as $exam)
                                            <option value="{{$exam->id}}" {{isset($result_setting)? (@$result_setting->exam_term_id1 == $exam->id? 'selected':''):''}}>{{@$exam->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam_term1'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('exam_term1') }}</strong>
                                    </span>
                                    @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('percentage_ex_1') ? ' is-invalid' : '' }}"
                                                type="number" name="percentage_ex_1" autocomplete="off" value="{{isset($result_setting)? @$result_setting->percentage1:old('percentage_ex_1')}}">
                                            <input type="hidden" name="id" value="{{isset($result_setting)? @$result_setting->percentage1: ''}}">
                                            <label> @lang('lang.first_term') @lang('lang.percentage')<span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('percentage_ex_1'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('percentage_ex_1') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-6 ">
                                    <select class="w-100 bb niceSelect form-control{{ $errors->has('exam_term2') ? ' is-invalid' : '' }}" name="exam_term2">
                                        <option data-display="@lang('lang.select') @lang('lang.second_term')*" value="">@lang('lang.select') @lang('lang.second_term') *</option>
                                        @foreach($exams as $exam)
                                            <option value="{{$exam->id}}" {{isset($result_setting)? (@$result_setting->exam_term_id2 == @$exam->id? 'selected':''):''}}>{{@$exam->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam_term2'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('exam_term2') }}</strong>
                                    </span>
                                    @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('percentage_ex_2') ? ' is-invalid' : '' }}"
                                                type="number" name="percentage_ex_2" autocomplete="off" value="{{isset($result_setting)? @$result_setting->percentage2:old('percentage_ex_2')}}">
                                            <input type="hidden" name="id" value="{{isset($result_setting)? @$result_setting->percentage2: ''}}">
                                            <label> @lang('lang.second_term') @lang('lang.percentage')<span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('percentage_ex_2'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('percentage_ex_2') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-6 ">
                                    <select class="w-100 bb niceSelect form-control{{ $errors->has('exam_term3') ? ' is-invalid' : '' }}" name="exam_term3">
                                        <option data-display="@lang('lang.select') @lang('lang.third_term')*" value="">@lang('lang.select') @lang('lang.third_term') *</option>
                                        @foreach($exams as $exam)
                                            <option value="{{@$exam->id}}" {{isset($result_setting)? (@$result_setting->exam_term_id3 == @$exam->id? 'selected':''):''}}>{{@$exam->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam_term3'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('exam_term3') }}</strong>
                                    </span>
                                    @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('percentage_ex_3') ? ' is-invalid' : '' }}"
                                                type="number" name="percentage_ex_3" autocomplete="off" value="{{isset($result_setting)? @$result_setting->percentage3:old('percentage_ex_3')}}">
                                            <input type="hidden" name="id" value="{{isset($academic_year)? @$academic_year->id: ''}}">
                                            <label> @lang('lang.third_term') @lang('lang.percentage')<span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('percentage_ex_3'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('percentage_ex_3') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                

                               
                               
                               @php 
                                    $tooltip = "";
                                    if(userPermission(437)){
                                            $tooltip = "";
                                        }else{
                                            $tooltip = "You have no permission to add";
                                        }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($result_setting))
                                                @lang('lang.update')
                                            @else
                                                @lang('lang.save')
                                            @endif

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            
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
                                                <h4 class="modal-title">@lang('lang.delete') @lang('lang.academic_year')</h4>
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
