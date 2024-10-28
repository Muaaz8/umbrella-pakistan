{{-- <style>
    #sidebar{
        width:350px !important;
    }
    .vertical-nav{
        width: 100% !important;
        padding-left: 0px;
        padding-right:0px;
    }
    @media only screen
and (min-device-width:768px) 
and (max-device-width:1024px)
and (orientation:portrait)
{
        #sidebar{
        width:100% !important;
    }
    .navtoggler{
        background:  rgb(255, 255, 255) !important;
        margin-left: 30px !important;
    }
}
    @media only screen and (max-width:767px){
        #sidebar{
        width:100% !important;
    }
    .navtoggler{
        background:  rgb(255, 255, 255) !important;
        margin-left: 30px !important;
    }
    }
    /* @media (min-width:992px) {    
  .vertical-nav {
      width: 200px;
      height: 100%;
      padding-top: 30px
    } */

</style> --}}
{{-- <div id="sidebar">
    <nav class="navbar navbar-expand-lg navbar-light ">
        <button class="navbar-toggler navtoggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class=" flex-column vertical-nav">
                <div class=" sidebar-header theme-color">
                    <span class="fa fa-capsules white-color  col-md-12 text-center" style="font-size: 50px ; "></span>
                    <h3 class="text-center">Lab Tests</h3>
                </div>

                {{-- {{ dd($data['sidebar']) }} --}}
{{-- <p style=" color:#fff; font-size:30px !important; background-color:#08295a" class="text-center">
                    Categories</p>
                <div style="height:300px !important; width:100% !important; overflow-y:scroll !important;">
                    
                    @foreach ($data['sidebar'] as $key => $item)
                        <li style="border-bottom:1px solid #fff; height:40px;">
                            <a href="/labs/{{ $item->slug }}"
                                class="">{{ $item->product_parent_category }}</a>
                        </li>
                    @endforeach
                </div>
            </ul>

        </div>
    </nav>
</div> --}}
{{-- <div class="Container">
    <div class="row">
        <div class="col-12 button-div">
            {{-- <div class="button-div"> --}}
                <div>
                <button class="labsbutton">  
                    <img src="uploads/601a49b9caa65.png" />
                </button>
                 <p >
                     <a href="/labs"  class="labs-service">All </a>
                 </p>
            </div>
                @foreach ($data['sidebar'] as $key => $item)
                <div>
                <button class="labsbutton">
                    <img src="uploads/{{ $item->thumbnail}}" />
                </button>
                <p class="text-center">
                    <a href="/labs/{{ $item->slug }}"
                        class="labs-service">{{ $item->product_parent_category }}
                    </a>
                </p>
        </div>
            @endforeach --}}

            {{-- </div> --}}

        </div>
    </div>
</div>
