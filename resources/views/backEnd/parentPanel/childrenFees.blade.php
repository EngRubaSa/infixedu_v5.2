@extends('backEnd.master')
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('lang.fees')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                    <a href="#">@lang('lang.fees')</a>
                    <a href="{{route('student_fees')}}">@lang('lang.pay_fees')</a>
                </div>
            </div>
        </div>
    </section>

    <input type="hidden" id="url" value="{{URL::to('/')}}">
    <input type="hidden" id="student_id" value="{{$student->id}}">
    <section class="">
        <div class="container-fluid p-0 table-responsive">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="d-flex justify-content-between">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('lang.fees') @lang('lang.info')</h3>
                        </div>
                    </div>
                </div>
            </div>
            @if(session()->has('message-success'))
                <div class="alert alert-success">
                    {{ session()->get('message-success') }}
                </div>
            @elseif(session()->has('message-danger'))
                <div class="alert alert-danger">
                    {{ session()->get('message-danger') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <table class="display school-table school-table-style-parent-fees" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>@lang('lang.fees_group')</th>
                            <th>@lang('lang.due_date')</th>
                            <th>@lang('lang.status')</th>
                            <th>@lang('lang.amount') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.payment_id')</th>
                            <th>@lang('lang.mode')</th>
                            <th>@lang('lang.date')</th>
                            <th>@lang('lang.discount') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.fine') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.paid') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.balance') ({{generalSetting()->currency_symbol}})</th>
                            <th>@lang('lang.payment') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $grand_total = 0;
                            $total_fine = 0;
                            $total_discount = 0;
                            $total_paid = 0;
                            $total_grand_paid = 0;
                            $total_balance = 0;
                            $count = 0;
                        @endphp

                        @foreach($fees_assigneds as $fees_assigned)

                            @php
                                $count++;

                                        $grand_total += $fees_assigned->feesGroupMaster->amount;


                            @endphp

                            @php
                                $discount_amount = $fees_assigned->applied_discount;
                                $total_discount += $discount_amount;
                                $student_id = $fees_assigned->student_id;
                            @endphp
                            @php
                                $paid = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'amount');
                                $total_grand_paid += $paid;
                            @endphp
                            @php
                                $fine = App\SmFeesAssign::discountSum($fees_assigned->student_id, $fees_assigned->feesGroupMaster->feesTypes->id, 'fine');
                                $total_fine += $fine;
                            @endphp

                            @php
                                $total_paid = $discount_amount + $paid;
                            @endphp
                            <tr>
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <td>{{@$fees_assigned->feesGroupMaster->feesGroups->name}}
                                    / {{@$fees_assigned->feesGroupMaster->feesTypes->name}}</td>
                                <td>
                                    {{@$fees_assigned->feesGroupMaster->date != ""? dateConvert(@$fees_assigned->feesGroupMaster->date):''}}

                                </td>
                                @php
                                    $total_payable_amount=number_format($fees_assigned->feesGroupMaster->amount+$fine, 2, '.', '');
                                @endphp
                                @php

                                    $rest_amount = $fees_assigned->fees_amount;
                                        $total_balance +=  $rest_amount;
                                        $balance_amount =number_format($rest_amount, 2, '.', '');
                                @endphp
                                <td>
                                    @if($total_paid == $total_payable_amount)
                                        <button class="primary-btn small bg-success text-white border-0">@lang('lang.paid')</button>

                                    @elseif($paid != 0)
                                        <button class="primary-btn small bg-warning text-white border-0">@lang('lang.partial')</button>
                                    @elseif($paid == 0)
                                        <button class="primary-btn small bg-danger text-white border-0">@lang('lang.unpaid')</button>
                                    @endif

                                </td>
                                <td>

                                    @php
                                        echo $total_payable_amount;
                                    @endphp
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($discount_amount, 2, '.', '') }}</td>
                                <td>{{ number_format($fine, 2, '.', '') }}</td>
                                <td>{{ number_format($paid, 2, '.', '') }}</td>
                                <td>
                                    @php

                                        $rest_amount = $fees_assigned->fees_amount;

                                    // $total_balance +=  $rest_amount;
                                    echo number_format($fees_assigned->fees_amount, 2, '.', '');
                                    @endphp
                                </td>
                                <td>
                                    @if($rest_amount =! 0)
                                     {{-- {{@$rest_amount}} --}}
                                        {{-- @dd($rest_amount) --}}

                                        @php
                                            $already_add = App\SmBankPaymentSlip::where('student_id', $fees_assigned->student_id)->where('fees_type_id', $fees_assigned->feesGroupMaster->fees_type_id)->first();
                                        @endphp

                                        <div class="dropdown">

                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                               

                                                @if($already_add == "")
                                                    {{-- @dd($rest_amount+$total_fine) --}}
                                                    @if(@$data['bank_info']->active_status == 1 || @$data['cheque_info']->active_status == 1)

                                                        <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                           title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                           href="{{route('fees-generate-modal-child', [$fees_assigned->fees_amount, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id])}}">@lang('lang.add') @lang('lang.bank') @lang('lang.payment') </a>


                                                    @endif
                                                @else
                                               
                                                        {{-- {{$balance_amount}} --}}
                                                    @if($balance_amount !=0)
                                                                {{-- <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                            title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                            href="{{route('fees-generate-modal-child', [$rest_amount+$total_fine, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id])}}">@lang('lang.add') @lang('lang.bank') @lang('lang.payment')</a> --}}
                                                            <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"  href="{{route('fees-generate-modal-child', [$fees_assigned->fees_amount, $fees_assigned->student_id, $fees_assigned->feesGroupMaster->fees_type_id])}}" >@lang('lang.add') @lang('lang.bank') @lang('lang.payment')</a>
                                                    @endif
                                                    
                                                    
                                                    <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                       title="{{$fees_assigned->feesGroupMaster->feesGroups->name.': '. $fees_assigned->feesGroupMaster->feesTypes->name}}"
                                                       href="{{route('fees-generate-modal-child-view', [$fees_assigned->student_id,$fees_assigned->feesGroupMaster->fees_type_id])}}">@lang('lang.view') @lang('lang.bank') @lang('lang.payment') </a>


                                                    @if($already_add->approve_status == 0)



                                                        <a onclick="deleteId({{$already_add->id}});"
                                                           class="dropdown-item" href="#" data-toggle="modal"
                                                           data-target="#deleteStudentModal"
                                                           data-id="{{$already_add->id}}">@lang('lang.delete') @lang('lang.bank') @lang('lang.payment')</a>

                                                    @endif
                                                @endif


                                                @php
                                                    $is_paystack = DB::table('sm_payment_methhods')->where('method','Paystack')->where('active_status',1)->first();
                                                @endphp
                                                @if(!empty($is_paystack) && $balance_amount !=0)
                                                    <form method="POST" action="{{ route('pay-with-paystack') }}"
                                                          accept-charset="UTF-8" class="form-horizontal" role="form">

                                                        <input type="hidden" name="email"
                                                               value="otemuyiwa@gmail.com"> {{-- required --}}
                                                        <input type="hidden" name="orderID" value="345">
                                                        <input type="hidden" name="amount"
                                                               value="{{$fees_assigned->fees_amount * 100}}"> {{-- required in kobo --}}
                                                        <input type="hidden" name="quantity" value="3">
                                                        <input type="hidden" name="fees_type_id"
                                                               value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                        <input type="hidden" name="student_id" value="{{$student->id}}">
                                                        <input type="hidden" name="metadata"
                                                               value="{{ json_encode($array = ['key_name' => 'value',]) }}"> {{-- For other necessary things you want to add to your payload. it is optional though --}}
                                                        <input type="hidden" name="reference"
                                                               value="{{ Paystack::genTranxRef() }}"> {{-- required --}}
                                                        <input type="hidden" name="key"
                                                               value="{{ config('paystack.secretKey') }}"> {{-- required --}}
                                                        {{ csrf_field() }} {{-- works only when using laravel 5.1, 5.2 --}}

                                                        <input type="hidden" name="_token"
                                                               value="{{ csrf_token() }}"> {{-- employ this in place of csrf_field only in laravel 5.0 --}}


                                                        <button type="submit" class=" dropdown-item">Pay via
                                                            Paystack </button>

                                                    </form>
                                                @endif

                                                @php
                                                    $is_stripe = DB::table('sm_payment_methhods')->where('method','Stripe')->where('active_status',1)->where('school_id', Auth::user()->school_id)->first();
                                                @endphp

                                            @if(!empty($is_stripe) && $balance_amount !=0)

                                                    <a class="dropdown-item modalLink" data-modal-size="modal-lg"
                                                       title="@lang('lang.pay') @lang('lang.fees') "
                                                       @php
                                                        if(is_null($rest_amount)){
                                                            $rest_amount = 0;
                                                        }
                                                       @endphp
                                                       href="{{route('fees-payment-stripe',[$fees_assigned->feesGroupMaster->fees_type_id, $student->id,  $fees_assigned->fees_amount])}}">Pay
                                                        via Stripe</a>

                                                @endif



                                                {{-- Start Razorpay Checking --}}

                                                @if(moduleStatusCheck('RazorPay')== TRUE )
                                                    @if(moduleStatusCheck('RazorPay') == TRUE && !empty($is_RazorPay))
                                                        <form id="rzp-footer-form_{{$count}}"
                                                              action="{!!route('razorpay/dopayment')!!}" method="POST"
                                                              style="width: 100%; text-align: center">
                                                            @csrf

                                                            <input type="hidden" name="amount" id="amount"
                                                                   value="{{$fees_assigned->fees_amount * 100}}"/>

                                                            <input type="hidden" name="fees_type_id" id="fees_type_id"
                                                                   value="{{$fees_assigned->feesGroupMaster->fees_type_id}}">
                                                            <input type="hidden" name="student_id" id="student_id"
                                                                   value="{{$student->id}}">


                                                            <input type="hidden" name="amount" id="amount"
                                                                   value="{{$fees_assigned->fees_amount}}"/>
                                                            <div class="pay">
                                                                <button class="dropdown-item razorpay-payment-button btn filled small"
                                                                        id="paybtn_{{$count}}" type="button">Pay via
                                                                    Razorpay
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @endif

                                            </div>
                                        </div>


                                        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

                                        <!-- start razorpay code -->

                                        <script>


                                            $('#rzp-footer-form_<?php echo $count; ?>').submit(function (e) {
                                                var button = $(this).find('button');
                                                var parent = $(this);
                                                button.attr('disabled', 'true').html('Please Wait...');
                                                $.ajax({
                                                    method: 'get',
                                                    url: this.action,
                                                    data: $(this).serialize(),
                                                    complete: function (r) {
                                                        console.log('complete');
                                                        console.log(r);
                                                    }
                                                })
                                                return false;
                                            })
                                        </script>

                                        <script>
                                            function padStart(str) {
                                                return ('0' + str).slice(-2)
                                            }

                                            function demoSuccessHandler(transaction) {
                                                // You can write success code here. If you want to store some data in database.
                                                $("#paymentDetail").removeAttr('style');
                                                $('#paymentID').text(transaction.razorpay_payment_id);
                                                var paymentDate = new Date();
                                                $('#paymentDate').text(
                                                    padStart(paymentDate.getDate()) + '.' + padStart(paymentDate.getMonth() + 1) + '.' + paymentDate.getFullYear() + ' ' + padStart(paymentDate.getHours()) + ':' + padStart(paymentDate.getMinutes())
                                                );

                                                $.ajax({
                                                    method: 'post',
                                                    url: "{!!url('razorpay/dopayment')!!}",
                                                    data: {
                                                        "_token": "{{ csrf_token() }}",
                                                        "razorpay_payment_id": transaction.razorpay_payment_id,
                                                        "amount": <?php echo $rest_amount * 100; ?>,
                                                        "fees_type_id": <?php echo $fees_assigned->feesGroupMaster->fees_type_id; ?>,
                                                        "student_id": <?php echo $student->id; ?>
                                                    },
                                                    complete: function (r) {
                                                        console.log('complete');
                                                        console.log(r);

                                                        setTimeout(function () {
                                                            toastr.success('Operation successful', 'Success', {
                                                                "iconClass": 'customer-info'
                                                            }, {
                                                                timeOut: 2000
                                                            });
                                                        }, 500);

                                                        location.reload();
                                                    }
                                                })
                                            }
                                        </script>
                                        <script>
                                            var options_<?php echo $count; ?> = {
                                                key: "{{ @$razorpay_info->gateway_secret_key }}",
                                                amount: <?php echo $rest_amount * 100;?>,
                                                name: 'Online fee payment',
                                                image: 'https://i.imgur.com/n5tjHFD.png',
                                                handler: demoSuccessHandler
                                            }
                                        </script>
                                        <script>
                                            window.r_<?php echo $count; ?> = new Razorpay(options_<?php echo $count; ?>);
                                            document.getElementById('paybtn_<?php echo $count; ?>').onclick = function () {
                                                r_<?php echo $count; ?>.open()
                                            }
                                        </script>
                                        <!-- end razorpay code -->

                                    @endif

                                    @endif

                                    {{-- End Razorpay checking --}}

                                </td>

                            </tr>
                            @php
                                $payments = App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id, $fees_assigned->student_id);


                                $i = 0;
                            @endphp

                            @foreach($payments as $payment)
                                <tr>
                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><img src="{{asset('public/backEnd/img/table-arrow.png')}}">
                                    </td>
                                    <td>


                                        @php
                                            $created_by = App\User::find($payment->created_by);
                                        @endphp

                                        @if($created_by != "")
                                            <a href="#" data-toggle="tooltip" data-placement="right"
                                               title="{{'Collected By: '.$created_by->full_name}}">{{$payment->fees_type_id.'/'.$payment->id}}</a>
                                    </td>
                                    @endif


                                    <td>
                                        {{$payment->payment_mode}}
                                    </td>
                                    <td>
                                        {{$payment->payment_date != ""? dateConvert($payment->payment_date):''}}
                                    </td>
                                    <td>{{number_format($payment->discount_amount, 2, '.', '') }}</td>
                                    <td>{{number_format($payment->fine, 2, '.', '') }}</td>
                                    <td>{{number_format($payment->amount, 2, '.', '') }}</td>
                                    <td></td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach

                        @endforeach
                        @foreach($fees_discounts as $fees_discount)
                            <tr>
                                <td> @lang('lang.discount')</td>
                                <td>{{$fees_discount->feesDiscount !=""?$fees_discount->feesDiscount->name:""}}</td>
                                <td></td>
                                <td>@if(in_array($fees_discount->id, $applied_discount))
                                        @php
                                            $createdBy = App\SmFeesAssign::createdBy($student_id, $fees_discount->id);
                                        @endphp

                                        @if($createdBy != "")
                                            @php
                                                $created_by = App\User::find($createdBy->created_by)
                                            @endphp
                                            @if(!empty( $created_by ))
                                                <a href="#" data-toggle="tooltip" data-placement="right"
                                                   title="{{'Collected By: '.$created_by->full_name}}">@lang('lang.discount') @lang('lang.of') {{generalSetting()->currency_symbol}}{{ number_format($fees_discount->feesDiscount->amount, 2, '.', '') }} @lang('lang.applied')
                                                    : {{$createdBy->id.'/'.$createdBy->created_by}}</a>
                                            @endif

                                        @endif

                                    @else
                                        @lang('lang.discount_of') {{generalSetting()->currency_symbol}}{{$fees_discount->feesDiscount !=""?number_format($fees_discount->feesDiscount->amount, 2, '.', '') :""}} @lang('lang.assigned')
                                    @endif
                                </td>
                                <td>{{$fees_discount->name}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>@lang('lang.grand_total') ({{generalSetting()->currency_symbol}})</th>
                            <th></th>
                            <th>{{ number_format($grand_total+$total_fine, 2, '.', '') }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{number_format($total_discount, 2, '.', '') }}</th>
                            <th>{{number_format($total_fine, 2, '.', '') }}</th>
                            <th>{{number_format($total_grand_paid, 2, '.', '') }}</th>
                            <th>{{number_format($total_balance, 2, '.', '') }}</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade admin-query" id="deleteFeesPayment">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('lang.delete') @lang('lang.item')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('lang.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg"
                                data-dismiss="modal">@lang('lang.cancel')</button>
                        {{ Form::open(['route' => 'fees-payment-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" id="feep_payment_id">
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade admin-query" id="deleteStudentModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('lang.delete') @lang('lang.item')</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <h4>@lang('lang.are_you_sure_to_delete')</h4>
                    </div>

                    <div class="mt-40 d-flex justify-content-between">
                        <button type="button" class="primary-btn tr-bg"
                                data-dismiss="modal">@lang('lang.cancel')</button>
                        {{ Form::open(['url' => 'child-bank-slip-delete', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="" id="student_delete_i"> {{-- using js in main.js --}}
                        <button class="primary-btn fix-gr-bg" type="submit">@lang('lang.delete')</button>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
