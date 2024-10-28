<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
   <table class="table table-hover tblData" id="mapMarkers-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>State</th>
        <th>Address</th>
        <th>City</th>
        <th>Zip Code</th>
        <!-- <th>Marker Type</th> -->
        <!-- <th>Marker Icon</th> -->
        <th>Lat</th>
        <th>Long</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($mapMarkers as $mapMarkers)
            <tr>
                <td>{{ $mapMarkers->name }}</td>
            <td>{{ $mapMarkers->state }}</td>
            <td>{{ $mapMarkers->address }}</td>
            <td>{{ $mapMarkers->city }}</td>
            <td>{{ $mapMarkers->zip_code }}</td>
            <!-- <td>{{ $mapMarkers->marker_type }}</td> -->
            <!-- <td>{{ $mapMarkers->marker_icon }}</td> -->
            <td>{{ $mapMarkers->lat }}</td>
            <td>{{ $mapMarkers->long }}</td>
                <td>
                    {!! Form::open(['route' => ['mapMarkers.destroy', $mapMarkers->id], 'method' => 'delete']) !!}
                    <div class='btns-group'>
                        <a href="{{ route('mapMarkers.show', [$mapMarkers->id]) }}" class='action-btn'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('mapMarkers.edit', [$mapMarkers->id]) }}" class='action-btn'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
<style>
    .btns-group a {
    margin-right: 10px;
    color: black;
    padding: 5px 10px;
    border-radius: 5px;
}
.btns-group button {
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
}

    
</style>
