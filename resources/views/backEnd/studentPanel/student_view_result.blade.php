@extends('backEnd.master')
@section('mainContent')
@php
    $user = Auth::user()->student;
@endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.online_exam')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.online_exam')</a>
                <a href="{{route('student_view_result')}}">@lang('lang.view') @lang('lang.result')</a>
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
                            <h3 class="mb-0">@lang('lang.online_exam') @lang('lang.view') @lang('lang.result')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead> 
                                <tr>
                                    <th>@lang('lang.title')</th>
                                    <th>@lang('lang.time')</th>
                                    <th>@lang('lang.total_marks')</th>
                                    <th>@lang('lang.obtained_marks') </th>
                                    <th>@lang('lang.result')</th>
                                    <th>@lang('lang.status')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($result_views as $result_view)
                                
                                    <tr>
                                        <td>{{$result_view->onlineExam !=""?@$result_view->onlineExam->title:""}}</td>
                                        <td  data-sort="{{strtotime(@$result_view->onlineExam->date)}}" >
                                            @if(!empty(@$result_view->onlineExam))
                                           {{@$result_view->onlineExam->date != ""? dateConvert(@$result_view->onlineExam->date):''}}


                                             <br> Time: {{@$result_view->onlineExam->start_time.' - '.@$result_view->onlineExam->end_time}}
                                            @endif
                                        </td>
                                        <td>
                                            @php 
                                            $total_marks = 0;
                                            foreach($result_view->onlineExam->assignQuestions as $assignQuestion){
                                                @$total_marks = $total_marks + @$assignQuestion->questionBank->marks;
                                            }
                                            echo @$total_marks;
                                            @endphp
                                        </td>
                                        <td>{{@$result_view->total_marks}}</td>
                                        <td>
                                            @php
                                                @$result = @$result_view->total_marks * 100 / @$total_marks;
                                                if(@$result >= @$result_view->onlineExam->percentage){
                                                    echo "Pass";  
                                                }else{
                                                    echo "Fail";
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            <a class="btn btn-success modalLink" data-modal-size="modal-lg" title="Answer Script"  href="{{route('student_answer_script', [@$result_view->online_exam_id, @$result_view->student_id])}}" >@lang('lang.answer_script')</a>
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
