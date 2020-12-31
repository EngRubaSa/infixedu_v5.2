@extends('backEnd.master')
@section('mainContent')
    <style>
        th {
            border: 1px solid black;
            text-align: center;
        }

        td {
            text-align: center;
        }

        td.subject-name {
            text-align: left;
            padding-left: 10px !important;
        }

        table.marksheet {
            width: 100%;
            border: 1px solid #828bb2 !important;
        }

        table.marksheet th {
            border: 1px solid #828bb2 !important;
        }

        table.marksheet td {
            border: 1px solid #828bb2 !important;
        }

        table.marksheet thead tr {
            border: 1px solid #828bb2 !important;
        }

        table.marksheet tbody tr {
            border: 1px solid #828bb2 !important;
        }

        .studentInfoTable {
            width: 100%;
            padding: 0px !important;
        }

        .studentInfoTable td {
            padding: 0px !important;
            text-align: left;
            padding-left: 15px !important;
        }

        h4 {
            text-align: left !important;
        }

        hr {
            margin: 0px;
        }

        #grade_table th {
            border: 1px solid black;
            text-align: center;
            background: #351681;
            color: white;
        }

        #grade_table td {
            color: black;
            text-align: center !important;
            border: 1px solid black;
        }
    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.mark_sheet_report') @lang('lang.student') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.reports')</a>
                    <a href="#">@lang('lang.mark_sheet_report') @lang('lang.student')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria')</h3>
                    </div>
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
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'mark_sheet_report_student', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                        <div class="col-lg-3 mt-30-md md_mb_20">
                            <select class="w-100 bb niceSelect form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                    name="exam">
                                <option data-display="@lang('lang.select_exam') *" value="">@lang('lang.select_exam')
                                    *
                                </option>
                                @foreach($exams as $exam)
                                    <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>

                                @endforeach
                            </select>
                            @if ($errors->has('exam'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('exam') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md md_mb_20">
                            <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                    id="select_class" name="class">
                                <option data-display="@lang('lang.select_class') *" value="">@lang('lang.select_class')
                                    *
                                </option>
                                @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>

                                @endforeach
                            </select>
                            @if ($errors->has('class'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('class') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md md_mb_20" id="select_section_div">
                            <select class="w-100 bb niceSelect form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section"
                                    id="select_section" name="section">
                                <option data-display="@lang('lang.select_section') *"
                                        value="">@lang('lang.select_section') *
                                </option>
                            </select>
                            @if ($errors->has('section'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('section') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-lg-3 mt-30-md md_mb_20" id="select_student_div">
                            <select class="w-100 bb niceSelect form-control{{ $errors->has('student') ? ' is-invalid' : '' }}"
                                    id="select_student" name="student">
                                <option data-display="@lang('lang.select_student') *"
                                        value="">@lang('lang.select_student') *
                                </option>
                            </select>
                            @if ($errors->has('student'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('student') }}</strong>
                                </span>
                            @endif
                        </div>


                        <div class="col-lg-12 mt-20 text-right">
                            <button type="submit" class="primary-btn small fix-gr-bg">
                                <span class="ti-search"></span>
                                @lang('lang.search')
                            </button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>


    @if(isset($mark_sheet))
        <style>
            * {
                margin: 0;
                padding: 0;
            }

            body {
                font-size: 12px;
                font-family: 'Poppins', sans-serif;
            }

            .student_marks_table {
                width: 100%;
                margin: 30px auto 0 auto;
                padding-left: 10px;
                padding-right: 5px;
            }

            .text_center {
                text-align: center;
            }

            p {
                margin: 0;
                font-size: 12px;
                text-transform: capitalize;
            }

            ul {
                margin: 0;
                padding: 0;
            }

            li {
                list-style: none;
            }

            td {
                border: 1px solid #726E6D;
                padding: .3rem;
                text-align: center;
            }

            th {
                border: 1px solid #726E6D;
                text-transform: capitalize;
                text-align: center;
                padding: .5rem;
                white-space: nowrap;
            }

            thead {
                font-weight: bold;
                text-align: center;
                color: #415094;
                font-size: 10px
            }

            .custom_table {
                width: 100%;
            }

            table.custom_table thead th {
                padding-right: 0;
                padding-left: 0;
            }

            table.custom_table thead tr > th {
                border: 0;
                padding: 0;
            }

            /* tr:last-child td {
                border: 0 !important;
            }
            tr:nth-last-child(2) td {
                border: 0 !important;
            }
            tr:nth-last-child(3) td {
                border: 0 !important;
            } */

            table.custom_table thead tr th .fees_title {
                font-size: 12px;
                font-weight: 600;
                border-top: 1px solid #726E6D;
                padding-top: 10px;
                margin-top: 10px;
            }

            .border-top {
                border-top: 0 !important;
            }

            .custom_table th ul li {
            }

            .custom_table th ul li p {
                margin-bottom: 10px;
                font-weight: 500;
                font-size: 14px;
            }

            /* tbody td p{
              text-align: right;
            } */
            tbody td {
                padding: 0.8rem;
            }

            table {
                border-spacing: 10px;
                width: 65%;
                margin: auto;
            }

            .fees_pay {
                text-align: center;
            }

            .border-0 {
                border: 0 !important;
            }

            .copy_collect {
                text-align: center;
                font-weight: 500;
                color: #000;
            }

            .copyies_text {
                display: flex;
                justify-content: space-between;
                margin: 30px 0;
            }

            .copyies_text li {
                text-transform: capitalize;
                color: #000;
                font-weight: 500;
                border-top: 1px dashed #ddd;
            }

            .text_left {
                text-align: left;
            }

            .italic_text {
            }

            .student_info {

            }

            .student_info li {
                display: flex;
            }

            .info_details {
                display: flex;
                flex-wrap: wrap;
                margin-top: 30px;
                margin-bottom: 30px;
            }

            .info_details li > p {
                flex-basis: 20%;
            }

            .info_details li {
                display: flex;
                flex-basis: 50%;
            }

            .school_name {
                text-align: center;
            }

            .numbered_table_row {
                display: flex;
                justify-content: space-between;
                margin-top: 40px;
                align-items: center;
            }

            .numbered_table_row thead {
                border: 1px solid #415094
            }

            .numbered_table_row h3 {
                font-size: 24px;
                text-transform: uppercase;
                margin-top: 15px;
                font-weight: 500;
                display: inline-block;
                border-bottom: 2px solid #415094;
            }

            .ingle-report-admit .numbered_table_row td {
                border: 1px solid #726E6D;
                padding: .4rem;
                font-weight: 400;
                color: #415094;
            }

            .table#grade_table_new th {
                border: 1px solid #726E6D !important;
                padding: .6rem !important;
                font-weight: 600;
                color: #415094;
                font-size: 10px;
            }

            td.border-top.border_left_hide {
                border-left: 0;
                text-align: left;
                font-weight: 600;
            }

            .devide_td {
                padding: 0;
            }

            .devide_td p {
                border-bottom: 1px solid #415094;
            }

            .ssc_text {
                font-size: 20px;
                font-weight: 500;
                color: #415094;
                margin-bottom: 20px;
            }

            table#grade_table_new td {
                padding: 0 !important;
                font-size: 8px;
            }

            table#grade_table_new {
                border-bottom: 1px solid #726E6D !important;
            }

            .student_info {
                flex: 70% 0 0;
            }

            .marks_wrap_area {
                display: flex;
                align-items: center;
            }

            .numbered_table_row {
                display: flex;
                justify-content: center;
                margin-top: 40px;
                align-items: center;
            }
        </style>
        <section class="student-details">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('lang.mark_sheet_report')</h3>
                        </div>
                    </div>
                    <div class="col-lg-8 pull-right">
                        <a href="{{route('mark_sheet_report_print', [$input['exam_id'], $input['class_id'], $input['section_id'], $input['student_id']])}}"
                           class="primary-btn small fix-gr-bg pull-right" target="_blank"><i
                                    class="ti-printer"> </i> @lang('lang.print')</a>


                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <div class="single-report-admit">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex">

                                                    <div class="offset-2 col-lg-2">
                                                        <img class="logo-img" src="{{ generalSetting()->logo }}" alt="">
                                                    </div>
                                                    <div class="col-lg-6 ml-30">
                                                        <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                                        <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                        <p class="text-white mb-0">
                                                            Email: {{isset($email)?$email:'admin@demo.com'}} ,
                                                            Phone: {{isset(generalSetting()->phone)?generalSetting()->phone:'admin@demo.com'}} </p>
                                                    </div>
                                                    <div class="offset-2">

                                                    </div>
                                                </div>
                                                {{-- <div>
                                                    <img class="report-admit-img"  src="{{ file_exists(@$studentDetails->student_photo) ? asset($studentDetails->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" width="100" height="100" alt="{{asset($studentDetails->student_photo)}}">
                                                </div> --}}


                                            </div>


                                            {{--Start  Result Table --}}
                                            <div class="student_marks_table">
                                                <table class="custom_table">
                                                    <thead>

                                                    <tr class="numbered_table_row">
                                                        <td class="border-0">

                                                        </td>
                                                        <td class="border-0">
                                                            <div class="school_mark">
                                                                <p class="ssc_text"> {{$exam_details->title}}
                                                                    - {{$studentDetails->className->class_name}}
                                                                    ({{$studentDetails->section_name}})</p>
                                                                {{-- <div>
                                                                        <img src="{{ generalSetting()->logo }}" alt="">
                                                                </div>  --}}
                                                                <h3>academic transcript</h3>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <!-- first header  -->
                                                        <th colspan="1" class="text_left">


                                                            </p>
                                                            <div class="marks_wrap_area">
                                                                <ul class="student_info">
                                                                    <li><p>Name of Student &nbsp; : &nbsp; </p> &nbsp;
                                                                        <p class="italic_text">   {{$student_detail->full_name}}</p>
                                                                    </li>
                                                                    <li><p>Father's Name &nbsp; : &nbsp; </p> &nbsp; <p
                                                                                class="italic_text">   {{$student_detail->parents->fathers_name}} </p>
                                                                    </li>
                                                                    <li><p>Mother's Name &nbsp; : &nbsp; </p> &nbsp; <p
                                                                                class="italic_text">  {{$student_detail->parents->mothers_name}} </p>
                                                                    </li>
                                                                    <li><p>Name of institution &nbsp; : &nbsp; </p>
                                                                        &nbsp; <p
                                                                                class="italic_text">  {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </p>
                                                                    </li>
                                                                </ul>
                                                                {{-- sm_marks_grades --}}
                                                                <div class="col-lg-4 text-black">
                                                                    @php $marks_grade=DB::table('sm_marks_grades')->where('academic_id', getAcademicId())->get(); @endphp
                                                                    @if(@$marks_grade)
                                                                        <table class="table  table-bordered table-striped "
                                                                               id="grade_table">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>@lang('lang.staring')</th>
                                                                                <th>@lang('lang.ending')</th>
                                                                                <th>@lang('lang.gpa')</th>
                                                                                <th>@lang('lang.grade')</th>
                                                                                <th>@lang('lang.evalution')</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                            @foreach($marks_grade as $grade_d)
                                                                                <tr>
                                                                                    <td>{{$grade_d->percent_from}}</td>
                                                                                    <td>{{$grade_d->percent_upto}}</td>
                                                                                    <td>{{$grade_d->gpa}}</td>
                                                                                    <td>{{$grade_d->grade_name}}</td>
                                                                                    <td class="text-left">{{$grade_d->description}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    @endif
                                                                </div>
                                                                {{--end sm_marks_grades --}}
                                                            </div>
                                                            <div>
                                                                <ul class="info_details">
                                                                    {{-- <li><p>Name of Centre &nbsp;  &nbsp; </p> &nbsp; <p class="italic_text">  (420) brammanbaria  </p></li> --}}
                                                                    {{-- <li><p>Type  &nbsp;  &nbsp; </p> &nbsp; <p class="italic_text">  Regular  </p></li> --}}
                                                                    <li><p>Roll No. &nbsp; &nbsp; </p> &nbsp; <p>
                                                                            <strong>{{$student_detail->roll_no}}</strong>
                                                                        </p></li>
                                                                    <li><p>Admission No. &nbsp; &nbsp; </p> &nbsp; <p>
                                                                            <strong> {{$student_detail->admission_no}}</strong>
                                                                        </p></li>
                                                                    {{-- <li><p>Group  &nbsp;  &nbsp; </p> &nbsp; <p class="italic_text"> business seience</p></li> --}}
                                                                    <li><p>Date of birth &nbsp; &nbsp; </p> &nbsp; <p>
                                                                            <strong> {{$student_detail->date_of_birth != ""? dateConvert($studentDetails->date_of_birth):''}}</strong>
                                                                        </p></li>
                                                                </ul>
                                                            </div>

                                                        </th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                                {{-- {{ dd($optional_subject) }} --}}
                                                <table class="custom_table">
                                                    <thead>

                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <!-- first header  -->
                                                        <th>SI.NO</th>
                                                        <th colspan="2">Name of subjects</th>
                                                        <th>letter grade</th>
                                                        <th>Total Marks</th>
                                                        <th>Grade Point</th>
                                                        <th>GPA <br> Without Additional</th>
                                                        <th>GPA</th>
                                                    </tr>
                                                    @php
                                                        $optional_countable_gpa=0;

                                                        $main_subject_total_gpa=0;
                                                         $Optional_subject_count=$subjects->count();

                                                    @endphp
                                                    @php $sum_gpa= 0;  $resultCount=1; $subject_count=1; $tota_grade_point=0; $this_student_failed=0; $count=1; @endphp
                                                    @foreach($mark_sheet as $data)
                                                        @if($data->subject_id != $optional_subject)
                                                            <tr>
                                                                <td class="border-top"
                                                                    style="border-bottom: 1px solid black;">{{ $count }}</td>
                                                                <td colspan="2" class="border-top"
                                                                    style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                                                                    <p>{{$data->subject->subject_name}}</p></td>
                                                                <td class="border-top"
                                                                    style="border-bottom: 1px solid black;">
                                                                    <p>
                                                                        @php
                                                                            $result = markGpa($data->total_marks);
                                                                        @endphp
                                                                        {{@$result->grade_name}}
                                                                    </p>
                                                                </td>
                                                                <td class="border-top"
                                                                    style="border-bottom: 1px solid black;">
                                                                    <p>
                                                                        {{@$data->total_marks}}
                                                                    </p>
                                                                </td>
                                                                @php
                                                                    $main_subject_total_gpa += $result->gpa;
                                                                @endphp
                                                                <td class="border-top"
                                                                    style="border-bottom: 1px solid black;"><p>
                                                                        {{@$result->gpa}}

                                                                    </p>
                                                                </td>
                                                                @if ($count==1)
                                                                    <td rowspan="{{ $Optional_subject_count+1 }}"
                                                                        class="border-top"
                                                                        style="border-bottom: 1px solid black;">
                                                                        <p id="main_subject_total_gpa"></p>
                                                                    </td>
                                                                    <td rowspan="{{ $Optional_subject_count+3 }}"
                                                                        class="border-top">
                                                                        <p id="final_result">
                                                                        </p>
                                                                    </td>


                                                                @endif
                                                                @php
                                                                    $count++
                                                                @endphp

                                                            </tr>

                                                            <tr>
                                                                <!-- first td wrap  -->
                                                                @else
                                                                    <td colspan="6" class="border-top border_left_hide">
                                                                        Additional Subject
                                                                    </td>
                                                            </tr>



                                                            <td class="border-top"
                                                                style="border-bottom:1px solid black">{{ $count }}</td>
                                                            <td colspan="2" class="border-top"
                                                                style="border-bottom:1px solid black">
                                                                <p>{{$data->subject->subject_name}}</p></td>
                                                            <td class="border-top"
                                                                style="border-bottom:1px solid black">
                                                                <p>
                                                                    {{@$result->grade_name}}
                                                                </p>
                                                            </td>

                                                            <td class="border-top"
                                                                style="border-bottom:1px solid black">
                                                                <p>
                                                                    {{@$result->gpa}}
                                                                </p>
                                                            </td>
                                                            <td class="border-top devide_td "
                                                                style="border-bottom:1px solid black"><p>GP
                                                                    above {{ $optional_subject_setup->gpa_above }}</p>
                                                                <span>
                                                            @php
                                                                if($result->gpa > $optional_subject_setup->gpa_above){

                                                                    $optional_countable_gpa= $result->gpa-$optional_subject_setup->gpa_above;
                                                                }else{
                                                                    $optional_countable_gpa=0;
                                                                }
                                                            @endphp
                                                                    {{$optional_countable_gpa}}
                                                        </span>
                                                            </td>



                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                                <script>
                                                    function myFunction(value, subject) {
                                                        if (value != "") {
                                                            var res = Number(value / subject).toFixed(2);
                                                        } else {
                                                            var res = 0;
                                                        }
                                                        document.getElementById("main_subject_total_gpa").innerHTML = res;
                                                    }

                                                    function myFunction2(value, subject) {
                                                        if (value != "") {
                                                            var res = Number(value / subject).toFixed(2);
                                                        } else {
                                                            var res = 0;
                                                        }
                                                        document.getElementById("final_result").innerHTML = res;
                                                    }

                                                    myFunction({{ $main_subject_total_gpa }}, {{ $Optional_subject_count }});
                                                    myFunction2( {{ $main_subject_total_gpa +$optional_countable_gpa  }}, {{ $Optional_subject_count-1 }});

                                                </script>


                                                <table style="width:100%" class="border-0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="border-0"><p class="result-date"
                                                                                style="text-align:left; float:left; display:inline-block; margin-top:50px; padding-left: 0;">
                                                                Date of Publication of Result : <b> February 3, 2020,
                                                                    6:40 am</b>
                                                            </p></td>
                                                        <td class="border-0">
                                                            <p style="text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">
                                                                ( Exam Controller )</p>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>


                                            {{--End  Result Table --}}


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif


@endsection
