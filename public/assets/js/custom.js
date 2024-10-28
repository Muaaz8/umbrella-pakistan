var btn = $('#top-scroll-button');

$(document).click(function (event) {

  /// If *navbar-collapse* is not among targets of event
  if (!$(event.target).is('.navbar-collapse *')) {

    /// Collapse every *navbar-collapse*
    $('.navbar-collapse').collapse('hide');

  }
});

// =============== For Nottification Start============
$(function () {
    $(".notification").on("click", function () {
        $.notify(
          {
              title: "<strong>1 New Notification</strong>",
              message: "<br>hello how are you",
              icon: 'fas fa-info-circle',
          },
          {
              type: "info",
              allow_dismiss: true,
              delay: 2000,
              placement: {
                from: "bottom",
                align: "right"
              },
          }
        );
    });
});

// =============== For Nottification Ends============

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '200');
});


var btn1 = $('#fixed-chat');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn1.addClass('show');
  } else {
    btn1.removeClass('show');
  }
});
let loading = document.getElementById("loader")

function lodingFunction(){
  loading.style.display="none";
}
//  ********INDEX PAGE ENDS************

$(document).ready(function() {

    $('.counter').each(function () {
    $(this).prop('Counter',0).animate({
    Counter: $(this).text()
    }, {
    duration: 4000,
    easing: 'swing',
    step: function (now) {
        $(this).text(Math.ceil(now));
    }
    });
    });

    });

//  ********ABOUT PAGE ENDS************


//  ********PHARMACY PAGE START************

function create_custom_dropdowns() {
    $('select').each(function (i, select) {
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


//  ********PHARMACY PAGE ENDS************

$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });



// ===========animation start====================

jQuery(function($) {

    // Function which adds the 'animated' class to any '.animatable' in view
    var doAnimations = function() {

      // Calc current offset and get all animatables
      var offset = $(window).scrollTop() + $(window).height(),
          $animatables = $('.animatable');

      // Unbind scroll handler if we have no animatables
      if ($animatables.length == 0) {
        $(window).off('scroll', doAnimations);
      }

      // Check all animatables and animate them if necessary
          $animatables.each(function(i) {
         var $animatable = $(this);
              if (($animatable.offset().top + $animatable.height() - 300) < offset) {
          $animatable.removeClass('animatable').addClass('animated');
              }
      });

      };

    // Hook doAnimations on scroll, and trigger a scroll
      $(window).on('scroll', doAnimations);
    $(window).trigger('scroll');

  });

// ===========animation ends====================










// ======== login validation ==================

const form = document.getElementById('form');
const email = document.getElementById('email');
const password = document.getElementById('password');

//Show input error messages
function showError(input, message) {
    const formControl = input.parentElement;
    formControl.className = 'login-inp error';
    const small = formControl.querySelector('small');
    small.innerText = message;
}

//show success colour
function showSucces(input) {
    const formControl = input.parentElement;
    formControl.className = 'login-inp success';
}

//checkRequired fields
// function checkRequired(inputArr) {
//   inputArr.forEach(function(input){
//       if(input.value.trim() === ''){
//           showError(input,` ${getFieldName(input)} is required`)
//       }else {
//           showSucces(input);
//       }
//   });
// }

//check email is valid
// function checkEmail(input) {
//     const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//     if(re.test(input.value.trim())) {
//         showSucces(input)
//     }else {
//         showError(input,'Email is not invalid');
//     }
// }





//check input Length
// function checkLength(input, min ,max) {
//     if(input.value.length < min) {
//         showError(input, `${getFieldName(input)} do not match`);
//     }else if(input.value.length > max) {
//         showError(input, `${getFieldName(input)} do not match`);
//     }else {
//         showSucces(input);
//     }
// }

//get FieldName
// function getFieldName(input) {
//     return input.id.charAt(0).toUpperCase() + input.id.slice(1);
// }


//Event Listeners
// form.addEventListener('submit',function(e) {
//     e.preventDefault();

//     checkRequired([email, password]);
//     checkLength(password,6,25);
//     checkEmail(email);
// });
// ======== login validation end ==================




// ======== Read-More Starts ==================


(function(root) {
    'use strict';

    var Readmore, isCssEmbeddedFor, isEnvironmentSupported, resizeBoxes, uniqueIdCounter;

    function forEach(array, callback, scope) {
      for (var i = 0; i < array.length; i++) {
        callback.call(scope, i, array[i]);
      }
    };

    function extend(child, parent) {
      var args, c1, hasProp, key, p1;

      hasProp = {}.hasOwnProperty;

      if (arguments.length > 2) {
        args = [];

        forEach(arguments, function(i, value) {
          args.push(value);
        });

        while (args.length > 2) {
          c1 = args.shift();
          p1 = args.shift();
          args.unshift(extend(c1, p1));
        }

        child = args.shift();
        parent = args.shift();
      }

      for (key in parent) {
        if (hasProp.call(parent, key)) {
          if (typeof parent[key] === 'object') {
            child[key] = child[key] || {};
            child[key] = extend(child[key], parent[key]);
          } else {
            child[key] = parent[key];
          }
        }
      }
      return child;
    };

    function debounce(func, wait, immediate) {
      var timeout;

      return function() {
        var args, callNow, context, later;

        args = arguments;
        callNow = immediate && !timeout;
        context = this;
        later = function() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };

        clearTimeout(timeout);
        timeout = setTimeout(later, wait);

        if (callNow) func.apply(context, args);
      };
    }

    function uniqueId(prefix) {
      var id;

      id = ++uniqueIdCounter;

      return String(prefix == null ? 'rmjs-' : prefix) + id;
    }

    function setBoxHeights(element) {
      var clonedElement, cssMaxHeight, defaultHeight, expandedHeight, width;

      clonedElement = element.cloneNode(true);
      clonedElement.style.height = 'auto';
      clonedElement.style.width = element.getBoundingClientRect().width;
      clonedElement.style.overflow = 'hidden';

      element.parentNode.insertBefore(clonedElement, element);

      clonedElement.style.maxHeight = 'none';

      expandedHeight = parseInt(clonedElement.getBoundingClientRect().height, 10);
      cssMaxHeight = parseInt(getComputedStyle(clonedElement).maxHeight, 10);
      defaultHeight = parseInt(element.readmore.defaultHeight, 10);

      element.parentNode.removeChild(clonedElement);

      // Store our measurements.
      element.readmore.expandedHeight = expandedHeight;
      element.readmore.maxHeight = cssMaxHeight;
      element.readmore.collapsedHeight = cssMaxHeight || element.readmore.collapsedHeight || defaultHeight;

      element.style.maxHeight = 'none';
    }

    function createElementFromString(htmlString) {
      var div;

      div = document.createElement('div');
      div.innerHTML = htmlString;

      return div.firstChild;
    }

    function embedCSS(options) {
      var styles;

      if (!isCssEmbeddedFor[options.selector]) {
        styles = ' ';

        if (options.embedCSS && options.blockCSS !== '') {
          styles += options.selector + ' + [data-readmore-toggle], ' +
            options.selector + '[data-readmore]{' +
              options.blockCSS +
            '}';
        }

        // Include the transition CSS even if embedCSS is false
        styles += options.selector + '[data-readmore]{' +
          'transition: height ' + options.speed + 'ms;' +
          'overflow: hidden;' +
        '}';

        (function(d, u) {
          var css = d.createElement('style');
          css.type = 'text/css';

          if (css.styleSheet) {
            css.styleSheet.cssText = u;
          }
          else {
            css.appendChild(d.createTextNode(u));
          }

          d.getElementsByTagName('head')[0].appendChild(css);
        }(document, styles));

        isCssEmbeddedFor[options.selector] = true;
      }
    }

    function buildToggle(link, element, scope) {
      var clickHandler, toggle;

      clickHandler = function(event) {
        this.toggle(event.target, element, event);
      };

      toggle = createElementFromString(link);
      toggle.setAttribute('data-readmore-toggle', element.id);
      toggle.setAttribute('aria-controls', element.id);
      toggle.addEventListener('click', clickHandler.bind(scope));

      return toggle;
    }

    isEnvironmentSupported = !!document.querySelectorAll && !!root.addEventListener;
    uniqueIdCounter = 0;
    isCssEmbeddedFor = [];

    resizeBoxes = debounce(function() {
      forEach(document.querySelectorAll('[data-readmore]'), function(i, element) {
        setBoxHeights(element);

        element.style.height = ((element.getAttribute('aria-expanded') === 'true') ? element.readmore.expandedHeight : element.readmore.collapsedHeight) + 'px';
      });
    }, 100);

    Readmore = (function() {
      var defaults;

      defaults = {
        speed: 100,
        collapsedHeight: 200,
        heightMargin: 16,
        moreLink: '<a href="#">Read More</a>',
        lessLink: '<a href="#">Close</a>',
        embedCSS: true,
        blockCSS: 'display: block; width: 100%;',
        startOpen: false,

        // callbacks
        blockProcessed: function() {},
        beforeToggle: function() {},
        afterToggle: function() {}
      };

      function Readmore(selector, options) {
        if (!isEnvironmentSupported) return;

        this.options = extend({}, defaults, options);
        this.options.selector = selector;

        embedCSS(this.options);

        // Need to resize boxes when the page has fully loaded.
        window.addEventListener('load', resizeBoxes);
        window.addEventListener('resize', resizeBoxes)

        forEach(document.querySelectorAll(selector), function(i, element) {
          var expanded, heightMargin, id, toggleLink;

          expanded = this.options.startOpen;

          element.readmore = {
            defaultHeight: this.options.collapsedHeight,
            heightMargin: this.options.heightMargin
          };

          setBoxHeights(element);

          heightMargin = element.readmore.heightMargin;

          if (element.getBoundingClientRect().height <= element.readmore.collapsedHeight + heightMargin) {
            if (this.options.blockProcessed && typeof this.options.blockProcessed === 'function') {
              this.options.blockProcessed(element, false);
            }
            return;
          }
          else {
            id = element.id || uniqueId();

            element.setAttribute('data-readmore', '');
            element.setAttribute('aria-expanded', expanded);
            element.id = id;

            toggleLink = expanded ? this.options.lessLink : this.options.moreLink;

            element.parentNode.insertBefore(buildToggle(toggleLink, element, this), element.nextSibling);

            element.style.height = (expanded ? element.readmore.expandedHeight : element.readmore.collapsedHeight) + 'px';

            if (this.options.blockProcessed && typeof this.options.blockProcessed === 'function') {
              this.options.blockProcessed(element, true);
            }
          }
        }, this);
      }

      Readmore.prototype.toggle = function(trigger, element, event) {
        var expanded, newHeight, toggleLink, transitionendHandler;

        if (event) event.preventDefault();

        // this.element only exists for jQuery-ified elements, may not make sense now
        // trigger = trigger || document.querySelector('[aria-controls="' + this.element.id + '"]');
        // element = element || this.element;

        expanded = element.getBoundingClientRect().height <= element.readmore.collapsedHeight;
        newHeight = expanded ? element.readmore.expandedHeight : element.readmore.collapsedHeight;

        // Fire beforeToggle callback
        // Since we determined the new "expanded" state above we're now out of sync
        // with our true current state, so we need to flip the value of `expanded`
        if (this.options.beforeToggle && typeof this.options.beforeToggle === 'function') {
          this.options.beforeToggle(trigger, element, !expanded);
        }

        element.style.height = newHeight + 'px';

        transitionendHandler = function(event) {
          if (this.options.afterToggle && typeof this.options.afterToggle === 'function') {
            this.options.afterToggle(trigger, element, expanded);
          }

          event.target.setAttribute('aria-expanded', expanded);
          event.target.removeEventListener('transitionend', transitionendHandler);
        };

        element.addEventListener('transitionend', transitionendHandler.bind(this));

        toggleLink = expanded ? this.options.lessLink : this.options.moreLink;

        trigger.parentNode.replaceChild(buildToggle(toggleLink, element, this), trigger);
      };

      Readmore.prototype.destroy = function() {}

      return Readmore;

    })();

    root.Readmore = Readmore;
  })(this);




  new Readmore('#info', {
        moreLink: '<a href="#">Usage, examples, and options</a>',
        collapsedHeight: 384,
        afterToggle: function(trigger, element, expanded) {
          if(! expanded) { // The "Close" link was clicked
            $('html, body').animate({scrollTop: element.offset().top}, {duration: 100});
          }
        }
      });
      new Readmore('article', {
        speed: 500,
        heightMargin: 50
      });


// ======== Read-More Ends ==================

// ========== For Navbar Active starts ==============
jQuery(function($) {
  var path = window.location.href;
  $('.navbar-nav li a').each(function() {
   if (this.href === path) {
    $(this).addClass('active');
   }
  });
 });
// ========== For Navbar Active Ends ==============


// ========== home catagories button Active starts ==============

var selector = '.labsbutton button';

$(selector).on('click', function(){
    $(selector).removeClass('active');
    $(this).addClass('active');
});

// ========== home catagories button Active Ends ==============





// ============ for scroll next page start ============

$(function(){
    var current = location.pathname;
    let paramString = current.split('/')[2];
    $('.labsbutton button').each(function(){
        var $this = $(this);
        // if the current path is like this link, make it active
        if($this.attr('onclick').indexOf(paramString) !== -1){
       
            $('html,body').animate({
              scrollTop: $(".scroll-head").offset().top});
        }
    })

})

$(function(){
  var current = location.pathname;
  let paramString = current.split('/')[2];
  $('.labsbutton button').each(function(){
      var $this = $(this);
      // if the current path is like this link, make it active
      if($this.attr('onclick').indexOf(paramString) !== -1){
     
          $('html,body').animate({
            scrollTop: $(".scroll-substance").offset().top-100});
      }
  })

})

// ============ for scroll next page Ends ============


// ============ scroll back Page button starts ============


$(document).ready(function() {
  // Show or hide the sticky footer button
  $(window).scroll(function() {
    if ($(this).scrollTop() > 200) {
      $('.go-back').fadeIn(200);
    } else {
      $('.go-back').fadeOut(200);
    }
  });

  // Animate the scroll to back
  $('.go-back').click(function(event) {
    event.preventDefault();

    $('html, body').animate({scrollTop: 0}, 300);
  })
});


// ============ scroll back Page button Ends ============





