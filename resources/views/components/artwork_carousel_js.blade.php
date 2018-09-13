{!! Html::script('js/slick.js') !!}

<script type="text/javascript">
  $(document).ready(function(){
    $("{{$slick_query}}").slick({
      dots: false,
      infinite: false,
      speed: 300,
      slide: 'a',
      centerMode: false,
      variableWidth: true
    });
  })
</script>