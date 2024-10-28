<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\HandRaise;
use App\User;
use App\State;
use App\City;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class UserController extends Controller
{
    public function view_editors()
    {
        $user_type = auth()->user()->user_type;
        if ($user_type == 'admin_pharmacy') {
            $editors = User::where('user_type', 'editor_pharmacy')->get();
        } else if ($user_type == 'admin_lab') {
            $editors = User::where('user_type', 'editor_lab')->get();
        }
        return view('all_editors', compact('editors', 'user_type'));
    }
    public function revoke_role(Request $request)
    {
        $id = $request['id'];
        $user = User::find($id);
        if ($user->roles->count() > 0) {
            $user_role = $user->roles[0]['name'];
            $user->removeRole($user_role);
        }
        $user->user_type = '';
        $user->save();
        return redirect()->route('manage_users');
    }
    public function get_states(Request $request)
    {
        $states = State::where('country_id', $request['id'])->get();
        return $states;
    }
    public function get_cities(Request $request)
    {
        $cities = City::where('state_id', $request['id'])->get();
        return $cities;
    }
    public function get_cities_v2(Request $request)
    {
        $cities['all'] = City::select('id', 'name as text')->where('state_id', $request['id'])->get();
        $cities['single'] = City::select('id', 'name as text')->where('state_id', $request['id'])->first();
        $cities['state_id'] = $request['id'];
        return $cities;
    }
    public function get_states_v2(Request $request)
    {
        $states['all'] = State::select('id', 'name as text')->get();
        $states['single'] = State::select('id', 'name as text')->where('id', $request['id'])->first();
        $states['state_id'] = $request['id'];
        $states['count'] = State::select('id', 'name as text')->count();
        //dd($states['single']);
        return $states;
    }

    public function get_states_cities(Request $request)
    {
        $states = DB::table('tbl_zip_codes_cities')
        ->join('states','tbl_zip_codes_cities.state','states.name')
        ->join('cities','tbl_zip_codes_cities.city','cities.name')
        ->where('tbl_zip_codes_cities.zip_code',$request['zip'])
        ->select('tbl_zip_codes_cities.*','states.id as state_id','cities.id as city_id')
        ->first();
        // $states['all'] = State::select('id', 'name as text')->get();
        // $states['single'] = State::select('id', 'name as text')->where('id', $request['id'])->first();
        // $states['city'] = City::select('id as city_id', 'name as city_name')->where('id', $request['city_id'])->first();
        // $states['state_id'] = $request['id'];
        // $states['count'] = State::select('id', 'name as text')->count();
        //dd($states['city']);
        return $states;
    }

    public function change_password(Request $request)
    {
        // dd($request);
        $request->validate([
            'old_password' => 'required',
            'confirm_password' => 'required|same:new_password|min:8',
            'new_password' => [
                'required',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ]);
        $user = auth()->user();
        if ($request['old_password'] != $request['new_password']) {
            if (Hash::check($request['old_password'], $user->password)) {
                $user->password = Hash::make($request['new_password']);
                $user->save();
                try {
                    Mail::to($user->email)->send(new PasswordReset($user->name));
                } catch (Exception $e) {
                    Log::info($e);
                }

                return redirect()->back()->with('success', 'Password Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'The old password did not match.');
            }
        } else {
            return redirect()->back()->with('error', 'The new password cannot be same as current Password.');
        }
    }

    public function read_excel(){
        // Specify the file path
        // C:\xampp\htdocs\umbrellamd8.0\public\checking.xlsx
        $file = public_path('gimaging.xlsx');

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file);

        // Get the active worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Get the highest row and column numbers
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $data = [];

        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = [];
            for($col='A'; $col!=$highestColumn;$col++){
                $cellValue = $worksheet->getCell($col.$row)->getValue();
                if($cellValue!=null)
                {
                    array_push($rowData,$cellValue);
                }
                else{
                    array_push($rowData,"0");
                }
            }
            array_push($data,$rowData);
        }
        $services = $data[0];
        $tt = $this->getCptAndName($services);
        $cpt = $tt['cpt'];
        $name = $tt['service'];

        // unset($data[0]);
        // $data = array_values($data);
        $this->insertServices($cpt,$name);
        $this->insertPrices($cpt,$name,$data);
        dd('imagingPrices added Successfully');

    }
    public function getCptAndName($services){
        $cpt_code_array = [];
        $services_name = [];
        foreach ($services as $key => $value) {
            if($key == 0 || $key == 1){
                array_push($cpt_code_array, $value);
                array_push($services_name, $value);
            }
            else{
                array_push($cpt_code_array, explode(" - ", $value)[0]);
                array_push($services_name, explode(" - ", $value)[1]);
            }
        }
        return ["cpt" => $cpt_code_array, "service" => $services_name];

    }

    // public function insertServices($services_name){
    public function insertServices($cpt_code_array,$services_name){
        foreach ($services_name as $key => $value) {
            if($key == '0' || $key == '1'){
                continue;
            }else{
                if($cpt_code_array[$key] != 0){
                    $prod = DB::table('tbl_products')
                        ->where('cpt_code',$cpt_code_array[$key])
                        ->where('name',$value)
                        ->first();
                    if($prod == null){
                        DB::table('tbl_products')->insert([
                            'name'=>$value,
                            'slug'=>$this->slugify($value),
                            'cpt_code'=>$cpt_code_array[$key],
                            'parent_category'=>'42',
                            'sub_category'=>'0',
                            'featured_image'=>'default-imaging.png',
                            'sale_price'=>'0',
                            'regular_price'=>'0',
                            'quantity'=>'1',
                            'mode'=>'imaging',
                            'medicine_type'=>'prescribed',
                            'is_featured'=>'0',
                            'user_id'=>'69',
                            'del_req'=>'0',
                            'product_status'=>'1',
                            'is_approved'=>'1',
                            'created_at' => NOW(),
                            'updated_at' => NOW(),
                        ]);
                    }
                }
            }

        }

    }

    // print_r("if");
    // print_r("key=> ".$key);
    // print_r(" value=> ".$value[$key]);
    // print_r(" CPT=> ".$cpt_code_array[$key]);
    // print_r(" name=> ".$services_name[$key]);
    // print_r("</br>");

    // public function insertPrices($cpt_code_array,$services_name,$data){
    //     $loc_id;
    //     foreach ($data as $key2d => $array2d) {
    //         foreach ($array2d as $key => $value) {
    //             if($key == '0'){
    //                 $loc_name = explode(' ', $value);
    //                 $city = $loc_name[3];
    //                 $clinic_name = implode(" ",array_slice($loc_name, 4, count($loc_name)));
    //                 $exist = DB::table('imaging_locations')
    //                     ->where('clinic_name','like','%'.$city.'%')
    //                     ->where('city','like','%'.$clinic_name.'%')
    //                     ->first();
    //                 if($exist == null){
    //                     $loc_id = DB::table('imaging_locations')->insertGetId([
    //                         'city' => $clinic_name,
    //                         'clinic_name' => $city,
    //                         'zip_code' => $array2d[1],
    //                         'address' => $array2d[2],
    //                         'status' => 1,
    //                         'created_at' => NOW(),
    //                         'updated_at' => NOW(),
    //                     ]);
    //                 }else{
    //                     $loc_id = $exist->id;
    //                 }

    //             }else{
    //                 if($value != "0"){
    //                     if($cpt_code_array[$key] != 0){
    //                         $id = DB::table('tbl_products')
    //                             ->where('name',$services_name[$key])
    //                             ->where('cpt_code',$cpt_code_array[$key])
    //                             ->pluck('id')->first();
    //                         $check = DB::table('imaging_prices')
    //                             ->where('location_id',$loc_id)
    //                             ->where('product_id',$id)
    //                             ->first();
    //                         if($check == null){
    //                             DB::table('imaging_prices')->insert([
    //                                 'location_id' => $loc_id,
    //                                 'product_id' => $id,
    //                                 'price' => $value,
    //                                 'created_at' => NOW(),
    //                                 'updated_at' => NOW(),
    //                             ]);
    //                         }else{
    //                             if($check->price != $value){
    //                                 DB::table('imaging_prices')->where('id',$check->id)->update([
    //                                     'price' => $value,
    //                                     'updated_at' => NOW(),
    //                                 ]);
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }
    public function insertPrices($cpt_code_array,$services_name,$data){
        $loc_id;
        foreach ($data as $key2d => $array2d) {
            if($key2d == 0){
                continue;
            }else{
                foreach ($array2d as $key => $value) {
                    if($key == '0'){
                        $loc_name = explode(' ', $value);
                        $city = $loc_name[3];
                        $clinic_name = implode(" ",array_slice($loc_name, 4, count($loc_name)));
                        $exist = DB::table('imaging_locations')
                            ->where('clinic_name','like','%'.$city.'%')
                            ->where('city','like','%'.$clinic_name.'%')
                            ->first();
                        if($exist == null){
                            $loc_id = DB::table('imaging_locations')->insertGetId([
                                'city' => $clinic_name,
                                'clinic_name' => $city,
                                'zip_code' => $array2d[1],
                                'address' => $array2d[2],
                                'status' => 1,
                                'created_at' => NOW(),
                                'updated_at' => NOW(),
                            ]);
                        }else{
                            $loc_id = $exist->id;
                        }
                    }else{
                        if($value != "0"){
                            if($cpt_code_array[$key] != 0){
                                $id = DB::table('tbl_products')
                                    ->where('name',$services_name[$key])
                                    ->where('cpt_code',$cpt_code_array[$key])
                                    ->pluck('id')->first();
                                $check = DB::table('imaging_prices')
                                    ->where('location_id',$loc_id)
                                    ->where('product_id',$id)
                                    ->first();
                                if($check == null){
                                    DB::table('imaging_prices')->insert([
                                        'location_id' => $loc_id,
                                        'product_id' => $id,
                                        'price' => $value,
                                        'created_at' => NOW(),
                                        'updated_at' => NOW(),
                                    ]);
                                }else{
                                    if($check->price != $value){
                                        DB::table('imaging_prices')->where('id',$check->id)->update([
                                            'price' => $value,
                                            'updated_at' => NOW(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function sc_share()
    {
        return view('website_pages.screen_sharing.index');
    }

    public function create_sc_sh(Request $request){
        $id = DB::table('screen_sharing')->insertGetid([
            'channel'=>$request->channel,
            'password'=>$request->password,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect(url('/host/join/video/'.$id));
    }

    public function host_join_vid($id)
    {
        $ses_id = $id;
        return view('website_pages.screen_sharing.host_join',compact('ses_id'));
    }

    public function guest_join_vid($id)
    {
        $ses_id = $id;
        return view('website_pages.screen_sharing.guest_join',compact('ses_id'));
    }

    public function host_video($id,Request $request)
    {
        $session = DB::table('screen_sharing')->where('id',$request->id)->first();
        if($session->password == $request->password)
        {
            if($session->users == null)
            {
                $session = DB::table('screen_sharing')->where('id',$id)->update(['users'=>1]);
            }
            else
            {
                $session = DB::table('screen_sharing')->where('id',$id)->update(['users'=>$session->users+1]);
            }
            $session = DB::table('screen_sharing')->where('id',$id)->first();
            $session->name = $request->name;
            $session->user_type = 'host';
            return view('website_pages.screen_sharing.host_video',compact('session'));
        }
        else
        {
            return redirect()->back()->with('errors','Invalid Password');
        }
    }

    public function guest_video($id,Request $request)
    {
        $session = DB::table('screen_sharing')->where('id',$request->id)->first();
        if($session->password == $request->password)
        {
            if($session->users == null)
            {
                $session = DB::table('screen_sharing')->where('id',$id)->update(['users'=>1]);
            }
            else
            {
                $session = DB::table('screen_sharing')->where('id',$id)->update(['users'=>$session->users+1]);
            }
            $session = DB::table('screen_sharing')->where('id',$id)->first();
            $session->name = $request->name;
            $session->user_type = 'guest';
            return view('website_pages.screen_sharing.guest_video',compact('session'));
        }
        else
        {
            return redirect()->back()->with('errors','Invalid Password');
        }
    }

    public function share_sc(Request $request)
    {
        $session = DB::table('screen_sharing')->where('id',$request->session_id)->first();
        if($request->msg=='start')
        {
            DB::table('screen_sharing')->where('id',$request->session_id)->update(['sc_index'=>$session->sc_index+1]);
            event(new HandRaise($request->session_id,$session->sc_index+1));
            return "ok";
        }
        else
        {
            DB::table('screen_sharing')->where('id',$request->session_id)->update(['sc_index'=>$session->sc_index-1]);
            event(new HandRaise($request->session_id,$session->sc_index-1));
            return "ok";
        }
        return "no";
    }
    public function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
