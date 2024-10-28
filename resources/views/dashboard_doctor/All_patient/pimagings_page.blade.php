<table class="table">
    <thead>
        <tr>
        <th scope="col">Service Name</th>
        <th scope="col">Session Date</th>
        <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody id="pimaging">
        @foreach ($imagings as $imaging)
        <tr>
            <td data-label="Service Name" scope="row">{{$imaging->name}}</td>
            <td data-label="Result Date">{{date("m-d-y",strtotime($imaging->session_date))}}</td>
            <td data-label="Action">waiting for result</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{$imagings->links('pagination::bootstrap-4')}}