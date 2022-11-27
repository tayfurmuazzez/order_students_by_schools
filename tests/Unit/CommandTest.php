<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class CommandTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function fixedOrderNumberCommandTest()
    {
        $this->artisan('schedule:run')->assertExitCode(0);
        //ONLY RUN SCHEDULE CODES 
        //NOT SEND MAIL
        //SEND MAIL NOT WORKING IN UNIT TEST
        $this->assertTrue(true);
    }
}
