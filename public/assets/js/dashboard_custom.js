// =============== Dashboard Layout Starts =========================
const mobileScreen = window.matchMedia("(max-width: 990px )");
$(document).ready(function () {
  $(".dashboard-nav-dropdown-toggle").click(function () {
    $(this)
      .closest(".dashboard-nav-dropdown")
      .toggleClass("show")
      .find(".dashboard-nav-dropdown")
      .removeClass("show");
    $(this).parent().siblings().removeClass("show");
  });
  $(".menu-toggle").click(function () {
    if (mobileScreen.matches) {
      $(".dashboard-nav").toggleClass("mobile-show");
    } else {
      $(".dashboard").toggleClass("dashboard-compact");
    }
  });
});

// =============== Dashboard Layout Ends =========================



// =============== SEARCHABLE SELECT START =====================
function create_custom_dropdowns() {
  $('.edit-select-new select').each(function (i, select) {
      if (!$(this).next().hasClass('dropdown-select')) {
          $(this).after('<div class="dropdown-select wide ' + ($(this).attr('class') || '') + '" tabindex="0"><span class="current"></span><div class="list"><ul></ul></div></div>');
          var dropdown = $(this).next();
          var options = $(select).find('option');
          var selected = $(this).find('option:selected');
          dropdown.find('.current').html(selected.data('display-text') || selected.text());
          options.each(function (j, o) {
              var display = $(o).data('display-text') || '';
              dropdown.find('ul').append('<li class="option ' + ($(o).is(':selected') ? 'selected' : '') + '" data-value="' + $(o).val() + '" data-display-text="' + display + '">' + $(o).text() + '</li>');
          });
      }
  });

  $('.dropdown-select ul').before('<div class="dd-search"><input id="txtSearchValue" autocomplete="off" onkeyup="filter()" class="dd-searchbox" type="text"></div>');
}

// Event listeners

// Open/close
$(document).on('click', '.dropdown-select', function (event) {
  if($(event.target).hasClass('dd-searchbox')){
      return;
  }
  $('.dropdown-select').not($(this)).removeClass('open');
  $(this).toggleClass('open');
  if ($(this).hasClass('open')) {
      $(this).find('.option').attr('tabindex', 0);
      $(this).find('.selected').focus();
  } else {
      $(this).find('.option').removeAttr('tabindex');
      $(this).focus();
  }
});

// Close when clicking outside
$(document).on('click', function (event) {
  if ($(event.target).closest('.dropdown-select').length === 0) {
      $('.dropdown-select').removeClass('open');
      $('.dropdown-select .option').removeAttr('tabindex');
  }
  event.stopPropagation();
});

function filter(){
  var valThis = $('#txtSearchValue').val();
  $('.dropdown-select ul > li').each(function(){
   var text = $(this).text();
      (text.toLowerCase().indexOf(valThis.toLowerCase()) > -1) ? $(this).show() : $(this).hide();
 });
};
// Search

// Option click
$(document).on('click', '.dropdown-select .option', function (event) {
  $(this).closest('.list').find('.selected').removeClass('selected');
  $(this).addClass('selected');
  var text = $(this).data('display-text') || $(this).text();
  $(this).closest('.dropdown-select').find('.current').text(text);
  $(this).closest('.dropdown-select').prev('select').val($(this).data('value')).trigger('change');
});

// Keyboard events
$(document).on('keydown', '.dropdown-select', function (event) {
  var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
  // Space or Enter
  //if (event.keyCode == 32 || event.keyCode == 13) {
  if (event.keyCode == 13) {
      if ($(this).hasClass('open')) {
          focused_option.trigger('click');
      } else {
          $(this).trigger('click');
      }
      return false;
      // Down
  } else if (event.keyCode == 40) {
      if (!$(this).hasClass('open')) {
          $(this).trigger('click');
      } else {
          focused_option.next().focus();
      }
      return false;
      // Up
  } else if (event.keyCode == 38) {
      if (!$(this).hasClass('open')) {
          $(this).trigger('click');
      } else {
          var focused_option = $($(this).find('.list .option:focus')[0] || $(this).find('.list .option.selected')[0]);
          focused_option.prev().focus();
      }
      return false;
      // Esc
  } else if (event.keyCode == 27) {
      if ($(this).hasClass('open')) {
          $(this).trigger('click');
      }
      return false;
  }
});

$(document).ready(function () {
  create_custom_dropdowns();
});
// =============== SEARCHABLE SELECT ENDS =====================

// ========== For Navbar Active starts ==============
jQuery(function($) {
  var path = window.location.href;
  $('.dashboard-nav-list a').each(function() {
   if (this.href === path) {
    $(this).addClass('active');
   }
  });
 });
 jQuery(function($) {
  var path = window.location.href;
  $('.dashboard-nav-dropdown-menu a').each(function() {
   if (this.href === path) {
    $(this).addClass('active');
   }
  });
 });

// ========== For Navbar Active Ends ==============

// ========== For Notification Starts ==============
// function myNoticationFunction() {
//   var x = document.getElementById("myNotificationDiv");
//   if (x.style.display === "none") {
//     x.style.display = "block";
//   } else {
//     x.style.display = "none";
//         // x.hide();
//   }
// }
// $(document).mouseup(function(e){
//     var container = $("#myNotificationDiv");
//     // if the target of the click isn't the container nor a descendant of the container
//     if (!container.is(e.target) && container.has(e.target).length === 0)
//     {
//         container.hide();
//     }
// });
// $(function() {
//     $('body').on('mouseup', function() {
//         $('#myNotificationDiv').hide();
//     });
// });
var $el = $(".fa-bell");
var $ee = $(".noti-box");
$el.click(function(e){
  e.stopPropagation();
  $(".noti-box").toggleClass('active');
});
$(document).on('click',function(e){
  if(($(e.target) != $el) && ($ee.hasClass('active'))){
  $ee.removeClass('active');
  // console.log("yes");
}
});
// ========== For Notification Ends ==============
