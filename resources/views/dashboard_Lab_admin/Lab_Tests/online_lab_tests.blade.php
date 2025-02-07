@extends('layouts.dashboard_Lab_admin')
@section('meta_tags')
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title')
    <title>online Lab Tests</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
    <script src="//cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            enterMode: CKEDITOR.ENTER_BR,
            on: {
                'instanceReady': function(evt) {
                    evt.editor.execCommand('');
                }
            },
        });
        CKEDITOR.replace('details', {
            enterMode: CKEDITOR.ENTER_BR,
            on: {
                'instanceReady': function(evt) {
                    evt.editor.execCommand('');
                }
            },
        });
        CKEDITOR.replace('e_des', {
            enterMode: CKEDITOR.ENTER_BR,
            on: {
                'instanceReady': function(evt) {
                    evt.editor.execCommand('');
                }
            },
        });
        CKEDITOR.replace('e_de', {
            enterMode: CKEDITOR.ENTER_BR,
            on: {
                'instanceReady': function(evt) {
                    evt.editor.execCommand('');
                }
            },
        });
    </script>
    <script>
        @php header("Access-Control-Allow-Origin: *"); @endphp
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function view_online_lab(id) {
            var na = $('#na_' + id).val();
            var sl = $('#sl_' + id).val();
            var pr = $('#pr_' + id).val();
            var sp = $('#sp_' + id).val();
            var dis = $('#dis_' + id).val();
            var ac = $('#ac_' + id).val();
            var des = $('#des_' + id).val();
            var de = $('#de_' + id).val();
            var cat = $('#cat_' + id).val();
            var img = $('#img_' + id).val();
            var mo = $('#mo_' + id).val();

            $('#v_na').text(na);
            $('#v_sl').text(sl);
            $('#v_pr').text(pr);
            $('#v_sp').text(sp);
            $('#v_des').text(des);
            $('#v_dis').text(dis);
            $('#v_ac').text(ac);
            $('#v_dess').text(des);
            $('#v_de').html(de);
            $('#v_cat').text(cat);
            $('#v_kw').text(mo);
            $('#v_img').html('<img class="img-fluid" src="' + img + '" alt="" style="width: 122px; height: 105px;">');

            $('#view_online_labtest').modal('show');
        }

        function edit_online_lab(id) {
            var name = $('#na_' + id).val();
            var des = $('#des_' + id).val();
            var pr = $('#pr_' + id).val();
            var sp = $('#sp_' + id).val();
            var dis = $('#dis_' + id).val();
            var ac = $('#ac_' + id).val();
            var cat = $('#cn_' + id).val();
            var catt = "{{ $categories }}";
            var de = $('#de_' + id).val();

            console.log("DIS" , dis);
            console.log("AC" , ac);


            $('#e_id').val(id);
            $('#e_name').val(name);
            $('#e_pr').val(pr);
            $('#e_sp').val(sp);
            $('#e_dis').val(dis);
            $('#e_ac').val(ac);
            $('#e_cat').val(cat);
            $('#e_des').text(des);
            $('#e_de').text(de);
            CKEDITOR.instances['e_de'].setData(de);
            CKEDITOR.instances['e_des'].setData(des);
            $('#edit_online_test').modal('show');
        }

        function del_test(id) {
            $('#d_id').val(id);
            $('#delete_online_labtest').modal('show');

        }

        var input = document.getElementById("search");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                document.getElementById("search_btn").click();
            }
        });

        function search(array) {
            var val = $('#search').val();
            if (val == '') {
                window.location.href = '/online/lab/tests';
            } else {
                $('#bodies').empty();
                $('#pag').html('');
                $.each(array, function(key, arr) {
                    if ((arr.TEST_NAME != null && arr.TEST_NAME.match(val)) || (arr.main_category_name != null &&
                            arr.main_category_name.match(val)) ||
                        (arr.SALE_PRICE != null && arr.SALE_PRICE.toString().match(val))) {
                        $('#bodies').append('<tr id="body_' + arr.TEST_CD + '"></tr>');
                        $('#body_' + arr.TEST_CD).append('<td data-label="Test Code">' + arr.TEST_NAME + '</td>' +
                            '<td data-label="Service Name">' + arr.main_category_name + '</td>' +
                            '<td data-label="Sale Price">' + arr.SALE_PRICE + '</td>' +
                            '<input type="hidden" id="des_' + arr.TEST_CD + '" value="' + arr.DESCRIPTION +
                            '">' +
                            '<input type="hidden" id="na_' + arr.TEST_CD + '" value="' + arr.TEST_NAME + '">' +
                            '<input type="hidden" id="pr_' + arr.TEST_CD + '" value="' + arr.PRICE + '">' +
                            '<input type="hidden" id="sp_' + arr.TEST_CD + '" value="' + arr.SALE_PRICE + '">' +
                            '<input type="hidden" id="cn_' + arr.TEST_CD + '" value="' + arr.PARENT_CATEGORY +
                            '">' +
                            '<input type="hidden" id="cn_' + arr.TEST_CD + '" value="' + arr.SLUG + '">' +
                            '<input type="hidden" id="cn_' + arr.TEST_CD + '" value="' + arr.featured_image +
                            '">' +
                            '<input type="hidden" id="cn_' + arr.TEST_CD + '" value="' + arr.mode + '">' +
                            '<input type="hidden" id="cn_' + arr.TEST_CD + '" value="' + arr.DETAILS + '">' +
                            '<td data-label="Action"><div class="dropdown">' +
                            '<button class="btn option-view-btn dropdown-toggle" type="button"' +
                            ' id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">OPTIONS</button>' +
                            '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">' +
                            '<li><a class="dropdown-item" href="#" onclick="view_online_lab(' + arr.TEST_CD +
                            ')">View</a></li>' +
                            '<li><a class="dropdown-item" href="#" onclick="edit_online_lab(' + arr.TEST_CD +
                            ')">Edit</a></li>' +
                            '<li><a class="dropdown-item" href="#" onclick="del_test(' + arr.TEST_CD +
                            ')">Delete</a></li></ul></div></td>'
                        );
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            function calculateSalePrice() {
                let discountPercentage = parseFloat($("#discount_percentage").val()) || 0;
                let actualPrice = parseFloat($("#actual_price").val()) || 0;

                if (discountPercentage > 100 || discountPercentage < 0) {
                    alert("Discount percentage should be between 0 and 100.");
                    $("#discount_percentage").val('');
                    $("#sell_price").val('');
                    return;
                }

                let discountAmount = (actualPrice * discountPercentage) / 100;
                let salePrice = actualPrice - discountAmount;

                if (!isNaN(salePrice)) {
                    $("#sell_price").val(salePrice.toFixed(2));
                }
            }

            $("#discount_percentage, #actual_price").on("input", calculateSalePrice);
        });

        $(document).ready(function () {
            function calculateSalePrice() {
                let discountPercentage = parseFloat($("#e_dis").val()) || 0;
                let actualPrice = parseFloat($("#e_ac").val()) || 0;

                if (discountPercentage > 100 || discountPercentage < 0) {
                    alert("Discount percentage should be between 0 and 100.");
                    $("#e_dis").val('');
                    $("#e_sp").val('');
                    return;
                }

                let discountAmount = (actualPrice * discountPercentage) / 100;
                let salePrice = actualPrice - discountAmount;

                if (!isNaN(salePrice)) {
                    $("#e_sp").val(salePrice.toFixed(2));
                }
            }

            $("#e_dis, #e_ac").on("input", calculateSalePrice);
        });
    </script>
@endsection
@section('content')
    <div class="dashboard-content">
        <div class="container-fluid">
            <div class="row m-auto">
                <div class="col-md-12">
                    <div class="row m-auto">
                        <div class="d-flex align-items-end p-0">
                            <div>
                                <h3>Online Labtests</h3>
                            </div>

                        </div>
                        <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                            <div class="d-flex align-items-baseline col-12 col-md-4 col-sm-6">
                                <input type="text" id="search" class="form-control mb-1" placeholder="Search labtest">
                                <button type="button" id="search_btn" onclick="search({{ json_encode($da) }})"
                                    class="btn process-pay"><i class="fa-solid fa-search"></i></button>
                            </div>
                            <div>
                                <button type="button" class="btn process-pay" data-bs-toggle="modal"
                                    data-bs-target="#add_online_test">Add Online Labtest</button>
                            </div>
                        </div>

                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Actual Price</th>
                                        <th scope="col">Sell Price</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse($data as $item)
                                        <tr>
                                            <td data-label="Name">{{ $item->TEST_NAME }}</td>
                                            <td data-label="Category Name">{{ $item->main_category_name }}</td>
                                            <td data-label="Actual Price">{{ $item->PRICE ?? '-' }}</td>
                                            <td data-label="Sale Price">{{ $item->SALE_PRICE }}</td>
                                            <input type='hidden' id="na_{{ $item->TEST_CD }}"
                                                value="{{ $item->TEST_NAME }}">
                                            <input type='hidden' id="sl_{{ $item->TEST_CD }}" value="{{ $item->SLUG }}">
                                            <input type='hidden' id="cn_{{ $item->TEST_CD }}"
                                                value="{{ $item->PARENT_CATEGORY }}">
                                            <input type='hidden' id="des_{{ $item->TEST_CD }}"
                                                value="{{ $item->DESCRIPTION }}">
                                            <input type='hidden' id="pr_{{ $item->TEST_CD }}"
                                                value="{{ $item->PRICE }}">
                                            <input type="hidden" id="dis_{{ $item->TEST_CD }}"
                                                value="{{ $item->discount_percentage }}">
                                            <input type='hidden' id="ac_{{ $item->TEST_CD }}"
                                                value="{{ $item->actual_price }}">
                                            <input type='hidden' id="sp_{{ $item->TEST_CD }}"
                                                value="{{ $item->SALE_PRICE }}">
                                            <input type='hidden' id="de_{{ $item->TEST_CD }}"
                                                value="{{ $item->DETAILS }}">
                                            <input type='hidden' id="img_{{ $item->TEST_CD }}"
                                                value="{{ $item->featured_image }}">
                                            <input type='hidden' id="mo_{{ $item->TEST_CD }}"
                                                value="{{ $item->mode }}">
                                            <td data-label="Action">
                                                <div class="dropdown">
                                                    <button class="btn option-view-btn dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        OPTIONS
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="view_online_lab({{ $item->TEST_CD }})">View</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="edit_online_lab({{ $item->TEST_CD }})">Edit</a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#"
                                                                onclick="del_test({{ $item->TEST_CD }})">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan='4'>
                                                <div class="m-auto text-center for-empty-div">
                                                    <img src="{{ asset('assets/images/for-empty.png') }}" alt="">
                                                    <h6> No Online Lab Tests</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                            </table>
                            <div class="row d-flex justify-content-center">
                                <div id="pag" class="paginateCounter">
                                    {{ $data->links('pagination::bootstrap-4') }}
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
            </div>
        </div>
    </div>
    </div>
    </div>

    <!-- ------------------View-Online-Labtest-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="view_online_labtest" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Online Labtest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="">
                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Test Name:</span> <span id="v_na">(ABO, Rh)</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Slug:</span> <span
                                            id="v_sl">blood-type-test-abo-rh</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Is Featured:</span> <span>Not Featured Test</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Test Category:</span> <span id="v_cat">21</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Price:</span> <span id="v_pr">$ 39.00</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Keyword:</span> <span id="v_kw">blood-type</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Discount %:</span> <span id="v_dis">39.00</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Sale Price:</span> <span id="v_sp">$ 39.00</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Is Approved from Admin:</span> <span>Approved</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><span class="fw-bold">Featured Image:</span> <span></span></p>
                                    <div id="v_img">
                                        <img class="img-fluid" src="./assets/images/lab-test-featured-img.png"
                                            alt="" style="width: 122px; height: 105px;">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">Test Details:</span> <span id="v_de">This test
                                            determines your blood type. It identifies your blood group (A, B, AB, or O) and
                                            whether you are positive or negative for the Rh antigen, a protein that affects
                                            what type of blood you can receive or donate. For example, O negative is a
                                            universal donor, while AB positive is a universal recipient.</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">Short Details:</span> <span id="v_des">This test
                                            determines your blood type. It identifies your blood group (A, B, AB, or O) and
                                            whether you are positive or negative for the Rh antigen, a protein that affects
                                            what type of blood you can receive or donate. For example, O negative is a
                                            universal donor, while AB positive is a universal recipient.</span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><span class="fw-bold">Description:</span> <span id="v_dess">Lorem ipsum dolor
                                            sit amet consectetur adipisicing elit. Distinctio assumenda quibusdam,
                                            repudiandae eum adipisci nesciunt impedit et beatae a doloribus.</span></p>
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


    <!-- ------------------View-Online-Labtest-Modal-end------------------ -->

    <!-- ------------------Add-Online-labtest-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_online_test" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/create/online/lab/test" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Online Labtest</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Code:</label>
                                    <input type="text" name="tcd" class="form-control" placeholder="" required>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="price">Actual Price:</label>
                                    <input type="text" name="price" id="price" class="form-control" placeholder="" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="discount_percentage">Discount %:</label>
                                    <input type="text" name="discount_percentage" id="discount_percentage" class="form-control" placeholder="" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="actual_price">Sell Price:</label>
                                    <input type="text" name="actual_price" id="actual_price" class="form-control" placeholder="" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sale_price">Discounted Price:</label>
                                    <input readonly type="text" name="sale_price" id="sell_price" class="form-control" placeholder="" required>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Choose Lab Test Category:</label>
                                    <select class="form-select" name="category" aria-label="Default select example"
                                        required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Test Details:</label>
                                    <textarea class="form-control" name="details" id="" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description:</label>
                                    <textarea class="form-control" name="description" id="" rows="4" required></textarea>
                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ------------------Add-Online-labtest-Modal-end------------------ -->


    <!-- ------------------Edit-Online-labtest-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit_online_test" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/edit/online/lab/test" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Online Labtest</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Name</label>
                                    <input type="text" id="e_name" name="tn" class="form-control"
                                        placeholder="(ABO, Rh)">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Test Code:</label>
                                    <input type="text" id="e_id" name="test_cd" class="form-control"
                                        placeholder="">
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Actual Price:</label>
                                    <input type="text" id="e_pr" name="pr" class="form-control"
                                        placeholder="21">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Discount %:</label>
                                    <input type="text" id="e_dis" name="discount_percentage" class="form-control"
                                        placeholder="39.00">
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="specialInstructions">Sell Price:</label>
                                    <input type="text" id="e_ac" name="actual_price" class="form-control"
                                        placeholder="39.00">
                                </div>
                                <div class="col-md-6">
                                    <label for="specialInstructions">Discounted Price:</label>
                                    <input type="text" id="e_sp" name="sp" class="form-control"
                                        placeholder="39.00" readonly>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Choose Lab Test Category:</label>
                                    <select class="form-select" id="e_cat" name="category" aria-label="Default select example">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Test Details:</label>
                                    <textarea class="form-control" name="sn" id="e_de" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="specialInstructions">Description:</label>
                                    <textarea class="form-control" name="des" id="e_des" rows="4"></textarea>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn process-pay">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ------------------Edit-Online-labtest-Modal-end------------------ -->


    <!-- ------------------Delete-Online-labtest-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="delete_online_labtest" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/del/online/lab/test" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Online Labtest</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-modal-body">
                            Are you sure you want to delete this labtest?
                            <input type="hidden" id="d_id" name="id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- ------------------Edit-Quest-labtest-Categories-Modal-end------------------ -->

<!-- Option 1: Bootstrap Bundle with Popper -->
