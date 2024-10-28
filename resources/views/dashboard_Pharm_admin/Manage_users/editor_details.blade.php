@extends('layouts.dashboard_Pharm_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>Editor Details</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script>
    $(document).ready(function(){
        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            adminfetch_data(page);
        });

        function adminfetch_data(page){
            var _id = '{{$user->id}}';
            $.ajax({
            url:"/admin/pagination/fetch_data?page="+page,
            data: {
                user_id: _id,
            },
            success:function(data)
            {
                $('#table_data').html(data);
            }
            });
        }
    });
</script>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-baseline justify-content-between flex-wrap p-0">
                            <div>
                                <h3>Editors Details</h3>
                            </div>
                            <div class="col-12 col-sm-7 col-md-5 p-0">
                                <div class="d-flex justify-content-between">
                                    @if ($user->status == 'deactivate')
                                        <button class="btn btn-success w-100 me-2" data-bs-toggle="modal"
                                            data-bs-target="#deactivate_user">Activate</button>
                                    @else
                                        <button class="btn btn-danger w-100 me-2" data-bs-toggle="modal"
                                            data-bs-target="#deactivate_user">Deactivate</button>
                                    @endif
                                    <button class="btn process-pay w-100" data-bs-toggle="modal"
                                        data-bs-target="#send_email_user">Send Email</button>
                                </div>
                            </div>
                        </div>
                        <div class="p-0">
                            <div class="pt-2">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">Personal Information</button>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"
                                            role="button" aria-expanded="false">Items</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" id="product-tab"
                                                    data-bs-toggle="tab" data-bs-target="#product" type="button"
                                                    role="tab" aria-controls="product" aria-selected="true">Product</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" id="categories-tab"
                                                    data-bs-toggle="tab" data-bs-target="#categories" type="button"
                                                    role="tab" aria-controls="categories"
                                                    aria-selected="true">Categories</a></li>
                                            <li><a class="dropdown-item" href="#" id="subcategories-tab"
                                                    data-bs-toggle="tab" data-bs-target="#subcategories" type="button"
                                                    role="tab" aria-controls="subcategories"
                                                    aria-selected="true">Subcategories</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab"
                                            data-bs-target="#activity" type="button" role="tab"
                                            aria-controls="activity" aria-selected="false">Activity Log</button>
                                    </li>
                                </ul>
                                <div class="tab-content tab-style  py-2" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="row m-auto">
                                            <div>
                                                <h5>Name: <span>{{ $user->name }} {{ $user->last_name }}</span></h5>
                                            </div>
                                            <div>
                                                <h5>State: <span>{{ $user->state }}</span></h5>
                                            </div>
                                            <div>
                                                <h5>Phone: <span>{{ $user->phone_number }}</span></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="product" role="tabpanel"
                                        aria-labelledby="product-tab">
                                        <div class="row m-auto">
                                            <div class="">
                                                <div>
                                                    <h4 class="items-heading-style mt-2">PRODUCTS</h4>

                                                </div>

                                                <table class="table">
                                                    <thead>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Category Name</th>
                                                        <th scope="col">Sale Price</th>
                                                        <th scope="col">Action</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($allProducts as $pro)
                                                            <tr>
                                                                <td data-label="Name" scope="row">{{ $pro->name }}
                                                                </td>
                                                                @foreach ($pro->cat_names as $cat_name)
                                                                    <td data-label="Category Name">{{ $cat_name['name'] }}
                                                                    </td>
                                                                @endforeach
                                                                <td data-label="Sale Price">{{ $pro->sale_price }}</td>
                                                                <td data-label="Action">
                                                                    <button type="button" class="btn orders-view-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#product_view_details">View</button>

                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan='4'>
                                                                    <div class="m-auto text-center for-empty-div">
                                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                                            alt="">
                                                                        <h6> No products</h6>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                        <tr>
                                                </table>
                                                <div class="row d-flex justify-content-center">
                                                    <div class="paginateCounter">
                                                        {{ $allProducts->links('pagination::bootstrap-4') }}
                                                    </div>
                                                </div>
                                                <!-- <nav aria-label="..." class="float-end pe-3">
                                            <ul class="pagination">
                                              <li class="page-item disabled">
                                                <span class="page-link">Previous</span>
                                              </li>
                                              <li class="page-item">
                                                <a class="page-link" href="#">1</a>
                                              </li>
                                              <li class="page-item active" aria-current="page">
                                                <span class="page-link">2</span>
                                              </li>
                                              <li class="page-item">
                                                <a class="page-link" href="#">3</a>
                                              </li>
                                              <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                              </li>
                                            </ul>
                                          </nav> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade show" id="categories" role="tabpanel"
                                        aria-labelledby="categories-tab">
                                        <div class="row m-auto">
                                            <div class="">
                                                <h4 class="items-heading-style mt-2">PRODUCT CATEGORIES</h4>
                                                <table class="table">
                                                    <thead>
                                                        <th scope="col">ID</th>
                                                        <th scope="col">Name</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($productCategories as $pc)
                                                            <tr>
                                                                <td data-label="ID" scope="row">{{ $pc->id }}
                                                                </td>
                                                                <td data-label="Name">{{ $pc->name }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan='2'>
                                                                    <div class="m-auto text-center for-empty-div">
                                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                                            alt="">
                                                                        <h6> No products category</h6>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                </table>
                                                <div class="row d-flex justify-content-center">
                                                    <div class="paginateCounter">
                                                        {{ $productCategories->links('pagination::bootstrap-4') }}
                                                    </div>
                                                </div>
                                                <!-- <nav aria-label="..." class="float-end pe-3">
                                        <ul class="pagination">
                                          <li class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                          </li>
                                          <li class="page-item">
                                            <a class="page-link" href="#">1</a>
                                          </li>
                                          <li class="page-item active" aria-current="page">
                                            <span class="page-link">2</span>
                                          </li>
                                          <li class="page-item">
                                            <a class="page-link" href="#">3</a>
                                          </li>
                                          <li class="page-item">
                                            <a class="page-link" href="#">Next</a>
                                          </li>
                                        </ul>
                                      </nav> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="subcategories" role="tabpanel"
                                        aria-labelledby="subcategories-tab">
                                        <div class="row m-auto">
                                            <div class="">
                                                <h4 class="items-heading-style mt-2">PRODUCTS SUBCATEGORIES</h4>

                                                <table class="table">
                                                    <thead>
                                                        <th scope="col">Sub ID</th>
                                                        <th scope="col">Sub Title</th>
                                                        <th scope="col">Main ID</th>
                                                        <th scope="col">Main Title</th>
                                                        <th scope="col">Action</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($productsSubCategories as $psc)
                                                            <tr>
                                                                <td data-label="Sub ID" scope="row">
                                                                    {{ $psc->id }}</td>
                                                                <td data-label="Sub Title">{{ $psc->title }}</td>
                                                                <td data-label="Main ID">{{ $psc->parent_id }}</td>
                                                                <td data-label="Main Title">{{ $psc->parent_name }}</td>
                                                                <td data-label="Action">
                                                                    <button type="button" class="btn orders-view-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#product_subcategory_details">View</button>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan='5'>
                                                                    <div class="m-auto text-center for-empty-div">
                                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                                            alt="">
                                                                        <h6> No products sub category</h6>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                </table>
                                                <div class="row d-flex justify-content-center">
                                                    <div class="paginateCounter">
                                                        {{ $productsSubCategories->links('pagination::bootstrap-4') }}
                                                    </div>
                                                </div>
                                                <!-- <nav aria-label="..." class="float-end pe-3">
                                      <ul class="pagination">
                                        <li class="page-item disabled">
                                          <span class="page-link">Previous</span>
                                        </li>
                                        <li class="page-item">
                                          <a class="page-link" href="#">1</a>
                                        </li>
                                        <li class="page-item active" aria-current="page">
                                          <span class="page-link">2</span>
                                        </li>
                                        <li class="page-item">
                                          <a class="page-link" href="#">3</a>
                                        </li>
                                        <li class="page-item">
                                          <a class="page-link" href="#">Next</a>
                                        </li>
                                      </ul>
                                    </nav> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="row m-auto">
                                            <div class="col-md-3">
                                                <!-- Tabs nav -->
                                                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab"
                                                    role="tablist" aria-orientation="vertical">
                                                    <a class="nav-link mb-3 p-3 active shadow" id="v-pills-settings-tab"
                                                        data-bs-toggle="pill" data-bs-target="#v-pills-settings"
                                                        type="button" role="tab" aria-controls="v-pills-settings"
                                                        aria-selected="false">
                                                        <i class="fa-solid fa-notes-medical me-2"></i>
                                                        <span
                                                            class="font-weight-bold small text-uppercase">Products</span></a>

                                                    <a class="nav-link mb-3 p-3 shadow" id="v-pills-lab-tab"
                                                        data-bs-toggle="pill" data-bs-target="#v-pills-lab"
                                                        type="button" role="tab" aria-controls="v-pills-lab"
                                                        aria-selected="false">
                                                        <i class="fa-solid fa-flask me-2"></i>
                                                        <span class="font-weight-bold small text-uppercase">Product
                                                            Categories</span></a>

                                                    <a class="nav-link mb-3 p-3 shadow" id="v-pills-imaging-tab"
                                                        data-bs-toggle="pill" data-bs-target="#v-pills-imaging"
                                                        type="button" role="tab" aria-controls="v-pills-imaging"
                                                        aria-selected="false">
                                                        <i class="fa-solid fa-x-ray"></i>
                                                        <span class="font-weight-bold small text-uppercase">Product
                                                            Subcategories</span></a>
                                                </div>
                                            </div>

                                            <div class="col-md-9">
                                                <!-- Tabs content -->
                                                <div class="tab-content" id="v-pills-tabContent">


                                                    <div class="tab-pane fade shadow rounded show active bg-white p-4"
                                                        id="v-pills-settings" role="tabpanel"
                                                        aria-labelledby="v-pills-settings-tab">
                                                        <div class="row m-auto">
                                                            <div class="">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">Name</th>
                                                                            <th scope="col">Category Name</th>
                                                                            <th scope="col">Sale Price</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">MiraLAX Laxative Powder</th>
                                                                            <td>Pain & Fever</td>

                                                                            <td>11</td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button
                                                                                        class="btn option-view-btn dropdown-toggle"
                                                                                        type="button"
                                                                                        id="dropdownMenuButton1"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                        OPTIONS
                                                                                    </button>
                                                                                    <ul class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuButton1">
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#add_specailization_price">View</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#add_specailization_price">Edit</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#delete_specialization">Deactivate</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">MiraLAX Laxative Powder</th>
                                                                            <td>Pain & Fever</td>

                                                                            <td>11</td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button
                                                                                        class="btn option-view-btn dropdown-toggle"
                                                                                        type="button"
                                                                                        id="dropdownMenuButton1"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                        OPTIONS
                                                                                    </button>
                                                                                    <ul class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuButton1">
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#add_specailization_price">View</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#add_specailization_price">Edit</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#delete_specialization">Deactivate</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                </table>
                                                                <nav aria-label="..." class="float-end pe-3">
                                                                    <ul class="pagination">
                                                                        <li class="page-item disabled">
                                                                            <span class="page-link">Previous</span>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">1</a>
                                                                        </li>
                                                                        <li class="page-item active" aria-current="page">
                                                                            <span class="page-link">2</span>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">3</a>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">Next</a>
                                                                        </li>
                                                                    </ul>
                                                                </nav>
                                                            </div>
                                                        </div>



                                                    </div>

                                                    <div class="tab-pane fade shadow rounded bg-white p-4"
                                                        id="v-pills-lab" role="tabpanel"
                                                        aria-labelledby="v-pills-lab-tab">
                                                        <div class="row m-auto">
                                                            <div class="">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">ID</th>
                                                                            <th scope="col">Name</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">39</th>
                                                                            <td>Pain Management</td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button
                                                                                        class="btn option-view-btn dropdown-toggle"
                                                                                        type="button"
                                                                                        id="dropdownMenuButton1"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                        OPTIONS
                                                                                    </button>
                                                                                    <ul class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuButton1">
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#edit_product_category">Edit</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#delete_specialization">Delete</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">39</th>
                                                                            <td>Pain Management</td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button
                                                                                        class="btn option-view-btn dropdown-toggle"
                                                                                        type="button"
                                                                                        id="dropdownMenuButton1"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                        OPTIONS
                                                                                    </button>
                                                                                    <ul class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuButton1">
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#edit_product_category">Edit</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#delete_specialization">Delete</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                </table>
                                                                <nav aria-label="..." class="float-end pe-3">
                                                                    <ul class="pagination">
                                                                        <li class="page-item disabled">
                                                                            <span class="page-link">Previous</span>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">1</a>
                                                                        </li>
                                                                        <li class="page-item active" aria-current="page">
                                                                            <span class="page-link">2</span>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">3</a>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">Next</a>
                                                                        </li>
                                                                    </ul>
                                                                </nav>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="tab-pane fade shadow rounded bg-white p-4"
                                                        id="v-pills-imaging" role="tabpanel"
                                                        aria-labelledby="v-pills-imaging-tab">
                                                        <div class="row m-auto">
                                                            <div class="">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">Sub ID</th>
                                                                            <th scope="col">Sub Title</th>
                                                                            <th scope="col">Main ID</th>
                                                                            <th scope="col">Main Title</th>
                                                                            <th scope="col">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">173</th>
                                                                            <td>Neck And Back Pain</td>
                                                                            <td>39</td>
                                                                            <td>asdasdsadasd</td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button
                                                                                        class="btn option-view-btn dropdown-toggle"
                                                                                        type="button"
                                                                                        id="dropdownMenuButton1"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                        OPTIONS
                                                                                    </button>
                                                                                    <ul class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuButton1">
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#add_specailization_price">View</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#edit_product_subcategory">Edit</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#delete_specialization">Deactivate</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">173</th>
                                                                            <td>Neck And Back Pain</td>
                                                                            <td>39</td>
                                                                            <td>asdasdsadasd</td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button
                                                                                        class="btn option-view-btn dropdown-toggle"
                                                                                        type="button"
                                                                                        id="dropdownMenuButton1"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-expanded="false">
                                                                                        OPTIONS
                                                                                    </button>
                                                                                    <ul class="dropdown-menu"
                                                                                        aria-labelledby="dropdownMenuButton1">
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#add_specailization_price">View</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#edit_product_subcategory">Edit</a>
                                                                                        </li>
                                                                                        <li><a class="dropdown-item"
                                                                                                href="#"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#delete_specialization">Deactivate</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                </table>
                                                                <nav aria-label="..." class="float-end pe-3">
                                                                    <ul class="pagination">
                                                                        <li class="page-item disabled">
                                                                            <span class="page-link">Previous</span>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">1</a>
                                                                        </li>
                                                                        <li class="page-item active" aria-current="page">
                                                                            <span class="page-link">2</span>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">3</a>
                                                                        </li>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="#">Next</a>
                                                                        </li>
                                                                    </ul>
                                                                </nav>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                        aria-labelledby="nav-contact-tab">
                                        <div class="row m-auto">
                                            <div class="">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Email</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">Muhammad Ahmer</th>
                                                            <td>ahmer@gamil.com</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn option-view-btn dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton1"
                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                        OPTIONS
                                                                    </button>
                                                                    <ul class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton1">
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#add_specailization_price">View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#add_specailization_price">Send
                                                                                Email</a></li>
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#delete_specialization">Deactivate</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Muhammad Ahmer</th>
                                                            <td>ahmer@gamil.com</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn option-view-btn dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton1"
                                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                                        OPTIONS
                                                                    </button>
                                                                    <ul class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton1">
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#add_specailization_price">View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#add_specailization_price">Send
                                                                                Email</a></li>
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#delete_specialization">Deactivate</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                </table>
                                                <nav aria-label="..." class="float-end pe-3">
                                                    <ul class="pagination">
                                                        <li class="page-item disabled">
                                                            <span class="page-link">Previous</span>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#">1</a>
                                                        </li>
                                                        <li class="page-item active" aria-current="page">
                                                            <span class="page-link">2</span>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#">3</a>
                                                        </li>
                                                        <li class="page-item">
                                                            <a class="page-link" href="#">Next</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="activity" role="tabpanel"
                                        aria-labelledby="activity-tab">
                                        <div class="row m-auto">
                                            <div class="">
                                                <div id="table_data">
                                                    @include('dashboard_admin.doctors.all_doctors.pagination_data')
                                                </div>
                                                {{-- <table class="table">
                                                    <thead>
                                                        <th scope="col">Activity</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Time</th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($activities as $act)
                                                            <tr>
                                                                <td data-label="Activity">{{ ucwords($act->activity) }}
                                                                </td>
                                                                <td data-label="Date">{{ $act->date }}</td>
                                                                <td data-label="Time">{{ $act->time }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan='3'>
                                                                    <div class="m-auto text-center for-empty-div">
                                                                        <img src="{{ asset('assets/images/for-empty.png') }}"
                                                                            alt="">
                                                                        <h6> No Activities</h6>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="row d-flex justify-content-center">
                                                    <div class="paginateCounter">
                                                        {{ $activities->links('pagination::bootstrap-4') }}
                                                    </div>
                                                </div> --}}
                                                <!-- <nav aria-label="..." class="float-end pe-3">
                                          <ul class="pagination">
                                            <li class="page-item disabled">
                                              <span class="page-link">Previous</span>
                                            </li>
                                            <li class="page-item">
                                              <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item active" aria-current="page">
                                              <span class="page-link">2</span>
                                            </li>
                                            <li class="page-item">
                                              <a class="page-link" href="#">3</a>
                                            </li>
                                            <li class="page-item">
                                              <a class="page-link" href="#">Next</a>
                                            </li>
                                          </ul>
                                        </nav> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    </div>

    <!-- ------------------Product-View-Details-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="product_view_details" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Product View Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">ID:</span> <span>123</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Name:</span> <span>Anotomy</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Parent Category:</span> <span>sdasada</span></p>

                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Sub Category:</span> <span>rtrteerww</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Sale Price:</span> <span>7</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Regular Price:</span> <span>10</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Created At:</span> <span>2020-11-24</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Updated At:</span> <span>2020-11-24</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">Short Description:</span> <span>Tylenol is used to reduce
                                            fever and relieve minor pain caused by conditions such as colds or
                                            flu,&nbsp;headache, muscle aches,&nbsp;arthritis, menstrual cramps
                                            and&nbsp;fevers.</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">Description:</span> <span>Lorem ipsum dolor sit amet
                                            consectetur adipisicing elit. Minima velit sint at. Voluptate atque, excepturi
                                            sequi dolorem tempora nesciunt, non iusto ad cum aliquam soluta! Incidunt enim
                                            cumque molestiae? Voluptate quam aut quos ab vitae. Numquam nihil sed enim
                                            aliquid.</span></p>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Product-View-Details-Modal-end------------------ -->


    <!-- ------------------Product-SubCategory-Details-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="product_subcategory_details" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Subcategory View Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Title:</span> <span>sdasdas</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Parent Name:</span> <span>kkjhjhl</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Created At:</span> <span>2020-11-24</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Updated At:</span> <span>2020-11-24</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">Description:</span> <span>Lorem ipsum dolor sit amet
                                            consectetur adipisicing elit. Minima velit sint at. Voluptate atque, excepturi
                                            sequi dolorem tempora nesciunt, non iusto ad cum aliquam soluta! Incidunt enim
                                            cumque molestiae? Voluptate quam aut quos ab vitae. Numquam nihil sed enim
                                            aliquid.</span></p>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Product-SubCategory-Details-Modal-end------------------ -->

    <!-- ------------------Block-User-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="deactivate_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deactivate User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal-body">
                        Are you sure you want to Deactivate this User?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="window.location.href='/lab_editor/change_status/{{ $user->id }}'"
                        class="btn btn-danger">Deactivate</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Block-User-Modal-end------------------ -->

    <!-- ------------------Send-Email-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="send_email_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/send_email" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Send Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">To</label>
                                    <input type="text" id="email" name="email" value="{{ $user->email }}"
                                        class="form-control" readonly placeholder="xyx@gmail.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Subject</label>
                                    <input type="text" name="subject" class="form-control"
                                        placeholder="Enter Subject" required>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label for="email_body">Email Body</label>
                                    <textarea class="form-control" name="ebody" id="email_body" rows="3" placeholder="Type your email message"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ------------------Send-Email-Modal-end------------------ -->

    <!-- ------------------Edit-Product-Categories-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit_product_category" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" class="form-control" placeholder="Pain Management">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Category Type</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected>Medicine</option>
                                        <option value="1">Lab-Test</option>
                                        <option value="2">Substance-Abuse</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!-- ------------------Edit-Product-Categories-Modal-end------------------ -->


    <!-- ------------------Product-SubCategories-Edit-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit_product_subcategory" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Name</label>
                                    <input type="text" class="form-control" placeholder="Neck And Back Pain">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Parent Category</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected>Pain Management</option>
                                        <option value="1">CT Scan</option>
                                        <option value="2">MR</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description</label>
                                    <input type="text" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-12">
                                    <label for="specialInstructions">Thumbnail</label>
                                    <input type="file" class="form-control" placeholder="">
                                </div>
                            </div>


                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn process-pay">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- ------------------Product-SubCategories-Edit-Modal-end------------------ -->

<!-- Option 1: Bootstrap Bundle with Popper -->
