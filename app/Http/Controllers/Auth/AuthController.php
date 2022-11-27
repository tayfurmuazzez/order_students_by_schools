<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function signin(Request $request){
        $status = false;
        $message = 'Failed, Token can not be retrieve!!';
        $responseCode = 400;
        $tokenData = [];
        $validationErrors = [];

        $validator = Validator::make($request->all(),[
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $validationErrors = $validator->errors();
        }

        $userAttempt = Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password
        ]);


        if(!$validationErrors && $userAttempt){
            $user = Auth::user();
            $userToken = $user->createToken('MgsSoftTask');

            $tokenData[$userToken->accessToken->name] = [
                'user' => $user->name,
                'token' => [
                    'access_token' => $userToken->plainTextToken,
                    'expires_in' => 3600,
                    'token_type' => 'Bearer',
                    'created_at' => $userToken->accessToken->created_at
                ]
            ];

            $message = 'Token successfully retrieved!!';
            $status = true;
            $responseCode = 200;
        }

        $result =  [
            'status' => $status,
            'message' => $message,
            'data' => array_filter([
                'success' => $tokenData,
                'failed' => array_filter([
                    'payload_error' => $validationErrors ?? []
                ])
            ])
        ];

        return response()->json($result,$responseCode,[]);

    }
}
