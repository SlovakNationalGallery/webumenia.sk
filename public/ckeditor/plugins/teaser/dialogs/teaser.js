CKEDITOR.dialog.add('teaserDialog', function (editor) {
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: editor.lang.teaser.title,
        minWidth: 400,
        minHeight: 300,

        // Dialog window content definition.
        contents: [
            {
                id: 'tab-basic',
                // The tab content.
                elements: [
                    {
                        type: 'radio',
                        id: 'type',
                        label: 'Typ',
                        items: [
                            ['Článok', 'article'],
                            ['Kolekcia', 'collection'],
                        ],
                        default: 'article',
                        inputStyle: 'width: 8px; height: 8px',
                    },
                    {
                        type: 'text',
                        id: 'id',
                        label: 'Id',
                        validate: CKEDITOR.dialog.validate.number(
                            editor.lang.teaser.idRequired
                        ), // true
                    },
                ],
            },
        ],
        onOk: function () {
            var dialog = this
            var articleId = dialog.getValueOf('tab-basic', 'id')
            var type = dialog.getValueOf('tab-basic', 'type')
            editor.insertHtml(`[teaser id=${articleId} type=${type}]`)
        },
    }
})
