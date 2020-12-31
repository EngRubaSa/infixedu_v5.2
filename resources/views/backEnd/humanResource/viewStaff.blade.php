@extends('backEnd.master')
@section('mainContent') 
@php
        function showTimelineDocName($data){
            $name = explode('/', $data);
            $number = count($name);
            return $name[$number-1];
        }
        function showDocumentName($data){
            $name = explode('/', $data);
            $number = count($name);
            return $name[$number-1];
        }
    @endphp 
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.human_resource')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="">@lang('lang.human_resource')</a>
                <a href="{{route('staff_directory')}}">@lang('lang.staff') @lang('lang.details')</a>
            </div>
        </div>
    </div>
</section>
<section class="mb-40 student-details">
    @if(session()->has('message-success'))
    <div class="alert alert-success">
        {{ session()->get('message-success') }}
    </div>
    @elseif(session()->has('message-danger'))
    <div class="alert alert-danger">
        {{ session()->get('message-danger') }}
    </div>
    @endif
    <div class="container-fluid p-0">
        <div class="row">
         <div class="col-lg-3 mb-30">
            <!-- Start Student Meta Information -->
            <div class="main-title">
                <h3 class="mb-20">@lang('lang.staff') @lang('lang.details')</h3>
            </div>
            <div class="student-meta-box">
                <div class="student-meta-top"></div>

                {{-- @if(!empty($staffDetails->staff_photo))
                    <img class="student-meta-img img-100" src="{{asset($staffDetails->staff_photo)}}"  alt="">
                @else
                    <img class="student-meta-img img-100" src="{{ file_exists(@$staffDetails->staff_photo) ? asset($staffDetails->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"  alt="">
                @endif --}}
                <img class="student-meta-img img-100" src="{{ file_exists(@$staffDetails->staff_photo) ? asset($staffDetails->staff_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"  alt="">
                <div class="white-box">
                    <div class="single-meta mt-10">
                        <div class="d-flex justify-content-between">
                            <div class="name">
                                @lang('lang.staff_name')
                            </div>
                            <div class="value">

                                @if(isset($staffDetails)){{$staffDetails->full_name}}@endif

                            </div>
                        </div>
                    </div>
                    <div class="single-meta">
                        <div class="d-flex justify-content-between">
                            <div class="name">
                                @lang('lang.role') 
                            </div>
                            <div class="value">
                               @if(isset($staffDetails)){{$staffDetails->roles->name}}@endif
                           </div>
                       </div>
                   </div>
                   <div class="single-meta">
                    <div class="d-flex justify-content-between">
                        <div class="name">
                            @lang('lang.designation')
                        </div>
                        <div class="value">
                           @if(isset($staffDetails)){{ !empty($staffDetails->designations)?$staffDetails->designations->title:''}}@endif
                            
                       </div>
                   </div>
               </div>
               <div class="single-meta">
                <div class="d-flex justify-content-between">
                    <div class="name">
                        @lang('lang.department')
                    </div>
                    <div class="value">
                        
                           @if(isset($staffDetails)){{ !empty($staffDetails->departments)?$staffDetails->departments->name:''}}@endif 

                    </div>
                </div>
            </div>
            <div class="single-meta">
                <div class="d-flex justify-content-between">
                    <div class="name">
                        @lang('lang.epf_no')
                    </div>
                    <div class="value">
                       @if(isset($staffDetails)){{$staffDetails->epf_no}}@endif
                   </div>
               </div>
           </div>
           <div class="single-meta">
            <div class="d-flex justify-content-between">
                <div class="name">
                    @lang('lang.basic_salary')
                </div>
                <div class="value">
                    ({{generalSetting()->currency_symbol}}) @if(isset($staffDetails)){{$staffDetails->basic_salary}}@endif
                </div>
            </div>
        </div>
        <div class="single-meta">
            <div class="d-flex justify-content-between">
                <div class="name">
                    @lang('lang.contract_type')
                </div>
                <div class="value">
                   @if(isset($staffDetails)){{$staffDetails->contract_type}}@endif
               </div>
           </div>
       </div>
       <div class="single-meta">
        <div class="d-flex justify-content-between">
            <div class="name">
                @lang('lang.date_of_joining')
            </div>
            <div class="value">
                @if(isset($staffDetails))
               
{{$staffDetails->date_of_joining != ""? dateConvert($staffDetails->date_of_joining):''}}


                @endif
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Student Meta Information -->

</div>

<!-- Start Student Details -->
<div class="col-lg-9 staff-details">
    
    <ul class="nav nav-tabs tabs_scroll_nav" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if (Session::get('staffDocuments') != 'active' && Session::get('staffTimeline') != 'active') active @endif" href="#studentProfile" role="tab" data-toggle="tab">@lang('lang.profile')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#payroll" role="tab" data-toggle="tab">@lang('lang.payroll')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#leaves" role="tab" data-toggle="tab">@lang('lang.leave')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Session::get('staffDocuments') == 'active'? 'active':''}}" href="#staffDocuments" role="tab" data-toggle="tab">@lang('lang.documents')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{Session::get('staffTimeline') == 'active'? 'active':''}}" href="#staffTimeline" role="tab" data-toggle="tab">@lang('lang.timeline')</a>
        </li>
        <li class="nav-item edit-button d-flex align-items-center justify-content-end">
            <a href="{{route('editStaff',$staffDetails->id)}}" class="primary-btn small fix-gr-bg">@lang('lang.edit')
            </a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Start Profile Tab -->
        <div role="tabpanel" class="tab-pane fade @if (Session::get('staffDocuments') != 'active' && Session::get('staffTimeline') != 'active') show active @endif" id="studentProfile">
            <div class="white-box">
                <h4 class="stu-sub-head">@lang('lang.personal') @lang('lang.info')</h4>
                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-5 col-md-5">
                            <div class="">
                                @lang('lang.mobile') @lang('lang.no')
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-6">
                            <div class="">
                                @if(isset($staffDetails)){{$staffDetails->mobile}}@endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="single-info">
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <div class="">
                               @lang('lang.emergency_mobile')
                           </div>
                       </div>

                       <div class="col-lg-7 col-md-7">
                        <div class="">
                         @if(isset($staffDetails)){{$staffDetails->emergency_mobile}}@endif
                     </div>
                 </div>
             </div>
         </div>

         <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        @lang('lang.email')
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">
                        @if(isset($staffDetails)){{$staffDetails->email}}@endif
                    </div>
                </div>
            </div>
        </div>

        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                        @lang('lang.gender')
                    </div>
                </div>

                <div class="col-lg-7 col-md-7">
                    <div class="">

                        @if(isset($staffDetails)) {{$staffDetails->genders->base_setup_name}} @endif 
                        
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
                        @if(isset($staffDetails))
                       
{{$staffDetails->date_of_birth != ""? dateConvert($staffDetails->date_of_birth):''}}


                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="single-info">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="">
                       @lang('lang.marital_status')
                   </div>
               </div>

               <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->marital_status}}@endif
                </div>
            </div>
        </div>
    </div>

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                    @lang('lang.father_name')
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->fathers_name}}@endif
                </div>
            </div>
        </div>
    </div>

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                    @lang('lang.mother_name')
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->mothers_name}}@endif
                </div>
            </div>
        </div>
    </div>

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                    @lang('lang.qualifications')
                </div>
            </div>

            <div class="col-lg-7 col-md-7">
                <div class="">
                    @if(isset($staffDetails)){{$staffDetails->qualification}}@endif
                </div>
            </div>
        </div>
    </div>

    <div class="single-info">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="">
                   @lang('lang.work_experience')
               </div>
           </div>

           <div class="col-lg-7 col-md-7">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->experience}}@endif
            </div>
        </div>
    </div>
</div>

<!-- Start Parent Part -->
<h4 class="stu-sub-head mt-40">@lang('lang.address')</h4>
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                @lang('lang.current_address')
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->current_address}}@endif
            </div>
        </div>
    </div>
</div>

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
             @lang('lang.permanent_address')
         </div>
     </div>

     <div class="col-lg-7 col-md-6">
        <div class="">
            @if(isset($staffDetails)){{$staffDetails->permanent_address}}@endif
        </div>
    </div>
</div>
</div>
<!-- End Parent Part -->

<!-- Start Transport Part -->
<h4 class="stu-sub-head mt-40">@lang('lang.bank') @lang('lang.account') @lang('lang.details')</h4>
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                @lang('lang.account_name')
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->bank_account_name}}@endif
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
                @if(isset($staffDetails)){{$staffDetails->bank_account_no}}@endif
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
                @if(isset($staffDetails)){{$staffDetails->bank_name}}@endif
            </div>
        </div>
    </div>
</div>

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
               @lang('lang.branch_name')
           </div>
       </div>

       <div class="col-lg-7 col-md-6">
        <div class="">
            @if(isset($staffDetails)){{$staffDetails->bank_brach}}@endif
        </div>
    </div>
</div>
</div>


<!-- End Transport Part -->

<!-- Start Other Information Part -->
<h4 class="stu-sub-head mt-40">@lang('lang.social_links_details')</h4>
<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                @lang('lang.facebook_url')
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->facebook_url}}@endif
            </div>
        </div>
    </div>
</div>

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                @lang('lang.twitter_url')
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->twiteer_url}}@endif
            </div>
        </div>
    </div>
</div>

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                @lang('lang.linkedin_url')
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->linkedin_url}}@endif
            </div>
        </div>
    </div>
</div>

<div class="single-info">
    <div class="row">
        <div class="col-lg-5 col-md-5">
            <div class="">
                @lang('lang.instragram_url')
            </div>
        </div>

        <div class="col-lg-7 col-md-6">
            <div class="">
                @if(isset($staffDetails)){{$staffDetails->instragram_url}}@endif
            </div>
        </div>
    </div>
</div>

<!-- End Other Information Part -->
</div>
</div>
<!-- End Profile Tab -->

<!-- Start payroll Tab -->
<div role="tabpanel" class="tab-pane fade" id="payroll">
    <div class="white-box">
        <table id="" class="table simple-table table-responsive school-table"
        cellspacing="0">
        <thead>
            <tr>
                <th width="5%">@lang('lang.payslip') @lang('lang.id')</th>
                <th width="20%">@lang('lang.month')-@lang('lang.year')</th>
                <th width="15%">@lang('lang.date')</th>
                <th width="15%">@lang('lang.mode_of_payment')</th>
                <th width="15%">@lang('lang.net_salary')({{generalSetting()->currency_symbol}})</th>
                <th width="12%">@lang('lang.status')</th>
                <th width="20%">@lang('lang.action')</th>
            </tr>
        </thead>

        <tbody>
            @if(count($staffPayrollDetails)>0)
            @foreach($staffPayrollDetails as $value)
            <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->payroll_month}} - {{$value->payroll_year}}</td>
                <td>
                    
{{$value->created_at != ""? dateConvert($value->created_at):''}}

                </td>
                <td><?php $payment_mode = ''; 
                    if(!empty($value->payment_mode)){
                        $payment_mode = App\SmHrPayrollGenerate::getPaymentMode($value->payment_mode);
                    }else{
                        $payment_mode = '';
                    }
                    ?>
                    {{$payment_mode}}
                </td>
                <td>{{$value->net_salary}}</td>
                <td>
                    @if($value->payroll_status == 'G')
                    <button class="primary-btn small bg-warning text-white border-0"> @lang('lang.generated')</button>
                    @endif

                    @if($value->payroll_status == 'P')
                    <button class="primary-btn small bg-success text-white border-0"> @lang('lang.paid') </button>
                    @endif
                </td>
                <td>
                    @if($value->payroll_status == 'P')
                    <a class="modalLink" data-modal-size="modal-lg" title="@lang('lang.view_payslip') @lang('lang.details')" href="{{route('view-payslip', $value->id)}}"><button class="primary-btn small tr-bg"> @lang('lang.view') @lang('lang.payslip')</button></a>
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr class="justify-content-center">
                <td colspan="7" class="justify-content-center text-center">@lang('lang.no_payroll_data')</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
</div>
<!-- End payroll Tab -->

<!-- Start leave Tab -->
<div role="tabpanel" class="tab-pane fade" id="leaves">
    <div class="white-box">
        <div class="row mt-30">
            <div class="col-lg-12">
                <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>@lang('lang.leave_type')</th>
                            <th>@lang('lang.leave_from') </th>
                            <th>@lang('lang.leave_to')</th>
                            <th>@lang('lang.apply_date')</th>
                            <th>@lang('lang.status')</th>
                            <th>@lang('lang.action')</th>
                        </tr>
                    </thead>
                    {{-- {{ dd($staffLeaveDetails) }} --}}
                    <tbody>
                       @php $diff = ''; @endphp
                       @if(count($staffLeaveDetails)>0)
                       @foreach($staffLeaveDetails as $value)
                       <tr>
                        <td>{{@$value->leaveType->type}}</td>
                        <td>
                            
{{$value->leave_from != ""? dateConvert($value->leave_from):''}}


                        </td>
                        <td>
                           
{{$value->leave_to != ""? dateConvert($value->leave_to):''}}

                        </td>
                        <td>
                            
{{$value->apply_date != ""? dateConvert($value->apply_date):''}}

                        </td>
                        <td>

                            @if($value->approve_status == 'P')
                            <button class="primary-btn small bg-warning text-white border-0"> @lang('lang.pending')</button>
                            @endif

                            @if($value->approve_status == 'A')
                            <button class="primary-btn small bg-success text-white border-0"> @lang('lang.approved')</button>
                            @endif

                            @if($value->approve_status == 'C')
                            <button class="primary-btn small bg-danger text-white border-0"> @lang('lang.cancelled')</button>
                            @endif

                        </td>
                        <td>
                            <a class="modalLink" data-modal-size="modal-md" title="@lang('lang.view') @lang('lang.leave') @lang('lang.details')" href="{{url('view-leave-details', $value->id)}}"><button class="primary-btn small tr-bg"> @lang('lang.view') </button></a>
                        </td>
                    </tr>
                    @endforeach
                    @else 
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>@lang('lang.not_leaves_data')</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif 
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<!-- End leave Tab -->

<!-- Start Documents Tab -->
<div role="tabpanel" class="tab-pane fade {{Session::get('staffDocuments') == 'active'? 'show active':''}}" id="staffDocuments">
    <div class="white-box">
        <div class="text-right mb-20">
            <button type="button" data-toggle="modal" data-target="#add_document_madal" class="primary-btn tr-bg text-uppercase bord-rad">
                                    @lang('lang.upload') @lang('lang.document')
                                    <span class="pl ti-upload"></span>
                                </button>
        </div>
        <table id="" class="table simple-table table-responsive school-table"
        cellspacing="0">
            <thead class="d-block">
                <tr class="d-flex">
                    <th class="col-8">@lang('lang.document') @lang('lang.title')</th>
                    <th class="col-4">@lang('lang.action')</th>
                </tr>
            </thead>

            <tbody class="d-block">
                @if($staffDetails->joining_letter != '')
                <tr class="d-flex">
                    <td class="col-8">@lang('lang.joining_letter')</td>
                    <td class="col-4">
                        <a href="{{route('download-staff-joining-letter',showDocumentName($staffDetails->joining_letter))}}">
                            <button class="primary-btn tr-bg text-uppercase bord-rad">
                                @lang('lang.download')
                                <span class="pl ti-download"></span>
                            </button>
                        </a>
                    </td>
                </tr>
                @endif
                @if($staffDetails->resume != '')
                <tr class="d-flex">
                    <td class="col-8">@lang('lang.resume')</td>
                    <td class="col-4">
                        <a href="{{route('download-resume',showDocumentName($staffDetails->resume))}}">
                            <button class="primary-btn tr-bg text-uppercase bord-rad">
                                @lang('lang.download')
                                <span class="pl ti-download"></span>
                            </button>
                        </a>
                    </td>
                </tr>
                @endif
                @if($staffDetails->other_document != '')
                <tr class="d-flex">
                    <td class="col-8">@lang('lang.other_documents')</td>
                    <td class="col-4">
                        <a href="{{route('download-other-document',showDocumentName($staffDetails->other_document))}}">
                            <button class="primary-btn tr-bg text-uppercase bord-rad">
                                @lang('lang.download')
                                <span class="pl ti-download"></span>
                            </button>
                        </a>
                    </td>
                </tr>
                @endif
                @if(isset($staffDocumentsDetails))
                @foreach($staffDocumentsDetails as $key=>$value)
                <tr class="d-flex">
                    <td class="col-8">{{$value->title}}</td>
                    <td class="col-4">
                        <a class="primary-btn tr-bg text-uppercase bord-rad" href="{{route('download-staff-document',showDocumentName($value->file))}}">
                            @lang('lang.download')
                                <span class="pl ti-download"></span>
                        </a>
                        <a class="primary-btn icon-only fix-gr-bg modalLink" title="Delete Document" data-modal-size="modal-md"  href="{{route('delete-staff-document-view',$value->student_staff_id)}}">
                        <span class="ti-trash pt-30"></span>
                        </a>
                        
                    </td>
                </tr>
                @endforeach
                @endif
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
                    <h4 class="modal-title">@lang('lang.upload') @lang('lang.document')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                   <div class="container-fluid">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'save_upload_document',
                            'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                            <div class="row">
                                <div class="col-lg-12">
                                <input type="hidden" name="staff_id" value="{{$staffDetails->id}}">
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control" type="text" name="title" id="title" required>
                                                <label>@lang('lang.title') <span>*</span> </label>
                                                <span class="focus-border"></span>

                                                <span class=" text-danger" role="alert" id="amount_error">

                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-30">
                                    <div class="row no-gutters input-right-icon mt-35">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input" id="placeholderInput" type="text"
                                                       placeholder="@lang('lang.new_document')"
                                                       readonly>
                                                <span class="focus-border"></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                       for="browseFile">@lang('lang.browse')</label>
                                                <input type="file" class="d-none" id="browseFile" name="staff_upload_document" required>
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
                                            <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>

                                            <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.save')</button>
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

<!-- Start Timeline Tab -->

           

            <div role="tabpanel" class="tab-pane fade {{Session::get('staffTimeline') == 'active'? 'show active':''}}" id="staffTimeline">
                <div class="white-box">
                    <div class="text-right mb-20">
                    <button type="button" data-toggle="modal" data-target="#add_timeline_madal" class="primary-btn tr-bg text-uppercase bord-rad">
                                    @lang('lang.add')
                                    <span class="pl ti-plus"></span>
                                </button>
                    </div>
                    @if(isset($timelines))
                    @foreach($timelines as $timeline)
                    <div class="student-activities">
                        <div class="single-activity">
                            <h4 class="title text-uppercase">
                                
{{$timeline->date != ""? dateConvert($timeline->date):''}}


                            </h4>
                            <div class="sub-activity-box d-flex">
                                <h6 class="time text-uppercase">{{date('h:i a', strtotime($timeline->created_at))}}</h6>
                                <div class="sub-activity">
                                    <h5 class="subtitle text-uppercase"> {{$timeline->title}}</h5>
                                    <p>
                                        {{$timeline->description}}
                                    </p>
                                </div>

                                <div class="close-activity">
                                    
                                    <a class="primary-btn icon-only fix-gr-bg modalLink" title="Delete Timeline" data-modal-size="modal-md"  href="{{route('delete-staff-timeline-view',$timeline->id)}}">
                                        <span class="ti-trash"></span>
                                    </a>
                                    @if($timeline->file != "")
                                    <a href="{{route('download-staff-timeline-doc',showTimelineDocName($timeline->file))}}" class="primary-btn tr-bg text-uppercase bord-rad">
                                        @lang('lang.download')<span class="pl ti-download"></span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                          </div>
                       @endforeach
                       @endif
                      </div>
                    </div>
                </div>
            </div>
        </div>
         <!-- End Timeline Tab -->
         <!-- timeline form modal start-->
        <div class="modal fade admin-query" id="add_timeline_madal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('lang.add') @lang('lang.timeline')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                       <div class="container-fluid">
                                                
                             {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staff_timeline_store',
                             'method' => 'POST', 'enctype' => 'multipart/form-data', 'name' => 'document_upload']) }}
                             <div class="row">
                                <div class="col-lg-12">
                                    <input type="hidden" name="staff_student_id" value="{{$staffDetails->id}}">
                                    <div class="row mt-25">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input class="primary-input form-control{" type="text" name="title" value="" id="title" required>
                                                <span class="focus-border"></span>
                                                <label>@lang('lang.title') <span>*</span> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-30">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input date form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                name="date" autocomplete="off" value="{{date('m/d/Y')}}" required>
                                                <span class="focus-border"></span>
                                                <label>@lang('lang.date') <span>*</span> </label>
                                                @if ($errors->has('date_of_birth'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('date_of_birth') }}</strong>
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
                                <div class="col-lg-12 mt-30">
                                    <div class="input-effect">
                                        <textarea class="primary-input form-control" cols="0" rows="3" name="description" id="Description" required></textarea>
                                        <label>@lang('lang.description')<span></span> </label>
                                        <span class="focus-border textarea"></span>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-30">
                                    <div class="row no-gutters input-right-icon mt-35">
                                        <div class="col">
                                            <div class="input-effect">
                                                <input class="primary-input" id="placeholderFileFourName" type="text"
                                                       placeholder="@lang('lang.document')"
                                                       readonly>
                                                <span class="focus-border"></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="primary-btn-small-input" type="button">
                                                <label class="primary-btn small fix-gr-bg"
                                                       for="document_file_4">@lang('lang.browse')</label>
                                                <input type="file" class="d-none" id="document_file_4" name="document_file_4">
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12 mt-30">

                                    <input type="checkbox" id="currentAddressCheck" class="common-checkbox" name="visible_to_student" value="1">
                                    <label for="currentAddressCheck">@lang('lang.visible_to_this_person')</label>
                                </div>


                                <!-- <div class="col-lg-12 text-center mt-40">
                                    <button class="primary-btn fix-gr-bg" id="save_button_sibling" type="button">
                                        <span class="ti-check"></span>
                                        save information
                                    </button>
                                </div> -->
                                <div class="col-lg-12 text-center mt-40">
                                    <div class="mt-40 d-flex justify-content-between">
                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>

                                        <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.save')</button>
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
    </div>
</div>
</section>
@endsection
