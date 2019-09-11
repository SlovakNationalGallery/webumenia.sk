$(document).ready(function(){
  $(".artworks-preview").slick({
    lazyLoad: 'progressive',
    slide: 'a',
    variableWidth: true,
    infinite: false
  });
  $(".multiple-views").slick({
    lazyLoad: 'progressive',
    slide: 'a',
    variableWidth: true,
    dots: true
  });
})