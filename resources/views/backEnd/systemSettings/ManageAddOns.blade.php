
@extends('backEnd.master')
@section('mainContent')
    <style type="text/css">
        #selectStaffsDiv, .forStudentWrapper {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        #waiting_loader{
            display: none;
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
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
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

    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.module') @lang('lang.manage')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.system_settings')</a>
                    <a href="#">@lang('lang.module') @lang('lang.manage')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-10 col-xs-6 col-md-6 col-6 no-gutters ">
                            <div class="main-title sm_mb_20 sm2_mb_20 md_mb_20 mb-30 ">
                                <h3 class="mb-0"> @lang('lang.module') @lang('lang.manage')</h3>
                            </div>
                        </div>
                        <div class="col-lg-2 col-xs-6 col-md-6 col-6 no-gutters ">
                            <a href="{{route('ModuleRefresh')}}" class="primary-btn fix-gr-bg small pull-right"> <i
                                        class="ti-reload"> </i> Refresh</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="default_table" class="display school-table school-table-style" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>@lang('lang.sl')</th>
                                    <th>@lang('lang.name')</th>
                                    <th>@lang('lang.status')</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                                </thead>

                                <tbody>
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                @php $count=1; @endphp
                                @foreach($is_module_available as $row)
                                    @php
                                        $is_module_available = 'Modules/' . $row->getName(). '/Providers/' .$row->getName(). 'ServiceProvider.php';
                                        if (! file_exists($is_module_available)){
                                            continue;
                                        }
                                        $is_data = \App\InfixModuleManager::where('name', $row->getName())->first();

                                    @endphp
                                    <tr>
                                        <td>{{@$count++}}</td>
                                        <td>
                                           <strong> {{@$row->getName()}} </strong>
                                            @if(!empty($is_data->purchase_code)) <p class="text-success">Verified |
                                                Published
                                                on {{date("F jS, Y", strtotime(@$is_data->activated_date))}}</p> @else<p
                                                    class="text-danger"> Not Verified @endif  </p>
                                        </td>
                                        <td>
                                           
                                            @if( moduleStatusCheck($row->getName() ) == False) 
                                                <a class="primary-btn small {{@$row->getName()}} bg-warning text-white border-0"
                                                   href="#"> @lang('lang.disable') </a>
                                            @else
                                                <a class="primary-btn small {{@$row->getName()}} bg-success text-white border-0"
                                                   href="#"> @lang('lang.enable') </a>
                                            @endif
                                        </td>

                                        <td>
                                           
                                            @if (file_exists($is_module_available))
                                                @php
                                                    $system_settings= App\SmGeneralSettings::first();
                                                
                                                    $is_moduleV= $is_data;
                                                    $configName = $row->getName();
                                                    $availableConfig=$system_settings->$configName;

                                                    // dd($availableConfig);
                                                @endphp
                                                @if(@$availableConfig==0 || @@$is_moduleV->purchase_code== null)
                                                    {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'ManageAddOnsValidation', 'method' => 'POST']) }} --}}
                                                    <input type="hidden" name="name" value="{{@$configName}}">
                                                    <div class="row">

                                                        <div class="col-lg-6">
                                                            <div class="col-lg-12 text-center">
                                                                @if(userPermission(400))
                                                                    {{-- <button class="primary-btn fix-gr-bg" >
                                                                        <span class="ti-check"></span>
                                                                            @lang('lang.verify')
                                                                    </button> --}}
                                                                    @if(Illuminate\Support\Facades\Config::get('app.app_pro'))
                                                                        <a class="primary-btn fix-gr-bg"
                                                                           data-toggle="modal"
                                                                           data-target="#proVerify{{@$configName}}"
                                                                           href="#">@lang('lang.verify')</a>
                                                                    @else
                                                                   
                                                                        <a class="primary-btn fix-gr-bg"
                                                                           data-toggle="modal"
                                                                           data-target="#Verify{{@$configName}}"
                                                                           href="#">@lang('lang.verify')</a>

                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                @else
                                                
                                                    @if('RolePermission' != $row->getName() && 'TemplateSettings' != $row->getName() )
                                                        <div id="waiting_loader" class="waiting_loader{{@$row->getName()}}"><img src="{{asset('public/backEnd/img/demo_wait.gif')}}" width="44" height="44" /><br>Installing..</div>
                                                        <label class="switch module_switch_label{{@$row->getName()}}">

                                                            <input type="checkbox" data-id="{{@$row->getName()}}" id="ch{{@$row->getName()}}" class="switch-input1 module_switch" {{moduleStatusCheck($row->getName() ) == false? '':'checked'}}>
                                                            <span class="slider round"></span>

                                                        </label>
                                                    @else
                                                        <p class="primary-btn fix-gr-bg small">Default</p>
                                                    @endif
                                                @endif
                                            @endif

                                        </td>


                                    </tr>
                                    <div class="modal fade admin-query" id="proVerify{{@$configName}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Module Verification</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>

                                                <div class="modal-body">

                                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'ManageAddOnsValidation', 'method' => 'POST']) }}
                                                    <input type="hidden" name="name" value="{{@$configName}}">

                                                    {{csrf_field()}}
                                                    <div class="form-group">
                                                        <label for="user">Email :</label>
                                                        <input type="text" class="form-control " name="email"
                                                               required="required" placeholder="Enter Your Email"
                                                               value="{{old('email')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="purchasecode">Purchase Code:</label>
                                                        <input type="text" class="form-control" name="purchase_code"
                                                               required="required"
                                                               placeholder="Enter Your Purchase Code"
                                                               value="{{old('purchasecode')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="domain">Installation Path:</label>
                                                        <input type="text" class="form-control" name="domain"
                                                               required="required" value="{{url('/')}}" readonly>
                                                    </div>
                                                    <div class="row mt-40">
                                                        <div class="col-lg-12 text-center">
                                                            <button class="primary-btn fix-gr-bg">
                                                                <span class="ti-check"></span>
                                                                @lang('lang.verify')
                                                            </button>

                                                        </div>
                                                    </div>

                                                    {{ Form::close() }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade admin-query" id="Verify{{@$configName}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Module Verification</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>

                                                <div class="modal-body">

                                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'ManageAddOnsValidation', 'method' => 'POST']) }}
                                                    <input type="hidden" name="name" value="{{@$configName}}">

                                                    {{csrf_field()}}
                                                    <div class="form-group">
                                                        <label for="user">Envato Username :</label>
                                                        <input type="text" class="form-control " name="envatouser"
                                                               required="required"
                                                               placeholder="Enter Your Envato User Name"
                                                               value="{{old('envatouser')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="purchasecode">Envato Purchase Code:</label>
                                                        <input type="text" class="form-control" name="purchase_code"
                                                               required="required"
                                                               placeholder="Enter Your Envato Purchase Code"
                                                               value="{{old('purchasecode')}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="domain">Installation Path:</label>
                                                        <input type="text" class="form-control"
                                                               name="installationdomain" required="required"
                                                               placeholder="Enter Your Installation Domain"
                                                               value="{{url('/')}}" readonly>
                                                    </div>
                                                    <div class="row mt-40">
                                                        <div class="col-lg-12 text-center">
                                                            <button class="primary-btn fix-gr-bg">
                                                                <span class="ti-check"></span>
                                                                @lang('lang.verify')
                                                            </button>

                                                        </div>
                                                    </div>

                                                    {{ Form::close() }}
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
@push('script')
    <script>
        $(document).on('click','.module_switch',function (){
            var url = $("#url").val();
            var module = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                beforeSend: function(){
                    $(".module_switch_label"+module).hide();
                    $(".waiting_loader"+module).show();
                },
                url: url + "/" + "manage-adons-enable/" + module,
                success: function(data) {
                    $(".waiting_loader"+module).hide();
                    $(".module_switch_label"+module).show();
                    if (data["success"]) {
                        if (data["data"] == "enable") {
                            $(`.${module}`).removeClass("bg-warning");
                            $(`.${module}`).addClass("bg-success");
                            $(`.${module}`).text("Enable");
                        } else {
                            $(`.${module}`).removeClass("bg-success");
                            $(`.${module}`).addClass("bg-warning");
                            $(`.${module}`).text("Disable");
                        }
                        toastr.success(data["success"], "Success Alert");
                    } else {
                        toastr.error(data["error"], "Faild Alert");
                    }
                },
                error: function(data) {
                    console.log("Error:", data["error"]);
                },
            })
        })

    </script>
    @endpush
