<?php

namespace Tests\Unit;
use Tests\TestCase;

use Faker\Factory;
use App\Models\School;
use App\Models\Student;

class ApiStudentTest extends TestCase
{

    protected $faker;
    protected $access_token;

    /**
     * A basic unit test for signin.
     *
     * @return string
     */
    /** @test */
    public function signinTest(){
        //SIGNIN FOR AUTH
        $adminUserMail = env('ADMIN_USER_MAIL','user@admin.com');
        $adminUserPassword = env('ADMIN_USER_PASSWORD','123');

        $responseAuth = $this->postJson('/api/signin', ['email' => $adminUserMail,'password' => $adminUserPassword]);
        $responseAuth->assertStatus(200);
        $content = $responseAuth->decodeResponseJson();
        
        $access_token = $content["data"]["success"]["MgsSoftTask"]["token"]["access_token"];

        $this->assertTrue($access_token != "");
        $this->assertAuthenticated();

        $GLOBALS['access_token'] = $access_token;
    }

    /**
     * A basic unit test for create new student.
     *
     * @return void
     */
    /** @test */
    public function createStudentTest()
    {
        $this->faker = Factory::create();
        $school = School::inRandomOrder()->first();
        $data[] = [
            'StudentName' => $this->faker->name,
            'SchoolName'  => $school->name,
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$GLOBALS['access_token'],
        ])->postJson('/api/student/create', $data);

        $jsonResponse = $response->decodeResponseJson();

        $this->assertTrue($jsonResponse["status"]);
        
    }

    /**
     * A basic unit test for get student list.
     *
     * @return void
     */
    /** @test */
    public function getStudentsTest()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$GLOBALS['access_token'],
        ])->get('/api/student/get');

        $jsonResponse = $response->decodeResponseJson();

        $this->assertTrue($jsonResponse["status"]);
        
    }

    /**
     * A basic unit test for update students.
     *
     * @return void
     */
    /** @test */
    public function updateStudentsTest()
    {
        $this->faker = Factory::create();
        $student = Student::inRandomOrder()->first();
        $data[] = [
            'StudentId'    => $student->id,
            'StudentName'  => $this->faker->name,
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$GLOBALS['access_token'],
        ])->postJson('/api/student/update', $data);

        $jsonResponse = $response->decodeResponseJson();

        $this->assertTrue($jsonResponse["status"]);
        
    }

    /**
     * A basic unit test for delete students.
     *
     * @return void
     */
    /** @test */
    public function deleteStudentsTest()
    {
        $student = Student::inRandomOrder()->first();
        $data[] = [
            'StudentId'    => $student->id
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$GLOBALS['access_token'],
        ])->postJson('/api/student/delete', $data);

        $jsonResponse = $response->decodeResponseJson();

        $this->assertTrue($jsonResponse["status"]);
        
    }

    /**
     * A basic unit test for get student list.
     *
     * @return void
     */
    /** @test */
    public function ReadOneStudentTest()
    {
        $student = Student::inRandomOrder()->first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$GLOBALS['access_token'],
        ])->get('/api/student/read/'.$student->id);

        $jsonResponse = $response->decodeResponseJson();

        $this->assertTrue($jsonResponse["status"]);
        
    }
}
