<script src="{{asset('public/backEnd/')}}/js/main.js"></script>

<div class="container-fluid mt-30">
    <div class="student-details">
        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-12 col-md-12">

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.content_title')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($ContentDetails))        
                                        {{@$ContentDetails->content_title != ""? $ContentDetails->content_title:''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.content_type')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($ContentDetails))
                                        @if ($ContentDetails->content_type == "sy")
                                            @lang('lang.syllabus')
                                        @elseif($ContentDetails->content_type == "as")
                                            @lang('lang.assignment')
                                        @else
                                            @lang('lang.other_downloads')
                                        @endif        
                                        
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.upload') @lang('lang.date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($ContentDetails))            
                                        {{@$ContentDetails->upload_date != ""? dateConvert(@$ContentDetails->upload_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.created_by')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                   @if(isset($ContentDetails))
                                   {{@$ContentDetails->users->full_name}}
                                   @endif
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('lang.available_for')
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($ContentDetails))
                                    @if ($ContentDetails->available_for_admin == 1)
                                        <p>@lang('lang.all_admins')</p>
                                    @endif
                                    @if ($ContentDetails->available_for_all_classes == 1)
                                        <p>@lang('lang.all_classes')</p>
                                    @endif
                                    @if ($ContentDetails->class != "")
                                        <p>@lang('lang.class'): {{$ContentDetails->classes->class_name}}</p>
                                    @endif
                                    @if ($ContentDetails->section != "")
                                        <p>@lang('lang.section'): {{$ContentDetails->sections->section_name}}</p>
                                    @endif

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(@$ContentDetails->source_url != "")
                <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('lang.source_url') 
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($ContentDetails))
                                    @if(@$ContentDetails->source_url != "")
                                        <a class="primary-btn small fix-gr-bg" target="_blank" href="{{@$ContentDetails->source_url}}">@lang('lang.click_here')</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('lang.attach_file') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                          
                            @if(@$ContentDetails->upload_file != "")
                           
                             <a href="{{route('download-content-document',getFilePath3(@$ContentDetails->upload_file))}}">
                                @lang('lang.download')  <span class="pl ti-download"></span>
                             </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('lang.description')  
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($ContentDetails))
                            {{@$ContentDetails->description}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

