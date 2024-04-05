<script>
// user tags
$(document).ready(function(){

    $("#tags").selectize({
        plugins: ['remove_button'],
        persist: false,
        create: true,
        createOnBlur: true
    });
    $("#btn-add-tags").click(function (event) {
        event.preventDefault();
        $(this).slideToggle("fast");
        $(".ui-adding-user-tags").slideToggle("fast");
    });
});
</script>