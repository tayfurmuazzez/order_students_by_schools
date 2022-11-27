<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //STUDENT CONTROL
        //CREATE NEW STUDENTS IF NOT EXIST DATA
        $isExistStudent = Student::first();
        if(!$isExistStudent){
            //GET ALL SCHOOLS
            $getSchools = School::get();
            
            //CREATE NEW STUDENTS FOR ALL SCHOOLS AND ADDED ORDERS
            foreach($getSchools as $getSchool){
                for($orderIndex=1;$orderIndex<=10;$orderIndex++){
                    Student::factory()->create([
                        'school_id' => $getSchool->id,
                        'order' => $orderIndex
                    ]);
                }
            }
        }
    }
}
