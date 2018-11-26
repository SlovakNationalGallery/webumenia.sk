$(document).ready(function(){
  $(".artworks-preview").slick({
    dots: false,
    lazyLoad: 'progressive',
    infinite: false,
    speed: 300,
    slide: 'a',
    centerMode: false,
    variableWidth: true
  });
  $(".multiple-views").slick();
})