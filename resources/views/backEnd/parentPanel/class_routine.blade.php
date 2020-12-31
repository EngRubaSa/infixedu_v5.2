@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.class_routine')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="{{route('parent_class_routine', [$student_detail->id])}}">@lang('lang.class_routine')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-20">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.student_information')</h3>
                    </div>
                </div>
                <div class="col-lg-6 pull-right mb-20">
                    <a href="{{route('classRoutinePrint',  [$student_detail->class_id, $student_detail->section_id])}}"
                       class="primary-btn small fix-gr-bg pull-right" target="_blank"><i
                                class="ti-printer"> </i> @lang('lang.print')</a>

                </div>

            </div>

            <div class="row">
                <div class="col-lg-3 mb-30">
                    <!-- Start Student Meta Information -->
                    <div class="student-meta-box ">
                        <div class="student-meta-top"></div>
                        <img class="student-meta-img img-100" src="{{asset($student_detail->student_photo)}}" alt="">
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
                                        @if($student_detail->className !="" && $student_detail->session !="")
                                            {{$student_detail->className->class_name}}
                                            ({{$student_detail->session->session}})
                                        @endif
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
                    <table id="default_table2" class="display school-table " cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>@lang('lang.class_period')</th>
                            @foreach($sm_weekends as $sm_weekend)
                                <th>{{$sm_weekend->name}}</th>
                            @endforeach
                        </tr>
                        </thead>

                        <tbody>
                       @foreach($class_times as $class_time)
                        <tr>
                            <td>
                                {{@$class_time->period}}
                                <br>
                                {{date('h:i A', strtotime(@$class_time->start_time)).' - '.date('h:i A', strtotime(@$class_time->end_time))}}
                            </td>

                            @foreach($sm_weekends as $sm_weekend)

                            <td>
                                @if(@$class_time->is_break == 0)
                                @if(@$sm_weekend->is_weekend != 1)
                                

                                @php
                                    $assinged_class_routine = App\SmClassRoutineUpdate::assingedClassRoutine(@$class_time->id, @$sm_weekend->id, @$class_id, @$section_id);
                                @endphp
                                @if(@$assinged_class_routine == "")

                                @if(userPermission(247))

                                <div class="col-lg-6 text-right">
                                    <a href="{{route('add-new-routine', [@$class_time->id, @$sm_weekend->id, @$class_id, @$section_id])}}" class="primary-btn small tr-bg icon-only mr-10 modalLink" data-modal-size="modal-md" title="Create Class routine">
                                        <span class="ti-plus" id="addClassRoutine"></span>
                                    </a>
                                </div>
                                @endif

                                @else
                                    <span class="">{{@$assinged_class_routine->subject !=""?@$assinged_class_routine->subject->subject_name:""}}</span>
                                    <br>
                                    <span class="">{{@$assinged_class_routine->classRoom!=""?@$assinged_class_routine->classRoom->room_no:""}}</span></br>
                                    <span class="tt">{{@$assinged_class_routine->teacherDetail!=""?@$assinged_class_routine->teacherDetail->full_name:""}}</span></br>
                                    @if(userPermission(248))

                                    <a href="{{url('edit-class-routine', [@$class_time->id, @$sm_weekend->id, @$class_id, @$section_id, @$assinged_class_routine->subject_id, @$assinged_class_routine->room_id, @$assinged_class_routine->id, @$assinged_class_routine->teacher_id])}}" class="modalLink" data-modal-size="modal-md" title="Edit Class routine"><span class="ti-pencil-alt" id="addClassRoutine"></span></a>
                                    @endif
                                    @if(userPermission(249))

                                    <a href="{{route('delete-class-routine-modal', [@$assinged_class_routine->id])}}" class="modalLink" data-modal-size="modal-md" title="Delete Class routine"><span class="ti-trash" id="addClassRoutine"></span></a>
                               
                                    @endif
                                    @endif

                                
                                @else
                                        @lang('lang.weekend')

                                @endif
                                @endif
                            </td>

                            @endforeach
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


@endsection
