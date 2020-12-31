@extends('backEnd.master')
@section('mainContent')

    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.approve_leave_request')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                  
                    <a href="#">@lang('lang.approve_leave_request')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">


            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('lang.apply_leave') @lang('lang.list')</h3>
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
                                    <td colspan="7">
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
                                <th>@lang('lang.type')</th>
                                <th>@lang('lang.from')</th>
                                <th>@lang('lang.to')</th>
                                <th>@lang('lang.apply_date')</th>
                                <th>@lang('lang.Status')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($apply_leaves as $apply_leave)
                                <tr>
                                    <td>{{isset($apply_leave->student)? $apply_leave->student->full_name:''}}</td>
                                    <td>
                                        @if($apply_leave->leaveDefine !="" && $apply_leave->leaveDefine->leaveType !="")
                                            {{$apply_leave->leaveDefine->leaveType->type}}
                                        @endif
                                    </td>
                                    <td data-sort="{{strtotime($apply_leave->leave_from)}}">
                                        {{$apply_leave->leave_from != ""? dateConvert($apply_leave->leave_from):''}}

                                    </td>
                                    <td data-sort="{{strtotime($apply_leave->leave_to)}}">
                                        {{$apply_leave->leave_to != ""? dateConvert($apply_leave->leave_to):''}}

                                    </td>
                                    <td data-sort="{{strtotime($apply_leave->apply_date)}}">
                                        {{$apply_leave->apply_date != ""? dateConvert($apply_leave->apply_date):''}}

                                    </td>
                                    <td>

                                        @if($apply_leave->approve_status == 'P')
                                            <button class="primary-btn small tr-bg">@lang('lang.pending')</button>@endif

                                        @if($apply_leave->approve_status == 'A')
                                            <button class="primary-btn small tr-bg">@lang('lang.approved')</button>
                                        @endif

                                        @if($apply_leave->approve_status == 'C')
                                            <button class="primary-btn small bg-danger text-white border-0">@lang('lang.cancelled')</button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
