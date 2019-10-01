$(document).ready(function(){
  $(".artworks-preview").slick({
    lazyLoad: 'progressive',
    variableWidth: true,
    infinite: false
  });
  $(".multiple-views").slick({
    lazyLoad: 'progressive',
    adaptiveHeight: true,
    dots: true
  });
})