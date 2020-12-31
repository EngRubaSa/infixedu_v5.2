@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.backup_settings')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.system_settings')</a>
                <a href="{{route('sms-settings')}}">@lang('lang.backup_settings')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('lang.upload_from_local_directory')</h3>
                        </div>
                        {{-- @if(isset($sms_dbs))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'url' => 'session/'.@$session->id, 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else --}}
                            @if(userPermission(457))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'backup-store',
                            'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @endif
                        {{-- @endif --}}
                        <div class="white-box sm_mb_20 sm2_mb_20 md_mb_20 ">
                            <div class="add-visitor">

                                <div class="row no-gutters input-right-icon mb-20">
                                    <div class="col">
                                        <div class="input-effect">
                                            <input class="primary-input form-control {{ $errors->has('content_file') ? ' is-invalid' : '' }}" readonly="true" type="text"
                                            placeholder="{{isset($editData->file) && @$editData->file != ""? getFilePath3(@$editData->file):'Upload File'}} "  id="placeholderUploadContent" name="content_file">
                                            <span class="focus-border"></span>
                                            @if ($errors->has('content_file'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('content_file') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="primary-btn-small-input" type="button">
                                            <label class="primary-btn small fix-gr-bg" for="upload_content_file">@lang('lang.browse')</label>
                                            <input type="file" class="d-none form-control" name="content_file" id="upload_content_file">
                                        </button>

                                    </div>
                                </div>

                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">

                    {{-- DEMO LIVE --}}
                   {{--  <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo ">
                      <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" disabled> @lang('lang.save')</button>
                    </span> --}}
                                        @php 
                                            $tooltip = "";
                                            if(userPermission(457)){
                                                    $tooltip = "";
                                                }else{
                                                    $tooltip = "You have no permission to add";
                                                }
                                        @endphp
                                        <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{@$tooltip}}">
                                            <span class="ti-check"></span>
                                            @if(isset($sms_dbs))
                                                @lang('lang.update')
                                            @else
                                                @lang('lang.save')
                                            @endif
                                            @lang('lang.file')
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


                    <div class="col-lg-4  col-xl-3">
                        <div class="main-title mb-20" >
                            <h3 class="mb-0"> @lang('lang.database_backup_list')</h3>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-9 text-right col-md-12 mb-20  md_mb_20 title_custom_margin">
                    {{-- <div class="col-lg-12 col-xl-8 text-right col-md-12 mb-20 text_xs_left text_sm_left md_mb_20 title_custom_margin"> --}}


                    {{-- DEMO LIVE --}}
                   
                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                    <span class="d-inline-block mb-10" tabindex="0" data-toggle="tooltip" title="Disabled Images @lang('lang.backup')">
                            <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >@lang('lang.image') @lang('lang.backup')</button>
                    </span>
                              <span class="d-inline-block mb-10" tabindex="0" data-toggle="tooltip" title="Disabled Full Project @lang('lang.backup')">
                            <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >Full Project @lang('lang.backup')</button>
                          </span>
                          <span class="d-inline-block mb-10" tabindex="0" data-toggle="tooltip" title="Disabled Database @lang('lang.backup')">
                            <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" >Database @lang('lang.backup')</button>
                          </span>
                    @else
                        @if(userPermission(460))
                            <a href="{{route('get-backup-files',1)}}" class="primary-btn small fix-gr-bg  demo_view">
                                <span class="ti-arrow-circle-down pr-2"></span>
                                Images @lang('lang.backup')
                            </a>
                        @endif 
                        @if(userPermission(461))
                                <a href="{{route('get-backup-files',2)}}" class="primary-btn small fix-gr-bg  demo_view">
                                <span class="ti-arrow-circle-down pr-2"></span>
                                Full Project @lang('lang.backup')
                                </a>
                        @endif 
                        @if(userPermission(462))
                             <a href="{{route('get-backup-db')}}" class="primary-btn small fix-gr-bg demo_view"> <span class="ti-arrow-circle-down pr-2"></span> Database @lang('lang.backup') </a>
                        @endif 
                @endif 

                        

                    </div>
                    </div>
                <div class="row">
                    <div class="col-lg-12">

                        <table id="default_table" class="display school-table " cellspacing="0" width="100%">
                            <thead>


                                @if(session()->has('message-success') != "" ||
                                    session()->get('message-danger') != "")
                                    <tr>
                                        <td colspan="5">
                                            @if(session()->has('message-success'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('message-success') }}
                                                </div>
                                                @elseif(session()->has('message-danger'))
                                                    <div class="alert alert-danger">
                                                {{ session()->get('message-danger') }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <th>Size</th>
                                    <th>@lang('lang.created_date_time')</th>
                                    <th>@lang('lang.backup_files')</th>
                                    <th>File Type</th>
                                    <th>@lang('lang.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($sms_dbs as $sms_db)
                                <tr>
                                    <td>
                                        @php
                                        if(file_exists(@$sms_db->source_link)){
                                        @$size = filesize(@$sms_db->source_link);
                                            @$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
                                            @$power = @$size > 0 ? floor(log(@$size, 1024)) : 0;
                                            echo number_format(@$size / pow(1024, @$power), 2, '.', ',') . ' ' . @$units[@$power];
                                        }else{
                                            echo 'File already deleted.';
                                        }
                                        @endphp
                                    </td>
                                    <td> 
                                        {{@$sms_db->created_at != ""? dateConvert(@$sms_db->created_at):''}}

                                    </td>
                                    <td>{{@$sms_db->file_name}}</td>
                                    <td>
                                        @php
                                        if(@$sms_db->file_type == 0){
                                            echo 'Database';
                                        }else if(@$sms_db->file_type==1){
                                            echo 'Images';
                                        }else{
                                            echo 'Whole Project';
                                        }
                                        @endphp
                                    </td>
                                    <td>

                    {{-- DEMO LIVE --}}
                    {{-- <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled Download">
                      <button class="primary-btn small fix-gr-bg   demo_view" style="pointer-events: none;" type="button" disabled><span class="pl ti-download"></span> @lang('lang.download')</button>
                    </span>

                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled Restore">
                      <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" disabled><span class="pl ti-upload"></span>  @lang('lang.restore')</button>
                    </span>
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled Delete">
                      <button class="primary-btn small fix-gr-bg  demo_view" style="pointer-events: none;" type="button" disabled> <span class="pl ti-close"></span>  @lang('lang.delete')</button>
                    </span> --}}
                                    @if(userPermission(458))
                                        <a  class="primary-btn small tr-bg  " href="{{route('download-files',@$sms_db->id)}}"  >
                                            <span class="pl ti-download"></span> @lang('lang.download')
                                        </a>
                                    @endif
                                        @php
                                        if(@$sms_db->file_type == 10){
                                        @endphp 
                                           
                                            <a  class="primary-btn small tr-bg  " href="{{route('restore-database',@$sms_db->id)}}"  >
                                                <span class="pl ti-upload"></span>  @lang('lang.restore')
                                           </a>
                                        @php
                                        } 
                                        @endphp

                                        @if(userPermission(459))
                                       <a data-target="#deleteDatabase{{@$sms_db->id}}" data-toggle="modal" class="primary-btn small tr-bg  " href="{{url('/'.@$sms_db->id)}}"  >
                                            <span class="pl ti-close"></span>  @lang('lang.delete')
                                        </a>
                                        @endif
                                    </td>
                                </tr>



                                  <div class="modal fade admin-query" id="deleteDatabase{{@$sms_db->id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"> @lang('lang.delete')  @lang('lang.item')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4> @lang('lang.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal"> @lang('lang.cancel')</button>
                                                    <a href="{{route('delete_database', [@$sms_db->id])}}" class="text-light">
                                                    <button class="primary-btn fix-gr-bg" type="submit"> @lang('lang.delete')</button>
                                                     </a>
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
</section>

@endsection
