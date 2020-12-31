@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.manage') @lang('lang.social_media')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.front') @lang('lang.settings')</a>
                <a href="#">@lang('lang.social_media')</a>
            </div>
    </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($visitor))
        @if(userPermission(530))
            <div class="row">
                <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                    <a href="{{route('social-media')}}" class="primary-btn small fix-gr-bg">
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
                                <h3 class="mb-30">
                                    @if(isset($visitor))
                                        @lang('lang.edit')
                                    @else
                                        @lang('lang.add')
                                    @endif
                                    @lang('lang.social_media')
                                </h3>
                            </div>
                            @if(isset($visitor))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'social-media-update',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @else
                              @if(in_array(530, App\GlobalVariable::GlobarModuleLinks()) || Auth::user()->role_id == 1)
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'social-media-store',
                                'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    

                                    <div class="row">
                                        <div class="col-lg-12">
                                             @if(session()->has('message-success'))
                                                <div class="alert alert-success">
                                                    @lang('lang.inserted_message')
                                                </div>
                                            @elseif(session()->has('message-danger'))
                                                <div class="alert alert-danger">
                                                    @lang('lang.error_message')
                                                </div>
                                            @endif


                                             <div class="alert alert-warning">
                                                Note: Font awesome icon enter only e.g. fa fa-facebook.
                                             </div>

                                            

                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}"  type="text" name="icon" autocomplete="off" value="{{isset($visitor)? $visitor->icon: old('icon')}}">

                                                <input type="hidden" name="id"
                                                       value="{{isset($visitor)? $visitor->id: ''}}">
                                                <label>@lang('lang.icon')(fa fa-facebook)<span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('icon'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('icon') }}</strong>
                                            </span>
                                                @endif
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row mt-25">
                                        <div class="col-lg-12">


                                           
                                            
                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"  type="text" name="url" autocomplete="off" value="{{isset($visitor)? $visitor->url: old('url')}}">

                                                <input type="hidden" name="id"
                                                       value="{{isset($visitor)? $visitor->id: ''}}">
                                                <label>@lang('lang.url')<span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('url'))
                                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('url') }}</strong>
                                            </span>
                                                @endif
                                            </div>


                                        </div>
                                    </div>

                  
                                   {{-- <div class="row no-gutters input-right-icon mt-35">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input" id="placeholderInput" type="text"
                                                       placeholder="{{isset($visitor)? ($visitor->icon != ""? showPicName($visitor->icon):'Icon(png/jpg 24x24px) *'):'Icon(png/jpg 24x24px) *'}}"
                                                       readonly>
                                                <span class="focus-border"></span>

                                                @if ($errors->has('icon'))
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ @$errors->first('icon') }}</strong>
                                                    </span>
                                            @endif
                                            
                                            </div>
                                        </div>
                                        

                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                       for="browseFile">@lang('lang.browse')</label>
                                                <input type="file" class="d-none" id="browseFile" name="icon">
                                            </button>
                                        </div>
                                    </div> --}}

                                    <div class="row mt-25">
                                            <div class="col-lg-12">
                                                <select class="niceSelect w-100 bb form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" name="status">
                                                    <option data-display="@lang('lang.status') *" value="">
                                                            <option value="1" {{isset($visitor)? ($visitor->status == 1? 'selected':''):'selected'}}>@lang('lang.active')</option>
                                                            <option value="0"  {{isset($visitor)? ($visitor->status == 0? 'selected':''):''}}>@lang('lang.inactive')</option>
                                                        
                                                </select>
                                                @if ($errors->has('status'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        @php 
                                            $tooltip = "";
                                            if(userPermission(530)){
                                                    $tooltip = "";
                                                }else{
                                                    $tooltip = "You have no permission to add";
                                                }
                                        @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                           <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($visitor))
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
            
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('lang.social_media')</h3>
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
                                    <td colspan="8">
                                        @if(session()->has('message-success-delete'))
                                            <div class="alert alert-success">
                                                @lang('lang.deleted_message')
                                            </div>
                                        @elseif(session()->has('message-danger-delete'))
                                            <div class="alert alert-danger">
                                                @lang('lang.error_message')
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>@lang('lang.url')</th>
                                <th>@lang('lang.icon')</th>
                                
                                <th>@lang('lang.status')</th>
                                <th>@lang('lang.actions')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($visitors as $value)
                                <tr>
                                    <td><a href="{{ @$value->url}}"> {{ @$value->url}} </a></td>
                                    <td><i class="{{$value->icon}}"></td>
                                    <td>{{ @$value->status == 1? 'active':'inactive'}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle"
                                                    data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission(531))

                                                    <a class="dropdown-item"
                                                       href="{{route('social-media-edit', [@$value->id])}}">@lang('lang.edit')</a>
                                                @endif
                                                @if(userPermission(532))

                                                    <a class="dropdown-item" data-toggle="modal"
                                                       data-target="#deleteVisitorModal{{@$value->id}}"
                                                       href="#">@lang('lang.delete')</a>
                                                   
                                                @endif

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteVisitorModal{{@$value->id}}">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('lang.delete') @lang('lang.social_media')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg"
                                                            data-dismiss="modal">@lang('lang.cancel')
                                                    </button>

                                                    <a href="{{route('social-media-delete', [@$value->id])}}"
                                                       class="primary-btn fix-gr-bg">@lang('lang.delete')</a>

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
