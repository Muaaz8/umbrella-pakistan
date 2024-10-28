<div class="col-sm-6 col-xs-3">
    <div class="form-group">
        <div class="form-line mt-5">
            {{-- {!! Form::label('name', 'Name:') !!} --}}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        </div>
    </div>
</div>

<!-- Slug Field -->
{!! Form::hidden('slug', null, ['class' => 'form-control']) !!}

{!! Form::hidden('category_type', 'imaging', ['class' => 'form-control']) !!}
{{-- <div class="col-sm-6 col-xs-6 ">
    <div class="form-group">
        <div class="form-line">
            {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
    </div>
</div> --}}

{{-- <div class="col-sm-3 col-xs-3 ">
    <div class="form-group">
        <div class="form-line">
            {!! Form::label('thumbnail', 'Thumbnail:') !!}
            {!! Form::file('thumbnail', ['class' => 'dropzone dz-clickable.']) !!}
        </div>
    </div>
</div> --}}

<?php $arr = ['', 'medicine', 'lab-test', 'substance-abuse']; ?>
<div class="form-group col-sm-6 ">
    <label for="category_type" class="mt-2">Category Type</label>
    <div class="form-line">
    <select class="form-control" name="category_type" id="category_type" required>
        @foreach ($arr as $item)
            <option value="{{ $item }}">{{ $item }}</option>
        @endforeach
    </select>
    </div>
</div>

<!-- Description Field -->
{{-- <div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Thumbnail Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('thumbnail', 'Thumbnail:') !!}
    {!! Form::file('thumbnail', ['class' => 'dropzone dz-clickable.']) !!}
</div>
<div class="clearfix"></div> --}}


<input value="{{ $user->id }}" name="created_by" hidden>
<!-- Submit Field -->
<div class="form-group d-flex justify-content-around ml-3 col-sm-3">
    {!! Form::submit('Save', ['class' => 'btn save-btn']) !!}
    <a href="{{ route('productCategories.index') }}" class="btn close-btn ml-3">Cancel</a>
</div>

