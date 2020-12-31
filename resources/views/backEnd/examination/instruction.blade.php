@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.examinations') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.examinations')</a>
                <a href="{{route('instruction')}}">@lang('lang.instruction')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        @if(isset($instruction))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('instruction')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add')
                </a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($instruction))
                                @lang('lang.edit')
                                @else
                                @lang('lang.add')
                                @endif
                                @lang('lang.instruction')
                            </h3>
                        </div>
                        @if(isset($instruction))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('instruction-update'.$instruction->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'instruction',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
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
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                                type="text" name="title" autocomplete="off" value="{{isset($instruction)? $instruction->title:''}}">
                                            <input type="hidden" name="id" value="{{isset($instruction)? $instruction->id: ''}}">
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
                                <div class="row mt-40">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <textarea class="primary-input form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" cols="0" rows="4" name="description">{{isset($instruction)? $instruction->description: ''}}</textarea>
                                            <label>@lang('lang.description') <span>*</span></label>
                                            <span class="focus-border textarea"> </span>
                                            @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>
                                            {{!isset($instruction)? 'save':'update'}} @lang('lang.instruction')
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
                            <h3 class="mb-0"> @lang('lang.instruction') @lang('lang.list')</h3>
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
                                    <td colspan="3">
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
                                    <th>@lang('lang.description')</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($instructions as $instruction)
                                <tr>
                                    <td>{{ @$instruction->title}}</td>
                                    <td>{{ @$instruction->description}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                 @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('instruction-edit', [$instruction->id])}}"> @lang('lang.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteRouteModal{{@$instruction->id}}"
                                                    href="#"> @lang('lang.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteRouteModal{{@$instruction->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> @lang('lang.Delete') @lang('lang.Instruction')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('lang.are_you_sure_to_detete_this_item')?</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('lang.cancel')</button>
                                                     {{ Form::open(['route' => array('instruction-delete',$instruction->id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                    <button class="primary-btn fix-gr-bg" type="submit"> @lang('lang.delete')</button>
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