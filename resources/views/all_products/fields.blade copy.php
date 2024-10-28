<?php
// Form Type
$user_type = Auth::user()->user_type;
$form_type = '';
// Mode
$mode = '';
if ($user_type === 'editor_lab') {
    $mode = 'lab-test';
} else if ($user_type === 'editor_pharmacy') {
    $mode = 'medicine';
} else if ($user_type === 'admin_pharmacy') {
    $mode = 'medicine';
} else if ($user_type === 'admin_lab') {
    $mode = 'lab-test';
}

if (isset($_GET['form_type'])) {
    $form_type = $_GET['form_type'];
}

// echo '<pre>';
//dd($allProducts->id);
// echo '</pre>';

// die;


if ($form_type == 'panel-test') { ?>

    <div class="form-group col-lg-6 col-sm-12">
        {!! Form::label('panel_name', 'Panel Name:') !!}
        {!! Form::text('panel_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group  col-lg-6 col-sm-12">
        {!! Form::label('sale_price', 'Price:') !!}
        {!! Form::text('sale_price', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-lg-6 col-sm-12">
        {!! Form::label('cpt_code', 'CPT Code:') !!}
        {!! Form::text('cpt_code', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-lg-6 col-sm-12">
        {!! Form::label('keyword', 'Keyword:') !!}
        {!! Form::text('keyword', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-lg-6 col-sm-12">
        <label for="name">Included Test ( Must Select Test While Add/Edit )</label>
        <select class="form-control testSelect2 " name="including_test[]" multiple="multiple">
        </select>
    </div>
    <div class="form-group col-lg-6 col-sm-12">
        <label for="parent_category">Choose Panel Category</label>
        <select class="form-control parent_category_products" name="parent_category[]" multiple="multiple" required>
            @if (isset($prefield))
            <option value="">Select</option>
            @foreach ($prefield['category_name'] as $item)
            <option selected="selected" value="<?= $item->id ?>"><?= $item->category_name ?></option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="form-group col-lg-6 col-sm-6">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured">
            <label class="custom-control-label" for="is_featured" style=" font-size: 18px; position: relative; top: 30px; ">Is Featured</label>
        </div>
    </div>

    <div class="form-group col-lg-6 col-sm-6">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="no_test" name="no_test">
            <label class="custom-control-label" for="no_test" style=" font-size: 18px; position: relative; top: 30px; ">No
                Tests Found</label>
        </div>
    </div>
    <!-- <div class="form-group col-sm-3">
    <label for="faq_for_test">FAQ's</label>
    <select class="form-control testFAQ" name="faq_for_test[]" multiple="multiple">
      </select>
</div> -->

    {!! Form::hidden('slug', null, ['class' => 'form-control']) !!}
    {!! Form::hidden('is_approved', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('mode', $mode, ['class' => 'form-control']) !!}
    {!! Form::hidden('quantity', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('type_of_image', 'panel_test', ['class' => 'form-control']) !!}

    <!-- Description Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('short_description', 'Short Description') !!}
        {!! Form::textarea('short_description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('test_details', 'Test Details:') !!}
        {!! Form::textarea('test_details', null, ['class' => 'form-control']) !!}
    </div>
<?php } elseif ($form_type == 'lab-test') { ?>

    <div class="form-group col-lg-6 col-sm-12">
        {!! Form::label('name', 'Test Name') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>


    <!-- <div class="form-group col-sm-4">
                <label for="faq_for_test">FAQ's</label>
                <select class="form-control testFAQ" name="faq_for_test[]" multiple="multiple">
                  </select>
            </div> -->

    <div class="form-group col-lg-6 col-sm-12">
        {!! Form::label('cpt_code', 'CPT Code:') !!}
        {!! Form::text('cpt_code', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-lg-6 col-sm-12">
        {!! Form::label('sale_price', 'Price:') !!}
        {!! Form::text('sale_price', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group  col-lg-6 col-sm-12">
        {!! Form::label('keyword', 'Keyword:') !!}
        {!! Form::text('keyword', null, ['class' => 'form-control']) !!}
    </div>

    {!! Form::hidden('is_approved', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('slug', null, ['class' => 'form-control']) !!}
    {!! Form::hidden('mode', $mode, ['class' => 'form-control']) !!}
    {!! Form::hidden('quantity', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('type_of_image', 'lab_test', ['class' => 'form-control']) !!}

    <div class="form-group col-lg-9  col-sm-12">
        <label for="parent_category">Choose Lab Test Category</label>
        <select class="form-control parent_category_products" name="parent_category[]" multiple="multiple" required>
            @if (isset($prefield))
            <option value="">Select</option>
            @foreach ($prefield['category_name'] as $item)
            <option selected="selected" value="<?= $item->id ?>"><?= $item->category_name ?></option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="form-group col-lg-3 col-sm-12">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured">
            <label class="custom-control-label" for="is_featured" style=" font-size: 18px; position: relative; top: 30px; ">Is Featured</label>
        </div>
    </div>

    <div class="form-group  col-sm-12">
        {!! Form::label('test_details', 'Test Details:') !!}
        {!! Form::textarea('test_details', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-12">
        {!! Form::label('short_description', 'Short Description') !!}
        {!! Form::textarea('short_description', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-12">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>

<?php } elseif ($form_type == 'imaging') { ?>

    <!-- Service Name -->
    <div class="form-group col-sm-4">
        {!! Form::label('name', 'Service Name:') !!}
        <select class="form-control imagingServicesSelect" name="name" id="name" required>
            <option selected="selected" value="{{ isset($allProducts) ? $allProducts->product_id : '' }}">
                {{ isset($allProducts) ? $allProducts->product_name : '' }}
            </option>
        </select>
    </div>

    <div class="form-group col-sm-4">
        <label for="city">Location</label>
        <select class="form-control imagingLocationSelect" name="city" id="city" required>
            <option selected="selected" value="{{ isset($allProducts) ? $allProducts->location_id : '' }}">
                {{ isset($allProducts) ? $allProducts->location_name : '' }}
            </option>
        </select>
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('price', 'Price:') !!}
        {!! Form::text('price', null, ['class' => 'form-control']) !!}
    </div>

    {!! Form::hidden('imaging_pricing', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('parent_category', 909, ['class' => 'form-control']) !!}
    {!! Form::hidden('sale_price', 0, ['class' => 'form-control']) !!}
    {!! Form::hidden('regular_price', 0, ['class' => 'form-control']) !!}
    {!! Form::hidden('quantity', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('mode', 'imaging', ['class' => 'form-control']) !!}
    {!! Form::hidden('medicine_type', 'prescribed', ['class' => 'form-control']) !!}
    {!! Form::hidden('is_approved', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('type_of_image', 'imaging', ['class' => 'form-control']) !!}
    {!! Form::hidden('imaging_data', 1, ['class' => 'form-control']) !!}

<?php } elseif ($form_type == 'imaging_location') { ?>

    <div class="form-group col-sm-4">
        {!! Form::label('clinic_name', 'State:') !!}
        {!! Form::text('clinic_name', null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('city', 'City:') !!}
        {!! Form::text('city', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-4">
        {!! Form::label('zip_code', 'ZipCode:') !!}
        {!! Form::text('zip_code', null, ['class' => 'form-control']) !!}
    </div>

    {{-- <div class="form-group col-sm-4">
    {!! Form::label('lat', 'Latitude:') !!}
    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('long', 'Longitude:') !!}
    {!! Form::text('long', null, ['class' => 'form-control']) !!}
</div> --}}

    {!! Form::hidden('imaging_location', 1, ['class' => 'form-control']) !!}

<?php } elseif ($form_type == 'imaging_services') { ?>

    <div class="form-group col-sm-4">
        {!! Form::label('name', 'Service Name:') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Sub Category Field -->
    <div class="form-group col-sm-4">
        <label for="parent_category">Imaging Category</label>
        <select class="form-control" name="parent_category" id="parent_category" required>
            <option value="">Select</option>
            @foreach ($categories as $item)
            <?php $sub_cat = explode('|', $item); ?>
            <option value="<?= $sub_cat[0] ?>"><?= $sub_cat[1] ?></option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-4">
        {!! Form::label('cpt_code', 'CPT Code:') !!}
        {!! Form::text('cpt_code', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Short Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('short_description', 'Short Description:') !!}
        {!! Form::textarea('short_description', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>

    {!! Form::hidden('sub_category', 0, ['class' => 'form-control']) !!}
    {!! Form::hidden('sale_price', 0, ['class' => 'form-control']) !!}
    {!! Form::hidden('regular_price', 0, ['class' => 'form-control']) !!}
    {!! Form::hidden('quantity', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('mode', 'imaging', ['class' => 'form-control']) !!}
    {!! Form::hidden('medicine_type', 'prescribed', ['class' => 'form-control']) !!}
    {!! Form::hidden('is_approved', 1, ['class' => 'form-control']) !!}
    {!! Form::hidden('type_of_image', 'imaging', ['class' => 'form-control']) !!}

<?php } elseif ($form_type == 'pharmacy') { ?>

    <!-- Name Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('name', 'Product Name:') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Sale Price Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('sale_price', 'Product Price:') !!}
        {!! Form::text('sale_price', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Quantity Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('quantity', 'Quantity:') !!}
        {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
    </div>
    <style>
        .form-group input[type=file] {
            position: relative;
            opacity: 1;
        }
    </style>
    <!-- Featured Image Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('featured_image', 'Product Image:') !!}
        {!! Form::file('featured_image') !!}
    </div>

    <!-- Slug Field -->
    {!! Form::hidden('slug', null, ['class' => 'form-control']) !!}
    {!! Form::hidden('mode', $mode, ['class' => 'form-control']) !!}
    {!! Form::hidden('type_of_image', 'pharmacy', ['class' => 'form-control']) !!}


    <!-- Sub Category Field -->
    <div class="form-group col-sm-6">
        <label for="sub_category">Product Category</label>
        <select class="form-control" name="sub_category" id="sub_category" required>
            <option value="">Select</option>
            @foreach ($categories as $item)
            <?php $sub_cat = explode('|', $item); ?>
            <option value="<?= $sub_cat[0] ?>"><?= $sub_cat[1] ?></option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="medicine_type">Product Type</label>
        <select class="form-control" name="medicine_type" id="medicine_type">
            <option value="" disabled>Select</option>
            <option value="counter_medicine">Counter Medicine</option>
            <option value="prescribed_medicine">Prescribed Medicine</option>
        </select>
    </div>

    <!-- Medicine Ingredients -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('medicine_ingredients', 'Product Ingredients:') !!}
    {!! Form::text('medicine_ingredients', null, ['class' => 'form-control']) !!}
</div> --}}

    <!-- Medicine Warnings -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('medicine_warnings', 'Product Warnings:') !!}
    {!! Form::text('medicine_warnings', null, ['class' => 'form-control']) !!}
</div> --}}

    <!-- Medicine Directions -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('medicine_directions', 'Product Directions:') !!}
    {!! Form::text('medicine_directions', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('test_details', 'Product Details:') !!}
    {!! Form::text('test_details', null, ['class' => 'form-control']) !!}
    </div> --}}

    <!-- Stock Status Field -->

    {{-- <div class="form-group col-sm-6"> --}}
    {{-- {!! Form::label('stock_status', 'Stock Status:') !!}
    {!! Form::text('stock_status', null, ['class' => 'form-control']) !!} --}}

    {{-- <label for="stock_status">Stock Status</label> --}}
    <select class="form-control" name="stock_status" id="stock_status" hidden>
        <option value="in_stock">In Stock</option>
        <option value="out_of_stock">Out Of Stock</option>
    </select>
    {{-- </div> --}}
    {{-- <div class="form-group col-sm-6">
    <label for="parent_category">Parent Category</label>
    <select class="form-control" name="parent_category" id="parent_category">
        @foreach ($data['main_category'] as $item)
    <option value="{{ $item->id }}">{{ $item->title }}</option>
    @endforeach
    </select>
    </div> --}}

    <!-- Gallery Field -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('gallery', 'Gallery:') !!}
    {!! Form::file('gallery') !!}
</div> --}}
    {{-- <div class="clearfix"></div> --}}

    <!-- Tags Field -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('tags', 'Tags:') !!}
    {!! Form::text('tags', null, ['class' => 'form-control']) !!}
</div> --}}

    <!-- Regular Price Field -->
    {{-- <div class="form-group col-sm-6">
        {!! Form::label('regular_price', 'Regular Price:') !!}
        {!! Form::text('regular_price', null, ['class' => 'form-control']) !!}
    </div> --}}

    <!-- Short Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('short_description', 'Short Description:') !!}
        {!! Form::text('short_description', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Description Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>


    <?php if ($user_type == 'editor_lab') { ?>
        <div class="form-group col-sm-6">
            {!! Form::label('cpt_code', 'CPT Code:') !!}
            {!! Form::text('cpt_code', null, ['class' => 'form-control']) !!}
        </div>
    <?php } ?>


    <!-- Stock Quantity Field -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('stock_quantity', 'Stock Quantity:') !!}
    {!! Form::text('stock_quantity', null, ['class' => 'form-control']) !!}
</div> --}}

    <!-- Stock Status Field -->
    {{-- <div class="form-group col-sm-6">
    {!! Form::label('stock_status', 'Stock Status:') !!}
    {!! Form::text('stock_status', null, ['class' => 'form-control']) !!}
</div> --}}
    <input name="user_id" value="{{ $user->id }}" hidden>
<?php } ?>

<!-- Submit Field -->
<div class="form-group d-flex justify-content-center col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary save-btn']) !!}
    @if (auth()->user()->user_type != 'editor_imaging')
    <a href="{{ route('allProducts.index') }}" class="btn btn-default">Cancel</a>
    @endif

</div>
<style>
    .btn-primary.save-btn {
        background: #4676be;
        /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #3498db, #2c3e50);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #3498db, #2c3e50);
        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        box-shadow: rgba(0, 0, 0, 0.25) 0px 14px 28px, rgba(0, 0, 0, 0.22) 0px 10px 10px !important;
        color: #fff !important;
        padding: 20px 50px !important;
        border-radius: 20px !important;
        border: none !important;
        margin: 10px !important;
    }

    .btn-default {
        padding: 20px 40px !important;
        border-radius: 20px !important;
        border: none !important;
        margin: 10px !important;
    }
</style>