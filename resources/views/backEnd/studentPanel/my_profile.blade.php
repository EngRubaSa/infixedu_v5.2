@extends('backEnd.master')
@section('mainContent')

<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3 mb-30">
                <!-- Start Student Meta Information -->
                <div class="main-title">
                    <h3 class="mb-20">@lang('lang.student_profile') </h3>
                </div>
                <div class="student-meta-box">
                    <div class="student-meta-top"></div>
                    <img class="student-meta-img img-100" src="{{file_exists(@$student_detail->student_photo) ? asset($student_detail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" alt="">
                    <div class="white-box radius-t-y-0">
                        <div class="single-meta mt-10">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.student_name')
                                </div>
                                <div class="value">
                                    {{@$student_detail->first_name.' '.@$student_detail->last_name}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.admission_number')
                                </div>
                                <div class="value">
                                    {{@$student_detail->admission_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.roll_number')
                                </div>
                                <div class="value">
                                     {{@$student_detail->roll_no}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    Class
                                </div>
                                <div class="value">
                                   {{@$student_detail->className != ""? @$student_detail->className->class_name:''}} ({{@$student_detail->session_id != ""? @$academic_year->year:''}})
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.section')
                                </div>
                                <div class="value">
                                    {{@$student_detail->section != ""? @$student_detail->section->section_name:""}}
                                </div>
                            </div>
                        </div>
                        <div class="single-meta">
                            <div class="d-flex justify-content-between">
                                <div class="name">
                                    @lang('lang.gender') 
                                </div>
                                <div class="value">
                                    {{@$student_detail->gender!= ""? @$student_detail->gender->base_setup_name:""}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Student Meta Information -->

                <!-- Start Siblings Meta Information -->
                <div class="main-title mt-40">
                    <h3 class="mb-20">@lang('lang.sibling_snformation') </h3>
                </div>
                @foreach($siblings as $sibling)
                    @if(@$sibling->id != @$student_detail->id)
                    <div class="student-meta-box mb-20">
                        <div class="student-meta-top siblings-meta-top"></div>
                        <img class="student-meta-img img-100" src="{{asset(@$sibling->student_photo)}}" alt="">
                        <div class="white-box radius-t-y-0">
                            <div class="single-meta mt-10">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.sibling_name')
                                    </div>
                                    <div class="value">
                                        {{@$sibling->full_name}}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.admission') @lang('lang.number') 
                                    </div>
                                    <div class="value">
                                        {{@$sibling->admission_no}}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.roll') @lang('lang.number') 
                                    </div>
                                    <div class="value">
                                        {{@$sibling->roll_no}}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.class')
                                    </div>
                                    <div class="value">
                                       {{@$sibling->className !=""?@$sibling->className->class_name:""}}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.section') 
                                    </div>
                                    <div class="value">
                                        {{@$sibling->section !=""?@$sibling->section->section_name:""}}
                                    </div>
                                </div>
                            </div>
                            <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.gender') 
                                    </div>
                                    <div class="value">
                                        {{@$sibling->gender !=""?@$sibling->gender->base_setup_name:""}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
                <!-- End Siblings Meta Information -->
            </div>

            <!-- Start Student Details -->
            <div class="col-lg-9">
                <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
                    @if(userPermission(12))
                        <li class="nav-item">
                            <a class="nav-link @if (Session::get('studentDocuments') != 'active' && Session::get('studentTimeline') != 'active') active @endif" href="#studentProfile" role="tab" data-toggle="tab"> @lang('lang.profile') </a>
                        </li>
                    @endif
                    @if(userPermission(13))
                        <li class="nav-item">
                            <a class="nav-link" href="#studentFees" role="tab" data-toggle="tab">@lang('lang.fees')</a>
                        </li>
                    @endif
                    @if(userPermission(14))
                        <li class="nav-item">
                            <a class="nav-link" href="#studentExam" role="tab" data-toggle="tab">@lang('lang.exam')</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="#studentOnlineExam" role="tab" data-toggle="tab">@lang('lang.online') @lang('lang.exam')</a>
                    </li>
                    @if(userPermission(15))
                        <li class="nav-item">
                            <a class="nav-link {{Session::get('studentDocuments') == 'active'? 'active':''}}" href="#studentDocuments" role="tab" data-toggle="tab">@lang('lang.documents')</a>
                        </li>
                    @endif
                    @if(userPermission(19))
                        <li class="nav-item">
                            <a class="nav-link {{Session::get('studentTimeline') == 'active'? 'active':''}} " href="#studentTimeline" role="tab" data-toggle="tab">@lang('lang.timeline')</a>
                        </li>
                    @endif
                    <li class="nav-item edit-button">
                	    <a href="{{route('update-my-profile',$student_detail->id)}}" class="primary-btn small fix-gr-bg pull-right">@lang('lang.edit')</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Start Profile Tab -->
                    <div role="tabpanel" class="tab-pane fade @if (Session::get('studentDocuments') != 'active' && Session::get('studentTimeline') != 'active') show active @endif" id="studentProfile">
                        <div class="white-box">
                            <h4 class="stu-sub-head">@lang('lang.personal') @lang('lang.info')</h4>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.admission')  @lang('lang.date')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">                                                                                
                                        {{@$student_detail->admission_date != ""? dateConvert(@$student_detail->admission_date):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.date_of_birth')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">                                                                                        
                                            {{@$student_detail->date_of_birth != ""? dateConvert(@$student_detail->date_of_birth):''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.type')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->category != ""? @$student_detail->category->catgeory_name:""}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.religion') 
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->religion != ""? @$student_detail->religion->base_setup_name:""}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.phone')  @lang('lang.number') 
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->mobile}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                              @lang('lang.email') @lang('lang.address') 
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->email}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.present')  @lang('lang.address')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                           {{@$student_detail->current_address}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-6">
                                        <div class="">
                                            @lang('lang.permanent') @lang('lang.address')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-7">
                                        <div class="">
                                            {{@$student_detail->permanent_address}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Start Parent Part -->
                            <h4 class="stu-sub-head mt-40">@lang('lang.parent') / @lang('lang.guardian') @lang('lang.details')</h4>
                            <div class="d-flex">
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100" src="{{@$student_detail->parents != ""? asset(@$student_detail->parents->fathers_photo):""}}" alt="">
                                </div>
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.father_name')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""? @$student_detail->parents->fathers_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.occupation')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""? @$student_detail->parents->fathers_occupation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.phone') @lang('lang.number')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""? @$student_detail->parents->fathers_mobile:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100" src="{{@$student_detail->parents != ""? asset(@$student_detail->parents->mothers_photo):""}}" alt="">
                                </div>
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.mother_name')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""? @$student_detail->parents->mothers_name:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                     @lang('lang.occupation')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->mothers_occupation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                     @lang('lang.phone') @lang('lang.number')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->mothers_mobile:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="mr-20 mt-20">
                                    <img class="student-meta-img img-100" src="{{@$student_detail->parents != ""?asset(@$student_detail->parents->guardians_photo):""}}" alt="">
                                </div>
                                <div class="w-100">
                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.guardian_name')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->guardians_mobile:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.email') @lang('lang.address')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->guardians_email:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.phone')  @lang('lang.number')
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->guardians_phone:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.relation_with_guardian') 
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->guardians_relation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.occupation') 
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->guardians_occupation:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-info">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="">
                                                    @lang('lang.guardian_address') 
                                                </div>
                                            </div>

                                            <div class="col-lg-8 col-md-7">
                                                <div class="">
                                                    {{@$student_detail->parents != ""?@$student_detail->parents->guardians_address:""}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Parent Part -->

                            <!-- Start Transport Part -->
                            <h4 class="stu-sub-head mt-40">@lang('lang.transport_and_dormitory_details')</h4>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.route')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{@$student_detail->route != ""? @$student_detail->route->title: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.vehicle_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{@$student_detail->vehicle != ""? @$student_detail->vehicle->vehicle_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.driver_name')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{@$student_detail->vehicle != ""? @$driver->full_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.driver_phone_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{@$student_detail->vehicle != ""? @$driver->mobile: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.dormitory_name')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{@$student_detail->dormitory != ""? @$student_detail->dormitory->dormitory_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Transport Part -->

                            <!-- Start Other Information Part -->
                            <h4 class="stu-sub-head mt-40">@lang('lang.information') @lang('lang.other')</h4>
                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.blood_group')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                           {{@$student_detail->bloodGroup != ""? @$student_detail->bloodGroup->base_setup_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.height')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->height)? @$student_detail->height: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.Weight')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->weight)? @$student_detail->weight: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.previous_school_details')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->previous_school_details)? @$student_detail->previous_school_details: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.national_identification_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->national_id_no)? @$student_detail->national_id_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.local_identification_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->local_id_no)? @$student_detail->local_id_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.bank_account_number')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bank_account_no)? @$student_detail->bank_account_no: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.bank_name')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->bank_name)? @$student_detail->bank_name: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-info">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                        <div class="">
                                            @lang('lang.IFSC_Code')
                                        </div>
                                    </div>

                                    <div class="col-lg-7 col-md-6">
                                        <div class="">
                                            {{isset($student_detail->ifsc_code)? @$student_detail->ifsc_code: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Other Information Part -->
                        </div>
                    </div>
                    <!-- End Profile Tab -->

                    <!-- Start Fees Tab -->
                    <div role="tabpanel" class="tab-pane fade" id="studentFees">
                        <div class="table-responsive">
                            <table  class="display school-table school-table-style table_not_fixed" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th> @lang('lang.fees_group')</th>
                                    <th>@lang('lang.fees_code')</th>
                                    <th>@lang('lang.due_date')</th>
                                    <th>@lang('lang.status')</th>
                                    <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('lang.payment_id')</th>
                                    <th>@lang('lang.mode')</th>
                                    <th>@lang('lang.date')</th>
                                    <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('lang.fine')({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('lang.paid') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('lang.balance')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    @$grand_total = 0;
                                    @$total_fine = 0;
                                    @$total_discount = 0;
                                    @$total_paid = 0;
                                    @$total_grand_paid = 0;
                                    @$total_balance = 0;
                                @endphp
                                @foreach($fees_assigneds as $fees_assigned)
                                    @php
                                         @$grand_total += @$fees_assigned->feesGroupMaster->amount;
                                       
                                        
                                    @endphp

                                    @php
                                        @$discount_amount = $fees_assigned->applied_discount;
                                        @$total_discount += @$discount_amount;
                                        @$student_id = @$fees_assigned->student_id;
                                    @endphp
                                    @php
                                        @$paid = App\SmFeesAssign::discountSum(@$fees_assigned->student_id, @$fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                                        @$total_grand_paid += @$paid;
                                    @endphp
                                    @php
                                        @$fine = App\SmFeesAssign::discountSum(@$fees_assigned->student_id, @$fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                                        @$total_fine += @$fine;
                                    @endphp
                                        
                                    @php
                                        @$total_paid = @$discount_amount + @$paid;
                                    @endphp
                                <tr>
                                    <td>{{@$fees_assigned->feesGroupMaster->feesGroups !=""?@$fees_assigned->feesGroupMaster->feesGroups->name:""}}</td>
                                    <td>{{@$fees_assigned->feesGroupMaster->feesTypes!=""?@$fees_assigned->feesGroupMaster->feesTypes->name:""}}</td>
                                    <td>
                                        @if(!empty(@$fees_assigned->feesGroupMaster))                                                                            
                                        {{@$fees_assigned->feesGroupMaster->date != ""? dateConvert(@$fees_assigned->feesGroupMaster->date):''}}
                                        @endif
                                    </td>
                                    @php
                                     $total_payable_amount=$fees_assigned->fees_amount;
                                        $rest_amount = $fees_assigned->feesGroupMaster->amount - $total_paid;
                                        $total_balance +=  $total_payable_amount;
                                        $balance_amount=number_format($rest_amount+$fine, 2, '.', '');
                                @endphp
                                <td>
                                    
                                    @if($balance_amount ==0)
                                        <button class="primary-btn small bg-success text-white border-0">@lang('lang.paid')</button>
                                    @elseif($paid != 0)
                                        <button class="primary-btn small bg-warning text-white border-0">@lang('lang.partial')</button>
                                    @elseif($paid == 0)
                                        <button class="primary-btn small bg-danger text-white border-0">@lang('lang.unpaid')</button>
                                    @endif
                                    
                                </td>
                                    <td>
                                        @php
                                        echo number_format($fees_assigned->feesGroupMaster->amount+$fine, 2, '.', '');
                                     @endphp
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> {{@$discount_amount}} </td>
                                    <td>{{@$fine}}</td>
                                    <td>{{@$paid}}</td>
                                    <td>
                                        @php 

                                            echo @$total_payable_amount;
                                        @endphp
                                    </td>
                                </tr>
                                    @php 
                                        @$payments = App\SmFeesAssign::feesPayment(@$fees_assigned->feesGroupMaster->feesTypes->id, @$fees_assigned->student_id);
                                        $i = 0;
                                    @endphp

                                    @foreach($payments as $payment)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}"></td>
                                        <td>
                                            @php
                                                @$created_by = App\User::find(@$payment->created_by);
                                            @endphp
                                            @if(@$created_by != "")
                                            <a href="#" data-toggle="tooltip" data-placement="right" title="{{'Collected By: '.@$created_by->full_name}}">{{@$payment->fees_type_id.'/'.@$payment->id}}</a></td>
                                            @endif
                                        <td>
                                            {{$payment->payment_mode}}
                                        </td>
                                        <td>                                                                                
                                        {{@$payment->payment_date != ""? dateConvert(@$payment->payment_date):''}}
                                        </td>
                                        <td>{{@$payment->discount_amount}}</td>
                                        <td>{{@$payment->fine}}</td>
                                        <td>{{@$payment->amount}}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                @endforeach
                                
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>@lang('lang.grand_total') ({{@generalSetting()->currency_symbol}})</th>
                                    <th></th>
                                    <th>{{@$grand_total+$total_fine}}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>{{@$total_discount}}</th>
                                    <th>{{@$total_fine}}</th>
                                    <th>{{@$total_grand_paid}}</th>
                                    <th>{{@$total_balance}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        </div>
                    </div>
                    <!-- End Profile Tab -->
                    
                    <!-- Start Exam Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="studentExam">
                           
                            @php
                            $exam_count= count($exam_terms); 
                            @endphp
                            @if($exam_count<1)
                            <div class="white-box no-search no-paginate no-table-info mb-2">
                               <table class="display school-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('lang.subject')</th>
                                            <th>@lang('lang.full_marks')</th>
                                            <th>@lang('lang.passing_marks')</th>
                                            <th>@lang('lang.obtained_marks')</th>
                                            <th>@lang('lang.results')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                               </table>
                            </div>

                            @endif

                       
                                <div class="white-box no-search no-paginate no-table-info mb-2">
                                    @if ($exam_count<1)
                                        <h4 class="text-center">@lang('lang.result_not_publish_yet')</h4>
                                    @endif
                                    @foreach($exam_terms as $exam)

                                    @php

                                        $get_results = App\SmStudent::getExamResult(@$exam->id, @$student_detail);


                                    @endphp


                                    @if($get_results)

                                    <div class="main-title">
                                        <h3 class="mb-0">{{@$exam->title}}</h3>
                                    </div>
                                    <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>@lang('lang.date')</th>
                                            <th>@lang('lang.subject')</th>
                                            <th>@lang('lang.full_marks')</th>
                                            <th>@lang('lang.obtained_marks')</th>
                                            <th>@lang('lang.grade')</th>
                                            <!-- <th>@lang('lang.results')</th> -->
                                        </tr>
                                        </thead>

                                        <tbody>
                                            
                                        @php
                                            $grand_total = 0;
                                            $grand_total_marks = 0;
                                            $result = 0;
                                        @endphp

                                        @foreach($get_results as $mark)
                                            @php
                                                $subject_marks = App\SmStudent::fullMarksBySubject($exam->id, $mark->subject_id);

                                                $schedule_by_subject = App\SmStudent::scheduleBySubject($exam->id, $mark->subject_id, @$student_detail);

                                                $result_subject = 0;

                                                $grand_total_marks += @$subject_marks->exam_mark;

                                                if(@$mark->is_absent == 0){
                                                    $grand_total += @$mark->total_marks;
                                                    if($mark->marks < $subject_marks->pass_mark){
                                                       $result_subject++;
                                                       $result++;
                                                    }
                                                }else{
                                                    $result_subject++;
                                                    $result++;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ !empty($schedule_by_subject->date)? dateConvert($schedule_by_subject->date):''}}</td>
                                                <td>{{@$mark->subject->subject_name}}</td>
                                                <td>{{@$subject_marks->exam_mark}}</td>
                                                <td>{{@$mark->total_marks}}</td>
                                                <td>{{@$mark->total_gpa_grade}}</td>
                                                <!-- <td>
                                                    @if($result_subject == 0)
                                                        <button
                                                            class="primary-btn small bg-success text-white border-0">
                                                            @lang('lang.pass')
                                                        </button>
                                                    @else
                                                        <button class="primary-btn small bg-danger text-white border-0">
                                                            @lang('lang.fail')
                                                        </button>
                                                    @endif
                                                </td> -->
                                            </tr>
                                        @endforeach
                                        </tbody>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>@lang('lang.grand_total'): {{$grand_total}}/{{$grand_total_marks}}</th>
                                                <th></th>
                                                <th>@lang('lang.grade'):
                                                    @php
                                                        if($result == 0 && $grand_total_marks != 0){
                                                            $percent = $grand_total/$grand_total_marks*100;


                                                            foreach($grades as $grade){
                                                               if(floor($percent) >= $grade->percent_from && floor($percent) <= $grade->percent_upto){
                                                                   echo $grade->grade_name;
                                                               }
                                                           }

                                                        }else{
                                                            echo "F";
                                                        }
                                                    @endphp
                                                </th>
                                            </tr>
                                            </tfoot>
                                    </table>

                                    @endif

                                    @endforeach
                                </div>
                            
                        </div>
                        <!-- End Exam Tab -->
                    <!-- Start Online Exam Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="studentOnlineExam">
                           
                            @php
                            $exam_count= count($exam_terms); 
                            @endphp
                            @if($result_views->count()<1)
                            <div class="white-box no-search no-paginate no-table-info mb-2">
                               <table class="display school-table" cellspacing="0" width="100%">
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
                                            
                                        </tbody>
                               </table>
                            </div>

                            @endif

                       
                                <div class="white-box no-search no-paginate no-table-info mb-2">
                                    @if ($result_views->count()<1)
                                        <h4 class="text-center">@lang('lang.result_not_publish_yet')</h4>
                                    @endif
                                    @foreach($result_views as $exam)
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
                                    @endforeach
                                </div>
                            
                        </div>
                        <!-- End Online Exam Tab -->
                  
                    <!-- Start Documents Tab -->
                    <div role="tabpanel" class="tab-pane fade {{Session::get('studentDocuments') == 'active'? 'show active':''}}" id="studentDocuments">
                        <div class="white-box">
                            <div class="text-right mb-20">
                                @if(userPermission(16))
                                    <button type="button" data-toggle="modal" data-target="#add_document_madal" class="primary-btn tr-bg text-uppercase bord-rad">
                                        @lang('lang.upload_document')
                                        <span class="pl ti-upload"></span>
                                    </button>
                                @endif
                            </div>
                            <table id="" class="table simple-table table-responsive school-table"
                                cellspacing="0">
                                <thead class="d-block">
                                    <tr class="d-flex">
                                        <th class="col-3">@lang('lang.document_title')</th>
                                        <th class="col-6">@lang('lang.name')</th>
                                        <th class="col-3">@lang('lang.action')</th>
                                    </tr>
                                </thead>

                                <tbody class="d-block">
                                    @if($student_detail->document_file_1 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_1}} </td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_1)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            @if(userPermission(17))
                                                <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                    <a href="{{asset($student_detail->document_file_1)}}" download>@lang('lang.download')</a>
                                                    <span class="pl ti-download"></span>
                                                </button>
                                            @endif
                                            @if(userPermission(18))
                                            <a class="primary-btn icon-only bg-danger text-light delete-doc" onclick="deleteDoc({{$student_detail->id}},1)" data-id="1"  href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @if($student_detail->document_file_2 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_2}}</td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_2)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            @if(userPermission(17))
                                                <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                    <a href="{{asset($student_detail->document_file_2)}}" download>@lang('lang.download')</a>
                                                    <span class="pl ti-download"></span>
                                                </button>
                                            @endif
                                            @if(userPermission(18))
                                                <a class="primary-btn icon-only bg-danger text-light delete-doc" onclick="deleteDoc({{$student_detail->id}},2)" data-id="2"  href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @if($student_detail->document_file_3 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_3}}</td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_3)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            @if(userPermission(17))
                                                <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                    <a href="{{asset($student_detail->document_file_3)}}" download>@lang('lang.download')</a>
                                                    <span class="pl ti-download"></span>
                                                </button>
                                            @endif
                                            @if(userPermission(18))
                                                <a class="primary-btn icon-only bg-danger text-light delete-doc" onclick="deleteDoc({{$student_detail->id}},3)" data-id="3"  href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @if($student_detail->document_file_4 != "")
                                    <tr class="d-flex">
                                        <td class="col-3">{{$student_detail->document_title_4}}</td>
                                        <td class="col-6">{{showDocument(@$student_detail->document_file_4)}}</td>
                                        <td class="col-3 d-flex align-items-center">
                                            @if(userPermission(17))
                                                <button class="primary-btn tr-bg text-uppercase bord-rad mr-1">
                                                    <a href="{{asset($student_detail->document_file_4)}}" download>@lang('lang.download')</a>
                                                    <span class="pl ti-download"></span>
                                                </button>
                                            @endif
                                            @if(userPermission(18))
                                                <a class="primary-btn icon-only bg-danger text-light delete-doc" onclick="deleteDoc({{$student_detail->id}},4)"  data-id="4"  href="#">
                                                    <span class="ti-trash"></span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    {{-- fgfdg --}}

                                    <div class="modal fade admin-query" id="delete-doc" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('lang.delete')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                    </div>

                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <form action="{{route('student_document_delete')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="student_id" >
                                                            <input type="hidden" name="doc_id">
                                                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                                                            <button type="submit" class="primary-btn fix-gr-bg">@lang('lang.delete')</button>
                                                            
                                                        </form>
                                                        
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    {{-- fgfdg --}}
                                    @foreach($documents as $document)
                                    <tr class="d-flex">
                                        <td class="col-3">{{@$document->title}}</td>
                                        <td class="col-6">{{showDocument(@$document->file)}}</td>
                                        <td class="col-3">
                                            @if(userPermission(17))
                                                <a class="primary-btn tr-bg text-uppercase bord-rad" href="{{route('student-download-document',showDocument(@$document->file))}}">
                                                    Download<span class="pl ti-download"></span>
                                                </a>
                                            @endif
                                            @if(@$document->type=='stu')
                                                @if(userPermission(18))
                                                    <a class="primary-btn icon-only bg-danger text-light" data-toggle="modal" data-target="#deleteDocumentModal{{@$document->id}}"  href="#">
                                                        <span class="ti-trash"></span>
                                                    </a>
                                                @endif
                                            @else
                                                <a></a>
                                            @endif
                                            
                                           
                                        </td>
                                    </tr>
                                    <div class="modal fade admin-query" id="deleteDocumentModal{{@$document->id}}" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('lang.delete')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="text-center">
                                                       <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                    </div>

                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                                                        <a class="primary-btn fix-gr-bg" href="{{route('delete_document', [@$document->id])}}">
                                                        @lang('lang.delete')</a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Documents Tab -->
                    <!-- Add Document modal form start-->
                    <div class="modal fade admin-query" id="add_document_madal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Upload Document</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                   <div class="container-fluid">
                                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_upload_document',
                                                            'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" name="student_id" value="{{@$student_detail->id}}">
                                                    <div class="row mt-25">
                                                        <div class="col-lg-12">
                                                            <div class="input-effect">
                                                                <input class="primary-input form-control{" type="text" name="title" value="" id="title">
                                                                <label>Title <span>*</span> </label>
                                                                <span class="focus-border"></span>
                                                                
                                                                <span class=" text-danger" role="alert" id="amount_error">
                                                                    
                                                                </span>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 mt-30">
                                                    <div class="row no-gutters input-right-icon">
                                                        <div class="col">
                                                            <div class="input-effect">
                                                                <input class="primary-input" type="text" id="placeholderPhoto" placeholder="Document"
                                                                    disabled>
                                                                <span class="focus-border"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button class="primary-btn-small-input" type="button">
                                                                <label class="primary-btn small fix-gr-bg" for="photo">browse</label>
                                                                <input type="file" class="d-none" name="photo" id="photo">
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- <div class="col-lg-12 text-center mt-40">
                                                    <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                                        <span class="ti-check"></span>
                                                        save information
                                                    </button>
                                                </div> -->
                                                <div class="col-lg-12 text-center mt-40">
                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Cancel</button>

                                                        <button class="primary-btn fix-gr-bg" type="submit">save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Add Document modal form end-->
                    <!-- delete document modal -->

                    <!-- delete document modal -->
                    <!-- Start Timeline Tab -->
                    <div role="tabpanel" class="tab-pane fade {{Session::get('studentTimeline') == 'active'? 'show active':''}}" id="studentTimeline">
                        <div class="white-box">
                            @foreach($timelines as $timeline)
                            <div class="student-activities">
                                <div class="single-activity">
                                    <h4 class="title text-uppercase">                                                                            
                                    {{@$timeline->date != ""? dateConvert(@$timeline->date):''}}
                                    </h4>
                                    <div class="sub-activity-box d-flex">
                                        <h6 class="time text-uppercase">{{date('h:i A', strtotime(@$timeline->date))}}</h6>
                                        <div class="sub-activity">
                                            <h5 class="subtitle text-uppercase"> {{@$timeline->title}}</h5>
                                            <p>
                                                {{@$timeline->description}}
                                            </p>
                                        </div>

                                        <div class="close-activity">
                                            @if(@$timeline->file != "")
                                            <a href="{{route('download-timeline-doc',showDocument(@$timeline->file))}}" class="primary-btn tr-bg text-uppercase bord-rad">
                                                Download<span class="pl ti-download"></span>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- End Timeline Tab -->
                </div>
            </div>
            <!-- End Student Details -->
        </div>

            
    </div>
</section>

<!-- timeline form modal start-->
<div class="modal fade admin-query" id="add_timeline_madal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Timeline</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
               <div class="container-fluid">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_timeline_store',
                                        'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="student_id" value="{{@$student_detail->id}}">
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="input-effect">
                                            <input class="primary-input form-control{" type="text" name="title" value="" id="title">
                                            <label>Title <span>*</span> </label>
                                            <span class="focus-border"></span>
                                            
                                            <span class=" text-danger" role="alert" id="amount_error">
                                                
                                            </span>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="input-effect">
                                            <input class="primary-input date form-control" id="startDate" type="text"
                                                 name="date">
                                                <label>Date</label>
                                                <span class="focus-border"></span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                <div class="input-effect">
                                    <textarea class="primary-input form-control" cols="0" rows="3" name="description" id="Description"></textarea>
                                    <label>Description<span></span> </label>
                                    <span class="focus-border textarea"></span>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-30">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="input-effect">
                                            <input class="primary-input" type="text" id="placeholderFileFourName" placeholder="Document"
                                                disabled>
                                            <span class="focus-border"></span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="document_file_4">browse</label>
                                            <input type="file" class="d-none" name="document_file_4" id="document_file_4">
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-30">
                                
                                <input type="checkbox" id="currentAddressCheck" class="common-checkbox" name="visible_to_student" value="1">
                                <label for="currentAddressCheck">Visible to this person</label>
                            </div>


                            <!-- <div class="col-lg-12 text-center mt-40">
                                <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                    <span class="ti-check"></span>
                                    save information
                                </button>
                            </div> -->
                            <div class="col-lg-12 text-center mt-40">
                                <div class="mt-40 d-flex justify-content-between">
                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">Cancel</button>

                                    <button class="primary-btn fix-gr-bg" type="submit">save</button>
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>
<!-- timeline form modal end-->


<script>
    // data table responsive problem tab
    $(document).ready(function () {
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $($.fn.dataTable.tables(true)).DataTable()
    .columns.adjust()
    .responsive.recalc();
    });
    });

    function deleteDoc(id,doc){
        // alert(doc);
        var modal = $('#delete-doc');
         modal.find('input[name=student_id]').val(id)
         modal.find('input[name=doc_id]').val(doc)
         modal.modal('show');
    }
</script>

@endsection
