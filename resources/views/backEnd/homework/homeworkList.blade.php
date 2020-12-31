@extends('backEnd.master')
@section('mainContent')
@php
    
    $DATE_FORMAT = systemDateFormat();   
@endphp

{{-- {{ dd(Session::get('permission')) }} --}}
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.homework_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.home_work')</a>
                <a href="#">@lang('lang.homework_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                </div>
            </div>
            <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                <a href="{{route('add-homeworks')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add_homework')
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'homework-list', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="input-effect">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('class_id') ? ' is-invalid' : '' }}" name="class_id" id="classSelectStudent">
                                <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select')</option>
                                    @foreach($classes as $key=>$value)
                                    <option value="{{$value->id}}">{{$value->class_name}}</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('class_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="input-effect" id="sectionStudentDiv">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('section_id') ? ' is-invalid' : '' }}" name="section_id" id="sectionSelectStudent">
                                     <option data-display="@lang('lang.select_section')" value="">@lang('lang.section')</option>
                                 </select>
                                 <span class="focus-border"></span>
                                 @if ($errors->has('section_id'))
                                 <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="input-effect" id="subjectSelecttDiv">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('subject_id') ? ' is-invalid' : '' }}" name="subject_id" id="subjectSelect">
                                    <option data-display="@lang('lang.select_subjects')" value="">@lang('lang.subject')</option>
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('subject_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('subject_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg">
                                <span class="ti-search pr-2"></span>
                                @lang('lang.search')
                            </button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @if (@$homeworkLists)                                

    <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('lang.homework_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                        <thead> 
                            
                            <tr>
                                <th>@lang('lang.class')</th>
                                <th>@lang('lang.section')</th>
                                <th>@lang('lang.subject')</th>
                                <th>@lang('lang.marks')</th>
                                <th>@lang('lang.home_work') @lang('lang.date')</th>
                                <th>@lang('lang.submission') @lang('lang.date')</th>
                                <th>@lang('lang.evaluation') @lang('lang.date')</th>
                                <th>@lang('lang.created_by')</th>
                                <th>@lang('lang.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach(@$homeworkLists as $value)
                            <tr>
                                <td>{{$value->classes  !=""?$value->classes->class_name:""}}</td>
                                <td>{{$value->sections !=""?$value->sections->section_name:""}}</td>
                                <td>{{$value->subjects !=""?$value->subjects->subject_name:""}}</td>
                                <td>{{$value->marks}}</td>
                                 <td  data-sort="{{strtotime($value->homework_date)}}" >
                                    {{$value->homework_date != ""? date_format(date_create($value->homework_date), $DATE_FORMAT):''}}

                                </td>
                                 <td  data-sort="{{strtotime($value->submission_date)}}" >
                                    {{$value->submission_date != ""? date_format(date_create($value->submission_date), $DATE_FORMAT):''}}

                                </td>
                                <td  data-sort="{{strtotime($value->evaluation_date)}}" >
                                @if(!empty($value->evaluation_date))
                                {{$value->evaluation_date != ""? date_format(date_create($value->evaluation_date), $DATE_FORMAT):''}}

                                @endif
                                </td>
                              
                               <td>{{$value->users !=""? $value->users->full_name:""}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            @lang('lang.select')
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                          @if(userPermission(281))
                                            <a class="dropdown-item modalLink" title="Evaluation Homework" data-modal-size="full-width-modal" 
                                            href="{{route('evaluation-homework',[@$value->class_id,@$value->section_id,@$value->id])}}">@lang('lang.evaluation')</a>
                                         @endif
                                          @if(userPermission(282))
                                           <a class="dropdown-item" href="{{route('homework_edit', [$value->id])}}">@lang('lang.edit')</a>
                                           @endif
                                            @if(userPermission(283))
                                            <a onclick="GlobaldeleteId();" class="dropdown-item"  data-url="{{route('homework_delete',$value->id) }}"  href="#">@lang('lang.delete')</a>
                                        @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>           

            </div>
        </div>
    @endif

    </div>
</section>
@endsection
