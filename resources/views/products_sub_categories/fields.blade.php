<!-- Title Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    
</div> --}}

<div class="col-sm-6 col-xs-12 ">
    <div class="form-group">
        <div class="form-line">
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div></div></div>

<!-- Slug Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('slug', 'Slug:') !!} --}}
    {!! Form::hidden('slug', null, ['class' => 'form-control']) !!}
{{-- </div> --}}

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>


<?php 

 //print_r($product_categoryItems);

?>
<!-- Parent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_id', 'Parent Id:') !!}
    {!! Form::select('parent_id', $product_categoryItems, null, ['class' => 'form-control']) !!}
</div>

<!-- Thumbnail Field -->
<div class="form-group col-sm-6">
    {!! Form::label('thumbnail', 'Thumbnail:') !!}
    {!! Form::file('thumbnail') !!}
</div>
<div class="clearfix"></div>
<input value="{{$user->id}}" name="created_by" hidden>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn save-btn']) !!}
    <a href="{{ route('productsSubCategories.index') }}" class="btn close-btn">Cancel</a>
</div>

<style>

    .btn-primary.save-btn {
        background: #2ac0b2 !important;
        color: #fff !important;
    }
    </style>