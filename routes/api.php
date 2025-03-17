<?php

use App\Events\AppEvent;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//patient registration
Route::post('email_verification', 'Api\RegistrationController@patient_registration');
Route::post('get_state', 'Api\RegistrationController@get_state');
Route::post('register_patient', 'Api\RegistrationController@store_patient');
//doctor registration
Route::get('get_specialization', 'Api\RegistrationController@doc_specialization');
Route::get('lienced_state', 'Api\RegistrationController@lienced_state');
Route::post('validation_doctor', 'Api\RegistrationController@validation_doctor');
Route::post('npi_validation', 'Api\RegistrationController@npi_validation');
Route::post('register_doctor', 'Api\RegistrationController@store_doctor');
Route::post('upload_id_Card', 'Api\RegistrationController@upload_id_Card')->middleware('auth:sanctum');
Route::get('resend_verification', 'Api\RegistrationController@resend_verification')->middleware('auth:sanctum');
Route::post('login', 'Api\RegistrationController@login');
Route::post('logout', 'Api\RegistrationController@logout')->middleware('auth:sanctum');
//reset password API
Route::post('reset_password', 'Api\RegistrationController@reset_password');
Route::post('sessionCheck/{session_id}', 'Api\RegistrationController@sessionCheck');
Route::post('email_varification','Api\RegistrationController@email_varification');

// products api

Route::get('products/{name}', 'Api\ProductsController@index');
Route::get('product/{name}/{id}', 'Api\ProductsController@singleProduct');  
Route::get('categories/{name}','Api\ProductsController@getCategories');
Route::get('category/{name}/{id}','Api\ProductsController@getProductsByCategory');
Route::get('doctors','Api\DoctorsController@index');
Route::get('online/doctors','Api\DoctorsController@getOnlineDoctors');
Route::get('doctor/{id}','Api\DoctorsController@singleDoctor');
Route::get('specializations','Api\DoctorsController@getSpeciallization');
Route::get('specialization/doctors/{id}','Api\DoctorsController@getDoctorsBySpeciallization');
Route::get('specialization/online/doctors/{id}','Api\DoctorsController@getOnlineDoctorsBySpeciallization');
Route::get('test' , function(){event(new AppEvent());});

//================================================//
        //PATIENT PROFILE
//================================================//
Route::middleware('auth:sanctum','doc_restrict','patToVideoScreen')->group( function () {
});
Route::middleware(['auth:sanctum','doc_restrict'])->group(function(){
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('session','Api\SessionsController@createSession');
    Route::get('session/{id}','Api\SessionsController@getSession');
    Route::get('session/invite/{id}','Api\SessionsController@sessionInvite');
    Route::get('joinCall/{id}','Api\SessionsController@videoJoin');
    Route::get('sessions/queue','Api\DoctorsController@patients_in_queue');
    Route::get('video/patient_join/{id}','Api\VideoController@waitingPatientJoinCall');
    Route::get('video/doctor/end/{id}','Api\VideoController@doctor_end_session');
    Route::post('recommendation','Api\RecommendationController@store');
});
    //================================================//
            //PATIENT PROFILE END HERE
    //================================================//
Route::middleware(['auth:sanctum','pat_restrict','apiDoctorIsActive','docToVideoScreen'])->group(function (){
});
Route::middleware(['auth:sanctum','pat_restrict','apiDoctorIsActive',])->group(function(){
});
   //=================================================//
            //Doctor PROFILE END HERE
    //================================================//

//vide screen apis
Route::middleware('auth:sanctum')->group(function (){

});
