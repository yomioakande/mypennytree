// Sticky Nav
$(function(){
  function sticky(){
    if($(window).scrollTop() >= 30){
      $('.nav-header').addClass('sticky');
    } else if ($(window).scrollTop() < 30) {
      $('.nav-header').removeClass('sticky');
    }
  }
  $(window).on('scroll', function(){sticky()});
  $(window).on('load', sticky());
});

// Navigation Button
$(document).mouseup(function(e){
  var fnav = $('.navigation');
  if(!fnav.is(e.target)){
    $('.navigation').removeClass('show');
    $( '.menu-icon' ).removeClass('open');
    $('.nav-header').removeClass('shadow');
  }
  $( '.menu-icon' ).on( 'click', function() {
    $(this).toggleClass('open');
    $('.navigation').toggleClass('show');
    $('.nav-header').toggleClass('shadow');
  });
});

var $animation_elements = $('.animation-element');
var $window = $(window);

function check_if_in_view() {
  var window_height = $window.height();
  var window_top_position = $window.scrollTop();
  var window_bottom_position = (window_top_position + window_height);
 
  $.each($animation_elements, function() {
    var $element = $(this);
    var element_height = $element.outerHeight();
    var element_top_position = $element.offset().top;
    var element_bottom_position = (element_top_position + element_height);
 
    //check to see if this current container is within viewport
    if ((element_bottom_position >= window_top_position) &&
        (element_top_position <= window_bottom_position)) {
      $element.addClass('in-view');
    } else {
      $element.removeClass('in-view');
    }
  });
}

$window.on('scroll resize', check_if_in_view);
$window.trigger('scroll');
