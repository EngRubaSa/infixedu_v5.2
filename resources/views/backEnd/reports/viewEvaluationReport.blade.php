

<div class="container-fluid mt-30">
    <div class="student-details">
        <div class="student-meta-box">
            <div class="single-meta">

                <div class="row">
                    <div class="col-lg-8 col-md-8">
                        <table id="" class="school-table-data school-table shadow-none" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('lang.admission_no')</th>
                                    <th>@lang('lang.student_name')</th>
                                    <th>@lang('lang.marks')</th>
                                    <th>@lang('lang.comments')</th>
                                    <th>@lang('lang.home_work') @lang('lang.status')</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($homework_students as $value)
                                <tr>
                                    <td width="8%">{{$value->studentInfo!=""?$value->studentInfo->admission_no:""}}</td>
                                    <td width="15%">{{$value->studentInfo!=""?$value->studentInfo->full_name:""}}</td>
                                    <td width="15%">{{$value->marks}}</td>

                                    <td width="15%">
                                        @if($value->teacher_comments == 'G')
                                        <a class=""><button class="primary-btn small fix-gr-bg"> @lang('lang.good') </button></a>
                                        @else
                                        <a class=""><button class="primary-btn small tr-bg">@lang('lang.not') @lang('lang.good') </button></a>
                                        @endif
                                    </td>

                                    <td width="15%">
                                        @if($value->complete_status == 'C')
                                        <a class=""><button class="primary-btn small fix-gr-bg"> @lang('lang.complete') </button></a>
                                        @else
                                        <a class=""><button class="primary-btn small tr-bg"> @lang('lang.incomplete') </button></a>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4">
                        <h4 class="stu-sub-head">@lang('lang.summery')</h4>
                        <div class="student-meta-box">

                            <div class="single-meta">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="name">
                                            @lang('lang.home_work') @lang('lang.date')
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="value text-left">
                                         @if(isset($homeworkDetails))
                                                                                    
                                            {{$homeworkDetails->homework_date != ""? dateConvert($homeworkDetails->homework_date):''}}

                                         @endif
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="single-meta">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="name">
                                        @lang('lang.submission') @lang('lang.date')
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="value text-left">
                                     @if(isset($homeworkDetails))                                   
                                                                            
                                        {{$homeworkDetails->submission_date != ""? dateConvert($homeworkDetails->submission_date):''}}

                                     @endif
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="value text-left">
                                    @lang('lang.evaluation') @lang('lang.date') 
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="name">
                                    @if($homeworkDetails->evaluation_date != "")                                  
                                                                        
                                    {{$homeworkDetails->evaluation_date != ""? dateConvert($homeworkDetails->evaluation_date):''}}

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="single-meta">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="name">
                                        @lang('lang.evaluation') @lang('lang.date')
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="value text-left">
                                     @if(isset($homeworkDetails))                                   
                                                                            
                                        {{$homeworkDetails->evaluation_date != ""? dateConvert($homeworkDetails->evaluation_date):''}}

                                     @endif
                                 </div>
                             </div>
                         </div>
                     </div>


                     <div class="single-meta">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="name">
                                    @lang('lang.created_by')
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="value text-left">
                                 @if(isset($homeworkDetails))
                                 {{$homeworkDetails->users->full_name}}
                                 @endif
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="name">
                                @lang('lang.evaluated_by')
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="value text-left">
                                @if(isset($homeworkDetails))
                                {{$homeworkDetails->users->full_name}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="single-meta">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="name">
                                @lang('lang.class')
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="value text-left">
                             @if(isset($homeworkDetails))
                             {{$homeworkDetails->classes->class_name}}
                             @endif
                         </div>
                     </div>
                 </div>
             </div>

             <div class="single-meta">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="name">
                            @lang('lang.section')
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="value text-left">
                          @if(isset($homeworkDetails))
                          {{$homeworkDetails->sections->section_name}}
                          @endif
                      </div>
                  </div>
              </div>
          </div>

          <div class="single-meta">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="name">
                        @lang('lang.subjects')
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="value text-left">
                      @if(isset($homeworkDetails))
                      {{$homeworkDetails->subjects->subject_name}}
                      @endif
                  </div>
              </div>
          </div>
      </div>

      <div class="single-meta">
            <div class="row">
                <div class="col-lg-6">
                    <div class="value text-left">
                        @lang('lang.marks')
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="name">
                        
                        {{$homeworkDetails->marks}}
                       
                    </div>
                </div>
            </div>
        </div>

      <div class="single-meta">
          <div class="row">
              <div class="col-lg-6">
                  <div class="value text-left">
                      @lang('lang.attach_file')
                  </div>
              </div>
              <div class="col-lg-6">
                  <div class="name">
                      @if($homeworkDetails->file != "")

                       <a href="{{asset('/'.$homeworkDetails->file)}}" download>
                              @lang('lang.download') <span class="pl ti-download"></span></a>
                              
                       {{-- <a href="{{url('evaluation-document/'.getFilePath3($homeworkDetails->file))}}">
                              @lang('lang.download') <span class="pl ti-download"></span></a> --}}
                      @endif
                  </div>
              </div>
          </div>
      </div>

      <div class="single-meta">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="name">
                    @lang('lang.description')
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="value text-left">
                  @if(isset($homeworkDetails))
                  {{$homeworkDetails->description}}
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
