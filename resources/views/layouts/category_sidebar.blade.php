<nav id="sidebar" class="col-md-2 col-sm-12">
    <div class="sidebar-header theme-color">
        <span class="fa fa-capsules white-color text-center col-md-12 " style="font-size: 50px"></span>
        <h3 style="text-align: center;">
            Categories
        </h3>
    </div>

    <div style="height:350px; overflow-y:scroll !important;" class="scrollable-element">

        <ul class="list-unstyled components">
            {{-- <p style="background-color:#1b3966">Medicine Categories</p> --}}
            @foreach ($data['sidebar']['sideMenus'] as $key => $item)
                <li>
                    @php
                        $key_sp = explode('|', $key);
                    @endphp
                    <a href="#{{ str_replace(' ', '', str_replace('&', '', str_replace(',', '', "$key_sp[0]"))) }}"
                        data-toggle="collapse" aria-expanded="true"
                        class="dropdown-toggle active" style="display:none;">{{ $key_sp[0] }}</a>

                    @if ($slug == $key_sp[1])
                        <ul class="list-unstyled collapse show"
                            id="{{ str_replace(' ', '', str_replace('&', '', str_replace(',', '', "$key_sp[0]"))) }}">
                        @else
                            <ul class="collapse list-unstyled show"
                                id="{{ str_replace(' ', '', str_replace('&', '', str_replace(',', '', "$key_sp[0]"))) }}">
                    @endif
                    <?php  foreach ($item as $val2) { $aa = explode("|",$val2); ?>
                <li><a href="/{{ $data['url_type'] }}/<?= $aa[2] ?>"><?= $aa[1] ?></a></li>
                <?php	} ?>
        </ul>
        </li>
        @endforeach
        </ul>
    </div>
</nav>
