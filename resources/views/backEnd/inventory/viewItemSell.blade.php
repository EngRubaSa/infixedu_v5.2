@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.sells') @lang('lang.details')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.inventory')</a>
                <a href="{{route('item-sell-list')}}">@lang('lang.item_sell') @lang('lang.list')</a>
                <a href="#"> @lang('lang.sells') @lang('lang.details')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
<div class="container-fluid p-0">
    <div class="row">
            <div class="col-lg-12">
                
                <div class="white-box">
                   <div class="row mt-40">
                    <div class="col-lg-12">
                <!-- <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">Item Receive List</h3>
                        </div>
                    </div>
                </div> -->

                <div class="row" id="purchaseInvoice">
                    <div class="container-fluid">
                        <div class="row mb-20">
                            <div class="col-lg-4">
                                <div class="invoice-details-left">
                                    <div class="mb-20">
                                        <label for="companyLogo" class="company-logo">
                                            <i class="ti-image"></i> <img src="{{ asset('/')}}{{ $general_setting->logo}}" alt=""> 
                                        </label>
                                        <input id="companyLogo" type="file"/>
                                    </div>

                                    <div class="business-info">
                                    <h3>{{ $general_setting->site_title}}</h3>
                                        <p>{{ $general_setting->address}}</p>
                                        <p>{{ $general_setting->email}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="offset-lg-4 col-lg-4">
                                <div class="invoice-details-right">
                                    <h1 class="text-uppercase">@lang('lang.sell_receipt')</h1>
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-500 primary-color">@lang('lang.sell_date'):</p>

                                       
                                        <p>{{(isset($viewData)) ?  dateConvert($viewData->sell_date) : ''}}</p>

                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-500 primary-color">@lang('lang.reference') @lang('lang.none'):</p>
                                        <p>#{{(isset($viewData)) ? $viewData->reference_no : ''}}</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-500 primary-color">@lang('lang.payment') @lang('lang.status'):</p>
                                        <p>
                                            @if($viewData->paid_status == 'P')
                                            <strong>@lang('lang.paid')</strong>
                                            @elseif($viewData->paid_status == 'PP')
                                            <strong>@lang('lang.partial_paid')</strong>
                                    
                                            @elseif($viewData->paid_status == 'R')
                                            <strong>@lang('lang.refund')</strong>
                                            @else
                                            <strong>@lang('lang.unpaid')</strong>
                                            @endif
                                        </p>
                                    </div>

                                    <span class="primary-btn fix-gr-bg large mt-30">${{(isset($viewData)) ? number_format( (float) $viewData->grand_total, 2, '.', '') : ''}}</span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="customer-info">
                                    <h2>@lang('lang.bill_to'):</h2>
                                </div>

                                @php
                                    $buyerDetails = '';
                                    if($viewData->role_id == '2'){
                                        $buyerDetails = $viewData->studentDetails;
                                    }elseif($viewData->role_id = "3"){
                                        $buyerDetails = $viewData->parentsDetails;
                                    }else{
                                        $buyerDetails = $viewData->staffDetails;
                                    }

                                @endphp

                                <div class="client-info">

                                    <h3>{{$viewData->role_id == 3? $buyerDetails->fathers_name:$buyerDetails->full_name }}</h3>
                                    <p>
                                        
                                        @if($viewData->role_id == "3")
                                            {{$buyerDetails->guardians_address}}
                                        @else
                                            {{$buyerDetails->current_address}}
                                        @endif
                                    </p>
                                    <p></p>
                                    <p></p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-30 mb-50">
                            <div class="col-lg-12">
                                <table class="d-table table-responsive custom-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40%">@lang('lang.description')</th>
                                            <th width="20%">@lang('lang.quantity')</th>
                                            <th width="20%">@lang('lang.price')</th>
                                            <th width="20%">@lang('lang.amount')</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @php $sub_totals = 0; @endphp
                                    @if(isset($editDataChildren))
                                    @foreach($editDataChildren as $value)
                                        <tr>
                                            <td>{{$value->items !=""?$value->items->item_name:""}}</td>
                                            <td>{{$value->quantity}}</td>
                                            <td>{{number_format( (float) $value->unit_price, 2, '.', '')}} </td>
                                            <td>{{number_format( (float) $value->sub_total, 2, '.', '')}}</td>
                                        </tr>
                                        @php $sub_totals += $value->sub_total; @endphp
                                    @endforeach
                                    @endif

                                        
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600 primary-color">@lang('lang.sub_total')</td>
                                            <td>{{number_format( (float) $sub_totals, 2, '.', '')}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600">@lang('lang.paid') @lang('lang.amount')</td>
                                            <td class="fw-600">
                                            {{(isset($viewData)) ? number_format( (float) $viewData->total_paid, 2, '.', '') : ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600">@lang('lang.due') @lang('lang.amount')</td>
                                            <td class="fw-600">
                                            {{(isset($viewData)) ? number_format( (float) $viewData->total_due, 2, '.', '') : ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600 text-dark">@lang('lang.total')</td>
                                            <td class="fw-600 text-dark">
                                            {{(isset($viewData)) ? number_format( (float) $viewData->grand_total, 2, '.', '') : ''}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>    

                    <div class="row mt-40">
                        <div class="col-lg-12 text-center">
                           <a class="primary-btn fix-gr-bg" target="_blank" href="{{ route('view-item-sell-print',@$viewData->id) }}" {{-- onclick="javascript:printDiv('purchaseInvoice')" --}}>@lang('lang.print')</a>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
@endsection
