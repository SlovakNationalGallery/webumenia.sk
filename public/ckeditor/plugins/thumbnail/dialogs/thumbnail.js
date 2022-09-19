function prepareSrcSet(basePath) {
    return `${basePath.replace('WIDTH', 600)} 600w,
			${basePath.replace('WIDTH', 220)} 220w,
			${basePath.replace('WIDTH', 300)} 300w,
			${basePath.replace('WIDTH', 600)} 600w,
			${basePath.replace('WIDTH', 800)} 800w`
}

CKEDITOR.dialog.add('thumbnailDialog', function (editor) {
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: editor.lang.thumbnail.title,
        minWidth: 400,
        minHeight: 200,

        // Dialog window content definition.
        contents: [
            {
                id: 'tab-basic',

                // The tab content.
                elements: [
                    {
                        type: 'text',
                        id: 'id',
                        label: 'Id',
                        validate: CKEDITOR.dialog.validate.number(editor.lang.thumbnail.idRequired), // true
                    },
                ],
            },
        ],
        onOk: function () {
            var dialog = this
            var articleId = dialog.getValueOf('tab-basic', 'id')

            editor.insertHtml(`[x-article_thumbnail :id=${articleId}/]`)
        },
    }
})
