

    <div class="container-fluid mt-30">
        <div class="student-details">
            <div class="student-meta-box">
                <div class="single-meta">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save-homework-evaluation-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <table id="" class="table table-condensed table-hover" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>@lang('lang.admission_no')</th>
                                    <th>@lang('lang.student_name')</th>
                                    <th>@lang('lang.marks')</th>
                                    <th>@lang('lang.comments')</th>
                                    <th>@lang('lang.home_work') @lang('lang.status')</th>
                                    <th>@lang('lang.download')</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($students as $value)
                                @php
                                    $submitted_student =  App\SmHomework::evaluationHomework($value->id, $homeworkDetails->id);

                                    @$uploadedContent = App\SmHomework::uploadedContent(@$value->id, $homeworkDetails->id);

                                @endphp

                                @if($submitted_student != "")
                                <tr>
                                    <td width="8%">{{$submitted_student->studentInfo->admission_no}}</td>
                                    <td width="10%">{{$submitted_student->studentInfo->full_name}}</td>
                                    <td width="10%">
                                        <div class="input-effect">

                                            @if ($submitted_student->marks>0)
                                            <input class="primary-input form-control" min="0" max="{{$homeworkDetails->marks}}" type="number" name="marks[]" value="{{$submitted_student->marks}}">
                                            <span class="focus-border"></span>
                                            <label></label>
                                            @if ($errors->has('marks'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('marks') }}</strong>
                                            </span>
                                            @endif
                                            @else
                                            <input class="primary-input form-control" min="0" max="{{$homeworkDetails->marks}}" type="number" name="marks[]" value="{{$submitted_student->marks}}">
                                            <span class="focus-border"></span>
                                            <label>@lang('lang.marks')</label>
                                            @if ($errors->has('marks'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('marks') }}</strong>
                                            </span>
                                            @endif
                                            @endif
                                            {{-- <input class="primary-input form-control" max="{{$homeworkDetails->marks}}" type="number" name="marks[]" value="{{$submitted_student->marks}}"> --}}

                                           
                                        </div>
                                        <input type="hidden" name="student_id[]" value="{{$submitted_student->studentInfo->id}}">
                                        <input type="hidden" name="homework_id" value="{{$homework_id}}">
                                        
                                    </td>

                                    <td>
                                        <div class="d-flex">
                                            <div class="mr-30">
                                                <input type="radio" id="buttonG{{$submitted_student->studentInfo->id}}" class="common-radio" name="teacher_comments[{{$submitted_student->studentInfo->id}}]" value="G" {{$submitted_student->teacher_comments == "G"? 'checked':''}}> &nbsp; &nbsp; &nbsp; &nbsp;<label  for="buttonG{{$submitted_student->studentInfo->id}}">@lang('lang.good')</label> &nbsp; &nbsp;
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" id="buttonNG{{$submitted_student->studentInfo->id}}" class="common-radio" name="teacher_comments[{{$submitted_student->studentInfo->id}}]" value="NG" {{$submitted_student->teacher_comments == "NG"? 'checked':''}}> &nbsp; &nbsp;<label for="buttonNG{{$submitted_student->studentInfo->id}}">@lang('lang.not') @lang('lang.good')</label>
                                            </div>
                                        </div>

                                    </td>

                                    <td>
                                        <div class="d-flex">
                                            <div class="mr-30">
                                                <input type="radio" id="buttonC{{$submitted_student->studentInfo->id}}" class="common-radio" name="homework_status[{{$submitted_student->studentInfo->id}}]" value="C" {{$submitted_student->complete_status == "C"? 'checked':''}}> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<label for="buttonC{{$submitted_student->studentInfo->id}}">@lang('lang.completed')</label> &nbsp; &nbsp;
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" id="buttonNC{{$submitted_student->studentInfo->id}}" class="common-radio" name="homework_status[{{$submitted_student->studentInfo->id}}]" value="NC" {{$submitted_student->complete_status == "NC"? 'checked':''}}>&nbsp; &nbsp; <label for="buttonNC{{$submitted_student->studentInfo->id}}">@lang('lang.not') @lang('lang.completed')</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td width="8%">{{$value->admission_no}}</td>
                                    <td width="10%">{{$value->full_name}}</td>
                                    <td width="10%">
                                        <div class="input-effect">
                                            <input class="primary-input form-control" type="number" min="0" max="{{@$homeworkDetails->marks}}" name="marks[]">
                                            <span class="focus-border"></span>
                                            <label>@lang('lang.marks')</label>
                                            @if ($errors->has('marks'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('marks') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        <input type="hidden" name="student_id[]" value="{{$value->id}}">
                                        <input type="hidden" name="homework_id" value="{{$homework_id}}">
                                        
                                    </td>

                                    <td>

                                        <div class="d-flex">
                                            <div class="mr-30">
                                                <input type="radio" id="buttonG{{$value->id}}" class="common-radio" name="teacher_comments[{{$value->id}}]" value="G" checked> &nbsp; &nbsp;<label for="buttonG{{$value->id}}">@lang('lang.good')</label> &nbsp; &nbsp;
                                            </div>
                                            <div class="mr-30">
                                                <input type="radio" id="buttonNG{{$value->id}}" class="common-radio" class="common-radio" name="teacher_comments[{{$value->id}}]" value="NG"> &nbsp; &nbsp;<label for="buttonNG{{$value->id}}">@lang('lang.not') @lang('lang.good')</label>
                                            </div>
                                        </div>
                                    </td>


                                    <td>
                                        <div class="d-flex">
                                        <div class="mr-30">

                                            <input type="radio" id="buttonC{{$value->id}}" class="common-radio" name="homework_status[{{$value->id}}]" value="C" checked> &nbsp; &nbsp;<label for="buttonC{{$value->id}}">@lang('lang.completed')</label> &nbsp; &nbsp;
                                        </div>
                                        <div class="mr-30">
                                            <input type="radio" id="buttonNC{{$value->id}}" class="common-radio" name="homework_status[{{$value->id}}]" value="NC">&nbsp; &nbsp; <label for="buttonNC{{$value->id}}">@lang('lang.not') @lang('lang.completed')</label>
                                        </div>
                                    </div>

                                    </td>
                                    <td>
                                        @if($uploadedContent != "")

                                         <a class="dropdown-item"
                                                               href="{{route('downloadHomeWorkContent',[$homework_id,$value->id])}}"> <span
                                                                    class="pl ti-download"></span>
                                                                    @endif

                                    </td>
                                    {{-- <td>
                                        @if($uploadedContent != "")

                                         <a class="dropdown-item"
                                                               href="{{url('download-uploaded-content/'.showPicName(@$uploadedContent->file))}}"> <span
                                                                    class="pl ti-download"></span>
                                                                    @endif

                                    </td> --}}
                                    {{-- <a class="dropdown-item"
                                    href="{{url('download-uploaded-content/'.$value->id)}}">
                                    <span class="pl ti-download"></span>
               
                                 </a> --}}

                                </tr>

                                @endif

                                @endforeach
                            </tbody>
                        </table>

                        <div class="col-lg-12 mt-30">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('evaluation_date') ? ' is-invalid' : '' }}" id="evaluation_date" type="text"
                                                placeholder="Evaluation Date" name="evaluation_date" value="{{date('m/d/Y')}}" autocomplete="off" readonly="true">
                                                <span class="focus-border"></span>
                                                @if ($errors->has('evaluation_date'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('evaluation_date') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="evaluation_date_icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button class="primary-btn fix-gr-bg pull-right mb-20 mt-10">
                                        <span class="ti-check"></span>
                                        @lang('lang.save') @lang('lang.home_work')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                       <div class="col-lg-12">
                           <div class="row">

                            <h4 class="stu-sub-head">@lang('lang.home_work') @lang('lang.summary')</h4>

                        </div>
                    </div> 

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                        @lang('lang.home_work') @lang('lang.date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))
                                    
                                    {{$homeworkDetails->homework_date != ""? dateConvert($homeworkDetails->homework_date):''}}

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                        @lang('lang.submission') @lang('lang.date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))
                                   
                                     {{$homeworkDetails->submission_date != ""? dateConvert($homeworkDetails->submission_date):''}}

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                        @lang('lang.evaluation') @lang('lang.date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if($homeworkDetails->evaluation_date != "")
                                  
                                        {{$homeworkDetails->evaluation_date != ""? dateConvert($homeworkDetails->evaluation_date):''}}
              
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                        @lang('lang.created_by')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                   @if(isset($homeworkDetails))
                                   {{$homeworkDetails->users->full_name}}
                                   @endif
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                    @lang('lang.evaluated_by')
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($homeworkDetails))
                                {{$homeworkDetails->users->full_name}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                    @lang('lang.class')
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                               @if(isset($homeworkDetails))
                               {{$homeworkDetails->classes->class_name}}
                               @endif
                           </div>
                       </div>
                   </div>
               </div>

               <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                                @lang('lang.section')
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{$homeworkDetails->sections->section_name}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                                @lang('lang.subject')
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{$homeworkDetails->subjects->subject_name}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                                @lang('lang.marks')
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            
                            {{$homeworkDetails->marks}}
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                                @lang('lang.attach_file') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if($homeworkDetails->file != "")
                             <a href="{{$homeworkDetails->file}}" download="">
                                    @lang('lang.download')  <span class="pl ti-download"></span>
                             {{-- <a href="{{url('evaluation-document/'.showPicName($homeworkDetails->file))}}">
                                    Download <span class="pl ti-download"></span> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                                @lang('lang.description') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{$homeworkDetails->description}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $('.school-table-data').DataTable({
        bLengthChange: false,
        language: {
            search: "<i class='ti-search'></i>",
            searchPlaceholder: 'Quick Search',
            paginate: {
                next: "<i class='ti-arrow-right'></i>",
                previous: "<i class='ti-arrow-left'></i>"
            }
        },
        buttons: [ ],
        columnDefs: [
        {
            visible: false
        }
        ],
        responsive: true
    });

    // for evaluation date

    $('#evaluation_date_icon').on('click', function() {
        $('#evaluation_date').focus();
    });

    $('.primary-input.date').datepicker({
        autoclose: true
    });

    $('.primary-input.date').on('changeDate', function(ev) {
        $(this).focus();
    });

</script>
