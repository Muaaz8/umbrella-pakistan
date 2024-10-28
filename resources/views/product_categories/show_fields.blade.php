<!-- Name Field -->
<div class="form-group col-md-3">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $productCategory->name }}</p>
</div>

<!-- Slug Field -->
<div class="form-group col-md-3">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $productCategory->slug }}</p>
</div>

<!-- Description Field -->
<div class="form-group col-md-3">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $productCategory->description }}</p>
</div>

<!-- Thumbnail Field -->
<div class="form-group col-md-3">
    {!! Form::label('thumbnail', 'Thumbnail:') !!}
    <p> <img src="/{{ $productCategory->thumbnail }}" height="50px" /></p>
   
</div>

<!-- Created At Field -->
<div class="form-group col-md-3">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $productCategory->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-3">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $productCategory->updated_at }}</p>
</div>

