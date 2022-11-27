<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //CONTROL SCHOOL DATA
        //CREATE NEW DATA IF NOT EXIST SCHOOL DATA
        $isExistShool = School::first();
        if(!$isExistShool){
            School::factory(10)->create();
        }
    }
}
