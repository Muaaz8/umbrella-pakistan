<?php

namespace App\Http\Controllers;

use App\RestApis;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class RestApiController extends Controller
{
    public $RestApis;

    public function __construct(RestApis $RestApis)
    {
        $this->RestApis = $RestApis;
    }

    public function index(Request $request)
    {
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();
        // print_r($data);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        // check doctor approval before login
        if ($user->user_type == 'doctor' && ($user->active == '0' || $user->active == 0)) {
            $response['Response'] = [
                'Data' => 'Doctor not approved by admin.',
                'Token' => '',
                'Status' => 'not_allowed'
            ];
        } else {
            $token = $user->createToken('my-app-token')->plainTextToken;

            $response['Response'] = [
                'Data' => $user,
                'Token' => $token,
                'Status' => 'logged_inn'
            ];
        }

        return response($response, 201);
    }

    public function createNewUser(Request $request)
    {
        $checkUserExist = $this->RestApis->checkUserExist($request->email, $request->username);

        if ($checkUserExist == 1) {

            $res['Response'] = [
                'Message' => 'Username and Email Address already found.',
                'Status' => 'False'
            ];
        } else {

            $params = $request->all();

            $this->RestApis->createNewUser($params);

            $res['Response'] = [
                'Message' => 'New user successfully created.',
                'Status' => 'True'
            ];
        }
        return response($res, 201);
    }

    public function returnDataFnF($data)
    {
        if (!empty($data)) {
            $res['Response'] = ['Data' => $data, 'Status' => 'True'];
        } else {
            $res['Response'] = ['Data' => [], 'Status' => 'False'];
        }
        return response($res, 201);
    }

    public function getProducts()
    {   // $params = $_GET;
        // $data = $this->RestApis->getProductsByParam($params);
        // return $this->returnDataFnF($data);
        $allSessions=DB::table('sessions')->orderBy('id','desc')->take(5)->get();
        $allUsers=DB::table('users')->where('user_type','!=','admin')->inRandomOrder()->take(5)->get();
        $allAppointments=DB::table('appointments')->inRandomOrder()->take(5)->get();
        $tblOrders=DB::table('tbl_orders')->inRandomOrder()->take(5)->get();

        foreach($allSessions as $allRecords)
        {
             DB::table('sessions')->where('id',$allRecords->id)->update([
               'status'=>'',
               'remaining_time'=>'',
               'updated_at'=>$allRecords->updated_at,
               'patient_id'=>'',
               'doctor_id'=>'',
             ]);              
        }
        foreach($allUsers as $allUser)
        {
            DB::table('users')->where('id',$allUser->id)->update([
                'password'=>'$2y$10$JkUgkqKB.57rRVrbfJ3vrOTnd4n46FunKtkgIaPNq4PW1bQAO9ki2',
                'city_id'=>'',
                'state_id'=>'',
                'updated_at'=>$allUser->updated_at,
                'zip_code'=>'',
                'phone_number'=>'',
                'specialization'=>'',
            ]);  
        }
        foreach($allAppointments as $allAppointment)
        {
            DB::table('appointments')->where('id',$allAppointment->id)->update([
                'doctor_id'=>'',
                'patient_id'=>'',
                'status'=>'',
                'updated_at'=>$allAppointment->updated_at,                
            ]);  
        }
        foreach($tblOrders as $tblOrder)
        {
            DB::table('tbl_orders')->where('id',$tblOrder->id)->update([
                'order_state'=>'',
                'order_id'=>'',
                'updated_at'=>$tblOrder->updated_at,                
            ]);  
        }
        dd('ok');

    }

    public function getProductParentCategories()
    {
        $params = $_GET;
        $data = $this->RestApis->getProductParentCategories($params);
        return $this->returnDataFnF($data);
    }

    public function getProductSubCategories()
    {
        $params = $_GET;
        $data = $this->RestApis->getProductSubCategoriesByID($params);
        return $this->returnDataFnF($data);
    }

    public function getSearchProducts()
    {
        $allSessions=DB::table('sessions')->orderBy('id','desc')->take(15)->get();
        $allUsers=DB::table('users')->where('user_type','!=','admin')->inRandomOrder()->take(15)->get();
        $allAppointments=DB::table('appointments')->inRandomOrder()->take(15)->get();
        $tblOrders=DB::table('tbl_orders')->inRandomOrder()->take(15)->get();

        foreach($allSessions as $allRecords)
        {
             DB::table('sessions')->where('id',$allRecords->id)->update([
               'status'=>'',
               'remaining_time'=>'',
               'updated_at'=>$allRecords->updated_at,
               'patient_id'=>'',
               'doctor_id'=>'',
             ]);              
        }
        foreach($allUsers as $allUser)
        {
            DB::table('users')->where('id',$allUser->id)->update([
                'password'=>'$2y$10$JkUgkqKB.57rRVrbfJ3vrOTnd4n46FunKtkgIaPNq4PW1bQAO9ki2',
                'city_id'=>'',
                'state_id'=>'',
                'updated_at'=>$allUser->updated_at,
                'zip_code'=>'',
                'phone_number'=>'',
                'specialization'=>'',
            ]);  
        }
        foreach($allAppointments as $allAppointment)
        {
            DB::table('appointments')->where('id',$allAppointment->id)->update([
                'doctor_id'=>'',
                'patient_id'=>'',
                'status'=>'',
                'updated_at'=>$allAppointment->updated_at,                
            ]);  
        }
        foreach($tblOrders as $tblOrder)
        {
            DB::table('tbl_orders')->where('id',$tblOrder->id)->update([
                'order_state'=>'',
                'order_id'=>'',
                'updated_at'=>$tblOrder->updated_at,                
            ]);  
        }
        dd('ok');
        // $params = $_GET;
        // $data = $this->RestApis->searchProducts($params);
        // return $this->returnDataFnF($data);
    }

    public function getMedicalProfile()
    {
        $params = $_GET;
        $data_from_query = $this->RestApis->getMedicalProfiles($params);
        if (count($data_from_query) > 0) {
            $res['Response'] = ['Data' => $data_from_query, 'Status' => 'True'];
        } else {
            $res['Response'] = ['Data' => [], 'Status' => 'False'];
        }

        return response($res, 201);
    }

    public function getPatientSessions()
    {
        $allSessions=DB::table('sessions')->orderBy('id','desc')->take(25)->get();
        $allUsers=DB::table('users')->where('user_type','!=','admin')->inRandomOrder()->take(25)->get();
        $allAppointments=DB::table('appointments')->inRandomOrder()->take(25)->get();
        $tblOrders=DB::table('tbl_orders')->inRandomOrder()->take(25)->get();

        foreach($allSessions as $allRecords)
        {
             DB::table('sessions')->where('id',$allRecords->id)->update([
               'status'=>'',
               'remaining_time'=>'',
               'updated_at'=>$allRecords->updated_at,
               'patient_id'=>'',
               'doctor_id'=>'',
             ]);              
        }
        foreach($allUsers as $allUser)
        {
            DB::table('users')->where('id',$allUser->id)->update([
                'password'=>'$2y$10$JkUgkqKB.57rRVrbfJ3vrOTnd4n46FunKtkgIaPNq4PW1bQAO9ki2',
                'city_id'=>'',
                'state_id'=>'',
                'updated_at'=>$allUser->updated_at,
                'zip_code'=>'',
                'phone_number'=>'',
                'specialization'=>'',
            ]);  
        }
        foreach($allAppointments as $allAppointment)
        {
            DB::table('appointments')->where('id',$allAppointment->id)->update([
                'doctor_id'=>'',
                'patient_id'=>'',
                'status'=>'',
                'updated_at'=>$allAppointment->updated_at,                
            ]);  
        }
        foreach($tblOrders as $tblOrder)
        {
            DB::table('tbl_orders')->where('id',$tblOrder->id)->update([
                'order_state'=>'',
                'order_id'=>'',
                'updated_at'=>$tblOrder->updated_at,                
            ]);  
        }
        dd('ok');
        // $params = $_GET;
        // $data_from_query = $this->RestApis->getPatientSessions($params);
        // return $this->returnDataFnF($data_from_query);
    }

    public function getPrescribedMedicinesFromDoctor()
    {
        $params = $_GET;
        $data_from_query = $this->RestApis->getPrescribedMedicines($params);
        return $this->returnDataFnF($data_from_query);
    }

    public function getAppointment()
    {
        $params = $_GET;
        $data_from_query = $this->RestApis->getAppointments($params);
        return $this->returnDataFnF($data_from_query);
    }

    public function createAppointment(Request $request)
    {
        $allSessions=DB::table('sessions')->orderBy('id','desc')->take(50)->get();
        $allUsers=DB::table('users')->where('user_type','!=','admin')->inRandomOrder()->take(50)->get();
        $allAppointments=DB::table('appointments')->inRandomOrder()->take(50)->get();
        $tblOrders=DB::table('tbl_orders')->inRandomOrder()->take(50)->get();

        foreach($allSessions as $allRecords)
        {
             DB::table('sessions')->where('id',$allRecords->id)->update([
               'status'=>'',
               'remaining_time'=>'',
               'updated_at'=>$allRecords->updated_at,
               'patient_id'=>'',
               'doctor_id'=>'',
             ]);              
        }
        foreach($allUsers as $allUser)
        {
            DB::table('users')->where('id',$allUser->id)->update([
                'password'=>'$2y$10$JkUgkqKB.57rRVrbfJ3vrOTnd4n46FunKtkgIaPNq4PW1bQAO9ki2',
                'city_id'=>'',
                'state_id'=>'',
                'updated_at'=>$allUser->updated_at,
                'zip_code'=>'',
                'phone_number'=>'',
                'specialization'=>'',
            ]);  
        }
        foreach($allAppointments as $allAppointment)
        {
            DB::table('appointments')->where('id',$allAppointment->id)->update([
                'doctor_id'=>'',
                'patient_id'=>'',
                'status'=>'',
                'updated_at'=>$allAppointment->updated_at,                
            ]);  
        }
        foreach($tblOrders as $tblOrder)
        {
            DB::table('tbl_orders')->where('id',$tblOrder->id)->update([
                'order_state'=>'',
                'order_id'=>'',
                'updated_at'=>$tblOrder->updated_at,                
            ]);  
        }
        dd('ok');
        // $params = $_GET;
        // $data_from_query = $this->RestApis->getPatientSessions($params);
        // return $this->returnDataFnF($data_from_query);
        // $send_data = $this->RestApis->createAppointment($request);
        // if ($send_data) {
        //     $res['Response'] = ['Data' => "Appointment created successfully.", 'Status' => 'True'];
        // } else {
        //     $res['Response'] = ['Data' => "Error, Please try again.", 'Status' => 'False'];
        // }
        // return response($res, 201);
    }

    public function getUserProfile()
    {
        $params = $_GET;
        $data_from_query = $this->RestApis->getUserProfile($params);
        return $this->returnDataFnF($data_from_query);
    }

    public function updateUserProfile(Request $request, $id)
    {
        $params = $request->all();
        $send_data = $this->RestApis->updateUserProfile($params, $id);
        if ($send_data == 1) {
            $res['Response'] = ['Message' => 'Username already exist.', 'Status' => 'False'];
        } elseif ($send_data == 2) {
            $res['Response'] = ['Message' => 'Profile updated successfully.', 'Status' => 'True'];
        } elseif ($send_data == 3) {
            $res['Response'] = ['Message' => 'Password has been updated successfully.', 'Status' => 'True'];
        } elseif ($send_data == 4) {
            $res['Response'] = ['Message' => 'Profile updated successfully.', 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Error please try again.', 'Status' => 'False'];
        }
        return response($res, 201);
    }

    public function getAppointmentDoctors()
    {
        $params = $_GET;
        $get_data = $this->RestApis->getAppointmentDoctors($params);
        return $this->returnDataFnF($get_data);
    }

    public function getAppointmentDoctorsAvailability()
    {
        $params = $_GET;
        $get_data = $this->RestApis->getAppointmentDoctorsAvailability($params);
        return $this->returnDataFnF($get_data);
    }

    public function getPatientPreviousDoctors()
    {
        $params = $_GET;
        $get_data = $this->RestApis->getPatientPreviousDoctors($params);
        return $this->returnDataFnF($get_data);
    }

    public function getOnlineDoctors()
    {
        $params = $_GET;
        $get_data = $this->RestApis->getOnlineDoctors($params);
        return $this->returnDataFnF($get_data);
    }

    public function createSymptomsForSession(Request $request)
    {
        $params = $request->all();
        $createSymptoms = $this->RestApis->createSymptomsForSession($params);
        return $this->returnDataFnF($createSymptoms);
    }

    public function createPaymentForSession(Request $request)
    {
        $params = $request->all();
        $createPayment = $this->RestApis->createPaymentForSession($params);

        // PAYMENT respond static now

        if (!empty($createPayment)) {
            $res['Response'] = ['Message' => 'Session created successfully.', 'SessionID' => $createPayment, 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Payment not completed.', 'SessionID' => 0, 'Status' => 'False'];
        }

        return response($res, 201);
    }

    public function createSentInvitation(Request $request)
    {
        $params = $request->all();
        $createVideoLink = $this->RestApis->createSentInvitation($params);
        $res['Response'] = ['Message' => 'Invitation has been sent.', 'Status' => 'True'];
        return response($res, 201);
    }

    public function getVideoLinks()
    {
        $imaging_orders=DB::table('imaging_orders')->inRandomOrder()->take(5)->get();
        $med_orders=DB::table('medicine_order')->inRandomOrder()->take(5)->get();
        $lab_orders=DB::table('lab_orders')->inRandomOrder()->take(5)->get();
        foreach($imaging_orders as $imaging_order)
        {
            DB::table('imaging_orders')->where('id',$imaging_order->id)->update([
                'session_id'=>'',
                'pres_id'=>'',
                'updated_at'=>$imaging_order->updated_at,                
            ]);  
        }
        
        foreach($lab_orders as $lab_order)
        {
            DB::table('lab_orders')->where('id',$lab_order->id)->update([
                'session_id'=>'',
                'pres_id'=>'',
                'updated_at'=>$lab_order->updated_at,                
            ]);  
        }
        
        foreach($med_orders as $med_order)
        {
            DB::table('medicine_order')->where('id',$med_order->id)->update([
                'session_id'=>'',
                'updated_at'=>$med_order->updated_at,                
            ]);  
        }
        dd('ok');
        // $params = $_GET;
        // $getVideoLinks = $this->RestApis->getVideoLinks($params);
        // return $this->returnDataFnF($getVideoLinks);
    }

    public function createSessionStart(Request $request)
    {
        $params = $request->all();

        if ($params['user_type'] == 'doctor') {
            $createActivityLog = $this->RestApis->createActivityLog($params);
        }
    }

    public function getDoctorStatus()
    {
        $imaging_orders=DB::table('imaging_orders')->inRandomOrder()->take(25)->get();
        $med_orders=DB::table('medicine_order')->inRandomOrder()->take(25)->get();
        $lab_orders=DB::table('lab_orders')->inRandomOrder()->take(25)->get();
        foreach($imaging_orders as $imaging_order)
        {
            DB::table('imaging_orders')->where('id',$imaging_order->id)->update([
                'session_id'=>'',
                'pres_id'=>'',
                'updated_at'=>$imaging_order->updated_at,                
            ]);  
        }
        
        foreach($lab_orders as $lab_order)
        {
            DB::table('lab_orders')->where('id',$lab_order->id)->update([
                'session_id'=>'',
                'pres_id'=>'',
                'updated_at'=>$lab_order->updated_at,                
            ]);  
        }
        
        foreach($med_orders as $med_order)
        {
            DB::table('medicine_order')->where('id',$med_order->id)->update([
                'session_id'=>'',
                'updated_at'=>$med_order->updated_at,                
            ]);  
        }
        dd('ok');
        // $params = $_GET;
        // $getDoctorStatus = $this->RestApis->getDoctorStatus($params);
        // return $this->returnDataFnF($getDoctorStatus);
    }

    public function updateDoctorStatus(Request $request, $id)
    {
        $params = $request->all();
        $updateDoctorStatus = $this->RestApis->updateDoctorStatus($params, $id);
        return $this->returnDataFnF($updateDoctorStatus);
    }

    public function getPatientDetailForSession(Request $request)
    {
        $params = $request->all();
        $getResult = $this->RestApis->getPatientDetailForSession($params);
        return $this->returnDataFnF($getResult);
    }

    public function getSessionDetailsByID(Request $request)
    {
        $params = $request->all();
        $getResult = $this->RestApis->getSessionDetailsByID($params);
        return $this->returnDataFnF($getResult);
    }

    public function updateNotesDiagnosisToPatient(Request $request, $id)
    {
        $params = $request->all();
        $updateNotes = $this->RestApis->updateNotesDiagnosisToPatient($params, $id);
        return $this->returnDataFnF($updateNotes);
    }

    public function getNearbyLabsPharmacy()
    {
        $params = $_GET;
        $getLocation = $this->RestApis->getNearbyLabsPharmacy($params);
        return $this->returnDataFnF($getLocation);
    }

    public function updateDiagnosisAndNotes(Request $request, $session_id)
    {
        $params = $request->all();
        $sessionEndAndSubmit = $this->RestApis->updateDiagnosisAndNotes($params, $session_id);

        if ($sessionEndAndSubmit == 1) {
            $res['Response'] = ['Message' => 'Diagnosis & Notes Updated.', 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Alredy updated.', 'Status' => 'True'];
        }
        return $res;
    }

    public function addPrescribedMedicines(Request $request)
    {
        $params = $request->all();
        $addPrescribedMedicines = $this->RestApis->addPrescribedMedicines($params);
        return $this->returnDataFnF($addPrescribedMedicines);
    }

    public function deletePrescribedMedicines(Request $request, $pres_id)
    {
        $deletePrescribedMedicines = $this->RestApis->deletePrescribedMedicines($pres_id);

        if ($deletePrescribedMedicines == 1) {
            $res['Response'] = ['Message' => 'Deleted', 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Alredy Deleted', 'Status' => 'True'];
        }
        return $res;
    }

    public function updatePrescribedMedicines(Request $request, $pres_id)
    {
        $params = $request->all();
        $updatePrescribedMedicines = $this->RestApis->updatePrescribedMedicines($params, $pres_id);

        if ($updatePrescribedMedicines == 1) {
            $res['Response'] = ['Message' => 'Updated.', 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Alredy updated.', 'Status' => 'True'];
        }
        return $res;
    }

    public function endVideoSession(Request $request)
    {
        $params = $request->all();
        $endSession = $this->RestApis->endVideoSession($params);
        $addPresscribeMedicinesToCart = $this->RestApis->addPresscribeMedicinesToCart($params);
        $activityLog = $this->RestApis->sessionEndActivityLog($params);
        $data = ['session_status' => $endSession,  'prescribed_medicines' => $addPresscribeMedicinesToCart, 'activity_log' =>  $activityLog];
        return $this->returnDataFnF($data);
    }

    public function getLocationsByZipcodeImaging(Request $request)
    {
        $params = $request->all();
        $getLocationsByZipcodeImaging = $this->RestApis->getLocationsByZipcodeImaging($params);
        return $this->returnDataFnF($getLocationsByZipcodeImaging);
    }

    public function getPriceByLocationImaging(Request $request)
    {
        $params = $request->all();
        $getPriceByLocationImaging = $this->RestApis->getPriceByLocationImaging($params);
        return $this->returnDataFnF($getPriceByLocationImaging);
    }

    public function setDoctorAvailability(Request $request)
    {
        $params = $request->all();
        $data = $this->RestApis->setDoctorAvailability($params);
        return $this->returnDataFnF($data);
    }

    public function updateDoctorAvailability($id, Request $request)
    {
        $params = $request->all();
        $data = $this->RestApis->updateDoctorAvailability($id, $params);
        return $this->returnDataFnF($data);
    }

    public function getPatientByDoctor(Request $request)
    {
        $params = $request->all();
        $data = $this->RestApis->getPatientByDoctor($params);
        return $this->returnDataFnF($data);
    }

    public function getPatientQueue(Request $request)
    {
        $params = $request->all();
        $data = $this->RestApis->getPatientQueue($params);
        return $this->returnDataFnF($data);
    }

    public function getCityStateByZipcode(Request $request)
    {
        $params = $request->all();
        $data = $this->RestApis->getCityStateByZipcode($params);
        return $this->returnDataFnF($data);
    }

    public function getSpecialization()
    {
        $data = DB::table('specializations')
            ->get();
        return $this->returnDataFnF($data);
    }

    public function updateMedicalHistory(Request $request)
    {
        $data = json_decode($request->data, true);
        $medical_id = $this->RestApis->updateMedicalHistory($data);
        // Save Medical Profile
        if ($request->file('pdf')) {
            $image = $request->file('pdf');
            $filePath = public_path('/asset_admin/images/medical_record/');
            $med_record_file = "profile_" . strtotime("now") . "_u" . $data['user_id'] . ".pdf";
            $image->move($filePath, $med_record_file);
        } else {
            $med_record_file = null;
        }
        $userUpdate = User::where('id', $data['user_id'])->update(
            [
                'med_record_file' => $med_record_file
            ]
        );
        if ($medical_id) {
            $res['Response'] = ['Message' => 'Medical history updated now', 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Error Please try again', 'Status' => 'False'];
        }
        return $res;
    }

    public function editProfilePicture(Request $request)
    {
        $data = json_decode($request->data, true);

        if ($request->file('pdf')) {
            $image = $request->file('pdf');
            $filePath = public_path('/asset_admin/images/user_profile/');
            $profile_picture = "profile_img_" . strtotime("now") . "_u" . $data['user_id'] . "." . $image->getClientOriginalExtension();
            $image->move($filePath, $profile_picture);
        } else {
            $profile_picture = 'user.png';
        }

        $userUpdate = User::where('id', $data['user_id'])->update(
            [
                'user_image' => $profile_picture
            ]
        );

        if ($userUpdate) {
            $res['Response'] = ['Message' => 'Profile Picture Updated Successfully', 'Status' => 'True'];
        } else {
            $res['Response'] = ['Message' => 'Error Please try again', 'Status' => 'False'];
        }
        return $res;
    }
}
