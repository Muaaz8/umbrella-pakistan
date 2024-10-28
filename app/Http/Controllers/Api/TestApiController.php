<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\TestModel;
use App\Http\Controllers\API\BaseController as BaseController;
use Auth;
class TestApiController extends BaseController
{
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised', ['error'=>'Invalid email and password']);
        }
    }
    public function record(){
        if(Auth::check()){
            $records = TestModel::get();
            return $this->sendResponse($records,'Records Fetched!');
        } else{
            return $this->sendError('Error',['error','Login Requried']);
        }

    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'country' => 'required'
        ]);
        if ($validator->fails()) {
          return $this->sendError('Validation error',['error',$validator->errors()]);
        } else{
           $data = TestModel::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'bio' => $request->bio,
            ]);
            return $this->sendResponse($data,'Record Successfully Created');
        }
    }
    public function delete($id){

        $record = TestModel::find($id);
        $record->delete();
        return $this->sendResponse('Success','Record Deleted Successfully');
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'country' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error',['error',$validator->errors()]);
        } else{
        $record = TestModel::find($id);
        $data = $record->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'bio' => $request->bio,
        ]);
        return $this->sendResponse($data,'Record Updated Successfully!');

        }

    }
}
