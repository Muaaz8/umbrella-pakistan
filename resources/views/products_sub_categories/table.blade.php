<link rel="stylesheet" href="{{ asset('asset_admin/css/table.css') }}">
<div class="table-responsive">
    <table class="table tblData" id="productsSubCategories-table">
        <thead>
            <tr>
                <th>Sub Cateogry ID</th>
                <th>Sub Cateogry Title</th>
                {{-- <th>Slug</th> --}}
                {{-- <th>Description</th> --}}
                <th>Main Category ID</th>
                <th>Main Category Title</th>
                {{-- <th>Thumbnail</th> --}}
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productsSubCategories as $productsSubCategory)
                <tr>
                    <td>{{ $productsSubCategory->id }}</td>
                    <td>{{ $productsSubCategory->title }}</td>
                    {{-- <td>{{ $productsSubCategory->slug }}</td> --}}
                    {{-- <td>{{ $productsSubCategory->description }}</td> --}}
                    <td>{{ $productsSubCategory->parent_id }}</td>
                    <td>{{ $productsSubCategory->parent_name }}</td>
                    {{-- <td><img src="/{{ $productsSubCategory->thumbnail }}" height="50px" /></td> --}}

                    <td>
                        {!! Form::open(['route' => ['productsSubCategories.destroy', $productsSubCategory->id], 'method' => 'delete']) !!}
                        <div class='btns-group'>
                            <a href="{{ route('productsSubCategories.show', [$productsSubCategory->id]) }}"
                                class='action-btn'><i class="fa fa-eye"></i></a>
                            <a href="{{ route('productsSubCategories.edit', [$productsSubCategory->id]) }}"
                                class='action-btn'><i class="fa fa-edit"></i></a>
                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            {{-- @php
                                $user_type = auth()->user()->user_type;
                            @endphp
                            @if ($user_type == 'admin')
                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'action-btn', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endif --}}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No Sub Categories Added</td>
                </tr>

            @endforelse
        </tbody>
    </table>
</div>
<style>
    .btns-group a {
        margin-right: 10px;
        color: black;
        background: #eee;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .btns-group button {
        background: #eee;
        border: none;
        border-radius: 5px;
        padding: 5px 10px;
    }

</style>
