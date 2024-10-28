<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Repositories\ProductCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Image;
use Auth;
use App\ActivityLog;
use App\User;
use DB;

class ProductCategoryController extends AppBaseController
{
    /** @var  ProductCategoryRepository */
    private $productCategoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepo)
    {
        $this->productCategoryRepository = $productCategoryRepo;
    }

    /**
     * Display a listing of the ProductCategory.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $type = $this->getBasicTypes();

        //$productCategories = $this->productCategoryRepository->all();
        $productCategories = $this->productCategoryRepository->getProductCategories($type);

        //print_r($productCategories);
        return view('product_categories.index')->with('productCategories', $productCategories);
    }

    public function dash_index()
    {
        $type = $this->getBasicTypes();

        //$productCategories = $this->productCategoryRepository->all();
        $productCategories = $this->productCategoryRepository->getProductCategories($type);

        $user_type = Auth::user()->user_type;
        if($user_type == 'admin_pharm'){
            return view('dashboard_Pharm_admin.pharmacy_products.main_categories')->with('productCategories', $productCategories);
        }elseif($user_type == 'editor_pharmacy'){
            return view('dashboard_Pharm_editor.pharmacy_products.main_categories')->with('productCategories', $productCategories);
        }
    }

    public function dash_main_cat_store(Request $request)
    {
        // dd($request->all());
       DB::table('product_categories')->insert([
        'name' => $request->cat_name,
        'slug' => $this->slugify($request->cat_name),
        'category_type' => $request->cat_type,
       ]);

        Flash::success('Product Category updated successfully.');

        return redirect()->back();
    }

    public function dash_main_cat_update(Request $request)
    {
        $id = $request->edit_id;
        $productCategory = $this->productCategoryRepository->find($id);

       DB::table('product_categories')->where('id',$id)->update([
        'name' => $request->cat_name,
        'slug' => $this->slugify($request->cat_name),
        'category_type' => $request->cat_type,
       ]);

        Flash::success('Product Category updated successfully.');

        return redirect()->back();
    }
    public function dash_main_cat_delete(Request $request)
    {
        // dd($request);
        $id = $request->delete_id;
        $productCategory = $this->productCategoryRepository->find($id);

        DB::table('product_categories')->where('id',$id)->delete();

       Flash::success('Product Category updated successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a new ProductCategory.
     *
     * @return Response
     */
    public function create()
    {
        $user = auth()->user();
        return view('product_categories.create', compact('user'));
    }

    /**
     * Store a newly created ProductCategory in storage.
     *
     * @param CreateProductCategoryRequest $request
     *
     * @return Response
     */

    public function store(CreateProductCategoryRequest $request)
    {

        $file = $request->file('thumbnail');
        // dd($request['name']);
        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $img_name =  uniqid() . '.' . $extension;
            $path = 'uploads/' . $img_name;
            $img = Image::make($file);
            $img->save(public_path($path));
            $input = $request->all();
            $input['thumbnail'] = $img_name;
            $input['slug'] = $this->slugify($request->name);
        } else {
            $input = $request->all();
            $input['slug'] = $this->slugify($request->name);
        }

        $productCategory = $this->productCategoryRepository->create($input);
        ActivityLog::add_activity('added category ' . $request['name'], $productCategory['id'], 'product_category_created');

        Flash::success('Product Category saved successfully.');

        return redirect(route('productCategories.index'));
    }

    public function slugify($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    /**
     * Display the specified ProductCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $productCategory = $this->productCategoryRepository->find($id);

        if (empty($productCategory)) {
            Flash::error('Product Category not found');

            return redirect(route('productCategories.index'));
        }

        return view('product_categories.show')->with('productCategory', $productCategory);
    }

    /**
     * Show the form for editing the specified ProductCategory.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $productCategory = $this->productCategoryRepository->find($id);

        if (empty($productCategory)) {
            Flash::error('Product Category not found');

            return redirect(route('productCategories.index'));
        }

        $user = auth()->user();

        return view('product_categories.edit')->with(['productCategory' => $productCategory, 'user' => $user]);
    }

    /**
     * Update the specified ProductCategory in storage.
     *
     * @param int $id
     * @param UpdateProductCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductCategoryRequest $request)
    {
        $productCategory = $this->productCategoryRepository->find($id);

        if (empty($productCategory)) {
            Flash::error('Product Category not found');

            return redirect(route('productCategories.index'));
        }


        $file = $request->file('thumbnail');


        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $img_name =  uniqid() . '.' . $extension;
            $path = 'uploads/' . $img_name;
            $img = Image::make($file);
            $img->save(public_path($path));
            $input = $request->all();
            $input['thumbnail'] = $img_name;
            $input['slug'] = $this->slugify($request->name);
        } else {
            $input = $request->all();
            $input['slug'] = $this->slugify($request->name);
        }

        $productCategory = $this->productCategoryRepository->update($input, $id);

        Flash::success('Product Category updated successfully.');

        return redirect(route('productCategories.index'));
    }


    /**
     * Remove the specified ProductCategory from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        DB::table('product_categories')->where('id', $id)->delete();

        Flash::success('Product Category deleted successfully.');

        return redirect(route('productCategories.index'));
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
        } else if (Auth::user()->user_type === 'admin_pharm') {
            $type = "medicine";
            $panel = "0";
        } else if (Auth::user()->user_type === 'admin_lab') {
            $type = "lab-test";
            $panel = "0";
        } else if (Auth::user()->user_type === 'admin_imaging') {
            $type = "imaging";
            $panel = "0";
        }

        $data = array(
            'type' => $type,
            'panel-test' => $panel
        );

        return $data;
    }

    //Lab_Admin_new_functions

    public function lab_test_categories()
    {
        $type = $this->getBasicTypes();

        //$productCategories = $this->productCategoryRepository->all();
        $productCategories = DB::table('product_categories')->select('*')->where('category_type', '=', $type)->paginate(10);
        $data = DB::table('product_categories')->select('*')->where('category_type', '=', $type)->get()->toArray();
        $data = json_encode($data);
        if(auth()->user()->user_type == 'admin_lab')
        {
            return view('dashboard_Lab_admin.Lab_Tests.Lab_test_categories',compact('productCategories','data'));
        }
        elseif(auth()->user()->user_type == 'editor_lab')
        {
            return view('dashboard_Lab_editor.Lab_Tests.Lab_test_categories',compact('productCategories','data'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function update_lab_cat(Request $request)
    {
        $productCategory = $this->productCategoryRepository->find($request->id);

        if (empty($productCategory)) {
            Flash::error('Product Category not found');

            return redirect()->back()->with('Product Category not found');
        }


        $file = $request->file('thumbnail');


        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $img_name =  uniqid() . '.' . $extension;
            $path = 'uploads/' . $img_name;
            $img = Image::make($file);
            $img->save(public_path($path));
            $input = $request->all();
            $input['thumbnail'] = $img_name;
            $input['slug'] = $this->slugify($request->name);
        } else {
            $input = $request->all();
            $input['slug'] = $this->slugify($request->name);
        }

        $productCategory = $this->productCategoryRepository->update($input, $request->id);

        Flash::success('Product Category updated successfully.');

        return redirect(url()->previous());
    }

    public function create_lab_cat(Request $request)
    {
        $file = $request->file('thumbnail');
        // dd($request['name']);
        if (!empty($file)) {
            $orignalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $img_name =  uniqid() . '.' . $extension;
            $path = 'uploads/' . $img_name;
            $img = Image::make($file);
            $img->save(public_path($path));
            $input = $request->all();
            $input['thumbnail'] = $img_name;
            $input['slug'] = $this->slugify($request->name);
        } else {
            $input = $request->all();
            $input['slug'] = $this->slugify($request->name);
        }

        $productCategory = $this->productCategoryRepository->create($input);
        ActivityLog::add_activity('added category ' . $request['name'], $productCategory['id'], 'product_category_created');

        Flash::success('Product Category saved successfully.');
        return redirect(url()->previous());

    }

    public function del_lab_cat(Request $request)
    {
        DB::table('product_categories')->where('id', $request->id)->delete();
        $productCategory = $this->productCategoryRepository->create($request->all());
        ActivityLog::add_activity('Deleted category with Id ' . $request->id, $productCategory['id'], 'product_category_deleted');
        Flash::success('Product Category deleted successfully.');
        return redirect(url()->previous());
    }

    //imaging admin new functions
    public function imaging_lab_categories(Request $request)
    {
        $type = $this->getBasicTypes();
        //$productCategories = $this->productCategoryRepository->all();
        $productCategories = DB::table('product_categories')->select('*')->where('category_type', '=', $type)->paginate(10);
        $data = DB::table('product_categories')->select('*')->where('category_type', '=', $type)->get()->toArray();
        $data = json_encode($data);
        if(auth()->user()->user_type == 'admin_imaging')
        {
            return view('dashboard_imaging_admin.imaging_categories.categories',compact('productCategories','data'));
        }
        elseif(auth()->user()->user_type == 'editor_imaging')
        {
            return view('dashboard_imaging_editor.imaging_categories.categories',compact('productCategories','data'));
        }
        else
        {
            return redirect()->back();
        }
    }
    public function imaging_lab_order_file(Request $request)
    {
        $type = $this->getBasicTypes();
        //$productCategories = $this->productCategoryRepository->all();
        $med = DB::table('imaging_file')->paginate(10);
        foreach ($med as $m) {
            $m->names = DB::table('imaging_orders')
            ->join('tbl_products','imaging_orders.product_id','tbl_products.id')
            ->where('imaging_orders.order_id',$m->order_id)
            ->select('tbl_products.name')->get();
            $doc = DB::table('users')->where('id',$m->doctor_id)->first();
            $m->doc = $doc->name.' '.$doc->last_name;
            $m->created_at = User::convert_utc_to_user_timezone(auth()->user()->id, $m->created_at);
            $m->created_at = $m->created_at['date'] . ' ' . $m->created_at['time'];
        }
        if(auth()->user()->user_type == 'admin_imaging'){
            return view('dashboard_imaging_admin.imaging_categories.imaging_file',compact('med'));
        }else{
            return redirect()->back();
        }
    }

    public function add_imaging_category(Request $request)
    {
        $slug = $this->slugify($request->name);
        //$productCategories = $this->productCategoryRepository->all();
        $pro_cat_id = DB::table('product_categories')->insertGetId([
            'name' => $request->name,
            'slug' => $slug,
            'category_type' => 'imaging',
            'thumbnail' => 'default-imaging.png',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id,
        ]);
        ActivityLog::add_activity('added category ' . $request->name, $pro_cat_id, 'product_category_created');

        Flash::success('Product Category saved successfully.');
        return redirect(url()->previous());
    }

    public function edit_imaging_category(Request $request)
    {
        $slug = $this->slugify($request->name);
        //$productCategories = $this->productCategoryRepository->all();
        $pro_cat_id = DB::table('product_categories')->where('id',$request->id)->update([
            'name' => $request->name,
            'slug' => $slug,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Flash::success('Product Category updated successfully.');
        return redirect(url()->previous());
    }

    public function del_imaging_category(Request $request)
    {
        DB::table('product_categories')->where('id',$request->id)->delete();
        return redirect()->back()->with(['msg'=>'Product Category deleted successfully.']);
    }
}
