<?php
if ($allProducts->mode === 'lab-test') { ?>
    @include('all_products.labtest_view')
<?php } else { ?>

    <!-- Id Field -->
    <div class="form-group card-div col-md-3">
        {!! Form::label('id', 'Id:') !!}
        <p>{{ $allProducts->id }}</p>
    </div>


    <!-- Name Field -->
    <div class="form-group  card-div col-lg-4  col-md-3">
        {!! Form::label('name', 'Name:') !!}
        <p>{{ $allProducts->name }}</p>
    </div>

    <!-- Name Field -->
    <div class="form-group card-div col-lg-4 col-md-3">
        {!! Form::label('panel_name', 'Panel Name:') !!}
        <p>{{ $allProducts->panel_name }}</p>
    </div>

    <div class="form-group card-div col-lg-4 col-md-3">
        {!! Form::label('including_test', 'Including Test:') !!}
        <p>{{ $allProducts->including_test }}</p>
    </div>

    <!-- Slug Field -->
    <div class="form-group col-lg-4 col-md-3">
        {!! Form::label('slug', 'Slug:') !!}
        <p>{{ $allProducts->slug }}</p>
    </div>

    <!-- Parent Category Field -->
    <div class="form-group col-md-3">
        {!! Form::label('parent_category', 'Parent Category:') !!}
        <p>{{ $allProducts->parent_name }}</p>
    </div>

    <!-- Sub Category Field -->
    <div class="form-group col-md-3">
        {!! Form::label('sub_category', 'Sub Category:') !!}
        <p>{{ $allProducts->child_name }}</p>
    </div>

    <!-- Featured Image Field -->
    <div class="form-group col-md-3">
        {!! Form::label('featured_image', 'Featured Image:') !!}
        <p><img src="/{{ $allProducts->featured_image }}" height="50px" /></p>
    </div>

    <!-- Gallery Field -->
    <div class="form-group col-md-3">
        {!! Form::label('gallery', 'Gallery:') !!}
        <p>{{ $allProducts->gallery }}</p>
    </div>

    <!-- Tags Field -->
    <div class="form-group col-md-3">
        {!! Form::label('tags', 'Tags:') !!}
        <p>{{ $allProducts->tags }}</p>
    </div>

    <!-- Sale Price Field -->
    <div class="form-group col-md-3">
        {!! Form::label('sale_price', 'Sale Price:') !!}
        <p>{{ $allProducts->sale_price }}</p>
    </div>

    <!-- Regular Price Field -->
    <div class="form-group col-md-3">
        {!! Form::label('regular_price', 'Regular Price:') !!}
        <p>{{ $allProducts->regular_price }}</p>
    </div>

    <!-- Quantity Field -->
    <div class="form-group col-md-3">
        {!! Form::label('quantity', 'Quantity:') !!}
        <p>{{ $allProducts->quantity }}</p>
    </div>

    <!-- Type Field -->
    <div class="form-group col-md-3">
        {!! Form::label('keyword', 'Keyword:') !!}
        <p>{{ $allProducts->keyword }}</p>
    </div>

    <!-- Mode Field -->
    <div class="form-group col-md-3">
        {!! Form::label('mode', 'Mode:') !!}
        <p>{{ $allProducts->mode }}</p>
    </div>

    <!-- Sku Field -->
    <div class="form-group col-md-3">
        {!! Form::label('medicine_type', 'Medicine Type:') !!}
        <p>{{ $allProducts->medicine_type }}</p>
    </div>

    <!-- Status Field -->
    <div class="form-group col-md-3">
        {!! Form::label('status', 'Status:') !!}
        <p>{{ $allProducts->labtest_status }}</p>
    </div>

    <!-- Short Description Field -->
    <div class="form-group col-md-3">
        {!! Form::label('short_description', 'Short Description:') !!}
        <p>{{ $allProducts->short_description }}</p>
    </div>

    <!-- Description Field -->
    <div class="form-group col-md-3">
        {!! Form::label('description', 'Description:') !!}
        <p>{{ $allProducts->description }}</p>
    </div>

    <div class="form-group col-md-3">
        {!! Form::label('cpt_code', 'CPT Code:') !!}
        <p>{{ $allProducts->cpt_code }}</p>
    </div>

    <div class="form-group col-md-3">
        {!! Form::label('test_details', 'Test Details:') !!}
        <p>{{ $allProducts->test_details }}</p>
    </div>

    <!-- Medicine Ingredients Field -->
    <div class="form-group col-md-3">
        {!! Form::label('medicine_ingredients', 'Medicine Ingredients:') !!}
        <p>{{ $allProducts->medicine_ingredients }}</p>
    </div>

    <!-- Stock Status Field -->
    <div class="form-group col-md-3">
        {!! Form::label('stock_status', 'Stock Status:') !!}
        <p>{{ $allProducts->stock_status }}</p>
    </div>

    <!-- Created At Field -->
    <div class="form-group col-md-3">
        {!! Form::label('created_at', 'Created At:') !!}
        <p>{{ $allProducts->created_at }}</p>
    </div>

    <!-- Updated At Field -->
    <div class="form-group col-md-3">
        {!! Form::label('updated_at', 'Updated At:') !!}
        <p>{{ $allProducts->updated_at }}</p>
    </div>

<?php } ?>
