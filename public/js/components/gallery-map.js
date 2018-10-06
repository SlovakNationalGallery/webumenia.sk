$("document").ready(function() {
  $(".galleries a").hover(function() {
    var gallery_id = $( this ).attr('id');
    var old_classes = $("svg#map path."+gallery_id ).attr('class');
    $("svg#map path."+gallery_id ).attr('class', 'active '+old_classes);
    $("svg#map path."+gallery_id ).data('old_classes', old_classes);
  }, function() {
    var gallery_id = $( this ).attr('id');
    var old_classes = $("svg#map path."+gallery_id ).data('old_classes');
    $("svg#map path."+gallery_id ).attr('class', old_classes);
  });


  $("svg#map path.cls-4").hover(function() {
    var classes = $( this ).attr('class').split(/\s+/);
    $.each( classes, function( index, class_name ){
        // if class_name is uppercased (e.g. SNG, OGD ...) it's gallery ID
        if (class_name === class_name.toUpperCase()) {
            $(".galleries a#"+ class_name ).addClass('active');
        }
    });
  }, function() {
    $(".galleries a").removeClass('active');
  });
});