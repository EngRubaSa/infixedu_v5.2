@extends('backEnd.master')
@section('mainContent')
<style>
    .CustomPaymentMethod{
        padding: 5px 0px 0px 0px !important;
        border-top: 0px !important;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.payment_method_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="#">@lang('lang.payment_method_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="mb-40 student-details">
    <div class="container-fluid p-0">
        <div class="row">

            
                <!-- Select a Payment Gateway --> 
                 
                 <div class="col-lg-3">

                    <div class="add_new gateway pb-10">
                        <div class="main-title">
                            <h3 class="mb-30">@if(isset($payment_method))
                                    @lang('lang.edit')
                                @else
                                    @lang('lang.add')
                                @endif
                                @lang('lang.payment_method')
                            </h3>
                        </div>
                        @if(isset($payment_method))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'payment_method_update',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @else
                          
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'payment_method_store',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12"> 
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ @$errors->has('method') ? ' is-invalid' : '' }}"
                                                type="text" name="method" autocomplete="off" value="{{isset($payment_method)? $payment_method->method: old('method')}}">
                                            <input type="hidden" name="id" value="{{isset($payment_method)? $payment_method->id: ''}}">
                                            <label>@lang('lang.method') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('method'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ @$errors->first('method') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                            	
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                      <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>
                                            @if(isset($payment_method))
                                                @lang('lang.update')
                                            @else
                                                @lang('lang.save')
                                            @endif
                                           @lang('lang.method')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div class="main-title pt-10">
                        <h3 class="mb-30">@lang('lang.select_a_payment_gateway')   </h3>  
                    </div>
                    @if(userPermission(413))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'is-active-payment']) }}
                    @endif
                    <div class="white-box">
                        <div class="row mt-40">
                            <div class="col-lg-12">
                                
                                <table class="table">
                                    @foreach($paymeny_gateway as $value)
                                    @if(moduleStatusCheck('RazorPay') == FALSE && $value->method =="RazorPay" ) 

                                    @else
                                      <tr>
                                            <td class="CustomPaymentMethod">                                              
                                                <div class="input-effect">
                                                    <input type="checkbox" id="gateway_{{@$value->method}}" class="common-checkbox class-checkbox" name="gateways[{{@$value->id}}]" 
                                                    value="{{@$value->id}}" {{@$value->active_status == 1? 'checked':''}}>
                                                    <label for="gateway_{{@$value->method}}">{{@$value->method}}    
                                                    </label>
                                                </div>
                                            </td> 
                                            <td class="CustomPaymentMethod"> 
                                                @if( @$value->type != "System")
                                                    <a class="pl-20" href="{{url('payment-method-settings-edit', [@$value->id])}}"><span class="ti-pencil-alt"></span></a>
                                                    <a class="pl-20" data-toggle="modal" data-target="#deletePaymentMethodModal{{@$value->id}}"  href="#"> <span class="ti-trash"></span> </a>
                                                    
                                                    {{-- modal for delete  --}}
                                                    <div class="modal fade admin-query" id="deletePaymentMethodModal{{@$value->id}}" >
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">@lang('lang.delete') @lang('lang.payment_method')</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                    
                                                                <div class="modal-body">
                                                                    <div class="text-center">
                                                                        <h4>@lang('lang.are_you_sure_to_delete')</h4>
                                                                    </div>
                    
                                                                    <div class="mt-40 d-flex justify-content-between">
                                                                        <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('lang.cancel')</button>
                                                                        <a href="{{route('payment_method_delete', [@$value->id])}}" class="text-light primary-btn fix-gr-bg">
                                                                            @lang('lang.delete')
                                                                        </a>
                                                                    </div>
                                                                </div>
                    
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- end modal for delete  --}}

                                                @endif
                                            </td>
                                      </tr>                               
                                    @endif

                                    @endforeach
                                </table>

                                @if($errors->has('gateways'))
                                    <span class="text-danger validate-textarea-checkbox" role="alert">
                                        <strong>{{ $errors->first('gateways') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        @php 
                            $tooltip = "";
                            if(userPermission(413)){ $tooltip = ""; }else{  $tooltip = "You have no permission to Update"; }
                        @endphp
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                    <span class="ti-check"></span>
                                    @lang('lang.update')
                                </button>
                            </div>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
                <!-- End Select a Payment Gateway -->  

            <div class="col-lg-9"> 

                 <div class="row pt-20">
                    <div class="main-title pt-10">
                        <h3 class="mb-30">@lang('lang.gateway_setting')   </h3>  
                    </div>
                    <ul class="nav nav-tabs justify-content-end mt-sm-md-20 mb-30" role="tablist">
                        @foreach($paymeny_gateway_settings as $row) 
                        @if(moduleStatusCheck('RazorPay') == FALSE && $row->gateway_name =="RazorPay")
                        @else
    
                            <li class="nav-item">
                                <a class="nav-link @if(@$row->gateway_name=='Stripe') active show @endif " href="#{{@$row->gateway_name}}" role="tab" data-toggle="tab">{{@$row->gateway_name}}</a> 
                            </li> 
    
                        @endif
                        @endforeach 
                    </ul>
                 </div>



                <!-- Tab panes -->
                <div class="tab-content">

                    @foreach($paymeny_gateway_settings as $row) 

                            <div role="tabpanel" class="tab-pane fade   @if(@$row->gateway_name=='Stripe') active show @endif " id="{{@$row->gateway_name}}">
 
                                @if(userPermission(414))
                                    <form class="form-horizontal" action="{{route('update-payment-gateway')}}" method="POST">
                                @endif   
                                    @csrf 
                                    <div class="white-box">

                                        

                                        <div class="">
                                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                                            <input type="hidden" name="gateway_name" id="gateway_{{@$row->gateway_name}}" value="{{@$row->gateway_name}}"> 
                                            <div class="row mb-30">
                                               <div class="col-md-10">
                                                <?php 

                                                if(@$row->gateway_name=="PayPal")
                                                {
                                                    @$paymeny_gateway = ['gateway_name','gateway_username','gateway_password','gateway_signature','gateway_client_id','gateway_mode','gateway_secret_key'];
                                                } 
                                                else if(@$row->gateway_name=="Stripe")
                                                { 
                                                    @$paymeny_gateway = ['gateway_name','gateway_username','gateway_secret_key','gateway_publisher_key']; 
                                                }
                                                else if(@$row->gateway_name=="Paystack")
                                                { 

                                                    @$paymeny_gateway = ['gateway_name','gateway_username','gateway_secret_key','gateway_publisher_key'];

                                                }else if(@$row->gateway_name=="Razorpay")
                                                { 
                                                    @$paymeny_gateway = ['gateway_name','gateway_secret_key','gateway_publisher_key'];

                                                }else if(@$row->gateway_name=="Bank"){
                                                    @$paymeny_gateway = ['gateway_name', 'bank_details'];

                                                }else if(@$row->gateway_name=="Cheque"){ 
                                                    @$paymeny_gateway = ['gateway_name','cheque_details'];

                                                }
                                                    if(@$row->gateway_name=="Stripe" || @$row->gateway_name=="Paystack" || @$row->gateway_name=="RazorPay"){

                                                    $count=0;

                                                    foreach ($paymeny_gateway as $input_field) {
                                                        @$newStr = @$input_field;
                                                        @$label_name = str_replace('_', ' ', @$newStr);  
                                                        @$value= @$row->$input_field; ?>

                                                        <div class="row">
                                                            <div class="col-lg-12 mb-30">
                                                                <div class="input-effect">
                                                                    <input class="primary-input form-control{{ $errors->has($input_field) ? ' is-invalid' : '' }}"
                                                                    type="text" name="{{$input_field}}" id="gateway_{{$input_field}}" autocomplete="off" value="{{isset($value)? $value : ''}}" @if(@$count==0) readonly="" @endif>
                                                                    <label>{{@$label_name}} <span></span> </label>
                                                                    <span class="focus-border"></span>
                                                                    <span class="modal_input_validation red_alert"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php $count++; } ?>

                                              <?php  }elseif(@$row->gateway_name=="Bank" || @$row->gateway_name=="Cheque") {

                                                $count=0;

                                                    foreach ($paymeny_gateway as $input_field) {
                                                        @$newStr = @$input_field;
                                                        @$label_name = str_replace('_', ' ', @$newStr);  
                                                        @$value= @$row->$input_field; ?>
                                                        @if($count == 0)

                                                            <div class="row">
                                                            <div class="col-lg-12 mb-30">
                                                                <div class="input-effect">
                                                                    <input class="primary-input form-control{{ $errors->has($input_field) ? ' is-invalid' : '' }}"
                                                                    type="text" name="{{$input_field}}" id="gateway_{{$input_field}}" autocomplete="off" value="{{isset($value)? $value : ''}}" @if(@$count==0) readonly="" @endif>
                                                                    <label>{{@$label_name}} <span></span> </label>
                                                                    <span class="focus-border"></span>
                                                                    <span class="modal_input_validation red_alert"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @else
                                                        
                                                        <div class="row">
                                                            <div class="col-lg-12 mt-50">
                                                                <div class="input-effect sm2_mb_20">
                                                                    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
                                                                    <textarea class="primary-input article-ckeditor form-control" cols="0" rows="3" name="{{$input_field}}" id="article-ckeditor">{{@$value}}</textarea>

                                                                    <script>
                                                                        CKEDITOR.replace( "<?php echo $input_field ?>" );
                                                                    </script>
                                                                    <span class="focus-border textarea"></span>
                                                                    <label class="textarea-label"> @lang('lang'.'.'.$input_field) <span></span> </label>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        @endif
                                                        

                                                        <?php $count++; } 



                                              }
                                              ?>


                                            </div>

                                            <div class="col-md-7">
                                                <div class="row justify-content-center">
                                                    @if(!empty(@$row->logo))
                                                        <img class="logo"  src="{{ URL::asset(@$row->logo) }}" style="width: auto; height: 100px; ">  

                                                    @endif


                                                </div>
                                                <div class="row justify-content-center">
                                                  
                                                        @if(session()->has('message-success'))
                                                          <p class=" text-success">
                                                              {{ session()->get('message-success') }}
                                                          </p>
                                                        @elseif(session()->has('message-danger'))
                                                          <p class=" text-danger">
                                                              {{ session()->get('message-danger') }}
                                                          </p>
                                                        @endif 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php 
                                        $tooltip = "";
                                        if(userPermission(414)){
                                                $tooltip = "";
                                            }else{
                                                $tooltip = "You have no permission to add";
                                            }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                                <span class="ti-check"></span>
                                                @lang('lang.update')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>



                        </div> 

                    @endforeach 

                </div>
            </div>



        </div>
    </div>
</section>
@endsection
