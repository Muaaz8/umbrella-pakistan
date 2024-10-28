<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class BaseController extends Controller
{
    public function sendResponse($result , $msg){
        $response = [
            'success' => true,
            'info'    => $result,
            'message' => $msg,
        ];
        return response()->json($response, 200);
    }
    public function sendError($result, $error, $errorMessages = [], $code =400)
    {
        $response = [
            'success' => false,
            'info' => $result,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['code'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
