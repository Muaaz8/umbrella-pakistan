@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Edit Quest Labtests</h2>
            </div>
            <div class="card">
                <div class="body">
                    <form method="post" action="{{ url('updateQuestLabTest') }}">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Test Code</label>
                                        <input type="text" class="form-control" name="TEST_CD"
                                            value="{{ $data->TEST_CD }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Service Name</label>
                                        <input type="text" class="form-control" value="{{ $data->DESCRIPTION }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Test Name</label>
                                        <input type="text" class="form-control" name="TEST_NAME"
                                            value="{{ $data->TEST_NAME }}" placeholder="Test Name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="PARENT_CATEGORY">Category</label>
                                        <select class="form-control" name="PARENT_CATEGORY[]" required multiple>
                                            {{-- <option selected disabled value="">Please Select</option> --}}
                                            @forelse ($mainCategory as $category)
                                                @if ($data->main_category_name == $category->name)
                                                    <option selected value="{{ $category->id }}">{{ $category->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endif
                                            @empty
                                                <h2>No Records</h2>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Price</label>
                                        <input type="text" class="form-control" value="{{ $data->PRICE }}"
                                            name="PRICE" placeholder="PRICE">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Sale Price</label>
                                        <input type="text" class="form-control" name="SALE_PRICE"
                                            value="{{ $data->SALE_PRICE }}" placeholder="SALE PRICE">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>DESCRIPTION</label>
                                        {!! Form::textarea('DETAILS', $data->DETAILS, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-12">
                                <a href="/viewAllQuestLabTest" class="btn btn-raised">Back</a>
                                <button type="submit" class="btn btn-raised g-bg-cyan">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>

    <script type="text/javascript">
        CKEDITOR.replace('DETAILS');
        CKEDITOR.add
    </script>
@endsection
