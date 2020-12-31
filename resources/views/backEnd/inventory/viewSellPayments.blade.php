<script src="{{asset('public/backEnd/')}}/js/main.js"></script>

<div class="row">
    <div class="col-lg-12">
        <table class="display school-table school-table-style" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('lang.payment') @lang('lang.date') </th>
                    <th>@lang('lang.reference') @lang('lang.none')</th>
                    <th>@lang('lang.amount')</th>
                    <th>@lang('lang.method')</th>
                    <th>@lang('lang.action')</th>

                </tr>
            </thead>

            <tbody>
            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
            @php $x=1; @endphp
            @if($payments)
                @foreach($payments as $value)
                <tr>
                    <td>{{$x++}}</td>
                    <td  data-sort="{{strtotime($value->payment_date)}}" >
                       {{$value->payment_date != ""? dateConvert($value->payment_date):''}}
                    </td>
                    <td>{{$value->reference_no}}</td>
                    <td>{{$value->amount}}</td>
                    <td>{{$value->paymentMethods !=""?$value->paymentMethods->method:""}}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="delete_sell_payments({{$value->id}})">@lang('lang.delete')</button></td>
                </tr>
               @endforeach
               @endif
            </tbody>
        </table>
    </div>
</div>
