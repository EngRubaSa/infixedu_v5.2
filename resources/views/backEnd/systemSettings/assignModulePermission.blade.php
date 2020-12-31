@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.system_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="{{route('module-permission')}}">@lang('lang.module') @lang('lang.permission')</a>
                <a href="{{route('assign_permission', [$role->id])}}">@lang('lang.assign_permission')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('lang.assign_permission') ({{@$role->name}})</h3>
                            </div>
                        </div>
                    </div>
                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admin-dashboard', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                @else
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'module-permission-store', 'method' => 'POST']) }}
                @endif
        
                   
                    <input type="hidden" name="role_id" value="{{@$role->id}}">
                    <div class="row">
                        <div class="col-lg-12 base-setup role-permission">
                            <table id="school-table-style" class="display school-table-style" cellspacing="0" width="100%">
                                <thead>
                                    @if(session()->has('message-danger') != "")
                                    <tr>
                                        <td colspan="9">
                                            @if(session()->has('message-danger'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('lang.module')</th>
                                        <th>@lang('lang.module_link')</th>
                                        <th>@lang('lang.permission')</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                    <td colspan="3" class="pr-0">
                                        <div id="accordion" role="tablist">

                                            <div class="card">

                                                @foreach($modules as $key => $values)
                                                
                                                <div class="card-header d-flex justify-content-between" id="headingOne">
                                                    <div class="row w-100 align-items-center">
                                                        <div class="col-lg-6">
                                                            <div>
                                                                <p class="mt-05 mb-0" id="modulueSelect">
                                                                    @if(@$key == 1)
                                                                    @lang('lang.admin_staff') @lang('lang.dashboard')
                                                                    @elseif(@$key == 2)

                                                                    @lang('lang.student') @lang('lang.dashboard')
                                                                    @elseif(@$key == 3)
                                                                    @lang('lang.parent') @lang('lang.dashboard')

                                                                    @endif
                                                            </p>
                                                                
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-lg-5">
                                                            <div>
                                                               <input type="text" hidden value="" class="selcall"/>
                                                                <p class="mt-05 mb-0 text-center">
                                                                    <label for="">
                                                                   <input  type="checkbox"  class="selet ml-2" value="" class="" onclick="Select()"/>
                                                                     Select all</label>
                                                                </p>
                                                            </div>
                                                        </div> --}}

                                                    </div>
                                                </div>

                                                <div id="collapseOne" class="show" aria-labelledby="headingOne" data-parent="#accordion">
                                                    <div class="card-body">
                                                        
                                                       @foreach($values as $module_link)    

                                                        <div class="row py-3 border-bottom align-items-center">
                                                            <div class="offset-lg-3 col-lg-5"> {{@$module_link->name }}</div>
                                                            <div class="col-lg-4">
                                                                <div class="">
                                                                <input type="checkbox" id="permissions{{@$module_link->id}}" class="common-checkbox  select" onclick="SelectOne,{{@$module_link->id}}" name="permissions[]" value="{{@$module_link->id}}" {{in_array(@$module_link->id, @$already_assigned_ids)? 'checked':''}}>
                                                                    <label for="permissions{{@$module_link->id}}"></label>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        @endforeach
                                                        
                                                          
                                                    </div>
                                                </div>
                                                @endforeach

                                            </div>

                                            
                                        </div>
                                    </td>
                                    <td></td>
                                    <td> </td>
                                </tr>


                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="col-lg-12 mt-20 text-right">
                                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" > @lang('lang.update')</button></span>
                                                @else
                                                <button type="submit" class="primary-btn fix-gr-bg">
                                                        <span class="ti-check"></span>
                                                        @lang('lang.save')
                                                    </button>
                                                @endif 
                                               
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
            


@endsection

@section('script')
<script src="{{asset('public/backEnd/')}}/vendors/js/print/1.9.1_jquery.min.js"></script>
<script>

s    
       function SelectOne(data,link){
        //    $(".selet"+data).prop("checked",false)

           var checkBoxes = $(".select"+data);
                if(checkBoxes.prop("checked")==true )
                    // for (let index = 0; index < checkBoxes.length; index++) {
                        
                        
                    // }
                    $(".selet"+data).prop("checked",true)
                else
                $(".selet"+data).prop("checked",false)
       }
        function Select(data){
            var checkBoxes = $(".select"+data);
                if(checkBoxes.prop("checked")==true)
                    checkBoxes.prop("checked", false); 
                else
                    checkBoxes.prop("checked", true)
           /*  $(".unselect"+data).css("display","inline") */
            // $("input[type=checkbox]").attr('checked', "checked");
        }
    
    </script>
@endsection
