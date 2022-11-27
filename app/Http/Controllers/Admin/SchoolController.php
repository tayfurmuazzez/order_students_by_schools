<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\School;
use App\Models\Student;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $status = false;
        $message = 'Failed, Can Not Get School List';
        $responseCode = 400;
        $schoolListData = [];

        //GET ALL SCHOOL LIST
        $schools = School::get();

        if($schools){
            foreach($schools as $school){
                $schoolListData[] = [
                    "SchoolId" => $school->id,
                    "SchoolName"  => $school->name
                ];
            }
            $status = true;
            $message = 'Successfuly Get School List!';
            $responseCode = 200;
        }

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $schoolListData,
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
        $message = 'Failed, School can not be created!!';
        $responseCode = 400;
        $createSchoolData = [];
        $validationErrors = [];
        $errorsData = [];

        $requestAllData = $request->all();

        $validateRequestData = [
            '*.SchoolName'     => 'required|string'
        ];

        $validator = Validator::make($request->all(),$validateRequestData);
        if(!$validator->fails()){
            foreach($requestAllData as $requestData){
                $schoolControl = School::select("id","name")->where("name",$requestData["SchoolName"])->first();
                if(!$schoolControl){
                    //CREATE NEW SCHOOL DATA
                    $createSchoolData[] = [
                        'name'       => $requestData["SchoolName"],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }else{
                    $errorsData[] = "All Ready Exist (".$requestData["SchoolName"].") School!!!";
                    continue;
                }
            }

            //CREATE NEW DATA WHEN IF DOES NOT EXIST ERROR AND EXIST INSERT DATA 
            if(!$validationErrors && !$errorsData && $createSchoolData){
                School::insert($createSchoolData);
                $status = true;
                $message = 'Successfuly Create School(s)!!';
                $responseCode = 200;
            }else{
                $createSchoolData = [];
            }
        } 

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $createSchoolData,
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
        $message = 'Failed, Not Found School';
        $responseCode = 400;
        $schoolData = [];

        //GET ONLY ONE SCHOOL DATA WİTH SCHOOL ID
        $school = School::where('id',$id)->first();

        if($school){
            //RETURN SUCCESS DATA
            $schoolData[] = [
                "SchoolId"    => $school->id,
                "SchoolName"  => $school->name,
                "createdAt"   => date("Y-m-d H:i:s",strtotime($school->created_at))
            ];

            $status = true;
            $message = 'Successfuly Get School!';
            $responseCode = 200;
        }

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $schoolData,
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
        $message = 'Failed, School can not be updated!!';
        $responseCode = 400;
        $updateSchoolData = [];
        $validationErrors = [];
        $errorsData = [];

        $requestAllData = $request->all();

        $validateRequestData = [
            '*.SchoolId'    => 'required|integer',
            '*.SchoolName'  => 'required|string'
        ];

        $validator = Validator::make($request->all(),$validateRequestData);
        if(!$validator->fails()){
            foreach($requestAllData as $requestData){
                $schoolControl = School::select("id","name")->where("id",$requestData["SchoolId"])->first();
                if(!$schoolControl){
                    $errorsData[] = "Not Found School (".$requestData["SchoolName"].")!!!";
                    continue;
                }else{
                    //UPDATE SCHOOL DATA
                    $updateSchoolData[] = [
                        'id'         => $requestData["SchoolId"],
                        'name'       => $requestData["SchoolName"],
                        'updated_at' => now(),
                    ];
                }
            }

            //UPDATE SCHOOL DATA WHEN IF DOES NOT EXIST ERROR AND EXIST UPDATE DATA 
            if(!$validationErrors && !$errorsData && $updateSchoolData){
                foreach($updateSchoolData as $updateSchoolDatum){
                    School::where('id',$updateSchoolDatum['id'])->update([
                        'name'       => $updateSchoolDatum['name'],
                        'updated_at' => $updateSchoolDatum['updated_at']
                    ]);
                }
                
                $status = true;
                $message = 'Successfuly Update School(s)!!';
                $responseCode = 200;
            }else{
                $updateSchoolData = [];
            }
        } 

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $updateSchoolData,
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
        $message = 'Failed, School can not be deleted!!';
        $responseCode = 400;
        $deleteSchoolData = [];
        $validationErrors = [];
        $errorsData = [];

        $requestAllData = $request->all();

        $validateRequestData = [
            '*.SchoolId'    => 'required|integer'
        ];

        $validator = Validator::make($request->all(),$validateRequestData);
        
        if(!$validator->fails()){
            foreach($requestAllData as $requestData){
                $schoolControl = School::select("id","name")->where("id",$requestData["SchoolId"])->get();
                if(!count($schoolControl)){
                    $errorsData[] = "Not Found School (".$requestData["SchoolId"].")!!!";
                    continue;
                }else{
                    //DELETE SCHOOL DATA
                    $deleteSchoolData[] = [
                        'id'         => $requestData["SchoolId"],
                        'deleted_at' => now(),
                    ];
                }
            }
            
            //DELETE SCHOOL DATA WHEN IF DOES NOT EXIST ERROR AND EXIST DELETE DATA 
            if(!$validationErrors && !$errorsData && $deleteSchoolData){
               
                foreach($deleteSchoolData as $deleteSchoolDatum){
                    School::find($deleteSchoolDatum['id'])->delete();
                    Student::where('school_id',$deleteSchoolDatum['id'])->delete();
                }
                
                $status = true;
                $message = 'Successfuly Delete School(s)!!';
                $responseCode = 200;
            }else{
                $deleteSchoolData = [];
            }
        } 

        //RESPONSE ARRAY DATA
        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $deleteSchoolData,
                'failed' => array_filter([
                    'payload_error' => $validationErrors ?? [],
                    'custom_error' => $errorsData ?? []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);
    }
}
