@extends('backEnd.master')
@section('mainContent')
<style>
    .propertiesname{
        text-transform: uppercase;
    }
    </style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Meeting List</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">Dashboard</a>
                <a href="#">Zoom</a>
                <a href="#">Meeting List</a>
            </div>
        </div>
    </div>
</section>


<section class="admin-visitor-area">
    <div class="container-fluid p-0">

        <div class="row">
            <div class="col-lg-10">
                <h3 class="mb-30">Zoom Meetings List</h3>
            </div>
            <div class="col-lg-2 text-right col-md-12 mb-20">
                <a href="{{url('zoom-meetting-create')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>Add
                </a>
            </div>
        </div>
        <div class="row">


            <div class="col-lg-3">

        @if(isset($editdata))
            <form class="form-horizontal" action="{{url('/zoom-meeting-upodate')}}" method="POST" enctype="multipart/form-data">
        @else
            <form class="form-horizontal" action="{{url('/zoom-meeting-store')}}" method="POST" enctype="multipart/form-data">
        @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="row mt-40">
                                <div class="col-lg-12">
                                    <div class="input-effect">
                                        <input class="primary-input form-control{{ $errors->has('topic') ? ' is-invalid' : '' }}"
                                        type="text" name="topic" autocomplete="off" value="{{isset($editData)? @$editData->topic : old('topic')}}">
                                        <label>Topic<span>*</span></label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('school_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('topic') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-40">
                                <div class="col-lg-12">
                                    <div class="input-effect">
                                    <textarea class="primary-input form-control" cols="0" rows="4" name="description" id="address">{{isset($editData) ? @$editData->description : old('description')}}</textarea>
                                        <label>Description <span></span> </label>
                                        <span class="focus-border textarea"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-40">
                                <div class="col-lg-6">
                                    <label>Date of Meeting *</label>
                                    <input class="primary-input date form-control" id="startDate" type="text" name="date" readonly="true" value="{{date('m/d/Y')}}" required>
                                    @if ($errors->has('date'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                        <label>Time of Meeting  *</label>
                                        <input class="primary-input time form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}" type="text" name="time" value="{{isset($editData)? $class_time->time: old('time')}}">
                                        <span class="focus-border"></span>
                                        @if ($errors->has('time'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ @$errors->first('time') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="row mt-40">
                                <div class="col-lg-12">
                                    <div class="input-effect">
                                        <input class="primary-input form-control{{ $errors->has('durration') ? ' is-invalid' : '' }}"
                                        type="number" name="durration" autocomplete="off" value="{{isset($editData)? @$editData->topic : old('topic')}}">
                                        <label>Meetting Durration (Minutes)<span>*</span></label>
                                        <span class="focus-border"></span>
                                        @if ($errors->has('durration'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('durration') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>


                        <div class="row mt-30">
                            <div class="col-lg-12 d-flex1">
                                <p class="text-uppercase fw-500 mb-10">Audio options</p>
                                <div class="d-flex1 radio-btn-flex1 ml-40">
                                    <div class="mr-30 row mt-10">
                                        <input type="radio" name="relationButton" id="relationFather" value="ot" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="relationFather">Only Telephone</label>
                                    </div>
                                    <div class="mr-30 row mt-10">
                                        <input type="radio" name="relationButton" id="relationMother" value="voip" class="common-radio relationButton" {{old('relationButton') == "M"? 'checked': ''}}>
                                        <label for="relationMother">Only Vo IP </label>
                                    </div>
                                    <div class="mr-30 row mt-10">
                                        <input type="radio" name="relationButton" id="relationOther" value="b" class="common-radio relationButton"  {{old('relationButton') != ""? (old('relationButton') == "O"? 'checked': ''): 'checked'}}>
                                        <label for="relationOther">Both</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-30">
                            <div class="col-lg-12 d-flex">
                                <p class="text-uppercase fw-500 mb-10" style="width: 130px;">Join before host</p>
                                <div class="d-flex radio-btn-flex ml-40">
                                    <div class="mr-30 row">
                                        <input type="radio" name="metting_options" id="metting_options1" value="1" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="metting_options1">Yes</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="metting_options" id="metting_options2" value="0" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="metting_options2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-30">
                            <div class="col-lg-12 d-flex">
                                <p class="text-uppercase fw-500 mb-10" style="width: 130px;">Host Video</p>
                                <div class="d-flex radio-btn-flex ml-40">
                                    <div class="mr-30 row">
                                        <input type="radio" name="host_video" id="host_video1" value="1" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="host_video1">Yes</label>
                                    </div>
                                    <div class="mr-30 row">
                                        <input type="radio" name="host_video" id="host_video2" value="0" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="host_video2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row mt-30">
                            <div class="col-lg-12 d-flex">
                                <p class="text-uppercase fw-500 mb-10" style="width: 130px;">Recurring options</p>
                                <div class="d-flex radio-btn-flex ml-40">
                                    <div class="mr-30">
                                        <input type="radio" name="recurring_options" id="recurring_options1" value="1" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="recurring_options1">Yes</label>
                                    </div>
                                    <div class="mr-30">
                                        <input type="radio" name="recurring_options" id="recurring_options2" value="0" class="common-radio relationButton" {{old('relationButton') == "F"? 'checked': ''}}>
                                        <label for="recurring_options2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                Save
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="" class="display school-table school-table-style" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th>SL</th>
                                {{--  <th>Host Id</th>  --}}
                                <th>Topic</th>
                                <th>Start Time</th>
                                <th>Duration</th>
                                <th>Join URL</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $count=1; @endphp
                            @foreach($meetings as $meeting)
                            <tr>
                                <td>{{@$count++}}</td>
                                {{--  <td>{{@$meeting['host_id']}}</td>  --}}
                                <td>{{@$meeting['topic']}}</td>
                                <td>
                                    @php
                                    $st = explode('T',$meeting['start_time'] );
                                    $mtDate=strtotime($st[0]);
                                    $today =  strtotime(date('Y-m-d'));
                                    if($today<$mtDate){
                                        $flag=1;
                                    }else{
                                        $flag=0;
                                    }
                                    @endphp
                                    {{date('l, jS \of F Y h:i:s A', strtotime($meeting['start_time']))}}</td>
                                <td>{{@$meeting['duration']}}</td>
                                {{--  <td>{{@$meeting['agenda']}}</td>  --}}
                                <td>
                                    @if($flag)
                                        <a class="primary-btn small bg-success text-white border-0" href="{{@$meeting['join_url']}}" target="_blank" >Start</a>
                                    @else
                                    <a href="#Closed" class="primary-btn small bg-warning text-white border-0">Closed</button>
                                        {{--  <a class="primary-btn small tr-bg" href="#" target="_blank" >Closed</a>   --}}
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                            select
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{url('zoom-metting-edit', [@$meeting['id']])}}">edit</a>
                                            <a class="dropdown-item" href="{{url('meetting-view', [@$meeting['id']])}}">view</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#d{{@$meeting['id']}}" href="#">delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <div class="modal fade admin-query" id="d{{@$meeting['id']}}" >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete Item</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="text-center">
                                                <h4>Are you sure to delete ?</h4>
                                            </div>

                                            <div class="mt-40 d-flex justify-content-between">
                                                <button type="button" class="primary-btn tr-bg" data-dismiss="modal">cancel</button>
                                                <form class="" action="{{url('zoom-meeting-delete')}}" method="POST" >
                                                    @csrf
                                                    <input type="hidden" value="{{@$meeting['id']}}" name="meeting_id">
                                                    <button class="primary-btn fix-gr-bg" type="submit">delete</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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
