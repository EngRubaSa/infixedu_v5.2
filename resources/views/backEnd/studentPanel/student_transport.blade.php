@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.transport')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="{{route('student_subject')}}">@lang('lang.transport')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
       
        <div class="row">

            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('lang.transport_route') @lang('lang.list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 mb-30">
                                <!-- Start Student Meta Information -->
                                <div class="student-meta-box">
                                    <div class="student-meta-top"></div>
                                    <img class="student-meta-img img-100" src="{{ file_exists($student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="">
                                    <div class="white-box radius-t-y-0">
                                        <div class="single-meta mt-10">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('lang.student_name')
                                                </div>
                                                <div class="value">
                                                    {{$student_detail->first_name.' '.$student_detail->last_name}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('lang.admission_no')
                                                </div>
                                                <div class="value">
                                                    {{$student_detail->admission_no}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('lang.roll_number')
                                                </div>
                                                <div class="value">
                                                     {{$student_detail->roll_no}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('lang.class')
                                                </div>
                                                <div class="value">
                                                   {{$student_detail->className !=""?$student_detail->className->class_name:""}} ({{$student_detail->session !=""?$student_detail->session->session:""}})
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('lang.section')
                                                </div>
                                                <div class="value">
                                                    {{$student_detail->section !=""?$student_detail->section->section_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-meta">
                                            <div class="d-flex justify-content-between">
                                                <div class="name">
                                                    @lang('lang.gender')
                                                </div>
                                                <div class="value">
                                                    {{$student_detail->gender !=""?$student_detail->gender->base_setup_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Student Meta Information -->
                
                            </div>
                    <div class="col-lg-9">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th>@lang('lang.route')</th>
                                    <th>@lang('lang.vehicle')</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($routes as $route)
                                <tr>
                                    <td valign="top">{{@$route->route->title}}</td>
                                    <td>
                                        <table>
                                            @php
                                              @$vehicles = explode(",",@$route->vehicle_id);
                                            @endphp
                                            @foreach($vehicles as $vehicle)
                                            <tr>
                                                <td>
                                                    @php @$vehicle = App\SmVehicle::find(@$vehicle);
                                                    @endphp
                                                    {{@$vehicle->vehicle_no}}


                                                </td>
                                                <td >
                                                    <div class="col-sm-6">
                                                        
                                                    @if(@$student_detail->route_list_id == @$route->route->id && @$student_detail->vechile_id == @$vehicle->id)
                                                        <a href="javascript:void(0)" class="primary-btn small fix-gr-bg">Assigned</a> 
                                                    @endif
                                                    </div>
                                                     
                                                    <div class="col-sm-6">
                                                         
                                                         <a class="primary-btn small fix-gr-bg modalLink" title="Transport Details" data-modal-size="modal" href="{{route('student_transport_view_modal', [@$route->route->id, @$vehicle->id])}}">@lang('lang.view')</a>
                                                   
                                                    </div>
                                                    

                                                </td>

                                                
                                            </tr>
                                            @endforeach

                                        </table>
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