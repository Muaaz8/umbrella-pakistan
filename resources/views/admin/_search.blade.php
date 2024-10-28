
<!-- Morphing Search  -->
<div id="morphsearch" class="morphsearch">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12" style="padding-left:10px;">
            <form class="search_form">
                <div class="form-group">
                    <input id="search" value="" type="search" placeholder="Search...." class="form-control morphsearch-input p-2" style="    border: 2px solid white;
                    background-color: #364d81;" maxlength='45' />
                    <button class="morphsearch-submit" type="submit">Search</button>                    
                </div>                 
            </form>
            <span class="morphsearch-close" style="color:white;"></span>
        </div>
    </div>  
    <div class="morphsearch-content clearfix">            
        <div class="col-12"> 
            <div class="row"> 
            <div class="col-12"> 
                <div class="row">
                    <div class="col-3 text-center"> 
                        <h3>Medicine</h3>
                    </div>                   
                    <div class="col-3 text-center"> 
                        <h3>Lab-Test</h3>
                    </div>                   
                                       
                    <div class="col-3 text-center"> 
                        <h3>Imaging</h3>
                    </div> 
                    
                    <div class="col-3 text-center"> 
                        @if(Auth::user()->user_type=='doctor')
                            <h3>My Patient</h3>
                        @elseif(Auth::user()->user_type=='patient')
                            <h3>Online Doctor</h3>                    
                        @endif
                    </div>                  
                </div>                   
            </div>                   
                <div class="col-3" id="searchMedison"> 
                    
                </div>  
                <div class="col-3 text-center" id="searchIab"> 
                    
                </div>  
                <div class="col-3" id="searchImaging"> 
                    
                </div>  
                <div class="col-3" id="searchPatient"> 
                    
                </div>  
                
            </div>
        </div>       
    </div>
</div>

    