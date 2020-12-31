@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.examinations') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.examinations')</a>
                <a href="{{route('marks_register')}}">@lang('lang.marks_register')</a>
                <a href="{{route('marks_register_create')}}">@lang('lang.fill_marks')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.select_criteria')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success') != "")
                    @if(session()->has('message-success'))
                    <div class="alert alert-success">
                        {{ session()->get('message-success') }}
                    </div>
                    @endif
                @endif
                 @if(session()->has('message-danger') != "")
                    @if(session()->has('message-danger'))
                    <div class="alert alert-danger">
                        {{ session()->get('message-danger') }}
                    </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks_register', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="Select Exam *" value="">@lang('lang.select_exam') *</option>
                                    @foreach($exam_types as $exam_type)
                                        <option value="{{@$exam_type->id}}" {{isset($exam_id)? ($exam_id == $exam_type->id? 'selected':''):''}}>{{@$exam_type->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md">
                                <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{@$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section') *</option>
                                </select>
                                @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>


                            <div class="col-lg-3 mt-30-md" id="select_subject_div">
                                <select class="w-100 bb niceSelect form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_subject" name="subject">
                                    <option data-display="Select subject *" value="">@lang('lang.select_subjects') *</option>
                                </select>
                                @if ($errors->has('subject'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                                @endif
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
    </div>
</section>

@if(isset($students))

<section class="mt-20">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-6 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('lang.fill_marks')</h3>
                </div>
            </div>
        </div>


    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks_register_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'marks_register_store']) }} 


        <input type="hidden" name="exam_id" value="{{@$exam_id}}">
        <input type="hidden" name="class_id" value="{{@$class_id}}">
        <input type="hidden" name="section_id" value="{{@$section_id}}">
        <input type="hidden" name="subject_id" value="{{@$subject_id}}"> 

        <div class="row">
            <div class="col-lg-12">
                <table id="default_table" class="display school-table" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th rowspan="2" >@lang('lang.admission_no').</th>
                            <th rowspan="2" >@lang('lang.roll_no').</th>
                            <th rowspan="2" >@lang('lang.student')</th>
                            <th colspan="{{@$number_of_exam_parts}}"> {{@$subjectNames->subject_name}}</th> 
                            <th rowspan="2">@lang('lang.is_present')</th>
                        </tr>
                        <tr>
                            @foreach($marks_entry_form as $part)
                            <th>{{@$part->exam_title}} ( {{@$part->exam_mark}} ) </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>                        
                        @php $colspan = 3; $counter = 0;  @endphp
                        @foreach($students as $student)
                        <tr>
                            <td>{{$student->admission_no}}
                                <input type="hidden" name="student_ids[]" value="{{@$student->id}}">
                                <input type="hidden" name="student_rolls[{{$student->id}}]" value="{{@$student->roll_no}}">
                                <input type="hidden" name="student_admissions[{{@$student->id}}]" value="{{@$student->admission_no}}">
                            </td>
                            <td>{{@$student->roll_no}}</td>
                            <td>{{@$student->full_name}}</td>
                            @php $entry_form_count=0; @endphp
                            @foreach($marks_entry_form as $part)
                            <?php 
                            $search_mark = App\SmMarkStore::get_mark_by_part($student->id, $exam_id, $class_id, $section_id, $subject_id, $part->id);
                            $is_absent = App\SmMarkStore::get_mark_by_part($student->id, $exam_id, $class_id, $section_id, $subject_id, $part->id);

                            ?>
                            <td>
                                <div class="input-effect mt-10"> 
                                    <p>{{@$search_mark}}</p>   
                                </div>                                
                            </td>
                            @endforeach
                            <?php 

                                $is_absent_check = App\SmMarksRegister::is_absent_check($part->exam_term_id, $part->class_id, $part->section_id, $part->subject_id, $student->id);
                            ?>
                            <td>
                                <div class="input-effect">
                                    @if(@$is_absent_check->attendance_type == 'P')
                                    <button class="primary-btn small fix-gr-bg" type="button">@lang('lang.present')</button>
                                    @else
                                    <button class="primary-btn small bg-danger text-white border-0" type="button">@lang('lang.absent')</button>
                                    @endif
                                </div>
                                    
                            </td>

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
