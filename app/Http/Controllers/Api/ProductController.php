<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use DB;

class ProductController extends BaseController
{
    //pharmacy
    public function categories(){
        $categories = DB::table('product_categories')->where('name',"!="," ")->get();
        if($categories != null){
            $status =200;
            $data['code'] = $status;
            $data['categories'] = $categories;
            return $this->SendResponse($data,"Categories Found");
        }
        $status =200;
        $dataCode['code'] = $status;
        return $this->sendError("Categories Not Found",$dataCode);
    }
    public function sub_category(){
        $subcateogries = DB::table('products_sub_categories')->where('parent_id','=',38)->get();
        if($subcateogries != null){
            $status =200;
            $data['code'] = $status;
            $data['subcateogries'] = $subcateogries;
            return $this->SendResponse($data,"Sub Categories Found");
        }
        $status =200;
        $data['code'] = $status;
        return $this->sendError("Sub Category Not Found",$data);
    }
    public function products($id){
        $products = DB::table('tbl_products')->where('parent_category','=',38)->where('sub_category',$id)->get();
        if($products != null){
            $status =200;
            $data['code'] = $status;
            $data['products'] = $products;
            return $this->SendResponse($data,"Products Found");
        }
        $status =200;
        $data['code'] = $status;
        return $this->sendError("Error",$data);
    }
    public function medicine(){
        $medicines = DB::table('tbl_products')->where('mode','=','medicine')->paginate(20);
        if($medicines != null){
            $status =200;
            $data['code'] = $status;
            $data['medicines'] = $medicines;
            return $this->SendResponse($data,"medicines Found");
        }
        $status =200;
        $data['code'] = $status;
        return $this->sendError("Error",$data);
    }
    public function product_search(Request $request){
        $product_name =$request->search;
        $sub_category =$request->sub_category;
        if($product_name && $sub_category){
            $products = DB::table('tbl_products')->where('parent_category','=',38)->where('name','LIKE','%'.$product_name.'%')->where('sub_category','=',$sub_category)->get();
            // dd($product_name);
            if(!$products->isEmpty()){
                $data['code'] = 200;
                $data['products'] = $products;
                return $this->sendResponse($data,"Product found");
            } else{
                $data['code'] =200;
                return $this->sendError($data,"Error in finding product,sub category is wrong");
            }
        } elseif($product_name){
            $products = DB::table('tbl_products')->where('parent_category','=',38)->where('name','LIKE','%'.$product_name.'%')->get();
            if(!$products->isEmpty()){
                $data['code'] = 200;
                $data['products'] = $products;
                return $this->sendResponse($data,"Product Found");
            } else{
                $data['code'] = 200;
                return $this->sendError($data,"Product Not Found");
            }
        } else{
            $products = DB::table('tbl_products')->where('parent_category','=',38)->get();
            $data['code'] = 200;
            $data['products'] = $products;
            return $this->sendResponse($data,"All Products");
        }
    }
    // lab-test
    public function lab_test_categories(){
        $data = DB::table('product_categories')->where('category_type', 'lab-test')
        ->orderBy('id', 'asc')->whereNotIn('id', ['27', '29', '43'])->get();
        if(!$data->isEmpty()){
            $status =200;
            $lab_test_category['code'] = $status;
            $lab_test_category['products'] = $data;
            return $this->sendResponse($lab_test_category,"lab test category found");
        } else{
            $status =200;
            $data['code'] = $status;
            return $this->sendError("Error",$data);
        }
    }
    public function lab_test($id){
        $idArray = explode(',', $id);
        $data = DB::table('quest_data_test_codes')
                ->where([
                    ['AOES_exist', null],
                    ['DETAILS', '!=', ""],
                    ['SALE_PRICE', '!=', ""],
                ])
                ->whereRaw("find_in_set('".$id."',quest_data_test_codes.PARENT_CATEGORY)")
                ->paginate(10);
        if(!$data->isEmpty()){
            $lab_test['code'] = 200;
            $lab_test['lab_test'] = $data;
            return $this->sendResponse($lab_test,"Lab Test Found");
        } else{
            $status =200;
            $dataCode['code'] = $status;
            return $this->sendError($dataCode,"Error in finding lab test data");
        }
    }
    public function lab_test_search(Request $request){
        $product_name =$request->search;
        $sub_category =$request->sub_category;
        if($product_name && $sub_category){
            $data = DB::table('quest_data_test_codes')->where([
                ['PARENT_CATEGORY', '!=', ""],
                ['AOES_exist', null],
                ['DETAILS', '!=', ""],
                ['SALE_PRICE', '!=', ""],
            ])->where('TEST_NAME','LIKE','%'.$product_name.'%')->whereRaw("find_in_set('$sub_category',`PARENT_CATEGORY`)")->get();
            if(!$data->isEmpty()){
                $status =200;
                $lab_test['code'] = $status;
                $lab_test['products'] = $data;
                return $this->sendResponse($lab_test,"Lab Test Found");
            } else{
                $status =200;
                $dataCode['code'] = $status;
                return $this->sendError("Product Not Found",$dataCode);
            }
        } elseif($product_name){

            $lab_data = DB::table('quest_data_test_codes')->where([
                ['PARENT_CATEGORY', '!=', ""],
                ['AOES_exist', null],
                ['DETAILS', '!=', ""],
                ['SALE_PRICE', '!=', ""],
            ])->where('TEST_NAME','LIKE','%'.$product_name.'%')->get();
            if(!$lab_data->isEmpty()){
                $status =200;
                $lab['code'] = $status;
                $lab['products'] = $lab_data;
                return $this->sendResponse($lab,"Lab Test Found");
            } else{
                $status =200;
                $dataCode['code'] = $status;
                return $this->sendError("Product Not Found",$dataCode);
            }
        } else{
            $lab_categories = DB::table('product_categories')->where('category_type', 'lab-test')
            ->orderBy('id', 'asc')->whereNotIn('id', ['27', '29', '43'])->get();
            $data = DB::table('quest_data_test_codes')->where([
                ['PARENT_CATEGORY', '!=', ""],
                ['AOES_exist', null],
                ['DETAILS', '!=', ""],
                ['SALE_PRICE', '!=', ""],
            ])->get();
            if(!$data->isEmpty()){
                $status =200;
                $lab_categories['code'] = $status;
                $lab_categories['lab_test'] = $lab_categories;
                return $this->sendResponse($data,"Lab Test Found");
            } else{
                $status =200;
                $data['code'] = $status;
                return $this->sendError("Product Not Found",$data);
            }
        }

    }
    // imaging
    public function imaging_categories(){
        $data = DB::table('product_categories')->where('category_type','imaging')->get();
        if(!$data->isEmpty()){
            $status =200;
            $imaging_categories['code'] = $status;
            $imaging_categories['imaging_categories'] = $data;
            return $this->sendResponse($imaging_categories,"Imaging Categories");
        } else{
            $status =200;
            $dataCode['code'] = $status;
            return $this->sendError("Error",$dataCode);
        }
    }
    public function imaging_product($id){
        $data = DB::table('tbl_products')->where('mode','imaging')->where('product_status',1)
        ->where('parent_category',$id)->paginate(10);
        if(!$data->isEmpty()){
            $status =200;
            $imaging_products['code'] = $status;
            $imaging_products['imaging_products'] = $data;
            return $this->sendResponse($imaging_products,"Imaging Products");
        } else{
            $status =200;
            $imaging_products['code'] = $status;
            return $this->sendError($imaging_products,"Error in imaging product");
        }
    }
    public function imaging_product_search(Request $request){
        $product_name =$request->search;
        $parent_category =$request->parent_category;
        if($product_name && $parent_category){
            $data = DB::table('tbl_products')->where('mode','imaging')
            ->where('product_status',1)
            ->where('name','LIKE','%'.$product_name.'%')
            ->where('parent_category',$parent_category)->get();
            if(!$data->isEmpty()){
                $status =200;
                $imaging_products['code'] = $status;
                $imaging_products['imaging_products'] = $data;
                return $this->sendResponse($imaging_products,"Imaging Product Found");
            } else{
                $status =200;
                $dataCode['code'] = $status;
                return $this->sendError("Imaging Product Not Found",$dataCode);
            }
        } elseif($product_name){
            $data = DB::table('tbl_products')->where('mode','imaging')->where('product_status',1)
            ->where('name','LIKE','%'.$product_name.'%')->get();
            if(!$data->isEmpty()){
                $status =200;
                $data_product_name['code'] = $status;
                $data_product_name['imaging_product'] = $data;
                return $this->sendResponse($data_product_name,"Imaging Product Found");
            } else{
                $status =200;
                $dataCode['code'] = $status;
                return $this->sendError("Imaging Product Not Found",$dataCode);
            }
        } else{
            $data = DB::table('tbl_products')->where('mode','imaging')->where('product_status',1)->get();
            if(!$data->isEmpty()){
                $status =200;
                $tbl_products['code'] = $status;
                $tbl_products['imaging_product'] = $data;
                return $this->sendResponse($tbl_products,"Imaging Product Found");
            } else{
                $status =200;
                $dataCode['code'] = $status;
                return $this->sendError("Imaging Product Not Found",$dataCode);
            }
        }
    }
    public function popular_lab_test(){
        $lab_orders = DB::table('lab_orders as lo')
            ->join('quest_data_test_codes as qcode','qcode.TEST_CD','=','lo.product_id')
            ->select('qcode.TEST_NAME','qcode.TEST_CD','qcode.SALE_PRICE as price', DB::raw('count(*) as popularity'))
            ->groupBy('lo.product_id')
            // ->where('lo.user_id','=',Auth::user()->id)
            ->orderBy('popularity','DESC')
            ->limit(10)->get();
        $popular['code'] = 200;
        $popular['popular_lab'] = $lab_orders;
        return $this->sendResponse($popular,"List of popular labs");
    }
    public function product_detail($id)
    {
       $productDetail = DB::table('tbl_products')->where('id',$id)->first();  
       $productData['code'] = 200;
       $productData['productDetail'] = $productDetail;
       return $this->sendResponse($productData,'product detial');  
    }
    public function lab_detail($id)
    {
       $productDetail = DB::table('tbl_products')->where('id',$id)->first();  
       $productData['code'] = 200;
       $productData['productDetail'] = $productDetail;
       return $this->sendResponse($productData,'product detial');  
    }
}
