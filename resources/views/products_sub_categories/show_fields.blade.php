<!-- Title Field -->


<div class="form-group col-md-3">
    {!! Form::label('title', 'Title:') !!}
    <p>{{ $productsSubCategory[0]->title }}</p>
</div>

<!-- Slug Field -->
<div class="form-group col-md-3">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $productsSubCategory[0]->slug }}</p>
</div>

<!-- Description Field -->
<div class="form-group col-md-3">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $productsSubCategory[0]->description }}</p>
</div>

<!-- Parent Id Field -->
<div class="form-group col-md-3">
    {!! Form::label('parent_id', 'Parent Name:') !!}
    <p>{{ $productsSubCategory[0]->parent_name}}</p>
</div>

<!-- Thumbnail Field -->
<div class="form-group col-md-3">
    {!! Form::label('thumbnail', 'Thumbnail:') !!}
    <p> <img src="/{{ $productsSubCategory[0]->thumbnail }}" height="50px" /></p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-3">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $productsSubCategory[0]->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-3">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $productsSubCategory[0]->updated_at }}</p>
</div>

