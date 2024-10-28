<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-cyan">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader --> 
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- #Float icon -->
@hasanyrole('doctor')
<ul id="f-menu" class="mfb-component--br mfb-zoomin" data-mfb-toggle="hover">
  
  <li class="mfb-component__wrap">
    
    <a href="#" class="mfb-component__button--main g-bg-cyan">
      <i class="mfb-component__main-icon--resting zmdi zmdi-plus"></i>
      
      <i class="mfb-component__main-icon--active zmdi zmdi-close"></i>
    </a>
   
    
    <ul class="mfb-component__list">
      <li>
        <a href="{{url('doctor_calendar')}}" data-mfb-label="Doctor Schedule" class="mfb-component__button--child bg-blue">
          <i class="zmdi zmdi-calendar mfb-component__child-icon"></i>
        </a>
      </li>
      <li>
        <a href="{{url('patients')}}" data-mfb-label="Patients List" class="mfb-component__button--child bg-blue">
          <i class="zmdi zmdi-account-o mfb-component__child-icon"></i>
        </a>
      </li>

      <li>
        <a href="{{url('wallet_page')}}" data-mfb-label="Payments" class="mfb-component__button--child bg-blue">
          <i class="zmdi zmdi-balance-wallet mfb-component__child-icon"></i>
        </a>
      </li>
    </ul>
  </li>
</ul> 


 @endhasanyrole
<!-- #Float icon -->