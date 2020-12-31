@extends('backEnd.master')
@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.add') @lang('lang.course')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.front') @lang('lang.settings')</a>
                    <a href="#">@lang('lang.add') @lang('lang.course')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($add_course))
                @if(userPermission(511))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{route('course-list')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('lang.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                @if(isset($add_course))
                                    @lang('lang.edit')
                                @else
                                    @lang('lang.add')
                                @endif
                                @lang('lang.course')
                            </h3>
                        </div>
                        @if(isset($add_course))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update_course',
                            'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'add-income-update']) }}
                        @else
                            @if(userPermission(511))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'store_course',
                                'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'add-income']) }}
                            @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(session()->has('message-success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('message-success') }}
                                            </div>
                                        @elseif(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                        @endif

                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <p class="error text-danger">{{$error}}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-lg-12 mt-40">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                       type="text" name="title" autocomplete="off"
                                                       value="{{isset($add_course)? $add_course->title: old('title')}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($add_course)? $add_course->id: ''}}">
                                                <label>@lang('lang.title') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('title'))
                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('title') }}</strong>
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 ">
                                    <div class="row mt-20">
                                        <div class="col-md-6 mt-20">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-effect">
                                                                <textarea class="primary-input form-control" cols="0"
                                                                          rows="6"
                                                                          name="overview">{{isset($add_course)? $add_course->overview: old('overview')}}</textarea>
                                                        <label>@lang('lang.overview') *<span></span></label>
                                                        <span class="focus-border textarea"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-lg-12 mt-20">
                                                    <div class="input-effect">
                                                        <textarea class="primary-input form-control" cols="0" rows="6"
                                                                  name="outline">{{isset($add_course)? $add_course->outline: old('outline')}}</textarea>
                                                        <label>@lang('lang.outline') *<span></span></label>
                                                        <span class="focus-border textarea"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 mt-20">
                                                    <div class="input-effect">
                                                                <textarea class="primary-input form-control" cols="0"
                                                                          rows="6"
                                                                          name="prerequisites">{{isset($add_course)? $add_course->prerequisites: old('prerequisites')}}</textarea>
                                                        <label>@lang('lang.prerequisites') *<span></span></label>
                                                        <span class="focus-border textarea"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-20">
                                            <div class="row ">
                                                <div class="col-lg-12">
                                                    <div class="input-effect">
                                                        <textarea class="primary-input form-control" cols="0" rows="6"
                                                                  name="resources">{{isset($add_course)? $add_course->resources: old('resources')}}</textarea>
                                                        <label>@lang('lang.resources') *<span></span></label>
                                                        <span class="focus-border textarea"></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-12 mt-20">
                                                    <div class="input-effect">
                                                        <textarea class="primary-input form-control" cols="0" rows="6"
                                                                  name="stats">{{isset($add_course)? $add_course->stats: old('stats')}}</textarea>
                                                        <label>@lang('lang.stats') *<span></span></label>
                                                        <span class="focus-border textarea"></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row no-gutters input-right-icon mt-50">
                                                <div class="col mt-50">
                                                    <div class="row no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="input-effect">
                                                                <input class="primary-input" type="text"
                                                                       id="placeholderFileOneName"
                                                                       placeholder="{{isset($add_course)? ($add_course->image !="") ? getFilePath3($add_course->image) :'image' :'image' }}"
                                                                       readonly
                                                                >
                                                                <span class="focus-border"></span>
                                                                @if ($errors->has('image'))
                                                                    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('image') }}</strong>
                                                        </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="primary-btn-small-input" type="button">
                                                                <label class="primary-btn small fix-gr-bg"
                                                                       for="document_file_1">@lang('lang.browse')</label>
                                                                <input type="file" class="d-none" name="image"
                                                                       id="document_file_1">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $tooltip = "";
                                if(userPermission(511)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                            @endphp
                            <div class="row mt-40">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                        <span class="ti-check"></span>
                                        @if(isset($add_course))
                                            @lang('lang.update')
                                        @else
                                            @lang('lang.add')
                                        @endif
                                        @lang('lang.course')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="col-lg-12 mt-40">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('lang.course_list')</h3>
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
                                <td colspan="4">
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
                            <th>@lang('lang.title')</th>
                            <th>@lang('lang.overview')</th>
                            <th>@lang('lang.image')</th>
                            <th>@lang('lang.action')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($course))
                            @foreach($course as $value)
                                <tr>
                                    <td>{{@$value->title}}</td>
                                    <td>{!! substr($value->overview, 0, 50) !!}</td>
                                    <td><img src="{{asset(@$value->image)}}" width="60px" height="50px"></td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission(510))
                                                    <a href="{{route('course-Details-admin',$value->id)}}"
                                                       class="dropdown-item small fix-gr-bg modalLink"
                                                       title="Course Details" data-modal-size="full-width-modal">
                                                        @lang('lang.view')
                                                    </a>
                                                @endif
                                                @if(userPermission(512))
                                                    <a class="dropdown-item"
                                                       href="{{route('edit-course',$value->id)}}">@lang('lang.edit')</a>
                                                @endif
                                                @if(userPermission(513))
                                                    <a href="{{route('for-delete-course',$value->id)}}"
                                                       class="dropdown-item small fix-gr-bg modalLink"
                                                       title="Delete Course" data-modal-size="modal-md">
                                                        @lang('lang.delete')
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

