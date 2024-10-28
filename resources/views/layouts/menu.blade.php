<li class="{{ Request::is('productCategories*') ? 'active' : '' }}">
    <a href="{{ route('productCategories.index') }}"><i class="fa fa-edit"></i><span>Product Categories</span></a>
</li>










<li class="{{ Request::is('productsSubCategories*') ? 'active' : '' }}">
    <a href="{{ route('productsSubCategories.index') }}"><i class="fa fa-edit"></i><span>Products Sub
            Categories</span></a>
</li>























<li class="{{ Request::is('allProducts*') ? 'active' : '' }}">
    <a href="{{ route('allProducts.index') }}"><i class="fa fa-edit"></i><span>All Products</span></a>
</li>






<li class="{{ Request::is('mapMarkers*') ? 'active' : '' }}">
    <a href="{{ route('mapMarkers.index') }}"><i class="fa fa-edit"></i><span>Map Markers</span></a>
</li>

<li class="{{ Request::is('tblOrders*') ? 'active' : '' }}">
    <a href="{{ route('orders.index') }}"><i class="fa fa-edit"></i><span>Tbl Orders</span></a>
</li>

<li class="{{ Request::is('mentalConditions*') ? 'active' : '' }}">
    <a href="{{ route('mentalConditions.index') }}"><i class="fa fa-edit"></i><span>Mental Conditions</span></a>
</li>

<li class="{{ Request::is('tblFaqs*') ? 'active' : '' }}">
    <a href="{{ route('tblFaqs.index') }}"><i class="fa fa-edit"></i><span>Tbl Faqs</span></a>
</li>
