'use strict';

(function (CKEDITOR) {
    CKEDITOR.plugins.setLang('slick', 'en', {
        title: 'Slick carousel',
        imagesRequired: 'Please define at least one image.',
        heightRequired: 'Please define height of container',
        height: 'Height, use format: value+unit',
        images: "List of images. One per row, use format: id/url(target)"
    });
})(CKEDITOR);
