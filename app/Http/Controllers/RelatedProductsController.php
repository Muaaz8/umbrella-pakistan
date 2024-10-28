<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RelatedProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('related_products')
        ->join('quest_data_test_codes as q1','q1.TEST_CD','related_products.product_id')
        ->join('quest_data_test_codes as q2','q2.TEST_CD','related_products.related_product_ids')
        ->select('related_products.*','q1.TEST_NAME','q2.TEST_NAME as related_test_name')
        ->paginate(10);
        return view('dashboard_admin.related_products.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $labs = DB::table('quest_data_test_codes')
        ->where('TEST_CD','!=',null)
        ->where('TEST_NAME','!=',null)
        ->get();

        return view('dashboard_admin.related_products.Add',compact('labs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'related_product_ids' => 'required',
        ]);
        $input = $request->all();
        $input['product_type'] = "labtests";
        foreach($input['related_product_ids'] as $r_id){
            DB::table('related_products')->insert([
                'product_id' => $input['product_id'],
                'related_product_ids' => $r_id,
                'product_type' => $input['product_type'],
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ]);
        }
        return redirect()->route('related_products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('related_products')->where('id',$id)->delete();
        return redirect()->route('related_products.index');
    }
}
