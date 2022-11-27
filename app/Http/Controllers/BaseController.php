<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($message,$data = []):JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if(!empty($data)){
            $response["data"] = $data;
        }

        return response()->json($response);
    }

    public function sendError($message,$data = [],$code = 404):JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if(!empty($data)){
            $response["data"] = $data;
        }

        return response()->json($response,$code,[]);
    }
}
