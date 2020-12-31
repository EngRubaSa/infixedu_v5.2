  @if(userPermission(56))
             <li>
                <a href="{{route('parent-dashboard')}}">
                    <span class="flaticon-resume"></span>
                    @lang('lang.dashboard')
                </a>
            </li>
            @endif
            @if(userPermission(66))
                <li>
                    <a href="#subMenuParentMyChildren" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-reading"></span>
                        @lang('lang.my_children')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentMyChildren">
                        

                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('my_children', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(71))
                <li>
                    <a href="#subMenuParentFees" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-wallet"></span>
                        @lang('lang.fees')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentFees">
                        @foreach($childrens as $children)
                        @if(moduleStatusCheck('FeesCollection')== false )
                            <li>
                                <a href="{{route('parent_fees', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @else
                            <li>
                                <a href="{{route('feescollection/parent-fee-payment', [$children->id])}}">{{$children->full_name}}</a>
                            </li>

                        @endif
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(72))
                <li>
                    <a href="#subMenuParentClassRoutine" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle">
                        <span class="flaticon-calendar-1"></span>
                        @lang('lang.class_routine')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentClassRoutine">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_class_routine', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(73))
                <li>
                    <a href="#subMenuParentHomework" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-book"></span>
                        @lang('lang.home_work')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentHomework">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_homework', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(75))
                <li>
                    <a href="#subMenuParentAttendance" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-authentication"></span>
                        @lang('lang.attendance')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentAttendance">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_attendance', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(76))
                <li>
                    <a href="#subMenuParentExamination" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle">
                        <span class="flaticon-test"></span>
                        @lang('lang.exam')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentExamination">
                        @foreach($childrens as $children)
                            @if(userPermission(77))
                                <li>
                                    <a href="{{route('parent_examination', [$children->id])}}">{{$children->full_name}}</a>
                                </li>
                            @endif
                            @if(userPermission(78))
                                <li>
                                    <a href="{{route('parent_exam_schedule', [$children->id])}}">@lang('lang.exam_schedule')</a>
                                </li>
                            @endif
                            @if(userPermission(79))
                                <li>
                                    <a href="{{ route('parent_online_examination', [$children->id])}}">@lang('lang.online_exam')</a>
                                </li>
                            @endif
                            <hr>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(80))
                <li>
                    <a href="#subMenuParentLeave" data-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle">
                        <span class="flaticon-test"></span>
                        @lang('lang.leave')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentLeave">
                        @if(userPermission(81))
                            <li>
                                <a href="{{route('parent-apply-leave')}}">@lang('lang.apply_leave')</a>
                            </li>
                        @endif
                        @if(userPermission(82))
                            <li>
                                <a href="{{route('parent-pending-leave')}}">@lang('lang.pending_leave_request')</a>
                            </li>
                        @endif
                        <hr>
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_leave', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        <hr>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(85))
                <li>
                    <a href="{{route('parent_noticeboard')}}">
                        <span class="flaticon-poster"></span>
                        @lang('lang.notice_board')
                    </a>
                </li>
            @endif
            @if(userPermission(86))
                <li>
                    <a href="#subMenuParentSubject" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-reading-1"></span>
                        @lang('lang.subjects')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentSubject">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_subjects', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(87))
                <li>
                    <a href="#subMenuParentTeacher" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-professor"></span>
                        @lang('lang.teacher_list')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentTeacher">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_teacher_list', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(88))
                <li>
                    <a href="#subMenuStudentLibrary" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"
                    href="#">
                        <span class="flaticon-book-1"></span>
                        @lang('lang.library')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuStudentLibrary">
                        @if(userPermission(89))
                            <li>
                                <a href="{{route('parent_library')}}"> @lang('lang.book_list')</a>
                            </li>
                        @endif
                        @if(userPermission(90))
                            <li>
                                <a href="{{route('parent_book_issue')}}">@lang('lang.book_issue')</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(userPermission(91))
                <li>
                    <a href="#subMenuParentTransport" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-bus"></span>
                        @lang('lang.transport')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentTransport">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_transport', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if(userPermission(92))
                <li>
                    <a href="#subMenuParentDormitory" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="flaticon-hotel"></span>
                        @lang('lang.dormitory_list')
                    </a>
                    <ul class="collapse list-unstyled" id="subMenuParentDormitory">
                        @foreach($childrens as $children)
                            <li>
                                <a href="{{route('parent_dormitory_list', [$children->id])}}">{{$children->full_name}}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
