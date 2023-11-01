    $(".toggle-nav").on('click',function () {
        "use strict";
        $('.nav-menu').css("left", "0px");
    });
    $(".mobile-back").on('click',function () {
        "use strict";
        $('.nav-menu').css("left", "-410px");
    });

    $(".page-wrapper").attr("class", "page-wrapper "+localStorage.getItem("page-wrapper"));
    $(".page-body-wrapper").attr("class", "page-body-wrapper "+localStorage.getItem("page-body-wrapper"));

    if (localStorage.getItem("page-wrapper") === null) {
        $(".page-wrapper").addClass("compact-wrapper");
    }   

  // left sidebar and horizotal menu
    if($('#pageWrapper').hasClass('compact-wrapper')){
          jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
          jQuery('.submenu-title').on('click',function () {
              jQuery('.submenu-title').removeClass('active');
              jQuery('.submenu-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              jQuery('.submenu-content').slideUp('normal');
              if (jQuery(this).next().is(':hidden') == true) {
                  jQuery(this).addClass('active');
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                  jQuery(this).next().slideDown('normal');
              } else {
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              }
          });
          jQuery('.submenu-content').hide();

          jQuery('.menu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
          jQuery('.menu-title').on('click',function () {
              jQuery('.menu-title').removeClass('active');
              jQuery('.menu-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              jQuery('.menu-content').slideUp('normal');
              if (jQuery(this).next().is(':hidden') == true) {
                  jQuery(this).addClass('active');
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                  jQuery(this).next().slideDown('normal');
              } else {
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              }
          });
          jQuery('.menu-content').hide();
    } else if ($('#pageWrapper').hasClass('horizontal-wrapper')) {
        var contentwidth = jQuery(window).width();
        if ((contentwidth) < '992') {
            $('#pageWrapper').removeClass('horizontal-wrapper').addClass('compact-wrapper');
            $('.page-body-wrapper').removeClass('horizontal-menu').addClass('sidebar-icon');
            jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
          jQuery('.submenu-title').on('click',function () {
              jQuery('.submenu-title').removeClass('active');
              jQuery('.submenu-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              jQuery('.submenu-content').slideUp('normal');
              if (jQuery(this).next().is(':hidden') == true) {
                  jQuery(this).addClass('active');
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                  jQuery(this).next().slideDown('normal');
              } else {
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              }
          });
          jQuery('.submenu-content').hide();

          jQuery('.menu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
          jQuery('.menu-title').on('click',function () {
              jQuery('.menu-title').removeClass('active');
              jQuery('.menu-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              jQuery('.menu-content').slideUp('normal');
              if (jQuery(this).next().is(':hidden') == true) {
                  jQuery(this).addClass('active');
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');
                  jQuery(this).next().slideDown('normal');
              } else {
                  jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');
              }
          });
          jQuery('.menu-content').hide();
        }
    }

// toggle sidebar
$('.toggle-sidebar').on('click',function() {   
    "use strict";
  $('.main-nav').toggleClass('close_icon');
  $('.page-main-header').toggleClass('close_icon');
  $('.page-body').toggleClass('close-everything');
});

//responsive sidebar
var $window = $(window);
var widthwindow = $window.width();
(function($) {
"use strict";
if(widthwindow+17 <= 993) {   
    $('.toggle-sidebar').attr('checked', false);
    $('.main-nav').addClass("close_icon");
    $('.page-main-header').addClass("close_icon");
    $('.page-body').addClass("close-everything");
}
})(jQuery);


$(window).on('resize',function() {
    "use strict";
var widthwindaw = $window.width();

if(widthwindaw+17 <= 991){
    $('.toggle-sidebar').attr('checked', false);
    $('.main-nav').addClass("close_icon");
    $('.page-main-header').addClass("close_icon");
    $('.page-body').addClass("close-everything");
}else{
    $('.toggle-sidebar').attr('checked', false);
    $('.main-nav').removeClass("close_icon");
    $('.page-main-header').removeClass("close_icon");
    $('.page-body').removeClass("close-everything");
}

if(widthwindow >= 768) {   
    $('.toggle-sidebar').on('click',function() {    
        $('.main-nav').toggleClass('close_icon');
        $('.page-main-header').toggleClass('close_icon');
        $('.page-body').toggleClass('close-everything');
    });
}
});

// horizontal arrowss
var view = $("#mainnav");
var move = "500px";
var leftsideLimit = -500

// get wrapper width
var getMenuWrapperSize = function () {
    return $('.sidebar-wrapper').innerWidth();
}
var menuWrapperSize = getMenuWrapperSize();

if ((menuWrapperSize) >= '1660') {
    var sliderLimit = -3000
    
} else if ((menuWrapperSize) >= '1440') {
    var sliderLimit = -3600
} else {
    var sliderLimit = -4200
}

$("#left-arrow").addClass("disabled");
$("#right-arrow").on('click',function () {
    "use strict";
    var currentPosition = parseInt(view.css("marginLeft"));
    if (currentPosition >= sliderLimit) {
        $("#left-arrow").removeClass("disabled");
        view.stop(false, true).animate({
            marginLeft: "-=" + move
        }, {
            duration: 400
        })
        if (currentPosition == sliderLimit) {
            $(this).addClass("disabled");
            console.log("sliderLimit", sliderLimit);
        }
    }
});

$("#left-arrow").on('click',function () {
    "use strict";
    var currentPosition = parseInt(view.css("marginLeft"));
    if (currentPosition < 0) {
        view.stop(false, true).animate({
            marginLeft: "+=" + move
        }, {
            duration: 400
        })
        $("#right-arrow").removeClass("disabled");
        $("#left-arrow").removeClass("disabled");
        if (currentPosition >= leftsideLimit) {
            $(this).addClass("disabled");
        }
    }
});

    var current = window.location.href
    $(".main-navbar ul>li a").on('filter',function() {
        "use strict";
        var link = $(this).attr("href");
        if( $(this).hasClass('active') == true)
        {
            $(this).parents().parents().children('ul').css('display', 'block');
        }
    });
    $('.custom-scrollbar').animate({
        scrollTop: $('a.nav-link.menu-title.active').offset().top - 500
    }, 1000);