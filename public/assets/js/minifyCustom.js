var btn=$("#top-scroll-button");$(document).click(function(e){$(e.target).is(".navbar-collapse *")||$(".navbar-collapse").collapse("hide")}),$(function(){$(".notification").on("click",function(){$.notify({title:"<strong>1 New Notification</strong>",message:"<br>hello how are you",icon:"fas fa-info-circle"},{type:"info",allow_dismiss:!0,delay:2e3,placement:{from:"bottom",align:"right"}})})}),$(window).scroll(function(){$(window).scrollTop()>300?btn.addClass("show"):btn.removeClass("show")}),btn.on("click",function(e){e.preventDefault(),$("html, body").animate({scrollTop:0},"200")});var btn1=$("#fixed-chat");$(window).scroll(function(){$(window).scrollTop()>300?btn1.addClass("show"):btn1.removeClass("show")});let loading=document.getElementById("loader");function lodingFunction(){loading.style.display="none"}function create_custom_dropdowns(){$("select").each(function(e,t){if(!$(this).next().hasClass("dropdown-select")){$(this).after('<div class="dropdown-select wide '+($(this).attr("class")||"")+'" tabindex="0"><span class="current"></span><div class="list"><ul></ul></div></div>');var o=$(this).next(),i=$(t).find("option"),n=$(this).find("option:selected");o.find(".current").html(n.data("display-text")||n.text()),i.each(function(e,t){var i=$(t).data("display-text")||"";o.find("ul").append('<li class="option '+($(t).is(":selected")?"selected":"")+'" data-value="'+$(t).val()+'" data-display-text="'+i+'">'+$(t).text()+"</li>")})}}),$(".dropdown-select ul").before('<div class="dd-search"><input id="txtSearchValue" autocomplete="off" onkeyup="filter()" class="dd-searchbox" type="text"></div>')}function filter(){var e=$("#txtSearchValue").val();$(".dropdown-select ul > li").each(function(){$(this).text().toLowerCase().indexOf(e.toLowerCase())>-1?$(this).show():$(this).hide()})}$(document).ready(function(){$(".counter").each(function(){$(this).prop("Counter",0).animate({Counter:$(this).text()},{duration:4e3,easing:"swing",step:function(e){$(this).text(Math.ceil(e))}})})}),$(document).on("click",".dropdown-select",function(e){!$(e.target).hasClass("dd-searchbox")&&($(".dropdown-select").not($(this)).removeClass("open"),$(this).toggleClass("open"),$(this).hasClass("open")?($(this).find(".option").attr("tabindex",0),$(this).find(".selected").focus()):($(this).find(".option").removeAttr("tabindex"),$(this).focus()))}),$(document).on("click",function(e){0===$(e.target).closest(".dropdown-select").length&&($(".dropdown-select").removeClass("open"),$(".dropdown-select .option").removeAttr("tabindex")),e.stopPropagation()}),$(document).on("click",".dropdown-select .option",function(e){$(this).closest(".list").find(".selected").removeClass("selected"),$(this).addClass("selected");var t=$(this).data("display-text")||$(this).text();$(this).closest(".dropdown-select").find(".current").text(t),$(this).closest(".dropdown-select").prev("select").val($(this).data("value")).trigger("change")}),$(document).on("keydown",".dropdown-select",function(e){var t=$($(this).find(".list .option:focus")[0]||$(this).find(".list .option.selected")[0]);if(13==e.keyCode)return $(this).hasClass("open")?t.trigger("click"):$(this).trigger("click"),!1;if(40==e.keyCode)return $(this).hasClass("open")?t.next().focus():$(this).trigger("click"),!1;if(38==e.keyCode){if($(this).hasClass("open")){var t=$($(this).find(".list .option:focus")[0]||$(this).find(".list .option.selected")[0]);t.prev().focus()}else $(this).trigger("click");return!1}if(27==e.keyCode)return $(this).hasClass("open")&&$(this).trigger("click"),!1}),$(document).ready(function(){create_custom_dropdowns()}),$(".toggle-password").click(function(){$(this).toggleClass("fa-eye fa-eye-slash");var e=$($(this).attr("toggle"));"password"==e.attr("type")?e.attr("type","text"):e.attr("type","password")}),jQuery(function(e){var t=function(){var o=e(window).scrollTop()+e(window).height(),i=e(".animatable");0==i.length&&e(window).off("scroll",t),i.each(function(t){var i=e(this);i.offset().top+i.height()-300<o&&i.removeClass("animatable").addClass("animated")})};e(window).on("scroll",t),e(window).trigger("scroll")});const form=document.getElementById("form"),email=document.getElementById("email"),password=document.getElementById("password");function showError(e,t){let o=e.parentElement;o.className="login-inp error";let i=o.querySelector("small");i.innerText=t}function showSucces(e){let t=e.parentElement;t.className="login-inp success"}!function(e){"use strict";var t,o,i,n,s,a,l,r;function c(e,t,o){for(var i=0;i<e.length;i++)t.call(o,i,e[i])}function d(e){var t,o,i,n;(t=e.cloneNode(!0)).style.height="auto",t.style.width=e.getBoundingClientRect().width,t.style.overflow="hidden",e.parentNode.insertBefore(t,e),t.style.maxHeight="none",n=parseInt(t.getBoundingClientRect().height,10),o=parseInt(getComputedStyle(t).maxHeight,10),i=parseInt(e.readmore.defaultHeight,10),e.parentNode.removeChild(t),e.readmore.expandedHeight=n,e.readmore.maxHeight=o,e.readmore.collapsedHeight=o||e.readmore.collapsedHeight||i,e.style.maxHeight="none"}function h(e,t,o){var i,n,s,a;return i=function(e){this.toggle(e.target,t,e)},(n=(s=e,(a=document.createElement("div")).innerHTML=s,a.firstChild)).setAttribute("data-readmore-toggle",t.id),n.setAttribute("aria-controls",t.id),n.addEventListener("click",i.bind(o)),n}i=!!document.querySelectorAll&&!!e.addEventListener,s=0,o=[],n=(a=function(){c(document.querySelectorAll("[data-readmore]"),function(e,t){d(t),t.style.height=("true"===t.getAttribute("aria-expanded")?t.readmore.expandedHeight:t.readmore.collapsedHeight)+"px"})},function(){var e,t,o,i;e=arguments,t=l&&!r,o=this,i=function(){r=null,l||a.apply(o,e)},clearTimeout(r),r=setTimeout(i,100),t&&a.apply(o,e)}),t=function(){var e;function t(t,a){var l,r,p,f,u;i&&(this.options=function e(t,o){var i,n,s,a,l;if(s=({}).hasOwnProperty,arguments.length>2){for(i=[],c(arguments,function(e,t){i.push(t)});i.length>2;)n=i.shift(),l=i.shift(),i.unshift(e(n,l));t=i.shift(),o=i.shift()}for(a in o)s.call(o,a)&&("object"==typeof o[a]?(t[a]=t[a]||{},t[a]=e(t[a],o[a])):t[a]=o[a]);return t}({},e,a),this.options.selector=t,!o[(l=this.options).selector]&&(r=" ",l.embedCSS&&""!==l.blockCSS&&(r+=l.selector+" + [data-readmore-toggle], "+l.selector+"[data-readmore]{"+l.blockCSS+"}"),r+=l.selector+"[data-readmore]{transition: height "+l.speed+"ms;overflow: hidden;}",p=document,f=r,(u=p.createElement("style")).type="text/css",u.styleSheet?u.styleSheet.cssText=f:u.appendChild(p.createTextNode(f)),p.getElementsByTagName("head")[0].appendChild(u),o[l.selector]=!0),window.addEventListener("load",n),window.addEventListener("resize",n),c(document.querySelectorAll(t),function(e,t){var o,i,n,a,l,r;if(o=this.options.startOpen,t.readmore={defaultHeight:this.options.collapsedHeight,heightMargin:this.options.heightMargin},d(t),i=t.readmore.heightMargin,t.getBoundingClientRect().height<=t.readmore.collapsedHeight+i){this.options.blockProcessed&&"function"==typeof this.options.blockProcessed&&this.options.blockProcessed(t,!1);return}n=t.id||(r=++s,String(null==l?"rmjs-":l)+r),t.setAttribute("data-readmore",""),t.setAttribute("aria-expanded",o),t.id=n,a=o?this.options.lessLink:this.options.moreLink,t.parentNode.insertBefore(h(a,t,this),t.nextSibling),t.style.height=(o?t.readmore.expandedHeight:t.readmore.collapsedHeight)+"px",this.options.blockProcessed&&"function"==typeof this.options.blockProcessed&&this.options.blockProcessed(t,!0)},this))}return e={speed:100,collapsedHeight:200,heightMargin:16,moreLink:'<a href="#">Read More</a>',lessLink:'<a href="#">Close</a>',embedCSS:!0,blockCSS:"display: block; width: 100%;",startOpen:!1,blockProcessed:function(){},beforeToggle:function(){},afterToggle:function(){}},t.prototype.toggle=function(e,t,o){var i,n,s,a;o&&o.preventDefault(),n=(i=t.getBoundingClientRect().height<=t.readmore.collapsedHeight)?t.readmore.expandedHeight:t.readmore.collapsedHeight,this.options.beforeToggle&&"function"==typeof this.options.beforeToggle&&this.options.beforeToggle(e,t,!i),t.style.height=n+"px",a=function(o){this.options.afterToggle&&"function"==typeof this.options.afterToggle&&this.options.afterToggle(e,t,i),o.target.setAttribute("aria-expanded",i),o.target.removeEventListener("transitionend",a)},t.addEventListener("transitionend",a.bind(this)),s=i?this.options.lessLink:this.options.moreLink,e.parentNode.replaceChild(h(s,t,this),e)},t.prototype.destroy=function(){},t}(),e.Readmore=t}(this),new Readmore("#info",{moreLink:'<a href="#">Usage, examples, and options</a>',collapsedHeight:384,afterToggle:function(e,t,o){o||$("html, body").animate({scrollTop:t.offset().top},{duration:100})}}),new Readmore("article",{speed:500,heightMargin:50}),jQuery(function(e){var t=window.location.href;e(".navbar-nav li a").each(function(){this.href===t&&e(this).addClass("active")})});var selector=".labsbutton button";$(selector).on("click",function(){$(selector).removeClass("active"),$(this).addClass("active")}),$(function(){let e=location.pathname.split("/")[2];$(".labsbutton button").each(function(){-1!==$(this).attr("onclick").indexOf(e)&&$("html,body").animate({scrollTop:$(".scroll-head").offset().top})})}),$(function(){let e=location.pathname.split("/")[2];$(".labsbutton button").each(function(){-1!==$(this).attr("onclick").indexOf(e)&&$("html,body").animate({scrollTop:$(".scroll-substance").offset().top-100})})}),$(document).ready(function(){$(window).scroll(function(){$(this).scrollTop()>200?$(".go-back").fadeIn(200):$(".go-back").fadeOut(200)}),$(".go-back").click(function(e){e.preventDefault(),$("html, body").animate({scrollTop:0},300)})});