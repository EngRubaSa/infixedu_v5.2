<script src="{{asset('public/backEnd/')}}/js/main.js"></script>

<div class="container-fluid mt-30">
    <div class="student-details">
        <div class="student-meta-box">
            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                       <div class="col-lg-12">
                           <div class="row">

                            <h4 class="stu-sub-head">@lang('lang.home_work') @lang('lang.summary')</h4>

                        </div>
                    </div> 

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.homework_date')
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))        
                                        {{@$homeworkDetails->homework_date != ""? dateConvert(@$homeworkDetails->homework_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.price')Submission Date
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(isset($homeworkDetails))            
                                        {{@$homeworkDetails->submission_date != ""? dateConvert(@$homeworkDetails->submission_date):''}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="value text-left">
                                    @lang('lang.evaluation_date') 
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="name">
                                    @if(@$homeworkDetails->evaluation_date != "")     
                                        {{@$homeworkDetails->evaluation_date != ""? dateConvert(@$homeworkDetails->evaluation_date):''}}
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
                                   @if(isset($homeworkDetails))
                                   {{@$homeworkDetails->users->full_name}}
                                   @endif
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('lang.evaluated_by')
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                                @if(isset($homeworkDetails))
                                {{@$homeworkDetails->users->full_name}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="value text-left">
                                @lang('lang.class') 
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="name">
                               @if(isset($homeworkDetails))
                               {{@$homeworkDetails->classes->class_name}}
                               @endif
                           </div>
                       </div>
                   </div>
               </div>

               <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('lang.section')
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{@$homeworkDetails->sections->section_name}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('lang.subject') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            @if(isset($homeworkDetails))
                            {{@$homeworkDetails->subjects->subject_name}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('lang.marks') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                            
                            {{@$homeworkDetails->marks}}
                           
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-meta">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="value text-left">
                            @lang('lang.attach_file') 
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="name">
                          
                            @if(@$homeworkDetails->file != "")
                           
                             <a href="{{route('evaluation-document',getFilePath3(@$homeworkDetails->file))}}">
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
                            @if(isset($homeworkDetails))
                            {{@$homeworkDetails->description}}
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
<script type="text/javascript">
    $('.school-table-data').DataTable({
        bLengthChange: false,
        language: {
            search: "<i class='ti-search'></i>",
            searchPlaceholder: 'Quick Search',
            paginate: {
                next: "<i class='ti-arrow-right'></i>",
                previous: "<i class='ti-arrow-left'></i>"
            }
        },
        buttons: [ ],
        columnDefs: [
        {
            visible: false
        }
        ],
        responsive: true
    });

    // for evaluation date

    $('#evaluation_date_icon').on('click', function() {
        $('#evaluation_date').focus();
    });

    $('.primary-input.date').datepicker({
        autoclose: true
    });

    $('.primary-input.date').on('changeDate', function(ev) {
        $(this).focus();
    });

</script>
