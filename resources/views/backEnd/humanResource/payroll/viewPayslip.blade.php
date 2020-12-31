<script src="{{asset('public/backEnd/')}}/js/main.js"></script>
<div class="container-fluid">
    <div class="student-details">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center">
                <div>
                    <img class="logo-img" src="http://localhost/naim/schoolmanagementsystem/public/backEnd/img/logo.png"
                        alt="">
                </div>
                <div class="ml-30">
                    <h2>@if(isset($schoolDetails)){{$schoolDetails->school_name}} @endif</h2>
                    <p class="mb-0">@if(isset($schoolDetails)){{$schoolDetails->address}} @endif</p>
                </div>
            </div>
            <h3 class="mt-3">@lang('lang.payslip') @lang('lang.for_the_period_of') {{$payrollDetails->payroll_month}} {{$payrollDetails->payroll_year}}</h3>
        </div>

        <div class="single-meta d-flex justify-content-between mb-4">
            <div class="value text-left">
                @lang('lang.payslip') #@if(isset($payrollDetails)){{$payrollDetails->id}} @endif
            </div>
            <div class="name">
               
                @lang('lang.payment') @lang('lang.date'): @if(isset($payrollDetails))

                {{dateConvert($payrollDetails->payment_date)}}
               
                @endif
            </div>
        </div>


        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.staff_ID')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffs->staff_no}} @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.name')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffDetails->full_name}} @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.departments')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffDetails->departments->name}} @endif

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.designation')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->staffDetails->designations->title}} @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.payment')  @lang('lang.mode')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if($payrollDetails->payment_mode != "")
                            {{$payrollDetails->paymentMethods->method}}
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.basic_salary')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->basic_salary}} @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.gross_salary')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->gross_salary}} @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5">
                        <div class="value text-left">
                            @lang('lang.net_salary')
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-7">
                        <div class="name">
                            @if(isset($payrollDetails)){{$payrollDetails->net_salary}} @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>