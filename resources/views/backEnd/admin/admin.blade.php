@extends('backEnd.master')
@section('mainContent')
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3>@if(isset($section)) 
                                    @lang('lang.edit')
                                @else 
                                    @lang('lang.add')
                                @endif 
                                @lang('lang.section')
                            </h3>
                        </div>
                        @if(isset($section))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'section_update', 'method' => 'POST']) }}
                        @else
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'section_store', 'method' => 'POST']) }}
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row  mt-25">
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
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($section)? $section->section_name: ''}}">
                                            <input type="hidden" name="id" value="{{isset($section)? $section->id: ''}}">
                                            <label>@lang('lang.name') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg">
                                            <span class="ti-check"></span>
                                            @lang('lang.save') @lang('lang.content')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3>@lang('lang.section') @lang('lang.list')</h3>
                        </div>
                    </div>

                    <div class="offset-lg-4 col-md-4 d-flex justify-content-end">
                        <select class="niceSelect tr-bg mr-10">
                            <option data-display="Column View">@lang('lang.column_view')</option>
                            <option value="Name">@lang('lang.name')</option>
                            <option value="Position">@lang('lang.phone')</option>
                            <option value="Source">@lang('lang.source')</option>
                            <option value="Query Date">@lang('lang.source')</option>
                            <option value="Last Follow Up Date">@lang('lang.last_follow_up_date')</option>
                            <option value="Next Follow Up Date">@lang('lang.next_follow_up_date')</option>
                            <option value="Status">@lang('lang.Status')</option>
                            <option value="Action">@lang('lang.action')</option>
                            <option value="Restore Visibility">@lang('lang.restore') @lang('lang.visibility')</option>
                        </select>
                        <select class="niceSelect tr-bg">
                            <option data-display="Actions">@lang('lang.action')</option>
                            <option value="1">@lang('lang.print')</option>
                            <option value="2">@lang('lang.export_to_csv')</option>
                            <option value="3">@lang('lang.export_to_excel')</option>
                            <option value="4">@lang('lang.export_to_pdf')</option>
                            <option value="5">@lang('lang.copy_table')</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        
                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                               @if(session()->has('message-success-delete') != " " ||
                                session()->get('message-danger-delete') != "")
                                <tr>
                                    <td colspan="3">
                                         @if(session()->has('message-success-delete'))
                                          <div class="alert alert-success">
                                              {{ session()->get('message-success-delete') }}
                                          </div>
                                        @elseif(session()->has('message-danger-delete'))
                                          <div class="alert alert-danger">
                                              {{ session()->get('message-danger-delete') }}
                                          </div>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>@lang('lang.class')</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($sections as $section)
                                <tr>
                                    <td>{{@$section->section_name}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('lang.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('section_edit', [@$section->id])}}">@lang('lang.edit')</a>
                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteSectionModal{{@$section->id}}"  href="#">@lang('lang.delete')</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                 <div class="modal fade" id="deleteSectionModal{{@$section->id}}" role="dialog">
                                    <div class="modal-dialog">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content modalContent">
                                        <div class="modal-body removeBtn">
                                          <p>@lang('lang.are_you_sure_to_delete')?</p>
                                        </div>
                                        <div class="modal-footer compareFooter deleteButtonDiv">
                                            <button type="button" class="modalbtn btn-primary"><a href="{{route('section_delete', [@$section->id])}}" class="text-light">@lang('lang.yes')</a></button>
                                            <button type="button" class="modalbtn btn-danger" data-dismiss="modal">@lang('lang.no')</button>
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
