<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css')}}">
<style>
    .btn{
        padding:3px;
        border-radius:6px;
    }
</style>
<div class="table-responsive">
    <table class="table" id="mentalConditions-table">
        <thead>
            <tr>
                <th width="500">Name</th>
                <!-- <th>Content</th> -->
                <th colspan="3"  class='d-flex justify-content-end mr-3'>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($mentalConditions as $mentalConditions)
            <tr>
                <td>{{ $mentalConditions->name }}</td>
                {{-- $mentalConditions->content --}}
                <td>
                    {!! Form::open(['route' => ['mentalConditions.destroy', $mentalConditions->id], 'method' => 'delete']) !!}
                    <div class='d-flex justify-content-end center-align'>
                        <a href="{{ route('mentalConditions.show', [$mentalConditions->id]) }}" class='btn'><i class="fa fa-eye"></i></a>
                        <a href="{{ route('mentalConditions.edit', [$mentalConditions->id]) }}" class='btn'><i class="fa fa-pen"></i></a>
                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i>', ['type' => 'submit', 'class' => 'btn', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @empty
        <tr>
        <td colspan="3"><center>No Record Found</center></td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
