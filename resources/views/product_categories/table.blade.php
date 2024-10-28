<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">
<table class="table  table-hover tblData" id="productCategories-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            {{-- <th>Description</th> --}}
            {{-- <th>Thumbnail</th> --}}
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($productCategories  as $productCategory)
            <tr>
                <td>{{ $productCategory->id }}</td>
                <td>{{ $productCategory->name }}</td>
                {{-- <td>{{ $productCategory->slug }}</td> --}}
                {{-- <td>{{ $productCategory->description }}</td> --}}
                {{-- <td><img src="{{ url('/uploads/' . $productCategory->thumbnail) }}" height="50px" /></td> --}}
                <td>
                    {!! Form::open(['route' => ['productCategories.destroy', $productCategory->id], 'method' => 'delete']) !!}
                    <div class='btns-group'>
                        {{-- <a href="{{ route('productCategories.show', [$productCategory->id]) }}" class='action-btn'><i class="fa fa-eye"></i></a> --}}
                        <a href="{{ route('productCategories.edit', [$productCategory->id]) }}" class='action-btn'><i
                                class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @php
                            $user_type = auth()->user()->user_type;
                        @endphp
                        @if ($user_type == 'admin')
                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No Categories Added</td>
            </tr>

        @endforelse
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
