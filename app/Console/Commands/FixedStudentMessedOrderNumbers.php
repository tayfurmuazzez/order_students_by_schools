<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\Models\School;
use App\Models\Student;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class FixedStudentMessedOrderNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:fixed_order_number_for_messed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixed Order Number All Student for Schools When Messed Order Numbers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //$this->info("Started Fixed Order Number Command");
        //GET ALL STUDENT LIST WITH ORDER NUMBER ORDER SCHOOL IDS
        $students = Student::orderBy('school_id','asc')
        ->orderBy('id','asc')
        ->get();

        $this->info(json_encode($students));
        $previousSchoolId = 0;
        $orderNumber = 1;

        $updateStudentCount = 0;
        foreach($students as $student){
            if($student->school_id != $previousSchoolId){
                $orderNumber = 1;
                $previousSchoolId = $student->school_id;
            }

            Student::where('id',$student->id)->update(['order' => $orderNumber]);

            $orderNumber++;
            $updateStudentCount++;
        }

        //$this->info("Finished Fixed Order Number Command");
        
        Mail::to(env('MAIL_TO','user@admin.com'))->send(new SendMail());
        //SEND MAIL FUNCTION FOR TEST 
        //USE MAILTRAP.IO
        //WHEN SEND NEW MAIL, ALL MAILS GETTING MAILTRAP INBOX
        //TEST FUNCTION SUCCESSFULL
        
    }
}
