<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductsSubCategoryRequest;
use App\Http\Requests\UpdateProductsSubCategoryRequest;
use App\Repositories\ProductsSubCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Image;
use App\User;
use DB;
use Auth;
use App\ActivityLog;
use App\Models\ProductsSubCategory;

class ProductsSubCategoryController extends AppBaseController
{
    /** @var  ProductsSubCategoryRepository */
    private $productsSubCategoryRepository;

    public function __construct(ProductsSubCategoryRepository $productsSubCategoryRepo)
    {
        $this->productsSubCategoryRepository = $productsSubCategoryRepo;
    }

    /**
     * Display a listing of the ProductsSubCategory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // $productsSubCategories = $this->productsSubCategoryRepository->all();
        $type = $this->getBasicTypes();

        $productsSubCategories = $this->productsSubCategoryRepository->getDataWithName($type);


        // dd($productsSubCategories);
        // die;

        return view('products_sub_categories.index')->with('productsSubCategories', $productsSubCategories);
    }

    public function dash_index(Request $request)
    {
        // $productsSubCategories = $this->productsSubCategoryRepository->all();
        $type = $this->getBasicTypes();

        $productsSubCategories = DB::table('products_sub_categories')
        ->join('product_categories', 'products_sub_categories.parent_id', '=', 'product_categories.id')
        ->select('products_sub_categories.*', 'product_categories.name as parent_name', 'product_categories.id as parent_id')
        ->where('product_categories.category_type', '=', $type['type'])
        ->paginate(10);
        $data = DB::table('products_sub_categories')
        ->join('product_categories', 'products_sub_categories.parent_id', '=', 'product_categories.id')
        ->select('products_sub_categories.*', 'product_categories.name as parent_name', 'product_categories.id as parent_id')
        ->where('product_categories.category_type', '=', $type['type'])
        ->get()->toArray();

        $user = Auth::user();
        foreach ($productsSubCategories as $pc) {
            $pc->created_at = User::convert_utc_to_user_timezone($user->id,$pc->created_at);
            $pc->created_at = $pc->created_at['date']." ".$pc->created_at['time'];
            $pc->updated_at = User::convert_utc_to_user_timezone($user->id,$pc->updated_at);
            $pc->updated_at = $pc->updated_at['date']." ".$pc->updated_at['time'];
        }

        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.pharmacy_products.sub_categories', compact('data'))->with('productsSubCategories', $productsSubCategories);
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.pharmacy_products.sub_categories', compact('data'))->with('productsSubCategories', $productsSubCategories);
        }
    }

    /**
     * Show the form for creating a new ProductsSubCategory.
     *
     * @return Response
     */
    public function create()
    {
        $user = auth()->user();
        return view('products_sub_categories.create', compact('user'));
    }

    /**
     * Store a newly created ProductsSubCategory in storage.
     *
     * @param CreateProductsSubCategoryRequest $request
     *
     * @return Response
     */
    // public function store(CreateProductsSubCategoryRequest $request)
    // {
    //     $input = $request->all();

    //     $productsSubCategory = $this->productsSubCategoryRepository->create($input);

    //     Flash::success('Products Sub Category saved successfully.');

    //     return redirect(route('productsSubCategories.index'));
    // }

    public function store(CreateProductsSubCategoryRequest $request)
    {
        // dd($request);
        $file = $request->file('thumbnail');

        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/' . uniqid() . '.' . $extension;
            $img = Image::make($file);
            $img->save(public_path($path));

            $input = $request->all();
            $input['thumbnail'] = $path;
            $input['slug'] = $this->slugify($request->title);
        } else {
            $input = $request->all();
            $input['slug'] = $this->slugify($request->title);
        }

        $productsSubCategory = $this->productsSubCategoryRepository->create($input);
        ActivityLog::add_activity('added category ' . $request['title'], $productsSubCategory['id'], 'product_sub_category_created');

        Flash::success('Product Category saved successfully.');

        return redirect(route('productsSubCategories.index'));
    }

    public function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    /**
     * Display the specified ProductsSubCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {

        // $productsSubCategory = $this->productsSubCategoryRepository->find($id);
        $productsSubCategory = $this->productsSubCategoryRepository->getDataWithNameWithId($id);
        // print_r($productsSubCategory);

        if (empty($productsSubCategory)) {
            Flash::error('Products Sub Category not found');

            return redirect(route('productsSubCategories.index'));
        }

        return view('products_sub_categories.show')->with('productsSubCategory', $productsSubCategory);
    }

    /**
     * Show the form for editing the specified ProductsSubCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $productsSubCategory = $this->productsSubCategoryRepository->find($id);

        if (empty($productsSubCategory)) {
            Flash::error('Products Sub Category not found');

            return redirect(route('productsSubCategories.index'));
        }


        $user = auth()->user();

        //return view('product_categories.edit')->with(['productCategory' => $productCategory, 'user'=> $user]);
        return view('products_sub_categories.edit')->with(['productsSubCategory' => $productsSubCategory, 'user' => $user]);
    }

    /**
     * Update the specified ProductsSubCategory in storage.
     *
     * @param int $id
     * @param UpdateProductsSubCategoryRequest $request
     *
     * @return Response
     */
    // public function update($id, UpdateProductsSubCategoryRequest $request)
    // {
    //     $productsSubCategory = $this->productsSubCategoryRepository->find($id);

    //     if (empty($productsSubCategory)) {
    //         Flash::error('Products Sub Category not found');

    //         return redirect(route('productsSubCategories.index'));
    //     }

    //     $productsSubCategory = $this->productsSubCategoryRepository->update($request->all(), $id);

    //     Flash::success('Products Sub Category updated successfully.');

    //     return redirect(route('productsSubCategories.index'));
    // }

    public function update($id, UpdateProductsSubCategoryRequest $request)
    {
        $productCategory = $this->productsSubCategoryRepository->find($id);

        if (empty($productCategory)) {
            Flash::error('Product Category not found');

            return redirect(route('productCategories.index'));
        }


        $file = $request->file('thumbnail');

        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/' . uniqid() . '.' . $extension;
            $img = Image::make($file);
            $img->save(public_path($path));

            $input = $request->all();
            $input['thumbnail'] = $path;
            $input['slug'] = $this->slugify($request->title);
        } else {
            $input = $request->all();
            $input['slug'] = $this->slugify($request->title);
        }

        $productsSubCategory = $this->productsSubCategoryRepository->update($input, $id);

        Flash::success('Product Category updated successfully.');

        return redirect(route('productCategories.index'));
    }



    /**
     * Remove the specified ProductsSubCategory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $productsSubCategory = $this->productsSubCategoryRepository->find($id);

        if (empty($productsSubCategory)) {
            Flash::error('Products Sub Category not found');

            return redirect(route('productsSubCategories.index'));
        }

        ProductsSubCategory::where([
            'id' => $id
        ])->delete();
        /// $this->productsSubCategoryRepository->delete($id);

        Flash::success('Products Sub Category deleted successfully.');

        return redirect(route('productsSubCategories.index'));
    }

    public function dash_sub_cat_delete(Request $request)
    {
        $id = $request->delete_id;
        // $productCategory = $this->productCategoryRepository->find($id);
        // dd($id);

        DB::table('products_sub_categories')->where('id',$id)->delete();

       Flash::success('Product Category updated successfully.');

        return redirect()->back();
    }
    public function dash_sub_cat_update(Request $request)
    {
        $id = $request->edit_id;
        if (isset($request->is_featured)){
            DB::table('products_sub_categories')->where('id',$id)->update([
                'title' => $request->title,
                'slug' => $this->slugify($request->title),
                'description' => $request->description,
                'parent_id' => $request->sub_cat,
                'is_featured' => 1,
            ]);
        } else {
            DB::table('products_sub_categories')->where('id',$id)->update([
                'title' => $request->title,
                'slug' => $this->slugify($request->title),
                'description' => $request->description,
                'parent_id' => $request->sub_cat,
                'is_featured' => 0,
           ]);
        }
        Flash::success('Product Category updated successfully.');

        return redirect()->back();
    }

    public function dash_sub_cat_store(Request $request)
    {
        // dd($request->all());
        if (isset($request->is_featured)){
            DB::table('products_sub_categories')->insert([
                'title' => $request->title,
                'slug' => $this->slugify($request->title),
                'description' => $request->description,
                'parent_id' => $request->sub_cat,
                'is_featured' => 1,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ]);
        }else{
            DB::table('products_sub_categories')->insert([
                'title' => $request->title,
                'slug' => $this->slugify($request->title),
                'description' => $request->description,
                'parent_id' => $request->sub_cat,
                'is_featured' => 0,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ]);
        }
        Flash::success('Product Category updated successfully.');

        return redirect()->back();
    }

    public function getMainCategories(){
        $data = DB::table('product_categories')->where('category_type','medicine')->get();
        return $data;
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
        } else if (Auth::user()->user_type === 'admin_pharm') {
            $type = "medicine";
            $panel = "0";
        }

        $data = array(
            'type' => $type,
            'panel-test' => $panel
        );

        return $data;
    }
}
