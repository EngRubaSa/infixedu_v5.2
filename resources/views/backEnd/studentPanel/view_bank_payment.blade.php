
<script src="{{asset('public/backEnd/')}}/js/main.js"></script>

<div class="container-fluid">
<style>
hr {
  border: 0;
  clear:both;
  display:block;
  width: 96%;               
  background-color:#369dff;
  height: 1px;
}
</style>
        <div class="row">
            @foreach ($fees_payments as $fees_payment)
                
            
            <div class="col-lg-12">
                <div class="row mt-25">
                    <div class="col-lg-6  mt-20">
                        <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.fees_type'):
                                    </div>
                                    <div class="value">
                                        {{@$fees_payment->feesType->name}}
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-6 mt-20">
                        <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.date'):
                                    </div>
                                    <div class="value">
                                        {{ !empty($fees_payment->date)? dateConvert(@$fees_payment->date):''}}
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-6 mt-20">
                        <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.amount'):
                                    </div>
                                    <div class="value">
                                        {{@$fees_payment->amount}}
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-6 mt-20">
                        <div class="single-meta">
                                <div class="d-flex justify-content-between">
                                    <div class="name">
                                        @lang('lang.note'):
                                    </div>
                                    <div class="value">
                                        {{@$fees_payment->note}}
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-12 mt-20">
                        <div class="single-meta">
                                <div class="justify-content-between">
                                    <div class="name">
                                        @lang('lang.slip')
                                    </div>
                                    <div class="value text-center">
                                        <img class="student-meta-img" width="80%" src="{{asset(@$fees_payment->slip)}}" alt="">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>


            </div>
            <hr/>
            @endforeach
    <div class="d-flex align-items-center justify-content-center">

            <h3>
                Total : {{$amount}}
            </h3>
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
                </div>
            </div>
        </div>
</div>
