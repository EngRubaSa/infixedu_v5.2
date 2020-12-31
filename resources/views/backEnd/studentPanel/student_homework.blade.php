@extends('backEnd.master')
@section('mainContent')


<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.homework_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.home_work')</a>
                <a href="#">@lang('lang.homework_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">

    <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('lang.homework_list')</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                        <thead>

                            @if(session()->has('message-success') != "" ||
                            session()->get('message-danger') != "")
                            <tr>
                                <td colspan="9">
                                     @if(session()->has('message-success'))
                                      <div class="alert alert-success">
                                          {{ session()->get('message-success') }}
                                      </div>
                                    @elseif(session()->has('message-danger'))
                                      <div class="alert alert-danger">
                                          {{ session()->get('message-danger') }}
                                      </div>
                                    @endif
                                </td>
                            </tr>
                             @endif

                            <tr>
                                <th>@lang('lang.class')</th>
                                <th>@lang('lang.section')</th>
                                <th>@lang('lang.subject')</th>
                                <th>@lang('lang.marks')</th>
                                <th>@lang('lang.homework_date')</th>
                                <th>@lang('lang.submission_date')</th>
                                <th>@lang('lang.evaluation_date')</th>
                                <th>@lang('lang.obtained_marks')</th>
                                <th>@lang('lang.status')</th>
                                <th>@lang('lang.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($homeworkLists as $value)
                                @php
                                    $student_result = $student_detail->homeworks->where('homework_id',$value->id)->first();;


                                    $uploadedContent = $student_detail->homeworkContents->where('homework_id',$value->id)->first();
                                @endphp

                            <tr>
                                <td>{{@$value->classes !=""?@$value->classes->class_name:""}}</td>
                                <td>{{@$value->sections !=""?@$value->sections->section_name:""}}</td>
                                <td>{{@$value->subjects !=""?@$value->subjects->subject_name:""}}</td>
                                <td>{{@$value->marks}}</td>
                                 <td  data-sort="{{strtotime(@$value->homework_date)}}" >
                                   {{@$value->homework_date != ""? dateConvert(@$value->homework_date):''}}

                                </td>
                                 <td  data-sort="{{strtotime(@$value->submission_date)}}" >
                                    {{@$value->submission_date != ""? dateConvert(@$value->submission_date):''}}

                                </td>
                                <td  data-sort="{{strtotime(@$value->evaluation_date)}}" >
                                @if(!empty(@$value->evaluation_date))
                               {{@$value->evaluation_date != ""? dateConvert(@$value->evaluation_date):''}}


                                @endif
                                </td>


                               <td>{{@$student_result != ""? @$student_result->marks:''}}</td>
                                <td>
                                    @if(@$student_result != "")

                                        @if(@$student_result->complete_status == "C")
                                            <button class="primary-btn small bg-success text-white border-0">@lang('lang.completed')</button>
                                        @else
                                            <button class="primary-btn small bg-warning text-white border-0">@lang('lang.incompleted')</button>
                                        @endif
                                    @else
                                        <button class="primary-btn small bg-warning text-white border-0">@lang('lang.incompleted')</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            Select
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">

                                         <a class="dropdown-item modalLink" title="Homework View" data-modal-size="modal-lg" href="{{route('student_homework_view', [@$value->class_id, @$value->section_id, @$value->id])}}">@lang('lang.view')</a>

                                         @if($uploadedContent == "")

                                          <a class="dropdown-item modalLink" title="Add Homework content" data-modal-size="modal-lg" href="{{route('add-homework-content', [@$value->id])}}">
                                            @lang('lang.add_content')</a>

                                          @else


                                          <a class="dropdown-item modalLink" title="Delete Homework content" data-modal-size="modal-md" href="{{route('deleteview-homework-content', [@$value->id])}}">
                                            @lang('lang.delete_uploaded_content')</a>


                                          <a class="dropdown-item"
                                                               href="{{route('download-uploaded-content',$value->id,Auth::user()->id)}}">
                                                                @lang('lang.download_uploaded_content') <span
                                                                    class="pl ti-download"></span>
                                          </a>

                                          @endif


                                        </div>
                                    </div>
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
