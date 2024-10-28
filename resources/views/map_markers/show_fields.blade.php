<!-- Name Field -->
<div class="form-group col-md-3">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $mapMarkers->name }}</p>
</div>

<!-- State Field -->
<div class="form-group col-md-3">
    {!! Form::label('state', 'State:') !!}
    <p>{{ $mapMarkers->state }}</p>
</div>

<!-- Address Field -->
<div class="form-group col-md-3">
    {!! Form::label('address', 'Address:') !!}
    <p>{{ $mapMarkers->address }}</p>
</div>

<!-- City Field -->
<div class="form-group col-md-3">
    {!! Form::label('city', 'City:') !!}
    <p>{{ $mapMarkers->city }}</p>
</div>

<!-- Zip Code Field -->
<div class="form-group col-md-3">
    {!! Form::label('zip_code', 'Zip Code:') !!}
    <p>{{ $mapMarkers->zip_code }}</p>
</div>

<!-- Marker Type Field -->
<div class="form-group col-md-3">
    {!! Form::label('marker_type', 'Marker Type:') !!}
    <p>{{ $mapMarkers->marker_type }}</p>
</div>

<!-- Marker Icon Field -->
<div class="form-group col-md-3">
    {!! Form::label('marker_icon', 'Marker Icon:') !!}
    <p>{{ $mapMarkers->marker_icon }}</p>
</div>

<!-- Lat Field -->
<div class="form-group col-md-3">
    {!! Form::label('lat', 'Lat:') !!}
    <p>{{ $mapMarkers->lat }}</p>
</div>

<!-- Long Field -->
<div class="form-group col-md-3">
    {!! Form::label('long', 'Long:') !!}
    <p>{{ $mapMarkers->long }}</p>
</div>

