<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\CursorPaginator;
use App\ActivityLog;
use App\DoctorLicense;
use App\DoctorSchedule;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Mail\ApprovedDoctor;
use App\Mail\SendContract;
use App\Mail\ResendContract;
use App\Mail\testingMail;
use App\Mail\SendEmail;
use App\Models\AllProducts;
use App\Models\PhysicalLocations;
use App\Models\ContactForm;
use App\Mail\UserVerificationEmail;
use App\Models\ProductCategory;
use App\Models\ProductsSubCategory;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Policy;
use App\Pharmacy;
use App\QuestResult;
use App\Session;
use App\Specialization;
use App\State;
use App\City;
use App\User;
use App\Appointment;
use Auth;
use DateTime;
use DateTimeZone;
use DB;
use PDF;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrder;
use Validator;

class AdminController extends Controller
{

    public function doctor_calendar(Request $request)
    {
        $doctors = User::where('user_type', 'doctor')->get();

        if (!isset($request->id)) {
            $events = DoctorSchedule::where('doctorID', $doctors->first()->id)->get();
        } else {
            $events = DoctorSchedule::where('doctorID', $request->id)->get();
        }
        // dd($events);

        return view("superadmin.doctor_calendar", compact('doctors', 'events'));
    }



//doctor_schedule
public function doctor_schedule($id)
    {
            $doctors = User::where('user_type', 'doctor')->get();
            $events = DoctorSchedule::where('doctorID',$id)->orderby('id','desc')->paginate(10);

            $user = Auth::user();
            foreach ($events as $ev) {
                $ev->appointments = Appointment::where('doctor_id',$ev->doctorID)->where('date',$ev->date)->where('status','pending')->orderBy('time')->get();
                $ev->date = User::convert_utc_to_user_timezone($user->id,$ev->date);
                $ev->date = $ev->date['date'];
                $ev->start = User::convert_utc_to_user_timezone($user->id,$ev->start);
                $ev->start = $ev->start['date']." ".$ev->start['time'];
                $ev->end = User::convert_utc_to_user_timezone($user->id,$ev->end);
                $ev->end = $ev->end['date']." ".$ev->end['time'];
            }
        // else {
        //     $events = DoctorSchedule::where('doctorID', $request->id)->get();
        // }
        //  dd($id);

        return view("dashboard_admin.doctors.doctor_schedule.index", compact('doctors', 'events','id'));
    }





public function all_doctor_schedule(){

    $doctors = User::where('user_type', 'doctor')->get();
    $events = DoctorSchedule::where('id')->paginate(10);
    $appointments = Appointment::where('id')->paginate(10);
$id=0;
    return view("dashboard_admin.doctors.doctor_schedule.index", compact('doctors', 'events','appointments','id'));

}

public function all_doctor_appointments(){


    $appointments = Appointment::get();

    return view("dashboard_admin.doctors.doctor_schedule.index", compact('doctors', 'events'));

}



    public function addSpecialization()
    {
        $edit_data = "";
        $data = DB::table('specializations')->get();
        return view('superadmin.addSpecialization', compact('data', 'edit_data'));
    }

    public function add_product_category(Request $request){
        DB::table('product_categories')->insert([
            'name' => $request->category,
            'slug' => strtolower(str_replace(" ","-",$request->category)),
            'category_type' => strtolower(str_replace(" ","-",$request->category)),
        ]);
        return redirect('/productCategory');
    }

    public function viewSpecialization()
    {
        $edit_data = "";
        $data = DB::table('specializations')->get();
        return view('superadmin.viewAllSpecializations', compact('data', 'edit_data'));
    }
    public function dash_viewSpecialization(Request $req)
    {
        $edit_data = "";
        if(isset($req)){
            $data = DB::table('specializations')->where('name','LIKE','%'.$req->name.'%')->paginate(7);
        }else{
            $data = DB::table('specializations')->paginate(7);
        }
        return view('dashboard_admin.All_specialization.index', compact('data', 'edit_data'));
    }
    public function addSpecializationPrice()
    {
        $edit_data = "";
        $data = DB::table('specalization_price')
            ->join('states', 'specalization_price.state_id', 'states.id')
            ->join('specializations', 'specializations.id', 'specalization_price.spec_id')
            ->where('states.country_code', 'US')
            ->select('states.name as state_name', 'specializations.name as spec_name', 'specalization_price.*')
            ->get();
        $spec = DB::table('specializations')
        ->where('status','=','1')
        ->get();
        $states = DB::table('states')->where('country_code', 'US')->get();
        return view('superadmin.addSpecializationPrice', compact('data', 'edit_data', 'spec', 'states'));
    }
    public function dash_addSpecializationPrice()
    {
        $edit_data = "";
        $data = DB::table('specalization_price')
            ->join('states', 'specalization_price.state_id', 'states.id')
            ->join('specializations', 'specializations.id', 'specalization_price.spec_id')
            ->where('states.country_code', 'US')
            ->select('states.name as state_name', 'specializations.name as spec_name', 'specalization_price.*')
            ->get();
        $spec = DB::table('specializations')
        ->where('status','=','1')
        ->get();
        $states = DB::table('states')->where('country_code', 'US')->get();
        return view('dashboard_admin.All_specialization.specialization_price', compact('data', 'edit_data', 'spec', 'states'));
    }
    public function delete_specialization_price($id)
    {
        DB::table('specalization_price')->where('id', $id)->delete();
        return redirect()->route('addSpecPrice')->with('message', 'remove specialization price ');
    }
    public function product_category()
    {
        $data = DB::table('product_categories')->get();
        return view('superadmin.product_category', compact('data'));
    }
    public function storeSpecializationPrice(Request $request)
    {
        $this->validate($request, [
            'specialization_name' => 'required',
            'state_name' => 'required',
            'specialization_initial_price' => 'required',
        ]);
        if ($request->specialization_price_id != null) {
            DB::table('specalization_price')->where('id', $request->specialization_price_id)->update([
                'spec_id' => $request->specialization_name,
                'state_id' => $request->state_name,
                'follow_up_price' => $request->specialization_follow_up_price,
                'initial_price' => $request->specialization_initial_price,
            ]);
        } else {
            $check_already = DB::table('specalization_price')->where('spec_id', $request->specialization_name)->where('state_id', $request->state_name)->count();
            if ($check_already > 0) {
                return redirect()->route('addSpecPrice')->with('message', 'already added specialization price');
            } else {
                DB::table('specalization_price')->insert([
                    'spec_id' => $request->specialization_name,
                    'state_id' => $request->state_name,
                    'follow_up_price' => $request->specialization_follow_up_price,
                    'initial_price' => $request->specialization_initial_price,
                ]);
            }
        }
        return redirect()->route('addSpecPrice')->with('message', 'add specialization price successfully');
    }

    public function addPsychiatryService(Request $request)
    {
        return view('superadmin.Psychiatrist.add_psychiatry_service');
    }
    public function admin_addPsychiatryService(Request $request)
    {
        return view('dashboard_admin.psychiatry.add_psychiatry_services');
    }
    public function storePsychiatryService(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
        ]);
        DB::table('products_sub_categories')->insert([
            'title' => $request->service_name,
            'slug' => strtolower(str_replace(" ","-",$request->service_name)),
            'description' => $request->content,
            'parent_id' => 44,
            // 'initial_price' => $request->specialization_initial_price,
        ]);
        return redirect()->route('view_psychiatrist_services')->with('message', 'New Service Added Succesfully');
    }
    public function admin_storePsychiatryService(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
        ]);
        DB::table('products_sub_categories')->insert([
            'title' => $request->service_name,
            'slug' => strtolower(str_replace(" ","-",$request->service_name)),
            'description' => $request->content,
            'parent_id' => 44,
            // 'initial_price' => $request->specialization_initial_price,
        ]);
        return redirect()->route('admin_view_psychiatrist_services')->with('message', 'New Service Added Succesfully');
    }
    public function admin_storeTherapyIssue(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
            'content' => 'required',
        ]);
        DB::table('products_sub_categories')->insert([
            'title' => $request->service_name,
            'slug' => strtolower(str_replace(" ","-",$request->service_name)),
            'description' => $request->content,
            'parent_id' => 58,
            // 'initial_price' => $request->specialization_initial_price,
        ]);
        return redirect()->route('admin_view_therapy_issues');
    }
    public function admin_updateTherapyIssue(Request $request)
    {
        $this->validate($request, [
            'service_name' => 'required',
            'content' => 'required',
        ]);
        DB::table('products_sub_categories')->where('id',$request->id)->update([
            'title' => $request->service_name,
            'slug' => strtolower(str_replace(" ","-",$request->service_name)),
            'description' => $request->content,
            // 'initial_price' => $request->specialization_initial_price,
        ]);
        return redirect()->route('admin_view_therapy_issues');
    }
    public function admin_delete_therapy_issue($id)
    {
        DB::table('products_sub_categories')->where('id',$id)->delete();
        return redirect()->route('admin_view_therapy_issues');
    }

    public function editSpecializationPrice($id)
    {
        $edit_data = DB::table('specalization_price')
            ->join('states', 'specalization_price.state_id', 'states.id')
            ->join('specializations', 'specializations.id', 'specalization_price.spec_id')
            ->where('states.country_code', 'US')
            ->where('specalization_price.id', $id)
            ->select('states.name as state_name', 'specializations.name as spec_name', 'specalization_price.*')
            ->first();

        $data = DB::table('specalization_price')
            ->join('states', 'specalization_price.state_id', 'states.id')
            ->join('specializations', 'specializations.id', 'specalization_price.spec_id')
            ->where('states.country_code', 'US')
            ->select('states.name as state_name', 'specializations.name as spec_name', 'specalization_price.*')
            ->get();
        $spec = DB::table('specializations')->get();
        $states = DB::table('states')->where('country_code', 'US')->get();
        return view('superadmin.addSpecializationPrice', compact('data', 'edit_data', 'spec', 'states'));
    }

    public function updateSpecializationPrice(Request $request){
        $input = $request->all();
        // dd($input);
        DB::table('specalization_price')->where('id',$input['id'])->update([
            'initial_price' => $input['initial'],
            'follow_up_price' => $input['final'],
        ]);

        return redirect('admin/specialization/price');
    }


    public function editSpecialization($id)
    {
        $edit_data = DB::table('specializations')->where('id', $id)->first();

        $data = DB::table('specializations')->get();

        return view('superadmin.addSpecialization', compact('data', 'edit_data'));
    }

    public function dash_editSpecialization(Request $request){
        $input = $request->all();
        if($input['status'] == "Activate"){
            $status = "1";
        }else{
            $status = "0";
        }
        DB::table('specializations')->where('id',$request['id'])->update(
            ['name'=>$input['specialization'],'status'=> $status]
        );
        return redirect('/admin/all/specializations');
    }
    public function dash_store_Specialization_price(Request $request){
        $input = $request->all();
        $this->validate($request, [
            'spec' => 'required',
            'state' => 'required',
        ]);
        if ($request->follow_up_price != null) {
            DB::table('specalization_price')->insert([
                'state_id' => $request->state,
                'spec_id' => $request->spec,
                'initial_price' => $request->initial_price,
                'follow_up_price' => $request->follow_up_price,
            ]);
        } else {
            DB::table('specalization_price')->insert([
                'state_id' => $request->state,
                'spec_id' => $request->spec,
                'initial_price' => $request->initial_price,
                'follow_up_price' => null,
            ]);
        }
        // dd($input);
        return redirect('/admin/specialization/price');
    }

    public function admin_store_spec(Request $request){
        $input = $request->all();
        // dd($input);
        Specialization::create([
            'name' => $request->spec_name,
            'status' => 0,
        ]);
        // dd($input);
        return redirect('/admin/all/specializations');
    }


    public function dash_deleteSpecialization(Request $request){
        $input = $request->all();
        // dd($input);
        DB::table('specializations')->where('id', $input['id'])->delete();
        return redirect('/admin/all/specializations');
    }
    public function dash_delete_Specialization_price(Request $request){
        $input = $request->all();
        // dd($input);
        DB::table('specalization_price')->where('id', $input['id'])->delete();
        return redirect('/admin/specialization/price');
    }
    public function destroySpecialization($id)
    {
        DB::table('specializations')->where('id', $id)->delete();

        $data = DB::table('specializations')->get();

        return view('superadmin.viewAllSpecializations', compact('data'));
    }
    public function storeSpecialization(Request $request)
    {
        $this->validate($request, [
            'specialization_name' => 'required',
            'specialization_status' => 'required',
        ]);
        if ($request->specialization_id != null) {
            Specialization::where('id', $request->specialization_id)->update([
                'name' => $request->specialization_name,
                'status' => $request->specialization_status,
            ]);
        } else {
            Specialization::create([
                'name' => $request->specialization_name,
                'status' => $request->specialization_status,
            ]);
        }
        return redirect()->route('addSpec')->with('message', 'add specialization successfully');
    }

    public function all_patients()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $patients = User::where('user_type', 'patient')->get();
                foreach ($patients as $patient) {
                    $sessionsfordoc = Session::where('patient_id', $patient['id'])->groupBy('doctor_id')->get();
                    // dd($sessionsfordoc);
                    $doctors = [];
                    foreach ($sessionsfordoc as $doc) {
                        $name = Helper::get_name($doc['doctor_id']);
                        array_push($doctors, $name);
                    }

                    $doc_str = implode(',', $doctors);

                    $session = Session::where('patient_id', $patient['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $patient->last_visit = Helper::get_date_with_format($session['date']);
                        $patient->last_diagnosis = $session['diagnosis'];
                        $patient->doctors = $doc_str;
                    }
                }
                $val = 'all';
                $states = State::where('country_code', 'US')->get();
                return view('superadmin.patients', compact('patients', 'val', 'states'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function admin_all_patients(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                if(isset($request->name)){
                    $patients = User::where('user_type', 'patient')
                    ->where('name', 'like', '%' . $request->name . '%')
                    ->orwhere('last_name', 'like', '%' . $request->name . '%')
                    ->orwhere('email', 'like', '%' . $request->name . '%')
                    ->paginate(10);
                }else{
                    $patients = User::where('user_type', 'patient')->paginate(10);
                }
                foreach ($patients as $patient) {
                    $sessionsfordoc = Session::where('patient_id', $patient['id'])->groupBy('doctor_id')->get();
                    // dd($sessionsfordoc);
                    $doctors = [];
                    foreach ($sessionsfordoc as $doc) {
                        $name = Helper::get_name($doc['doctor_id']);
                        array_push($doctors, $name);
                    }

                    $doc_str = implode(',', $doctors);

                    $session = Session::where('patient_id', $patient['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $patient->last_visit = Helper::get_date_with_format($session['date']);
                        $patient->last_diagnosis = $session['diagnosis'];
                        $patient->doctors = $doc_str;
                    }
                }
                $val = 'all';
                $states = State::where('country_code', 'US')->get();
                return view('dashboard_admin.all_patient.index', compact('patients', 'val', 'states'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function all_patients_name()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $patients = User::where('user_type', 'patient')->orderBy('name', 'ASC')->paginate(9);
                foreach ($patients as $patient) {
                    $sessionsfordoc = Session::where('patient_id', $patient['id'])->groupBy('doctor_id')->get();
                    // dd($sessionsfordoc);
                    $doctors = [];
                    foreach ($sessionsfordoc as $doc) {
                        $name = Helper::get_name($doc['doctor_id']);
                        array_push($doctors, $name);
                    }
                    $doc_str = implode(',', $doctors);
                    // dd($doc_str);

                    $session = Session::where('patient_id', $patient['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $patient->last_visit = Helper::get_date_with_format($session['date']);
                        $patient->last_diagnosis = $session['diagnosis'];
                        $patient->doctors = $doc_str;
                    }
                    // dd($patient->last_visit);
                }
                $states = State::where('country_code', 'US')->get();
                $val = 'name';
                return view('superadmin.patients', compact('patients', 'val', 'states'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function all_patients_reg()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $patients = User::where('user_type', 'patient')->orderByDesc('created_at')->paginate(9);
                foreach ($patients as $patient) {
                    $sessionsfordoc = Session::where('patient_id', $patient['id'])->groupBy('doctor_id')->get();
                    // dd($sessionsfordoc);
                    $doctors = [];
                    foreach ($sessionsfordoc as $doc) {
                        $name = Helper::get_name($doc['doctor_id']);
                        array_push($doctors, $name);
                    }
                    $doc_str = implode(',', $doctors);
                    // dd($doc_str);

                    $session = Session::where('patient_id', $patient['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $patient->last_visit = Helper::get_date_with_format($session['date']);
                        $patient->last_diagnosis = $session['diagnosis'];
                        $patient->doctors = $doc_str;
                    }
                    // dd($patient->last_visit);
                }
                $states = State::where('country_code', 'US')->get();
                $val = 'reg';
                return view('superadmin.patients', compact('patients', 'val', 'states'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function all_patients_visit()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $patients = User::where('user_type', 'patient')->paginate(9);
                foreach ($patients as $patient) {
                    $sessionsfordoc = Session::where('patient_id', $patient['id'])->groupBy('doctor_id')->paginate(9);
                    // dd($sessionsfordoc);
                    $doctors = [];

                    foreach ($sessionsfordoc as $doc) {
                        $name = Helper::get_name($doc['doctor_id']);
                        array_push($doctors, $name);
                    }
                    $doc_str = implode(',', $doctors);
                    // dd($doc_str);

                    $session = Session::where('patient_id', $patient['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $patient->last_visit = Helper::get_date_with_format($session['date']);
                        $patient->last_diagnosis = $session['diagnosis'];
                        $patient->doctors = $doc_str;
                    }
                    // dd($patient->last_visit);
                }
                $states = State::where('country_code', 'US')->get();
                // $states=State::all();
                // dd($states);
                $val = 'visit';
                return view('superadmin.patients', compact('patients', 'val', 'states'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function patient_by_state(Request $request)
    {
        // dd($request);
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $val = 'all_states';
                $selected_state = '';
                if (isset($request['state'])) {
                    $val = 'one_state';
                    if ($request['state'] == "all") {
                        $patients = User::where('user_type', 'patient')->orderBy('state_id', 'ASC')->get();
                    } else {
                        $selected_state = State::find($request['state'])->name;
                        $patients = User::where('user_type', 'patient')->where('state_id', $request['state'])->orderBy('name', 'ASC')->get();

                    }

                } else {
                    $patients = User::where('user_type', 'patient')->orderBy('state_id', 'ASC')->get();
                }

                foreach ($patients as $patient) {
                    $sessionsfordoc = Session::where('patient_id', $patient['id'])->groupBy('doctor_id')->get();
                    // dd($sessionsfordoc);
                    $doctors = [];
                    foreach ($sessionsfordoc as $doc) {
                        $name = Helper::get_name($doc['doctor_id']);
                        array_push($doctors, $name);
                    }
                    $doc_str = implode(',', $doctors);
                    // dd($doc_str);

                    $session = Session::where('patient_id', $patient['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $patient->last_visit = Helper::get_date_with_format($session['date']);
                        $patient->last_diagnosis = $session['diagnosis'];
                        $patient->doctors = $doc_str;
                    }
                    // dd($patient->last_visit);
                }
                $states = State::where('country_code', 'US')->get();
                // $states=State::all();
                // dd($states);
                return view('superadmin.patients', compact('patients', 'val', 'states', 'selected_state'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function all_doctors()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $doctors = User::where('user_type', 'doctor')->where('active', '1')->get();
                foreach ($doctors as $doctor) {
                    $doc_percentage = DB::table('doctor_percentage')->where('doc_id', $doctor['id'])->first();
                    if ($doc_percentage != null) {
                        $doctor->percentage_doctor = number_format($doc_percentage->percentage, 2) . '%';
                    } else {
                        $doctor->percentage_doctor = "not assign";
                    }
                    $session = Session::where('doctor_id', $doctor['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $doctor->last_visit = Helper::get_date_with_format($session['date']);
                        // $doctor->last_diagnosis=$session['diagnosis'];
                    }
                    $doctor->state = State::find($doctor->state_id)->name;
                    // dd($patient->last_visit);
                }
                return view('superadmin.doctors', compact('doctors'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
//doctors->admin
public function all_doctor_index(Request $req)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                if(isset($req->name)){
                    $doctors = User::where('user_type', 'doctor')
                    ->join('specializations','specializations.id','users.specialization')
                    ->join('states','users.state_id','states.id')
                    ->where('users.name','LIKE','%'. $req->name . '%')
                    ->orwhere('users.last_name','LIKE','%'. $req->name . '%')
                    ->orwhere('users.email','LIKE','%'. $req->name . '%')
                    ->orwhere('users.nip_number','LIKE','%'. $req->name . '%')
                    ->orwhere('states.name','LIKE','%'. $req->name . '%')
                    ->where('users.active', '1')
                    ->select('users.*','specializations.name as spec_name')
                    ->orderBy('id','DESC')
                    ->paginate(8);
                }else{
                    $doctors = User::where('user_type', 'doctor')
                    ->join('specializations','specializations.id','users.specialization')
                    ->where('active', '1')
                    ->leftjoin('lab_approval_doctors','lab_approval_doctors.doctor_id','users.id')
                    ->select('users.*','lab_approval_doctors.status as lab_status')
                    ->select('users.*','specializations.name as spec_name')
                    ->orderBy('id','DESC')
                    ->paginate(8);
                }
                foreach ($doctors as $doctor) {
                    $doc_percentage = DB::table('doctor_percentage')->where('doc_id', $doctor['id'])->first();
                    if ($doc_percentage != null) {
                        $doctor->percentage_doctor = number_format($doc_percentage->percentage, 2) . '%';
                    } else {
                        $doctor->percentage_doctor = "not assign";
                    }
                    $session = Session::where('doctor_id', $doctor['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $doctor->last_visit = Helper::get_date_with_format($session['date']);
                        // $doctor->last_diagnosis=$session['diagnosis'];
                    }
                    $doctor->state = State::find($doctor->state_id)->name;
                    $cntrcName = 'signed_contract/' . $doctor->nip_number . '_contract.pdf';
                    $doctor->contract = \App\Helper::get_files_url($cntrcName);
                    // dd($doctor->contract);
                }
                return view('dashboard_admin.doctors.all_doctors.index', compact('doctors'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }


public function lab_approval_doctor(Request $req)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $doctors = DB::table('lab_approval_doctors')
                ->join('users','users.id','lab_approval_doctors.doctor_id')
                ->select('users.*','lab_approval_doctors.status as lab_status')
                ->get();
                foreach ($doctors as $doctor) {
                    $doctor->state = State::find($doctor->state_id)->name;
                }
                // dd($doctors);
                return view('dashboard_admin.doctors.lab_approval_doctors.index', compact('doctors'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function assign_doctor_for_lab($id){
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                DB::table('lab_approval_doctors')->insert([
                    'doctor_id' => $id,
                    'status' => 'active',
                    'created_at' => NOW(),
                    'updated_at' => NOW(),
                ]);
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function deactive_doctor_for_lab($id){
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                // dd($id);
                DB::table('lab_approval_doctors')->where('doctor_id', $id)->delete();
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }





    public function patient_records(){


        $fetch = DB::table('patients_redord')
        ->where('edited','=','0')
        ->get();
        $this->data['patients_redord'] = $fetch;
        return view('edit_permissions/patient_records',$this->data);
    }

    public function admin_patient_records(){
        $fetch = DB::table('patients_redord')
        ->where('edited','=','0')
        ->get();
        foreach($fetch as $data){
            $actual = DB::table('users')->where('id',$data->user_id)->first();
            $data->actual = $actual;
        }
        return view('dashboard_admin.edit_permission.patient_record',compact('fetch'));
    }

    public function doctor_profile_update(){
        $fetch = DB::table('doctor_profile_update')
        ->where('edited','=','0')
        ->get();
        $this->data['doctor_profile_update'] = $fetch;
        return view('edit_permissions/doctor_profile_update',$this->data);
    }

    public function admin_doctor_profile_update(){
        $fetch = DB::table('doctor_profile_update')
        ->where('edited','=','0')
        ->get();
        foreach($fetch as $data){
            if($data->certificate != ""){
                $data->certificate = \App\Helper::check_bucket_files_url($data->certificate);
            }
            $actual = DB::table('users')->where('id',$data->user_id)->first();
            $data->actual = $actual;
        }
        return view('dashboard_admin.edit_permission.doctor_record',compact('fetch'));
    }


    public function add_category(){
        return view('superadmin.category_form');
    }


    public function pending_doctors(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                // $doctors = \DB::table('users')
                // ->rightJoin('contracts', 'contracts.provider_id', '!=', 'users.id')
                // ->join('states', 'users.state_id', '=', 'states.id')
                // ->select('users.*', 'states.name as state')
                // ->where('user_type', 'doctor')
                // ->where('users.active', '0')
                    // ->where('users.status', '!=', 'declined')
                //     ->groupBy('users.id')
                //     ->orderBy('users.created_at', 'ASC')
                    // ->get();

                $doctors = DB::table('users')
                ->select('users.*','states.name as state_name')
                ->join('states', 'users.state_id', '=', 'states.id')
                ->where('user_type','=','doctor')
                ->where('users.active','=','0')
                ->where('users.status','!=','declined')
                ->groupBy('users.id')
                ->orderBy('users.created_at', 'ASC')
                ->get();
                $doctorsCollection = collect();
                foreach ($doctors as $doctor) {
                    $did = $doctors[0]->id;
                    $contract = Contract::where('provider_id', $did)->first();
                    if (!isset($contract->id)) {
                        $doctorsCollection->push($doctor);
                    }
                }
                // dd($doctorsCollection[0]);
                $doctors = $doctorsCollection;
                // dd($doctors);
                return view('superadmin.pending_doctors', compact('doctors'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }



    public function pending_doctor_requests(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $user = Auth::user();
                if(isset($request->name)){
                    $doctors = DB::table('users')
                    ->select('users.*')
                    // ->join('states', 'users.state_id', '=', 'states.id')
                    ->leftjoin('contracts','contracts.provider_id','users.id')
                    ->where('user_type','=','doctor')
                    ->where('users.active','=','0')
                    ->where('contracts.status','=',null)
                    ->where('users.status','!=','ban')
                    ->where('users.name','LIKE','%'.$request->name.'%')
                    ->orwhere('users.nip_number','LIKE','%'. $request->name . '%')
                    ->orwhere('users.last_name','LIKE','%'. $request->name . '%')
                    ->groupBy('users.id')
                    ->orderBy('users.created_at', 'DESC')
                    ->paginate(8);
                    // dd($request->name,$doctors);
                }else{
                    $doctors = DB::table('users')
                    ->select('users.*')
                    // ->join('states', 'users.state_id', '=', 'states.id')
                    ->leftjoin('contracts','contracts.provider_id','users.id')
                    ->where('users.user_type','=','doctor')
                    ->where('users.active','=','0')
                    ->where('contracts.status','=',null)
                    // ->where('users.status','!=','declined')
                    ->where('users.status','!=','ban')
                    ->groupBy('users.id')
                    ->orderBy('users.created_at', 'DESC')
                    ->paginate(8);
                }

                foreach ($doctors as $doc) {
                    $doc->created_at = User::convert_utc_to_user_timezone($user->id,$doc->created_at);
                    $doc->created_at = $doc->created_at['date']." ".$doc->created_at['time'];
                }
                return view('dashboard_admin.doctors.pending_doctor_request.index', compact('doctors'));
            } else {
                return redirect('/admin/dash');
            }
        } else {
            return redirect('/login');
        }
    }

    public function blocked_doctor(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $user = Auth::user();
                if(isset($request)){
                    $doctors = DB::table('users')
                    ->select('users.*','states.name as state_name')
                    ->join('states', 'users.state_id', 'states.id')
                    ->where('user_type','doctor')
                    ->where('users.name','LIKE','%'.$request->name.'%')
                    // ->orwhere('users.nip_number','LIKE','%'. $request->name . '%')
                    ->where('users.active','0')
                    ->where('users.status','ban')
                    // ->orwhere('users.last_name','LIKE','%'. $request->name . '%')
                    ->groupBy('users.id')
                    ->orderBy('users.created_at', 'DESC')
                    ->paginate(8);
                }else{
                    $doctors = DB::table('users')
                    ->select('users.*','states.name as state_name')
                    ->join('states', 'users.state_id', 'states.id')
                    ->where('user_type','doctor')
                    ->where('users.active','0')
                    // ->where('users.status','!=','declined')
                    ->where('users.status','ban')
                    ->groupBy('users.id')
                    ->orderBy('users.created_at', 'DESC')
                    ->paginate(8);
                }

                foreach ($doctors as $doc) {
                    $doc->created_at = User::convert_utc_to_user_timezone($user->id,$doc->created_at);
                    $doc->created_at = $doc->created_at['date']." ".$doc->created_at['time'];
                }
                return view('dashboard_admin.doctors.blocked_doctor.index', compact('doctors'));
            } else {
                return redirect('/admin/dash');
            }
        } else {
            return redirect('/login');
        }
    }







    public function send_contract(Request $request)
    {
        // dd($request);
        $id = $request['id'];
        $licenses = DoctorLicense::where('doctor_id', $id)->get();
        foreach ($licenses as $license) {
            if (isset($request[$license->state_id])) {
                DoctorLicense::find($license->id)->update(['is_verified' => '1']);
            }

        }
        $provider = User::find($id);
        $slug = 'UMB' . time();
        $contract = [
            'slug' => $slug,
            'provider_id' => $id,
            'provider_name' => $provider->name . ' ' . $provider->last_name,
            'provider_address' => $provider->office_address,
            'provider_email_address' => $provider->email,
            'provider_speciality' => Specialization::find($provider->specialization)->name,
            'signature' => $provider->signature,
            'date' => $request['date'],
            'session_percentage' => $request['session_percentage'],
            'status' => 'sent',
        ];
        Contract::create($contract);
        $url = "/provider_contract/" . $slug;
        Mail::to($provider->email)->send(new SendContract($contract, $url));

        return redirect()->route('pending_doctors');

    }
    public function admin_send_contract(Request $request)
    {
        $id = $request['id'];
        $licenses = DoctorLicense::where('doctor_id', $id)->get();
        foreach ($licenses as $license) {
            if (isset($request[$license->state_id])) {
                DoctorLicense::find($license->id)->update(['is_verified' => '1']);
            }

        }
        $provider = User::find($id);
        $slug = 'UMB' . time();
        $contract = [
            'slug' => $slug,
            'provider_id' => $id,
            'provider_name' => $provider->name . ' ' . $provider->last_name,
            'provider_address' => $provider->office_address,
            'provider_email_address' => $provider->email,
            'provider_speciality' => Specialization::find($provider->specialization)->name,
            'signature' => $provider->signature,
            'date' => $request['date'],
            'session_percentage' => $request['session_percentage'],
            'status' => 'sent',
        ];
        Contract::create($contract);
        $url = "/provider_contract/" . $slug;
        Mail::to($provider->email)->send(new SendContract($contract, $url));

        return redirect()->route('pending_doctor_requests');

    }
    public function view_contract($slug = '')
    {
        if ($slug != '') {
            $contract = Contract::where('slug', $slug)->first();
            if (isset($contract->id)) {
                if ($contract->status == 'sent') {
                    $date = date_create($contract->date);
                    $contract->date = date_format($date, "d M, Y");
                    $admin_sign = DB::table('users')->where('user_type','admin')->first();
                    $contract->admin_sign = $admin_sign->signature;
                    return view('superadmin.doctors.provider_contract', compact('contract'));
                } else {
                    return redirect()->route('welcome_page');
                }
            } else {
                return redirect()->route('welcome_page');
            }
        } else {
            return redirect()->route('welcome_page');
        }
    }
    public function sign_contract($slug = '')
    {
        // dd($slug);
        if ($slug != '') {
            $contract = Contract::where('slug', $slug)->first();
            if (isset($contract->id)) {
                if ($contract->status == 'sent') {
                    Contract::where('slug', $slug)->update(['status' => 'signed']);
                    User::where('id', $contract->provider_id)->update(['active' => '1']);
                    DB::table('doctor_percentage')->insert(['doc_id' => $contract->provider_id, 'percentage' => $contract->session_percentage]);
                    // assign doctor role
                    $user = User::find($contract->provider_id);
                    $currentRole = strtolower($user->user_type);
                    $user->assignRole($currentRole);
                    $date = date_create($contract->date);
                    $contract->date = date_format($date, "d M, Y");
                    $admin_sign = DB::table('users')->where('user_type','admin')->first();
                    $contract->admin_sign = $admin_sign->signature;
                    $pdf = PDF::loadView('pdfcontract', compact('contract'))->setOptions(['defaultFont' => 'sans-serif'])->output();
                    \Storage::disk('s3')->put('signed_contract/' . $user->nip_number . '_contract.pdf', $pdf);
                    return view('errors.contract_signed_page');
                } else {
                    User::where('id', $contract->provider_id)->update(['active' => '1']);
                    DB::table('doctor_percentage')->insert(['doc_id' => $contract->provider_id, 'percentage' => $contract->session_percentage]);
                    // assign doctor role
                    $user = User::find($contract->provider_id);
                    $currentRole = strtolower($user->user_type);
                    $user->assignRole($currentRole);
                    return view('errors.contract_signed_page');
                }
            } else {
                return redirect()->route('doctor_dashboard');
            }
        } else {
            return redirect()->route('doctor_dashboard');
        }
    }
    public function approve_doctor(Request $request)
    {
        // dd($request);
        $id = $request['id'];
        $licenses = DoctorLicense::where('doctor_id', $id)->get();
        foreach ($licenses as $license) {
            if (isset($request[$license->state_id])) {
                DoctorLicense::find($license->id)->update(['is_verified' => '1']);
            }

        }
        User::where('id', $id)->update(['active' => '1']);
        $doc = User::find($id);
        //send mail mailgun

        //Mail::to('baqir.redecom@gmail.com')->send(new ApprovedDoctor(ucwords($doc->name)));
        Mail::to($doc->email)->send(new ApprovedDoctor(ucwords($doc->name)));

        return redirect()->route('pending_doctors');
    }
    public function decline_doctor(Request $request)
    {
        $id = $request['id'];
        User::where('id', $id)->update(['active' => '0', 'status' => 'declined']);
        return redirect()->back();
    }
    public function ban_doctor(Request $request)
    {
        $id = $request['id'];
        User::where('id', $id)->update(['active' => '0', 'status' => 'ban']);
        $all_patients_id = DB::table('sessions')
            ->join('users', 'sessions.patient_id', '=', 'users.id')
            ->where('doctor_id', $id)
            ->groupBy('patient_id')
            ->select('users.id')
            ->get();

        return redirect()->route('admin_doctors');
    }



    public function deactivate(Request $request)
    {
        $id = $request['id'];
        User::where('id', $id)->update(['active' => '0', 'status' => 'ban']);
        $all_patients_id = DB::table('sessions')
            ->join('users', 'sessions.patient_id', '=', 'users.id')
            ->where('doctor_id', $id)
            ->groupBy('patient_id')
            ->select('users.id')
            ->get();

            return redirect()->route('all_doctors')->with('message', 'successfully deactivated!');
    }
    public function activate(Request $request)
    {
        // dd($request->all());
        $id = $request['id'];
        User::where('id', $id)->update(['active' => '1', 'status' => 'offline']);

        return redirect()->back();
    }




    public function activity_log_doctor(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $id = $request['id'];
                $doctor = User::find($id);
                $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->get();
                foreach ($activities as $act) {
                    $act->date = Helper::get_date_with_format($act->created_at);
                    $act->time = Helper::get_time_with_format($act->created_at);
                }
                return view('superadmin.activity_log', compact('doctor', 'activities'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function doctor_full_details(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $id = $request['id'];
                $doctor = User::find($id);
                $certificate = DB::table('doctor_certificates')->where('doc_id', $id)->get();
                $doctor->state = State::find($doctor->state_id)->name;
                foreach ($certificate as $cert) {
                    if ($cert->certificate_file != "") {
                        $cert->certificate_file = \App\Helper::get_files_url($cert->certificate_file);
                    }

                }
                $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->paginate(9);
                foreach ($activities as $act) {
                    $user = User::find($act->user_id);
                    $date = new DateTime($act->created_at);
                    $date->setTimezone(new DateTimeZone($user->timeZone));
                    $act->date = Helper::get_date_with_format($date->format('Y-m-d H:i:s'));
                    $act->time = Helper::get_time_with_format($date->format('Y-m-d H:i:s'));
                }
                return view('superadmin.doctor_full_details', compact('doctor', 'activities', 'certificate'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
//doctor_view_details

    public function all_doctor_view(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $id = $request['id'];
                $doctor = User::find($id);
                $doctor->spec = DB::table('specializations')->where('id',$doctor->specialization)->select('name')->first();
                $doctor->spec = $doctor->spec->name;
                $certificate = DB::table('doctor_certificates')->where('doc_id', $id)->get();
                $doctor->state = State::find($doctor->state_id)->name;
                foreach ($certificate as $cert) {
                    if ($cert->certificate_file != "") {
                        $cert->certificate_file = \App\Helper::get_files_url($cert->certificate_file);
                    }

                }
                $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->limit(3)->paginate(9);
                foreach ($activities as $act) {
                    $user = User::find($act->user_id);
                    $date = new DateTime($act->created_at);
                    $date->setTimezone(new DateTimeZone($user->timeZone));
                    $act->date = Helper::get_date_with_format($date->format('Y-m-d H:i:s'));
                    $act->time = Helper::get_time_with_format($date->format('Y-m-d H:i:s'));
                }
                // dd($doctor);
                return view('dashboard_admin.doctors.all_doctors.view', compact('doctor', 'activities', 'certificate'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }


    public function admin_acc_settings()
    {
        $user = Auth()->user();
        $user->user_image = \App\Helper::check_bucket_files_url($user->user_image);
        return view('dashboard_admin.AccountSetting.index',compact('user'));
    }

    public function error_log_view(){
        $data = DB::table('error_log')->join('users','users.id','error_log.user_id')->get();
        // dd($data);
        return view('superadmin.view_error_log',compact('data'));
    }

    public function admin_error_log_view(){
        $data = DB::table('error_log')
        ->join('users','users.id','error_log.user_id')
        ->orderBy('error_log.id','desc')
        ->paginate(5);
        // dd($data);
        return view('dashboard_admin.View_errors.index',compact('data'));
    }

    public function admin_physical_location(){
        $data = PhysicalLocations::with(['states','cities'])->paginate(10);
        return view('dashboard_admin.physical_location.index',compact('data'));
    }

    public function admin_add_physical_location(){
        return view('dashboard_admin.physical_location.create');
    }

    public function store_physical_location(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'zipcode' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);
        $physicalLocation = PhysicalLocations::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'zipcode' => $request->zipcode,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'status' => "1",
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'services' => json_encode($request->services),
        ]);
        return redirect()->route('admin_physical_location');
    }

    public function admin_edit_physical_location($id){
        $pl = PhysicalLocations::find($id);
        return view('dashboard_admin.physical_location.edit',compact('pl'));
    }

    public function admin_update_physical_location($id,Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'zipcode' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);
        $physicalLocation = PhysicalLocations::find($id)->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'zipcode' => $request->zipcode,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'status' => "1",
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'services' => json_encode($request->services),
        ]);
        return redirect()->route('admin_physical_location');
    }

    public function delete_physical_location($id){
        $physicalLocation = PhysicalLocations::find($id)->delete();
        return redirect()->route('admin_physical_location');
    }


    public function pending_doctor_detail(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $id = $request['id'];
                $doctor = User::find($id);
                $doctor->date_of_birth = Helper::get_date_with_format($doctor['date_of_birth']);
                $doctor->state = State::find($doctor['state_id'])->name;
                $doctor->specialization = Specialization::find($doctor['specialization'])->name;
                $doctor->licenses = \DB::table('doctor_licenses')
                    ->join('states', 'states.id', '=', 'doctor_licenses.state_id')
                    ->where('doctor_licenses.doctor_id', $id)
                    ->select('states.name as state', 'doctor_licenses.*')->get();
                return view('superadmin.pending_doctor_detail', compact('doctor'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function doctor_pending_request_view(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $id = $request['id'];
                $doctor = User::find($id);
                $doctor->date_of_birth = Helper::get_date_with_format($doctor['date_of_birth']);
                $doctor->specialization = Specialization::find($doctor['specialization'])->name;
                $doctor->email_verify = DB::table('users_email_verification')->where('user_id',$id)->first();
                return view('dashboard_admin.doctors.pending_doctor_request.view', compact('doctor'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }



    public function pending_doctor_view(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                $id = $request['id'];
                $doctor = User::find($id);
                $doctor->date_of_birth = Helper::get_date_with_format($doctor['date_of_birth']);
                $doctor->state = State::find($doctor['state_id'])->name;
                $doctor->specialization = Specialization::find($doctor['specialization'])->name;
                $doctor->licenses = \DB::table('doctor_licenses')
                    ->join('states', 'states.id', '=', 'doctor_licenses.state_id')
                    ->where('doctor_licenses.doctor_id', $id)
                    ->select('states.name as state', 'doctor_licenses.*')->get();
                return view('dashboard_admin.doctors.pending_doctor_request.view', compact('doctor'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }







    public function admin_contact()
    {
        $data = ContactForm::orderBy('id', 'DESC')->paginate(10);

        return view('superadmin.contact', ['data' => $data])
        // $data->orderByDesc('id')->first();
        ;

    }
    public function dash_admin_contact(Request $request)
    {

        if(isset($request->name)){
            $data = DB::table('contact_form')
            ->where('name', 'like', '%'.$request->name.'%')
            // ->orWhere('email', 'like', '%'.$request->name.'%')
            // ->orWhere('phone', 'like', '%'.$request->name.'%')
            // // ->orWhere('subject', 'like', '%'.$request->name.'%')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }else{
            $data = ContactForm::orderBy('id', 'DESC')->paginate(10);
        }

        return view('dashboard_admin.contact_us.index', ['data' => $data])
        // $data->orderByDesc('id')->first();
        ;

    }
    public function admin_contact_msg($id, Request $request)
    {
        $data = ContactForm::find($id);
        // $msg = ContactUs::find($id);
        $msg = ContactForm::where('id', $id)->first();
        return view('superadmin.contact_msg', ['msg' => $msg]);
    }
    public function all_users()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $admin_phar = User::where('user_type', 'admin_pharmacy')->get();
                $admin_lab = User::where('user_type', 'admin_lab')->get();
                $admin_imaging = User::where('user_type', 'admin_imaging')->get();
                $editor_phar = User::where('user_type', 'editor_pharmacy')->get();
                $editor_lab = User::where('user_type', 'editor_lab')->get();
                $editor_imaging = User::where('user_type', 'editor_imaging')->get();
                $hr = User::where('user_type', 'hr')->get();
                // $users=User::where('user_type','!=','patient')->where('user_type','!=','doctor')->where('user_type','!=','admin')->get();
                return view('superadmin.users.all_users', compact('admin_phar', 'admin_lab', 'admin_imaging', 'editor_phar', 'editor_lab', 'editor_imaging', 'hr'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }





    //Manage_users Admin
    public function manage_all_users($userType)
    {

        if($userType==='all')
        {
            $users = User::all();
        }
        else{
            $users = User::where('user_type', $userType)->get();
        }

        $roles = DB::table('roles')->get();
        return view('dashboard_admin.manage_users.index',compact('users','roles','userType'));

    }






    public function store_admin(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                // dd($request);
                $username = 'adm_' . time();
                if ($request['role'] == 'pharmacy') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_pharmacy',
                        'email' => $request['email'],
                        'password' => Hash::make('12345678'),
                        'created_by' => auth()->user()->id,
                    ])->id;
                    DB::table('users_email_verification')
                        ->insert(
                            [
                                'user_id'=>$user_id,
                                'status'=>0,
                            ]);
                } else if ($request['role'] == 'lab') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_lab',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                } else if ($request['role'] == 'imaging') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_imaging',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                }
                return redirect()->route('manage_users');
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function store_editor(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                // dd($request);
                $username = 'edt_' . time();
                if ($request['role'] == 'pharmacy') {
                    $id = User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'editor_pharmacy',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                    ])->id;
                } else if ($request['role'] == 'lab') {
                    $id = User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'editor_lab',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        'status' => 'active',
                    ])->id;
                } else if ($request['role'] == 'imaging') {
                    $id = User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'editor_imaging',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                    ])->id;
                }
                $type = auth()->user()->user_type;
                if ($type == 'admin_lab' || $type == 'admin_pharmacy' || $type == 'admin_imaging') {
                    ActivityLog::add_activity('added editor ' . $request['dtitle'], $id, 'editor_created');
                    return redirect()->route('all_editors');
                }
                return redirect()->route('manage_users');
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function user_details(Request $request)
    {
        if (Auth::check()){

            if (Auth::user()->user_type == 'admin') {

                $user = User::find($request['id']);
                $user_type = $user->user_type;

                $allProducts = Pharmacy::get_created_products($request['id']);


                $productCategories = Pharmacy::get_created_product_categories($request['id']);

                $productsSubCategories = Pharmacy::get_created_product_sub_categories($request['id']);
                $activities = ActivityLog::where('user_id', $request['id'])->orderByDesc('id')->paginate(10);
                foreach ($activities as $act) {
                    $act->date = Helper::get_date_with_format($act->created_at);
                    $act->time = Helper::get_time_with_format($act->created_at);
                }

                if ($user_type == 'admin_pharmacy' || $user_type == 'admin_lab' || $user_type == 'admin_imaging') {
                    $editors = User::where('created_by', $user->id)->get();
                    // dd($products);

                    return view(
                        'superadmin.users.user_details',
                        compact(
                            'user',
                            'allProducts',
                            'productCategories',
                            'productsSubCategories',
                            'editors',
                            'activities'
                        )
                    );
                }
                return view(
                    'superadmin.users.user_details',
                    compact(
                        'user',
                        'allProducts',
                        'productCategories',
                        'productsSubCategories',
                        'activities'
                    )
                );
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function prod_del_req(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                // dd($request['id']);
                AllProducts::find($request['id'])->update(['del_req' => '1', 'product_status' => 0]);
                $prod = AllProducts::find($request['id'])->first();
                // dd($prod);
                ActivityLog::add_activity('deleted ' . $prod['name'], $prod['id'], 'product_del_request');
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function final_del_prod(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                // dd($request['id']);
                AllProducts::find($request['id'])->forcedelete();
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function temp_del_prod(Request $request)
    {
        // dd($request['id']);
        AllProducts::find($request['id'])->forcedelete();
        return redirect()->back();

    }
    public function reset_del_prod(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                // dd($request['id']);
                AllProducts::find($request['id'])->update(['del_req' => '0', 'product_status' => '1']);
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function add_approve_prod(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                // dd($request['id']);
                AllProducts::find($request['id'])->update(['is_approved' => '1']);
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function submit_terms_of_use(Request $request)
    {
        // dd($request);
        $input=$request->all();
        Document::create($input);
        Flash::success('Document saved successfully.');
        return redirect()->back();

    }

    public function submit_privacy_policy(Request $request)
    {
        // dd($request);
        $input=$request->all();
        Policy::create($input);
        Flash::success('Policy saved successfully.');
        return redirect()->back();

    }


    public function dash_store_admin(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $user = User::where('email', $request['email'])->first();
                if(isset($user)){
                    return redirect()->back()->with('error','Email already exists');
                }
                $username = 'adm_' . time();
                // dd($request['role'],$request['dtitle'],$request['email'],$username);
                if ($request['role'] == 'pharmacy') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_pharm',
                        'email' => $request['email'],
                        'password' => Hash::make('12345678'),
                        'created_by' => auth()->user()->id,
                    ])->id;
                    DB::table('users_email_verification')
                        ->insert(
                            [
                                'user_id'=>$user_id,
                                'status'=>0,
                            ]);
                } else if ($request['role'] == 'lab') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_lab',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                } else if ($request['role'] == 'imaging') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_imaging',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                } else if ($request['role'] == 'finance') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_finance',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                } else if ($request['role'] == 'chat_support') {
                    $username = "chat_admin_".time();
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'chat_support',
                        'email' => $request['email'],
                        'status' => 'online',
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                } else if ($request['role'] == 'seo') {
                    $user_id=User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'admin_seo',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                        ])->id;
                        DB::table('users_email_verification')
                            ->insert(
                                [
                                    'user_id'=>$user_id,
                                    'status'=>0,
                                ]);
                }
                $x = rand(10e12, 10e16);
                $hash_to_verify = base_convert($x, 10, 36);
                $data = [
                    'hash' => $hash_to_verify,
                    'user_id' => $user_id,
                    'to_mail' => $request['email'],
                ];
                try {
                    Mail::to($request['email'])->send(new UserVerificationEmail($data));
                } catch (Exception $e) {
                    Log::error($e);
                }
                DB::table('users_email_verification')->insert([
                    'verification_hash_code' => $hash_to_verify,
                    'user_id' => $user_id,
                ]);
                return redirect()->back();
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function dash_store_editor(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $user = User::where('email', $request['email'])->first();
                if(isset($user)){
                    return redirect()->back()->with('error','Email already exists');
                }
                // dd($request);
                $username = 'edt_' . time();
                if ($request['role'] == 'pharmacy') {
                    $id = User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'editor_pharmacy',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                    ])->id;
                } else if ($request['role'] == 'lab') {
                    $id = User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'editor_lab',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                    ])->id;
                } else if ($request['role'] == 'imaging') {
                    $id = User::create([
                        'name' => $request['dtitle'],
                        'username' => $username,
                        'user_type' => 'editor_imaging',
                        'email' => $request['email'],
                        'created_by' => auth()->user()->id,
                        'password' => Hash::make('12345678'),
                    ])->id;
                }
                $x = rand(10e12, 10e16);
                $hash_to_verify = base_convert($x, 10, 36);
                $data = [
                    'hash' => $hash_to_verify,
                    'user_id' => $id,
                    'to_mail' => $request['email'],
                ];
                try {
                    Mail::to($request['email'])->send(new UserVerificationEmail($data));
                } catch (Exception $e) {
                    Log::error($e);
                }
                DB::table('users_email_verification')->insert([
                    'verification_hash_code' => $hash_to_verify,
                    'user_id' => $id,
                ]);
                $type = auth()->user()->user_type;
                if ($type == 'admin_lab' || $type == 'admin_pharmacy' || $type == 'admin_imaging') {
                    ActivityLog::add_activity('added editor ' . $request['dtitle'], $id, 'editor_created');
                    return redirect()->route('all_editors');
                }
                return redirect()->back()->with('success','Added Successfully');
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }

    public function dash_view_user($type,$id)
    {
        // dd($type,$id);s
        if($type == 'doctor'){
            return redirect()->route('all_doctor_view',['id'=>$id ]);
        }elseif($type == 'patient'){
            return redirect()->route('patient_detailed',['id'=>$id ]);
        }elseif($type == 'editor_lab' || $type == 'editor_pharmacy' || $type == 'editor_imaging'){
            return redirect()->route('dash_editor_details',['id'=>$id ]);
        }elseif($type == 'admin_lab' || $type == 'admin_pharm' || $type == 'admin_imaging' || $type == 'admin_finance'){
            return redirect()->route('dash_admin_details',['id'=>$id ]);
        }else{
            return redirect()->back();
        }
    }

    public function dash_editor_details($id)
    {
        $user = User::find($id);
        //$state = State::find($user->state);
        $allProducts = Pharmacy::get_created_products_by_id($id);
        $productCategories = Pharmacy::get_created_product_categories_by_id($id);
        $productsSubCategories = Pharmacy::get_created_product_sub_categories_by_id($id);
        $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->paginate(10);
        foreach ($activities as $act) {
            $act->date = User::Convert_utc_to_user_timezone(auth()->user()->id,$act->created_at)['date'];
            $act->time = User::Convert_utc_to_user_timezone(auth()->user()->id,$act->created_at)['time'];
        }
        $user_type = Auth::user()->user_type;
        // dd($id);
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.Manage_users.editor_details',compact('user','allProducts','productCategories','productsSubCategories','activities'));
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_admin.manage_users.editor_details',compact('user','allProducts','productCategories','productsSubCategories','activities'));
        }elseif($user_type == 'admin'){
            return view('dashboard_admin.manage_users.editor_details',compact('user','allProducts','productCategories','productsSubCategories','activities'));
        }
    }

    public function pharmacy_admin_change_status($id){
        $user = DB::table('users')->where('id',$id)->first();
        // dd($user->active);
        if($user->active == '0'){
            DB::table('users')->where('id',$id)->update([
                'active' => '1',
            ]);
        }else{
            DB::table('users')->where('id',$id)->update([
                'active' => '0',
            ]);
        }
        return redirect()->back();
    }

    public function dash_admin_details($id)
    {

        $user = User::find($id);
        //$state = State::find($user->state);
        $allProducts = Pharmacy::get_created_products_by_id($id);
        $productCategories = Pharmacy::get_created_product_categories_by_id($id);
        $productsSubCategories = Pharmacy::get_created_product_sub_categories_by_id($id);
        $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->paginate(10);
        foreach ($activities as $act) {
            $act->date = User::Convert_utc_to_user_timezone(auth()->user()->id,$act->created_at)['date'];
            $act->time = User::Convert_utc_to_user_timezone(auth()->user()->id,$act->created_at)['time'];
        }
        return view('dashboard_admin.manage_users.admin_details',compact('user','allProducts','productCategories','productsSubCategories','activities'));
    }




//documents

public function document()
{
    return view('dashboard_admin.documents.add_documents.index');
}

public function store_documents(Request $request){

    $validated = $request->validate([
        'content' => 'required',
    ]);

    $insert_arr = array(
        'name' => "term of use",
        'content' => $request->input('content'),
    );

    $query = Document::insert($insert_arr);
    return redirect()->route('docs')
    ->with('success','document created successfully.');
}

public function store_policy(Request $request){

    $validated = $request->validate([
        'content' => 'required|max:255',
    ]);

    $insert_arr = array(
        'content'       => $request->input('content'),

    );

    $query = Policy::insert($insert_arr);
    return redirect()->route('view_privacy_policy')
    ->with('success','document created successfully.');
}






    public function terms_of_use()
    {
        return view('superadmin.terms_and_condition.terms_of_use');

    }
    public function privacy_policy()
    {
        return view('superadmin.privacy_policy.privacy_policy');

    }
    public function view_psychiatrist_services()
    {
        $data = DB::table('products_sub_categories')->where('parent_id',44)->get();
        return view('superadmin.Psychiatrist.view_psychiatrist_services', ['data' => $data]);
    }
    public function admin_view_psychiatrist_services()
    {
        $data = DB::table('products_sub_categories')->where('parent_id',44)->get();
        return view('dashboard_admin.psychiatry.index', ['data' => $data]);
    }
    public function admin_add_therapy_issue()
    {
        return view('dashboard_admin.therapy.add_therapy_issue');
    }
    public function admin_view_therapy_issue()
    {
        $data = DB::table('products_sub_categories')->where('parent_id',58)->get();
        return view('dashboard_admin.therapy.view_therapy_issues', ['data' => $data]);
    }
    public function admin_edit_therapy_issue($id)
    {
        $data = DB::table('products_sub_categories')->where('id',$id)->first();
        return view('dashboard_admin.therapy.edit_therapy_issue', ['data' => $data]);
    }
    public function view_terms_of_use()
    {
        $data = Document::where('name','term of use')->orderBy('id', 'DESC')->get();
        return view('superadmin.terms_and_condition.view_terms_of_use', ['data' => $data]);
    }
    public function view_privacy_policy()
    {
        $data = Policy::where('name','privacy policy')->orderBy('id', 'DESC')->get();
        return view('superadmin.privacy_policy.view_privacy_policy', ['data' => $data]);
    }

    //show_documents
    public function show_document()
    {
        $data = Document::where('name','term of use')->orderBy('id', 'DESC')->get();
        return view('dashboard_admin.documents.view_documents.show', ['data' => $data]);
    }

    public function view_terms($id, Request $request)
    {
        $data = Document::find($id);
        $msg = Document::where('id', $id)->first();
        return view('superadmin.terms_and_condition.show_terms', ['msg' => $msg]);
    }

    public function delete_doc(Request $request){
        $input = $request->all();
        $data = Document::find($input['id']);
        $data->delete();
        return redirect('/admin/documents/view');
    }


    public function delete_terms($request)
    {
        $data = Document::find($request);
        $data->delete();
        return redirect('/view_terms_of_use');
    }

    public function delete_policy($request)
    {
        $data = Policy::find($request);
        $data->delete();
        return redirect('/view_privacy_policy');

    }


    public function delete_document($id){

        Document::where('id',$id)->delete();
        return redirect()->route('show_document')
        ->with('success',' Deleted successfully.');
    }



    public function edit_terms($id)
    {
        $data = Document::find($id);
        return view('superadmin.terms_and_condition.update_terms_of_use', compact('data'));

    }

    public function edit_policy($id)
    {
        $data = Policy::find($id);
        return view('superadmin.privacy_policy.update_privacy_policy', compact('data'));

    }


    public function edit_term($id)
    {
        $data = Document::find($id);
        return view('dashboard_admin.documents.view_documents.edit', compact('data'));

    }


    public function update_terms(Request $request, $id)
    {
        $input=$request->all();
        // dd($input['content']);
        $data = Document::find($id);
        $data->content = $input['content'];
        $data->save();
        Flash::success('Document updated successfully.');
        return back();

    }

    public function update_policy(Request $request, $id)
    {
        $input=$request->all();
        $data = Policy::find($id);
        $data->content = $input['content'];
        $data->save();
        Flash::success('Document updated successfully.');
        return back();

    }

//update_document
    public function update_term(Request $request)
    {
        $input=$request->all();
        $data = Document::find($request->id);
        // dd($data);
        $data->content = $input['content'];
        $data->save();
        return redirect()->route('docs')
    ->with('success','document updated successfully.');

    }

    public function show_terms($id, Request $request)
    {
        $data = Document::find($id);
        $msg = Document::where('id', $id)->first();
        return view('superadmin.terms_and_condition.show_terms', ['msg' => $msg]);
    }

    public function show_policy($id, Request $request)
    {
        $data = Policy::find($id);
        $msg = Policy::where('id', $id)->first();
        return view('superadmin.privacy_policy.show_privacy_policy', ['msg' => $msg]);
    }

    public function doctor_percentage($doc_id)
    {
        return view('superadmin.doctor_percentage', compact('doc_id'));
    }
    public function add_doctor_percentage(Request $request, $doc_id)
    {
        $validatedData = $request->validate(
            ['doc_percentage' => ['required']],
            ['doc_percentage.required' => 'Please add some percentage to doctor its required']
        );
        $exist = DB::table('doctor_percentage')->where('doc_id', $doc_id)->first();
        if ($exist != null) {
            DB::table('doctor_percentage')->where('doc_id', $doc_id)->update(['percentage' => $request->doc_percentage]);
            return redirect()->back()->with('message', 'Update Persentage Of Doctor Successfully');
        } else {
            DB::table('doctor_percentage')->insert(['doc_id' => $doc_id, 'percentage' => $request->doc_percentage]);
            return redirect()->back()->with('message', 'Add Persentage To Doctor Successfully');

        }

    }




    public function change_percentage(Request $request, $doctor)
    {
        $validatedData = $request->validate(
            ['doc_percentage' => ['required']],
            ['doc_percentage.required' => 'Please add some percentage to doctor its required']
        );
        $exist = DB::table('doctor_percentage')->where('doc_id', $doctor)->first();
// dd($exist);
        if ($exist != null) {
            DB::table('users')->where('id',$doctor)->update([
                'active'=>'0',
            ]);
            DB::table('contracts')->where('provider_id',$doctor)->update([
                'status'=>'sent',
                'session_percentage' => $request->doc_percentage,
            ]);
            DB::table('doctor_percentage')->where('doc_id', $doctor)->update(['percentage' => $request->doc_percentage]);
            $provider = User::find($doctor);
            $slug = 'UMB' . time();
            $contract = [
                'slug' => $slug,
                'provider_id' => $doctor,
                'provider_name' => $provider->name . ' ' . $provider->last_name,
                'provider_address' => $provider->office_address,
                'provider_email_address' => $provider->email,
                'provider_speciality' => Specialization::find($provider->specialization)->name,
                'signature' => $provider->signature,
                'date' => date('Y-m-d'),
                'session_percentage' => $request['doc_percentage'],
                'status' => 'sent',
            ];
            Contract::create($contract);
            $url = "/provider_contract/" . $slug;
            Mail::to($provider->email)->send(new ResendContract($contract, $url));
            return redirect()->back()->with('message', 'Update Persentage Of Doctor Successfully');
        } else {
            DB::table('doctor_percentage')->insert(['doc_id' => $doctor, 'percentage' => $request->doc_percentage]);
            return redirect()->back()->with('message', 'Add Persentage To Doctor Successfully');

        }

    }







    public function states()
    {
        $data = array();
        $states = State::where('country_code', 'US')->get();
        $spec = DB::table('specializations')->where('status',1)->get();
        array_push($data,$states,$spec);
        return $data;
    }

    public function allStates()
    {
        $states = State::where('country_code', 'US')->paginate(10);
        return view('superadmin.states.all-states', compact('states'));
    }
    public function dash_allStates(Request $request)
    {
        if(isset($request)){
            $states = State::where('country_code', 'US')
            ->where('name','LIKE','%'.$request->name.'%')
            ->orwhere('state_code','LIKE','%'.$request->name.'%')
            ->orderBy('active','desc')
            ->paginate(10);
        }else{
            $states = State::where('country_code', 'US')
            ->orderBy('active','desc')
            ->paginate(10);
        }
        return view('dashboard_admin.All_state.index', compact('states'));
    }
    public function activateStates($id)
    {
        State::find($id)->update(['active' => '1']);
        return redirect()->back()->with(['msg' => 'State activated successfully']);

    }
    public function deactivateStates($id)
    {
        State::find($id)->update(['active' => '0']);
        return redirect()->back()->with(['msg' => 'State deactivated successfully']);

    }
    public function allOrdersDetails($type, $id)
    {
        $user_obj = new User();
        $items = $user_obj->get_user_all_orders_items($id);
        // dd($items);
        $items = $items[$type];
        // dd($items);
        return view('superadmin.order_details', compact('items'));
    }

    public function send_email(Request $request)
    {
        $user_type = Auth::user()->user_type;
        if (Auth::check()) {
            if ($user_type == 'admin' || $user_type == 'admin_lab' || $user_type == 'admin_pharmacy' || $user_type == 'admin_imaging') {
                 $data = array('name'=>$request->email,'subject'=>$request->subject,'body'=>$request->ebody);
                Mail::to($request->email)->send(new SendEmail($data));

                return redirect(url()->previous());
            }
            else {
                return redirect('/home');
            }
        }
        else {
            return redirect('/login');
        }
    }





    public function send_mail(Request $request)
    {
        // dd($request->all());
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                $name = DB::table('users')->where('email', $request->email)->select('users.name')->first();
                $data = array('name'=>$name->name,'subject'=>$request->subject,'body'=>$request->ebody);
                // dd($data);
                Mail::to($request->email)->send(new SendEmail($data));

                return redirect(url()->previous());
            }
            else {
                return redirect('/home');
            }
        }
        else {
            return redirect('/login');
        }
    }

    public function lab_editor_status($id)
    {
        $user = User::find($id);
        if($user->status == 'active')
        {
            $user->status = 'deactivate';
            $user->save();
        }
        else
        {
            $user->status = 'active';
            $user->save();
        }
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'Changed editor status to ' . $user->status,
            'type'=>'editor_status_changed',
            'party_involved'=>$user->id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect(url()->previous());
    }
    public function activate_user(Request $request)
    {
        // dd($request->all());
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                DB::table('users')->where('id',$request->user_id)->update([
                    'active' => 1,
                ]);
                return redirect(url()->previous());
            }
            else {
                return redirect('/home');
            }
        }
        else {
            return redirect('/login');
        }
    }
    public function deactivate_user(Request $request)
    {
        // dd($request->all());
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {

                DB::table('users')->where('id',$request->user_id)->update([
                    'active' => 0,
                ]);
                return redirect(url()->previous());
            }
            else {
                return redirect('/home');
            }
        }
        else {
            return redirect('/login');
        }
    }


    public function lab_admin_dash()
    {
        $lab_editors = DB::table('users')
        ->where('user_type','editor_lab')
        ->paginate(10);
        $edt = DB::table('users')
        ->where('user_type','editor_lab')
        ->get()->toArray();
        $edt = json_encode($edt);
        return view('dashboard_Lab_admin.Lab_admin',compact('lab_editors','edt'));
    }

    public function add_editor(Request $request)
    {
        $user_type = Auth::user()->user_type;
        if ($user_type == 'admin' || $user_type == 'admin_lab' || $user_type == 'admin_pharm' || $user_type == 'admin_imaging') {
            $username = 'edt_' . time();
            if ($request['role'] == 'pharmacy') {
                $id = User::create([
                    'name' => $request['name'],
                    'username' => $username,
                    'user_type' => 'editor_pharmacy',
                    'email' => $request['email'],
                    'created_by' => auth()->user()->id,
                    'password' => Hash::make('12345678'),
                ])->id;
            } else if ($request['role'] == 'lab') {
                $id = User::create([
                    'name' => $request['name'],
                    'username' => $username,
                    'user_type' => 'editor_lab',
                    'email' => $request['email'],
                    'created_by' => auth()->user()->id,
                    'password' => Hash::make('12345678'),
                    'status' => 'active',
                ])->id;
            } else if ($request['role'] == 'imaging') {
                $id = User::create([
                    'name' => $request['name'],
                    'username' => $username,
                    'user_type' => 'editor_imaging',
                    'email' => $request['email'],
                    'created_by' => auth()->user()->id,
                    'password' => Hash::make('12345678'),
                ])->id;
            }
            $type = auth()->user()->user_type;
            if ($type == 'admin_lab' || $type == 'admin_pharm' || $type == 'admin_imaging') {
                ActivityLog::add_activity('added editor ' . $request['name'], $id, 'editor_created');
                return redirect(url()->previous());
            }
            return redirect()->route('manage_users');
        } else {
            return redirect('/home');
        }

    }

    public function editor_details($id)
    {

        $user = User::find($id);
        //$state = State::find($user->state);
        $allProducts = Pharmacy::get_created_products_by_id($id);
        $productCategories = Pharmacy::get_created_product_categories_by_id($id);
        $productsSubCategories = Pharmacy::get_created_product_sub_categories_by_id($id);
        $activities = ActivityLog::where('user_id', $id)->orderByDesc('id')->paginate(10);
        foreach ($activities as $act) {
            $act->date = User::Convert_utc_to_user_timezone(auth()->user()->id,$act->created_at)['date'];
            $act->time = User::Convert_utc_to_user_timezone(auth()->user()->id,$act->created_at)['time'];
        }
        // dd($activities);
        if(auth()->user()->user_type == 'admin_lab')
        {
            return view('dashboard_Lab_admin.Lab_admin.editor_details',compact('user','allProducts','productCategories','productsSubCategories','activities'));
        }
        elseif(auth()->user()->user_type == 'admin_imaging')
        {
            return view('dashboard_imaging_admin.imaging_admin.editor_details',compact('user','allProducts','productCategories','productsSubCategories','activities'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function unassigned_lab_orders()
    {
        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'lab-editor-approval')
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(10);
        $data = DB::table('lab_orders')
        ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
        ->where('lab_orders.status', 'lab-editor-approval')
        ->orderByDesc('lab_orders.order_id')
        ->groupBy('lab_orders.order_id')
        ->get()->toArray();

        foreach ($pending_requisitions as $requisition) {
            $requisition->test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.order_id',$requisition->order_id)
            ->where('lab_orders.status', 'lab-editor-approval')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            // $user = User::find($requisition->user_id);
            // $state = State::find($user->state_id);
            // $requisition->user_state = $state->name;
            // $doctors = DB::table('doctor_licenses')
            //     ->join('users', 'users.id', 'doctor_licenses.doctor_id')
            //     ->where('doctor_licenses.state_id', $user->state_id)
            //     ->select('users.name', 'users.last_name', 'users.id as user_id')
            //     ->get()->toArray();
            $doctors = DB::table('doctor_licenses')
            ->join('users', 'users.id', 'doctor_licenses.doctor_id')
            // ->where('doctor_licenses.state_id', $user->state_id)
            ->join('lab_approval_doctors', 'users.id','lab_approval_doctors.doctor_id')
            ->select('users.name', 'users.last_name', 'users.id as user_id')
            ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval declined by Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }

        foreach ($data as $requisition) {
            $requisition->test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.order_id',$requisition->order_id)
            ->where('lab_orders.status', 'lab-editor-approval')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
            $requisition->test_name = json_encode($requisition->test_name);
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            // $user = User::find($requisition->user_id);
            // $state = State::find($user->state_id);
            // $requisition->user_state = $state->name;
            // $doctors = DB::table('doctor_licenses')
            //     ->join('users', 'users.id', 'doctor_licenses.doctor_id')
            //     ->where('doctor_licenses.state_id', $user->state_id)
            //     ->select('users.name', 'users.last_name', 'users.id as user_id')
            //     ->get()->toArray();
            $doctors = DB::table('doctor_licenses')
            ->join('users', 'users.id', 'doctor_licenses.doctor_id')
            // ->where('doctor_licenses.state_id', $user->state_id)
            ->join('lab_approval_doctors', 'users.id','lab_approval_doctors.doctor_id')
            ->select('users.name', 'users.last_name', 'users.id as user_id')
            ->get()->toArray();
            // dd($doctors);
            $doctors = json_encode($doctors);
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval declined by Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }
        $data = json_encode($data);
        return view('dashboard_Lab_admin.Order_Approvals.unassigned_orders',compact('pending_requisitions','data'));
    }

    public function pendingLabOrders()
    {

        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);
        $data = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->get()->toArray();

        foreach ($pending_requisitions as $requisition) {
            $requisition->test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.order_id',$requisition->order_id)
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval Requested To Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }

        foreach ($data as $requisition) {
            $requisition->test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.order_id',$requisition->order_id)
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
            $requisition->test_name = json_encode($requisition->test_name);
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Approval Requested To Dr." . $doc->name . ' ' . $doc->last_name;
            } else {
                $requisition->decline_status = "Not assigned to any doctor yet";
            }
        }
        $data = json_encode($data);
        // $user = Auth::user();
        // $orders = $this->tblOrdersRepository->getAllPendingOrders();
        // foreach ($orders as $order) {
        //     $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at);
        //     $order->created_at = $order->created_at['date'] . ' ' . $order->created_at['time'];
        // }
        // dd($orders);
        return view('dashboard_Lab_admin.Order_Approvals.pending_orders', compact('pending_requisitions','data'));
    }

    public function pendingrefunds()
    {

        $pending_requisitions = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.status', 'cancelled')
            ->orderByDesc('lab_orders.order_id')
            ->groupBy('lab_orders.order_id')
            ->paginate(9);

        foreach ($pending_requisitions as $requisition) {
            $requisition->test_name = DB::table('lab_orders')
            ->join('quest_data_test_codes', 'lab_orders.product_id', 'quest_data_test_codes.TEST_CD')
            ->where('lab_orders.order_id',$requisition->order_id)
            ->where('lab_orders.status', 'forwarded_to_doctor')
            ->orderByDesc('lab_orders.order_id')
            ->select('quest_data_test_codes.TEST_NAME', 'lab_orders.order_id')
            ->get()->toArray();
            $requisition->date = User::convert_utc_to_user_timezone($requisition->user_id, $requisition->created_at);
            $requisition->date = $requisition->date['date'];
            $user = User::find($requisition->user_id);
            $state = State::find($user->state_id);
            $requisition->user_state = $state->name;
            $doctors = DB::table('doctor_licenses')
                ->join('users', 'users.id', 'doctor_licenses.doctor_id')
                ->where('doctor_licenses.state_id', $user->state_id)
                ->select('users.name', 'users.last_name', 'users.id as user_id')
                ->get()->toArray();
            $requisition->doctors = $doctors;

            if ($requisition->doc_id != null) {
                $doc = User::find($requisition->doc_id);
                $requisition->decline_status = "Last assigned to Dr." . $doc->name . ' ' . $doc->last_name.' and got cancelled';
            } else {
                $requisition->decline_status = "called and have to refund amount";
            }
        }

        // $user = Auth::user();
        // $orders = $this->tblOrdersRepository->getAllPendingOrders();
        // foreach ($orders as $order) {
        //     $order->created_at = User::convert_utc_to_user_timezone($user->id, $order->created_at);
        //     $order->created_at = $order->created_at['date'] . ' ' . $order->created_at['time'];
        // }
        // dd($orders);
        return view('dashboard_Lab_admin.Order_Approvals.pending_refunds', compact('pending_requisitions'));
    }

    public function quest_orders()
    {

        $requisitions = DB::table('quest_labs')
        ->join('quest_requests', 'quest_labs.id', '=', 'quest_requests.quest_lab_id')
        ->join('lab_orders', 'quest_requests.order_id', '=', 'lab_orders.order_id')
        ->where('lab_orders.status', 'quest-forwarded')
        ->groupBy('quest_requests.id')
        ->orderByDesc('quest_requests.id')->paginate(9);
        $req =  DB::table('quest_labs')
        ->join('quest_requests', 'quest_labs.id', '=', 'quest_requests.quest_lab_id')
        ->join('lab_orders', 'quest_requests.order_id', '=', 'lab_orders.order_id')
        ->where('lab_orders.status', 'quest-forwarded')
        ->groupBy('quest_requests.id')
        ->orderByDesc('quest_requests.id')->get()->toArray();
        foreach ($requisitions as $requisition) {
            $requisition->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $requisition->created_at);
            $requisition->names = json_decode($requisition->names);
        }
        foreach ($req as $re) {
            $re->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $re->created_at);
        }
        $req = json_encode($req);
        return view('dashboard_Lab_admin.Orders.quest_orders', compact('requisitions','req'));
    }

    public function quest_failed_requests()
    {
        if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'admin_lab') {
            $results = QuestResult::where('status', '!=', 'success')
                ->where('status', '!=', 'resolved')
                ->paginate(10);
            $hl7_obj = new HL7Controller();
            foreach ($results as $result) {
                $decoded = $hl7_obj->hl7Decode($result->hl7_message);
                $result->patient_matching = $decoded['patient_matching'];
                $result->placer_order_num = $decoded['arrOBR'][0]['placer_order_num'];
                $result->filler_order_num = $decoded['arrOBR'][0]['filler_order_num'];
            }
            // dd($results);

            return view('dashboard_Lab_admin.Orders.quest_failed_requests', compact('results'));
        } else {
            return redirect()->route('home');
        }
    }

    public function online_labtests(Request $request)
    {
        $data = DB::table('quest_data_test_codes')
        ->leftJoin('product_categories', 'quest_data_test_codes.PARENT_CATEGORY', '=', 'product_categories.id')
        ->select(
            'quest_data_test_codes.*',
            // 'product_categories.name as main_category_name'
        )
        ->where([['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],['quest_data_test_codes.AOES_exist', null]])
        // ->where('quest_data_test_codes.TEST_CD', '1759')
        ->orderBy('quest_data_test_codes.id', 'ASC')
        ->get()->toArray();

        foreach ($data as $item) {
            $ids = explode(",", $item->PARENT_CATEGORY);
            $cat_names = ProductCategory::select(
                DB::raw("GROUP_CONCAT(`name`) AS names"),
            )->whereIn('id', $ids)->first();
            $item->main_category_name = $cat_names->names;
            // $item->featured_image = \App\Helper::check_bucket_files_url($item->featured_image);
        }

        $da = $data;
        $data = (new Collection($data))->paginate(10);

        $categories = DB::table('product_categories')
            ->where('category_type','lab-test')
            ->orWhere('category_type','imaging')
            ->get();
        $states = DB::table('states')->where('country_id','233')->get();

        if(auth()->user()->user_type == 'admin_lab')
        {
            return view('dashboard_Lab_admin.Lab_Tests.online_lab_tests',compact('data','categories','states','da'));
        }
        elseif(auth()->user()->user_type == 'editor_lab')
        {
            return view('dashboard_Lab_editor.Lab_Tests.online_lab_tests',compact('data','categories','states','da'));
        }
        else
        {
            return redirect()->back();
        }

        //return view('all_products.index')->with(['allProducts' => $this->converToArrayToObj($allProducts), 'user' => $user]);
    }

    public function del_online_labtest(Request $request)
    {
        DB::table('quest_data_test_codes')->where('TEST_CD',$request->id)->delete();
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'deleted online lab test',
            'type'=>'deleted_lab_test',
            'product_id'=>$request->id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect(url()->previous());
    }

    public function create_online_labtest(Request $request)
    {
        $slug = $this->slugify($request->name);
        $product_category = DB::table('product_categories')->where('id',$request->category)->first();
        $id = DB::table('quest_data_test_codes')->insertGetId(
            [
                'TEST_CD' => $request->tcd,
                'TEST_NAME' => $request->name,
                'DESCRIPTION' => $request->description,
                // 'LEGAL_ENTITY' => $request->LE,
                // 'UNIT_CD' => $request->uc,
                // 'STATE' => $request->state,
                'PRICE' => $request->price,
                'SALE_PRICE' => $request->sale_price,
                'PARENT_CATEGORY' => $request->category,
                'SPECIMEN_TYPE' => $request->sp_type,
                // 'medicine_type' => $request->type,
                // 'NBS_SERVICE_CODE' => $request->NSC,
                // 'TOPLAB_PERFORMING_SITE' => $request->TLPS,
                // 'NBS_PERFORMING_SITE' => $request->NBSPS,
                // 'SUFFIX' => $request->suffix,
                // 'PROFILE_IND' => $request->PIND,
                // 'SELECTEST_IND' => $request->STI,
                // 'TEST_FLAG' => $request->Tflag,
                // 'NO_BILL_INDICATOR' => $request->NBI,
                // 'BILL_ONLY_INDICATOR' => $request->BOI,
                // 'SEND_OUT_REFLEX_COUNT' => $request->SORC,
                // 'AOES_exist' => $request->aoess,
                'DETAILS' => $request->details,
                'mode' => $product_category->category_type,
                'ACTIVE_IND' => 'A',
                'UPDATE_USER' => 'OEAPP',
                'INSERT_DATETIME' => date('Y-m-d H:i:s'),
                'SLUG' => $slug,
            ]
        );

        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'added online lab test '.$request->name,
            'type'=>'added_lab_test',
            'product_id'=>$request->tcd,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect(url()->previous());
    }

    public function edit_lab_test(Request $request)
    {
        $test = DB::table('quest_data_test_codes')->where('TEST_CD',$request->test_cd)->first();
        $product_category = DB::table('product_categories')->where('id',$request->category)->first();
        DB::table('quest_data_test_codes')->where('TEST_CD',$request->test_cd)->update([
            'TEST_NAME' => $request->tn,
            'PRICE' => $request->pr,
            'SALE_PRICE' => $request->sp,
            'PARENT_CATEGORY' => $request->category,
            'mode' => $product_category->category_type,
            'DETAILS' => $request->sn,
            'DESCRIPTION' => $request->des,
            'UPDATE_DATETIME' => now(),
        ]);
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'edited online lab test '.$request->tn,
            'type'=>'edited_lab_test',
            'product_id'=>$request->test_cd,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect(url()->previous());
    }

    public function quest_lab_tests()
    {
        $data = DB::table('quest_data_test_codes')
            ->leftJoin('product_categories', 'quest_data_test_codes.PARENT_CATEGORY', '=', 'product_categories.id')
            ->select(
                'quest_data_test_codes.TEST_CD',
                'quest_data_test_codes.DESCRIPTION',
                'quest_data_test_codes.TEST_NAME',
                'quest_data_test_codes.PRICE',
                'quest_data_test_codes.SALE_PRICE',
                'quest_data_test_codes.PARENT_CATEGORY',
                'quest_data_test_codes.DETAILS',
                // 'product_categories.name as main_category_name'
            )
            ->where([['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],])
            // ->where('quest_data_test_codes.TEST_CD', '1759')
            ->orderBy('quest_data_test_codes.TEST_NAME', 'ASC')
            ->get()->toArray();
        $sub = ProductCategory::where('category_type','lab-test')->get();
        foreach ($data as $item) {
            $ids = explode(",", $item->PARENT_CATEGORY);
            $cat_names = ProductCategory::select(
                DB::raw("GROUP_CONCAT(`name`) AS names"),
            )->whereIn('id', $ids)->first();
            $item->main_category_name = $cat_names->names;
        }

        $da = $data;
        $data = (new Collection($data))->paginate(10);

        if(auth()->user()->user_type == 'admin_lab')
        {
            return view('dashboard_Lab_admin.Lab_Tests.quest_lab_tests', compact('data','da','sub'));
        }
        elseif(auth()->user()->user_type == 'editor_lab')
        {
            return view('dashboard_Lab_editor.Lab_Tests.quest_lab_tests', compact('data','da','sub'));
        }
        else
        {
            return redirect()->back();
        }

    }

    public function lab_admin_setting()
    {
        return view('dashboard_Lab_admin.AccountSetting.index');
    }

    public function pharmacy_admin_manage_editors()
    {
        $users = DB::table('users')->where('user_type','editor_pharmacy')->get();
        return view('dashboard_Pharm_admin.Manage_users.index', compact('users'));
    }

    public function pharmacy_editor_orders()
    {
        $user = auth()->user();
        $tblOrders = DB::table('medicine_order')
        ->join('tbl_products', 'medicine_order.order_product_id', '=', 'tbl_products.id')
        ->join('users', 'users.id', '=', 'medicine_order.user_id')
        ->join('states', 'states.id', '=', 'users.state_id')
        ->join('cities', 'cities.id', '=', 'users.city_id')
        ->join('tbl_orders', 'tbl_orders.order_id', '=', 'medicine_order.order_main_id')
        ->select(
            'tbl_products.name as name',
            'tbl_products.sale_price as total',
            'medicine_order.*',
            'users.name as fname',
            'users.last_name as lname',
            'users.office_address as address',
            'cities.name as order_city',
            'states.name as order_state',
            'medicine_order.status as order_status',
            'medicine_order.session_id as session_id',
            'medicine_order.order_product_id as product_id',
            'medicine_order.update_price as price',
            'medicine_order.order_main_id as order_id',
            'tbl_orders.payment_title',
            'tbl_orders.payment_method',
            'tbl_orders.currency',
            'tbl_orders.created_at as created_at',
        )
        ->orderBy('medicine_order.id', 'desc')
        ->orderBy('order_status')
        ->paginate(10);
        $data = DB::table('medicine_order')
        ->join('tbl_products', 'medicine_order.order_product_id', '=', 'tbl_products.id')
        ->join('users', 'users.id', '=', 'medicine_order.user_id')
        ->join('states', 'states.id', '=', 'users.state_id')
        ->join('cities', 'cities.id', '=', 'users.city_id')
        ->join('tbl_orders', 'tbl_orders.order_id', '=', 'medicine_order.order_main_id')
        ->select(
            'tbl_products.name as name',
            'tbl_products.sale_price as total',
            'medicine_order.*',
            'users.name as fname',
            'users.last_name as lname',
            'users.office_address as address',
            'cities.name as order_city',
            'states.name as order_state',
            'medicine_order.status as order_status',
            'medicine_order.session_id as session_id',
            'medicine_order.order_product_id as product_id',
            'medicine_order.update_price as price',
            'medicine_order.order_main_id as order_id',
            'tbl_orders.payment_title',
            'tbl_orders.payment_method',
            'tbl_orders.currency',
            'tbl_orders.created_at as created_at',
        )
        ->orderBy('medicine_order.id', 'desc')
        ->orderBy('order_status')
        ->get()->toArray();

        foreach ($tblOrders as $tblOrder) {
            $datetime = date('Y-m-d h:i A', strtotime($tblOrder->created_at));
            $tblOrder->created_at = User::convert_utc_to_user_timezone($user->id, $datetime);
            $tblOrder->created_at = $tblOrder->created_at['date']." ".$tblOrder->created_at['time'];
        }
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.Orders.index',compact('tblOrders','data'));
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.Orders.index',compact('tblOrders','data'));
        }
    }



    public function pharmacy_editor_setting()
    {
        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.AccountSetting.index');
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.AccountSetting.index');
        }
    }

    public function lab_editor_setting()
    {
        return view('dashboard_Lab_editor.AccountSetting.index');
    }
    public function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public function imaging_admin_dash()
    {
        $user_type = auth()->user()->user_type;
        if($user_type == "admin_imaging")
        {
            $lab_editors = DB::table('users')
            ->where('user_type','editor_imaging')
            ->paginate(10);
            $edt = DB::table('users')
            ->where('user_type','editor_imaging')
            ->get()->toArray();
            $edt = json_encode($edt);
            return view('dashboard_imaging_admin.imaging_admin',compact('lab_editors','edt'));
        }
        else
        {
            return redirect()->route('wrong_address');
        }
    }

    public function img_editor_dash()
    {
        $old = ini_set('memory_limit', '8192M');
        $records = DB::table('imaging_prices')
        ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_prices.location_id')
        ->join('tbl_products', 'tbl_products.id', '=', 'imaging_prices.product_id')
        ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
        ->select(
            'imaging_prices.id as id',
            'imaging_prices.id as price_id',
            'product_categories.name AS product_category',
            'tbl_products.name as product_name',
            'tbl_products.cpt_code',
            'imaging_prices.price AS price',
            'imaging_prices.actual_price AS ac_price',
            'imaging_locations.clinic_name AS state',
            'imaging_locations.city',
            'imaging_locations.zip_code',
            'imaging_locations.lat',
            'imaging_locations.long',
            'imaging_locations.address',
            DB::raw("CONCAT(`city`, ' ', `clinic_name`, '(', `zip_code`, ')') AS `location_name`")
        )->where('tbl_products.mode', 'imaging')
        ->orderby('imaging_prices.id','DESC')
        ->paginate(10);

        $data = DB::table('imaging_prices')
        ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_prices.location_id')
        ->join('tbl_products', 'tbl_products.id', '=', 'imaging_prices.product_id')
        ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
        ->select(
            'imaging_prices.id as id',
            'imaging_prices.id as price_id',
            'product_categories.name AS product_category',
            'tbl_products.name as product_name',
            'tbl_products.cpt_code',
            'imaging_prices.price AS price',
            'imaging_prices.actual_price AS ac_price',
            'imaging_locations.clinic_name AS state',
            'imaging_locations.city',
            'imaging_locations.zip_code',
            'imaging_locations.lat',
            'imaging_locations.long',
            'imaging_locations.address',
            DB::raw("CONCAT(`city`, ' ', `clinic_name`, '(', `zip_code`, ')') AS `location_name`")
        )->where('tbl_products.mode', 'imaging')
        ->orderby('imaging_prices.id','DESC')
        ->get()->toArray();
        $data = json_encode($data);
        if(auth()->user()->user_type == "admin_imaging")
        {
            return view('dashboard_imaging_admin.All_record.all_records',compact('records','data'));
        }
        elseif(auth()->user()->user_type == "editor_imaging")
        {
            return view('dashboard_imaging_editor.imaging_editor',compact('records','data'));
        }
        else
        {
            return redirect(url()->previous());
        }
    }

    //imaging admin functions
    public function imaging_services(Request $request)
    {
        $services =  DB::table('tbl_products')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->select(
                'tbl_products.id',
                'tbl_products.name as product_name',
                'tbl_products.cpt_code',
                'product_categories.name as product_category',
                'product_categories.id as product_category_id',
                'tbl_products.short_description',
                'tbl_products.description',
            )
            ->where('tbl_products.mode', 'imaging')
            ->paginate(10);
        $data =  DB::table('tbl_products')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->select(
                'tbl_products.id',
                'tbl_products.name as product_name',
                'tbl_products.cpt_code',
                'product_categories.name as product_category',
                'product_categories.id as product_category_id',
                'tbl_products.short_description',
                'tbl_products.description',
            )
            ->where('tbl_products.mode', 'imaging')
            ->get()->toarray();
        $data = json_encode($data);
        $categories = DB::table('product_categories')->where('category_type','imaging')->get();
        if(auth()->user()->user_type == "admin_imaging")
        {
            return view('dashboard_imaging_admin.imaging_services.services',compact('services','categories','data'));
        }
        elseif(auth()->user()->user_type == "editor_imaging")
        {
            return view('dashboard_imaging_editor.imaging_services.services',compact('services','categories','data'));
        }
        else
        {
            return redirect(url()->previous());
        }
    }

    public function add_imaging(Request $request)
    {
        $slug = $this->slugify($request->name);
        $id = DB::table('tbl_products')->insertGetId(
        [
            'name' => $request->name,
            'slug' => $slug,
            'featured_image' => 'default-imaging.png',
            'mode' => 'imaging',
            'medicine_type' => 'prescribed',
            'short_description' => $request->short_description,
            'description' => $request->description,
            'cpt_code' => $request->cpt_code,
            'user_id' => auth()->user()->id,
            'product_status' => 1,
            'is_approved' => 1,
            'parent_category' => $request->category,

        ]);
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'added imaging service '.$request->name,
            'type'=>'imaging_service_added',
            'product_id'=>$id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg'=>'service added succesfully']);
    }
    public function del_imaging(Request $request)
    {
        DB::table('tbl_products')->where('id',$request->id)->delete();
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'deleted imaging service',
            'type'=>'imaging_service_deleted',
            'product_id'=>$request->id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg'=>'service deleted succesfully']);
    }

    public function edit_imaging(Request $request)
    {
        $slug = $this->slugify($request->name);
        DB::table('tbl_products') ->where('id',$request->id)->update(
        [
            'name' => $request->name,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'cpt_code' => $request->cpt_code,
            'parent_category' => $request->category,

        ]);
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'edited imaging service '.$request->name,
            'type'=>'imaging_service_edited',
            'product_id'=>$request->id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg'=>'service updated succesfully']);
    }
    public function getCoordinates($zip)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zip) . "&key=AIzaSyDRPb5zlYiohViujlUkCaMsBjwYMzhONGk";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return $result3[0];
    }

    public function getAddress($lat, $long)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . trim($lat) . ',' . trim($long) . "&sensor=false&key=AIzaSyDRPb5zlYiohViujlUkCaMsBjwYMzhONGk";
        $json = file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if ($status == "OK") {
            return $data->results[0]->formatted_address;
        } else {
            return false;
        }
    }

    public function imaging_locations(Request $request)
    {
        $locations = DB::table('imaging_locations')->paginate(10);
        $data = DB::table('imaging_locations')->get()->toArray();
        $data = json_encode($data);
        if(auth()->user()->user_type == 'admin_imaging')
        {
            return view('dashboard_imaging_admin.imaging_location.locations',compact('locations','data'));
        }
        elseif(auth()->user()->user_type == 'editor_imaging')
        {
            return view('dashboard_imaging_editor.imaging_location.locations',compact('locations','data'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function add_imaging_locations(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();
        $getCordinate = $this->getCoordinates($input['zip']);
        $id = DB::table('imaging_locations')->insertGetId([
            'city' => $input['city'],
            'zip_code' => $input['zip'],
            'clinic_name' => $input['clinic_name'],
            'lat' => $getCordinate['lat'],
            'long' => $getCordinate['lng'],
            'address' => $this->getAddress($getCordinate['lat'], $getCordinate['lng']),
            'created_by' => $user->id,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'added imaging location '.$input['city'],
            'type'=>'imaging_location_added',
            'product_id'=>$id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg' => 'Imaging Location added successfully']);
    }

    public function edit_imaging_locations(Request $request)
    {
        $input = $request->all();
        DB::table('imaging_locations')->where('id',$input['id'])->update([
            'city' => $input['city'],
            'zip_code' => $input['zip'],
            'clinic_name' => $input['clinic_name'],
            'updated_at' => NOW(),
        ]);
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'edited imaging location '.$input['city'],
            'type'=>'imaging_location_edited',
            'product_id'=>$input['id'],
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg' => 'Imaging Location updated successfully']);
    }

    public function del_imaging_location(Request $request)
    {
        DB::table('imaging_locations')->where('id',$request->id)->delete();
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'deleted imaging location',
            'type'=>'imaging_location_deleted',
            'product_id'=>$request->id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg' => 'Imaging Location deleted successfully']);
    }

    public function imaging_prices(Request $request)
    {
        $prices = DB::table('imaging_prices')
            ->join('imaging_locations','imaging_prices.location_id','imaging_locations.id')
            ->join('tbl_products','imaging_prices.product_id','tbl_products.id')
            ->join('product_categories','tbl_products.parent_category','product_categories.id')
            ->select('imaging_prices.*','imaging_locations.id as loc_id','imaging_locations.address as loc_add',
                    'tbl_products.id as pro_id','tbl_products.name as pro_name','product_categories.name as cat_name',
                    'tbl_products.cpt_code as pro_cpt','imaging_locations.zip_code as loc_zip','imaging_locations.city as loc_st')
            ->paginate(10);
        // $data = DB::table('imaging_prices')
        //     ->join('imaging_locations','imaging_prices.location_id','imaging_locations.id')
        //     ->join('tbl_products','imaging_prices.product_id','tbl_products.id')
        //     ->join('product_categories','tbl_products.parent_category','product_categories.id')
        //     ->select('imaging_prices.*','imaging_locations.id as loc_id','imaging_locations.address as loc_add',
        //             'tbl_products.id as pro_id','tbl_products.name as pro_name','product_categories.name as cat_name',
        //             'tbl_products.cpt_code as pro_cpt','imaging_locations.zip_code as loc_zip','imaging_locations.city as loc_st')
        //     ->get()->toArray();
        // $data = json_encode($data);
        $product = DB::table('tbl_products')->where('mode','imaging')->orderby('name')->get();
        $locations = DB::table('imaging_locations')->orderby('city')->get();
        if(auth()->user()->user_type == 'admin_imaging')
        {
            return view('dashboard_imaging_admin.imaging_prices.prices',compact('prices','product','locations'));
        }
        elseif(auth()->user()->user_type == 'editor_imaging')
        {
            return view('dashboard_imaging_editor.imaging_prices.prices',compact('prices','product','locations'));
        }
        else
        {
            return redirect()->back();
        }
    }
    public function search_imaging_prices(Request $request)
    {
        $search = $request->search;
        if ($search != '') {
            $prices = DB::table('imaging_prices')
                ->join('imaging_locations','imaging_prices.location_id','imaging_locations.id')
                ->join('tbl_products','imaging_prices.product_id','tbl_products.id')
                ->join('product_categories','tbl_products.parent_category','product_categories.id')
                ->select('imaging_prices.*','imaging_locations.id as loc_id','imaging_locations.address as loc_add',
                        'tbl_products.id as pro_id','tbl_products.name as pro_name','product_categories.name as cat_name',
                        'tbl_products.cpt_code as pro_cpt','imaging_locations.zip_code as loc_zip','imaging_locations.city as loc_st')
                ->where('tbl_products.cpt_code', $search)
                ->orWhere('tbl_products.name', $search)
                ->orWhere('imaging_locations.city', $search)
                ->orWhere('imaging_locations.address', $search)
                ->paginate(10,['*'],'page');
            $product = DB::table('tbl_products')->where('mode','imaging')->orderby('name')->get();
            $locations = DB::table('imaging_locations')->orderby('city')->get();
            if($request->ajax()){
                $result = array();
                array_push($result,$prices);
                array_push($result,$product);
                array_push($result,$locations);
                array_push($result,$search);
             return $result;
            }
            if(auth()->user()->user_type == 'admin_imaging')
            {
                return view('dashboard_imaging_admin.imaging_prices.prices_search',compact('prices','product','locations','search'));
            }
            elseif(auth()->user()->user_type == 'editor_imaging')
            {
                return view('dashboard_imaging_editor.imaging_prices.prices_search',compact('prices','product','locations','search'));
            }
            else
            {
                return redirect()->back();
            }
        }else{
            return redirect()->route('imaging_prices');
        }
    }

    public function add_imaging_prices(Request $request)
    {
        $price = ((int)$request->actual_price/100)*25;
        $price += (int)$request->actual_price;
        $id = DB::table('imaging_prices')->insertGetId([
            'location_id' => $request->location_id,
            'product_id' => $request->pro_id,
            'price' => $price,
            'actual_price' => $request->actual_price,
            'created_by' => auth()->user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'added imaging price id '.$id,
            'type'=>'imaging_price_added',
            'product_id'=>$request->pro_id,
            'party_involved'=>$request->location_id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg'=>'Imaging price added successfully']);
    }

    public function edit_imaging_prices(Request $request)
    {
        DB::table('imaging_prices')->where('id',$request->id)->update([
            'location_id' => $request->location_id,
            'product_id' => $request->pro_id,
            'price' => $request->price,
            'actual_price' => $request->actual_price,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'updated imaging price id '.$request->id,
            'type'=>'imaging_price_updated',
            'product_id'=>$request->pro_id,
            'party_involved'=>$request->location_id,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg'=>'Imaging price updated successfully']);
    }

    public function del_imaging_prices(Request $request)
    {
        DB::table('imaging_prices')->where('id',$request->id)->delete();
        DB::table('activity_log')->insert([
            'user_id'=>auth()->user()->id,
            'user_type'=>auth()->user()->user_type,
            'activity'=>'deleted imaging price id '.$request->id,
            'type'=>'imaging_price_deleted',
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with(['msg'=>'Imaging price deleted successfully']);
    }

    public function imaging_admin_setting()
    {
        if(auth()->user()->user_type == 'admin_imaging')
        {
            return view('dashboard_imaging_admin.AccountSetting.index');
        }
        elseif(auth()->user()->user_type == 'editor_imaging')
        {
            return view('dashboard_imaging_editor.AccountSetting.index');

        }
        else
        {
            return redirect()->back();
        }
    }

    public function change_authorize_api_mode()
    {
        $service = DB::table('services')->where('name','authorize_api_mode')->first();
        if($service!= null && $service->status == 'testMode')
        {
            DB::table('services')->where('name','authorize_api_mode')->update(['status'=>'liveMode','updated_at'=>date('Y-m-d H:i:s')]);
        }
        elseif($service!= null && $service->status == 'liveMode')
        {
            DB::table('services')->where('name','authorize_api_mode')->update(['status'=>'testMode','updated_at'=>date('Y-m-d H:i:s')]);
        }
        return redirect()->back();
    }

    public function change_maintainance_mode()
    {
        $service = DB::table('services')->where('name','maintainance_mode')->first();
        if($service!= null && $service->status == 'off')
        {
            DB::table('services')->where('name','maintainance_mode')->update(['status'=>'on','updated_at'=>date('Y-m-d H:i:s')]);
            \Illuminate\Support\Facades\Artisan::call('down --secret="developer_mode"');
        }
        elseif($service!= null && $service->status == 'on')
        {
            DB::table('services')->where('name','maintainance_mode')->update(['status'=>'off','updated_at'=>date('Y-m-d H:i:s')]);
            \Illuminate\Support\Facades\Artisan::call('up');
        }
        return redirect()->back();
    }
    public function change_ticker(Request $request)
    {
        $service = DB::table('services')->where('name','ticker')->first();
        if($service!= null && $service->status == 'off')
        {
            DB::table('services')->where('name','ticker')->update(['status'=>'on','value'=>$request->ticker_text,'updated_at'=>date('Y-m-d H:i:s')]);
        }
        elseif($service!= null && $service->status == 'on')
        {
            DB::table('services')->where('name','ticker')->update(['status'=>'off','value'=>$request->ticker_text,'updated_at'=>date('Y-m-d H:i:s')]);
        }
        else
        {
            DB::table('services')->insert([
                'name'=>'ticker',
                'status'=>'on',
                'value'=>$request->ticker_text,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->back();
    }

    public function upload_banner()
    {
        return view('dashboard_admin.banners.upload_banner');
    }

    public function upload_new_banner(Request $request)
    {
        $filename = null;
        if($request->hasFile('image'))
        {
            $files = $request->file('image');
            $filename = \Storage::disk('s3')->put('banners', $files);
        }
        DB::table('banner')->insert([
            'img'=>$filename,
            'html'=>$request->html,
            'status'=>$request->status,
            'page_name'=>$request->page_name,
            'sequence'=>$request->sequence,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('banner uploaded succesfully');
    }
    public function view_banners()
    {
        $banners = DB::table('banner')->get();
        foreach($banners as $banner)
        {
            if($banner->img != null)
            {
                $banner->img=\App\Helper::check_bucket_files_url($banner->img);
            }
        }
        return view('dashboard_admin.banners.view_banners',compact('banners'));
    }

    public function change_banner_status($id)
    {
        $banner = DB::table('banner')->where('id',$id)->first();
        if($banner!=null)
        {
            if($banner->status=='0')
            {
                DB::table('banner')->where('id',$id)->update(['status'=>"1"]);
            }
            else
            {
                DB::table('banner')->where('id',$id)->update(['status'=>"0"]);
            }
        }
        return redirect()->back();
    }

    public function delete_banner($id)
    {
        if($id!=null)
        {
            DB::table('banner')->where('id',$id)->delete();
        }
        return redirect()->back();
    }

    public function pending_contract(){
        if (Auth::check()) {
            if (Auth::user()->user_type == 'admin') {
                if(isset($req->name)){
                    $doctors = User::where('user_type', 'doctor')
                    ->join('specializations','specializations.id','users.specialization')
                    ->join('states','users.state_id','states.id')
                    ->where('users.name','LIKE','%'. $req->name . '%')
                    ->orwhere('users.last_name','LIKE','%'. $req->name . '%')
                    ->orwhere('users.email','LIKE','%'. $req->name . '%')
                    ->orwhere('users.nip_number','LIKE','%'. $req->name . '%')
                    ->orwhere('states.name','LIKE','%'. $req->name . '%')
                    ->where('users.active', '1')
                    ->select('users.*','specializations.name as spec_name')
                    ->paginate(8);
                }else{
                    try {
                        $doctors = User::where('user_type', 'doctor')
                            ->join('specializations','specializations.id','users.specialization')
                            ->join('users_email_verification', 'users.id', 'users_email_verification.user_id')
                            ->join('contracts', 'contracts.provider_id', '=', 'users.id')
                            ->where('contracts.status', '=', 'sent')
                            ->where('users_email_verification.status', '=', '1')
                            ->where('users.active', '=', 0)
                            ->select('users.*','users_email_verification.status as emailverify_status','contracts.status as contract_status','specializations.name as sp_name')
                            ->latest()
                            ->paginate(8);
                    } catch (\Exception $e) {
                        // Log or dump the error message for debugging
                        dd($e->getMessage());
                    }


                }
                foreach ($doctors as $doctor) {
                    $doc_percentage = DB::table('doctor_percentage')->where('doc_id', $doctor->id)->first();
                    if ($doc_percentage != null) {
                        $doctor->percentage_doctor = number_format($doc_percentage->percentage, 2) . '%';
                    } else {
                        $doctor->percentage_doctor = "not assign";
                    }
                    $session = Session::where('doctor_id', $doctor['id'])->where('status', 'ended')->orderByDesc('id')->first();
                    if ($session != null) {
                        $doctor->last_visit = Helper::get_date_with_format($session['date']);
                        // $doctor->last_diagnosis=$session['diagnosis'];
                    }
                    $doctor->state = State::find($doctor->state_id)->name;
                    $cntrcName = 'signed_contract/' . $doctor->nip_number . '_contract.pdf';
                    $doctor->contract = \App\Helper::get_files_url($cntrcName);
                }
                return view('dashboard_admin.doctors.all_doctors.pending_contract', compact('doctors'));
            } else {
                return redirect('/home');
            }
        } else {
            return redirect('/login');
        }
    }
    public function medicine_purchase(){
        $locations = PhysicalLocations::where('status',1)->get();
        $medicines = DB::table('tbl_products')->where('mode','medicine')->get();
        return view('dashboard_admin.medicine_purchase.index',compact('locations','medicines'));
    }
    public function medicine_purchase_store(Request $request){
        // dd($request->all());
        $validators = Validator::make($request->all(),[
            'location' => 'required',
        ]);
        if($validators->fails()){
            return redirect()->back()->withErrors($validators);
        } else{
            $purchase = PurchaseOrder::create([
                'location_id' => $request->location,
                'status' => 'pending',
            ]);

            foreach($request->medicine as $key => $medi){
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchase->id,
                    'medicine_id' => $medi,
                    'quantity' => $request->medicine_quantity[$key]
                ]);
            }
            return redirect()->back()->with('success','Medicines purchased successfully');
        }
    }
}
