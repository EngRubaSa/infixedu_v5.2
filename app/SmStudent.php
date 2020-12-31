<?php

namespace App;

use App\SmStudentGroup;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\FeesCollection\Entities\InfixAssignDiscount;
use Modules\FeesCollection\Entities\InfixFeesMaster;
use Modules\FeesCollection\Entities\InfixFeesPayment;

class SmStudent extends Model
{
    protected $fillable = [];
    // protected $appends =['date_of_birth'];

    // public function getbirthdateFormatAttribute()
    // {
    //     return Carbon::parse($this->date_of_birth)->isoformat('Do MMMM Y');
    // }

    public function parents()
    {
        return $this->belongsTo('App\SmParent', 'parent_id', 'id');
    }

    public function drivers()
    {
        return $this->belongsTo('App\SmStaff', 'driver_id', 'id');
    }

    public function className()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

    public function feesPayment()
    {
        return $this->hasMany(SmFeesPayment::class, 'student_id');
    }

    public function gender()
    {
        return $this->belongsTo('App\SmBaseSetup', 'gender_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo('App\SmSchool', 'school_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo('App\SmBaseSetup', 'religion_id', 'id');
    }

    public function bloodGroup()
    {
        return $this->belongsTo('App\SmBaseSetup', 'bloodgroup_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\SmStudentCategory', 'student_category_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\SmStudentGroup', 'student_group_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\SmSession', 'session_id', 'id');
    }

    public function academicYear()
    {
        return $this->belongsTo('App\SmAcademicYear', 'academic_id', 'id');
    }

    //student class name
    public function class()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }

    public function route()
    {
        return $this->belongsTo('App\SmRoute', 'route_list_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\SmVehicle', 'vechile_id', 'id');
    }

    public function dormitory()
    {
        return $this->belongsTo('App\SmDormitoryList', 'dormitory_id', 'id');
    }


    public function sections()
    {
        return $this->hasManyThrough('App\SmSection', 'App\SmClassSection', 'class_id', 'id', 'class_id', 'section_id');
    }

    public function rooms()
    {
        return $this->hasMany('App\SmRoomList', 'dormitory_id', 'dormitory_id');
    }

    public function room()
    {
        return $this->belongsTo('App\SmRoomList', 'room_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(SmStudentAttendance::class, 'student_id')->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId());
    }

    function forwardBalance()
    {
        return $this->belongsTo('App\SmFeesCarryForward', 'id', 'student_id');
    }

    public function meritList()
    {
        return $this->belongsTo('App\SmTemporaryMeritlist', 'id', 'student_id');
    }

    public function feesAssign()
    {
        return $this->hasMany('App\SmFeesAssign', 'student_id', 'id');
    }

    public function getClass()
    {
        return $this->belongsTo(CheckClass::class, 'class_id');
    }

    public function getAttendanceType($month)
    {
        return $this->attendances()->whereMonth('attendance_date', $month)->get();
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

  

    public function assignDiscount()
    {
        return $this->hasMany(InfixAssignDiscount::class, 'student_id');
    }

    public function feesMasters()
    {
        return $this->hasMany(InfixFeesMaster::class, 'class_id', 'class_id');
    }

    public function markStores()
    {
        return $this->hasMany(SmMarkStore::class, 'student_id')->where('class_id', $this->class_id)
            ->where('section_id', $this->section_id);
    }

    public function assignSubjects()
    {
        return $this->hasMany(SmAssignSubject::class, 'class_id', 'class_id')->where('section_id', $this->section_id)->where('active_status', 1);
    }

    public function studentOnlineExams()
    {
        return $this->hasMany(SmOnlineExam::class, 'class_id', 'class_id')->where('section_id', $this->section_id)
            ->where('active_status', 1)->where('status', 1)->where('school_id', Auth::user()->school_id);
    }

    public function scheduleBySubjects()
    {
        return $this->hasMany(SmExamSchedule::class, 'class_id', 'class_id')->where('section_id', $this->section_id);
    }

    public function assignSubject()
    {
        return $this->hasMany(SmAssignSubject::class, 'class_id', 'class_id')->where('section_id', $this->section_id)->distinct('teacher_id');
    }

    public function bookIssue()
    {
        return $this->hasMany(SmBookIssue::class, 'member_id', 'user_id')->where('issue_status', 'I');
    }

    public function examSchedule()
    {
        return $this->hasMany(SmExamSchedule::class, 'class_id', 'class_id')->where('section_id', $this->section_id);
    }

    public function homework()
    {
        return $this->hasMany(SmHomework::class, 'class_id', 'class_id')->where('section_id', $this->section_id)
            ->where('evaluation_date', '=', null)->where('submission_date', '>', date('H:i:s'));
    }

    public function studentAttendances()
    {
        return $this->hasMany(SmStudentAttendance::class, 'student_id')->where('attendance_date', 'like', date('Y') . '-' . date('m') . '%')
            ->where('attendance_type', 'P');
    }

    public function studentOnlineExam()
    {
        return $this->hasMany(SmStudentTakeOnlineExam::class, 'student_id');
    }

    public function examsSchedule()
    {
        return $this->hasMany(SmExamSchedule::class, 'class_id', 'class_id')->where('section_id', $this->section_id);
    }
    public function homeworkContents()
    {
        return $this->hasMany(SmUploadHomeworkContent::class, 'student_id');
    }

    public function bankSlips()
    {
        return $this->hasMany(SmBankPaymentSlip::class, 'student_id');
    }

    public static function totalFees($feesAssigns)
    {

        try {
            $amount = 0;
            foreach ($feesAssigns as $feesAssign) {
                $master = SmFeesMaster::select('fees_group_id', 'amount')->where('id', $feesAssign->fees_master_id)->first();
                $amount += $master->amount;
            }
            return $amount;
        } catch (\Exception $e) {
            $data = [];
            $data[0] = $e->getMessage();
            return $data;
        }
    }

    public function getTotalAmount()
    {
        $amount = 0;
        foreach ($this->feesAssign as $feesAssign) {
            $amount += $feesAssign->feesGroupMaster->amount;
        }
        return $amount;
    }

    public function getTotalDiscount($id)
    {
        $amount = 0;
        foreach ($this->feesAssign as $feesAssign) {
            $amount += SmFeesAssign::where('fees_type_id', $feesAssign->feesGroupMaster->fees_type_id)->where('student_id', $id)->sum('discount_amount');
        }
        return $amount;
    }

    public function getTotalFine($id)
    {
        $amount = 0;
        foreach ($this->feesAssign as $feesAssign) {
            $amount += SmFeesPayment::where('fees_type_id', $feesAssign->feesGroupMaster->fees_type_id)->where('student_id', $id)->sum('fine');
        }
        return $amount;
    }

    public function getTotalDeposit($id)
    {
        $amount = 0;
        foreach ($this->feesAssign as $feesAssign) {
            $amount += SmFeesPayment::where('fees_type_id', $feesAssign->feesGroupMaster->fees_type_id)->where('student_id', $id)->sum('amount');
        }
        return $amount;
    }

    public static function totalDeposit($feesAssigns, $student_id)
    {

        try {
            $amount = 0;
            foreach ($feesAssigns as $feesAssign) {
                $fees_type = SmFeesMaster::select('fees_type_id')->where('id', $feesAssign->fees_master_id)->first();
                $amount += SmFeesPayment::where('fees_type_id', $fees_type->fees_type_id)->where('student_id', $student_id)->sum('amount');
            }
            return $amount;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function totalDiscount($feesAssigns, $student_id)
    {

        try {
            $amount = 0;
            foreach ($feesAssigns as $feesAssign) {
                $amount = SmFeesAssign::where('student_id', $student_id)->sum('applied_discount');
            }
            return $amount;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function totalFine($feesAssigns, $student_id)
    {

        try {
            $amount = 0;
            foreach ($feesAssigns as $feesAssign) {
                $fees_type = SmFeesMaster::select('fees_type_id')->where('id', $feesAssign->fees_master_id)->first();
                $amount += SmFeesPayment::where('fees_type_id', $fees_type->fees_type_id)->where('student_id', $student_id)->sum('fine');
            }
            return $amount;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function marks($exam_id, $s_id)
    {

        try {
            $marks_register = SmMarksRegister::where('exam_id', $exam_id)->where('student_id', $s_id)->first();
            $marks_register_clilds = [];
            if ($marks_register != "") {
                $marks_register_clilds = SmMarksRegisterChild::where('marks_register_id', $marks_register->id)->where('active_status', 1)->get();
            }
            return $marks_register_clilds;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function fullMarks($exam_id, $sb_id)
    {
        try {
            return SmExamScheduleSubject::where('exam_schedule_id', $exam_id)->where('subject_id', $sb_id)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function fullMarksBySubject($exam_id, $sb_id)
    {
        try {
            return SmExamSetup::where('exam_term_id', $exam_id)->where('subject_id', $sb_id)->first();
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function scheduleBySubject($exam_id, $sb_id, $student_detail)
    {
        try {
            $schedule = SmExamSchedule::where('exam_term_id', $exam_id)->where('subject_id', $sb_id)->where('class_id', $student_detail->class_id)
                ->where('section_id', $student_detail->section_id)->first();
            return $schedule;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public function promotion()
    {
        return $this->hasMany('App\SmStudentPromotion', 'student_id', 'id');
    }

    public function feesPayments()
    {
        return $this->hasMany(InfixFeesPayment::class, 'student_id');
    }

    public function getClassesAttribute()
    {
        $classes = '';
        if (count($this->promotion) > 0) {
            $maxClass = $this->promotion->max('current_class_id');
            $minClass = $this->promotion->min('previous_class_id');
            $classes = $minClass . ' - ' . $maxClass;
        } else
            $classes = $this->class->class_name . ' - ' . $this->class->class_name;

        return $classes;
    }

    public function getSessionsAttribute()
    {
        $sessions = '';
        if (count($this->promotion) > 0) {
            $maxSession = $this->promotion->max('current_session_id');
            $minSession = $this->promotion->min('previous_session_id');
            $sessions = $maxSession . ' - ' . $minSession;
        } else
            @$sessions = $this->session->session . ' - ' . $this->session->session;

        return $sessions;
    }

    public static function classPromote($class)
    {
        try {
            $class = SmClass::where('id', $class)->first();
            return $class->class_name;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function sessionPromote($session)
    {

        try {
            $session = SmSession::where('id', $session)->first();
            return $session->session;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }


    public static function getExamResult($exam_id, $student)
    {
        $eligible_subjects = SmAssignSubject::where('class_id', $student->class_id)->where('section_id', $student->section_id)->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)->get();

        foreach ($eligible_subjects as $subject) {

            $getMark = SmResultStore::where([
                ['exam_type_id', $exam_id],
                ['class_id', $student->class_id],
                ['section_id', $student->section_id],
                ['student_id', $student->id],
                ['subject_id', $subject->subject_id]
            ])->first();

            if ($getMark == "") {
                return false;
            }


            $result = SmResultStore::where([
                ['exam_type_id', $exam_id],
                ['class_id', $student->class_id],
                ['section_id', $student->section_id],
                ['student_id', $student->id]
            ])->get();

            return $result;
        }
    }

    public function examAttendances()
    {
        return $this->hasMany(SmExamAttendanceChild::class, 'student_id');
    }

    public function homeworks()
    {
        return $this->hasMany(SmHomeworkStudent::class, 'student_id');
    }

    public function onlineExams()
    {
        return $this->hasMany(SmStudentTakeOnlineExam::class, 'student_id');
    }

    public function subjectAssign()
    {
        return $this->hasOne(SmOptionalSubjectAssign::class, 'student_id')->where('session_id', $this->session_id)->where('academic_id', getAcademicId());
    }

}