@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Class Routine </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.class_routine')</a>
            </div>
        </div>
    </div>
</section>

@if(isset($class_times))
<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.class_routine')</h3>
                </div>
            </div>
            <div class="col-lg-6 pull-right">
                <a href="{{route('classRoutinePrint', [@$class_id, @$section_id])}}" class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> Print</a>
                
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table id="default_table" class="display school-table " cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th> @lang('lang.class_period') </th>
                            @foreach($sm_weekends as $sm_weekend)
                            <th>{{@$sm_weekend->name}}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($class_times as $class_time)
                        <tr>
                            <td>{{@$class_time->period}}<br>{{date('h:i A', strtotime(@$class_time->start_time)).' - '.date('h:i A', strtotime(@$class_time->end_time))}}</td>
                            @foreach($sm_weekends as $sm_weekend)

                            <td>
                                @if(@$class_time->is_break == 0)
                                @if(@$sm_weekend->is_weekend != 1)
                                

                                @php
                                    // @$assinged_class_routine = $class_time->routineUpdates->where('day',$sm_weekend->id)->where('class_id',$class_id)->where('section_id',$section_id)->frist();
                                    @$assinged_class_routine = App\SmClassRoutineUpdate::assingedClassRoutine($class_time->id, $sm_weekend->id, $class_id, $section_id);
                                @endphp
                                @if(@$assinged_class_routine != "")
                                    <span class="">{{@$assinged_class_routine->subject->subject_name}}</span>
                                    <br>
                                    <span class="">{{@$assinged_class_routine->classRoom->room_no}}</span></br>
                                    <span class="tt">{{@$assinged_class_routine->teacherDetail->full_name}}</span></br>
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

@endif



@endsection
