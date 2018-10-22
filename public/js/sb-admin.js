$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    initModals()

    $(window).bind("load resize", function() {
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    });

    var url = window.location;

    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
})


function initFeatures() {
    $(".action").prependTo(".dt-buttons");

    $('.pop').popover({ html: true });

    // $('.image-link').magnificPopup({
    //     type:'image',
    //     closeBtnInside: true
    // });

    $('[data-toggle="tooltip"]').tooltip();
    initModals();
}

function initModals() {
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

    $('.btn-detail').on('click', function( event ){
        $('#detailModal').modal({modal:true,remote:($(this).attr('href'))});
        event.preventDefault();
    });
}