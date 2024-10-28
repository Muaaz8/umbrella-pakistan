<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Events\LoadPrescribeItemList;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateAllProductsRequest;
use App\Http\Requests\UpdateAllProductsRequest;
use App\ImagingLocations;
use App\ImagingPrices;
use App\Models\AllProducts;
use App\Models\ProductCategory;
use App\Prescription;
use App\QuestDataAOE;
use App\QuestDataTestCode;
use App\Repositories\AllProductsRepository;
use App\User;
use Auth;
use App\Referal;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Image;
use PDF;

class AllProductsController extends AppBaseController
{
    /** @var  AllProductsRepository */
    private $allProductsRepository;

    public function __construct(AllProductsRepository $allProductsRepo)
    {
        $this->allProductsRepository = $allProductsRepo;
    }
    public function show_product_on_checkout(Request $request)
    {
        $item_id = $request->item_id;
        $result = DB::table('tbl_cart')->where('id', $item_id)->update(['show_product' => '1']);
        $countItem = 0;
        $itemSum = 0;
        $providerFee = 0;
        if ($result) {

            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {

                if ($item->item_type == 'prescribed') {
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                }
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
                    $providerFee = 6;
                }
                if ($item->show_product == 1) {
                    $countItem += 1;
                    $itemSum += $item->update_price;
                }
                if ($item->doc_session_id != '0') {
                    $doctorDetails = User::find($item->doc_id);
                    $item->prescribed_by = 'Dr.' . $doctorDetails->name . ' ' . $doctorDetails->last_name;
                } else {
                    $item->prescribed_by = '';
                }
            }

            $totalPrice = $itemSum + $providerFee;
        }

        // $count = 0;
        // $providerFee = 0;
        // $countItems = DB::table('tbl_cart')->where('show_product', '1')->where('user_id', Auth::user()->id)->get();
        // foreach ($countItems as $countItem) {
        //     $count += 1;
        //     if ($countItem->product_mode == 'lab-test' && $countItem->item_type == "counter") {
        //         $providerFee = 6;
        //     }
        // }

        // $itemSum = DB::table('tbl_cart')->where('show_product', '1')->where('user_id', Auth::user()->id)->sum('update_price');
        // $totalPrice = $itemSum;

        $res = ['countItem' => $countItem, 'itemSum' => number_format($itemSum, 2), 'totalPrice' => number_format($totalPrice, 2), 'providerFee' => number_format($providerFee, 2)];
        return $res;
    }
    public function remove_item_from_cart($id)
    {
        DB::table('tbl_cart')->where('id', $id)->delete();
        return redirect()->back();
    }
    public function show_product_on_final_checkout(Request $request)
    {
        $count = 0;
        $providerFee = 0;
        $allProducts = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->get();
        $itemSum = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->sum('update_price');
        $totalPrice = $itemSum;
        foreach ($allProducts as $allProduct) {
            $count += 1;
            if ($allProduct->product_mode == 'lab-test' && $allProduct->item_type == "counter") {
                $providerFee = 6;
            }
            $item_type = $allProduct->item_type;
            if ($item_type == 'prescribed') {
                $doctor = User::find($allProduct->doc_id);
                $allProduct->prescribed = $doctor->name . ' ' . $doctor->last_name;
            }
            // dd($allProduct->coupon_code_id);
            if($allProduct->coupon_code_id != ""){
                $percentage = DB::table('coupon_code')
                ->where('id',$allProduct->coupon_code_id)
                ->where('status','1')->select('discount_percentage')->first();
                $percentage = $percentage->discount_percentage;
                $new_price = (int)$allProduct->price - ((int)$allProduct->price*((int)$percentage/100));
                $discount_item = DB::table('tbl_cart')
                ->where('id',$allProduct->id)
                ->update([
                    'update_price' => $new_price,
                ]);
                $allProduct->update_price = $new_price;
                // $itemSum = $itemSum - ((int)$allProduct->price*((int)$percentage/100));
                // $totalPrice = $totalPrice - ((int)$allProduct->price*((int)$percentage/100));
                // $totalPrice -= $new_price;
            }
        }
        $itemSum = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->sum('update_price');
        $totalPrice = $itemSum;
        $res = ['countItem' => $count, 'itemSum' => number_format($itemSum, 2), 'totalPrice' => number_format($totalPrice + $providerFee, 2), 'allProducts' => $allProducts, 'providerFee' => number_format($providerFee, 2)];
        return $res;
    }
    public function remove_product_on_checkout(Request $request)
    {
        $item_id = $request->item_id;
        $result = DB::table('tbl_cart')->where('id', $item_id)->update(['show_product' => '0']);
        $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
        $countItem = 0;
        $itemSum = 0;
        $providerFee = 0;
        if ($result) {

            $user_cart_items = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->get();
            foreach ($user_cart_items as $item) {
                $temp = 0;
                if ($item->item_type == 'prescribed' && $item->product_mode == 'pharmacy') {
                    $temp = 1;
                }
                if ($item->item_type == 'prescribed') {
                    $pres = DB::table('prescriptions')->where('id', $item->pres_id)->first();
                    $item->prescription_date = $pres->created_at;
                    $item->medicine_usage = $pres->usage;
                }
                if ($item->item_type == 'counter' && $item->product_mode == 'lab-test' && $item->show_product == '1') {
                    $providerFee = 6;
                }
                if ($item->show_product == 1) {
                    $countItem += 1;
                    $itemSum += $item->update_price;
                }
                if ($item->doc_session_id != '0') {
                    $doctorDetails = User::find($item->doc_id);
                    $item->prescribed_by = 'Dr.' . $doctorDetails->name . ' ' . $doctorDetails->last_name;
                } else {
                    $item->prescribed_by = '';
                }
            }

            $totalPrice = $itemSum + $providerFee;
        }

        $res = ['countItem' => $countItem, 'itemSum' => number_format($itemSum, 2), 'totalPrice' => number_format($totalPrice, 2), 'providerFee' => number_format($providerFee, 2), 'temp'=>$temp];
        return $res;
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        $type = $this->getBasicTypes();
        $allProductsData = $this->converToObjToArray($this->allProductsRepository->getProductsData($type));
        $allProducts = [];
        foreach ($allProductsData as $item) {
            $cat_names = [];
            $arrayCatIDs = explode(",", $item['parent_category']);
            foreach ($arrayCatIDs as  $arrayCatID) {
                $cat = ProductCategory::where('id', $arrayCatID)->select('name')->first()->toArray();
                array_push($cat_names, $cat);
            }
            $item['cat_names'] = $cat_names;
            $allProducts[] = $item;
        }
        return view('all_products.index')->with(['allProducts' => $this->converToArrayToObj($allProducts), 'user' => $user]);
    }

    public function imagingServices()
    {
        $services =  DB::table('tbl_products')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->select(
                'tbl_products.id',
                'tbl_products.name as product_name',
                'tbl_products.cpt_code',
                'product_categories.name as product_category',
            )
            ->where('tbl_products.mode', 'imaging')
            ->get();
        return view('all_products.imaging_service_tbl')->with(['services' => $services]);
    }

    public function imagingAllData()
    {
        return view('all_products.imaging_service_tbl_all');
    }

    public function getImagingAllData()
    {
        $services = DB::table('imaging_prices')
            ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_prices.location_id')
            ->join('tbl_products', 'tbl_products.id', '=', 'imaging_prices.product_id')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->select(
                'imaging_prices.id AS id',
                'imaging_prices.id as price_id',
                'product_categories.name AS product_category',
                'tbl_products.name as product_name',
                'tbl_products.cpt_code',
                'imaging_prices.price AS price',
                'imaging_locations.clinic_name AS state',
                'imaging_locations.city',
                'imaging_locations.zip_code',
                'imaging_locations.lat',
                'imaging_locations.long',
                'imaging_locations.address',
                DB::raw("CONCAT(`city`, ' ', `clinic_name`, '(', `zip_code`, ')') AS `location_name`")
            )->where('tbl_products.mode', 'imaging')
            ->orderby('imaging_prices.id','DESC')
            ->get();

        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($services),
            "totaldisplayrecords" => count($services),
            "data" => $services,
        );

        return json_encode($dataset);
    }

    public function imagingServicesDelete(Request $request)
    {
        $user = auth()->user();
        $getMode = AllProducts::where('id', $request['id'])->get()->toArray();
        $name = $getMode[0]['name'];
        $mode = $getMode[0]['mode'];
        AllProducts::find($request['id'])->forcedelete();
        $forLogMsg = 'deleteProduct|userID:' . $user->id . '|productID:' . $request['id'] . '|mode:' . $mode . '|productName:' . $name;
        Log::channel('allProducts')->info($forLogMsg);
        return redirect()->back();
    }

    public function imagingPrices()
    {
        $services = DB::table('imaging_prices')
            ->join('imaging_locations', 'imaging_prices.location_id', '=', 'imaging_locations.id')
            ->join('tbl_products', 'imaging_prices.product_id', '=', 'tbl_products.id')
            ->select(
                'imaging_prices.id',
                'imaging_prices.price',
                'imaging_prices.actual_price',
                'imaging_locations.city',
                'imaging_locations.zip_code',
                'imaging_locations.clinic_name',
                'tbl_products.name as service',
                'tbl_products.cpt_code as codes'
            )
            ->paginate(10);
        return view('all_products.imaging_price_tbl',compact('services'));
    }

    public function imagingLocations()
    {
        $services = ImagingLocations::all();
        return view('all_products.imaging_location_tbl')->with(['services' => $services]);
    }

    public function imagingLocationsDelete(Request $request)
    {
        $user = auth()->user();
        $getDetails = ImagingLocations::where('id', $request['id'])->get()->toArray();
        $state = $getDetails[0]['clinic_name'];
        $city = $getDetails[0]['city'];
        $zipCode = $getDetails[0]['zip_code'];
        $forLogMsg = 'deleteLocation|userID:' . $user->id . '|locationID:' . $request['id'] . '|state:' . $state . '|city:' . $city . '|zipCode:' . $zipCode;
        Log::channel('imagingLocations')->info($forLogMsg);
        ImagingLocations::find($request['id'])->forcedelete();
        return redirect()->back();
    }

    public function imagingPricesDelete(Request $request)
    {
        $user = auth()->user();
        $getDetails = ImagingPrices::where('id', $request['id'])->get()->toArray();
        $location_id = $getDetails[0]['location_id'];
        $product_id = $getDetails[0]['product_id'];
        $price = $getDetails[0]['price'];
        $forLogMsg = 'deletePrice|userID:' . $user->id . '|priceID:' . $request['id'] . '|locationID:' . $location_id . '|productID:' . $product_id . '|price:' . $price;
        Log::channel('imagingPrices')->info($forLogMsg);
        ImagingPrices::find($request['id'])->forcedelete();
        return redirect()->back();
    }

    public function create()
    {
        $type = $this->getBasicTypes();
        $categories = $this->allProductsRepository->getMainCategories($type);
        $user = auth()->user();
        $locations = ImagingLocations::all();
        $imaging_products = AllProducts::where('mode', 'imaging')->get();
        //print_r($categories);
        return view('all_products.create')->with(['categories' => $categories, 'user' => $user, 'imaging_products' => $imaging_products, 'locations' => $locations]);
    }

    public function checkProductNameIsArray($val)
    {
        $req_value = "";
        if (is_array($val)) {
            $req_value = $this->allProductsRepository->getTestNameAndPrice($val);
        } else {
            $req_value = $val;
        }

        return $req_value;
    }

    public function getFAQTest($val)
    {

        $faq_value = "";

        if (is_array($val)) {
            $faq_value = $this->allProductsRepository->getFAQByID($val);
        } else {
            $faq_value = $val;
        }

        return $faq_value;
    }

    public function store(CreateAllProductsRequest $request)
    {

        $input = $request->all();
        // dd($input);
        // die;
        $user = auth()->user();
        //  dd($user->user_type);
        if (isset($input['imaging_location'])) {
            $insert = $this->imaging_location($input);
            Flash::success('Location saved successfully.');
            return redirect('/imaging_locations');
        }

        if (isset($input['imaging_pricing'])) {
            $insert = $this->addImagingData($input);
            Flash::success('Prices saved successfully.');
            return redirect('/imaging_prices');
        }

        // Check Image File
        $file = $request->file('featured_image');

        $type_of_image = $input['type_of_image'];

        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $img_name = uniqid() . '.' . $extension;
            $path = 'uploads/' . $img_name;
            $img = Image::make($file);
            $img->save(public_path($path));
            $input = $request->all();
            $input['featured_image'] = $img_name;
        } else {
            $input['featured_image'] = 'no_image.png';
            $input = $request->all();
        }

        if ($type_of_image === 'lab_test') {
            $input['featured_image'] = 'default-labtest.jpg';
        } else if ($type_of_image === 'panel_test') {
            $input['featured_image'] = 'default-labtest.jpg';
        } elseif ($type_of_image === 'imaging') {
            $input['featured_image'] = 'default-imaging.png';
        }

        //   $parent_name =  $this->allProductsRepository->getParentCatName($input['sub_category']);

        // dd($input['including_test']);die;
        if (isset($input['including_test'])) {
            $including_test = $this->checkProductNameIsArray($input['including_test']);
            $input['slug'] = is_array($input['including_test']) ? $this->slugify($input['panel_name']) : $this->slugify($input['name']);
            $input['sub_category'] = 0;
            $input['name'] = $input['panel_name'];
            $input['including_test'] = $including_test;
        } else if (isset($input['no_test']) && $input['no_test'] == 'on') {
            $input['slug'] = $this->slugify($input['panel_name']);
            $input['sub_category'] = 0;
            $input['name'] = $input['panel_name'];
            $arr = [];
            $input['including_test'] = json_encode($arr);
        } else {
            $input['slug'] = $this->slugify($input['name']);
            $input['sub_category'] = 0;
            $input['including_test'] = '';
        }

        if (is_array($input['parent_category'])) /* for imaging 909 */ {
            $input['parent_category'] = implode(",", $input['parent_category']);
        } else {
            $input['parent_category'] = $input['parent_category'];
        }

        if (isset($input['is_featured'])) {
            $input['is_featured'] = 1;
        } else {
            $input['is_featured'] = 0;
        }

        // Set User ID while Creating
        $input['user_id'] = $user->id;
        //  dd($input);

        $allProducts = $this->allProductsRepository->create($input);

        ActivityLog::add_activity('added product ' . $input['name'], $allProducts['id'], 'product_created');

        // Log
        $forLogMsg = 'addProduct|userID:' . $user->id . '|productID:' . $allProducts['id'] . '|mode:' . $input['mode'] . '|productName:' . $input['name'];
        Log::channel('allProducts')->info($forLogMsg);
        // Log

        Flash::success('Product saved successfully.');

        if ($user->user_type == 'editor_imaging') {
            return redirect('imaging_services');
        } else {
            return redirect(route('allProducts.index'));
        }
    }

    public function imaging_location($input)
    {
        $user = auth()->user();
        $getCordinate = $this->getCoordinates($input['zip_code']);
        $res = ImagingLocations::Create([
            'city' => $input['city'],
            'zip_code' => $input['zip_code'],
            'clinic_name' => $input['clinic_name'],
            'lat' => $getCordinate['lat'],
            'long' => $getCordinate['lng'],
            'address' => $this->getAddress($getCordinate['lat'], $getCordinate['lng']),
            'created_by' => $user->id,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        $forLogMsg = 'addLocation|userID:' . $user->id . '|locationID:' . $res['id'] . '|state:' . $input['clinic_name'] . '|city:' . $input['city'] . '|zipCode:' . $input['zip_code'];
        Log::channel('imagingLocations')->info($forLogMsg);
        return $res;
    }

    public function imaging_location_update($id, $input)
    {
        $user = auth()->user();
        $getCordinate = $this->getCoordinates($input['zip_code']);
        $input['lat'] = $getCordinate['lat'];
        $input['long'] = $getCordinate['lng'];
        $input['address'] = $this->getAddress($getCordinate['lat'], $getCordinate['lng']);
        unset($input['_method']);
        unset($input['_token']);
        unset($input['imaging_location']);
        $input['created_by'] = $user->id;
        $input['updated_at'] = NOW();

        $forLogMsg = 'editLocation|userID:' . $user->id . '|locationID:' . $id . '|state:' . $input['clinic_name'] . '|city:' . $input['city'] . '|zipCode:' . $input['zip_code'];
        Log::channel('imagingLocations')->info($forLogMsg);

        $res = ImagingLocations::where('id', $id)->update($input);
        return $res;
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

    public function addImagingData($input)
    {
        $user = auth()->user();
        $price = ImagingPrices::Create([
            'location_id' => $input['city'],
            'product_id' => $input['name'],
            'price' => $input['price'],
            'created_by' => $user->id,
            'updated_at' => NOW(),
        ]);

        $forLogMsg = 'addPrice|userID:' . $user->id . '|priceID:' . $price['id'] . '|locationID:' . $input['city'] . '|productID:' . $input['name'] . '|price:' . $input['price'];
        Log::channel('imagingPrices')->info($forLogMsg);
    }

    public function updateImagingPricing($id, $input)
    {
        $user = auth()->user();
        $price = ImagingPrices::where('id', $id)->update([
            'location_id' => $input['city'],
            'product_id' => $input['name'],
            'price' => $input['price'],
            'created_by' => $user->id,
            'updated_at' => NOW(),
        ]);

        $forLogMsg = 'updatePrice|userID:' . $user->id . '|priceID:' . $id . '|locationID:' . $input['city'] . '|productID:' . $input['name'] . '|price:' . $input['price'];
        Log::channel('imagingPrices')->info($forLogMsg);
    }

    public function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public function show($id)
    {
        $allProducts = $this->allProductsRepository->find($id);

        // $allProducts = $this->allProductsRepository->getParentChildName($id);

        if (empty($allProducts)) {
            Flash::error('All Products not found');

            return redirect(route('allProducts.index'));
        }

        return view('all_products.show')->with('allProducts', $allProducts);
    }

    public function edit($id)
    {
        if (isset($_GET) && $_GET['form_type'] == 'imaging_location') {
            $data = ImagingLocations::where('id', $id)->first();
            return view('all_products.edit')->with(['allProducts' => $data]);
        }

        // die;
        $allProducts = $this->allProductsRepository->find($id);
        $type = $this->getBasicTypes();
        $categories = $this->allProductsRepository->getMainCategories($type);
        $user = auth()->user();
        $locations = ImagingLocations::all();
        $imaging_products = AllProducts::where('mode', 'imaging')->get();

        if ($type['type'] != 'imaging') /* For Imaging */ {
            // Get Category Names
            $json_parent_category_names = $this->allProductsRepository->getParentCategoryNames($allProducts->parent_category, $type);
            $prefield_values = $this->getValuesForEditPreFields($allProducts, $json_parent_category_names);
        } else {
            $prefield_values = [];
        }

        //   dd($prefield_values);

        if (isset($_GET) && $_GET['form_type'] == 'imaging') {
            $allProducts = ImagingPrices::leftjoin('tbl_products', 'imaging_prices.product_id', 'tbl_products.id')->leftjoin('imaging_locations', 'imaging_prices.location_id', 'imaging_locations.id')
                ->select(
                    'imaging_prices.id as id',
                    'tbl_products.name as product_name',
                    'imaging_prices.product_id',
                    'imaging_prices.location_id',
                    DB::raw("CONCAT(`city`, ' ', `clinic_name`, '(', `zip_code`, ')') AS `location_name`"),
                    'imaging_prices.price',
                )
                ->where('imaging_prices.id', $id)
                ->first();
        }

        //dd($allProducts->location_name);
        return view('all_products.edit')->with([
            'allProducts' => $allProducts,
            'imaging_products' => $imaging_products, 'locations' => $locations,
            'categories' => $categories, 'prefield' => $prefield_values, 'user' => $user,
        ]);
    }

    public function getValuesForEditPreFields($product_data, $json_parent_category_names)
    {

        // For Panels
        $panelTests = json_decode($product_data->including_test);
        $cats = json_decode($json_parent_category_names);

        if (!empty($panelTests)) {
            $panel_names = [];

            foreach ($panelTests as $key => $item) {
                array_push($panel_names, $item->test_name);
            }
            $data['panel_names'] = $panel_names;
        } else {
            $data['panel_names'] = [];
        }

        if (!empty($json_parent_category_names)) {
            $cat_names = [];

            foreach ($cats as $key => $item) {
                array_push($cat_names, $item);
            }
            $data['category_name'] = $cat_names;
        } else {
            $data['category_name'] = [];
        }

        return $data;
    }

    public function update($id, UpdateAllProductsRequest $request)
    {

        $user = auth()->user();
        $allProducts = $this->allProductsRepository->find($id);
        $file = $request->file('featured_image');
        $input = $request->all();

        if (isset($input['imaging_location'])) {
            $update = $this->imaging_location_update($id, $input);
            Flash::success('Location update successfully.');
            return redirect('/imaging_locations');
        }

        if (isset($input['imaging_pricing'])) {
            $update = $this->updateImagingPricing($id, $input);
            Flash::success('Record Update Successfully.');
            return redirect('/imaging_prices');
        }
        // die;
        $type_of_image = $input['type_of_image'];

        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $img_name = uniqid() . '.' . $extension;
            $path = 'uploads/' . $img_name;
            $img = Image::make($file);
            $img->save(public_path($path));
            $input = $request->all();
            $input['featured_image'] = $img_name;
        } else {
            $input['featured_image'] = 'no_image.png';
            $input = $request->all();
        }

        if ($type_of_image === 'lab_test') {
            $input['featured_image'] = 'default-labtest.jpg';
        } else if ($type_of_image === 'panel_test') {
            $input['featured_image'] = 'default-labtest.jpg';
        } elseif ($type_of_image === 'imaging') {
            $input['featured_image'] = 'default-imaging.png';
        }

        if (is_array($input['parent_category'])) /* for imaging 909 */ {
            $input['parent_category'] = implode(",", $input['parent_category']);
        }

        if (isset($input['including_test'])) {
            $including_test = $this->checkProductNameIsArray($input['including_test']);
            $input['slug'] = is_array($input['including_test']) ? $this->slugify($input['panel_name']) : $this->slugify($input['name']);
            $input['name'] = $input['panel_name'];
            $input['including_test'] = $including_test;
            $input['sub_category'] = 0;
        } else if (isset($input['no_test']) && $input['no_test'] == 'on') {
            $input['slug'] = $this->slugify($input['panel_name']);
            $input['sub_category'] = 0;
            $input['name'] = $input['panel_name'];
            $arr = [];
            $input['including_test'] = json_encode($arr);
        } else {
            $input['slug'] = $this->slugify($input['name']);
            $input['including_test'] = '';
            $input['sub_category'] = 0;
        }

        if (isset($input['is_featured'])) {
            $input['is_featured'] = 1;
        } else {
            $input['is_featured'] = 0;
        }
        //dd($input);

        // Set User ID while Creating
        $input['user_id'] = $user->id;

        $allProducts = $this->allProductsRepository->update($input, $id);

        // Log
        $forLogMsg = 'editProduct|userID:' . $user->id . '|productID:' . $id . '|mode:' . $input['mode'] . '|productName:' . $input['name'];
        Log::channel('allProducts')->info($forLogMsg);
        // Log

        Flash::warning('Product update successfully.');

        if ($user->user_type == 'editor_imaging') {
            return redirect('imaging_services');
        } else {
            return redirect(route('allProducts.index'));
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $allProducts = $this->allProductsRepository->find($id);
        if (empty($allProducts)) {
            Flash::error('All Products not found');

            return redirect(route('allProducts.index'));
        }
        $getMode = AllProducts::where('id', $id)->get()->toArray();
        $name = $getMode[0]['name'];
        $mode = $getMode[0]['mode'];

        $this->allProductsRepository->delete($id);

        $forLogMsg = 'deleteProduct|userID:' . $user->id . '|productID:' . $id . '|mode:' . $mode . '|productName:' . $name;
        Log::channel('allProducts')->info($forLogMsg);

        Flash::success('All Products request sent to Admin.');

        return redirect(route('allProducts.index'));
    }
    public function add_imging_pro(Request $request)
    {
        $dre = DB::table('imaging_selected_location')->insert([
            'session_id' => $request->session_id,
            'product_id' => $request->id,
            'imaging_location_id' => $request->location_id,
        ]);
        $pres = DB::table('prescriptions')->where('session_id',$request->session_id)->where('imaging_id',$request->id)->get();
        // dd(count($pres));
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => $request['session_id'],
                'imaging_id' => $request['id'],
                'type' => 'imaging',
                'quantity' => 1,
                'created_at' => Carbon::now(),
            ]);

            event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
        }
    }

    public function get_product_details(Request $request)
    {
        $id = $request['id'];
        // dd($request);
        if ($request['type'] == 'img') {
            $pres = DB::table('prescriptions')->where('session_id',$request['session_id'])->where('imaging_id',$id)->get();
            // dd(count($pres));
            if(count($pres)==0){
                Prescription::insert([
                    'session_id' => $request['session_id'],
                    'imaging_id' => $id,
                    'type' => 'imaging',
                    'quantity' => 1,
                    'created_at' => Carbon::now(),
                ]);
                event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
            }
        } else if ($request['type'] == 'med') {
            $pres = DB::table('prescriptions')->where('session_id',$request['session_id'])->where('medicine_id',$id)->get();
            if(count($pres)==0){
                Prescription::insert([
                    'session_id' => $request['session_id'],
                    'medicine_id' => $id,
                    'type' => 'medicine',
                    'quantity' => 1,
                    'created_at' => Carbon::now(),
                ]);
                event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
            }
        }

        return "ok";
    }
    public function get_lab_details(Request $request)
    {
        // dd($request);
        $pres = DB::table('prescriptions')->where('session_id',$request->session_id)->where('test_id',$request->id)->get();
        if(count($pres)==0){
            Prescription::insert([
                'session_id' => $request['session_id'],
                'test_id' => $request['id'],
                'type' => 'lab-test',
                'quantity' => 1,
                'created_at' => Carbon::now(),
            ]);
            event(new LoadPrescribeItemList($request['session_id'], $request['user_id']));
        }
    return "ok";
    }
    public function get_prescribe_item_list(Request $request)
    {
        $items = [];
        $pro_lists = Prescription::where('session_id', $request['session_id'])->get();

        foreach ($pro_lists as $pro_list) {
            if ($pro_list->type == "lab-test") {

                $labData = \App\QuestDataTestCode::where('TEST_CD', $pro_list->test_id)->first();

                $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
                    ->where('TEST_CD', $pro_list->test_id)
                    ->groupBy('AOE_QUESTION_DESC')
                    ->get();
                $count = count($getTestAOE);
                if ($count > 0) {
                    $labData->aoes = 1;
                } else {
                    $labData->aoes = 0;
                }
                $items[] = $labData;
            } else if ($pro_list->type == "imaging") {
                $data = $this->allProductsRepository->find($pro_list->imaging_id);
                // dd($data->id);
                $res = DB::table('imaging_selected_location')->where('session_id', $request['session_id'])->where('product_id', $data->id)->first();
                if ($res != null) {
                    // dd($res);
                    $get = DB::table('imaging_locations')->where('imaging_locations.id', $res->imaging_location_id)->first();
                    $data->location = $get->clinic_name . ', ' . $get->city . ', ' . $get->zip_code;
                } else {
                    $data->location = 'nothing';
                }

                $items[] = $data;
            } else if ($pro_list->type == "medicine") {
                if ($pro_list->usage != null) {
                    $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                    $getRes->usage = $pro_list->usage;
                    $items[] = $getRes;
                } else {
                    $getRes = $this->allProductsRepository->find($pro_list->medicine_id);
                    $getRes->usage = "";
                    $items[] = $getRes;
                }
            }
        }
        $product = collect($items);
        return $product;
    }
    public function deletePrescribeItemFromSession(Request $request)
    {
        // dd($request);
        if ($request['type'] == "lab-test") {
            Prescription::where('session_id', $request['session_id'])->where('test_id', $request['pro_id'])->delete();
            // return redirect(url()->previous());
        } else if ($request['type'] == "imaging") {
            Prescription::where('session_id', $request['session_id'])->where('imaging_id', $request['pro_id'])->delete();
            // return redirect(url()->previous());
        } else if ($request['type'] == "medicine") {
            Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['pro_id'])->delete();
            // return redirect(url()->previous());
        }
        event(new LoadPrescribeItemList($request->session_id, $request->user_id));
    }

    public function deletePrescribeItemFromRecom($id)
    {
        Prescription::where('id', $id)->delete();
        // dd($request);
        // if ($request['type'] == "lab-test") {

        //     // return redirect(url()->previous());
        // } else if ($request['type'] == "imaging") {
        //     Prescription::where('session_id', $request['session_id'])->where('imaging_id', $request['pro_id'])->delete();
        //     // return redirect(url()->previous());
        // } else if ($request['type'] == "medicine") {
        //     Prescription::where('session_id', $request['session_id'])->where('medicine_id', $request['pro_id'])->delete();
        //     // return redirect(url()->previous());
        // }
        //event(new LoadPrescribeItemList($request->session_id, $request->user_id));
        return redirect(url()->previous());
    }

    public function getBasicTypes()
    {
        $type = "";
        $panel = "";

        if (Auth::user()->user_type === 'editor_lab') {
            $type = "lab-test";
            $panel = "1";
        } else if (Auth::user()->user_type === 'editor_pharmacy') {
            $type = "medicine";
            $panel = "0";
        } else if (Auth::user()->user_type === 'editor_imaging') {
            $type = "imaging";
            $panel = "0";
        } else if (Auth::user()->user_type === 'admin_pharmacy') {
            $type = "medicine";
            $panel = "0";
        } else if (Auth::user()->user_type === 'admin_pharmacy') {
            $type = "medicine";
            $panel = "0";
        } else if (Auth::user()->user_type === 'admin') {
            $type = "medicine";
            $panel = "0";
        }

        $data = array(
            'type' => $type,
            'panel-test' => $panel,
        );

        return $data;
    }

    public function get_products_name()
    {
        $keyword = $_REQUEST['term'];

        $data = $this->allProductsRepository->getProductNameID($keyword);

        echo json_encode($data);
    }

    public function get_faq_name()
    {
        $keyword = $_REQUEST['term'];

        $data = $this->allProductsRepository->getFAQNameID($keyword);

        echo json_encode($data);
    }

    public function get_parent_category_names()
    {
        $keyword = $_REQUEST['term'];

        $data = $this->allProductsRepository->getParentCategoryNameID($keyword);

        echo json_encode($data);
    }

    public function all_prod_del_req()
    {
        // $prods=AllProducts::where('del_req','1')->get();
        $type = $this->getBasicTypes();

        $prods = $this->allProductsRepository->getDeletedProductsData($type);
        $new_added_products = $this->allProductsRepository->getNewAddedProduct();
        //dd($new_added_products);
        return view('superadmin.all_prod_del_req', compact('prods', 'new_added_products'));
    }

    public function dash_all_prod_del_req()
    {
        $type = $this->getBasicTypes();

        $prods = $this->allProductsRepository->getDeletedProductsData($type);
        $new_added_products = $this->allProductsRepository->getNewAddedProduct();
        return view('dashboard_admin.Delete_Request.product', compact('prods', 'new_added_products'));
    }

    public function get_products_by_category(Request $request)
    {
        if ($request->type == 'imaging') {
            $products = AllProducts::where('parent_category', $request->id)->get();

            foreach ($products as $product) {
                $res = DB::table('prescriptions')->where('imaging_id', $product->id)->where('session_id', $request->session_id)->first();
                if ($res != null) {
                    $product->added = 'yes';
                } else {
                    $product->added = 'no';
                }
            }
            return $products;
        } else if ($request->type == 'medicine') {
            $products = DB::table('tbl_products')->where('sub_category', $request->id)->get();

            foreach ($products as $product) {
                $res = DB::table('prescriptions')->where('medicine_id', $product->id)->where('session_id', $request->session_id)->first();
                if ($res != null) {
                    $product->added = 'yes';
                } else {
                    $product->added = 'no';
                }
            }
            return $products;
        }
    }
    public function new_get_products_by_category(Request $request)
    {
        // dd($request->med_id,$request->name);
        if($request->name=='')
        {
            $products = DB::table('tbl_products')
            ->where('sub_category', $request->med_id)
            ->where('product_status', 1)
            ->where('is_approved', 1)
            ->get();
        }
        else
        {
            $products = DB::table('tbl_products')
            ->where('sub_category', $request->med_id)
            ->where('name','LIKE', "%{$request->name}%")
            ->where('product_status', 1)
            ->where('is_approved', 1)
            ->get();
        }

        foreach ($products as $product) {
            $res = DB::table('prescriptions')->where('medicine_id', $product->id)->where('session_id', $request->session_id)->first();
            if ($res != null) {
                $product->added = 'yes';
            } else {
                $product->added = 'no';
            }
        }
        return $products;
    }

    public function get_med_filtered_category(Request $request)
    {
        $med_cat = DB::table('products_sub_categories')->where('parent_id', '38')
        ->join('tbl_products','products_sub_categories.id','tbl_products.sub_category')
        ->select('products_sub_categories.*')
        ->where('title', 'LIKE', "%{$request->title}%")
        ->groupBy('tbl_products.sub_category')
        ->get();
        // $med_cat = DB::table('products_sub_categories')->where('parent_id', '38')->where('title', 'LIKE', "%{$request->title}%")->get();
        return $med_cat;
    }

    public function new_get_imaging_products_by_category(Request $request)
    {
        if ($request->name == '') {
            $products = DB::table('tbl_products')
                ->join('imaging_prices', 'imaging_prices.product_id', 'tbl_products.id')
                ->where('imaging_prices.location_id', $request->location_id)
                ->where('tbl_products.parent_category', $request->cat_id)
                ->where('imaging_prices.price','!=','0')
                ->select('tbl_products.id as pro_id', 'tbl_products.name as pro_name', 'imaging_prices.location_id')
                ->get();
        } else {
            $products = DB::table('tbl_products')
                ->join('imaging_prices', 'imaging_prices.product_id', 'tbl_products.id')
                ->where('tbl_products.name', 'LIKE', "%{$request->name}%")
                ->where('imaging_prices.location_id', $request->location_id)
                ->where('tbl_products.parent_category', $request->cat_id)
                ->where('imaging_prices.price','!=','0')
                ->select('tbl_products.id as pro_id', 'tbl_products.name as pro_name', 'imaging_prices.location_id')
                ->get();
        }
        if (count($products) > 0) {
            foreach ($products as $product) {
                $res = DB::table('prescriptions')->where('imaging_id', $product->pro_id)->where('session_id', $request->session_id)->first();
                if ($res != null) {
                    $product->added = 'yes';
                } else {
                    $product->added = 'no';
                }
            }
            return $products;
        } else {
            return "notfound";
        }
    }
    public function new_get_lab_products_video_page(Request $request)
    {
        if ($request->name == '') {
            $labs = QuestDataTestCode::whereRaw("TEST_CD NOT LIKE '#%%' ESCAPE '#'")
                ->whereIn('id', [
                    '3327', '4029', '1535', '3787', '47', '1412',
                    '1484', '1794', '3194', '3352', '3566', '3769',
                    '4446', '18811', '11363', '899', '16846', '3542',
                    '229', '747', '6399', '7573', '16814',
                ])
                ->where('TEST_CD', '!=', '92613')
                ->where('TEST_CD', '!=', '11196')
                ->where('LEGAL_ENTITY', 'DAL')
                ->where('TEST_NAME','!=', null)
                ->orWhere('PRICE', '!=', '')
                ->get();
        } else {
            $labs = QuestDataTestCode::where('TEST_NAME', 'LIKE', "%{$request->name}%")
                ->where('LEGAL_ENTITY', 'DAL')
                ->where('PRICE', '!=', null)
                ->get();
        }

        foreach ($labs as $lab) {

            $res = DB::table('prescriptions')->where('test_id', $lab->TEST_CD)->where('session_id', $request->id)->first();
            if ($res != null) {
                $lab->added = 'yes';
            } else {
                $lab->added = 'no';
            }

            // $getTestAOE = QuestDataAOE::select("TEST_CD AS TestCode", "AOE_QUESTION AS QuestionShort", "AOE_QUESTION_DESC AS QuestionLong")
            //     ->where('TEST_CD', $lab->TEST_CD)
            //     ->groupBy('AOE_QUESTION_DESC')
            //     ->get()
            //     ->toArray();

            // $count = count($getTestAOE);
            // if ($count > 0) {
            //     $lab->aoes = 1;
            //     $lab->aoeQuestions = $getTestAOE;
            // } else {
            //     $lab->aoes = 0;
            //     $lab->aoeQuestions = '';
            // }
        }

        return $labs;
    }
    public function refer_doc_search(Request $request)
    {
        // dd($request->name);
        if ($request->name == '') {
            $spec_docs = DB::table('users')
            ->where('user_type', 'doctor')
            ->where('id', '!=', $request->doctor_id)
            ->where('specialization', $request->spec)
            ->where('status', '!=', 'ban')
            ->where('active', 1)
            ->get();

            $referBtn = 0;
            foreach ($spec_docs as $doc) {
                $refered = Referal::where('session_id', $request->session_id)->where('sp_doctor_id', $doc->id)->first();
                if ($refered != null) {
                    $doc->refered = true;
                    $referBtn = 1;
                    $doc->refer_id = $refered->id;
                } else {
                    $doc->refered = false;
                }
                $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            }
            return array($spec_docs, $referBtn);
        }else{
            $spec_docs = DB::table('users')
            ->where('user_type', 'doctor')
            ->where('id', '!=', $request->doctor_id)
            ->where('name', 'like',"{$request->name}%")
            ->where('specialization', $request->spec)
            ->where('status', '!=', 'ban')
            ->where('active', 1)
            ->get();

            $referBtn = 0;
            foreach ($spec_docs as $doc) {
                $refered = Referal::where('session_id', $request->session_id)->where('sp_doctor_id', $doc->id)->first();
                if ($refered != null) {
                    $doc->refered = true;
                    $referBtn = 1;
                    $doc->refer_id = $refered->id;
                } else {
                    $doc->refered = false;
                }
                $doc->user_image = \App\Helper::check_bucket_files_url($doc->user_image);
            }

            return array($spec_docs, $referBtn);
        }
    }
    public function getImagingServicesSelect()
    {
        $keyword = $_REQUEST['term'];

        $data = $this->allProductsRepository->getImagingServicesSelect($keyword);

        echo json_encode($data);
    }

    public function getImagingLocationSelect()
    {
        $keyword = $_REQUEST['term'];

        $data = $this->allProductsRepository->getImagingLocationSelect($keyword);

        echo json_encode($data);
    }

    public function bulkUploadImagingPrices(Request $request)
    {
        $input = $request->all();
        return view('all_products.bulk_upload_imaging_prices');
    }

    public function bulkUploadImagingServices(Request $request)
    {
        $input = $request->all();
        return view('all_products.bulk_upload_imaging_services');
    }

    public function bulkUploadImagingPricesStore(Request $request)
    {
        $input = $request->all();
        $citiesIds = $input["city"];
        $user = auth()->user();
        foreach ($citiesIds as $item) {
            $price = ImagingPrices::Create([
                'location_id' => $item,
                'product_id' => $input['name'],
                'price' => $input['price'],
                'created_by' => $user->id,
                'updated_at' => NOW(),
            ]);

            $forLogMsg = 'addPrice|userID:' . $user->id . '|priceID:' . $price['id'] . '|locationID:' . $item . '|productID:' . $input['name'] . '|price:' . $input['price'];
            Log::channel('imagingPrices')->info($forLogMsg);
        }
        Flash::success('Prices are saved successfully.');
        return redirect('/imaging_prices');
    }

    public function bulkUploadImagingServicesStore(Request $request)
    {
        $input = $request->all();
        $servicesIds = $input["name"];
        $user = auth()->user();
        foreach ($servicesIds as $item) {
            $price = ImagingPrices::Create([
                'location_id' => $input['city'],
                'product_id' => $item,
                'price' => $input['price'],
                'created_by' => $user->id,
                'updated_at' => NOW(),
            ]);

            $forLogMsg = 'addPrice|userID:' . $user->id . '|priceID:' . $price['id'] . '|locationID:' . $input['city'] . '|productID:' . $item . '|price:' . $input['price'];
            Log::channel('imagingPrices')->info($forLogMsg);
        }
        Flash::success('Prices are saved successfully.');
        return redirect('/imaging_prices');
    }

    public function viewAllQuestLabTest()
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
                // 'product_categories.name as main_category_name'
            )
            ->where([['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],])
            // ->where('quest_data_test_codes.TEST_CD', '1759')
            ->orderBy('quest_data_test_codes.TEST_NAME', 'ASC')
            ->get();

        foreach ($data as $item) {
            $ids = explode(",", $item->PARENT_CATEGORY);
            $cat_names = ProductCategory::select(
                DB::raw("GROUP_CONCAT(`name`) AS names"),
            )->whereIn('id', $ids)->first();
            $item->main_category_name = $cat_names->names;
        }
        return view('all_products.questLabTestViewAll', compact('data'));
    }

    public function viewQuestLabTest()
    {
        $data = DB::table('quest_data_test_codes')
            // ->leftJoin('product_categories', 'quest_data_test_codes.PARENT_CATEGORY', '=', 'product_categories.id')
            ->select(
                'quest_data_test_codes.TEST_CD',
                'quest_data_test_codes.DESCRIPTION',
                'quest_data_test_codes.TEST_NAME',
                'quest_data_test_codes.PRICE',
                'quest_data_test_codes.SALE_PRICE',
                'quest_data_test_codes.PARENT_CATEGORY',
                // 'product_categories.name as main_category_name'
            )
            // ->where([['quest_data_test_codes.PARENT_CATEGORY', '!=', ""],])
            // ->where('quest_data_test_codes.TEST_CD', '1759')
            ->orderBy('quest_data_test_codes.TEST_NAME', 'ASC')
            ->get();

        foreach ($data as $item) {
            $ids = explode(",", $item->PARENT_CATEGORY);
            $cat_names = ProductCategory::select(
                DB::raw("GROUP_CONCAT(`name`) AS names"),
            )->whereIn('id', $ids)->first();
            $item->main_category_name = $cat_names->names;
        }
        return view('all_products.questLabTestViewAll', compact('data'));
    }

    public function editQuestLabTest($id = "")
    {
        $mainCategory = ProductCategory::where('category_type', 'lab-test')->orderBy('name', 'asc')->get();
        $data = DB::table('quest_data_test_codes')
            ->leftJoin('product_categories', 'quest_data_test_codes.PARENT_CATEGORY', '=', 'product_categories.id')
            ->select(
                'quest_data_test_codes.TEST_CD',
                'quest_data_test_codes.DESCRIPTION',
                'quest_data_test_codes.TEST_NAME',
                'quest_data_test_codes.PRICE',
                'quest_data_test_codes.SALE_PRICE',
                'quest_data_test_codes.DETAILS',
                'product_categories.name as main_category_name'
            )
            ->where([
                ['quest_data_test_codes.TEST_CD', $id],
            ])
            ->first();

        return view('all_products.editQuestLabTest', compact('mainCategory', 'data'));
    }

    public function updateQuestLabTest(Request $request)
    {
        $input = $request->all();
        unset($input['_token']);
        $input['SLUG'] = $this->slugify($input['TEST_NAME']);
        $input['PARENT_CATEGORY'] = implode(",", $input['PARENT_CATEGORY']);
        $query = DB::table('quest_data_test_codes')
            ->where('TEST_CD', $input['TEST_CD'])
            ->update($input);


        $create = ActivityLog::create([
            'activity' => 'update lab test by ' . auth()->user()->name,
            'type' => 'labtest update',
            'user_id' => auth()->user()->id,
            'user_type' => 'editor_lab'
        ]);

        Flash::success('Data submitted successfully.');
        return redirect('/viewAllQuestLabTest');
    }

    public function converToObjToArray($data)
    {
        return json_decode(json_encode($data), true);
    }

    public function converToArrayToObj($data)
    {
        return json_decode(json_encode((object) $data), false);
    }

    public function medicinedescription(Request $request)
    {

        // After Select any product from dropdown this check will autofill text editor
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            $getDesc = AllProducts::select('description', 'short_description')->where('id', $product_id)->first();
            echo json_encode($getDesc);
            exit();
        }

        $data = DB::table('tbl_products')
            ->select('id', 'name')
            ->where('mode', '=', "medicine")
            ->orderBy('name', 'asc')
            ->get();

        return view('all_products.pharmacy_description', compact('data'))->with("message", " Updated Successfully");
    }

    public function dash_medicine_description(Request $request)
    {

        // After Select any product from dropdown this check will autofill text editor
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            $getDesc = AllProducts::select('description', 'short_description')->where('id', $product_id)->first();
            echo json_encode($getDesc);
            exit();
        }

        $data = DB::table('tbl_products')
            ->select('id', 'name')
            ->where('mode', '=', "medicine")
            ->orderBy('name', 'asc')
            ->get();

        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.Medicine.medicine_description', compact('data'))->with("message", " Updated Successfully");
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.Medicine.medicine_description', compact('data'))->with("message", " Updated Successfully");
        }
    }

    public function dash_get_description()
    {
        // After Select any product from dropdown this check will autofill text editor
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            $getDesc = DB::table('tbl_products')->select('description', 'short_description')->where('id', $product_id)->first();
            // $getDesc = AllProducts::select('description', 'short_description')->where('id', $product_id)->first();
            // $getDesc = json_decode($getDesc);
            return $getDesc;
            exit();
        }else{
            $data = DB::table('tbl_products')
                ->select('id', 'name')
                ->where('mode', '=', "medicine")
                ->orderBy('name', 'asc')
                ->get();
            $user_type = Auth::user()->user_type;
            if($user_type == 'admin_pharm'){
                return view('dashboard_Pharm_admin.Medicine.medicine_description', compact('data'))->with("message", " Updated Successfully");
            }elseif($user_type == 'editor_pharmacy'){
                return view('dashboard_Pharm_editor.Medicine.medicine_description', compact('data'))->with("message", " Updated Successfully");
            }
        }
    }

    public function storedesc(Request $request)
    {

        $input = $request->all();
        // dd($input);

        $validateData = $request->validate([
            'description' => ['required'],
            'short_description' => ['required'],
        ]);

        $res = DB::table('tbl_products')
            ->where('id', $input['medicine'])
            ->update(
                ['description' => $validateData['description'], 'short_description' => $validateData['short_description']]
            );


        Flash::success('Description saved successfully.');
        return redirect()->back();
    }
    public function generatePDF()
    {
        // $pdf = PDF::loadView('all_products.rxOutreach.e_prescription');
        $data = ['title' => 'Welcome to web-tuts.com'];
        $pdf = PDF::loadView('all_products.rxOutreach.e_prescription', $data);
        return $pdf->download('e_prescription.pdf');
        // dd($pdf)
    }

    public function generate_online_pdf(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if($request->date!='')
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $OnlineItems = DB::table('lab_orders')->where('pres_id', 0)->where('date', '>=', $startdate)->where('date', '<=', $enddate)->orderBy('id','DESC')->get();
        }
        else
        {
            $OnlineItems = DB::table('lab_orders')->where('pres_id', 0)->orderBy('id','DESC')->get();
        }
        foreach ($OnlineItems as $ot) {
            $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
            $ot->price = $test->PRICE;
            $ot->sale_price = $test->SALE_PRICE;
            $ot->datetime = User::convert_utc_to_user_timezone($user_id, $ot->created_at);
            $ot->name = $test->DESCRIPTION;
        }
        $pdf = PDF::loadView('dashboard_admin.wallet_files.OnlineDetails', compact('OnlineItems'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Online_Earnings_Details.pdf"
        );
    }

    public function generate_online_csv(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if($request->date!='')
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $OnlineItems = DB::table('lab_orders')->where('pres_id', 0)->where('date', '>=', $startdate)->where('date', '<=', $enddate)->orderBy('id','DESC')->get();
        }
        else
        {
            $OnlineItems = DB::table('lab_orders')->where('pres_id', 0)->orderBy('id','DESC')->get();
        }
        $headers = array(
            'Content-Type' => 'text/csv'
          );
        $filename =  public_path('Online_Earning_Details.xlxs');
        $handle = fopen($filename,'w');
        fputcsv($handle, [
            "Order ID",
            "Product ID",
            "Product Name",
            "Product Type",
            "Price",
            "Selling Price",
            "Date",
            "Time",
        ]);

        foreach ($OnlineItems as $ot) {
            $test = DB::table('quest_data_test_codes')->where('TEST_CD', $ot->product_id)->first();
            $ot->price = $test->PRICE;
            $ot->sale_price = $test->SALE_PRICE;
            $ot->datetime = User::convert_utc_to_user_timezone($user_id, $ot->created_at);
            $ot->name = $test->DESCRIPTION;

            fputcsv($handle, [
                $ot->order_id,
                $ot->product_id,
                $ot->name,
                $ot->type,
                $ot->price,
                $ot->sale_price,
                $ot->datetime['date'],
                $ot->datetime['time'],
            ]);

        }
        fclose($handle);
        return response()->download($filename, "Online_Earning_Details.csv", $headers);
    }

    public function generate_prescriptions_pdf(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if($request->id != '')
        {
            $request->id = explode('-',$request->id);
            $s_id = $request->id[1];
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('sessions.session_id',$s_id)
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id')
            ->get();
        }
        elseif($request->date != '')
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->whereDate('prescriptions.created_at', '>=', $startdate)
            ->whereDate('prescriptions.created_at', '<=', $enddate)
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id')
            ->get();
        }
        else
        {
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id')
            ->get();
        }
        foreach ($prescriptions as $pres) {
            if ($pres->type == 'lab-test') {
                $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                $pres->name = $test->DESCRIPTION;
                $pres->sale_price = $test->SALE_PRICE;
                $pres->price = $test->PRICE;
                $pres->order_id = $order->order_id;
                $pres->pro_id = $pres->test_id;
            } else if ($pres->type == 'imaging') {
                $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                $price = DB::table('imaging_prices')->where('id', $pres->imaging_id)->first();
                $pres->name = $test->name;
                $pres->sale_price = $price->price;
                $pres->price = $price->price;
                $pres->order_id = $order->order_id;
                $pres->pro_id = $pres->imaging_id;
            } else if ($pres->type == 'medicine') {
                $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                $order = DB::table('lab_orders')->where('pres_id', $pres->id)->first();
                $price = DB::table('medicine_pricings')->where('id', $pres->price)->first();
                $pres->name = $test->name;
                $pres->sale_price = $price->sale_price;
                $pres->price = $price->price;
                if ($order != null) {
                    $pres->order_id = $order->order_id;
                } else {
                    $pres->order_id = $pres->id;
                }
                $pres->pro_id = $pres->medicine_id;
            }
            $pres->datetime = User::convert_utc_to_user_timezone($user_id, $pres->created_at);
            if($pres->usage!=null)
            {
                $dose = explode(':',$pres->usage);
                $pres->usage = $dose[1];
            }
        }

        $pdf = PDF::loadView('dashboard_admin.wallet_files.PrescriptionsDetails', compact('prescriptions'))->output();
        return response()->streamDownload(
            fn () => print($pdf),
            "Prescription_Earnings_Details.pdf"
        );
    }

    public function generate_prescriptions_csv(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if($request->id != '')
        {
            $request->id = explode('-',$request->id);
            $s_id = $request->id[1];
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('sessions.session_id',$s_id)
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
            ->get();
        }
        elseif($request->date != '')
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->whereDate('prescriptions.created_at', '>=', $startdate)
            ->whereDate('prescriptions.created_at', '<=', $enddate)
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
            ->get();
        }
        else
        {
            $prescriptions = DB::table('prescriptions')
            ->join('sessions','prescriptions.session_id','sessions.id')
            ->join('tbl_cart','prescriptions.id','tbl_cart.pres_id')
            ->where('tbl_cart.item_type','prescribed')
            ->where('tbl_cart.status','purchased')
            ->orderBy('prescriptions.id','DESC')
            ->select('prescriptions.*','sessions.session_id as ses_id','sessions.id as sessi_id')
            ->get();
        }
        $headers = array(
            'Content-Type' => 'text/csv'
          );
            $filename =  public_path('Prescriptions_Earning_Details.xlxs');
            $handle = fopen($filename,'w');
            fputcsv($handle, [
                "Order ID",
                "Session ID",
                "Product ID",
                "Product Name",
                "Product Type",
                "Dosage Days",
                "Price",
                "Selling Price",
                "Date",
                "Time",
            ]);


        foreach ($prescriptions as $pres) {
            if ($pres->type == 'lab-test') {
                $test = DB::table('quest_data_test_codes')->where('TEST_CD', $pres->test_id)->first();
                $order = DB::table('lab_orders')->where('pres_id', $pres->id)->where('product_id', $pres->test_id)->first();
                $pres->name = $test->DESCRIPTION;
                $pres->sale_price = $test->SALE_PRICE;
                $pres->price = $test->PRICE;
                $pres->order_id = $order->order_id;
                $pres->pro_id = $pres->test_id;
            } else if ($pres->type == 'imaging') {
                $test = DB::table('tbl_products')->where('id', $pres->imaging_id)->first();
                $order = DB::table('imaging_orders')->where('pres_id', $pres->id)->where('product_id', $pres->imaging_id)->first();
                $price = DB::table('imaging_prices')->where('id', $pres->imaging_id)->first();
                $pres->name = $test->name;
                $pres->sale_price = $price->price;
                $pres->price = $price->actual_price;
                $pres->order_id = $order->order_id;
                $pres->pro_id = $pres->imaging_id;
            } else if ($pres->type == 'medicine') {
                $test = DB::table('tbl_products')->where('id', $pres->medicine_id)->first();
                $order = DB::table('medicine_order')->where('session_id', $pres->sessi_id)->first();
                $price = DB::table('medicine_pricings')->where('id', $pres->price)->first();
                $pres->name = $test->name;
                $pres->sale_price = $price->sale_price;
                $pres->price = $price->price;
                $pres->order_id = $order->order_main_id;
                $pres->pro_id = $pres->medicine_id;
            }
            $pres->datetime = User::convert_utc_to_user_timezone($user_id, $pres->created_at);
            if($pres->usage!=null)
            {
                $dose = explode(':',$pres->usage);
                $pres->usage = $dose[1];
            }
            fputcsv($handle, [
                $pres->order_id,
                $pres->ses_id,
                $pres->pro_id,
                $pres->name,
                $pres->type,
                $pres->usage,
                $pres->price,
                $pres->sale_price,
                $pres->datetime['date'],
                $pres->datetime['time'],
            ]);
        }

        fclose($handle);
        return response()->download($filename, "Prescriptions_Earning_Details.csv", $headers);
    }

    public function generate_sessions_pdf(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if($request->id != '')
        {
            $request->id = explode('-',$request->id);
            $s_id = $request->id[1];
            $getSessionTotalSessions = DB::table("sessions")->where('session_id',$s_id)->where('status', 'ended')->orwhere('status', 'paid')->get();
        }
        elseif($request->date != '')
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $getSessionTotalSessions = DB::table("sessions")->where('date', '>=', $startdate)->where('date', '<=', $enddate)->where('status', 'ended')->orwhere('status', 'paid')->orderBy('id','DESC')->get();
        }
        else
        {
            $getSessionTotalSessions = DB::table("sessions")->where('status', 'ended')->orwhere('status', 'paid')->orderBy('id','DESC')->get();
        }
        foreach ($getSessionTotalSessions as $getSessionTotalSession) {
            $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->doc_percent = $getpercentage->percentage;
            $getSessionTotalSession->doc_price = $doc_price;
            $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
            $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
            $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
            $user_check = User::where('id', $getSessionTotalSession->doctor_id)->first();
            $getSessionTotalSession->doc_name = $user_check->name . ' ' . $user_check->last_name;
            $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user_id, $getSessionTotalSession->created_at);
        }


        $pdf = PDF::loadView('dashboard_admin.wallet_files.SessionsDetails', compact('getSessionTotalSessions'))->output();
        //return $pdf->download('EarningDetails.pdf');
        return response()->streamDownload(
            fn () => print($pdf),
            "Sessions_Earning_Details.pdf"
        );
    }

    public function generate_sessions_csv(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        if($request->id != '')
        {
            $request->id = explode('-',$request->id);
            $s_id = $request->id[1];
            $getSessionTotalSessions = DB::table("sessions")->where('session_id',$s_id)->where('status', 'ended')->orwhere('status', 'paid')->get();
        }
        elseif($request->date != '')
        {
            $request->date = explode('-', $request->date);
            $startdate = date('Y-m-d', strtotime($request->date[0]));
            $enddate = date('Y-m-d', strtotime($request->date[1]));
            $getSessionTotalSessions = DB::table("sessions")->where('date', '>=', $startdate)->where('date', '<=', $enddate)->where('status', 'ended')->orwhere('status', 'paid')->orderBy('id','DESC')->get();
        }
        else
        {
            $getSessionTotalSessions = DB::table("sessions")->where('status', 'ended')->orwhere('status', 'paid')->orderBy('id','DESC')->get();
        }
        $headers = array(
            'Content-Type' => 'text/csv'
          );
            $filename =  public_path('Sessions_Earning_Details.xlxs');
            $handle = fopen($filename,'w');
            fputcsv($handle, [
                "Session ID",
                "Doctor Name",
                "Patient Name",
                "Patient Paid",
                "Doctor Fee",
                "Credit Card Fee",
                "Net Profit",
                "Date",
                "Time",
            ]);

        foreach ($getSessionTotalSessions as $getSessionTotalSession) {
            $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->doc_percent = $getpercentage->percentage;
            $getSessionTotalSession->doc_price = $doc_price;
            $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
            $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
            $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
            $user_check = User::where('id', $getSessionTotalSession->doctor_id)->first();
            $getSessionTotalSession->doc_name = $user_check->name . ' ' . $user_check->last_name;
            $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user_id, $getSessionTotalSession->created_at);

            fputcsv($handle, [
                $getSessionTotalSession->session_id,
                $getSessionTotalSession->doc_name,
                $getSessionTotalSession->pat_name,
                $getSessionTotalSession->price,
                $getSessionTotalSession->doc_price,
                $getSessionTotalSession->card_fee,
                $getSessionTotalSession->Net_profit,
                $getSessionTotalSession->datetime['date'],
                $getSessionTotalSession->datetime['time'],
            ]);
        }
        fclose($handle);
        return response()->download($filename, "Sessions_Earning_Details.csv", $headers);
    }

    public function get_sessions_pdf(Request $request)
    {
        $user_time_zone = auth()->user()->timeZone;
        $user_type = Auth::user()->user_type;
        $user_id = Auth::user()->id;
        $getSessionTotalSessions = DB::table("sessions")->where('status', 'ended')->orwhere('status', 'paid')->get();
        foreach ($getSessionTotalSessions as $getSessionTotalSession) {
            $getpercentage = DB::table('doctor_percentage')->where('doc_id', $getSessionTotalSession->doctor_id)->first();
            $doc_price = ($getpercentage->percentage / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->doc_percent = $getpercentage->percentage;
            $getSessionTotalSession->doc_price = $doc_price;
            $getSessionTotalSession->card_fee = (2 / 100) * $getSessionTotalSession->price;
            $getSessionTotalSession->Net_profit = $getSessionTotalSession->price - $doc_price - $getSessionTotalSession->card_fee;
            $user_check = User::where('id', $getSessionTotalSession->patient_id)->first();
            $getSessionTotalSession->pat_name = $user_check->name . ' ' . $user_check->last_name;
            $user_check = User::where('id', $getSessionTotalSession->doctor_id)->first();
            $getSessionTotalSession->doc_name = $user_check->name . ' ' . $user_check->last_name;
            $getSessionTotalSession->datetime = User::convert_utc_to_user_timezone($user_id, $getSessionTotalSession->created_at);
        }


        $pdf = PDF::loadView('dashboard_admin.wallet_files.SessionsDetails', compact('getSessionTotalSessions'))->output();
        //return $pdf->download('EarningDetails.pdf');
        return response()->streamDownload(
            fn () => print($pdf),
            "Sessions_Earning_Details.pdf"
        );
    }

    public function generate_all_imaging_record_csv(Request $request)
    {
        if($request->csv_date != null)
        {
            $records = DB::table('imaging_prices')
            ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_prices.location_id')
            ->join('tbl_products', 'tbl_products.id', '=', 'imaging_prices.product_id')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->where('tbl_products.mode', 'imaging')
            ->where('imaging_prices.id','LIKE','%'.$request->csv_date.'%')
            ->orwhere('product_categories.name','LIKE','%'.$request->csv_date.'%')
            ->orwhere('tbl_products.name','LIKE','%'.$request->csv_date.'%')
            ->orwhere('tbl_products.cpt_code','LIKE','%'.$request->csv_date.'%')
            ->orwhere('imaging_prices.price','LIKE','%'.$request->csv_date.'%')
            ->orwhere('imaging_prices.actual_price','LIKE','%'.$request->csv_date.'%')
            ->orwhere('imaging_locations.zip_code','LIKE','%'.$request->csv_date.'%')
            ->orwhere('imaging_locations.address','LIKE','%'.$request->csv_date.'%')
            ->select(
                'imaging_prices.id as id',
                'tbl_products.id AS product_id',
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
            )
            ->orderby('imaging_prices.id','DESC')
            ->get();
        }
        else
        {
            $records = DB::table('imaging_prices')
            ->join('imaging_locations', 'imaging_locations.id', '=', 'imaging_prices.location_id')
            ->join('tbl_products', 'tbl_products.id', '=', 'imaging_prices.product_id')
            ->join('product_categories', 'tbl_products.parent_category', '=', 'product_categories.id')
            ->where('tbl_products.mode', 'imaging')
            ->select(
                'imaging_prices.id as id',
                'tbl_products.id as id',
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
            )
            ->orderby('imaging_prices.id','DESC')
            ->get();
        }

        $headers = array(
            'Content-Type' => 'text/csv'
        );
        $filename =  public_path('Imaging_All_Records.xlxs');
        $handle = fopen($filename,'w');
        fputcsv($handle, [
            "ID",
            "Parent Category",
            "Name",
            "CPT Code",
            "Price",
            "Actual Price",
            "Address",
            "Location",
            "Zipcode",
        ]);

        foreach($records as $record)
        {
            fputcsv($handle, [
                $record->id,
                $record->product_category,
                $record->product_name,
                $record->cpt_code,
                $record->price,
                $record->ac_price,
                $record->address,
                $record->location_name,
                $record->zip_code,
            ]);
        }

        fclose($handle);
        return response()->download($filename, "Imaging_All_Records.csv", $headers);
    }

    public function e_prescription()
    {
        return view('all_products.rxOutreach.e_prescription');
    }

    public function delete_imaging_prices($id)
    {
        $user = auth()->user();
        $getDetails = ImagingPrices::where('id', $id)->get()->toArray();
        $location_id = $getDetails[0]['location_id'];
        $product_id = $getDetails[0]['product_id'];
        $price = $getDetails[0]['price'];
        $forLogMsg = 'deletePrice|userID:' . $user->id . '|priceID:' . $id . '|locationID:' . $location_id . '|productID:' . $product_id . '|price:' . $price;
        Log::channel('imagingPrices')->info($forLogMsg);
        ImagingPrices::find($id)->forcedelete();
        return redirect()->back()->with('msg','Pricing ID '.$id.' deleted successfully');
    }
}
