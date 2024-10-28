<table class="table">
    <thead>
        <tr>
        <th scope="col">Test Name</th>
        <th scope="col">Session Date</th>
        <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($labs as $lab)
        <tr>
            <td data-label="Test Name" data-th="Supplier Code">
                {{$lab->name}}
            </td>
            <td data-label="Result Date" data-th="Supplier Name">
                {{date("m-d-y",strtotime($lab->session_date))}}
            </td>
            <td data-label="Action" data-th="Invoice Number">waiting for result</td>
        </tr> 
        @endforeach
    </tbody>
</table>
{{$labs->links('pagination::bootstrap-4')}}