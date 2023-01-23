CKEDITOR.dialog.add('collectionTeaserDialog', function (editor) {
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: editor.lang.collectionTeaser.title,
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
                        validate: CKEDITOR.dialog.validate.number(
                            editor.lang.collectionTeaser.idRequired
                        ), // true
                    },
                ],
            },
        ],
        onOk: function () {
            var dialog = this
            var collectionId = dialog.getValueOf('tab-basic', 'id')

            editor.insertHtml(`[collection_teaser id=${collectionId}]`)
        },
    }
})
