<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $mentalConditions->name }}</p>
</div>

<!-- Content Field -->
<div class="form-group">
    {!! Form::label('content', 'Content:') !!}
    <input hidden value="{{ $mentalConditions->content }}" id="content_text">
    <p id="content"></p>
</div>

