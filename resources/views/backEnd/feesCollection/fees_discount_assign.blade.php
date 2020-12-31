@extends('backEnd.master')
@section('mainContent')
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('lang.select_criteria')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                        @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                        @elseif(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                        @endif
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-discount-assign-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <input type="hidden" name="fees_discount_id" id="fees_discount_id" value="{{$fees_discount_id}}">
                                <div class="col-lg-3 mt-30-md">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                        <option data-display="@lang('lang.select_class')" value="">@lang('lang.select_class')*</option>
                                        @foreach($classes as $class)
                                        <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                     @if ($errors->has('class'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('class') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md" id="select_section_div">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                        <option data-display="@lang('lang.select_section')" value="">@lang('lang.select_section')</option>
                                    </select>
                                    @if ($errors->has('section'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('category') ? ' is-invalid' : '' }}" name="category">
                                        <option data-display="@lang('lang.select') @lang('lang.category')" value="">@lang('lang.select') @lang('lang.category')</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}")}} {{isset($category_id)? ($category_id == $category->id? 'selected':''):''}}>{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('category'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('group') ? ' is-invalid' : '' }}" name="group">
                                        <option data-display="@lang('lang.select') @lang('lang.group')" value="">@lang('lang.select') @lang('lang.group') </option>
                                        @foreach($groups as $group)
                                        <option value="{{$group->id}}" {{isset($group_id)? ($group_id == $group->id? 'selected':''):''}}>{{$group->group}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('group'))
                                    <span class="invalid-feedback invalid-select" role="alert">
                                        <strong>{{ $errors->first('group') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('lang.search')
                                    </button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            {{-- {{dd(@$students)}} --}}
    @if(!empty($students))

        
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'fees-assign-store', 'enctype' => 'multipart/form-data'])}}
       


            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row mb-30">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('lang.assign') @lang('lang.fees_discount')</h3>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="fees_discount_id" value="{{$fees_discount_id}}" id="fees_discount_id">
                      
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-4">
                            <table id="table_id_table" class="display school-table" cellspacing="0" width="100%">  
                                <thead>
                                    <tr>
                                        <tr>
                                            <th>@lang('lang.fees_discount')</th>
                                            <th></th>
                                        </tr>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>{{$fees_discount->name}}</td>
                                        <td>{{$fees_discount->amount}}</td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-8">
                            <table  class="display school-table school-table-style" cellspacing="0" width="100%">
                                        
                                <thead>
                                    <tr>
                                        <th width="10%">
                                            <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll"  @php
                                                if(count($students) > 0){
                                                    if(count($students) == count($pre_assigned)){
                                                        echo 'checked';
                                                    }         
                                                }
                                            @endphp>
                                            <label for="checkAll"> @lang('lang.all')</label>
                                        </th>
                                        <th width="20%">@lang('lang.student') @lang('lang.name')</th>
                                        <th width="10%">@lang('lang.admission') @lang('lang.no')</th>
                                        <th width="15%">@lang('lang.class')</th>
                                        <th width="15%">@lang('lang.fees_type')</th>
                                        <th width="15%">@lang('lang.father_name')</th>
                                        <th width="10%">@lang('lang.category')</th>
                                        <th width="5%">@lang('lang.gender')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>
                                            @php
                                                $checkPayment=App\SmFeesDiscount::CheckAppliedDiscount($fees_discount_id,$student->id);
                                               $show='';
                                                if ($checkPayment=='false') {
                                                    $show='disabled';
                                                }
                                            @endphp
                                            <input type="checkbox" id="student.{{$student->id}}" {{@$show}} class="common-checkbox" name="student_checked[]" value="{{$student->id}}" {{in_array($student->id, $pre_assigned)? 'checked':''}} {{in_array($student->id, $already_paid)? 'disabled="disabled"':''}} >
                                            <label for="student.{{$student->id}}"></label>
                                        </td>
                                        <td>{{$student->first_name.' '.$student->last_name}} <input type="hidden" name="id[]" value="{{isset($update)? $student->forwardBalance->id: $student->id}}"></td>
                                        <td>{{$student->admission_no}}</td>
                                        <td>{{$student->className != ""? @$student->className->class_name :""}} {{'('.$student->section!=""? $student->section->section_name:"".')' }}</td>
                                        <td>
                                            
                                             @php
                                           
                                            $check_discount_apply= DB::table('sm_fees_assign_discounts')
                                            ->where('student_id',$student->id)
                                            ->where('fees_discount_id',$fees_discount_id)
                                            ->leftjoin('sm_fees_types','sm_fees_types.id','=','sm_fees_assign_discounts.fees_type_id')
                                            ->leftjoin('sm_fees_groups','sm_fees_groups.id','=','sm_fees_assign_discounts.fees_group_id')
                                            ->select('sm_fees_assign_discounts.*','sm_fees_groups.name as fees_group_name','sm_fees_types.name as fees_type_name')
                                            ->first();
                                            @endphp

                                            @if ($fees_discount->type=='once')
                                            

                                                    @if ($check_discount_apply=='') 
                                                        <select class="niceSelect w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" {{@$show}} name="fees_master_id[]" id="fees_master{{$student->id}}">
                                                            <option data-display="@lang('lang.select') @lang('lang.fees_type')" value="">@lang('lang.select') @lang('lang.fees_type')</option>
                                                            @foreach(@$assigned_fees_types[$student->id] as $fees_type)
                                                            <option value="{{$fees_type->id}}")}} >{{$fees_type->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('fees_master_id'))
                                                        <span class="invalid-feedback invalid-select" role="alert">
                                                            <strong>{{ $errors->first('fees_master_id') }}</strong>
                                                        </span>
                                                        @endif
                                                    @else 
                                                        {{-- {{@$check_discount_apply->fees_type_id}} --}}

                                                        <select class="niceSelect w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" name="fees_master_id[]" title="Discount Applied" id="fees_master{{$student->id}}">
                                                            <option data-display="@lang('lang.select') @lang('lang.fees_type')" value="">@lang('lang.select') @lang('lang.fees_type')</option>
                                                            {{-- @foreach(@$assigned_fees_types[$student->id] as $fees_type) --}}
                                                            <option value="{{$check_discount_apply->fees_type_id}}" selected  >{{$check_discount_apply->fees_type_name}}</option>
                                                            {{-- @endforeach --}}
                                                        </select>
                                                        @if ($errors->has('fees_master_id'))
                                                        <span class="invalid-feedback invalid-select" role="alert">
                                                            <strong>{{ $errors->first('fees_master_id') }}</strong>
                                                        </span>
                                                        @endif
                                                @endif
                                            @else 
                                            
                                            @php
                                            
                                            $group_ids= array();
                                            @endphp
                                                @if ($check_discount_apply=='')

                                               
                                               <select class="niceSelect w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" name="fees_master_id[]" id="fees_master{{$student->id}}">
                                                    <option data-display="@lang('lang.select') @lang('lang.fees_group')" value="">@lang('lang.select') @lang('lang.fees_group')</option>
                                                    
                                                    @foreach($assigned_fees_groups[$student->id] as $key=> $fees_group)
                                                   
                                                                @php
                                                                // $group_ids=$fees_group->group_id;
                                                                
                                                                if( in_array($fees_group->group_id, $group_ids) ) { 
                                                                continue;
                                                                }
                                                                array_push($group_ids,$fees_group->group_id);
                                                                @endphp
                                                        <option value="{{$fees_group->group_id}}")}} >{{$fees_group->name}} </option>
                                                    @endforeach
                                                 </select>
                                                    @if ($errors->has('fees_master_id'))
                                                    <span class="invalid-feedback invalid-select" role="alert">
                                                        <strong>{{ $errors->first('fees_master_id') }}</strong>
                                                    </span>
                                                @endif
                                                @else 
                                               {{-- {{$check_discount_apply->fees_group_id}} --}}
                                                    <select class="niceSelect w-100  form-control{{ $errors->has('fees_master_id') ? ' is-invalid' : '' }} select_fees_master" name="fees_master_id[]" id="fees_master{{$student->id}}">
                                                        {{-- <option data-display="@lang('lang.select') @lang('lang.fees_group')" value="">@lang('lang.select') @lang('lang.fees_group')</option> --}}
                                                        
                                                       
                                                            <option value="{{@$check_discount_apply->fees_group_id}}" checked >{{@$check_discount_apply->fees_group_name}} </option>
                                                    </select>
                                                       {{-- {{@$check_discount_apply->fees_group_name}} --}}
                                                @endif

                                            @endif
                                            
          
                                        </td>
                                        
                                        <td>{{$student->parents!=""?$student->parents->fathers_name:""}}</td>
                                        <td>{{$student->category!=""?$student->category->category_name:""}}</td>
                                        <td>{{$student->gender!=""?$student->gender->base_setup_name:""}}</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                                @if($students->count() > 0)
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center">
                                            <button type="button" class="primary-btn fix-gr-bg mb-0" id="btn-assign-fees-discount">
                                                <span class="ti-save pr"></span>
                                                @lang('lang.assign')  @lang('lang.discount')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                       
                            </table>
                        </div>

                    </div>
                </div>
            </div>
    {{ Form::close() }}
    @endif

    </div>
</section>


@endsection
