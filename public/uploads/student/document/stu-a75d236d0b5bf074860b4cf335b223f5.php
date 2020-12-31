@extends('website2.layout.master2')
@section('content')
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- Top header  -->
        <!-- ============================================================== -->

        <!-- =========================== Breadcrumbs =================================== -->
        <div class="breadcrumbs_wrap dark">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="text-center">
                            <h2 class="breadcrumbs_title">Checkout</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i
                                                class="fa fa-home"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- =========================== Breadcrumbs =================================== -->
        <section>
            <div class="container">
                @if (count($data['billing_infos']) > 0)
                    <h4 class="mb-3">Billing Address</h4>
                    <!-- Shipping details -->
                    <form action="{{route('user.grocery.checkout')}}" method="post" class="billing_form">@csrf
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm table-hover mb-0">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input billing_info" id="billing_address"
                                                   name="billing_address" value="new" type="radio" checked>
                                            <label class="custom-control-label text-body text-nowrap"
                                                   for="billing_address">Add Delivery Address</label>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input billing_info"
                                                   id="checkoutShippingExpress"
                                                   name="billing_address" value="previous" type="radio" {{session()->has('old') ? 'checked' : ''}}>
                                            <label class="custom-control-label text-body text-nowrap"
                                                   for="checkoutShippingExpress">Previous Delivery Address</label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-8 col-md-12">
                                <!-- Heading -->
                                <h4 class="mb-5">Billing Details</h4>
                                <div class="row mb-5 billing_address" @if (!session()->has('old'))
                                style="display: none" @endif>
                                    <div class="col-12 col-md-12">
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="checkoutBillingEmail">Billing Address *</label>
                                            <select name="billing_id" id="billing_id"
                                                    class="form-control city js-example-basic-single billing_id form-control-sm">
                                                <option disabled selected>Select Billing Address</option>
                                                @foreach ($data['billing_infos'] as $billing_info)
                                                    <option value="{{$billing_info->id}}">
                                                        Zip: {{$billing_info->zip_code}}
                                                        &
                                                        Address: {{Illuminate\Support\Str::limit($billing_info->address,30)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive mb-3 old_billing_info" style="display: none">
                                    <table class="table table-bordered table-sm table-hover mb-0">
                                        <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td class="billing_name"></td>
                                        </tr>

                                        <tr>
                                            <td>Email</td>
                                            <td class="billing_email"></td>
                                        </tr>
                                        <tr>
                                            <td>Phone</td>
                                            <td class="billing_phone"></td>
                                        </tr>

                                        <tr>
                                            <td>City</td>
                                            <td class="billing_city"></td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="old_billing_address"></td>
                                        </tr>
                                        <tr>
                                            <td>Zip Code</td>
                                            <td class="zip_code"></td>
                                        </tr>
                                        <tr>
                                            <td>Order Note</td>
                                            <td class="billing_note"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Billing details -->
                                <div class="row mb-5 new_billing_form" @if (session()->has('old'))
                                style="display: none" @endif>
                                    <div class="col-12 col-md-6">
                                        <!-- First Name -->
                                        <div class="form-group">
                                            <label for="checkoutBillingFirstName">Name *</label>
                                            <input class="form-control form-control-sm" id="name"
                                                   type="text" name="name" placeholder="First Name">
                                            <div class="invalid-feedback">
                                                Please fill in your username
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="checkoutBillingEmail">Email *</label>
                                            <input class="form-control form-control-sm" name="email"
                                                   id="email" type="email" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="checkoutBillingEmail">Phone *</label>
                                            <input class="form-control form-control-sm" name="phone"
                                                   id="phone" type="tel" placeholder="Phone">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="checkoutBillingEmail">City *</label>
                                            <select name="city" id="city"
                                                    class="form-control city js-example-basic-single form-control-sm">
                                                <option value="">Select City</option>
                                                @foreach ($data['cities'] as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{--                                <div class="col-12 col-md-5">--}}
                                    {{--                                    <!-- Email -->--}}
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <label for="checkoutBillingEmail">Area *</label>--}}
                                    {{--                                        <select name="location" id="location" class="form-control location form-control-sm">--}}

                                    {{--                                        </select>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <span class="text-danger location_id"></span>--}}
                                    {{--                                    --}}
                                    {{--                                </div>--}}

                                    <div class="col-12 col-md-5">
                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="checkoutBillingEmail">Zip Code *</label>
                                            <input name="zip_code" id="zip_code" class="form-control form-control-sm">

                                        </div>

                                    </div>
                                    <div class="col-12">
                                        <!-- Address Line 2 -->
                                        <div class="form-group">
                                            <label for="checkoutBillingAddressTwo">Address</label>
                                            <input class="form-control form-control-sm" name="address"
                                                   id="address" type="text" placeholder="Address">
                                        </div>

                                    </div>
                                    <div class="col-12">
                                    <textarea class="form-control form-control-sm mb-9 mb-md-0 font-size-xs" rows="5"
                                              name="notes" id="order_note"
                                              placeholder="Order Notes (optional)"></textarea>
                                    </div>
                                </div>

                                <!-- Heading -->
                                <h4 class="mb-3">Payment</h4>
                                <input type="hidden" class="gateway" name="gateway_code">

                                @foreach($data['gates'] as $gateway)
                                    <input type="submit" data-id="{{$gateway->code}}"
                                           value="Pay with {{$gateway->name}}" id="clickForPayment"
                                           class="brd-rd3 btn btn-primary" title="">
                        @endforeach
                    </form>

            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="cart_detail_box mb-4">
                    <div class="card-body">
                        <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                            <li class="list-group-item d-flex">
                                <h5 class="mb-0">Order Summary</h5>
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Subtotal</span> <span
                                    class="ml-auto font-size-sm">${{$data['sub_total']}}</span>
                            </li>
                            @if(Auth::check() && Auth::user()->is_storesale ==0)
                                <li class="list-group-item d-flex">
                                    <span>Tax</span> <span
                                        class="ml-auto font-size-sm">${{number_format($data['charge'] ,2)}}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex">
                                <span>Delivery Charge</span> <span
                                    class="ml-auto font-size-sm">${{number_format($data['delivery_charge'],2)}}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Tip</span> <span
                                    @php
                                        $tip = session()->has('tip') ? session()->get('tip') : 0;
                                    @endphp
                                    class="ml-auto font-size-sm">${{number_format($tip,2)}}</span>
                            </li>

                            @if($data['discount'])
                                <li class="list-group-item d-flex">
                                    <span>Discount</span> <span
                                        class="ml-auto font-size-sm">${{number_format($data['discount'],2)}}</span>
                                </li>
                                @php
                                    $total =($data['sub_total'] + $data['charge'] + $data['delivery_charge'] + $tip) - $data['discount'];
                                @endphp
                            @else
                                @php
                                    $total = ($data['sub_total'] + $data['charge'] + $data['delivery_charge'] + $tip);
                                @endphp
                            @endif
                            <li class="list-group-item d-flex font-size-lg font-weight-bold">
                                <span>Total</span> <span
                                    class="ml-auto font-size-sm">${{number_format($total,2)}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="px-0 text-body" href="{{route('home')}}"><i class="ti-back-left mr-2"></i>
                    Continue
                    Shopping</a>
            </div>

        </section>


        @endsection
        @push('js')
            <script type="text/javascript" src="https://rawgit.com/jessepollak/card/master/dist/card.js"></script>
            <script>
                $(document).ready(function () {
                    $(".city").css("display", "")
                    $(".location").css("display", "")
                    $(document).on('change', '.city', function () {
                        let city_id = $(this).val();
                        $.ajax({
                            url: "{{route('find.location')}}",
                            method: "POST",
                            data: {
                                city_id: city_id,
                                _token: "{{csrf_token()}}"
                            },
                            success: function (data) {
                                $('.location').html(data);

                            }
                        });
                    })
                });
                // $(document).ready(function () {
                //     var card = new Card({
                //         form: '#payment-form',
                //         container: '.form-Stripe',
                //         formSelectors: {
                //             numberInput: 'input[name="cardNumber"]',
                //             expiryInput: 'input[name="cardExpiry"]',
                //             cvcInput: 'input[name="cardCVC"]',
                //             nameInput: 'input[name="name"]'
                //         }
                //     });
                // });

                $(document).on('change', '.billing_id', function () {
                    let id = $(this).val();
                    $.ajax({
                        url: "{{route('user.billing.info')}}",
                        method: "POST",
                        data: {
                            id: id,
                            _token: "{{csrf_token()}}"
                        },
                        success: function (data) {
                            $('.old_billing_info').show();
                            $('.billing_name').text(data.name);
                            $('.billing_phone').text(data.phone);
                            $('.old_billing_address').text(data.address);
                            $('.billing_email').text(data.email);
                            $('.billing_city').text(data.city.name);
                            $('.billing_note').text(data.order_note);
                            $('.zip_code').text(data.zip_code);
                        }
                    })
                });

                $(document).on('change', '.location', function () {
                    let location_id = $(this).val();
                    $.ajax({
                        url: "{{route('find.zip')}}",
                        method: "POST",
                        data: {
                            location_id: location_id,
                            _token: "{{csrf_token()}}"
                        },
                        success: function (data) {
                            $('.zip').val(data);
                        }
                    });
                });
                $(document).on('click', '.billing_info', function () {
                    let value = $(this).val();
                    if (value == 'previous') {
                        $('.new_billing_form').hide()
                        $('.billing_address').show()
                    }
                    if (value == 'new') {
                        $('.new_billing_form').show()
                        $('.billing_address').hide();
                        $('.old_billing_info').hide();
                    }
                });
                $(document).on('click', "#clickForPayment", function () {
                    let code = $(this).data('id');
                    $('.gateway').val(code)
                    /*let email = $("#email").val();
                    let name = $("#name").val();
                    let phone = $("#phone").val();
                    let address = $("#address").val();
                    let order_note = $("#order_note").val();
                    let zip_code = $("#zip_code").val();
                    let city_id = $("#city").val();
                    // let location_id = $("#location").val();
                    let billing_id = $("#billing_id").val();
                    let billing_address = $("input[name='billing_address']:checked").val();
                    let gateway = $(this).data('id');


                    $.ajax({
                        url: "{{route('user.grocery.checkout')}}",
                method: "POST",
                data: {
                    name: name,
                    email: email,
                    phone: phone,
                    address: address,
                    order_note: order_note,
                    city_id: city_id,
                    zip_code: zip_code,
                    billing_id: billing_id,
                    billing_address: billing_address,
                    _token: "{{csrf_token()}}"
                },
                success: function (data) {
                    window.location.href = "{{url('/user/payment_via/')}}" + "/" + gateway + "/payment"
                },
                error: function (reject) {
                    var errors = reject.responseJSON.errors;
                    console.log(errors.name);
                    if (errors.name)
                        $('.name').text(errors.name);
                    if (errors.email)
                        $('.email').text(errors.email);
                    if (errors.phone)
                        $('.phone').text(errors.phone);
                    if (errors.address)
                        $('.address').text(errors.address);
                    if (errors.city_id)
                        $('.city_id').text(errors.city_id);
                    if (errors.zip_code)
                        $('.zip_code').text(errors.zip_code);
                    if (errors.billing_id)
                        $('.billing_id').text(errors.billing_id);
                    if (errors.billing_address)
                        $('.address').text(errors.billing_address);

                }
            });*/
                })
            </script>
    @endpush
