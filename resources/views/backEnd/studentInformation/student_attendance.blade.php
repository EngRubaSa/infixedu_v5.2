@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.student_attendance')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.student_information')</a>
                <a href="#">@lang('lang.student_attendance')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="main-title mt_0_sm mt_0_md">
                        <h3 class="mb-30">@lang('lang.select_criteria') </h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 text_sm_right text_xs_left col-sm-6">
                    <a href="{{route('student-attendance-import')}}" class="primary-btn small fix-gr-bg pull-right mb-20"><span class="ti-plus pr-2"></span>Import Attendance</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @elseif(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                        @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-4 col-md-4 sm_mb_20 sm2_mb_20">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected': ''):'' }}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                     @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4" id="select_section_div">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section') *</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-4 mt-30-md">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}" id="startDate" type="text"
                                                    name="attendance_date" autocomplete="off" value="{{isset($date)? $date: date('m/d/Y')}}">
                                                <label for="startDate">@lang('lang.attendance') @lang('lang.date')*</label>
                                                <span class="focus-border"></span>
                                                
                                                @if ($errors->has('attendance_date'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('attendance_date') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('lang.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
@if(isset($already_assigned_students))

 {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-attendance-store', 'method' => 'POST', 'enctype' => 'multipart/form-data'])}} --}}

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('lang.student_attendance') | <small>@lang('lang.class'): {{$search_info['class_name']}}, @lang('lang.section'): {{$search_info['section_name']}}, @lang('lang.date'): {{dateConvert($search_info['date'])}}</small></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 no-gutters">
                            @if($attendance_type != "" && $attendance_type == "H")
                            <div class="alert alert-warning">@lang('lang.attendance_already_submitted_as_holiday')</div>
                            @elseif($attendance_type != "" && $attendance_type != "H")
                            <div class="alert alert-success">@lang('lang.attendance_already_submitted')</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-30">
                        <div class="col-lg-6  col-md-6 no-gutters text-md-left mark-holiday ">
                            <form action="{{route('student-attendance-holiday')}}" method="POST">
                                @csrf
                            <input type="hidden" name="purpose" value="mark">
                            <input type="hidden" name="class_id" value="{{$class_id}}">
                            <input type="hidden" name="section_id" value="{{$section_id}}">
                            <input type="hidden" name="attendance_date" value="{{$date}}">
                                <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                    @lang('lang.mark_holiday')
                                </button>
                        </form>
                        </div>
                        <div class="col-lg-6  col-md-6 no-gutters text-md-right mark-holiday">
                            <form action="{{route('student-attendance-holiday')}}" method="POST">
                                @csrf
                            <input type="hidden" name="purpose" value="unmark">
                            <input type="hidden" name="class_id" value="{{$class_id}}">
                            <input type="hidden" name="section_id" value="{{$section_id}}">
                            <input type="hidden" name="attendance_date" value="{{$date}}">
                                <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                    @lang('lang.unmark_holiday')
                                </button>
                        </form>
                        </div>
            {{--@if(userPermission(69))
                        <div class="col-lg-6 col-md-6 text-md-right">
                            <button type="submit" class="primary-btn fix-gr-bg mb-20">
                            <span class="ti-save pr"></span>
                                @lang('lang.save') @lang('lang.attendance')
                            </button>
                        </div>
            @endif--}}
                    </div>

                   

                    <input type="hidden" name="date" class="attendance_date" value="{{isset($date)? $date: ''}}">

                    <!-- <div class="d-flex justify-content-between mb-20"> -->
                        <!-- <button type="submit" class="primary-btn fix-gr-bg mr-20" onclick="javascript: form.action='{{route('student-attendance-holiday')}}'">
                            <span class="ti-hand-point-right pr"></span>
                            mark as holiday
                        </button> -->

                        
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="default_table" class="display school-table" cellspacing="0" width="100%">
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
                                        <th>@lang('lang.admission') @lang('lang.no')</th>
                                        <th>@lang('lang.student') @lang('lang.name')</th>
                                        <th>@lang('lang.roll') @lang('lang.number')</th>
                                        <th>@lang('lang.attendance')</th>
                                        <th>@lang('lang.note')</th>
                                    </tr>
                                </thead>
{{-- {{dd($already_assigned_students)}} --}}
                                <tbody>
                                    @foreach($already_assigned_students as $already_assigned_student)
                                    <tr>
                                        <td>{{$already_assigned_student->studentInfo->admission_no}}<input type="hidden" name="id[]" value="{{$already_assigned_student->studentInfo->id}}"></td>
                                        <td>
                                            @if(!empty($already_assigned_student->studentInfo))
                                            {{$already_assigned_student->studentInfo->first_name.' '.$already_assigned_student->studentInfo->last_name}}
                                            @endif
                                        </td>
                                        <td>{{$already_assigned_student->studentInfo!=""?$already_assigned_student->studentInfo->roll_no:""}}</td>
                                        <td>
                                            <div class="input-effect">
                                                <textarea class="primary-input form-control note_{{$already_assigned_student->studentInfo->id}}" cols="0" rows="2" name="note[{{$already_assigned_student->studentInfo->id}}]" id="">{{$already_assigned_student->notes}}</textarea>
                                                <label>@lang('lang.add_note_here')</label>
                                                <span class="focus-border textarea"></span>
                                                <span class="invalid-feedback">
                                                    <strong>@lang('lang.error')</strong>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                         
                                                    <input type="radio" data-id="{{$already_assigned_student->studentInfo->id}}" name="attendance[{{$already_assigned_student->studentInfo->id}}]" id="attendanceP{{$already_assigned_student->studentInfo->id}}" value="P" class="common-radio attendanceP attendance_type" {{$already_assigned_student->attendance_type == "P"? 'checked':''}}>
                                                    <label for="attendanceP{{$already_assigned_student->studentInfo->id}}">@lang('lang.present')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" data-id="{{$already_assigned_student->studentInfo->id}}" name="attendance[{{$already_assigned_student->studentInfo->id}}]" id="attendanceL{{$already_assigned_student->studentInfo->id}}" value="L" class="common-radio attendance_type" {{$already_assigned_student->attendance_type == "L"? 'checked':''}}>
                                                    <label for="attendanceL{{$already_assigned_student->studentInfo->id}}">@lang('lang.late')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" data-id="{{$already_assigned_student->studentInfo->id}}" name="attendance[{{$already_assigned_student->studentInfo->id}}]" id="attendanceA{{$already_assigned_student->studentInfo->id}}" value="A" class="common-radio attendance_type" {{$already_assigned_student->attendance_type == "A"? 'checked':''}}>
                                                    <label for="attendanceA{{$already_assigned_student->studentInfo->id}}">@lang('lang.absent')</label>
                                                </div>
                                                <div>
                                                    <input type="radio" data-id="{{$already_assigned_student->studentInfo->id}}" name="attendance[{{$already_assigned_student->studentInfo->id}}]" id="attendanceH{{$already_assigned_student->studentInfo->id}}" value="F" class="common-radio attendance_type" {{$already_assigned_student->attendance_type == "F"? 'checked':''}}>
                                                    <label for="attendanceH{{$already_assigned_student->studentInfo->id}}">@lang('lang.half_day')</label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                    @foreach($new_students as $student)
                                    <tr>
                                        <td>{{$student->admission_no}}<input type="hidden" name="id[]" value="{{$student->id}}"></td>
                                        <td>{{$student->first_name.' '.$student->last_name}}</td>
                                        <td>{{$student->roll_no}}</td>
                                        <td>
                                            <div class="input-effect">
                                                <textarea class="primary-input form-control note_{{$student->id}}" cols="0" rows="2" name="note[{{$student->id}}]" id=""></textarea>
                                                <label>@lang('lang.add_note_here')</label>
                                                <span class="focus-border textarea"></span>
                                                <span class="invalid-feedback">
                                                    <strong>@lang('lang.error')</strong>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio" data-id="{{$student->id}}" name="attendance[{{$student->id}}]" id="attendanceP{{$student->id}}" value="P" class="common-radio attendanceP attendance_type" >
                                                    <label for="attendanceP{{$student->id}}">@lang('lang.present')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" data-id="{{$student->id}}" name="attendance[{{$student->id}}]" id="attendanceL{{$student->id}}" value="L" class="common-radio attendance_type">
                                                    <label for="attendanceL{{$student->id}}">@lang('lang.late')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio" data-id="{{$student->id}}" name="attendance[{{$student->id}}]" id="attendanceA{{$student->id}}" value="A" class="common-radio attendance_type">
                                                    <label for="attendanceA{{$student->id}}">@lang('lang.absent')</label>
                                                </div>
                                                <div>
                                                    <input type="radio" data-id="{{$student->id}}" name="attendance[{{$student->id}}]" id="attendanceH{{$student->id}}" value="F" class="common-radio attendance_type">
                                                    <label for="attendanceH{{$student->id}}">@lang('lang.half_day')</label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- {{ Form::close() }} --}}
                </div>
            </div>
@endif

    </div>
</section>
@endsection

@push('script')
    <script>
        $(document).on('change','.attendance_type',function (){
            let student_id = $(this).data('id');
            let attendance_type ='';
            if ($(this).is(':checked'))
            {
                attendance_type = $(this).val();
            }
            let attendance_date = $('.attendance_date').val();
            let notes = $('.note_'+student_id).val();
            $.ajax({
                url : "{{route('student-attendance-store')}}",
                method : "POST",
                data : {
                    student_id : student_id,
                    attendance_type : attendance_type,
                    attendance_date : attendance_date,
                    notes : notes,
                },
                success : function (result){
                    toastr.success('Attendance Has Been Saved', 'Successful', {
                        timeOut: 5000
                    })
                }
            })


        })
    </script>
    @endpush
