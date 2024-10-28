<nav id="sidebar" class="col-5 col-md-3">
    <div class="sidebar-header theme-color" >
        <span class="fa fa-capsules white-color mr-2 col-md-6 offset-2" style="font-size: 50px"></span>
        <h3>
            Categories
        </h3>
    </div>


    <ul class="list-unstyled components">
        {{-- <p style="background-color:#1b3966">Medicine Categories</p> --}}
        @foreach ($data['sidebar']['sideMenus'] as $key => $item)
        <li>
            @php
            $key_sp=explode('|',$key);
        @endphp
        <a 
        href="#{{ str_replace(' ', '', str_replace("&","",str_replace(",","","$key_sp[0]"))) }}" 
        data-toggle="collapse" 
        aria-expanded="true" 
        class="dropdown-toggle active"x>{{ $key_sp[0] }}</a>
    
        @if($slug==$key_sp[1])
        <ul class="collapse show list-unstyled" id="{{ str_replace(' ', '', str_replace("&","",str_replace(",","","$key_sp[0]"))) }}">
        @else
        <ul class="collapse list-unstyled" id="{{ str_replace(' ', '', str_replace("&","",str_replace(",","","$key_sp[0]"))) }}">
        @endif
        <?php  foreach ($item as $val2) { $aa = explode("|",$val2); ?>
                <li><a href="/{{ $data['url_type'][1] }}/<?= $aa[2] ?>"><?= $aa[1] ?></a></li>
          <?php	} ?>
          </ul>
        </li>
        @endforeach
    </ul>
</nav>