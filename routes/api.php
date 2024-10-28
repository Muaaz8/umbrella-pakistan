<?php

use App\Http\Controllers\Api\AgoraController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\RestApiController;
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
//route for api: localhost/api/new_item
Route::get('/new_item', function () {
    return "welcome";
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
//Product and Category
Route::get('categories', 'Api\ProductController@categories');
Route::get('sub_category', 'Api\ProductController@sub_category');
Route::get('products/{id}', 'Api\ProductController@products');
Route::get('medicine','Api\ProductController@medicine');
Route::get('product_detail/{id}', 'Api\ProductController@product_detail');
Route::get('lab_detail/{id}', 'Api\ProductController@lab_detail');
Route::post('product_search', 'Api\ProductController@product_search');
//lab test
Route::get('lab_test_categories', 'Api\ProductController@lab_test_categories');
Route::get('lab_test_product/{id}', 'Api\ProductController@lab_test');
Route::post('labtest_search', 'Api\ProductController@lab_test_search');
//Imaging
Route::get('imaging_categories', 'Api\ProductController@imaging_categories');
Route::get('imaging_product/{id}', 'Api\ProductController@imaging_product');
Route::post('imaging_product_search', 'Api\ProductController@imaging_product_search');
//psychiatry
Route::get('psychiatry', 'Api\ProductController@psychiatry');
//popular lab test
Route::get('popular_lab_test', 'Api\ProductController@popular_lab_test');
//email varification
Route::post('email_varification','Api\RegistrationController@email_varification');
// add to cart
Route::post('add_to_cart','Api\CartController@add_to_cart')->middleware('auth:sanctum');
Route::get('remove_item_from_cart/{cart_id}','Api\CartController@remove_item_from_cart')->middleware('auth:sanctum');
Route::get('view_cart','Api\CartController@view_cart')->middleware('auth:sanctum');
Route::post('selected_CartItems','Api\CartController@selected_CartItems')->middleware('auth:sanctum');
Route::get('your_item','Api\CartController@your_item')->middleware('auth:sanctum');
Route::post('oldcard_details','Api\PaymentController@get_card_details')->middleware('auth:sanctum');
Route::post('new_card_payment','Api\PaymentController@authorize_create_new_order')->middleware('auth:sanctum');
Route::post('old_card_payment','Api\PaymentController@oldCard_payment')->middleware('auth:sanctum');
Route::get('oldCards', 'Api\PaymentController@oldCards')->middleware('auth:sanctum');

//================================================//
        //PATIENT PROFILE
//================================================//
Route::middleware('auth:sanctum','doc_restrict','patToVideoScreen')->group( function () {
    //profile and Activity log
    Route::get('profile_view', 'Api\ProfileController@userinfo');
    //patient activity
    Route::get('patient_activity', 'Api\ProfileController@patient_activity');
    //profile picture upload
    Route::post('profile_picture', 'Api\ProfileController@profile_picture');
    //profile phone number
    Route::post('profile_number', 'Api\ProfileController@profile_number');
    //profile info update
    Route::post('update_profileInfo', 'Api\ProfileController@update_profileInfo');
    //changed password
    Route::post('changepassword', 'Api\ProfileController@changepassword');
    //medical profile
    Route::get('medical_profile', 'Api\ProfileController@medical_profile');
    //family list
    Route::get('family_list', 'Api\ProfileController@family_list');
    //user allergies
    Route::post('user_allergies', 'Api\ProfileController@allergies');
    //user medical condition
    Route::post('user_medical_condition', 'Api\ProfileController@medical_condition');
    //user family history
    Route::post('family_history', 'Api\ProfileController@family_history');
    //user Immunization history
    Route::post('immunization_history', 'Api\ProfileController@immunization_history');
    //user Medication history
    Route::post('medication_history', 'Api\ProfileController@medication_history');
    //user surgeries
    Route::post('surgeries', 'Api\ProfileController@surgeries');
    //user user comments
    Route::post('user_comments', 'Api\ProfileController@user_comments');
    //medical file upload
    Route::post('medical_record_file', 'Api\ProfileController@medical_record_file');
    //isabel symptoms
    Route::get('isabel_symptoms', 'Api\ProfileController@isabel_symptoms');
    //immunization
    Route::get('immunization', 'Api\ProfileController@immunization');
    //diseases
    Route::get('diseases', 'Api\ProfileController@diseases');
    //medical_profile_update
    Route::post('medical_profile_update', 'Api\ProfileController@medical_profile_update');
    //mydoctor
    Route::get('mydoctor', 'Api\MydoctorController@mydoctor');
    Route::post('search_mydoctor', 'Api\MydoctorController@search_mydoctor');
    //view mydoctor profile
    Route::get('view_docprofile/{id}', 'Api\MydoctorController@view_docProfile');
    //sessions Records
    Route::get('my_sessions', 'Api\MysessionsController@mysession');
    Route::get('mysession_detail/{session_id}', 'Api\MysessionsController@mysession_detail');
    //session search
    Route::post('session_search_by_id', 'Api\MysessionsController@session_searchByID');
    Route::post('session_search_by_datefilter', 'Api\MysessionsController@session_searchByDatefilter');
    Route::post('session_search_by_id_and_datefilter', 'Api\MysessionsController@session_searchById_and_Datefilter');
    //all_orders
    Route::get('all_orders', 'Api\MyOrderController@all_orders');
    Route::post('search_order', 'Api\MyOrderController@search_order');
    //order_details
    Route::get('order_details/{id}', 'Api\MyOrderController@order_details');
    //imaging
    Route::get('imaging', 'Api\ImagingController@imaging');
    Route::get('all_imaging_products', 'Api\ImagingController@imaging_products');
    Route::get('dash_imaging_file', 'Api\ImagingController@dash_imaging_file');
    Route::post('imaging_result_search', 'Api\ImagingController@imaging_result_search');
    //Lab
    Route::get('lab_requisition', 'Api\LabController@lab_requisition');
    Route::post('lab_requisition_search', 'Api\LabController@lab_requisition_search');
    //lab requisition pending
    Route::get('lab_requisition_pending', 'Api\LabController@lab_requisition_pending');
    Route::post('lab_requisition_pending_search', 'Api\LabController@lab_requisition_pending_search');
    // view requisition
    Route::get('view_requisition/{id}', 'Api\LabController@view_requisition');
    //lab report
    Route::get('lab_report', 'Api\LabController@lab_report');
    //recent lab buy
    Route::get('recent_lab_buy', 'Api\LabController@recent_lab_buy');
    //current medication
    Route::get('current_medication', 'Api\MedicationController@current_medication');
    Route::post('current_medication_search', 'Api\MedicationController@current_medication_search');
    //detials current medication
    Route::get('current_medication/{id}', 'Api\MedicationController@current_medication_detail');
    //refill_request
    Route::post('refill_request', 'Api\MedicationController@refill_request');
    //Appointment
    Route::get('specializations', 'Api\AppointmentController@specializations');
    //book appointment
    Route::get('book_appointment/{id}', 'Api\AppointmentController@book_appointment');
    //search doctor
    Route::post('search_doctor', 'Api\AppointmentController@search_doctor');
    //doctor schedule
    Route::get('doctor_schedule/{id}', 'Api\AppointmentController@doctor_schedule');
    //get doctor date
    Route::get('get_doctor_date/{doctor_id}', 'Api\AppointmentController@get_doctor_date');
    //get doctor time slots
    Route::post('get_doctor_time_slots', 'Api\AppointmentController@get_doctor_time_slots');
    //create_appointment
    Route::post('create_appointment','Api\AppointmentController@create_appointment');
    //appointment payment
    Route::get('get_old_cards/{session_id}', 'Api\AppointmentController@appointment_payment');
    //payment old cards _apppointment
    Route::post('payment_old_cards', 'Api\AppointmentController@payment_old_cards_apppointment');
    //create new card _apppointment
    Route::post('create_new_card', 'Api\AppointmentController@create_new_card_apppointment');
    //All Appointments
    Route::get('all_appointment', 'Api\AppointmentController@all_appointment');
    //appointment detail
    Route::get('appointment_detail/{appointment_id}', 'Api\AppointmentController@appointment_detail');

    Route::post('appointment_search', 'Api\AppointmentController@appointment_search');
    //patient appointment cancel
    Route::get('patient_appointment_cancel/{id}', 'Api\AppointmentController@patient_appointment_cancel');
    //patient health store
    Route::post('patient_health_store', 'Api\AppointmentController@patient_health_store');
    //mood disorder store
    Route::post('mood_disorder_store', 'Api\AppointmentController@mood_disorder_store');
    //anxiety scale store
    Route::post('anxiety_scale_store', 'Api\AppointmentController@anxiety_scale_store');
    //get online Doctors Evisit
    Route::get('onlineDoctors/{id}', 'Api\EvisitController@onlineDoctors');
    //problem inquiry
    Route::post('problem_inquiry', 'Api\EvisitController@problem_inquiry');
    //evisit old card payment
    Route::post('evisit_oldCard', 'Api\EvisitController@evisit_oldCard');
    //evisit new card payment
    Route::post('evisit_newcard', 'Api\EvisitController@evisit_newcard');
    //send Invitation
    Route::post('sendinvite', 'Api\EvisitController@sendinvite');
    //waiting room patient
    Route::post('waiting_room_patient', 'Api\EvisitController@waiting_room_pat');
    //latest session
    Route::get('latest_session', 'Api\EvisitController@latest_session');

    //mydoctor
    Route::get('mydoctor', 'Api\MydoctorController@mydoctor');
    //view mydoctor profile
    Route::get('view_docprofile/{id}', 'Api\MydoctorController@view_docProfile');
    //all notifictions
    Route::get('all_notifications_pat', 'Api\PatientNotificationController@all_notifictions');
    //read all notification
    Route::get('read_all_notification_pat', 'Api\PatientNotificationController@read_all_notification');
    // view unread notifictions
    Route::get('view_unread_notifictions_pat', 'Api\PatientNotificationController@view_unread_notifictions');
    //read notification
    Route::post('read_notification_pat', 'Api\PatientNotificationController@read_notification_pat');
    Route::get('remove_reminder/{session_id}', 'Api\PatientNotificationController@remove_reminder');
    //patient dashboard info
    Route::get('patient_dashboard_info', 'Api\PatientDashboardController@patient_dashboard_info');
    //reminder
    Route::get('reminder', 'Api\PatientDashboardController@reminder');
    //user timezone
    Route::post('timezone', 'Api\UserTimeZoneController@timezone');
    //blog Controller
    Route::post('blog_store', 'Api\BlogController@blog_store');

});
Route::middleware(['auth:sanctum','doc_restrict'])->group(function(){
    //patient join button
    Route::post('patient_join', 'Api\EvisitController@patient_join');
    // patient Video Screen
    Route::get('patient_video/{session_id}', 'Api\Video\VideoScreenController@patientVideo');
    Route::post('patient_symptoms', 'Api\Video\VideoScreenController@patient_symptoms');
    Route::post('patient_medical_history', 'Api\Video\VideoScreenController@patient_medical_history');
    Route::post('patient_current_medication', 'Api\Video\VideoScreenController@patient_current_medication');
    Route::post('patient_family_history', 'Api\Video\VideoScreenController@patient_family_history');
    Route::post('patient_imaging_report', 'Api\Video\VideoScreenController@patient_imaging_report');
    Route::post('patient_lab_report', 'Api\Video\VideoScreenController@patient_lab_report');
    Route::post('my_visit_history', 'Api\Video\VideoScreenController@patient_visit_history');
});
    //================================================//
            //PATIENT PROFILE END HERE
    //================================================//
Route::middleware(['auth:sanctum','pat_restrict','apiDoctorIsActive','docToVideoScreen'])->group(function (){
    //Doctor Dashboard
    Route::get('doctor_dashboard', 'Api\DoctorsApi\DoctorDashboardController@doctor_dashboard');
    //Doctor Appointment
    Route::get('doctor_appointments', 'Api\DoctorsApi\AppointmentController@doctor_appointments');
    //view_appointment
    Route::get('view_appointment', 'Api\DoctorsApi\AppointmentController@view_appointment');
    Route::get('appointment_detail_doc/{session_id}', 'Api\DoctorsApi\AppointmentController@appointment_detail');
    //doc appointment cancel
    Route::get('doc_appointment_cancel/{id}', 'Api\DoctorsApi\AppointmentController@doc_appointment_cancel');
    //doctor schedule list
    Route::get('doctor_schedule_list', 'Api\DoctorsApi\DoctorScheduleController@doctor_schedule_list');
    //get schedule slots
    Route::post('get_schedule_slots', 'Api\DoctorsApi\DoctorScheduleController@get_schedule_slots');
    //schedule check
    Route::post('schedule_check', 'Api\DoctorsApi\DoctorScheduleController@schedule_check');
    //schedule create
    Route::post('schedule_create', 'Api\DoctorsApi\DoctorScheduleController@schedule_create');
    //edit_schedule
    Route::get('edit_schedule/{id}', 'Api\DoctorsApi\DoctorScheduleController@edit_schedule');
    //update_schedule
    Route::put('update_schedule/{id}', 'Api\DoctorsApi\DoctorScheduleController@update_schedule');
    //delete schedule
    Route::get('delete_schedule/{id}', 'Api\DoctorsApi\DoctorScheduleController@delete_schedule');
    // doctor patient queue
    Route::get('doctor_patient_queue', 'Api\DoctorsApi\DoctorScheduleController@doctor_patient_queue');
    //patient refill requests
    Route::get('patient_refill_requests', 'Api\DoctorsApi\RefillRequestController@patient_refill_requests');
    //grant refill
    Route::post('grant_refill', 'Api\DoctorsApi\RefillRequestController@grant_refill');
    //refill request detail
    Route::get('refill_request_detail/{id}', 'Api\DoctorsApi\RefillRequestController@refill_request_detail');
    // refill session detail
    Route::get('refill_sessions_detail/{id}', 'Api\DoctorsApi\RefillRequestController@refill_sessions_detail');
    //session request to patient if schedule is available
    Route::post('send_session_req', 'Api\DoctorsApi\RefillRequestController@send_session_req');
    //doctor request session instead of accepting refill request
    Route::post('req_session', 'Api\DoctorsApi\RefillRequestController@req_session');
    //doctor creating schedule for sending session request
    Route::post('request_session_schedule', 'Api\DoctorsApi\RefillRequestController@request_session_schedule');
    //doctor all patients
    Route::get('doctor_all_patients', 'Api\DoctorsApi\DoctorDashboardController@doctor_all_patients');
    //view patient record
    Route::get('view_patient_record/{id}', 'Api\DoctorsApi\DoctorDashboardController@view_patient_record');
    Route::get('patient_imagings_history/{id}', 'Api\DoctorsApi\DoctorDashboardController@fetch_pending_imagings');
    Route::get('patient_labs_history/{id}', 'Api\DoctorsApi\DoctorDashboardController@fetch_pending_labs');


    //view DocProfile
    Route::get('view_doctor_profile', 'Api\DoctorsApi\ProfileController@view_DocProfile');
    //doctor profile picture upload
    Route::post('doc_profile_picture', 'Api\DoctorsApi\ProfileController@profile_picture');
    //doctor phone number update
    Route::post('doc_phone_number', 'Api\DoctorsApi\ProfileController@phone_number');
    //doctor update profileInfo
    Route::post('doc_update_profileInfo', 'Api\DoctorsApi\ProfileController@update_profileInfo');
    Route::post('doc_certificate_update', 'Api\DoctorsApi\ProfileController@doc_certificate_update');
    //doctor change password
    Route::post('doc_change_password', 'Api\DoctorsApi\ProfileController@change_password');
    //doctor activity
    Route::get('doc_activity', 'Api\DoctorsApi\ProfileController@doc_activity');
    //doctor certificate
    Route::get('doc_certificate', 'Api\DoctorsApi\ProfileController@doc_certificate');
    //Doctor Session list
    Route::get('all_session', 'Api\DoctorsApi\SessionController@all_session');
    Route::post('session_search', 'Api\DoctorsApi\SessionController@session_search');
    //session details
    Route::get('session_details/{session_id}', 'Api\DoctorsApi\SessionController@session_details');
    //doctor order
    Route::get('doctor_order', 'Api\DoctorsApi\OrderController@doctor_order');
    //doctor order detail
    Route::get('doctor_order_detail/{id}', 'Api\DoctorsApi\OrderController@doctor_order_detail');
    //doctor search order
    Route::post('doctor_search_order', 'Api\DoctorsApi\OrderController@doctor_search_order');
    //doctor wallet
    Route::get('doctor_wallet', 'Api\DoctorsApi\PaymentController@doctor_wallet');
    //doctor wallet dateFilter
    Route::post('doctor_wallet_dateFilter', 'Api\DoctorsApi\PaymentController@doctor_wallet_dateFilter');
    //doctor status
    Route::post('doctor_status', 'Api\DoctorsApi\DoctorDashboardController@doctor_status');
    //get doctor status
    Route::get('get_doctor_status', 'Api\DoctorsApi\DoctorDashboardController@get_doctor_status');
    //doctor earning search
    Route::post('doctor_earning_search', 'Api\DoctorsApi\DoctorDashboardController@doctor_earning_search');
    //all doctor lab reports
    Route::get('all_doctor_lab_reports', 'Api\DoctorsApi\LabController@all_doctor_lab_reports');
     //view doctor lab report
    Route::get('pat_lab_report/{id}', 'Api\DoctorsApi\LabController@pat_lab_report');
    //doctor online lab approvals
    Route::get('doctor_online_lab_approvals', 'Api\DoctorsApi\LabController@doctor_online_lab_approvals');
    //pending onlinelab
    Route::post('pending_onlinelab_search', 'Api\DoctorsApi\LabController@pending_onlinelab');
    //online lab order accept
    Route::post('online_lab_order_accept', 'Api\DoctorsApi\LabController@online_lab_order_accept');
    //online lab order cancel
    Route::post('online_lab_order_cancel', 'Api\DoctorsApi\LabController@online_lab_order_cancel');
    //approved labs
    Route::get('approved_labs', 'Api\DoctorsApi\LabController@approved_labs');
    //approved labs search
    Route::post('approved_labs_search', 'Api\DoctorsApi\LabController@approved_labs_search');
    //doctor_pending_requisitions
    Route::get('doctor_pending_requisitions', 'Api\DoctorsApi\LabController@doctor_pending_requisitions');
    //doctor lab requisition pending search
    Route::post('doc_lab_requisition_pending_search', 'Api\DoctorsApi\LabController@doc_lab_requisition_pending_search');
    //doc lab requisitions
    Route::get('doc_lab_requisitions', 'Api\DoctorsApi\LabController@doc_lab_requisitions');
    //recent lab buy
    Route::get('doc_recent_lab_buy', 'Api\DoctorsApi\LabController@recent_lab_buy');
    //all notifictions
    Route::get('all_notifiction', 'Api\NotificationController@all_notifictions');
    //read all notification
    Route::get('read_all_notification', 'Api\NotificationController@read_all_notification');
    // view unread notifictions
    Route::get('view_unread_notifictions', 'Api\NotificationController@view_unread_notifictions');
    //read notification
    Route::post('read_notification', 'Api\NotificationController@read_notification');
    // Route::get('remove_reminder/{session_id}', 'Api\NotificationController@remove_reminder');

});
Route::middleware(['auth:sanctum','pat_restrict','apiDoctorIsActive',])->group(function(){
    // Doctor Video Screen
    //doctor video
     //click on join button
    Route::post('join', 'Api\DoctorsApi\SessionController@waitingPatientJoinCall');
    Route::get('doctor_video/{session_id}', 'Api\Video\DoctorVideoScreenController@doctor_video');
    Route::post('doctor_end_session', 'Api\Video\DoctorVideoScreenController@doctor_end_session');
    Route::post('add_medicine', 'Api\Video\DoctorVideoScreenController@add_medicine');
    Route::post('check_lab_aoes', 'Api\Video\DoctorVideoScreenController@check_aeos');
    Route::post('aoes_submit', 'Api\Video\DoctorVideoScreenController@aoes_submit');
    Route::post('add_lab', 'Api\Video\DoctorVideoScreenController@add_lab');
    Route::post('fetch_state_zipcode', 'Api\Video\DoctorVideoScreenController@fetch_state_zipcode');
    Route::post('add_imaging', 'Api\Video\DoctorVideoScreenController@add_imaging');
    Route::post('remove_prescription', 'Api\Video\DoctorVideoScreenController@remove_prescription');
    Route::post('get_prescribe_item_list', 'Api\Video\DoctorVideoScreenController@get_prescribe_item_list');
    Route::post('provider_notes_update', 'Api\Video\DoctorVideoScreenController@provider_notes_update');
    Route::post('recommendation_display', 'Api\Video\DoctorVideoScreenController@recommendation_display');
    Route::post('add_dosage', 'Api\Video\DoctorVideoScreenController@add_dosage');
    Route::post('get_med_detail', 'Api\Video\DoctorVideoScreenController@get_med_detail');
    Route::post('store_cart_for_user', 'Api\Video\DoctorVideoScreenController@store_cart_for_user');

    Route::post('recommendation_store', 'Api\Video\RecommendationController@recommendation_store');
    Route::get('prescriptions_dosageInfo/{session_id}', 'Api\Video\RecommendationController@prescriptions_dosageInfo');
    //load diagnosis video page
    Route::get('doctor_diagnosis_doctor_side/{session_id}', 'Api\Video\DoctorVideoScreenController@diagnosis_doctor_side');
    Route::post('doctor_patient_symptoms', 'Api\Video\DoctorVideoScreenController@patient_symptoms');
    Route::post('doctor_patient_medical_history', 'Api\Video\DoctorVideoScreenController@patient_medical_history');
    Route::post('doctor_patient_current_medication', 'Api\Video\DoctorVideoScreenController@patient_current_medication');
    Route::post('doctor_patient_family_history', 'Api\Video\DoctorVideoScreenController@patient_family_history');
    Route::post('doctor_patient_imaging_report', 'Api\Video\DoctorVideoScreenController@patient_imaging_report');
    Route::post('patient_visit_history', 'Api\Video\DoctorVideoScreenController@patient_visit_history');
    Route::post('doctor_patient_lab_report', 'Api\Video\DoctorVideoScreenController@patient_lab_report');
    Route::get('medician_categories', 'Api\Video\DoctorVideoScreenController@medician_categories');
    Route::post('medician_categories_search', 'Api\Video\DoctorVideoScreenController@medician_categories_search');
    Route::get('medician_based_on_categories/{id}', 'Api\Video\DoctorVideoScreenController@medician_based_on_categories');
    Route::get('lab_test', 'Api\Video\DoctorVideoScreenController@lab_test');
    Route::post('lab_test_search', 'Api\Video\DoctorVideoScreenController@lab_test_search');
    Route::post('imaging_state_by_zipcode', 'Api\Video\DoctorVideoScreenController@imaging_state_by_zipcode');
    Route::get('imaging_category', 'Api\Video\DoctorVideoScreenController@imaging_category');
    Route::get('imaging_product_video', 'Api\Video\DoctorVideoScreenController@imaging_product');
    Route::post('imaging_category_product', 'Api\Video\DoctorVideoScreenController@imaging_category_product');
    Route::get('refer_doctor/{specialization_id}', 'Api\Video\DoctorVideoScreenController@refer_doctor');
    Route::post('refer_doctor_search/{specialization_id}', 'Api\Video\DoctorVideoScreenController@refer_doctor_search');
    Route::post('refer_to_doctor', 'Api\Video\DoctorVideoScreenController@refer_to_doctor');
    Route::post('cancelReferal', 'Api\Video\DoctorVideoScreenController@cancelReferal');
});
   //=================================================//
            //Doctor PROFILE END HERE
    //================================================//

//vide screen apis
Route::middleware('auth:sanctum')->group(function (){

});
// Login / Registration

Route::post("login_from_app", [RestApiController::class, 'index']);
Route::post("signup_from_app", [RestApiController::class, 'createNewUser']);

// All Rest API's Links with Token Authorization
//Route::group(['middleware' => 'auth:sanctum'], function () {

//All secure URL's
Route::get('/getProductParentCategories', [RestApiController::class, 'getProductParentCategories']);
Route::get('/getProductSubCategories', [RestApiController::class, 'getProductSubCategories']);
Route::get('/getProducts', [RestApiController::class, 'getProducts']);
Route::get('/getSearchProducts', [RestApiController::class, 'getSearchProducts']);
Route::get('/getOrders', [PharmacyController::class, 'get_orders_for_app']);
Route::post('/createOrder', [PharmacyController::class, 'create_order_from_app']);
Route::get('/getMedicalProfile', [RestApiController::class, 'getMedicalProfile']);
Route::get('/getPatientSessions', [RestApiController::class, 'getPatientSessions']);
Route::get('/getPrescribedMedicines', [RestApiController::class, 'getPrescribedMedicinesFromDoctor']);
Route::get('/getUserProfile', [RestApiController::class, 'getUserProfile']);
Route::put('/updateUserProfile/{id}', [RestApiController::class, 'updateUserProfile']);
Route::get('/getPatientAppointment', [RestApiController::class, 'getAppointment']);
Route::post('/createAppointment', [RestApiController::class, 'createAppointment']);
Route::get('/getAppointmentDoctors', [RestApiController::class, 'getAppointmentDoctors']);
Route::get('/getAppointmentDoctorsAvailability', [RestApiController::class, 'getAppointmentDoctorsAvailability']);
Route::get('/getPatientPreviousDoctors', [RestApiController::class, 'getPatientPreviousDoctors']);
Route::get('/getOnlineDoctors', [RestApiController::class, 'getOnlineDoctors']);
Route::post('/createSymptomsForSession', [RestApiController::class, 'createSymptomsForSession']);
Route::post('/createPaymentForSession', [RestApiController::class, 'createPaymentForSession']);
Route::post('/createSentInvitation', [RestApiController::class, 'createSentInvitation']);
// Route::post('/joinSessionFromDoctor', [RestApiController::class, 'createSessionStart']);
Route::get('/getVideoLinks', [RestApiController::class, 'getVideoLinks']);
Route::get('/getDoctorStatus', [RestApiController::class, 'getDoctorStatus']);
Route::put('/updateDoctorStatus/{id}', [RestApiController::class, 'updateDoctorStatus']);
Route::get('/getPatientDetailForSession', [RestApiController::class, 'getPatientDetailForSession']);
Route::get('/getSessionDetailsByID', [RestApiController::class, 'getSessionDetailsByID']);
Route::put('/updateNotesDiagnosisToPatient/{id}', [RestApiController::class, 'updateNotesDiagnosisToPatient']);
Route::get('/getNearbyLabsPharmacy', [RestApiController::class, 'getNearbyLabsPharmacy']);
Route::put('/updateDiagnosisAndNotes/{id}', [RestApiController::class, 'updateDiagnosisAndNotes']);
Route::post('/addPrescribedMedicines', [RestApiController::class, 'addPrescribedMedicines']);
Route::delete('/deletePrescribedMedicines/{id}', [RestApiController::class, 'deletePrescribedMedicines']);
Route::put('/updatePrescribedMedicines/{id}', [RestApiController::class, 'updatePrescribedMedicines']);
Route::post('/endVideoSession', [RestApiController::class, 'endVideoSession']);
Route::get('/getLocationsByZipcodeImaging', [RestApiController::class, 'getLocationsByZipcodeImaging']);
Route::get('/getPriceByLocationImaging', [RestApiController::class, 'getPriceByLocationImaging']);
Route::post('/setDoctorAvailability', [RestApiController::class, 'setDoctorAvailability']);
Route::put('/updateDoctorAvailability/{id}', [RestApiController::class, 'updateDoctorAvailability']);
Route::get('/getPatientByDoctor', [RestApiController::class, 'getPatientByDoctor']);
Route::get('/getPatientQueue', [RestApiController::class, 'getPatientQueue']);
Route::get('/getCityStateByZipcode', [RestApiController::class, 'getCityStateByZipcode']);
Route::get('/getSpecialization', [RestApiController::class, 'getSpecialization']);
Route::post('/updateMedicalHistory', [RestApiController::class, 'updateMedicalHistory']);
Route::post('/editProfilePicture', [RestApiController::class, 'editProfilePicture']);
//});

// Payment API Internal
Route::get('/pay', 'PaymentController@new_getPaymentToken');
Route::get('/customerId','PaymentController@new_createCustomerProfile');
// Route::get('/getProfile','PaymentController@new_getCustomerProfile');
Route::get('/getProfile','PaymentController@new_createPaymentwithCustomerProfile');
Route::post('/proceedToPay', 'PaymentController@proceedToPay');

// EFAX API Internal
Route::post("/proccedToEFax", "RecommendationController@proccedToEFax");

// Isabel API Route
Route::get('/getAgeGroup','IsabelController@age_group');
Route::get('/getRegion','IsabelController@region');
Route::get('/getCountries','IsabelController@countries');
Route::get('/getProQuery','IsabelController@proquery');
Route::get('/getSymtoms','IsabelController@getsymptoms');



// agora route
Route::post('/aquire', [AgoraController::class, 'aquire'])->name('aquire');
Route::post('/stop', [AgoraController::class, 'stop'])->name('stop');
Route::post('/aynalatics', [AgoraController::class, 'getAgoraAynalatics'])->name('aynalatics');
