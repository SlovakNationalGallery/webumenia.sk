{!! Html::script('js/slick.js') !!}
<script type="text/javascript">
  $(document).ready(function(){
    $("{{$slick_query}}").slick({
      dots: false,
      lazyLoad: 'progressive',
      infinite: false,
      speed: 300,
      slidesToShow: 1,
      slide: 'a',
      centerMode: false,
      variableWidth: true,
    });
  })
</script>