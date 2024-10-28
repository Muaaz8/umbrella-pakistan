<link rel="stylesheet" href="{{ asset('asset_admin/css/all_product_table.css')}}">
<table class="table tblData table-hover" id="allProducts-table">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>Category Name</th>
            <th>Sale Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($allProducts as $allProduct)

        <tr>
            <td>
                @if (empty($allProduct->panel_name))
                {{ $allProduct->name }}
                @else
                {{ $allProduct->panel_name }}
                @endif
            </td>
            <td style="width:40%;">
                @foreach ($allProduct->cat_names as $name)
                    @if(auth()->user()->user_type=='admin')
                        {{ $name['name'] }}<br>
                    @else
                        {{ $name->name }}<br>
                    @endif
                @endforeach
            </td>
            <td>{{ $allProduct->sale_price }}</td>
            <td>
                {!! Form::open(['route' => ['allProducts.destroy', $allProduct->id], 'method' => 'delete']) !!}
                <div class='btns-group d-flex'>
                    <div class="icon_eye">
                        <a href="{{ route('allProducts.show', [$allProduct->id]) }}" class=''><i
                                class="fa fa-eye pt-2"></i></a>
                    </div>
                    <?php if (!empty($allProduct->panel_name) && $allProduct->mode === 'lab-test') {
                            $edit_url = 'allProducts/' . $allProduct->id . '/edit?form_type=panel-test';
                        } elseif (empty($allProducts->panel_name) && $allProduct->mode === 'imaging') {
                            $edit_url = 'allProducts/' . $allProduct->id . '/edit?form_type=imaging';
                        } elseif (empty($allProducts->panel_name) && $allProduct->mode === 'lab-test' && empty($allProduct->including_test)) {
                            $edit_url = 'allProducts/' . $allProduct->id . '/edit?form_type=lab-test';
                        } elseif (empty($allProducts->panel_name) && $allProduct->mode === 'medicine' && empty($allProduct->including_test)) {
                            $edit_url = 'allProducts/' . $allProduct->id . '/edit?form_type=pharmacy';
                        } ?>
                    <div class="icon_edit">
                        <a href="{{ $edit_url }}" class=''><i class="fa fa-edit pt-2"></i></a>
                    </div>
                    <div class="icon_trash">
                        @if ($user->user_type == 'admin')
                        {!! Form::button('<i class="fa fa-trash pt-2"></i>', ['type' => 'submit', 'class' => '',
                        'onclick' => "return confirm('Are you sure?')"]) !!}
                        @else
                        <a href="{{ route('prod_del_request', $allProduct->id) }}"><i class="fa fa-trash pt-2"></i></a>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No Products Added</td>
        </tr>
        @endforelse
    </tbody>
</table>
