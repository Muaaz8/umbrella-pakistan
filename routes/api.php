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
Route::get('login/check', 'Api\RegistrationController@autoLogin');
Route::post('email_verification', 'Api\RegistrationController@patient_registration');
Route::post('get_state', 'Api\RegistrationController@get_state');
Route::post('register_patient', 'Api\RegistrationController@create');
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
Route::post('otp_verification','Api\RegistrationController@otp_verification');
Route::post('resend_otp','Api\RegistrationController@resend_otp');

// products api

Route::get('products/{name}', 'Api\ProductsController@index');
Route::get('product/{name}/{id}', 'Api\ProductsController@singleProduct'); 
Route::post('pharmacy', 'Api\ProductsController@getPharmacyByCategory'); 
Route::post('imaging', 'Api\ProductsController@getImagingByCategory'); 
Route::get('categories/{name}','Api\ProductsController@getCategories');
Route::get('category/{name}/{id}','Api\ProductsController@getProductsByCategory');
Route::get('doctors','Api\DoctorsController@index');
Route::get('doctors/filter/{type}','Api\DoctorsController@doctors_filter');
Route::get('doctor/{id}','Api\DoctorsController@singleDoctor');
Route::get('online/doctors','Api\DoctorsController@getOnlineDoctors');
Route::get('specializations','Api\DoctorsController@getSpeciallization');
Route::get('specialization/doctors/{id}','Api\DoctorsController@getDoctorsBySpeciallization');
Route::get('specialization/online/doctors/{id}','Api\DoctorsController@getOnlineDoctorsBySpeciallization');
Route::post('medicine/detail','Api\MedicineController@get_medicine_detail');

Route::get('test' , function(){event(new AppEvent());});

//================================================//
//PATIENT PROFILE
//================================================//
Route::middleware('auth:sanctum','doc_restrict','patToVideoScreen')->group( function () {
});

Route::middleware(['auth:sanctum','doc_restrict'])->group(function(){
    Route::get('patient/sessions','Api\SessionsController@pat_sessions_record');
    Route::get('patient/orders/{id}','Api\OrdersController@order_details');
    Route::get('patient/orders','Api\OrdersController@patient_orders');
    Route::post('patient/create/appointment','Api\AppointmentsController@create_appointment');
    Route::get('patient/cancel/appointment/{id}','Api\AppointmentsController@pat_appointment_cancel');
    Route::get('patient/appointments','Api\AppointmentsController@patient_appointments');
    Route::get('patient/medical/profile','Api\PatientsController@pat_medical_profile');
    Route::get('patient/get/doctors/{id}','Api\AppointmentsController@book_appointment');
    Route::get('available/dates/{id}','Api\AppointmentsController@get_doc_avail_dates');
    Route::get('patient/dashboard/info','Api\PatientsController@get_patient_dasboard_info');
    Route::post('patient/medical/profile/update','Api\PatientsController@patient_medical_prfile_save');
    Route::post('doctor/day/timing','Api\AppointmentsController@doctor_timing');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('session','Api\SessionsController@createSession');
    Route::get('session/{id}','Api\SessionsController@getSession');
    Route::get('session/invite/{id}','Api\SessionsController@sessionInvite');
    Route::post('joinCall/{id}','Api\SessionsController@videoJoin');
    Route::get('sessions/queue','Api\DoctorsController@patients_in_queue');
    Route::get('video/patient_join/{id}','Api\VideoController@waitingPatientJoinCall');
    Route::get('video/doctor/end/{id}','Api\VideoController@doctor_end_session');
    Route::get('change/status','Api\DoctorsController@change_online_status');
    Route::get('prescription/{id}','Api\PrescriptionController@getSessionPrescription');
    Route::get('cart', 'Api\CartController@my_cart');
    Route::get('select/product/{id}', 'Api\CartController@select_cart_product');
    Route::get('remove/product/{id}', 'Api\CartController@remove_item_from_cart');
    Route::get('checkout/products', 'Api\CartController@show_checkout_products');
    Route::get('unselect/product/{id}', 'Api\CartController@remove_product_on_checkout');
    Route::post('checkout', 'Api\CartController@create_new_order');
    Route::post('add_to_cart', 'Api\CartController@add_to_cart');
    Route::get('checkout/return/{id}', 'Api\CartController@order_payment_app_return');
    Route::get('cart/count', 'Api\CartController@get_cart_counter');
    Route::get('get_notification', 'Api\NotificationController@getUserNotification');
    Route::get('payment/return/{id}', 'MeezanPaymentController@payment_return_app');
    Route::get('order/confirm', 'Api\OrdersController@order_confirm');
});
//================================================//
//PATIENT PROFILE END HERE
//================================================//
Route::middleware(['auth:sanctum','pat_restrict','apiDoctorIsActive','docToVideoScreen'])->group(function (){
});




Route::middleware(['auth:sanctum','pat_restrict'])->group(function(){
    Route::post('recommendation','Api\RecommendationController@store');
    Route::post('prescription/medicine/addDose','Api\PrescriptionController@addMedicineDose');
    Route::post('prescription/addLab','Api\PrescriptionController@addLab');
    Route::post('prescription/addImaging','Api\PrescriptionController@addImaging');
    Route::post('prescription/removeItem','Api\PrescriptionController@removeItem');
    Route::post('prescription/addMedicine','Api\PrescriptionController@addMedicine');
});
   //=================================================//
            //Doctor PROFILE END HERE
    //================================================//

//vide screen apis
Route::middleware('auth:sanctum')->group(function (){

});
