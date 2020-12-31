@extends('backEnd.master')
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('lang.student_book_issue')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('lang.dashboard')</a>
                <a href="#">@lang('lang.library')</a>
                <a href="#">@lang('lang.student_book_issue')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
<div class="row mt-40">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-0">@lang('lang.all_issued_book_list')</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>@lang('lang.book_title')</th>
                            <th>@lang('lang.book') @lang('lang.no')</th>
                            <th>@lang('lang.isbn') @lang('lang.no')</th>
                           {{-- <th>Member Name</th> --}}
                            <th>@lang('lang.author')</th>
                            <th>@lang('lang.subject')</th>
                            <th>@lang('lang.issue_date')</th>
                            <th>@lang('lang.return_date')</th>
                            <th>@lang('lang.status')</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issueBooks as $value)
                        <tr>
                           <td>{{@$value->books !=""?@$value->books->book_title:""}}</td>
                           <td>{{@$value->books !=""?@$value->books->book_number:""}}</td>
                           <td>{{@$value->books !=""?@$value->books->isbn_no:""}}</td>

                              <td>{{@$value->books !=""?@$value->books->author_name:""}}</td>
                              <td>{{$value->subject_name !=""?$value->subject_name:""}}</td>

                              <td  data-sort="{{strtotime(@$value->given_date)}}" >
                               {{@$value->given_date != ""? dateConvert(@$value->given_date):''}}

                              </td>
                              <td  data-sort="{{strtotime(@$value->due_date)}}" >
                               {{$value->due_date != ""? dateConvert(@$value->due_date):''}}

                              </td>
                              <td>
                              @if($value->issue_status == 'I')
                           
                               <button class="primary-btn small bg-success text-white border-0">Issued</button>
                              @endif
                              </td>
                          </tr>
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
