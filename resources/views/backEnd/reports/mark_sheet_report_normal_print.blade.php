<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('/')}}/public/backEnd/css/report/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Mark Sheet Report</title>
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
      width: 65%;
      margin: 30px auto 0 auto;
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
    padding: .8rem;
    text-align: center;
  }
  th{
    border: 1px solid #726E6D;
    text-transform: capitalize;
    text-align: center;
    padding: 1rem;
    white-space: nowrap;
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
  }
  .custom_table th ul li p {
    margin-bottom: 10px;
    font-weight: 500;
    font-size: 14px;
}
/* tbody td p{
  text-align: right;
} */
tbody td{
  padding: 0.8rem;
}
table{
  border-spacing: 10px;
  width: 65%;
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
  margin: 30px 0;
}
.copyies_text li{
  text-transform: capitalize;
  color: #000;
  font-weight: 500;
  border-top: 1px dashed #ddd;
}
.text_left{
    text-align: left;
}
.italic_text{
}
.student_info{
    
}
.student_info li{
    display: flex;
}
.info_details{
    display: flex;
    flex-wrap: wrap;
    margin-top: 30px;
    margin-bottom: 30px;
}
.info_details li > p{
    flex-basis: 20%;
}
.info_details li{
    display: flex;
    flex-basis: 50%;
}
.school_name{
    text-align: center;
}
.numbered_table_row{
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    align-items: center;
}
.numbered_table_row thead{
    border: 1px solid #222
}
.numbered_table_row h3{
    font-size: 24px;
    text-transform: uppercase;
    margin-top: 15px;
    font-weight: 500;
    display: inline-block;
    border-bottom: 2px solid #222;
}
.numbered_table_row td{
   border: 1px solid #726E6D;
   padding: .4rem;
   font-weight: 400;
   color: #222;
}

table#grade_table th {
    border: 1px solid #726E6D !important;
    padding: .6rem;
    font-weight: 600;
    color: #222;
}
td.border-top.border_left_hide {
    border-left: 0;
    text-align: left;
    font-weight: 600;
}
.devide_td{
    padding: 0;
}
.devide_td p{
    border-bottom: 1px solid #222;
}
.ssc_text{
    font-size: 20px;
    font-weight: 500;
    color: #222;
    margin-bottom: 20px;
}
  </style>
@php 
$generalSetting= App\SmGeneralSettings::find(1);
if(!empty($generalSetting)){
    $school_name =$generalSetting->school_name;
    $site_title =$generalSetting->site_title;
    $school_code =$generalSetting->school_code;
    $address =$generalSetting->address;
    $phone =$generalSetting->phone;
    $email =$generalSetting->email;  
}

@endphp
  </head>
  <script>
        var is_chrome = function () { return Boolean(window.chrome); }
        if(is_chrome) 
        {
           window.print();
        //    setTimeout(function(){window.close();}, 10000); 
           //give them 10 seconds to print, then close
        }
        else
        {
           window.print();
        //    window.close();
        }
        </script>
    <body onLoad="loadHandler();">
    
    <div class="student_marks_table">
    <table class="custom_table">
        <thead>
            <tr>
                    <div class="school_name">
                            <h4>{{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h4>
                            <p>{{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}
                               
                    </div>
            </tr>
            <tr class="numbered_table_row" >
                <td class="border-0">

                </td>
                <td class="border-0" >
                    <div class="school_mark">
                        <p class="ssc_text" >{{$exam_details->title}} -  {{$class_name->class_name}}({{$section->section_name}})</p>
                        <div>
                                <img src=" {{asset('/')}}{{generalSetting()->logo }}" alt="">
                        </div>
                        <h3>academic transcript</h3>
                    </div>
                </td>
                <td class="border-0">
                        @php $marks_grade=DB::table('sm_marks_grades')->where('academic_id', getAcademicId())->get(); @endphp
                        @if(@$marks_grade)
                    <table class="table" id="grade_table">
                        <thead>
                            <tr>
                                <th>Staring</th>
                                <th>Ending</th>
                                <th>GPA</th>
                                <th>Grade</th>
                                <th>Evalution</th>
                            </tr>
                        </thead>
                        <tbody> 
                       @foreach($marks_grade as $grade_d)
                         <tr>
                            <td>{{$grade_d->percent_from}}</td>
                            <td>{{$grade_d->percent_upto}}</td>
                            <td>{{$grade_d->gpa}}</td>
                            <td>{{$grade_d->grade_name}}</td>
                            <td class="text-left">{{$grade_d->description}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </td>
            </tr>
            <tr>
                <!-- first header  -->
                <th colspan="1" class="text_left">


                    </p>
                    <ul class="student_info" >
                        <li><p>Name of Student &nbsp;  : &nbsp; </p> &nbsp; <p class="italic_text">   {{$student_detail->full_name}}</p></li>
                        <li><p>Father's Name &nbsp;  : &nbsp; </p> &nbsp; <p class="italic_text">  {{$student_detail->parents->fathers_name}} </p></li>
                        <li><p>Mother's Name &nbsp;  : &nbsp; </p> &nbsp; <p class="italic_text">  {{$student_detail->parents->mothers_name}} </p></li>
                        <li><p>Name of institution &nbsp;  : &nbsp; </p> &nbsp; <p class="italic_text">  {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </p></li>
                    </ul>
                    <ul class="info_details">
                        {{-- <li><p>Name of Centre &nbsp;  &nbsp; </p> &nbsp; <p class="italic_text">  (420) brammanbaria  </p></li> --}}
                        {{-- <li><p>Type  &nbsp;  &nbsp; </p> &nbsp; <p class="italic_text">  Regular  </p></li> --}}
                        <li><p>Roll No.   &nbsp; &nbsp; </p> &nbsp; <p>  <strong>{{$student_detail->roll_no}}</strong>  </p></li>
                        <li><p>Admission No.   &nbsp;  &nbsp; </p> &nbsp; <p>  <strong>{{$student_detail->admission_no}}</strong>  </p></li>
                        {{-- <li><p>Group  &nbsp;  &nbsp; </p> &nbsp; <p class="italic_text"> business seience</p></li> --}}
                        <li><p>Date of birth   &nbsp;  &nbsp; </p> &nbsp; <p> <strong>{{$student_detail->date_of_birth != ""? dateConvert($student_detail->date_of_birth):''}}</strong></p></li>
                    </ul> 
                </th>
            </tr>
        </thead>
    </table>
     
    <table class="custom_table">
        <thead>
       
        </thead>
        <tbody>

            <tr>
                <!-- first header  -->
                <th>SI.NO</th>
                <th colspan="2">Name of subjects</th>
                <th>letter grade</th>
                <th>Total Marks</th>
                <th>Grade Point</th>
                <th>GPA</th>

            </tr>
            @php
                $main_subject_total_gpa=0;
                  
                  $Optional_subject_count=$subjects->count();
            @endphp
            @php $sum_gpa= 0;  $resultCount=1; $subject_count=1; $tota_grade_point=0; $this_student_failed=0; $count=1; @endphp

            @foreach($is_result_available as $data)

                <tr>
                    <td class="border-top"
                        style="border-bottom: 1px solid black;">{{ $count }}</td>
                    <td colspan="2" class="border-top"
                        style="text-align: left;padding-left: 15px;border-bottom: 1px solid black;">
                        <p>{{$data->subject->subject_name}}</p></td>
                    <td class="border-top"
                        style="border-bottom: 1px solid black;">
                        <p>
                            @php
                                $result = markGpa($data->total_marks);
                            @endphp
                            {{@$result->grade_name}}
                        </p>
                        {{-- {{@$subject_result[2][0]}}  --}}
                    </td>
                    <td class="border-top"
                        style="border-bottom: 1px solid black;">
                        <p>


                            {{@$data->total_marks}}
                        </p>
                        {{-- {{@$subject_result[2][0]}}  --}}
                    </td>
                    @php
                        $main_subject_total_gpa += $result->gpa;
                    @endphp
                    <td class="border-top"
                        style="border-bottom: 1px solid black;"><p>
                            {{@$result->gpa}}

                        </p>
                    </td>

                    @if ($count==1)
                        <td rowspan="{{ $Optional_subject_count+1 }}"
                            class="border-top"
                            style="border-bottom: 1px solid black;">
                            <p id="main_subject_total_gpa"></p>
                        </td>

                    @endif
                    @php
                        $count++
                    @endphp

                </tr>
            @endforeach

        </tbody>
      </table>


      <script>
            function myFunction(value, subject) {
                if(value !=""){
                    var res =  Number( value/subject).toFixed(2) ; 
                }else{
                    var res = 0;
                } 
                  document.getElementById("main_subject_total_gpa").innerHTML = res; 
              }
    
           
              myFunction({{ $main_subject_total_gpa }}, {{ $Optional_subject_count }});
            
            
        </script>



      <table style="width:100%" class="border-0">
            <tbody><tr> 
                <td class="border-0"><p class="result-date" style="text-align:left; float:left; display:inline-block; margin-top:50px; padding-left: 0;">
                    @php
                    $data = App\SmMarkStore::select('created_at')->where([
                       ['student_id',$student_detail->id],
                       ['class_id',$class_id],
                       ['section_id',$section_id],
                       ['exam_term_id',$exam_type_id],
                   ])->first();

                   @endphp
                   @lang('lang.date_of_publication_of_result') : <b> {{date_format(date_create($data->created_at),"F j, Y, g:i a")}}</b></b>
</p></td>
                <td class="border-0"> 
                    <p style="text-align:right; float:right; border-top:1px solid #ddd; display:inline-block; margin-top:50px;">( Exam Controller )</p> 
                </td>
            </tr>
    
        </tbody></table>
</div>




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