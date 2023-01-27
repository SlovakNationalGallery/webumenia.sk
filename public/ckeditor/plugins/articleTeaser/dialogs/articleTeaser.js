CKEDITOR.dialog.add('articleTeaserDialog', function (editor) {
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: editor.lang.articleTeaser.title,
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
                            editor.lang.articleTeaser.idRequired
                        ), // true
                    },
                    {
                        type: 'radio',
                        id: 'type',
                        label: 'Type',
                        items: [
                            ['Článok', 'article'],
                            ['Kolekcia', 'collection'],
                        ],
                        default: 'article',
                    },
                ],
            },
        ],
        onOk: function () {
            var dialog = this
            var articleId = dialog.getValueOf('tab-basic', 'id')
            var type = dialog.getValueOf('tab-basic', 'type')
            editor.insertHtml(`[article_teaser id=${articleId} type=${type}]`)
        },
    }
})
