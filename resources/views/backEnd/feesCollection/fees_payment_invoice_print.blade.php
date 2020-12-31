<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <title>@lang('lang.student') @lang('lang.fees')</title>
  <style>
    *{
      margin: 0;
      padding: 0;
    }
    body{
      font-size: 12px;
      font-family: 'Poppins', sans-serif;
    }
    .student_marks_table{
      width: 95%;
      margin: 10px auto 0 auto;
    }
    .text_center{
      text-align: center;
    }
    p{
      margin: 0;
      font-size: 12px;
      text-transform: capitalize;
    }
    ul{
      margin: 0;
      padding: 0;
    }
    li{
      list-style: none;
    }
    td {
    border: 1px solid #726E6D;
    padding: .3rem;
    text-align: center;
  }
  th{
    border: 1px solid #726E6D;
    text-transform: capitalize;
    text-align: center;
    padding: .5rem;
  }
  thead{
    font-weight:bold;
    text-align:center;
    color: #222;
    font-size: 10px
  }
  .custom_table{
    width: 100%;
  }
  table.custom_table thead th {
    padding-right: 0;
    padding-left: 0;
  }
  table.custom_table thead tr > th {
    border: 0;
    padding: 0;
}
/* tr:last-child td {
    border: 0 !important;
}
tr:nth-last-child(2) td {
    border: 0 !important;
}
tr:nth-last-child(3) td {
    border: 0 !important;
} */

table.custom_table thead tr th .fees_title{
  font-size: 12px;
  font-weight: 600;
  border-top: 1px solid #726E6D;
  padding-top: 10px;
  margin-top: 10px;
}
.border-top{
  border-top: 0 !important;
}
  .custom_table th ul li {
    display: flex;
    justify-content: space-between;
  }
  .custom_table th ul li p {
    margin-bottom: 5px;
    font-weight: 500;
    font-size: 12px;
}
tbody td p{
  text-align: right;
}
tbody td{
  padding: 0.3rem;
}
table{
  border-spacing: 10px;
  width: 95%;
  margin: auto;
}
.fees_pay{
  text-align: center;
}
.border-0{
  border: 0 !important;
}
.copy_collect{
  text-align: center;
  font-weight: 500;
  color: #000;
}

.copyies_text{
  display: flex;
  justify-content: space-between;
  margin: 10px 0;
}
.copyies_text li{
  text-transform: capitalize;
  color: #000;
  font-weight: 500;
  border-top: 1px dashed #ddd;
}
.school_name{
  font-size: 14px;
  font-weight: 600;
  }

  .print_btn{
    float:right;
    padding: 20px;
    font-size: 12px;
  }
  .fees_book_title{
    display: inline-block;
    width: 100%;
    text-align: center;
    font-size: 12px;
    margin-top: 5px;
    border-top: 1px solid #ddd;
    padding: 5px;
  }

.footer{
  width: 95%;
  margin: auto;
  display: flex;
  justify-content: space-between;
  position: fixed;
  bottom: 30px;
  margin: auto;
  left: 0;
  right: 0;
}
.footer .footer_widget{
  width: 30%;
}
.footer .footer_widget .copyies_text{
  justify-content: space-between;
}

  </style>
<style type="text/css" media="print">
    @page { size: A4 landscape; }
  </style>
  </head>
  <script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome)
        {
          //  window.print();
          //  setTimeout(function(){window.close();}, 10000);
           //give them 10 seconds to print, then close
        }
        else
        {
           window.print();
          //  window.close();
        }
        </script>
  <body onLoad="loadHandler();">
        @php  $setting = generalSetting();@endphp
        <div class="student_marks_table print" >
      <table class="custom_table">
        <thead>
          <tr>
            <!-- first header  -->
            <th colspan="2">
                  <div style="float:left; width:30%">
                        <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                </div>
                <div style="float:right; width:70%; text-aligh:left">
                        <h4 class="school_name">{{$setting->school_name}}</h4>
                        <p>{{$setting->address}}</p>
                </div>

                <h4 class="fees_book_title" style="display:inline-block"></h4>

                <ul>
                <li><p>Admission No: {{@$student->admission_no}}</p> <p>date: {{date('d/m/Y')}}</p></li>
                    <li><p>Student Name: {{@$student->full_name}} </p></li>
                    <li><p>Class: {{@$student->class->class_name}}</p> <p>Roll:{{@$student->roll_no}}</p></li>
                    <li><p>Section: {{@$student->section->section_name}}</p> <p>Version: ___</p></li>
                    <li><p>Group: ___</p> <p>Shift: ___</p></li>
                </ul>
            </th>
            <!-- space  -->
            <th class="border-0" rowspan="9"></th>

            <!-- 2nd header  -->
            <th colspan="2">
                    <div style="float:left; width:30%">
                          <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                  </div>
                  <div style="float:right; width:70%; text-aligh:left">
                          <h4 class="school_name">{{$setting->school_name}}</h4>
                          <p>{{$setting->address}}</p>
                  </div>

                  <h4 class="fees_book_title" style="display:inline-block"></h4>

                  <ul>
                  <li><p>Admission No: {{@$student->admission_no}}</p> <p>date: {{date('d/m/Y')}}</p></li>
                      <li><p>Student Name: {{@$student->full_name}} </p></li>
                      <li><p>Class: {{@$student->class->class_name}}</p> <p>Roll:{{@$student->roll_no}}</p></li>
                      <li><p>Section: {{@$student->section->section_name}}</p> <p>Version: ___</p></li>
                      <li><p>Group: ___</p> <p>Shift: ___</p></li>
                  </ul>
              </th>
            <th class="border-0" rowspan="9"></th>

            <!-- 3rd header  -->
            <th colspan="2">
                    <div style="float:left; width:30%">
                          <img src="{{url($setting->logo)}}" style="width:100px; height:auto"   alt="">
                  </div>
                  <div style="float:right; width:70%; text-aligh:left">
                          <h4 class="school_name">{{$setting->school_name}}</h4>
                          <p>{{$setting->address}}</p>
                  </div>

                  <h4 class="fees_book_title" style="display:inline-block"></h4>

                  <ul>
                  <li><p>Admission No: {{@$student->admission_no}}</p> <p>date: {{date('d/m/Y')}}</p></li>
                      <li><p>Student Name: {{@$student->full_name}} </p></li>
                      <li><p>Class: {{@$student->class->class_name}}</p> <p>Roll:{{@$student->roll_no}}</p></li>
                      <li><p>Section: {{@$student->section->section_name}}</p> <p>Version: ___</p></li>
                      <li><p>Group: ___</p> <p>Shift: ___</p></li>
                  </ul>
              </th>
          </tr>
        </thead>
        <tbody>
            <tr>
              <!-- first header  -->
                <th>fees Details</th>
                <th>taka</th>
                <!-- space  -->
            <th class="border-0" rowspan="{{4+count($fees_assigneds)}}" ></th>

                <!-- 2nd header  -->
                <th>fees Details</th>
                <th>taka</th>
                <th class="border-0" rowspan="{{4+count($fees_assigneds)}}" ></th>

                <!-- 3rd header  -->
                <th>fees Details</th>
                <th>taka</th>
            </tr>

            @php
            $grand_total = 0;
            $total_fine = 0;
            $total_discount = 0;
            $total_paid = 0;
            $total_grand_paid = 0;
            $total_balance = 0;
            $totalpayable=0;
        @endphp
        @foreach($fees_assigneds as $fees_assigned)
            @php $grand_total += $fees_assigned->feesGroupMaster->amount; @endphp

            @php
                $discount_amount = $fees_assigned->applied_discount;
                $total_discount += $discount_amount;
                $student_id = $fees_assigned->student_id;
            @endphp
            @php
               //Sum of total paid amount of single fees type
                $paid = \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id)->sum('amount');
                $total_grand_paid += $paid;
            @endphp
            @php
            //Sum of total fine for single fees type
                $fine = \App\SmFeesAssign::feesPayment($fees_assigned->feesGroupMaster->feesTypes->id,$fees_assigned->student_id)->sum('fine');
               
                $total_fine += $fine;
            @endphp

            @php
                $total_paid = $discount_amount + $paid;
            @endphp

          <tr>
                @php
                    
                    $assigned_main_fees=number_format((float)@$fees_assigned->feesGroupMaster->amount, 2, '.', '');
                    $p_amount= $assigned_main_fees-$paid+$fine-$discount_amount;

                    // $totalpayable+=number_format((float)@$fees_assigned->feesGroupMaster->amount, 2, '.', '');
                    $totalpayable+=$p_amount;

              @endphp
             <!-- first td wrap  -->
             {{-- @if ($p_amount>0) --}}
                <td class="border-top">
                  {{-- {{'fine: '.$fine}} --}}
                    <p>
                      {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}} 
                      [{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}]
                    </p>
                    @if ($discount_amount>0)
                      <p> <b>Discount(-)</b> </p>
                    @endif
                    @if ($fine>0)
                      <p> <b>Fine(+)</b> </p>
                    @endif
                    @if ($paid>0)
                      <p> <b>Paid(+)</b> </p>
                    @endif
                    
                    
                    <p> <b>Unpaid</b> </p>
                    </td>
                    <td class="border-top" style="text-align: right">
                    {{@$assigned_main_fees}}
                    @if ($discount_amount>0)
                      <br>
                      {{number_format($discount_amount, 2, '.', '')}}
                    @endif
                    @if ($fine>0)
                      <br>
                      {{number_format($fine, 2, '.', '')}}
                    @endif
                    @if ($paid>0)
                      <br>
                      {{number_format($paid, 2, '.', '')}}
                    @endif
                    <br>
                  {{number_format(@$p_amount, 2, '.', '')}}
              </td>
             {{-- @endif --}}
          

            <!-- 2nd td wrap  -->
            {{-- @if ($p_amount>0) --}}
            <td class="border-top">
              <p>
                {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}} 
                [{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}]
              </p>
              @if ($discount_amount>0)
                <p> <b>Discount(-)</b> </p>
              @endif
              @if ($fine>0)
                <p> <b>Fine(+)</b> </p>
              @endif
              @if ($paid>0)
                <p> <b>Paid(+)</b> </p>
              @endif
              
              
              <p> <b>Unpaid</b> </p>
              </td>
              <td class="border-top" style="text-align: right">
              {{@$assigned_main_fees}}
              @if ($discount_amount>0)
                <br>
                {{number_format($discount_amount, 2, '.', '')}}
              @endif
              @if ($fine>0)
                <br>
                {{number_format($fine, 2, '.', '')}}
              @endif
              @if ($paid>0)
                <br>
                {{number_format($paid, 2, '.', '')}}
              @endif
              <br>
            {{number_format(@$p_amount, 2, '.', '')}}
        </td>
         {{-- @endif --}}

            <!-- 3rd td wrap  -->
            {{-- @if ($p_amount>0) --}}
            <td class="border-top">
              <p>
                {{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesGroups->name:""}} 
                [{{$fees_assigned->feesGroupMaster!=""?$fees_assigned->feesGroupMaster->feesTypes->name:""}}]
              </p>
              @if ($discount_amount>0)
                <p> <b>Discount(-)</b> </p>
              @endif
              @if ($fine>0)
                <p> <b>Fine(+)</b> </p>
              @endif
              @if ($paid>0)
                <p> <b>Paid(+)</b> </p>
              @endif
              
              
              <p> <b>Unpaid</b> </p>
              </td>
              <td class="border-top" style="text-align: right">
              {{@$assigned_main_fees}}
              @if ($discount_amount>0)
                <br>
                {{number_format($discount_amount, 2, '.', '')}}
              @endif
              @if ($fine>0)
                <br>
                {{number_format($fine, 2, '.', '')}}
              @endif
              @if ($paid>0)
                <br>
                {{number_format($paid, 2, '.', '')}}
              @endif
              <br>
            {{number_format(@$p_amount, 2, '.', '')}}
        </td>
         {{-- @endif --}}
          </tr>

          @endforeach

          @php
          
              $totalpayable=$totalpayable-$unapplied_discount_amount;
              
              if ($totalpayable<0) {
                  $totalpayable=0.00;
              } else {
                $totalpayable=$totalpayable;
              }
              
              
              
          @endphp

          <tr>
            <!-- 1st td wrap  -->
            <td>
              {{-- <p>Discount Amount(-)</p> --}}
              <p><strong>total payable amount</strong></p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $totalpayable, 2, '.', '')}} </strong>
             </td>

            <!-- 2nd td wrap  -->
            <td>
              {{-- <p>Discount Amount(-)</p> --}}
              <p><strong>total payable amount</strong></p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $totalpayable, 2, '.', '')}} </strong>
             </td>
            <!-- 3rd td wrap  -->
            <td>
              {{-- <p>Discount Amount(-)</p> --}}
              <p><strong>total payable amount</strong></p>
            </td>
            <td style="text-align: right">
              {{-- {{ number_format((float) $unapplied_discount_amount, 2, '.', '')}}<br> --}}
              <strong> {{ number_format((float) $totalpayable, 2, '.', '')}} </strong>
             </td>
          </tr>

          <tr>
              </tr>

            <tr>

                <td colspan="2" >if unpaid, admission will be cancelled after</td>
                <!-- 2nd td wrap  -->
                <td colspan="2" >if unpaid, admission will be cancelled after</td>

                <!-- 3rd td wrap  -->
                <td colspan="2" >if unpaid, admission will be cancelled after</td>
            </tr>
            <tr>
                <td colspan="2"><p class="parents_num text_center"> Parents phone number : <span>{{@$parent->guardians_mobile}}</span> </p>
                   </td>
                    <!-- 2nd td wrap  -->

                <td class="border-0" ></td>

                <td colspan="2"><p class="parents_num text_center"> Parents phone number : <span>{{@$parent->guardians_mobile}}</span> </p>
                    </td>
                <td class="border-0"></td>

                    <!-- 2nd td wrap  -->
                <td colspan="2"><p class="parents_num text_center"> Parents phone number : <span>{{@$parent->guardians_mobile}}</span> </p>

                  </td>
            </tr>

        </tbody>
      </table>
    </div>
<footer class="footer" >
  <div class="footer_widget">
    <ul class="copyies_text">
      <li>parent/student</li>
      <li>casier</li>
      <li>officer</li>
    </ul>
    <p class="copy_collect">
      parent/student copy
    </p>
  </div>
  <div class="footer_widget">
      <ul class="copyies_text">
        <li>parent/student</li>
        <li>casier</li>
        <li>officer</li>
      </ul>
      <p class="copy_collect">
        parent/student copy
      </p>
    </div>
    <div class="footer_widget">
        <ul class="copyies_text">
          <li>parent/student</li>
          <li>casier</li>
          <li>officer</li>
        </ul>
        <p class="copy_collect">
          parent/student copy
        </p>
      </div>
</footer>

    <script>
            function printInvoice() {
              window.print();
            }
     </script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/jquery-3.2.1.slim.min.js"></script>
    <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/popper.min.js"></script>
    <script src="{{ asset('/') }}/public/backEnd/js/fees_invoice/bootstrap.min.js"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> --}}
  </body>
</html>
