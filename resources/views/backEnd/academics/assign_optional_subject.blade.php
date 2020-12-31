@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.optional_subject')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.academics')</a>
                <a href="#">@lang('lang.optional_subject')</a>
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
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign_optional_subject_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                          
                                <div class="col-lg-4 mt-30-md">
                                    <select class="w-100 bb niceSelect form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('lang.select_class')*" value="">@lang('lang.select_class') *</option>
                                        @foreach($classes as $class)
                                           <option value="{{$class->id}}"  {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ @$errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>


                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <select class="w-100 bb niceSelect form-control{{ @$errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                        <option data-display="@lang('lang.select_section') *" value="">@lang('lang.select_section') *</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ @$errors->first('section') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-lg-4 mt-30-md" id="select_subject_div">
                                    <select class="w-100 bb niceSelect form-control{{ @$errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_subject" name="subject">
                                        <option data-display="@lang('lang.select_subjects') *" value="">@lang('lang.select_subjects') *</option>
                                    </select>
                                    @if ($errors->has('subject'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ @$errors->first('subject') }}</strong>
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
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.assign') @lang('lang.optional') @lang('lang.subject') - ({{ @$subject_info->subject_name }})  </h3>
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                     
                </div>
            </div>
            <style>
            .all_check{
                background: linear-gradient(90deg, #7c32ff 0%, #c738d8 51%, #7c32ff 100%);
                color: #ffffff;
                background-size: 200% auto;
            }
            </style>
            <div class="row"> 
                <div class="col-lg-12">
                    <div class="white-box"> 
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'assign-optional-subject-store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'formid']) }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="assign-subject" id="assign-subject">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                        <input type="hidden" name="class_id" id="class_id" value="{{@$class_id}}">
                                        <input type="hidden" name="section_id" id="class_id" value="{{@$section_id}}">
                                        <input type="hidden" name="subject_id" id="" value="{{@$subject_id}}">
                                        <input type="hidden" name="update" value="1"> 
                                        <div class="table-responsive">

                                        
                                        <table id="" class="display school-table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><span class="all_check btn btn-sm fix-gr-bg" id="all_check" tyle="width: 143.715px; height: 143.715px; top: -48.611px; left: -26.5173px;" > Select All </span> </th> 
                                                    <th class="nowrap p-2" >@lang('lang.admission_no').</th>
                                                    <th class="nowrap p-2">@lang('lang.name')</th>
                                                    <th class="nowrap p-2">@lang('lang.optional') @lang('lang.subject')</th>   
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php $count=1; @endphp
                                                @foreach($students as $student)
                                                @php 
                                                    $subjects =  DB::table('sm_optional_subject_assigns')
                                                    ->leftjoin('sm_subjects', 'sm_subjects.id', 'sm_optional_subject_assigns.subject_id')->select('sm_optional_subject_assigns.*', 'sm_subjects.subject_name')
                                                    ->where('sm_optional_subject_assigns.student_id', '=', @$student->id)
                                                    ->first();
                                                @endphp 
                                                <tr>
                                                    <td>  
                                                        <div class="col-lg-12"> 
                                                            <div class="input-effect">
                                                                <input type="checkbox" id="optional_subject_{{@$count}}" class="common-checkbox optional_subject fix-gr-bg small" name="student_id[]" {{ (@$subjects->subject_name == @$subject_info->subject_name? 'checked': '' ) }} value="{{@$student->id}}">
                                                                <label for="optional_subject_{{@$count}}">{{@$count++}}</label>
                                                            </div> 
                                                        </div> 
                                                    </td> 
                                                    <td>{{@$student->admission_no}}</td>
                                                    <td class="nowrap">{{@$student->full_name}}</td> 
                                                    <td>
                                                        <span class="" style="    border-bottom: 2px dashed #ddd !important;">{{@$subjects->subject_name}}</span>
                                                    </td>   
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table> 
                                        </div>
                                    </div>
                                </div>
                                @if(userPermission(251))
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-save pr-2"></span>
                                        @lang('lang.assign')  @lang('lang.subject')
                                    </button>
                                </div>
                                @endif
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<script language="JavaScript">
function checkAll() {
    console.log("clicked");
        
        $('input:checkbox').prop('checked', this.checked);
} 


</script>
 

@endsection
