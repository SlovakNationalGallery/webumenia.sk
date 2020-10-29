'use strict';

(function (CKEDITOR) {
    CKEDITOR.plugins.setLang('slick', 'cs', {
        title: 'Slick carousel',
        imagesRequired: 'Prosím definujte alespoň 1 obrázek',
        heightRequired: 'Prosím zadefinujte výšku kontajnera',
        height: 'Výška (html atribut, hodnota + jednotka)',
        images: "Seznam obrázků. Jeden na řádek, formát: id / url (link)"
    });
})(CKEDITOR);
