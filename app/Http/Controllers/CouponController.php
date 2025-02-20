<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuestDataTestCode;
use Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function view(){
        $coupons = DB::table('coupon_code')->get();
        return view('dashboard_admin.coupons.view_all_coupons',compact('coupons'));
    }

    public function index(){
        return view('dashboard_admin.coupons.add_new_coupon');
    }

    public function get_category($category){
        $category = explode(',',$category);
        $fulldata = array();
        // dd($cat);
        foreach ($category as $cat) {
            if($cat == 'pharmacy'){
                $data = DB::table('products_sub_categories')->where('parent_id', '38')
                ->join('tbl_products','products_sub_categories.id','tbl_products.sub_category')
                ->select('products_sub_categories.*')
                ->groupBy('tbl_products.sub_category')
                ->get();
                array_push($fulldata,$data);
            }elseif($cat == 'imaging'){
                $data = DB::table('product_categories')->where('category_type', 'imaging')->get();
                array_push($fulldata,$data);
            }elseif($cat == 'lab'){
                $data = QuestDataTestCode::whereRaw("TEST_CD NOT LIKE '#%%' ESCAPE '#'")
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
                array_push($fulldata,$data);
            }else{
                $fulldata = 'ok';
            }
        }
        return $fulldata;
    }

    public function get_sub_category_product($cat,$sub_cat){
        if($cat == 'pharmacy' && $sub_cat != 'all'){
            $products = DB::table('tbl_products')->where('sub_category', $sub_cat)->get();
        }elseif($cat == 'imaging' && $sub_cat != 'all'){
            $products = DB::table('tbl_products')
            ->join('imaging_prices', 'imaging_prices.product_id', 'tbl_products.id')
            ->where('tbl_products.parent_category', $sub_cat)
            ->select('tbl_products.id as pro_id', 'tbl_products.name as pro_name', 'imaging_prices.location_id')
            ->get();
        }elseif($cat == 'pharmacy' && $sub_cat == 'all'){
            $products = DB::table('tbl_products')->get();
        }elseif($cat == 'imaging' && $sub_cat == 'all'){
            $products = DB::table('tbl_products')
            ->join('imaging_prices', 'imaging_prices.product_id', 'tbl_products.id')
            // ->where('tbl_products.parent_category', $sub_cat)
            ->select('tbl_products.id as pro_id', 'tbl_products.name as pro_name', 'imaging_prices.location_id')
            ->get();
        }
        return $products;
    }

    public function store(Request $request){
        DB::table('coupon_code')->insert([
            'coupon_code' => $request->code,
            'discount_percentage' => $request->dis_per,
            'category' => serialize($request->category),
            'sub_category' => serialize($request->sub_category),
            'product' => serialize($request->prod),
            'status' => $request->status,
            'expiry_date' => $request->exp_date,
            'created_at' => NOW(),
            'updated_at' => NOW(),
        ]);

        return redirect()->back();
    }

    public function apply_discount(Request $request){
        $discount = DB::table('coupon_code')->where('coupon_code' , $request->code)->where('status',1)->first();
        $discount->category = unserialize($discount->category);
        $discount->sub_category = unserialize($discount->sub_category);
        $discount->product = unserialize($discount->product);

        $allProducts = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->get();
        $request->prod_id = explode(',',$request->prod_id);
        array_pop($request->prod_id);
        $request->cart_id = explode(',',$request->cart_id);
        array_pop($request->cart_id);

        $checkFirst = DB::table('tbl_cart')
            ->where('coupon_code_id', $discount->id)
            ->where('user_id', Auth::user()->id)
            ->get();

        $temp = 'false';
        if($checkFirst == "[]")
        {    if($discount != ''){
                $products = $discount->product;
                foreach($request->prod_id as $key => $id){
                    if(in_array($id, $products)){
                        if($discount->expiry_date >= date('Y-m-d')){
                            $discount_item = DB::table('tbl_cart')
                            ->where('id',$request->cart_id[$key])
                            ->update([
                                'coupon_code_id' => $discount->id,
                            ]);
                            $temp = 'true';
                        }else{
                            $temp = 'Date Expired!!';
                        }
                    }else if($products[0] == 'all'){
                        $discount_item = DB::table('tbl_cart')
                            ->where('id',$request->cart_id[$key])
                            ->update([
                                'coupon_code_id' => $discount->id,
                            ]);
                            $temp = 'true';
                    }else{
                        $temp = 'false';
                    }
                }
                return $temp;
            }else{
                return $temp;
            }
        }else{
            return $temp;
        }
    }

    public function destroy($id){
        DB::table('coupon_code')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function check(){
        $allProducts = DB::table('tbl_cart')->where('show_product', '1')->where('status', 'recommended')->where('user_id', Auth::user()->id)->get();
        $res = false;
        foreach ($allProducts as $prod) {
            if($prod->coupon_code_id != ""){
                $res = true;
            }
        }
        return $res;
    }
}
