<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sms_templates');
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('admission_pro')->nullable();
            $table->longText('student_admit')->nullable();
            $table->longText('login_disable')->nullable();
            $table->longText('exam_schedule')->nullable();
            $table->longText('exam_publish')->nullable();
            $table->longText('due_fees')->nullable();
            $table->longText('collect_fees')->nullable();
            $table->longText('stu_promote')->nullable();
            $table->longText('attendance_sms')->nullable();
            $table->longText('absent')->nullable();
            $table->longText('late_sms')->nullable();
            $table->longText('er_checkout')->nullable();
            $table->longText('st_checkout')->nullable();
            $table->longText('st_credentials')->nullable();
            $table->longText('staff_credentials')->nullable();
            $table->longText('holiday')->nullable();
            $table->longText('leave_app')->nullable();
            $table->longText('approve_sms')->nullable();
            $table->longText('birth_st')->nullable();
            $table->longText('birth_staff')->nullable();
            $table->longText('cheque_bounce')->nullable();
            $table->longText('l_issue_b')->nullable();
            $table->longText('re_issue_book')->nullable();
            $table->longText('sms_text')->nullable();

            //sms template new

            $table->longText('student_approve_message_sms')->nullable();
            $table->string('student_approve_message_sms_status')->default(1)->comment('1 enable, 3 disable');

            $table->longText('student_registration_message_sms')->nullable();
            $table->string('student_registration_message_sms_status')->default(1)->comment('1 enable, 3 disable');  


            $table->longText('student_admission_message_sms')->nullable();
            $table->string('student_admission_message_sms_status')->default(1)->comment('1 enable, 3 disable');


            $table->longText('exam_schedule_message_sms')->nullable();
            $table->string('exam_schedule_message_sms_status')->default(1)->comment('1 enable, 3 disable');


            $table->longText('dues_fees_message_sms')->nullable();
            $table->string('dues_fees_message_sms_status')->default(1)->comment('1 enable, 3 disable');



            //email template
            $table->longText('password_reset_message')->nullable();
            $table->longText('student_login_credential_message')->nullable();
            $table->longText('guardian_login_credential_message')->nullable();

            $table->longText('student_registration_message')->nullable();
            $table->longText('guardian_registration_message')->nullable();

            $table->longText('staff_login_credential_message')->nullable();
            $table->longText('send_email_message')->nullable();
            $table->longText('dues_payment_message')->nullable();

            $table->longText('email_footer_text')->nullable();

            $table->boolean('active_status')->default(1);
            $table->timestamps();
        });

        DB::table('sms_templates')->insert([
            [
                'admission_pro' => 'Dear parent |ParentName|, your child |StudentName| admission is in process.',
                'student_admit' => 'Dear parent |ParentName|, your child |StudentName| admission is completed You can login to your account using username:|Username| Password:|Password|',
                'login_disable' => 'hello world',
                'exam_schedule' => 'hello world',
                'exam_publish' => 'hello world',
                'due_fees' => 'Fee Due Reminder for your child |StudentName|. 
                                Dear Parent |ParentName|, please find the below fee summary.
                                Fee: Rs.|Fee|, Back dues 
                                Adjustment: Rs.|Adjustment|, 
                                Total: Rs.|Total|, 
                                Paid: Rs.|Paid|, 
                                Balance: Rs.|Balance|. 
                                Please ignore in case already paid.',
                'collect_fees' => 'Fee Due Reminder for your child |StudentName|. 
                                Dear Parent |ParentName|, please find the below fee summary.
                                Fee: Rs.|Fee|, Back dues 
                                Adjustment: Rs.|Adjustment|, 
                                Total: Rs.|Total|, 
                                Paid: Rs.|Paid|, 
                                Balance: Rs.|Balance|. 
                                Please ignore in case already paid.',
                'stu_promote' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have promoted in the next class.',
                'attendance_sms' => 'Dear Parent |ParentName|, your child |StudentName| came to the school at |time|',
                'absent' => 'Dear parent |ParentName|, your child |StudentName| is absent to the school on |AttendanceDate|',
                'late_sms' => 'Dear parent |ParentName|, your child |StudentName| is late to the school on |AttendanceDate|',
                'er_checkout' => 'Dear parent |ParentName|, your child |StudentName| is checkout  at |time| to the school on |AttendanceDate|',
                'st_checkout' => 'Dear Parent |ParentName|, your child |StudentName| left the school at |time|',
                'st_credentials' => 'Dear parent |ParentName|, your child |StudentName| login details: username:|Username| Password:|Password|',
                'staff_credentials' => 'Dear staff |StaffName| your login details: username:|Username| Password:|Password|',
                'holiday' => 'This is to update you that |HolidayDate| is holiday due to |HolidayName|',
                'leave_app' => 'Dear staff |StaffName|, Thank you for your leave application. Please wait for approval. Thanks ',
                'approve_sms' => 'Dear staff |StaffName|, Thank you for your leave application. Your leave approved. Thanks ',
                'birth_st' => 'Dear parent |ParentName|, Warm wishes to your child  |StudentName| on behalf of his/her birthday',
                'birth_staff' => 'Dear staff |StaffName|, Warm wishes to your birthday. Happy Birthday. Thanks',
                'cheque_bounce' => 'Dear parent |ParentName|, the Cheque with no :|ChequeNo| for Rs.|FeePaid| received towards fee payment for your child :|StudentName| with receipt number:|ReceiptNo| has been Bounced',
                'l_issue_b' => 'Dear parent |ParentName|, Library book  is issued to your child |StudentName| studying in class: |ClassName| , section: |SectionName| with roll no:|RollNo| On |IssueDate| .Please find the details , Book Title: |BookTitle|, Book No: |BookNo|, Due Date: |DueDate|',
                're_issue_book' => 'Dear parent |ParentName|, Library book  is returned by your child |StudentName| studying in class: |ClassName| , section: |SectionName| with roll no:|RollNo| On |ReturnDate| .Please find the details , Book Title: |BookTitle|, Book No: |BookNo|, Issue Date: |IssueDate|, Due Date: |DueDate|',
                'sms_text' => 'hello world',
                
                'password_reset_message' => 'Hi [name], Tap the button below to reset your account password. If you didnt request a new password, you can safely delete this email.',
                'student_login_credential_message' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'guardian_login_credential_message' => 'Hi [father_name]  , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'student_registration_message' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'guardian_registration_message' => 'Hi [father_name]  , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'staff_login_credential_message' => 'Hi [father_name]  , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'send_email_message' => 'Hi [father_name]  , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'dues_payment_message' => 'Hi [student_name], You fees due amount [dues_amount] for [fees_name] on [date]. Thanks',
                'email_footer_text' => 'Copyright &copy; 2020 All rights reserved | This template is made by Codethemes',

                'student_approve_message_sms' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'student_registration_message_sms' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'student_admission_message_sms' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'exam_schedule_message_sms' => 'Hi [student_name] , Welcome to [school_name]. Congratulations ! You have registered successfully. Please login using this username [username] and password [password]. Thanks.',
                'dues_fees_message_sms' => 'Hi [student_name], You fees due amount [dues_amount] for [fees_name] on [date]. Thanks',

            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_templates');
    }
}
