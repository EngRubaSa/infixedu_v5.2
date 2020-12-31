@extends('backEnd.master')
@section('mainContent')
<style type="text/css">
    #selectStaffsDiv, .forStudentWrapper {
        display: none;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    .buttons_div_one{
    /* border: 4px solid #FFFFFF; */
    border-radius:12px;

    padding-top: 0px;
    padding-right: 5px;
    padding-bottom: 0px;
    margin-bottom: 4px;
    padding-left: 0px;
     }
    .buttons_div{
    border: 4px solid #19A0FB;
    border-radius:12px
    }
</style>

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.weekend')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="#">@lang('lang.weekend')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($editData))
                                    @lang('lang.edit')
                                @else
                                    @lang('lang.add')
                                @endif
                                @lang('lang.weekend')
                            </h3>
                        </div>
                        @if(isset($editData))
                            @if(userPermission(450))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('weekend-update',@$editData->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'weekendForm']) }}
                                        <input type="hidden" name="id" value="{{@$editData->id}}">
                            @endif
                       
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif
                                    <div class="col-lg-12 mb-20 mt-10 {{!isset($editData)? 'disabledbutton':''}}">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            type="text" name="name" autocomplete="off" value="{{isset($editData)? @$editData->name : '' }}" readonly="true">
                                            <label>@lang('lang.title') <span>*</span> </label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>


                                <div class="row mt-25 {{!isset($editData)? 'disabledbutton':''}}">
                                    <div class="col-lg-12"> 
                                        <div class="input-effect">
                                            <input type="checkbox" id="weekend" value="1" class="common-checkbox" name="make_weekend" value="" {{isset($editData)?(@$editData->is_weekend == 1? 'checked':''):''}}>
                                            <label for="weekend">@lang('lang.weekend')</label>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="row mt-40 {{!isset($editData)? 'disabledbutton':''}}">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>

                                            @if(isset($editData))
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
                        @else
                        @if(userPermission(450))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'weekend', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'weekendForm']) }}
                                        
                            @endif
                       
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    @if(session()->has('message-success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message-success') }}
                                    </div>
                                    @elseif(session()->has('message-danger'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message-danger') }}
                                    </div>
                                    @endif
                                    <div class="col-lg-12 mb-20 mt-10">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('holiday_title') ? ' is-invalid' : '' }}"
                                            type="text" name="name" autocomplete="off" value="" >
                                            <label>@lang('lang.title') <span>*</span> </label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                </div>


                                <div class="row mt-25">
                                    <div class="col-lg-12"> 
                                        <div class="input-effect">
                                            <input type="checkbox" id="weekend" value="1" class="common-checkbox" name="make_weekend" value="">
                                            <label for="weekend">@lang('lang.weekend')</label>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>

                                            @if(isset($editData))
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

                        @endif

                    </div>
                </div>
            </div> --}}

            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title mt_4">
                        <h3 class="mb-30">@lang('lang.day_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <table id="" class="display school-table school-table-style" cellspacing="0" width="100%">

                        <thead>
                            @if(session()->has('message-success-delete') != "" ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="5">
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
                            <th>@lang('lang.name')</th>
                            <th>@lang('lang.weekend')</th>
                            <th>@lang('lang.action')</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($weekends as $weekend)
                        <tr>
                            <td>{{@$weekend->name}}</td>
                            <td>
                                @if(@$weekend->is_weekend == 1)
                                <button class="primary-btn small fix-gr-bg">
                                    yes
                                </button>
                                @else
                                    {{'No'}}
                                @endif


                            </td>
                            <td>
                                <label class="switch">
                                <input type="checkbox" data-id="{{$weekend->id}}"
                                        class="weekend_switch_btn" {{@$weekend->is_weekend == 0? '':'checked'}}>
                                    <span class="slider round"></span>
                                </label>

                                {{-- <div class="dropdown">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                        @lang('lang.select')
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if(userPermission(449))
                                        <a class="dropdown-item" href="{{route('weekend-edit',@$weekend->id)}}">@lang('lang.edit')</a>
                                        @endif
                                    </div>
                                </div> --}}

                            </td>
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

@push('script')
<script>
    $(document).ready(function() {
            $(".weekend_switch_btn").on("change", function() {
                var day_id = $(this).data("id");
                
                if ($(this).is(":checked")) {
                    var status = "1";
                } else {
                    var status = "0";
                }
                
                
                var url = $("#url").val();
                

                $.ajax({
                    type: "POST",
                    data: {'status': status, 'day_id': day_id},
                    dataType: "json",
                    url: url + "/" + "weekend/switch",
                    success: function(data) {
                         location.reload();
                        setTimeout(function() {
                            toastr.success(
                                "Operation Success!",
                                "Success Alert", {
                                    iconClass: "customer-info",
                                }, {
                                    timeOut: 2000,
                                }
                            );
                        }, 500);
                        // console.log(data);
                    },
                    error: function(data) {
                        // console.log('no');
                        setTimeout(function() {
                            toastr.error("Operation Not Done!", "Error Alert", {
                                timeOut: 5000,
                            });
                        }, 500);
                    },
                });
            });
        });
</script>
@endpush
