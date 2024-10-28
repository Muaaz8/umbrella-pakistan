
@php
if (isset($_GET['form_type'])) {$form_type= $_GET['form_type'];}    
@endphp

<!-- Name Field -->
<div class="form-group col-md-4">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- State Field -->
<div class="form-group col-md-4">
    {!! Form::label('state', 'State:') !!}
    {!! Form::textarea('state', null, ['class' => 'form-control product-input']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-md-4">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- City Field -->
<div class="form-group col-md-4">
    {!! Form::label('city', 'City:') !!}
    {!! Form::textarea('city', null, ['class' => 'form-control product-input']) !!}
</div>

<!-- Zip Code Field -->
<div class="form-group col-md-4">
    {!! Form::label('zip_code', 'Zip Code:') !!}
    {!! Form::text('zip_code', null, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Marker Type Field -->
{{-- <div class="form-group col-md-4">
    {!! Form::label('marker_type', 'Marker Type:') !!} --}}
    {!! Form::hidden('marker_type',  $form_type, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
{{-- </div> --}}

<!-- Marker Icon Field -->
<div class="form-group col-md-4">
    {!! Form::label('marker_icon', 'Marker Icon:') !!}
    {!! Form::text('marker_icon', null, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-md-4">
    {!! Form::label('lat', 'Lat:') !!}
    {!! Form::text('lat', null, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Long Field -->
<div class="form-group col-md-4">
    {!! Form::label('long', 'Long:') !!}
    {!! Form::text('long', null, ['class' => 'form-control product-input','maxlength' => 191,'maxlength' => 191]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 submit-btnse d-flex justify-content-center">
    {!! Form::submit('Save', ['class' => 'btn save-btn']) !!}
    <a href="{{ route('mapMarkers.index') }}" class="btn save-btn">Cancel</a>
</div>

<style>
    .submit-btnse .btn-primary{
        color: white !important; 
    }
</style>