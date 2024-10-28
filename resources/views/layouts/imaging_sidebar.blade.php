<nav id="sidebar" class="col-md-2">
    <div class="sidebar-header theme-color">
        <span class="fa fa-capsules white-color  col-md-12 text-center" style="font-size: 50px"></span>
        <h3 class="text-center">Imaging Services</h3>
    </div>

    {{-- {{ dd($data['sidebar']) }} --}}

    <ul class="list-unstyled components">
        <p style="background-color:#1b3966">Categories</p>
        @foreach ($data['sidebar'] as $key => $item)
            <li>
                <a href="/imaging/{{ $item->slug }}"
                    class="active">{{ $item->product_parent_category }}</a>
            </li>
        @endforeach
    </ul>

</nav>
