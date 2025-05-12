<?php

namespace App\Http\Controllers;

use App\Mail\RequisitionFile;
use App\Models\ContactForm;
use App\Models\ProductsSubCategory;
use App\Pharmacy;
use App\TestConnection;
use App\User;
use App\Models\Document;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WelcomeController extends Controller
{

    private $Pharmacy;

    public function __construct(Pharmacy $Pharmacy)
    {
    //     Nexmo::message()->send([
    //     'to'   => '923461652351',
    //     'from' => '923461652351',
    //     'text' => 'That Is Testing Message By Umbrella-MD'
    // ]);
        $this->Pharmacy = $Pharmacy;
    }
    public function contact_us(Request $r)
    {
        $data = $r->all();
        $data = new ContactForm;
        $data->name = $r->name;
        $data->email = $r->email;
        $data->phone = $r->phone;
        $data->subject = $r->subject;
        $data->message = $r->message;
        $data->save();
        //  dd($data);
        return redirect('contact_us');
    }

    public function index($slug = '',Request $request)
    {
 
        if ($request->query('qrScan') === 'true') {
            $ip = $request->ip();
            $userAgent = $request->header('User-Agent');

                DB::table('qr_scans')->insert([
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        $data['imaging_category'] = $this->Pharmacy->getMainCategoryHomePage('imaging');
        // $data['labtest_category'] = $this->Pharmacy->getMainCategoryHomePage('lab-test');
        $data['prescribed_medicines_category'] = ProductsSubCategory::select('id', 'slug', 'title')
            ->where('parent_id', '38')
            ->whereNotIn('id', ['126', '88', '139', '137'])
            ->orderBy('is_featured', 'desc')
            ->limit(5)
            ->get();
        // $data['pain_categories'] = ProductsSubCategory::select('id', 'slug', 'title', 'thumbnail')
        //     ->where('parent_id', '39')
        //     ->orderBy('title', 'asc')
        //     ->limit(5)
        //     ->get();
        // $data['substance_categories'] = ProductsSubCategory::select('id', 'slug', 'title', 'thumbnail')
        //    ->where('parent_id', '18')
        //    ->orderBy('id', 'asc')
        //    ->limit(6)
        //    ->get();
        $data['psychiatrist'] = ProductsSubCategory::select('id', 'slug', 'title', 'thumbnail')
            ->where('parent_id', '44')
            ->orderBy('id', 'asc')
            ->limit(8)
            ->get();
        // $data['therapy'] = ProductsSubCategory::select('id', 'slug', 'title', 'thumbnail')
        //     ->where('parent_id', '58')
        //     ->orderBy('title', 'asc')
        //     ->limit(6)
        //     ->get();

        // Get Products
        // $data['imaging_products'] = $this->Pharmacy->getProductOrderByDesc('imaging');
        // $data['labtests_products'] = $this->Pharmacy->getProductOrderByDesc('lab-test');
        // $data['medicines_products'] = $this->Pharmacy->getProductOrderByDesc('medicine');
        // $data['substance_products'] = $this->Pharmacy->getProductOrderByDesc('substance-abuse');
        // $data['labtest-recommended'] = $this->Pharmacy->getLabtestbyCategoryID('43', 16);

        // search for pharmacy
        // $data['url_type'] = 'pharmacy';
        // $data['searchDropdown'] = $this->Pharmacy->searchDropdownSubCategory('medicine');


        $url = url()->current();
        $tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        $faqs = DB::table('tbl_faq')->orderby('id','desc')->limit(3)->get();
        $banners = DB::table('banner')->where('status',1)->orderBy('sequence','asc')->get();
        $sectionsWithContents = DB::table('section')
            ->leftJoin('content', 'section.id', '=', 'content.section_id')
            ->where('section.page_id', 1)
            ->select(
                'section.id as section_id',
                'section.section_name',
                'content.id as content_id',
                'content.content as content_content'
            )
            ->get();
        $groupedSections = [];
        foreach ($sectionsWithContents as $row) {
            $sectionName = $row->section_name;
            if (!isset($groupedSections[$sectionName])) {
                $groupedSections[$sectionName] = [
                    'id' => $row->section_id,
                    'section_name' => $row->section_name,
                    'contents' => [],
                ];
            }
            if ($row->content_id) {
                $groupedSections[$sectionName]['contents'][] = [
                    'id' => $row->content_id,
                    'content' => $row->content_content,
                ];
            }
        }
        $groupedSections = collect($groupedSections);
        foreach ($banners as $banner) {
            $banner->img=\App\Helper::check_bucket_files_url($banner->img);
        }
        return view('website_pages.new_pakistan_home', compact('data', 'slug', 'title','tags','faqs','banners','groupedSections'));
    }

    public function therapy_events_search(Request $request)
    {
        $state = DB::table('tbl_zip_codes_cities')
            ->join('states','tbl_zip_codes_cities.state','states.name')
            ->where('tbl_zip_codes_cities.zip_code',$request->zip)
            ->select('states.id')->first();
        $state = $state->id;
        $license = DB::table('doctor_licenses')
            ->where('state_id',$state)
            ->groupBy('doctor_id')
            ->select('doctor_id')
            ->get()->toArray();
        $license = json_decode(json_encode($license),true);
        $therapy_events = DB::table('therapy_session')
            ->where('status','started')
            ->orWhere('start_time','>=',date('Y-m-d H:i:s'))
            ->whereIn('doctor_id',$license)
            ->paginate(6,['*'],'search');
        foreach($therapy_events as $te)
        {
            $user = DB::table('users')->where('id',$te->doctor_id)->first();
            $te->doc_name = $user->name.' '.$user->last_name;
            $te->doc_img=\App\Helper::check_bucket_files_url($user->user_image);
            $te->short_des = DB::table('psychiatrist_info')->where('doctor_id',$te->doctor_id)->where('event_id',$te->event_id)->first();
            $te->state = DB::table('states')
                ->where('id',$te->states)
                ->select('states.name')->first();
            if($te->time_zone == 'CST'){
                $te->start_time = date('Y-m-d H:i:s',strtotime('-6 hours',strtotime($te->start_time)));
            }elseif($te->time_zone == 'EST'){
                $te->start_time = date('Y-m-d H:i:s',strtotime('-5 hours',strtotime($te->start_time)));
            }elseif($te->time_zone == 'PST'){
                $te->start_time = date('Y-m-d H:i:s',strtotime('-8 hours',strtotime($te->start_time)));
            }elseif($te->time_zone == 'MST'){
                $te->start_time = date('Y-m-d H:i:s',strtotime('-7 hours',strtotime($te->start_time)));
            }
            $te->date = date('M-d-Y',strtotime($te->start_time));
            // $d = new DateTime($te->date);
            $te->day = Carbon::parse($te->date)->format('l');
            $te->start_time = date('h:i A',strtotime($te->start_time));
            $event = DB::table('therapy_session')->where('event_id',$te->event_id)->first();
            $te->session_id = $event->id;
            // dd($te->short_des);
        }
        return view('website_pages.therapy_events',compact('therapy_events'));
    }



    public function mainSearch(Request $request)
    {
        $search = $request["value"];
        $data = DB::table('tbl_products')
            ->where('name', 'like', '%' . $search . '%')
            ->take(5)
            ->get();
        // echo $count=count($data);
        $userData["medison"] = $data;
        echo json_encode($userData);
    }

    public function setCookie(Request $request)
    {
        $time = 100;
        $response = new Response('Helllo world');
        $response->withCookie(cookie('session_is', '1', $time));
        $response->withCookie(cookie('session_out', '2', $time));
        return $response;
    }

    public function getCookie(Request $request)
    {
        print_r($request->cookie());
    }

    public function getaddress($lat, $long)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . trim($lat) . ',' . trim($long) . "&sensor=false&key=AIzaSyCRPRccs93XYIWyD-1I5ygtkzQ_ROCFafU";
        $json = file_get_contents($url);
        $data = json_decode($json);
        $status = $data->status;
        if ($status == "OK") {
            return $data->results[0]->formatted_address;
        } else {
            return false;
        }
    }

    public function terms_of_use(){
        $term_of_use=Document::where('name','term of use')->orderByDesc('id')->first();
        return view('site.term_of_use',compact('term_of_use'));
    }

    public function policy_privacy(){
        $pp = Policy::where('name','privacy policy')->orderByDesc('id')->first();
        return view('website_pages.privacy_policy',compact('pp'));
    }

    public function get_physical_location(Request $request){
        $data = DB::table('physical_locations')->where('zipcode',$request->zip)->get();
        return $data;
    }

    public function get_physical_location_by_state(Request $request){
        if($request->state != null){
            $data = DB::table('physical_locations')->where('state_id',$request->state)->get();
            $cities = DB::table('physical_locations')->where('physical_locations.state_id',$request->state)
                ->join('cities','cities.id','physical_locations.city_id')
                ->groupBy('cities.id')
                ->select('cities.*')
                ->get();
        }else{
            $data = DB::table('physical_locations')->get();
            $cities = DB::table('physical_locations')
                ->join('cities','cities.id','physical_locations.city_id')
                ->groupBy('cities.id')
                ->select('cities.*')
                ->get();
        }
        $response = ['data'=>$data, 'cities'=>$cities];
        return $response;
    }

    public function get_physical_location_by_city(Request $request){
        if($request->city != null && $request->state != null){
            $data = DB::table('physical_locations')->where('state_id',$request->state)->where('city_id',$request->city)->get();
        }elseif($request->city != null && $request->state == null){
            $data = DB::table('physical_locations')->where('city_id',$request->city)->get();
        }else{
            $data = DB::table('physical_locations')->get();
        }
        return $data;
    }

    public function get_physical_location_by_id(Request $request){
        $data = DB::table('physical_locations')->where('id',$request->id)->first();
        $data->services = json_decode($data->services);
        return $data;
    }

    public function landing_page($slug = ''){
        $url = url()->current();
        $tags = DB::table('meta_tags')->where('url',$url)->get();
        $title = DB::table('meta_tags')->where('url',$url)->where('name','title')->first();
        $faqs = DB::table('tbl_faq')->orderBy('id','desc')->limit(5)->get();
        return view('website_pages.landing', compact('slug','faqs','title','tags'));
    }
}
