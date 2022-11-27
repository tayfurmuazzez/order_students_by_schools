<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\School;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $status = false;
        $message = 'Failed, Can Not Get Student List';
        $responseCode = 400;
        $studentListData = [];

        //GET ALL Student LIST
        $students = Student::join("schools","schools.id","=","students.school_id")
        ->get([
            "students.id as StudentId",
            "schools.id as SchoolId",
            "students.name as StudentName",
            "schools.name as SchoolName",
            "students.order as StudentOrderNumber",
            "students.created_at as StudentCreatedDate"
        ]);

        if($students){
            foreach($students as $student){
                $studentListData[] = [
                    "StudentId"           => $student->StudentId,
                    "SchoolId"            => $student->SchoolId,
                    "StudentName"         => $student->StudentName,
                    "SchoolName"          => $student->SchoolName,
                    "StudentOrderNumber"  => $student->StudentOrderNumber,
                    "StudentCreatedDate"  => date("Y-m-d H:i:s",strtotime($student->StudentCreatedDate))
                ];
            }
            $status = true;
            $message = 'Successfuly Get Student List!';
            $responseCode = 200;
        }

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $studentListData,
                'failed' => array_filter([
                    'payload_error' => []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $status = false;
        $message = 'Failed, Student can not be created!!';
        $responseCode = 400;
        $createStudentData = [];
        $validationErrors = [];
        $errorsData = [];

        $requestAllData = $request->all();

        $validateRequestData = [
            '*.StudentName'    => 'required|string',
            '*.SchoolName'     => 'required|string'
        ];

        $validator = Validator::make($request->all(),$validateRequestData);
        if(!$validator->fails()){
            foreach($requestAllData as $requestData){
                $schoolControl = School::select("id","name")->where("name",$requestData["SchoolName"])->first();
                if(!$schoolControl){
                    $errorsData[] = "Not Found (".$requestData["SchoolName"].") School!!!";
                    continue;
                }

                $studentControl = Student::select("id","name")
                ->where("name",$requestData["StudentName"])
                ->where("school_id",$schoolControl->id)
                ->first();

                if($studentControl){
                    $errorsData[] = "All Ready Exist Student (".$requestData["StudentName"]." -- ".$requestData["SchoolName"].")!!!";
                    continue;
                }

                //IF NOT EXİST ERROR SUM CORRECT DATAS
                if(!$errorsData){
                    $allStudentCount = Student::where('school_id',$schoolControl->id)->count();
                    $createStudentData[] = [
                        'name'       => $requestData["StudentName"],
                        'school_id'  => $schoolControl->id,
                        'order'      => ++$allStudentCount,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            //CREATE NEW STUDENT DATA WHEN IF DOES NOT EXIST ERROR AND EXIST INSERT DATA 
            if(!$validationErrors && !$errorsData && $createStudentData){
                Student::insert($createStudentData);
                $status = true;
                $message = 'Successfuly Create Student(s)!!';
                $responseCode = 200;
            }else{
                $createStudentData = [];
            }
        } 

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $createStudentData,
                'failed' => array_filter([
                    'payload_error' => $validationErrors ?? [],
                    'custom_error' => $errorsData ?? []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);
    }

       /**
     * Show the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function read($id)
    {
        $status = false;
        $message = 'Failed, Not Found Student';
        $responseCode = 400;
        $studentData = [];

        //GET ONLY ONE STUDENT DATA WİTH STUDENT ID
        $student = Student::join('schools','schools.id','=','students.school_id')
        ->select('students.id as student_id','schools.id as school_id','students.name as student_name',
        'schools.name as school_name','students.order','students.created_at')
        ->where('students.id',$id)
        ->first();

        if($student){
            //RETURN SUCCESS DATA
            $studentData[] = [
                "StudentId"           => $student->student_id,
                "SchoolId"            => $student->school_id,
                "StudentName"         => $student->student_name,
                "SchoolName"          => $student->school_name,
                "StudentOrderNumber"  => $student->order,
                "StudentCreatedDate"  => date("Y-m-d H:i:s",strtotime($student->created_at))
            ];

            $status = true;
            $message = 'Successfuly Get Student!';
            $responseCode = 200;
        }

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $studentData,
                'failed' => array_filter([
                    'payload_error' => []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $status = false;
        $message = 'Failed, Student can not be updated!!';
        $responseCode = 400;
        $updateStudentData = [];
        $validationErrors = [];
        $errorsData = [];

        $requestAllData = $request->all();

        $validateRequestData = [
            '*.StudentId'   => 'required|integer',
            '*.StudentName' => 'required|string'
        ];

        $validator = Validator::make($request->all(),$validateRequestData);
        if(!$validator->fails()){
            foreach($requestAllData as $requestData){
                $studentControl = Student::select("id","name")->where("id",$requestData["StudentId"])->first();
                if(!$studentControl){
                    $errorsData[] = "Not Found Student (".$requestData["StudentId"].")!!!";
                    continue;
                }

                if(!$errorsData){
                    $updateStudentData[] = [
                        'StudentId'          => $requestData["StudentId"],
                        'StudentName'        => $requestData["StudentName"]
                    ];
                }
            }

            //UPDATE STUDENT DATA WHEN IF DOES NOT EXIST ERROR AND EXIST UPDATE DATA 
            if(!$validationErrors && !$errorsData && $updateStudentData){
                foreach($updateStudentData as $updateStudentDatum){
                    Student::where('id',$updateStudentDatum['StudentId'])->update([
                        'name'        => $updateStudentDatum['StudentName'],
                        'updated_at'  => now()
                    ]);
                }
                
                $status = true;
                $message = 'Successfuly Update Student(s)!!';
                $responseCode = 200;
            }else{
                $updateStudentDatum = [];
            }
        } 

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $updateStudentData,
                'failed' => array_filter([
                    'payload_error' => $validationErrors ?? [],
                    'custom_error' => $errorsData ?? []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $status = false;
        $message = 'Failed, Student can not be deleted!!';
        $responseCode = 400;
        $deleteStudentData = [];
        $validationErrors = [];
        $errorsData = [];

        $requestAllData = $request->all();

        $validateRequestData = [
            '*.StudentId'    => 'required|integer'
        ];

        $validator = Validator::make($request->all(),$validateRequestData);
        
        if(!$validator->fails()){
            foreach($requestAllData as $requestData){
                $studentControl = Student::select("id","name")->where("id",$requestData["StudentId"])->get();
                if(!count($studentControl)){
                    $errorsData[] = "Not Found Student (".$requestData["StudentId"].")!!!";
                    continue;
                }else{
                    //DELETE STUDENT DATA
                    $deleteStudentData[] = [
                        'id'         => $requestData["StudentId"],
                        'deleted_at' => now(),
                    ];
                }
            }
            
            //DELETE STUDENT DATA WHEN IF DOES NOT EXIST ERROR AND EXIST DELETE DATA 
            if(!$validationErrors && !$errorsData && $deleteStudentData){
               
                foreach($deleteStudentData as $deleteStudentDatum){
                    Student::find($deleteStudentDatum['id'])->delete();
                }
                
                $status = true;
                $message = 'Successfuly Delete Student(s)!!';
                $responseCode = 200;
            }else{
                $deleteStudentData = [];
            }
        } 

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $deleteStudentData,
                'failed' => array_filter([
                    'payload_error' => $validationErrors ?? [],
                    'custom_error' => $errorsData ?? []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);
    }
}
