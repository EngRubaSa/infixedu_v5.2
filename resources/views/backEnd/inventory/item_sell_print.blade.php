{{-- <!DOCTYPE html>
<html>
<head>
    <title>Fees groups Details</title>
    <style>
      
        .school-table-style {
            padding: 10px 0px!important;
        }
        .school-table-style tr th {
            font-size: 6px!important;
            text-align: left!important;
        }
        .school-table-style tr td {
            font-size: 7px!important;
            text-align: left!important;
            padding: 10px 0px!important;
        }
        .logo-image {
            width: 10%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
</head>
<body>
    @php  $setting = App\SmGeneralSettings::find(1);  if(!empty($setting->currency_symbol)){ generalSetting()->currency_symbol = $setting->currency_symbol; }else{ generalSetting()->currency_symbol = '$'; }   @endphp 
 
    <table style="width: 100%;" tyle="width: 100%; table-layout: fixed">
        <tr>
             
            <td style="width: 30%"> 
                <img src="{{url($setting->logo)}}" alt="{{url($setting->logo)}}"> 
            </td> 
            <td  style="width: 70%">  
                <h3>{{$setting->school_name}}</h3>
                <h4>{{$setting->address}}</h4>
            </td> 
        </tr> 
    </table>
    <hr>
    <div class="row mb-20">
            <div class="col-lg-4">
                <div class="invoice-details-left">
                        <div class="customer-info">
                                <h2>Bill To:</h2>
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
                </div>
            </div>
            <div class="offset-lg-4 col-lg-4">
                <div class="invoice-details-right">
                    <h1 class="text-uppercase">Sell receipt</h1>
                    <div class="d-flex justify-content-between">
                        <p class="fw-500 primary-color">Sell Date:</p>

                       
                        <p>{{(isset($viewData)) ?  dateConvert($viewData->sell_date) : ''}}</p>

                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="fw-500 primary-color">Reference No:</p>
                        <p>#{{(isset($viewData)) ? $viewData->reference_no : ''}}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="fw-500 primary-color">Payment Status:</p>
                        <p>
                            @if($viewData->paid_status == 'P')
                            <strong>Paid</strong>
                            @elseif($viewData->paid_status == 'PP')
                            <strong>Partial Paid</strong>
                    
                            @elseif($viewData->paid_status == 'R')
                            <strong>Refund</strong>
                            @else
                            <strong>Unpaid</strong>
                            @endif
                        </p>
                    </div>

                    <span class="primary-btn fix-gr-bg large mt-30">${{(isset($viewData)) ? number_format( (float) $viewData->grand_total, 2, '.', '') : ''}}</span>
                </div>
            </div>
        </div>
    <div class="text-center"> 
        <h4 class="text-center mt-1"><span>Sell item paid details</span></h4>
    </div>
	<table class="d-table table-responsive custom-table" cellspacing="0" width="100%" style="width: 100%; table-layout: fixed">
        <thead>
            <tr>
                <th width="40%">Description</th>
                <th width="20%">Quantity</th>
                <th width="20%">Price</th>
                <th width="20%">Amount</th>
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
                <td class="fw-600 primary-color">Subtotal</td>
                <td>{{number_format( (float) $sub_totals, 2, '.', '')}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600">Paid Amount</td>
                <td class="fw-600">
                {{(isset($viewData)) ? number_format( (float) $viewData->total_paid, 2, '.', '') : ''}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600">Due Amount</td>
                <td class="fw-600">
                {{(isset($viewData)) ? number_format( (float) $viewData->total_due, 2, '.', '') : ''}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600 text-dark">Total</td>
                <td class="fw-600 text-dark">
                {{(isset($viewData)) ? number_format( (float) $viewData->grand_total, 2, '.', '') : ''}}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
 --}}