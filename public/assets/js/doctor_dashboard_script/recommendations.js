$(".but").click (function(){
    // Close all open windows
    $(".content").stop().slideUp(300); 
    // Toggle this window open/close
    $(this).next(".content").stop().slideToggle(300);
    $(".content1").stop().slideUp(300); 

  });

  
  $(".but1").click (function(){
    // Close all open windows
    $(".content1").stop().slideUp(300); 
    
    // Toggle this window open/close
    $(this).next(".content1").stop().slideToggle(300);
    
    
  });

  function showHide(elem) {
    if(elem.selectedIndex !== 0) {
         //hide the divs
         for(var i=0; i < divsO.length; i++) {
             divsO[i].style.display = 'none';
        }
        //unhide the selected div
        document.getElementById(elem.value).style.display = 'block';
    }
}
 
// window.onload=function() {
//     //get the divs to show/hide
//     divsO = document.getElementById("specialization").getElementsByClassName('show-hide');
// };

