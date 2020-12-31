@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.language')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.system_settings')</a>
                    <a href="#">@lang('lang.language')</a>
                    {{-- <a href="#">@lang('lang.manage') @lang('lang.currency')</a> --}}

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            @if(isset($edit_languages))
                @if(userPermission(550))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{route('marks-grade')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('lang.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($editData))
                                        @lang('lang.edit')
                                    @else
                                        @lang('lang.add')
                                    @endif
                                    @lang('lang.language')
                                </h3>
                            </div>
                            @if(isset($editData))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'language_update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                <input type="hidden" name="id" value="{{isset($editData)? @$editData->id: ''}}">
                            @else
                                @if(userPermission(550))
                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'language_store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
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

                                    
                                    <div class="row mt-40"> 
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($editData)? @$editData->name: ''}}" maxlength="25" required>                                            
                                                <label>@lang('lang.name') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-40"> 
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" type="text" name="code" autocomplete="off" value="{{isset($editData)? @$editData->code: ''}}" maxlength="10" required>                                            
                                                <label>@lang('lang.code') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('code'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-40"> 
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ $errors->has('native') ? ' is-invalid' : '' }}" type="text" name="native" autocomplete="off" value="{{isset($editData)? @$editData->native: ''}}" maxlength="5" required>                                            
                                                <label>@lang('lang.native') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('native'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('native') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
 
                                    @php 
                                        $tooltip = "";
                                        if(userPermission(550)){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($editData))
                                                    @lang('lang.update')
                                                @else
                                                    @lang('lang.save')
                                                @endif
                                                @lang('lang.language')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('lang.language') @lang('lang.list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                           


                            <table id="default_table" class="display school-table" cellspacing="0" width="100%">


                                <thead>
                                @if(session()->has('message-success-delete') != "" ||session()->has('langChange')!= "" ||
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
                                            @if(session()->has('langChange'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('langChange') }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>@lang('lang.sl')</th>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.code')</th>
                                    <th>@lang('lang.native')</th> 
                                    <th>@lang('lang.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php $i=1;  @endphp

                                @foreach($languages as $value)
                                    <tr>
                                        <td>{{$i++}}
                                        <td>{{@$value->name}}</td>
                                        <td>{{@$value->code}}</td>
                                        <td>{{@$value->native}}</td> 
                                        <td>

                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission(551))
                                                    <a class="dropdown-item" href="{{route('language_edit', [@$value->id])}}">@lang('lang.edit')</a>
                                                @endif
                                                @if(userPermission(552))
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteCurrency{{@$value->id}}"  href="{{route('currency_delete', [@$value->id])}}">@lang('lang.delete')</a>
                                                @endif
                                            </div>
                                        </div>
                                        </td>

                                            <div class="modal fade admin-query" id="deleteCurrency{{@$value->id}}" >
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">@lang('lang.delete') @lang('lang.currency')</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                            </div>
                                                            <div class="mt-40 d-flex justify-content-between">
                                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                                                                <a href="{{route('currency_delete', [@$value->id])}}" class="text-light">
                                                                <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div> 
                                    </tr>
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