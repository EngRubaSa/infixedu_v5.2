@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.dormitory')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="{{route('student_dormitory')}}">@lang('lang.dormitory')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        @if(isset($room_list))
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('room-list')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('lang.add')
                </a>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30"> @lang('lang.dormitory_room_list')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="default_table2" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                                <tr>
                                    <th>@lang('lang.dormitory')</th>
                                    <th>@lang('lang.room_number') </th>
                                    <th>@lang('lang.room_type')</th>
                                    <th>@lang('lang.no_of_bed')</th>
                                    <th>@lang('lang.cost_per_bed')</th>
                                    <th>@lang('lang.status')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($room_lists as $values)
                                @php @$rowCount=0; @endphp
                                    @foreach($values as $room_list)
                                    <tr>
                                        @if(@$rowCount==0)
                                        <td rowspan="{{@$values->count()}}">{{@$room_list->dormitory != ""? @$room_list->dormitory->dormitory_name:''}}</td>
                                        @endif
                                        @php @$rowCount=@$rowCount+1; @endphp
                                        <td>{{@$room_list->name}}</td>
                                        <td>{{@$room_list->roomType != ""? @$room_list->roomType->type: ''}}</td>
                                        <td>{{@$room_list->number_of_bed}}</td>
                                        <td>{{@$room_list->cost_per_bed}}</td>
                                        <td>
                                            @if(@$student_detail->room_id == @$room_list->id)
                                                <button class="primary-btn small fix-gr-bg">
                                                   @lang('lang.assigned')                                                 
                                                </button>
                                             @else
                                              
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection