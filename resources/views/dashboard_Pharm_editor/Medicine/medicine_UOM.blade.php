@extends('layouts.dashboard_Pharm_editor')

@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>CHCC - Pharmacy Editor Dashboard</title>
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
    <script>
        $(document).on("click", '.edit', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var value = $(this).attr('class');
            var breakC = value.split(" ")[2];
            $("#edit_id").val(id);
            $("#UOM").val(breakC);
            $('#edit_uoms').modal('show')
        });


        $(document).on("click", '.delete', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $("#delete_id").val(id);
            $('#delete_medicine').modal('show')
        });
    </script>
    <script>
        var input = document.getElementById("search");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                document.getElementById("search_btn").click();
            }
        });

        function search(array) {
            var val = $('#search').val();
            console.log(val,array);
            if (val == '') {
                window.location.href = '/pharmacy/medicine/UOM';
            } else {
                $('#bodies').empty();
                $('#pag').html('');
                $.each(array, function(key, arr) {
                    if ((arr.unit != null && arr.unit.toString().match(val))) {
                        $('#bodies').append('<tr><td data-label="UOM">'+arr.unit+'</td></tr>')
                    }
                });
            }
        }
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
                                <h3>Medicines UOMs (Unit Of Measurements)</h3>
                            </div>

                        </div>
                        <div class="d-flex align-items-baseline flex-wrap justify-content-between p-0">
                            <div class="d-flex">
                                <input type="text" class="form-control mb-1" id="search"
                                    placeholder="Search">
                                <button type="button" id="search_btn"
                                    onclick="search({{ json_encode($data) }})" class="btn process-pay"><i
                                        class="fa-solid fa-search"></i></button>
                            </div>
                            <div>
                                <button type="button" class="btn process-pay" data-bs-toggle="modal"
                                    data-bs-target="#add_uoms">Add UOMs</button>
                            </div>
                        </div>

                        <div class="wallet-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Unit Of Measurement (UOM)</th>
                                    </tr>
                                </thead>
                                <tbody id="bodies">
                                    @forelse ($dataGrid as $data)
                                        <tr>
                                            <td data-label="UOM">{{ $data->unit }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td> No UOM of Show</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="pag">
                            {{ $dataGrid->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------------Edit-UOMS-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="edit_uoms" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dash_medicine_UOM_update') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Edit Unit Of Measurement (UOM)</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="modal-body">
                        <div class="dosage-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="specialization_status">Unit Of Measurement</label>
                                    <input type="text" name="unit" id="UOM" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn con-recomm-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ------------------Edit-UOMS-Modal-end------------------ -->


    <!-- ------------------Add-UOMS-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="add_uoms" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('dash_medicine_UOM_store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Add Unit Of Measurement (UOM)</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="dosage-body">
                            <form action="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="specialization_status">Unit Of Measurement</label>
                                        <input type="text" name="unit" class="form-control" placeholder="200mg">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn con-recomm-btn">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ------------------Add-UOMS-Modal-end------------------ -->

    <!-- ------------------Delete-UOM-Modal-start------------------ -->

    <!-- Modal -->
    <div class="modal fade" id="delete_medicine" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dash_medicine_UOM_delete') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete UOM</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-modal-body">
                            Are you sure you want to Delete this UOM?
                            <input type="hidden" id="delete_id" name="delete_id">
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


    <!-- ------------------Delete-UOM-Modal-end------------------ -->
@endsection
