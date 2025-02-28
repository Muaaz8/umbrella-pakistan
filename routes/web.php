<?php

use App\Events\RealTimeMessage;
use App\Events\AppEvent;
use App\Http\Controllers\AllProductsController;
use App\Http\Controllers\WelcomeController;
use App\Mail\prescriptionMail;
use App\Mail\ReminderAppointmentPatientMail;
use App\Mail\testingMail;
use App\Session;
use App\User;
use App\State;
use App\Prescription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Events\DoctorJoinedVideoSession;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Mail\AdviyatOrderEmail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/api/pasasword/reset/{token}/{email}','ProfileController@view_Api_password');
Route::put('/api/pasasword/update','ProfileController@update_Api_password')->name('apiUpdatePassword');
Route::get('sitemap', function () {
    SitemapGenerator::create('https://www.umbrellamd.com')->writeToFile(public_path('sitemap.xml'));
    return "sitemap created";
});

Route::get('testing/mail',function(){
    // Mail::to('adviyat@yopmail.com')->send(new AdviyatOrderEmail());
    $inclinic_data = \App\Models\InClinics::with(['user','prescriptions'])->where('id', 1)->first();
    foreach($inclinic_data->prescriptions as $pres){
        if($pres->type == "medicine"){
            $pres->med_details = DB::table('tbl_products')->where('id',$pres->medicine_id)->first();
        }elseif($pres->type == "lab-test"){
            $pres->lab_details = DB::table('quest_data_test_codes')->where('TEST_CD',$pres->test_id)->first();
        }elseif($pres->type == "imaging"){
            $pres->imaging_details = DB::table('quest_data_test_codes')->where('TEST_CD',$pres->imaging_id)->first();
        }
    }

    $user_data = $inclinic_data->user;

    $pdf = PDF::loadView('prescriptionPdf',compact('inclinic_data'));
    Mail::send('emails.prescriptionEmail', ['user_data'=>$user_data], function ($message) use ($pdf) {
        $message->to('zayan@yopmail.com')->subject('patient prescription')->attachData($pdf->output(), "prescription.pdf");
    });

    dd('ok');
});

Route::get('/app_pat_video/{id}',function($id){
    $session = DB::table('sessions')->where('id',$id)->first();
    return view('dashboard_patient.Video.app_video',compact('session'));
});
Route::get('/app_doc_video/{id}',function($id){
    $session = DB::table('sessions')->where('id',$id)->first();
    return view('dashboard_doctor.Video.app_video',compact('session'));
});

Route::get('/seed',function(){
    $page = DB::table('pages')->insertGetId([
        'name' => 'Our Doctor',
        'url' => '/our-doctor',
    ]);
    DB::table('section')->insert([
        'page_id'=> $page,
        'section_name'=> 'upper-text',
        'sequence_no'=> 1,
        'created_at'=>now(),
        'updated_at'=>now(),
    ]);
});
Route::post('/upload-image-endpoint','SEOAdminController@upload_image_endpoint')->name('upload_image_endpoint');
Route::get('/doctor-profile/{id}',function($id){
    $doctor = DB::table('users')->where('id',$id)->first();
    if($doctor){
        $doctor->details = DB::table('doctor_details')->where('doctor_id',$id)->first();
        $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
        $doctor->schedules = DB::table('doctor_schedules')->where('doctorID',$id)->get();

        foreach($doctor->schedules as $sc){
            $sc->from_time = User::convert_utc_to_user_timezone($id,$sc->from_time)['time'];
            $sc->to_time = User::convert_utc_to_user_timezone($id,$sc->to_time)['time'];
        }

        if($doctor->details){
            $doctor->details->certificates = json_decode($doctor->details->certificates);
            $doctor->details->conditions = json_decode($doctor->details->conditions);
            $doctor->details->procedures = json_decode($doctor->details->procedures);
        }
    }
    $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
    return view('website_pages.doc_profile_page',compact('doctor'));
});

Route::get('/our-doctors',function(){
    $specializations = DB::table('specializations')
        ->join('users','specializations.id','users.specialization')
        ->select('specializations.*')
        ->groupBy('specializations.id')
        ->get();
    $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->orderBy('id','desc')
        ->paginate(8);
    foreach($doctors as $doctor){
        if ($doctor) {
            $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
        }
    }
    return view('website_pages.doc_profile_page_list',compact('doctors' , 'specializations'));
})->name('doc_profile_page_list');

Route::get('/our-doctors/{name}',function($name){

    if($name == "0"){
        $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->whereNull('zip_code')
        ->orderBy('id','desc')
        ->get();
        foreach($doctors as $doctor){
            $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
        }
        return json_encode($doctors);
    }

    if($name == "1"){
        $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->whereNotNull('zip_code')
        ->orderBy('id','desc')
        ->get();
        foreach($doctors as $doctor){
            $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
        }
        return json_encode($doctors);
    }

    if($name == "2"){
        $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->orderBy('id','desc')
        ->get();
        foreach($doctors as $doctor){
            $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
        }
        return json_encode($doctors);
    }

    if($name == "3"){
        $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('status','online')
        ->where('active','1')
        ->orderBy('id','desc')
        ->get();
        foreach($doctors as $doctor){
            $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
            $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
            $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
        }
        return json_encode($doctors);
    }

    $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->where('name','LIKE','%'.$name.'%')
        ->orWhere('last_name','LIKE','%'.$name.'%')
        ->orderBy('id','desc')
        ->get();
    foreach($doctors as $doctor){
        $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
    }
    return json_encode($doctors);
});

Route::get('/american-doctors', function(){
    return view('website_pages.american_docs_page');
}
)->name('american-doctors');

Route::get('/filter/doctors/{id}', function($id){

    $doctors;

    if($id == "0"){
        $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->orderBy('id','desc')
        ->get();
    }else{
        $doctors = DB::table('users')
        ->where('user_type','doctor')
        ->where('active','1')
        ->where('status','!=','ban')
        ->where('specialization',$id)
        ->orderBy('id','desc')
        ->get();
    }

    foreach($doctors as $doctor){
        $doctor->details = DB::table('doctor_details')->where('doctor_id',$doctor->id)->first();
        $doctor->user_image = \App\Helper::check_bucket_files_url($doctor->user_image);
        $doctor->specializations = DB::table('specializations')->where('id',$doctor->specialization)->first();
    }
    return json_encode($doctors);
});

Route::get('/screen_sharing','UserController@sc_share')->name('sc_share');
Route::post('/create/screen_sharing','UserController@create_sc_sh')->name('create_sc_sh');
Route::get('/host/join/video/{id}','UserController@host_join_vid')->name('host_join_vid');
Route::get('/guest/join/video/{id}','UserController@guest_join_vid')->name('guest_join_vid');
Route::post('/host/video/{id}','UserController@host_video')->name('host_video');
Route::post('/guest/video/{id}','UserController@guest_video')->name('guest_video');
Route::post('/share/screen','UserController@share_sc')->name('share_sc');

Route::get('excel','UserController@read_excel');

// Route::get('/checkApiRes',function(){
//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//       CURLOPT_URL => 'https://apiscsandbox.isabelhealthcare.com/v3/convert_to_pro_query?language=en&web_service=json&query=Abdominal%2520aortic%2520aneurysm,Acne,Acute%2520cholecystitis',
//       CURLOPT_RETURNTRANSFER => true,
//       CURLOPT_ENCODING => '',
//       CURLOPT_MAXREDIRS => 10,
//       CURLOPT_TIMEOUT => 0,
//       CURLOPT_FOLLOWLOCATION => true,
//       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//       CURLOPT_CUSTOMREQUEST => 'GET',
//       CURLOPT_HTTPHEADER => array(
//         'authorization: krJM2RgWk7W5L2b5j3X8oYJTLey4E5AJ',
//         'Content-Type: application/json',
//         'Cookie: _session_id=e42ba831ad6182e1ae8b9728bd0a5c67; language=en'
//       ),
//     ));
//     $response = curl_exec($curl);
//     curl_close($curl);
//     echo $response;

//     dd($response);
// });

Route::get('phpmyinfo', function () { phpinfo(); })->name('phpmyinfo');


Route::get('error/{code}','PaymentController@errorCodeMessage');
Route::get('pdfcontract/{slug}','AdminController@pdfview_contract');

Route::post('/order/done', 'PharmacyController@create_new_order')->name('order.done');
Route::post('/order/payment/done', 'PharmacyController@authorize_create_new_order')->name('order.payment');


Route::get('/meezan/payment', 'MeezanPaymentController@payment')->name('meezan.store');
Route::get('/meezan/payment/return', 'MeezanPaymentController@payment_return')->name('meezan.return');
Route::get('/meezan/payment/order/return', 'PharmacyController@order_payment_return')->name('meezan.return');

Route::get('doc/video/page/{id}', 'VideoController@doctorVideo')->name('doc_video_page')->middleware('no_cache');
Route::get('pat/video/page/{id}', 'VideoController@patientVideo')->name('pat_video_page')->middleware('no_cache');
Route::post('load_symtems_video_page', 'VideoController@load_symtems_video_page');
Route::post('load_current_medication_video_page', 'VideoController@load_current_medication_video_page');
Route::post('load_session_record_video_page', 'VideoController@load_session_record_video_page');
Route::post('load_medical_history_video_page', 'VideoController@load_medical_history_video_page');
Route::post('load_family_history_video_page', 'VideoController@load_family_history_video_page');
Route::post('load_imaging_report_video_page', 'VideoController@load_imaging_report_video_page');
Route::post('load_lab_report_video_page', 'VideoController@load_lab_report_video_page');
Route::post('check_lab_aoes', 'VideoController@check_lab_aoes');
Route::post('new_add_labtest_aoes_into_db', 'VideoController@new_add_labtest_aoes_into_db');
Route::post('fetch_user_state_by_zipcode', 'VideoController@fetch_user_state_by_zipcode');
Route::post('/new_get_imaging_products_by_category', 'AllProductsController@new_get_imaging_products_by_category');
Route::post('/add_imging_pro', 'AllProductsController@add_imging_pro');
Route::post('/get_specializations_doctors', 'VideoController@get_specializations_doctors');

Route::get('/sessions/details/{id}', 'SessionController@dash_session_detail_current')->name('session_detail_current');

Route::post('appointment/payment/authorize', 'AppointmentController@api_payment_appointment')->name('payment_appointment1');
Route::post('session/payment/authorize', 'AppointmentController@api_payment_session')->name('payment_session1');
Route::get('admin/coupon/get/{category}/{sub}', 'CouponController@get_sub_category_product');
Route::get('admin/coupon/delete/{id}', 'CouponController@destroy');
Route::get('/admin/coupon/check', 'CouponController@check');

// Route::group(['middleware' => ['auth', 'user-email-verify', 'activeUser']], function () {
//     Route::get('/conference_video',function(){
//         return view('dashboard_doctor.Video.doc_conference');
//     });
// });


Route::get('/wrong/address',function(){
    return view('errors.Oops');
})->name('wrong_address');

Route::get('admin/dash', 'HomeController@new_admin_index')->middleware('admin_auth');
Route::get('pharmacy/editor/dash', 'HomeController@new_pharm_editor_index')->name('pharmacy_editor_dash')->middleware('phar_auth');
Route::get('pharmacy/admin/dash', 'HomeController@new_pharm_admin_index')->name('pharmacy_admin_dash')->middleware('phar_auth');
//open route
Route::get(
    '/clear',
    function () {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        //  \Illuminate\Support\Facades\Artisan::call('websockets:clean');
        dd('ok');
    }
);

Route::get('passwordChange', function () {
    $pass = Hash::make('uhcs@1234');
    DB::table('users')->update(['password' => $pass]);
    dd('done change all user passwords');
});

Route::post('/get_states_cities', 'UserController@get_states_cities');
Route::post('/load_session_diagnosis', 'VideoController@load_diagnosis_video_page');
Route::post('/load_psych_question', 'VideoController@load_psych_question');
Route::post('get_card_details', 'PharmacyController@get_card_details');
Route::post('get_card_shipping_details', 'PharmacyController@get_card_shipping_details');

Route::post('/send/guest/message','ContactController@send_guest_msg')->name('send_guest_msg');
Route::post('/get/guest/msgs','ContactController@get_guest_msgs')->name('get_guest_msgs');
Route::get('/get/chatbot/questions','ContactController@get_chatbot_questions')->name('get_chatbot_questions');
Route::get('/new/patient/not/join/call/{id}', 'VideoController@patient_NotJoiningCall');

Route::get('search_items/{text}', function($text){

    $products = DB::table('tbl_products')
                ->select('id', 'name', 'slug')
                ->where('name', 'like', '%' . $text . '%')
                ->where('mode' , 'medicine')
                ->limit(20)
                ->get();


    $testCodes = DB::table('quest_data_test_codes')
                ->select('TEST_CD', 'TEST_NAME', 'SLUG')
                ->where('TEST_NAME', 'like', '%' . $text . '%')
                ->limit(20)
                ->get();

    return response()->json([
        'products' => $products,
        'test_codes' => $testCodes,
    ]);
});



Route::group(['middleware' => 'redirecttovideo'], function () {
    Auth::routes();
    Route::get('admin/editor/details/{id}','AdminController@dash_editor_details')->name('dash_editor_details');
    Route::get('order/complete/{id}', 'PharmacyController@orderComplete')->name('order.complete');
    Route::post('fetch_pharmacy_item_by_category', 'unAuthController@fetchPharmacyItemByCategory')->name('fetch_pharmacy_item_by_category');
    Route::post('search_pharmacy_item', 'unAuthController@searchPharmacyItem')->name('search_pharmacy_item');
    Route::post('search_pharmacy_item_by_category', 'unAuthController@searchPharmacyItemByCategory')->name('search_pharmacy_item_by_category');
    Route::post('search_imaging_item_by_category', 'unAuthController@searchImagingItemByCategory')->name('search_imaging_item_by_category');
    Route::post('search_lab_item_by_category', 'unAuthController@searchLabItemByCategory')->name('search_lab_item_by_category');
    Route::post('search_lab_item', 'unAuthController@searchLabItem')->name('search_lab_item');
    Route::post('search_imaging_item', 'unAuthController@searchImagingItem')->name('search_imaging_item');
    Route::post('fetch_labtest_item_by_category', 'unAuthController@fetchLabtestItemByCategory')->name('fetch_labtest_item_by_category');
    Route::post('fetch_imaging_item_by_category', 'unAuthController@fetchImagingItemByCategory')->name('fetch_imaging_item_by_category');
    Route::get('/labtests', 'PharmacyController@index')->name('labs');
    Route::get('/pharmacy', 'PharmacyController@index')->name('pharmacy');
    Route::get('/imaging', 'PharmacyController@index')->name('imaging');
    Route::get('/pharmacy/{slug}', 'PharmacyController@index')->name('pharmacy.category');
    Route::get('/labtests/{slug}', 'PharmacyController@index')->name('slug.labtest');
    Route::get('/imaging/{slug}', 'PharmacyController@index')->name('slug.imaging');
    Route::get('/pain-management/{slug}', 'PharmacyController@index')->name('pain-management');
    Route::get('/pain-management', 'PharmacyController@index')->name('pain.management');
    Route::post("/getDataByZipCode", "unAuthController@getDataByZipCode");
    Route::post("/getCityStateByZipCode", "MapMarkersController@getCityStateByZipCode");
    Route::get('patient_register', 'Auth\RegisterController@patient_register')->name('pat_register');
    Route::get('doctor_register', 'Auth\RegisterController@doctor_register')->name('doc_register');
    Route::get('assistant_doctor_register', 'Auth\RegisterController@nurse_register')->name('assistant_doctor_register');
    Route::post('/verify_email_unique', 'Auth\RegisterController@verify_email_unique');
    Route::post('/verify_nip_unique', 'Auth\RegisterController@verify_nip_unique');
    Route::post('/verify_username_unique', 'Auth\RegisterController@verify_username_unique');

    Route::post('/symptom_checker_cookie_store',"unAuthController@symptom_checker_cookie_store");
    Route::post('/symptom_chat',"unAuthController@chat");
    Route::post('/chat_done',"unAuthController@done");
    Route::get('/check_cookie',"unAuthController@check_cookie");
    Route::get('/symptom_checker',"unAuthController@symptom_checker")->name('symptom_checker');
    Route::post('/get_symptom',"unAuthController@get_symptom")->name('get_symptom');
    Route::get('/forget_cookie',"unAuthController@forget_cookie");

    Route::get('/', 'WelcomeController@index')->name('welcome_page');
    Route::get('/landing-page', 'WelcomeController@landing_page')->name('landing_page');
    Route::get('/therapy/events/search', 'WelcomeController@therapy_events_search')->name('therapy_events_search');
    Route::post('/get_physical_location', 'WelcomeController@get_physical_location')->name('get_physical_location');
    Route::post('/get_physical_location_by_state', 'WelcomeController@get_physical_location_by_state')->name('get_physical_location_by_state');
    Route::post('/get_physical_location_by_city', 'WelcomeController@get_physical_location_by_city')->name('get_physical_location_by_city');
    Route::post('/get_physical_location_by_id', 'WelcomeController@get_physical_location_by_id')->name('get_physical_location_by_id');

    Route::get('/about-us', function () {
        $url = url()->current();
        $tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        return view('website_pages.new_pakistan_about',compact('tags','title'));
    })->name('about_us');
    Route::get('/contact-us', function () {
        $url = url()->current();
        $tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        return view('website_pages.new_pakistan_contact_us',compact('tags','title'));
    })->name('contact_us');
    Route::post('/contact_us', 'WelcomeController@contact_us')->name('contact_submit');
    Route::post('/contact', 'ContactController@contact_sub');
    Route::get('/privacy_policy', 'WelcomeController@policy_privacy')->name('privacy_policy');
    Route::get('/terms_of_use', 'WelcomeController@terms_of_use')->name('terms_of_use');
    Route::get('/coming_soon', function () {
        return view('coming_soon');
    })->name('coming_soon');
    Route::get('email/verification/{user_id}/{hash}', "EmailVerificationController@email_verification");

    Route::post('email/verification', "EmailVerificationController@otp_verification")->name('otp_verification');

    Route::get('/forgot_password', function () {
        return view('auth.passwords.email');
    })->name('forgot_password');
    Route::get('/e-visit', function () {
        $url = url()->current();
        $tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        return view('website_pages.new_pakistan_e-visit',compact('tags','title'));
    })->name('e-visit');
    Route::get('/careers', function () {
        return view('careers');
    })->name('careers');
    Route::get('/our_doctors', 'DoctorController@our_doctors')->name('our_doctors');
    Route::get('/faq', 'TblFaqController@website_faqs')->name('faq');
    Route::get('/faq/{id}', 'TblFaqController@show_one')->name('faq_one');
    Route::get('/error', function () {
        return view('errors.page_expired');
    })->name('error');
    Route::get('/errors/{code}', 'ErrorController@errorPage')->name('errors');

    Route::get('health_topic', 'PharmacyController@healthTopic')->name('health_topic');
    Route::get('health_topic/{name}', 'PharmacyController@healthTopicSingle')->name('singleTopic');
    // PharmacyController
    Route::get('/get_cities_by_state/{state_code}', 'PharmacyController@get_cities_by_state');
    Route::get('/get_maps_locations/{zipCode}', 'PharmacyController@get_lang_long');
    Route::get('/get_near_locations/{lat}/{long}', 'PharmacyController@get_near_location');
    Route::get('/product/{type}/{slug}', 'PharmacyController@single_product_oldroute')->name('single_product_view');
    Route::get('/labtest/{slug}', 'PharmacyController@single_product')->name('single_product_view_labtest');
    Route::get('/medicines/{slug}', 'PharmacyController@single_product')->name('single_product_view_medicines');
    Route::get('/imagings/{slug}', 'PharmacyController@single_product')->name('single_product_view_imagings');
    Route::get('/primary-care', 'PharmacyController@index')->name('primary');
    Route::get('/substance-abuse/{slug}', 'PharmacyController@index')->name('substance');
    Route::get('/psychiatry/{slug}', 'PharmacyController@index')->name('psychiatry');
    Route::get('/therapy-session/{slug}', 'PharmacyController@therapy_single')->name('therapy_session');
    Route::get('/provider_contract/{slug}', 'AdminController@view_contract')->name('open_contract');
    Route::post('/sign_contract/{slug}', 'AdminController@sign_contract')->name('sign_contract');
});
Route::post('resend/mail', 'PatientController@resendVerificationMail')->name('resend');
Route::post('upload/license', 'DoctorController@upload_license')->name('upload_license');
//login able Route
Route::group(['middleware' => ['auth', 'user-email-verify', 'activeUser']], function () {

    Route::get('add_discount_in_cart/{slug}','PatientController@add_discount_in_cart')->name('add_discount_in_cart');
    //Therapy Events Routs
    // Route::get('therapy/events','PatientController@therapy_events')->name('therapy_events');
    // Route::get('therapy/event/payment/{id}','PatientController@therapy_event_payment')->name('therapy_event_payment');
    // Route::get('/patient/therapy/{id}','PatientController@patient_therapy_video')->name('patient_therapy_video');
    // Route::get('/doctor/therapy/{id}','DoctorController@doctor_therapy_video')->name('doctor_therapy_video');
    // Route::get('/end/therapy/{id}','DoctorController@end_therapy_video')->name('end_therapy_video');
    // Route::post('therapy/payment/authorize', 'PatientController@api_payment_therapy')->name('payment_therapy');
    Route::get('view/psychiatrist/info/form','DoctorController@view_psychiatrist_form')->name('psychiatrist_form');
    Route::get('/admin/doctor/profile/management','DoctorController@admin_doctor_profile_management')->name('admin_doctor_profile_management');
    Route::get('/seo/doctor/profile/management','DoctorController@seo_doctor_profile_management')->name('seo_doctor_profile_management');
    Route::get('doctor/profile/management','DoctorController@doctor_profile_management')->name('doctor_profile_management');
    // Route::get('/get_doctor_details','DoctorController@get_doctor_details')->name('get_doctor_details');
    Route::get('/get_doctor_details/{doc_id}','DoctorController@get_doctor_details_by_id')->name('get_doctor_details_by_id');
    Route::get('edit/psychiatrist/info/form/{id}','DoctorController@edit_psychiatrist_form')->name('edit_psychiatrist_form');
    Route::post('update/psychiatrist/info/form','DoctorController@update_therapy_event')->name('update_therapy_event');
    Route::get('patient/end/conference/call/{id}','PatientController@end_conference_call')->name('end_conference_call');
    Route::post('submit/psychiatrist/info/form','DoctorController@submit_psychiatrist_form')->name('submit_psychiatrist_form');
    Route::post('raise/hand','PatientController@raise_hand')->name('raise_hand');

    Route::get('/chat/support','ContactController@chat_support')->name('chat_support');
    Route::get('/chat/support/account/setting','ContactController@chat_account_setting')->name('chat_account_setting');
    Route::post('send/message','ContactController@send_msg')->name('send_msg');
    Route::post('chat/status','ContactController@chat_status')->name('chat_status');
    Route::post('chat/done','ContactController@chat_done')->name('chat_done');
    Route::get('chatbot/questions','ContactController@chatbot_questions')->name('chatbot_questions');
    Route::post('add/chatbot/question','ContactController@add_chatbot_question')->name('add_chatbot_question');
    Route::get('/del/chatbot/question/{id}','ContactController@del_chatbot_question')->name('del_chatbot_question');
    Route::get('view/chat/{id}','ContactController@view_chat')->name('view_chat');
    Route::post('coupon/apply/discount', 'CouponController@apply_discount');
    Route::get('/editor/details/{id}','AdminController@editor_details')->name('editor_details');
    Route::get('lab_editor/change_status/{id}','AdminController@lab_editor_status')->name('lab_editor_status');
    Route::post('/add/editor','AdminController@add_editor')->name('add_editor');
    Route::get('/seo/admin/dash','SEOAdminController@seo_admin_dash')->name('seo_admin_dash');
    Route::get('/seo/admin/account/settings','SEOAdminController@seo_admin_acc_setting')->name('seo_admin_acc_setting');
    Route::post('/insert/meta/tag','SEOAdminController@save_meta_tag')->name('save_meta_tag');
    Route::get('/pages/meta/tag','SEOAdminController@pages_meta_tag')->name('pages_meta_tag');

    Route::get('/get/sections/by/page/{id}','SEOAdminController@get_sections_by_page_id')->name('get_sections_by_page_id');
    Route::get('/get/sequences/by/section/{id}','SEOAdminController@get_sequences_by_section_id')->name('get_sequences_by_section_id');
    Route::get('/get/content/by/content_id/{id}','SEOAdminController@get_content_by_content_id')->name('get_content_by_content_id');

    Route::get('/pages','SEOAdminController@pages')->name('pages');
    Route::get('/edit/page/{id}','SEOAdminController@edit_page')->name('edit_page');
    Route::post('/update/pages/{id}','SEOAdminController@update_page')->name('update_page');

    Route::get('/top_banner','SEOAdminController@top_banner')->name('top_banner');
    Route::get('/edit/top_banner/{id}','SEOAdminController@edit_top_banner')->name('edit_top_banner');
    Route::post('/update/top_banner/{id}','SEOAdminController@update_top_banner')->name('update_top_banner');
    Route::post('/insert/top_banner','SEOAdminController@save_top_banner')->name('save_top_banner');
    Route::get('/del/top_banner/{id}','SEOAdminController@del_top_banner')->name('del_top_banner');

    Route::get('/pages/section','SEOAdminController@pages_section')->name('pages_section');
    Route::get('/edit/section/{id}','SEOAdminController@edit_section')->name('edit_section');
    Route::post('/update/section/{id}','SEOAdminController@update_section')->name('update_section');

    Route::get('/pages/section/content','SEOAdminController@pages_section_content')->name('pages_section_content');
    Route::get('/edit/content/{id}','SEOAdminController@edit_content')->name('edit_content');
    Route::post('/update/content','SEOAdminController@update_content')->name('update_content');

    Route::get('/pages/image/content','SEOAdminController@pages_image_content')->name('pages_image_content');
    Route::post('/update/image/content','SEOAdminController@update_image_content')->name('update_image_content');
    Route::get('/get/image/content/by/section/{id}','SEOAdminController@get_image_content_by_section')->name('get_image_content_by_section');
    // Route::get('/edit/content/{id}','SEOAdminController@edit_content')->name('edit_content');
    // Route::post('/update/content','SEOAdminController@update_content')->name('update_content');

    Route::post('/insert/pages','SEOAdminController@save_pages')->name('save_pages');
    Route::get('/del/pages/{id}','SEOAdminController@del_pages')->name('del_pages');

    Route::post('/insert/pages/section','SEOAdminController@save_pages_section')->name('save_pages_section');
    Route::get('/del/pages/section/{id}','SEOAdminController@del_pages_section')->name('del_pages_section');

    Route::post('/insert/pages/section/content','SEOAdminController@save_pages_section_content')->name('save_pages_section_content');
    Route::get('/del/pages/section/content/{id}','SEOAdminController@del_pages_section_content')->name('del_pages_section_content');

    Route::get('/del/pages/meta/tag/{id}','SEOAdminController@del_pages_meta_tag')->name('del_pages_meta_tag');
    Route::get('/del/meta/tag/{id}','SEOAdminController@del_meta_tag')->name('del_meta_tag');
    Route::post('/edit/meta/tag','SEOAdminController@edit_meta_tag')->name('edit_meta_tag');
    Route::post('/insert/pages/meta/tag','SEOAdminController@save_pages_meta_tag')->name('save_pages_meta_tag');
    Route::group(['middleware' => ['lab_auth']], function () {
        //New Lab Admin Routes
        Route::get('lab/admin/dash','AdminController@lab_admin_dash')->name('lab_admin_dash');
        Route::get('lab/orders','TblOrdersController@lab_admin_orders')->name('lab_admin_orders');
        Route::get('/unassigned/lab/orders','AdminController@unassigned_lab_orders')->name('unassigned_lab_orders');
        Route::get('/pending/lab/orders','AdminController@pendingLabOrders')->name('pendingLabOrders');
        Route::get('/pending/lab/refunds','AdminController@pendingrefunds')->name('pendingrefunds');
        Route::get('/quest/orders','AdminController@quest_orders')->name('quest_orders');
        Route::get('/quest/failed/requests','AdminController@quest_failed_requests')->name('quest_failed_requests');
        Route::get('/quest/lab/tests','AdminController@quest_lab_tests')->name('quest_lab_tests');
        Route::post('edit/lab/test','AdminController@edit_lab_test')->name('edit_lab_test');
        Route::get('/lab/test/categories','ProductCategoryController@lab_test_categories')->name('lab_test_categories');
        Route::post('update/lab/cat','ProductCategoryController@update_lab_cat')->name('update_lab_cat');
        Route::post('create/lab/cat','ProductCategoryController@create_lab_cat')->name('create_lab_cat');
        Route::post('del/lab/cat','ProductCategoryController@del_lab_cat')->name('del_lab_cat');
        Route::get('/online/lab/tests','AdminController@online_labtests')->name('online_labtests');
        Route::post('/del/online/lab/test','AdminController@del_online_labtest')->name('del_online_labtest');
        Route::post('/create/online/lab/test','AdminController@create_online_labtest')->name('create_online_labtest');
        Route::post('/edit/online/lab/test','AdminController@edit_lab_test')->name('edit_lab_test');
        Route::get('/lab/admin/account/setting','AdminController@lab_admin_setting')->name('lab_admin_setting');
        Route::get('/lab/reports','QuestController@lab_reports')->name('lab_reports');
        Route::get('/lab/reports/view/patient/{id}','QuestController@lab_reports_view_patient')->name('lab_reports_view_patient');
        Route::get('/lab/reports/view/doctor/{id}','QuestController@lab_reports_view_doctor')->name('lab_reports_view_doctor');
        //New Lab Editor Routes
        Route::get('lab/editor/dash','ProductCategoryController@lab_test_categories')->name('lab_editor_dash');
        Route::get('/lab/editor/account/setting','AdminController@lab_editor_setting')->name('lab_editor_setting');
    });
    Route::get('/inclinic/pharmacy/all/orders','AdminController@inclinic_pharmacy_editor_orders')->name('inclinic_pharmacy_editor_orders');
    Route::get('admin/fee-approval' , 'AdminController@fee_approval')->name('fee_approval');

    Route::get('/inclinic/pharmacy/prescription/download/{id}','AdminController@inclinic_pharmacy_prescription_download')->name('dash_inclinic_pharmacy_prescription_download');

    Route::group(['middleware' => ['phar_auth']], function () {
        //New Pharmacy Editor Routes
        Route::get('/pharmacy/account/setting','AdminController@pharmacy_editor_setting')->name('pharmacy_editor_setting');
        Route::get('/pharmacy/all/orders','AdminController@pharmacy_editor_orders')->name('pharmacy_editor_orders');
        Route::get('/pharmacy/product/categories', 'ProductCategoryController@dash_index')->name('pharmacy_editor_prod_cat');
        Route::post('/pharmacy/product/update', 'ProductCategoryController@dash_main_cat_update')->name('pharmacy_editor_prod_cat_update');
        Route::post('/pharmacy/product/delete', 'ProductCategoryController@dash_main_cat_delete')->name('pharmacy_editor_prod_cat_delete');
        Route::post('/pharmacy/product/store', 'ProductCategoryController@dash_main_cat_store')->name('pharmacy_editor_prod_cat_store');
        Route::get('/pharmacy/product/sub/categories', 'ProductsSubCategoryController@dash_index')->name('pharmacy_editor_sub_cat');
        Route::post('/pharmacy/sub/delete', 'ProductsSubCategoryController@dash_sub_cat_delete')->name('pharmacy_editor_sub_cat_delete');
        Route::post('/pharmacy/sub/update', 'ProductsSubCategoryController@dash_sub_cat_update')->name('pharmacy_editor_sub_cat_update');
        Route::post('/pharmacy/sub/store', 'ProductsSubCategoryController@dash_sub_cat_store')->name('pharmacy_editor_sub_cat_store');
        Route::get('/getMainCategories', 'ProductsSubCategoryController@getMainCategories')->name('getMainCategories');
        Route::get('/pharmacy/medicine/description', 'AllProductsController@dash_medicine_description')->name('dash_medicine_description');
        Route::get('/pharmacy/medicine/UOM', 'MedicineUOMController@dash_show')->name('dash_medicine_UOM_show');
        Route::post('/pharmacy/UOM/update', 'MedicineUOMController@dash_update')->name('dash_medicine_UOM_update');
        Route::post('/pharmacy/UOM/delete', 'MedicineUOMController@dash_delete')->name('dash_medicine_UOM_delete');
        Route::post('/pharmacy/UOM/store', 'MedicineUOMController@dash_store')->name('dash_medicine_UOM_store');
        Route::post("/pharmacy/uploadCSV", "MedicineImportController@dash_uploadFile")->name('dash_uploadCSV');
        Route::get('/pharmacy/medicine/view', 'MedicineImportController@pe_view_Medicine')->name('pe_view_Medicine');
        Route::get('/pharmacy/medicine/upload', 'MedicineImportController@dash_index')->name('upload_Medicine');
        Route::post("/pharmacy/medicine/delete", "MedicineImportController@dash_deleteRxMedicine")->name('dash_deleteRxMedicine');
        Route::post("/dash_delete_medicine_product", "MedicineImportController@dash_delete_medicine_product")->name('dash_delete_medicine_product');
        Route::post("/pharmacy/medicine/edit", "MedicineImportController@dash_editRxMedicine")->name('dash_editRxMedicine');
        Route::post("/dash_edit_medicine_product", "MedicineImportController@dash_edit_medicine_product")->name('dash_edit_medicine_product');
        Route::get("/get/medicine/details", "MedicineImportController@dash_get_medicine_details")->name('dash_get_medicine_details');
        Route::post("/get/medicine/details/store", "MedicineImportController@dash_storeMedicineVariation")->name('dash_store_Medicine_Variation');
        Route::post("/dash/store/medicine/product", "MedicineImportController@dash_store_medicine_product")->name('dash_store_medicine_product');
        Route::get("/pharmacy/admin/manage/users", "AdminController@pharmacy_admin_manage_editors")->name('pharmacy_admin_manage_editors');
        Route::get("/pharmacy/admin/manage/user/status/{id}", "AdminController@pharmacy_admin_change_status")->name('pharmacy_admin_change_status');
        Route::get("/pharmacy/admin/all/medicines", "MedicineImportController@pharmacy_admin_all_medicines")->name('pharmacy_admin_all_medicines');
    });

    Route::group(['middleware' => ['img_auth']], function () {
        //New Imaging Admin Routes
        Route::get('imaging/admin/dash','AdminController@imaging_admin_dash')->name('imaging_admin_dash');
        Route::get('imaging/lab/services','AdminController@imaging_services')->name('imaging_services');
        Route::post('imaging/lab/services','AdminController@imaging_services')->name('imaging_services');
        Route::post('add/imaging','AdminController@add_imaging')->name('add_imaging');
        Route::post('del/imaging','AdminController@del_imaging')->name('del_imaging');
        Route::post('edit/imaging','AdminController@edit_imaging')->name('edit_imaging');
        Route::get('imaging/lab/locations','AdminController@imaging_locations')->name('imaging_locations');
        Route::post('imaging/lab/locations','AdminController@imaging_locations')->name('imaging_locations');
        Route::post('/add/imaging/location','AdminController@add_imaging_locations')->name('add_imaging_locations');
        Route::post('/edit/imaging/location','AdminController@edit_imaging_locations')->name('edit_imaging_locations');
        Route::post('/del/imaging/location','AdminController@del_imaging_location')->name('del_imaging_location');
        Route::get('imaging/lab/prices','AdminController@imaging_prices')->name('imaging_prices');
        Route::post('imaging/lab/prices','AdminController@imaging_prices')->name('imaging_prices');
        Route::post('search/imaging/lab/prices','AdminController@search_imaging_prices')->name('search_imaging_prices');
        Route::get('search/imaging/lab/prices','AdminController@search_imaging_prices')->name('search_imaging_prices');
        Route::post('imaging/lab/pagination/fetch_data', 'AdminController@search_imaging_prices');
        Route::post('add/imaging/prices','AdminController@add_imaging_prices')->name('add_imaging_prices');
        Route::post('edit/imaging/prices','AdminController@edit_imaging_prices')->name('edit_imaging_prices');
        Route::post('del/imaging/prices','AdminController@del_imaging_prices')->name('del_imaging_prices');
        Route::get('imaging/lab/categories','ProductCategoryController@imaging_lab_categories')->name('imaging_lab_categories');
        Route::get('imaging/lab/order/file','ProductCategoryController@imaging_lab_order_file')->name('imaging_lab_order_file');
        Route::post('imaging/lab/categories','ProductCategoryController@imaging_lab_categories')->name('imaging_lab_categories');
        Route::post('create/imaging/category','ProductCategoryController@add_imaging_category')->name('add_imaging_category');
        Route::post('edit/imaging/category','ProductCategoryController@edit_imaging_category')->name('edit_imaging_category');
        Route::post('del/imaging/category','ProductCategoryController@del_imaging_category')->name('del_imaging_category');
        Route::get('imaging/lab/orders','TblOrdersController@imaging_admin_orders')->name('imaging_admin_orders');
        Route::get('/imaging/admin/account/setting','AdminController@imaging_admin_setting')->name('imaging_admin_setting');
        Route::get('imaging/lab/order/{id}','TblOrdersController@imaging_admin_order_details')->name('imaging_admin_order_details');
        //New Imaging Editor Routes
        Route::get('imaging/editor/dash','AdminController@img_editor_dash')->name('img_editor_dash');
        Route::get('imaging/all/records','AdminController@img_editor_dash')->name('img_all_records');
        Route::get('/imaging/editor/account/setting','AdminController@imaging_admin_setting')->name('imaging_admin_setting');
        Route::get('/delete/price/{id}','AllProductsController@delete_imaging_prices')->name('delete_imaging_prices');
    });

    Route::group(['middleware' => ['finance_auth']], function () {
        //New Finance Admin Routes
        Route::get('finance/admin/dash', 'PaymentController@Wallet_Pay')->name('finance_admin_dash');
        Route::get('finance/admin/account/setting','FinanceController@finance_admin_setting')->name('finance_admin_setting');
        Route::get('doctors/finance/reports','FinanceController@doctor_finance_reports')->name('doctor_finance_reports');
        Route::get('doctors/online/lab/{id}','FinanceController@online_lab')->name('online_lab');
        Route::post('doctors/online/lab','FinanceController@online_lab_filter')->name('online_lab_filter');
        Route::get('doctors/evisit/{id}','FinanceController@evisit')->name('evisit');
        Route::post('doctors/evisit','FinanceController@evisit_filter')->name('evisit_filter');
        Route::get('doctors/payable/{id}','FinanceController@doc_payable_amount')->name('doc_payable_amount');
        Route::post('doctors/payable','FinanceController@doc_payable_amount_filter')->name('doc_payable_amount_filter');
        Route::get('doctors/paid/{id}','FinanceController@doc_paid_amount')->name('doc_paid_amount');
        Route::post('doctors/paid','FinanceController@doc_paid_amount_filter')->name('doc_paid_amount_filter');
        Route::get('/vendors','FinanceController@vendors')->name('vendors');
        Route::get('/vendor/details/{id}','FinanceController@vendor_details')->name('vendor_details');
        Route::get('/pay/{name}/{id}','FinanceController@vendor_payment')->name('vendor_payment');
        Route::get('/quest/amount/{pagename}','FinanceController@quest_amount')->name('quest_amount');
        Route::post('/quest/amount/{pagename}','FinanceController@quest_amount')->name('quest_amount');
        Route::get('/add/quest/invoice/{id}','FinanceController@add_quest_invoice')->name('add_quest_invoice');
        Route::post('/add/quest/invoice/{id}','FinanceController@add_quest_invoice')->name('add_quest_invoice');
        Route::get('/mark/invoice/paid/{id}','FinanceController@mark_invoice_paid')->name('mark_invoice_paid');

    });
    Route::get('/doctor/in/clinics', 'PatientController@doctor_in_clinic')->name('doctor_in_clinic');

    Route::post('/inclinic_new_get_products_by_category', 'AllProductsController@inclinic_new_get_products_by_category');
    Route::post('/inclinic_new_get_lab_products_video_page', 'AllProductsController@inclinic_new_get_lab_products_video_page');
    Route::post('/inclinic_get_product_details', 'AllProductsController@inclinic_get_product_details');
    Route::post('/inclinic_get_prescribe_item_list', 'AllProductsController@inclinic_get_prescribe_item_list');
    Route::post('/inclinic_delete_prescribe_item_from_session', 'AllProductsController@inclinicdeletePrescribeItemFromSession');
    Route::post('/inclinic_get_lab_details', 'AllProductsController@inclinic_get_lab_details');
    Route::post('/inclinic_add_imging_pro', 'AllProductsController@inclinic_add_imging_pro');
    Route::post('/inclinic_new_get_imaging_products_by_category', 'AllProductsController@inclinic_new_get_imaging_products_by_category');
    Route::post('/inclinic_add_dosage', 'SessionController@inclinic_add_dosage');
    Route::post('/inclinic_check_prescription_completed', 'DoctorController@inclinic_check_prescription_completed');
    Route::post('/inclinic_doctor_end_session', 'SessionController@inclinic_doctor_end_session');
    Route::post('/inclinic_pharmacy_payment', 'SessionController@inclinic_pharmacy_payment')->name('inclinic_pharmacy_payment');

    Route::post('patient/health/store','DoctorController@patient_health_store')->name('patient_health_store');
    Route::post('mood/disorder/store','DoctorController@mood_disorder_store')->name('mood_disorder_store');
    Route::post('anxiety/scale/store','DoctorController@anxiety_scale_store')->name('anxiety_scale_store');

    Route::get('upload/banner','AdminController@upload_banner')->name('upload_banner');
    Route::get('view/banners','AdminController@view_banners')->name('view_banners');
    Route::get('change/banner/status/{id}','AdminController@change_banner_status')->name('change_banner_status');
    Route::get('delete/banner/{id}','AdminController@delete_banner')->name('delete_banner');
    Route::post('upload/new/banner','AdminController@upload_new_banner')->name('upload_new_banner');

    Route::group(['middleware' => 'admin_auth'], function () {
        Route::get('change/authorize_api/mode','AdminController@change_authorize_api_mode')->name('change_authorize_api_mode');
        Route::get('change/maintainance/mode','AdminController@change_maintainance_mode')->name('change_maintainance_mode');
        Route::get('transaction','AdminController@tbl_transaction')->name('tbl_transaction');
        Route::post('change/ticker','AdminController@change_ticker')->name('change_ticker');
        Route::get('admin/all/state', 'AdminController@dash_allStates');
        Route::get('admin/all/appointments', 'AppointmentController@dash_admin_appointments');
        Route::post('admin/all/appointments', 'AppointmentController@dash_admin_appointments');
        Route::get('admin/all/specializations', 'AdminController@dash_viewSpecialization');
        Route::post('admin/all/specializations', 'AdminController@dash_viewSpecialization');
        Route::get('admin/view/therapy/issues', 'AdminController@admin_view_therapy_issue')->name('admin_view_therapy_issues');
        Route::get('admin/edit/therapy/issues/{id}', 'AdminController@admin_edit_therapy_issue')->name('admin_edit_therapy_issue');
        Route::get('admin/delete/therapy/issues/{id}', 'AdminController@admin_delete_therapy_issue')->name('admin_delete_therapy_issue');
        Route::get('admin/add/therapy/issues', 'AdminController@admin_add_therapy_issue')->name('admin_add_therapy_issues');
        Route::get('admin/view/psychiatrist/services', 'AdminController@admin_view_psychiatrist_services')->name('admin_view_psychiatrist_services');
        Route::get('admin/add/PsychiatryService', 'AdminController@admin_addPsychiatryService')->name('admin_addPsycService');
        Route::get('admin/all/quest/orders','QuestController@dash_index')->name('admin_quest_orders');
        Route::post('admin/all/quest/orders','QuestController@dash_index')->name('admin_quest_orders');
        Route::get('admin/patient_records', 'AdminController@admin_patient_records')->name('admin_patient_records');
        Route::get('admin/doctor/profile_update', 'AdminController@admin_doctor_profile_update')->name('admin_doctor_profile_update');
        Route::get('admin/all/prescription','QuestController@dash_e_prescription')->name('admin_e_prescription');
        Route::get('admin/all/imgfile','QuestController@dash_imaging_file')->name('imaging_file');
        Route::post('admin/all/prescription','QuestController@dash_e_prescription')->name('admin_e_prescription');
        Route::POST('admin/store/PsychiatryService', 'AdminController@admin_storePsychiatryService')->name('admin_storePsycService');
        Route::POST('admin/store/TherapyIssues', 'AdminController@admin_storeTherapyIssue')->name('admin_storeTherapyIssue');
        Route::POST('admin/update/TherapyIssues', 'AdminController@admin_updateTherapyIssue')->name('admin_updateTherapyIssue');
        Route::get('admin/acc/settings', 'AdminController@admin_acc_settings')->name('admin_acc_settings');
        Route::get('admin/all/patient', 'AdminController@admin_all_patients')->name('admin_all_patients');
        Route::post('admin/all/patient', 'AdminController@admin_all_patients')->name('admin_all_patients');
        Route::get('admin/error/log/view', 'AdminController@admin_error_log_view')->name('admin_error_log_view');
        Route::get('admin/physical/location', 'AdminController@admin_physical_location')->name('admin_physical_location');
        Route::get('admin/medicine/purchase', 'AdminController@medicine_purchase')->name('medicine_purchase');
        Route::post('admin/medicine/purchase/store', 'AdminController@medicine_purchase_store')->name('medicine_purchase_store');
        Route::get('admin/add/physical/location', 'AdminController@admin_add_physical_location')->name('admin_add_physical_location');
        Route::post('admin/store/physical/location', 'AdminController@store_physical_location')->name('store_physical_location');
        Route::get('admin/edit/physical/location/{id}', 'AdminController@admin_edit_physical_location')->name('admin_edit_physical_location');
        Route::post('admin/update/physical/location/{id}', 'AdminController@admin_update_physical_location')->name('admin_update_physical_location');
        Route::get('/admin/delete/physical/location/{id}', 'AdminController@delete_physical_location')->name('delete_physical_location');
        Route::get('admin/all/orders', 'TblOrdersController@admin_orders')->name('all_orders_admin');
        Route::post('admin/confirm-approval', 'AdminController@confirm_approval')->name('confirm_approval');
        Route::post('decline-approval', 'AdminController@decline_approval')->name('decline_approval');
        Route::post('admin/all/orders', 'TblOrdersController@admin_orders')->name('all_orders_admin');
        Route::get('admin/all/orders/{id}', 'TblOrdersController@order_details')->name('admin_order_details');
        Route::get('admin/documents/add', 'AdminController@document')->name('add_docs');
        Route::post('admin/documents/store', 'AdminController@store_documents')->name('store_docs');
        Route::get('admin/documents/view', 'AdminController@show_document')->name('docs');
        Route::post('admin/documents/delete', 'AdminController@delete_doc')->name('delete_docs');
        Route::post('admin/documents/update', 'AdminController@update_term')->name('update_docs');
        Route::get('admin/contact_us', 'AdminController@dash_admin_contact')->name('admin_contact_us');
        Route::post('admin/contact_us', 'AdminController@dash_admin_contact')->name('admin_contact_us');
        Route::get('admin/all/state', 'AdminController@dash_allStates')->name('admin_all_state');
        Route::post('admin/all/state', 'AdminController@dash_allStates')->name('admin_all_state');
        Route::get('admin/all/appointments', 'AppointmentController@dash_admin_appointments')->name('admin_all_appointments');;
        Route::get('admin/all/specializations', 'AdminController@dash_viewSpecialization')->name('specializations');
        Route::get('admin/all/statemodal', 'AdminController@states');
        Route::post('admin/store/spec', 'AdminController@admin_store_spec')->name('admin_store_spec');
        Route::post('admin/store/specializations', 'AdminController@dash_store_Specialization_price')->name('admin_store_spec_price');
        Route::get('admin/edit/specializations', 'AdminController@dash_editSpecialization')->name('admin_edit_spec');
        Route::get('admin/delete/spec', 'AdminController@dash_deleteSpecialization')->name('admin_del_spec');
        Route::get('admin/delete/specializations', 'AdminController@dash_delete_Specialization_price')->name('admin_del_spec_price');
        Route::get('admin/specialization/price', 'AdminController@dash_addSpecializationPrice')->name('price_specializations');
        Route::get('admin/specialization/price/edit', 'AdminController@updateSpecializationPrice')->name('update_specialization_price');
        Route::get('admin/coupon/view', 'CouponController@view')->name('ViewCoupons');
        Route::get('admin/coupon/add/view', 'CouponController@index')->name('CouponPage');
        Route::post('admin/coupon/store', 'CouponController@store')->name('store_coupon');
        Route::get('admin/coupon/get/{category}', 'CouponController@get_category');
        Route::get('admin/coupon/get/sub/{category}', 'CouponController@get_sub_category_product');
        Route::get('admin/lab/reports','QuestController@admin_lab_reports')->name('admin_lab_reports');
    });
    Route::post('get_lab_test_aoes_during_session', 'PharmacyController@getLabTestAoesDuringSession')->name('getAoesDuringSession');
    Route::post('/set_session_start_time', 'SessionController@set_session_start_time');
    Route::post('/get_patient_id', 'SessionController@get_patient_id');
    Route::post('/get_product_details', 'AllProductsController@get_product_details');

    Route::post('/get_lab_details', 'AllProductsController@get_lab_details');
    Route::post('add_labtest_aoes_into_db', 'PharmacyController@add_labtest_aoes_into_db');

    Route::post('/delete_prescribe_item_from_session', 'AllProductsController@deletePrescribeItemFromSession');
    Route::get('/delete_prescribe_item_from_recom/{id}', 'AllProductsController@deletePrescribeItemFromRecom');
    Route::post('/get_med_detail', 'SessionController@get_med_detail');
    Route::post('/add_dosage', 'SessionController@add_dosage');
    Route::post('/doctor_end_session', 'SessionController@doctor_end_session');
    Route::get('/waiting/page/{session}', 'SessionController@end_session_patient')->name('end_session_patient')->middleware('no_cache');
    Route::post('/get_marker_by_id_imaging', 'SessionController@get_marker_by_id_imaging');
    Route::post('/get_locations_imaging', 'SessionController@get_locations_imaging');
    Route::post('/get_medicine_price', 'SessionController@get_medicine_price');
    Route::post('/check_prescription_completed', 'DoctorController@check_prescription_completed');
    Route::post('/get_marker_by_id', 'SessionController@get_marker_by_id');
    Route::post('/get_products_by_category', 'AllProductsController@get_products_by_category');
    Route::post('/new_get_products_by_category', 'AllProductsController@new_get_products_by_category');
    Route::post('/get_med_filtered_category', 'AllProductsController@get_med_filtered_category');
    Route::post('/new_get_lab_products_video_page', 'AllProductsController@new_get_lab_products_video_page');
    Route::post('/refer_doc_search', 'AllProductsController@refer_doc_search');
    Route::post('/get_img_report', 'TblOrdersController@get_img_report');
    Route::post('/check_session_video_status', 'SessionController@check_status_video');
    Route::post('/get_prescribe_item_list', 'AllProductsController@get_prescribe_item_list');
    Route::post('/getSpecializedDoctors', 'DoctorController@getSpecializedDoctors');
    Route::post('/cancelReferal', 'DoctorController@cancelReferal');
    Route::post('/newCancelReferal', 'DoctorController@newCancelReferal');
    Route::post('/sendReferal', 'DoctorController@sendReferal');
    Route::post('/newSendReferal', 'DoctorController@newSendReferal');
    Route::post('/getSessionDetails', 'SessionController@show');
    Route::get('notificaation', 'NotificationController@dash_allNotification')->name('notifications');
    Route::get('readAllNotificaation', 'NotificationController@dash_ReadAllNotification')->name('Read_all');
    Route::get('unreadAllNotificaation', 'NotificationController@dash_GetUnreadNotifications')->name('unread_all');
    Route::get('/patient/video/{id}', 'VideoController@videoJoin')->name('patient.video.session');
    Route::get('/doctor/video/{id}', 'VideoController@videoJoin')->name('doctor.video.session');
    // Quest Routes
    // Route::get('/quest_orders', 'QuestController@index')->name('quest_orders');
    // Route::get('/e_prescription', 'QuestController@e_prescription')->name('e_prescription');
    // Route::get('/quest_order/{id}', 'QuestController@requisition')->name('quest.requisition');
    // Route::get('/quest_failed_requests', 'QuestController@failed_requests')->name('quest_failed_requests');
    Route::get('admin/quest/failed/requests', 'QuestController@dash_failed_requests')->name('admin_quest_failed_requests');
    // Lab editor routes
    Route::get('/unassignedLabOrders', 'TblOrdersController@unassignedLabOrders')->name('unassignedLabOrders');
    Route::post('/assignApprovalDoctor', 'TblOrdersController@assignApprovalDoctor');
    Route::post('/assignLabForApprovalToDoctor', 'TblOrdersController@assignLabForApprovalToDoctor');
    Route::get('/pendingLabOrders', 'TblOrdersController@pendingLabOrders');
    Route::get('/pendingRefunds', 'TblOrdersController@pendingRefunds');
    Route::post('/refundLabOrder', 'TblOrdersController@refundLabOrder');
    // pharmacy editor routes
    Route::get('/add_pharmacy_location', 'PharmacyController@add_pharmacy_location')->name('add_pharmacy_location');
    Route::post('/add_location', 'PharmacyController@add_location')->name('add_location');
    Route::get('/view_pharmacy_location', 'PharmacyController@view_pharmacy_location')->name('view_pharmacy_location');
    Route::get('delete_pharmacy_location/{id}', 'PharmacyController@delete_pharmacy_location');
    Route::get('edit_pharmacy_location/{id}', 'PharmacyController@edit_pharmacy_location');
    Route::put('update_pharmacy_location', 'PharmacyController@update_pharmacy_location')->name('update_pharmacy_location');
    Route::post('/get_neareast_pharmacy', 'PharmacyController@get_neareast_pharmacy');
    Route::get('checkMail', 'PaymentController@checkMail');
    // Editors Route
    Route::get('/editors', 'UserController@view_editors')->name('all_editors');
    Route::get('/revoke_role/{id}', 'UserController@revoke_role')->name('revoke_role');
    // Products Controller and Image editor routes
    Route::get('/imaging_services_all', 'AllProductsController@imagingAllData');
    Route::get('/imaging_services', 'AllProductsController@imagingServices');
    Route::get('/imaging_services_delete/{id}', 'AllProductsController@imagingServicesDelete');
    Route::get('/imaging_locations', 'AllProductsController@imagingLocations');
    Route::get('/imaging_locations_delete/{id}', 'AllProductsController@imagingLocationsDelete');
    Route::get('/imaging_prices', 'AllProductsController@imagingPrices');
    Route::get('/bulkUploadImagingPrices', 'AllProductsController@bulkUploadImagingPrices');
    Route::get('/bulkUploadImagingServices', 'AllProductsController@bulkUploadImagingServices');
    Route::post('/bulkUploadImagingPricesStore', 'AllProductsController@bulkUploadImagingPricesStore')->name('bulkUploadImagingPricesStore');
    Route::post('/bulkUploadImagingServicesStore', 'AllProductsController@bulkUploadImagingServicesStore')->name('bulkUploadImagingServicesStore');
    Route::get('/imaging_prices_delete/{id}', 'AllProductsController@imagingPricesDelete');
    Route::resource('allProducts', 'AllProductsController');
    Route::get('/get_imaging_services_all', 'AllProductsController@getImagingAllData');
    Route::resource('productCategories', 'ProductCategoryController');
    Route::get('/medicine_description', 'AllProductsController@medicinedescription');
    Route::post('/medicine_desc', 'AllProductsController@storedesc');
    Route::resource('productsSubCategories', 'ProductsSubCategoryController');

    Route::post('/patient_check_status', 'DoctorController@patient_check_status');
    Route::get('/waiting_room_my_doc', 'DoctorController@waiting_room_my_doc');
    Route::get('/waiting_room_load_doc/{id}', 'DoctorController@waiting_room_load_doc');
    Route::get('/patient_absent/{id}', 'VideoController@patient_absent');
    Route::get('/doctor/patient/queue', 'DoctorController@doctor_patient_queue')->name('doctor_queue')->middleware('no_cache');
    Route::post('/get_symptom_data','DoctorController@get_symptom_data')->name('get_symptom_data');
    Route::post('/waiting/patient/join/call', 'VideoController@waitingPatientJoinCall')->name('waitingPatientJoinCall');
    Route::get('/waiting/room/{id}', 'DoctorController@waiting_room_pat')->name('waiting_room_pat')->middleware('no_cache');
    Route::post('sendInviteToDoctorForSession', 'SessionController@sendInvite')->name('invite.session');
    Route::post('checkSessionStatus', 'SessionController@sessionCtatusCheck')->name('session.status.check');
    Route::get('/paid/book/appointment/{id}/{ses_id}', 'AppointmentController@paid_book_appointment')->name('paid_book_appointment');
    Route::get('patient/paid/online/doctors/{id}/{ses_id}', 'DoctorController@dash_paid_getonlinedoctors')->name('dash_paid_getonlinedoctors');
    Route::get('/patient/non_refund/appointment_cancel/{id}', 'AppointmentController@non_refund_cancel_appointment')->name('non_refund_cancel_appointment');
    Route::post('/send/doctors/online/alert', 'DoctorController@send_doc_online_alert')->name('send_doc_online_alert');

    Route::group(['middleware' => 'redirecttovideo'], function () {
        Route::get('session/reminder/{id}', 'PatientController@session_reminder')->name('session_reminder');
        Route::get('error/log/view', 'AdminController@error_log_view')->name('error_log_view');
        Route::get('view/doctor/{id}', 'PatientController@view_doctor')->name('view_doctor');
        Route::get('pagination/fetch_data', 'ProfileController@fetch_data');
        Route::get('admin/pagination/fetch_data', 'ProfileController@admin_fetch_data');
        Route::get('pagination/fetch_session_data', 'PaymentController@fetch_session_data');
        Route::get('pagination/fetch_pres_data', 'PaymentController@fetch_pres_data');
        Route::get('pagination/fetch_vendor_pres_data', 'FinanceController@vendor_fetch_pres_data');
        Route::get('pagination/fetch_online_data', 'PaymentController@fetch_online_data');
        Route::get('pagination/fetch_vendor_online_data', 'FinanceController@vendor_fetch_online_data');
        Route::post('/profile/picture/update', 'ProfileController@editprofilepicture')->name('updatePicture');
        Route::post('/profile/number/update', 'ProfileController@editprofilenumber')->name('updateNumber');
        Route::post('/GetUnreadNotifications', 'NotificationController@GetUnreadNotifications');
        Route::get('patient/my_doctor', 'PatientController@my_doctors')->name('my_doctors');
        Route::get('patient/my_reports', 'PatientController@my_reports')->name('my_reports');
        Route::get('remove_item_from_cart/{id}', 'AllProductsController@remove_item_from_cart')->name('remove_item_from_cart');
        Route::post('show_product_on_final_checkout', 'AllProductsController@show_product_on_final_checkout')->name('show_product_on_final_checkout');
        Route::post('show_product_on_checkout', 'AllProductsController@show_product_on_checkout')->name('show_product_on_checkout');
        Route::post('remove_product_on_checkout', 'AllProductsController@remove_product_on_checkout')->name('remove_product_on_checkout');
        Route::post('/get_available_dates', 'AppointmentController@get_doc_avail_dates')->name('get_doc_avail_dates');
        Route::put('/updateDocProfile', 'ProfileController@updateDocProfile')->name('updateDocProfile');
        Route::get('patient/all/orders', 'TblOrdersController@patient_order')->name('patient_all_order');
        Route::get('patient/evisit/locations', 'SessionController@dash_evisit_locations')->name('patient_evisit_locations');
        Route::get('patient/evisit/specialization', 'SessionController@dash_evisit_specialization')->name('patient_evisit_specialization');
        Route::post('states/specialization', 'SessionController@states_specialization')->name('states_specialization');
        Route::get('patient/lab/results', 'QuestController@patient_dash_lab_reports')->name('patient_Lab_result');
        Route::get('/patient/view/lab/result/{id}', 'QuestController@patient_view_lab_report')->name('patient_view_Lab_result');
        Route::get('patient/lab/requisition', 'QuestController@patient_viewAllQuestRequisitions')->name('patient_Lab_requisition');
        Route::get('doctor/lab/requisition', 'QuestController@doctor_viewAllQuestRequisitions')->name('doctor_Lab_requisition');
        Route::get('patient/lab/pending/requisition', 'QuestController@view_pending_requisitions')->name('Lab_pending_requisition');
        Route::get('doctor/lab/pending/requisition', 'QuestController@view_doctor_pending_requisitions')->name('Doctor_lab_pending_requisition');
        Route::get('patient/online/doctors/{id}', 'DoctorController@dash_getonlinedoctors')->name('patient_online_doctors');
        Route::post('get/patient/online/doctors/{id}', 'DoctorController@dash_getonlinedoctors_ajax')->name('patient_online_doctors_ajax');
        Route::get('psych/patient/online/doctors/{id}', 'DoctorController@dash_get_online_doctors')->name('psych_patient_online_doctors');
        Route::post('patient/store_symptoms', 'DoctorController@dash_store_symptoms_inquiry')->name('patient_inquiry_store');
        Route::post('/isabel/inquiry', 'DoctorController@isabel_inquiry')->name('isabel_inquiry');
        Route::get('patient/payment/session/{id}', 'DoctorController@dash_session_payment_page')->name('patient_session_payment_page')->middleware('no_cache');
        Route::post('/patient/waiting_room', 'DoctorController@dash_session_payment')->name('patient_session_payment')->middleware('no_cache');
        Route::post('session/search', 'DoctorController@session_search')->name('session_search');
        Route::post('pat/session/search', 'PatientController@session_search')->name('patient_session_search');
        Route::get('patient/imaging/report', 'TblOrdersController@dash_imaging_orders')->name('patient_imaging_orders');
        Route::get('patient/imaging/file', 'TblOrdersController@dash_imaging_file')->name('patient_imaging_file');
        Route::get('patient/account', 'PatientController@pat_acc_settings')->name('patient_acc_settings');
        Route::post('/update_pat_pass', 'PatientController@update_pat_pass')->name('update_pat_pass');
        Route::get('patient/waiting_room/{id}', 'DoctorController@dash_waiting_room')->name('patient_waiting_room');
        Route::get('all/patient/sessions', 'SessionController@dash_pat_record')->name('patients_all_sessions');
        Route::get('/sessions/details/{id}', 'SessionController@dash_session_detail_current')->name('sessionDetail');
        Route::post('/add/doctor/schedule', 'DoctorScheduleController@add_doc_schedule')->name('add_doc_schedule');
        Route::get('/add/therapy/schedule', 'DoctorScheduleController@add_therapy_schedule')->name('add_therapy_schedule');
        Route::post('/edit/therapy/event', 'DoctorScheduleController@edit_therapy_event')->name('edit_therapy_event');
        Route::post('/add/therapy/event', 'DoctorScheduleController@add_therapy_event')->name('add_therapy_event');
        Route::post('/add/doctor/details', 'DoctorController@add_doctor_details')->name('add_doctor_details');
        Route::post('/admin/add/doctor/details', 'DoctorController@admin_add_doctor_details')->name('admin_add_doctor_details');
        Route::get('/delete/doctor_schedule/{id}', 'DoctorScheduleController@del_doc_schedule')->name('del_doc_schedule');
        Route::post('/schedule_check', 'DoctorScheduleController@schedule_check')->name('schedule_check');
        Route::get('/doctor/edit/profile', 'ProfileController@editDoctorDetail')->name('editDoctorDetail');
        Route::post('/updateDocProfile', 'ProfileController@updateDocProfile')->name('updateDocProfile');
        Route::get('add/doctor/schedule', 'DoctorScheduleController@add_doctor_schedule')->name('add_doctor_schedule');
        Route::post('/edit/doctor/schedule', 'DoctorScheduleController@edit_doc_schedule')->name('edit_doc_schedule');
        Route::post('/get_schedule_slots', 'DoctorScheduleController@get_schedule_slots')->name('get_schedule_slots');
        Route::post('/get_therapy_slots', 'DoctorScheduleController@get_therapy_slots')->name('get_therapy_slots');
        Route::get('all/lab/reports/{patient_id}', 'QuestController@pat_lab_report');
        Route::get('/user/profile/{username}', 'ProfileController@view_profile')->name('profile');
        Route::get('patient/medical/profile', 'PatientController@pat_medical_profile')->name('patient_medical_profile');
        // Route::get('patient/update/medical/profile', 'PatientController@dash_update_medical_profile')->name('patient_update_medical_profile');
        Route::get('patient/update/medical/profile', 'PatientController@dash_update_medical_profile_new')->name('patient_update_medical_profile');
        Route::post('/store/medical/profile', 'PatientController@store_medical_history_new')->name('new_store_medical_profile');
        Route::get('delete/patient/medical/record/{id}', 'PatientController@delete_patient_medical_record')->name('delete_patient_medical_record');


        //patient editss

        Route::get('/editPatientDetail', 'ProfileController@editPatientDetail')->name('editPatientDetail');
        Route::post('/updatePatient', 'ProfileController@updatePatient')->name('updatePatient');
        Route::post('/updatePatient', 'ProfileController@updatePatient')->name('updatePatient');
        Route::get('/updateRecord/{id}', 'ProfileController@updateProfile')->name('updateRecord');

        Route::get('patient/refill/requests', 'DoctorController@patient_refill_requests')->name('patient_refill_requests');
        Route::get('all/lab/reports', 'QuestController@all_doctor_lab_reports')->name('all_lab_reports');
        Route::get('doctor/online/lab/approval/requests', 'TblOrdersController@doctor_online_lab_approvals')->name('lab_approve');
        Route::get('doctor/online/approved/labs', 'TblOrdersController@approved_labs')->name('approved_labs');
        Route::get('/doctor_lab_approvals', 'TblOrdersController@doctor_lab_approvals')->name('doctor_lab_approvals');
        //Doctor_Dashboard_Appointments_routes
        Route::get('/doctor/appointments', 'AppointmentController@Doctor_Appointments')->name('doc_appointments');
        Route::get('/doctor/appointment_cancel/{id}', 'AppointmentController@doc_appointment_cancel')->name('doc_appointment_cancel');
        //Doctor_Dashboard_Account_Settings_routes
        Route::get('/doctor/account_settings', 'DoctorController@doc_acc_settings')->name('doc_acc_settings');
        Route::post('/update_doc_pass', 'DoctorController@update_doc_pass')->name('update_doc_pass');
        //Doctor_Dashboard_Wallet_Routes
        Route::get('/doctor/wallet', 'PaymentController@doc_wallet')->name('doctor_wallet');
        Route::post('/doctor/wallet', 'PaymentController@doc_wallet')->name('doctor_wallet');
        Route::get('/viewAllQuestLabTest', 'AllProductsController@viewAllQuestLabTest');
        Route::get('/viewQuestLabTest', 'AllProductsController@viewQuestLabTest');
        Route::post('/ReadAllNotifications', 'NotificationController@ReadAllNotification');




        Route::post('/session_store', 'RecommendationController@store')->name('recommendations.store');
        Route::get('all/doctors', 'DoctorController@dash_all')->name('doctors_all');
        Route::get('all/sessions', 'SessionController@dash_record')->name('sessions_all');
        Route::get('doctor/inclinic/sessions', 'SessionController@inclinic_sessions')->name('inclinic_sessions');
        Route::get('all/patients', 'DoctorController@dash_all_patients')->name('patient_all');
        Route::get('patient/detail/{id}', 'PatientController@dash_view_patient_record')->name('patient_detailed');
        Route::get('pagination/fetch_pending_labs/{id}', 'PatientController@fetch_pending_labs');
        Route::get('pagination/fetch_pending_imagings/{id}', 'PatientController@fetch_pending_imagings');
        Route::get('doctor/order', 'TblOrdersController@dash_index')->name('doctors_orders');
        //Patient_Dashboard Appointments
        Route::get('/patient/appointments', 'AppointmentController@Patient_Appointments')->name('pat_appointments');
        Route::get('/patient/appointment_cancel/{id}', 'AppointmentController@pat_appointment_cancel')->name('pat_appointment_cancel');
        Route::get('/appointment/reschedule/{app_id}/specialization/{spec_id}', 'PatientController@appointment_reschedule')->name('appointment_reschedule');
        Route::get('/appointment/locations', 'AppointmentController@select_appointment_location')->name('select_appointment_location');
        Route::get('/specializations', 'AppointmentController@select_specialization')->name('select_specialization');
        Route::post('/get/states/specializations', 'AppointmentController@get_state_specializations')->name('get_state_specializations');
        Route::get('/book/appointment/{id}', 'AppointmentController@book_appointment')->name('book_appointment');
        Route::get('/book/requested/appointment/{id}', 'AppointmentController@requested_session')->name('requested_session');
        Route::post('/get/book/appointment/{id}', 'AppointmentController@book_appointment_ajax')->name('book_appointment_ajax');
        Route::get('psych/book/appointment/{id}', 'AppointmentController@psych_book_appointment')->name('psych_book_appointment');
        Route::post('psych/book/appointment/{id}', 'AppointmentController@psych_book_appointment')->name('psych_book_appointment');
        Route::post('/book/appointment/{id}', 'AppointmentController@book_appointment')->name('book_appointment');
        Route::post('/create_appointment', 'AppointmentController@create_appointment')->name('create_appointment');
        Route::get('/appointment/payment/{id}', 'AppointmentController@appoint_payment')->name('appoint_payment');
        Route::post('/appointment/payment', 'AppointmentController@payment_appointment')->name('payment_appointment');
        Route::get('load/online_doctors/{id}', 'DoctorController@load_online_doctors')->name('load_online_doctors');

        //Patient Current Medication new route
        Route::get('/current/medications', 'PatientController@current_medication')->name('current_medication');
        Route::post('/current/medications', 'PatientController@current_medication')->name('current_medication');
        Route::post('/refill_request', 'PatientController@refill_request')->name('refill_request');
        Route::get('/session/requested/{id}', 'AppointmentController@request_session')->name('request_session');
        //doctor watiing room new route
        Route::get('doctor/order/{id}', 'TblOrdersController@dash_show')->name('doctors_order_details');
        // Route::get('doctor/order/{id}', 'TblOrdersController@doctors_order_details')->name('doctors_order_details');


        //doctor watiing room new route

        Route::get('/profile/{username}', 'ProfileController@view_DocProfile')->name('user_profile');
        Route::post('update-fees' , 'ProfileController@updateFees')->name('updateFees');
        Route::get('/waiting_room_my', 'DoctorController@waiting_room_my');
        Route::get('/session', 'RecommendationController@display_session')->name('session_recom.display');
        Route::post('/recommendations_store_pres', 'RecommendationController@store_pres')->name('recommendations.store.pres');
        Route::post('/add_diagnosis_and_notes', 'RecommendationController@add_diagnosis_and_notes')->name('add_diagnosis_and_notes');
        Route::get('/recommendations', 'RecommendationController@display')->name('recommendations.display')->middleware('no_cache');
        Route::get('/doc_profile', function () {
            return view('doc_profile');
        })->name('doc_profile');
        Route::get('/lab_reports', 'QuestController@doctor_lab_patients')->name('lab_orders');
        Route::get('/doctor_lab_approvals', 'TblOrdersController@doctor_lab_approvals')->name('doctor_lab_approvals');
        Route::get('/acc_settings', function () {
            return view('acc_settings');
        })->name('acc_settings');
        Route::get('/medical_profile', 'PatientController@medical_profile')->name('medical_profile');
        Route::get('/add_medical_profile', 'PatientController@add_medical_profile')->name('add_medical_profile');
        Route::post('/add_medical_profile', 'PatientController@store_medical_history')->name('add_medical_profile');
        Route::get('/register_medical_profile', 'PatientController@register_medical_profile')->name('register_medical_profile'); //not authorized to access home
        Route::get('/current_medications', 'PatientController@current_meds')->name('current_meds');
        Route::get('/lab_requisitions', 'QuestController@viewAllQuestRequisitions')->name('lab_requisitions');
        Route::get('/patient_lab_reports', 'QuestController@patient_lab_reports')->name('patient_lab_reports');
        Route::get('/imaging_orders', 'TblOrdersController@imaging_orders')->name('imaging_orders');
        Route::get('/admin/wallet', 'PaymentController@wallet')->name('admin_wallet');
        Route::post('/admin/wallet', 'PaymentController@wallet')->name('admin_wallet');
        Route::get('/get_wallet_graph_values', 'PaymentController@wallet_graph_values')->name('wallet_graph_values');
        Route::get('/get_filtered_values', 'PaymentController@filtered_values')->name('filtered_values');
        Route::get('/admin/walletPay', 'PaymentController@Wallet_Pay')->name('admin_wallet_pay')->middleware('admin_auth');
        Route::get('/inclinic_patient', 'AdminController@inclinic_patient')->name('inclinic_patient');
        Route::get('/in_clinics_create', 'AdminController@in_clinics_create')->name('in_clinics_create');
        Route::post('/in_clinics_store', 'AdminController@in_clinics_store')->name('in_clinics_store');
        //Route::post('/admin/walletPay', 'PaymentController@Wallet_Pay')->name('admin_wallet_pay');
        // Route::get('/new_item',function(){return view('new_item');})->name('new_item');

        //mental_conditions
        Route::get('/add/items/mental/condition', 'MentalConditionsController@view_condition')->name('mental_condition');
        Route::get('/add/items/mental/ondition/view/{id}', 'MentalConditionsController@view')->name('view_condition');
        Route::get('/add/items/mental/condition/create', 'MentalConditionsController@create_condition')->name('create_condition');
        Route::post('/add/items/mental/condition/insert_condition', 'MentalConditionsController@insert_condition')->name('insert_condition');
        Route::get('/add/items/mental/condition/edit/{id}', 'MentalConditionsController@edit_condition')->name('edit_condition');
        Route::post('/add/items/mental/condition/update/{id}', 'MentalConditionsController@update_condition')->name('update_condition');
        Route::get('/add/items/mental/condition/delete/{id}', 'MentalConditionsController@delete')->name('delete_condition');


        //faqs
        Route::get('/add/items/faqs', 'TblFaqController@faqs')->name('FAQs');
        Route::get('/add/items/faqs/view/{id}', 'TblFaqController@view')->name('view_faqs');
        Route::get('/add/items/faqs/create', 'TblFaqController@create_faqs')->name('create_faqs');
        Route::post('/add/items/faqs/insert_faqs', 'TblFaqController@insert_faqs')->name('insert_faqs');
        Route::get('/add/items/faqs/edit/{id}', 'TblFaqController@edit_faqs')->name('edit_faqs');
        Route::post('/add/items/faqs/update/{id}', 'TblFaqController@update_faqs')->name('update_faqs');
        Route::get('/add/items/faqs/delete/{id}', 'TblFaqController@delete')->name('delete_faqs');

        //Related Products
        Route::resource('related_products', 'RelatedProductsController');

        //  Route::get('/add/items/mental/condition', 'MentalConditionsController@view_condition')->name('mental_condition');
        //  Route::get('/add/items/faqs', 'TblFaqController@faqs')->name('FAQs');
        // Route::get('/add/items/faqs/create', 'TblFaqController@create_faqs')->name('create_faqs');
        // Route::post('/add/items/faqs/insert_faqs', 'TblFaqController@insert_faqs')->name('insert_faqs');
        //  Route::get('/add/items/faqs/delete/{id}', 'TblFaqController@delete')->name('delete_faqs');
        //  Route::get('/add/items/faqs/view/{id}', 'TblFaqController@view')->name('view_faqs');
        //  Route::get('/add/items/faqs/edit/{id}', 'TblFaqController@edit_faqs')->name('edit_faqs');
        // Route::post('/add/items/faqs/update/{id}', 'TblFaqController@update_faqs')->name('update_faqs');







        Route::get('/Add/item', function () {
            return view('new_item');
        })->name('new_item');
        Route::get('/all_doctors', 'AdminController@all_doctors')->name('admin_doctors');

        //doctor->Admin
        Route::get('/lab/approval/doctors', 'AdminController@lab_approval_doctor')->name('lab_approval_doctor');
        Route::get('assign/doctor/for/lab/{id}', 'AdminController@assign_doctor_for_lab')->name('assign_doctor_for_lab');
        Route::get('deactive/doctor/for/lab/{id}', 'AdminController@deactive_doctor_for_lab')->name('deactive_doctor_for_lab');
        Route::get('/doctors/all/doctors', 'AdminController@all_doctor_index')->name('all_doctors');
        Route::get('/doctors/online/doctors', 'AdminController@online_docs')->name('online_docs');
        Route::get('/doctors/all/pending/contract', 'AdminController@pending_contract')->name('pending_contract');
        Route::post('/doctors/all/doctors', 'AdminController@all_doctor_index')->name('all_doctors');
        Route::get('/doctors/all/doctors/view/{id}', 'AdminController@all_doctor_view')->name('all_doctor_view');
        Route::get('/doctors/pending/request/view/{id}', 'AdminController@doctor_pending_request_view')->name('doctor_pending_request_view');
        Route::get('/doctors/deactivate/{id}', 'AdminController@deactivate')->name('deactivate');
        Route::post('/doctors/Activate', 'AdminController@Activate')->name('Activate');
        Route::post('/doctors/send_mail', 'AdminController@send_mail');
        Route::post('/admin/activate_user', 'AdminController@activate_user')->name('activate_user');
        Route::post('/admin/deactivate_user', 'AdminController@deactivate_user')->name('deactivate_user');
        Route::get('/doctors/percentage/{id}', 'AdminController@change_percentage')->name('change_percentage');
        Route::post('/doctors/percentage/{id}', 'AdminController@change_percentage')->name('change_percentage');

        //doctor_schedule
        Route::get('/doctors/doctor/schedule/{id}', 'AdminController@doctor_schedule')->name('doctor_schedule_admin');
        Route::get('/doctors/all/doctor/schedule', 'AdminController@all_doctor_schedule')->name('all_doctor_schedule')->middleware('admin_auth');
        Route::get('/doctors/all/doctor/appointments', 'AdminController@all_doctor_appointments')->name('all_doctor_appointments');





        //pending_doctor_requests
        Route::get('/doctors/pending/doctor/requests', 'AdminController@pending_doctor_requests')->name('pending_doctor_requests');
        Route::post('/doctors/pending/doctor/requests', 'AdminController@pending_doctor_requests')->name('pending_doctor_requests');
        Route::get('/doctors/blocked/doctors', 'AdminController@blocked_doctor')->name('blocked_doctor');
        Route::post('/doctors/blocked/doctors', 'AdminController@blocked_doctor')->name('blocked_doctor');
        Route::get('/doctors/pending/doctor/requests/view/{id}', 'AdminController@pending_doctor_view')->name('pending_doctor_view');





        //edit_permissions
        Route::get('/patient_records', 'AdminController@patient_records')->name('patient_records');
        Route::get('/doctor/Profile_update', 'AdminController@doctor_profile_update')->name('Doctor_profile_update');
        Route::get('/approvePatientRecord/{id}', 'ProfileController@adminApprovalPatUpdateProfile')->name('approvePatientRecord');
        Route::get('/adminCancelPatUpdateProfile/{id}', 'ProfileController@adminCancelPatUpdateProfile')->name('adminCancelPatUpdateProfile');
        Route::get('/adminCancelDocUpdateProfile/{id}', 'ProfileController@adminCancelDocUpdateProfile')->name('adminCancelDocUpdateProfile');

        //All-sessions-Admin
        Route::get('admin/all/sessions/record', 'SessionController@all_sessions_record')->name('all_sessions_record');
        Route::post('admin/all/sessions/record', 'SessionController@all_sessions_record')->name('all_sessions_record');
        Route::post('admin/all/sessions/record/spec', 'SessionController@all_sessions_record_with_spec')->name('all_sessions_record_with_spec');
        Route::get('admin/all/sessions/view/recording/{id}', 'SessionController@VideoRecordSession')->name('VideoRecordSession');
        Route::get('admin/inclinic/sessions', 'SessionController@inclinic_sessions')->name('admin_inclinic_sessions');

        //Manage Users
        Route::get('admin/manage/all/users/{id}', 'AdminController@manage_all_users')->name('manage_all_users')->middleware('admin_auth');
        Route::get('admin/manage/all/users', 'AdminController@manage_all_user')->name('manage_all_user')->middleware('admin_auth');



        Route::get('admin/admin/details/{id}','AdminController@dash_admin_details')->name('dash_admin_details');
    	Route::post('/admin/store', 'AdminController@dash_store_admin')->name('dash_store_admin');
        Route::post('/editor/store', 'AdminController@dash_store_editor')->name('dash_store_editor');
        Route::get('/admin/details/{type}/{id}', 'AdminController@dash_view_user')->name('dash_view_user');

        Route::get('/pending_doctors', 'AdminController@pending_doctors')->name('pending_doctors');
        Route::get('/add_category', 'AdminController@add_category')->name('add_category');
        Route::get('/all-states', 'AdminController@allStates')->name('all-states');
        Route::get('/all_users', 'AdminController@all_users')->name('manage_users');
        Route::get('/all_prod_del_req', 'AllProductsController@all_prod_del_req')->name('all_prod_del_req');
        Route::get('admin/all/prod/del/req', 'AllProductsController@dash_all_prod_del_req')->name('dash_all_prod_del_req');
        Route::get('/all_patients', 'AdminController@all_patients')->name('admin_patients');
        Route::get('/view_terms_of_use', 'AdminController@view_terms_of_use')->name('view_terms_of_use');
        Route::get('/view_psychiatrist_services', 'AdminController@view_psychiatrist_services')->name('view_psychiatrist_services');
        Route::get('/add_terms_of_use', 'AdminController@terms_of_use')->name('terms_of_use');
        Route::get('/view_privacy_policy', 'AdminController@view_privacy_policy')->name('view_privacy_policy');
        Route::get('/add_privacy_policy', 'AdminController@privacy_policy');
        Route::get('/admin_contact', 'AdminController@admin_contact')->name('admin_contact');
        Route::post('add_to_cart', 'PatientController@add_to_cart');
        Route::post('/get_states', 'UserController@get_states');
        Route::post('/get_states_v2', 'UserController@get_states_v2');
        Route::post('/get_cities', 'UserController@get_cities');
        Route::get('/checkout', function () {
            return view('checkout');
        })->name('checkout');
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('patient/dash', 'HomeController@new_pat_index')->name('New_Patient_Dashboard');
        Route::get('doctor/dash', 'HomeController@doctor_dashboard')->name('doctor_dashboard');
        Route::get('/admin_doctor_calendar', 'AdminController@doctor_calendar')->name('admin_doctor_calendar');
        Route::get('/doctor_calendar', 'DoctorScheduleController@doctor_calendar')->name('doctor_calendar');
        Route::get('/waiting_room', 'DoctorController@doc_waiting_room')->name('doc_waiting_room');
        Route::get('/patients', 'DoctorController@all_patients')->name('patients');
        Route::get('/all_appointments', 'AppointmentController@index')->name('appointment.index');
        Route::get('/appointment/{id}', 'AppointmentController@view')->name('appointment.view');
        Route::get('/sessions_record', 'SessionController@record')->name('sessions.record');
        Route::get('/view-recording/{id}', 'SessionController@videoRecording')->name('sessions.record.view');
        Route::get('/refill_requests', 'DoctorController@get_refill_requests')->name('get_refill_requests');
        Route::get('/doctors', 'DoctorController@all')->name('doctors.all');
        Route::resource('orders', 'TblOrdersController');
        Route::get('/wallet_page', 'PaymentController@wallet')->name('wallet_page');
        Route::get('/load_all_appointments', 'AppointmentController@loadAllAppointment');
        Route::get('/load_all_appointments_home_page', 'AppointmentController@load_all_appointments_home_page');
        Route::get('/edit_doctor_profile', 'ProfileController@getDoctorDetail')->name('edit_doctor_profile');
        Route::get('/edit_patient_profile', 'ProfileController@getPatientProfileDetail')->name('edit_patient_profile');
        Route::post('/noteUpdate', 'NotificationController@noteUpdate')->name("noteUpdate");
        Route::get('/notification', 'NotificationController@allNotification');
        Route::get('/ReadNotification/{id}', 'NotificationController@ReadNotification');
        Route::get('/Toaster', 'NotificationController@Toaster');


        Route::get('/decode_requisition', 'WelcomeController@decode_requisition');
        Route::get('/fetchPendingResults', 'QuestController@fetchPendingResults');
        Route::get('/cart', 'PharmacyController@cart');
        // Route::get('/my/cart', 'PharmacyController@my_cart')->name('user_cart');
        Route::get('/my/cart', 'PharmacyController@new_my_cart')->name('user_cart');
        Route::get('/checkout', 'PharmacyController@checkout');
        Route::get('/complete_order/{order_id}', 'PharmacyController@complete_order');
        Route::post('/create_order', 'PharmacyController@create_order');
        Route::post('new/create_order', 'PharmacyController@create_new_order');
        Route::get('/get_products_name/{keyword}', 'AllProductsController@get_products_name');
        Route::get('/get_faq_name/{keyword}', 'AllProductsController@get_faq_name');
        Route::get('/get_parent_category_names/{keyword}', 'AllProductsController@get_parent_category_names');
        Route::get('/getImagingServicesSelect', 'AllProductsController@getImagingServicesSelect');
        Route::get('/getImagingLocationSelect', 'AllProductsController@getImagingLocationSelect');
        Route::get('/get_cart_content/{id}/{quantity}/{mode}', 'PharmacyController@get_cart_content');
        Route::get('/get_cart_counter', 'PharmacyController@get_cart_counter');
        Route::get('/remove_single_cart_item/{product_id}/{cart_row_id}', 'PharmacyController@remove_single_cart_item');
        Route::get('/update_cart_item_quantity/{product_id}/{quantity}', 'PharmacyController@update_cart_item_quantity');
        Route::get('/getUserCartData', 'PharmacyController@getUserCartData');
        Route::get('/getPrescribedProducts', 'PharmacyController@getPrescribedProducts');
        Route::get('/updateCheckoutStatus/{product_id}/{status}/{cart_row_id}', 'PharmacyController@updateCheckoutStatus');
        Route::get("/appointment_time_check", "AppointmentController@appointment_time_check");
        Route::get('/destroy/specialization/{id}', 'AdminController@destroySpecialization')->name('destroySpec');
        Route::get('/view/specialization', 'AdminController@viewSpecialization')->name('viewSpec');
        Route::get('/add/specialization', 'AdminController@addSpecialization')->name('addSpec');
        Route::post('/add/product_category', 'AdminController@add_product_category')->name('add_product_category');
        Route::get('/add/specialization/price', 'AdminController@addSpecializationPrice')->name('addSpecPrice');
        Route::get('/edit/specialization/{id}', 'AdminController@editSpecialization')->name('editSpec');
        Route::get('/edit/specialization/price/{id}', 'AdminController@editSpecializationPrice')->name('editPriceSpec');
        Route::POST('/store/specialization/price', 'AdminController@storeSpecializationPrice')->name('storeSpecPrice');
        Route::POST('/store/specialization', 'AdminController@storeSpecialization')->name('storeSpec');
        Route::POST('/store/PsychiatryService', 'AdminController@storePsychiatryService')->name('storePsycService');
        Route::get('/add/PsychiatryService', 'AdminController@addPsychiatryService')->name('addPsycService');
        Route::get('/select/specialization', 'AppointmentController@appointment_specialization')->name('select.specialization');
        Route::get('/select/doctor/{id}', 'AppointmentController@doctorForAppointment')->name('select.doctor');
        Route::get('/delete/specialization/{id}', 'AdminController@delete_specialization_price')->name('delete.specialization');
        //Route::get('/{username}', 'ProfileController@view_profile')->name('profile');
        Route::resource('finance', 'FinanceController');
        Route::post('term/agree', 'HomeController@term_agree')->name('term.agree');
        Route::post('/send_email', 'AdminController@send_email');
        Route::get('/evisit/specialization', 'SessionController@evisit_specialization')->name('evisit.specialization');

        Route::get('/all_patients_name', 'AdminController@all_patients_name');
        Route::get('/all_patients_reg', 'AdminController@all_patients_reg');
        Route::get('/all_patients_visit', 'AdminController@all_patients_visit');
        Route::get('/patient_by_state', 'AdminController@patient_by_state');
        Route::get('/patient_by_state/{state}', 'AdminController@patient_by_state');


        Route::get('/admin_contact/{id}', 'AdminController@admin_contact_msg')->name('admin_contact_msg');
        Route::get('/send_contract/{id}', 'AdminController@send_contract')->name('send_contract');
        Route::get('admin/send_contract/{id}', 'AdminController@admin_send_contract')->name('admin_send_contract');
        Route::get('/approve/{id}', 'AdminController@approve_doctor')->name('approve_doctor');
        Route::get('/decline/{id}', 'AdminController@decline_doctor')->name('decline_doctor');
        Route::get('/ban/{id}', 'AdminController@ban_doctor')->name('ban_doctor');
        Route::get('/activity_log/{id}', 'AdminController@activity_log_doctor')->name('activity_log_doctor');
        Route::get('/doctor/{id}', 'AdminController@doctor_full_details')->name('doctor_full_details');
        Route::get('/doctor/percentage/{id}', 'AdminController@doctor_percentage')->name('doctor.percentage');
        Route::post('/add/doctor/percentage/{id}', 'AdminController@add_doctor_percentage')->name('add.doctor.percentage');
        Route::post('/admin_store', 'AdminController@store_admin')->name('store_admin');
        Route::post('/editor_store', 'AdminController@store_editor')->name('store_editor');

        Route::get('/user_details/{id}', 'AdminController@user_details')->name('user_details');
        Route::get('/prod_del_request/{id}', 'AdminController@temp_del_prod')->name('prod_del_request');

        Route::get('/final_del_prod/{id}', 'AdminController@final_del_prod')->name('final_del_prod');
        Route::get('/reset_del_prod/{id}', 'AdminController@reset_del_prod')->name('reset_del_prod');
        Route::get('/add_approve_prod/{id}', 'AdminController@add_approve_prod')->name('add_approve_prod');

        Route::post('/submit_terms_of_use', 'AdminController@submit_terms_of_use')->name('submit_terms_of_use');
        Route::post('/submit_privacy_policy', 'AdminController@submit_privacy_policy')->name('submit_privacy_policy');

        Route::get('/terms_of_use/delete/{id}', 'AdminController@delete_terms')->name('delete_terms');
        Route::get('/terms_of_use/update/{id}', 'AdminController@edit_terms')->name('edit_terms');
        Route::get('/term_of_use/update/{id}', 'AdminController@edit_term')->name('edit_term');
        Route::put('/terms_of_use/update/{id}', 'AdminController@update_terms')->name('update_terms');
        Route::put('/term_of_use/update/{id}', 'AdminController@update_term')->name('update_term');
        Route::get('/terms_of_use/show/{id}', 'AdminController@show_terms')->name('show_terms');

        Route::get('/privacy_policy/delete/{id}', 'AdminController@delete_policy')->name('delete_policy');
        Route::get('/privacy_policy/update/{id}', 'AdminController@edit_policy')->name('edit_policy');
        Route::put('/privacy_policy/update/{id}', 'AdminController@update_policy')->name('update_policy');
        Route::get('/privacy_policy/show/{id}', 'AdminController@show_policy')->name('show_policy');

        Route::get('/check/orders/items/{type}/{id}', 'AdminController@allOrdersDetails')->name('order.details');
        Route::get('/activate_state/{id}', 'AdminController@activateStates')->name('activate_state');
        Route::get('/deactivate_state/{id}', 'AdminController@deactivateStates')->name('deactivate_state');
        Route::get('/doctors_admin', function () {
            return view('doctors_admin');
        })->name('doctors_admin');
        Route::get('/pending_doctor_detail/{id}', 'AdminController@pending_doctor_detail')->name('pending_doctor_detail');


        Route::post('/wallet_page', 'PaymentController@wallet')->name('wallet_page');

        Route::get('/inbox', function () {
            return view('inbox');
        })->name('inbox');
        // Route::put('/edit_patient_profile', 'ProfileController@uProfile')->name('edit_patient_profile');
        Route::put('/edit_doctor_profile', 'ProfileController@updateDoctorProfile')->name('edit_doctor_profile');
        Route::post('/change_password', 'UserController@change_password')->name('change_password');
        Route::post('/addEvent', 'DoctorScheduleController@addEvent')->name('addEvent');
        Route::put('/updateEvent', 'DoctorScheduleController@updateEvent')->name('updateEvent');
        Route::post('/fetchDoctorAppointmentOnCalendar', 'DoctorScheduleController@fetchAppointment')->name('fetchDoctorAppointmentOnCalendar');
        Route::get('/payment/appointment/{id}', 'AppointmentController@appointment_payment_page')->name('payment.appointment');
        Route::post('/appointment_payment', 'AppointmentController@appointment_payment')->name('appointment_payment');
        Route::get('/appointment/sepecialization/{id}/doctor/{doc}', 'AppointmentController@create')->name('book.appointment');
        Route::put('/book_appointment_update/{id}', 'AppointmentController@update')->name('appointment.update');
        Route::post('/book_appointment_store', 'AppointmentController@store')->name('appointment.store');
        Route::get('/cancel_appointment/{id}', 'AppointmentController@cancel')->name('cancel_appointment');
        Route::post('/booked', 'AppointmentController@booked');
        Route::post('/bookedappoint', 'AppointmentController@bookedappoint');
        Route::post('/getappointments', 'AppointmentController@getappointments');
        Route::post('/another_doctor', 'AppointmentController@getanotherdoctor');
        Route::post('/another_appointment', 'AppointmentController@getanotherappointment');
        Route::get('/payment/session/{id}', 'DoctorController@session_payment_page')->name('session_payment_page');
        Route::get('/online/doctors/{id}', 'DoctorController@getonlinedoctors')->name('online_doctors');
        Route::get('load/online/doctors/{id}', 'DoctorController@loadonlinedoctors')->name('load.online.doctors');
        Route::post('/store_symptoms', 'DoctorController@store_symptoms_inquiry')->name('inquiry.store');
        Route::post('/paient/waiting_room', 'DoctorController@session_payment')->name('session_payment');
        Route::get('/waiting_room_store', 'DoctorController@waiting_room_store')->name('waiting_room_store');
        Route::get('/waiting_room/{id}', 'DoctorController@waiting_room')->name('waiting_room');
        Route::post('/grant_refill', 'DoctorController@grant_refill')->name('grant_refill');
        Route::post('/get_dosage_info', 'DoctorController@get_dosage_info');
        Route::post('/destroy_waiting', 'DoctorController@destroy_waiting');
        Route::get('/req_session/{id}', 'DoctorController@req_session')->name('req_session');
        Route::get('send/session/req/{id}', 'DoctorController@send_session_req')->name('send_session_req');
        Route::post('send/session/add/doc/schedule', 'DoctorController@request_session_schedule')->name('request_session_schedule');

        Route::get('/waiting_room_load/{id}', 'DoctorController@waiting_room_load');
        Route::get('/reschedule/appointment/{app_id}/specialization/{spec_id}', 'PatientController@reschedule_appointment')->name('reschedule.appointment');
        Route::get('/reschedule/appointment/{app_id}/specialization/{spec_id}/doctor/{doc_id}', 'AppointmentController@reschedule_appoint')->name('reschedule.form');
        Route::get('/waiting_room_load_data', 'DoctorController@load_data_waiting_room');
        Route::get('/modal_change_status', 'DoctorController@modal_change_status');
        Route::get('/change_status', 'DoctorController@change_status');
        Route::get('/check_status', 'DoctorController@check_status');
        Route::get('/change_doc_online_status', 'DoctorController@change_doc_online_status');
        Route::post('/check_queue', 'DoctorController@check_queue');
        Route::post('/waiting_pat', 'DoctorController@waiting_pat');
        Route::get('/doc_pay_details/{id}', 'DoctorController@doc_pay_details')->name('doc_pay_details');
        Route::resource('schedule_add', 'DoctorScheduleController');
        Route::get('/doctor_schedule', 'DoctorScheduleController@index')->name('doctor_schedule');
        Route::get('/doctor_schedule_calendar', 'DoctorScheduleController@calendar')->name('doctor_schedule_calendar');
        Route::get('add_schedule', 'DoctorScheduleController@schedulePage')->name('add_schedule');
        Route::post('/schedule_list', 'DoctorScheduleController@schedule_list');
        Route::get('edit_schedule/{id}', 'DoctorScheduleController@edit')->name('edit_schedule');
        Route::put('edit_schedule/{id}', 'DoctorScheduleController@update')->name('update_schedule');
        Route::get('doctor_schedule/{id}', 'DoctorScheduleController@destroy')->name('del_schedule');
        Route::post('/timing', 'DoctorScheduleController@timing')->name('timing');
        Route::post('/checkAlredyBookTiming', 'DoctorScheduleController@checkAlredyBookTiming')->name('checkAlredyBookTiming');
        Route::get('/holiday/{id}', 'DoctorScheduleController@add_holiday')->name('holiday');
        Route::post('/doctor_schedule', 'DoctorScheduleController@store_holiday')->name('holiday_store');
        Route::post('/calendar_holiday', 'DoctorScheduleController@store_calendar_holiday');
        Route::post('/all_holidays', 'DoctorScheduleController@all_holidays');



        Route::get('/filter/session/record/{from_date}/to/{to_date}', 'SessionController@filter_session_record')->name('filter.session.record');
        Route::get('/sessions', 'SessionController@all')->name('sessions.all');
        Route::get('/sessions/{id}', 'SessionController@show')->name('session.show');

        Route::post('/change_session_status', 'SessionController@change_session_status');
        Route::post('/check_session_status', 'SessionController@check_status');
        Route::get('/feedback/{session}', 'SessionController@feedback')->name('feedback');
        Route::post('/feedback/submit', 'SessionController@feedback_submit')->name('feedback_submit');
        Route::post('/new/feedback/submit', 'SessionController@new_feedback_submit')->name('new_feedback_submit');
        Route::post('/update_rem_time', 'SessionController@update_rem_time');
        Route::post('/check_cart_status', 'SessionController@check_cart_status');
        Route::post('/get_locations', 'SessionController@get_locations');
        Route::post('/update_feedback_status', 'SessionController@update_feedback_status');
        Route::post('/minute_rem_timer', 'SessionController@minute_rem_timer');


        //Link for mail
        Route::get('/video_session/{user_id}/{doc_id}', 'VideoController@join_video_session');
        //VideoController
        Route::post('/doctor/video', 'VideoController@index')->name('video.index');
        Route::post('/video/post', 'VideoController@index')->name('video.index.post');
        Route::post('/add_video_links', 'VideoController@add_video_links');

        Route::get('/patient/not/call/join/{id}', 'VideoController@patientNotJoiningCall');


        //PatientController
        Route::get('/patient_record/{id}', 'PatientController@view_patient_record')->name('patient_record');
        Route::get('/patient_payment_details/{id}', 'PatientController@patient_payment_details')->name('patient_payment_details');

        Route::post('/request_refill', 'PatientController@request_refill')->name('request_refill');
        Route::get('/referal_requests', 'PatientController@referal_requests');
        Route::get('/accept_referal/{id}', 'PatientController@accept_referal')->name('accept_referal');
        Route::get('/decline_referal/{id}', 'PatientController@decline_referal')->name('decline_referal');
        Route::get('/session_requested', 'PatientController@session_requested');


        //Resources
        Route::resource('mapMarkers', 'MapMarkersController');
        Route::resource('payment', 'PaymentController');
        Route::resource('mentalConditions', 'MentalConditionsController');
        Route::resource('faqs', 'TblFaqController');
        Route::get('productCategory', 'AdminController@product_category');
        //TblOrdersController
        Route::get('/lab_order/{id}', 'TblOrdersController@lab_order')->name('lab_order');
        Route::get('/lab/order/{id}', 'TblOrdersController@dash_lab_order')->name('dash_lab_order');
        Route::post('/lab/admin/add/note/{id}', 'TblOrdersController@lab_admin_add_note')->name('lab_admin_add_note');
        Route::post('/upload_lab_repot/{id}', 'TblOrdersController@upload_lab_report')->name('upload_lab_report');
        Route::get('/lab_orders_filter', 'TblOrdersController@labFilter');
        Route::get('/imaging_orders_filter', 'TblOrdersController@imagingFilter');
        Route::get('/imaging_order/{id}', 'TblOrdersController@imaging_order')->name('imaging_order');
        Route::get('/pharmacy_order/{id}', 'TblOrdersController@pharmacy_order')->name('pharmacy_order');
        Route::get('/pharmacy/order/{id}', 'TblOrdersController@dash_pharmacy_order')->name('dash_pharmacy_order');
        Route::get('/inclinic/pharmacy/order/{id}', 'TblOrdersController@dash_inclinic_pharmacy_order')->name('dash_inclinic_pharmacy_order');
        Route::post('/upload_imaging_report/{id}', 'TblOrdersController@upload_imaging_report')->name('upload_imaging_report');
        Route::get('patient/order/detail/{id}', 'TblOrdersController@dash_show')->name('patient_order_detail');


        Route::get('/quest_failed_request_details/{result_id}', 'QuestController@show_failed_requests')->name('quest_failed_request_details');
        Route::get('/resolve_request/{id}', 'QuestController@resolve_request')->name('resolve_request');
        Route::get('/hl7encode', 'HL7Controller@hl7Encode')->name('hl7encode');
        Route::get('/viewQuestTestReport/{get_request_id}', 'QuestController@viewQuestTestReport')->name('viewQuestTestReport');
        Route::get('/lab_reports/{patient_id}', 'QuestController@patient_lab_reports');
        Route::get('/lab_report_using_base64_req', 'QuestController@get_lab_report_using_base64_req');
        Route::post('/submit_base64_req', 'QuestController@submit_base64_req');
        Route::get('/lab_report_using_hl7_req', 'QuestController@get_lab_report_using_hl7_req');
        Route::post('/submit_hl7_req', 'QuestController@submit_hl7_req');
        // QuestLabTest Route
        Route::get('/editQuestLabTest/{id}', 'AllProductsController@editQuestLabTest');
        Route::post('/updateQuestLabTest', 'AllProductsController@updateQuestLabTest')->name('updateQuestLabTest');


        Route::post('/submit_pending_approvals', 'PharmacyController@online_lab_order_create')->name('submit_pending_approvals');
        Route::post('/disapprovedLabTest', 'PharmacyController@disapprovedLabTest');

        // RxOutreach Medication
        Route::resource('medicineUOM', 'MedicineUOMController');
        Route::get('/uploadMedicineByCSV', 'MedicineImportController@index');
        Route::post("/uploadMedicineByCSV", "MedicineImportController@uploadFile");
        Route::get("/viewMedicines", "MedicineImportController@viewMedicine");
        Route::post("/medicinePricingVariation", "MedicineImportController@storeMedicineVariation");
        Route::get("/getRxMedicine", "MedicineImportController@getRxMedicine");
        Route::put("/editRxMedicine/{id}", "MedicineImportController@editRxMedicine");
        Route::delete("/deleteRxMedicine/{id}", "MedicineImportController@deleteRxMedicine");

        //dashboard search
        Route::post('/searchProductMedicine', "HomeController@searchProductMedicine")->name("searchProductMedicine");
        Route::post('/searchProductImaging', "HomeController@searchProductImaging")->name("searchProductImaging");
        Route::post('/searchProductLab', "HomeController@searchProductLab")->name("searchProductLab");
        Route::post('/searchPatient', "HomeController@searchPatient")->name("searchPatient");
        Route::post('/mainSearch', "WelcomeController@mainSearch")->name("mainSearch");
    });

    Route::get('/generate-pdf', [AllProductsController::class, 'generatePDF']);
    Route::post('/generate-sessionspdf', [AllProductsController::class, 'generate_sessions_pdf']);
    Route::post('/generate-sessionscsv', [AllProductsController::class, 'generate_sessions_csv']);
    Route::post('/generate-prescriptionspdf', [AllProductsController::class, 'generate_prescriptions_pdf']);
    Route::post('/generate-prescriptionscsv', [AllProductsController::class, 'generate_prescriptions_csv']);
    Route::post('/generate-onlinepdf', [AllProductsController::class, 'generate_online_pdf']);
    Route::post('/generate-onlinecsv', [AllProductsController::class, 'generate_online_csv']);
    Route::post('/generate-doc_onlinepdf',"FinanceController@generate_doc_online_pdf");
    Route::post('/generate-doc_onlinecsv',"FinanceController@generate_doc_online_csv");
    Route::post('/generate-doc_evisitcsv',"FinanceController@generate_doc_evisit_csv");
    Route::post('/generate-doc_evisitpdf',"FinanceController@generate_doc_evisit_pdf");
    Route::post('/generate-doc_payablecsv',"FinanceController@generate_doc_payable_csv");
    Route::post('/generate-doc_payablepdf',"FinanceController@generate_doc_payable_pdf");
    Route::post('/generate-doc_paidcsv',"FinanceController@generate_doc_paid_csv");
    Route::post('/generate-doc_paidpdf',"FinanceController@generate_doc_paid_pdf");
    Route::post('/generate-all_imaging_record_csv', [AllProductsController::class, 'generate_all_imaging_record_csv']);
    Route::post('/generate_all_medicine_record_csv', [AllProductsController::class, 'generate_all_medicine_record_csv']);
});
