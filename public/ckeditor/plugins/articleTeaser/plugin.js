// Register the plugin within the editor.
CKEDITOR.plugins.add('articleTeaser', {
    // Register the icons.
    icons: 'articleTeaser',
    lang: 'sk',

    // The plugin initialization logic goes inside this method.
    init: function (editor) {
        editor.addCommand('articleTeaser', new CKEDITOR.dialogCommand('articleTeaserDialog'))

        // Create a toolbar button that executes the above command.
        editor.ui.addButton('articleTeaser', {
            // The text part of the button (if available) and the tooltip.
            label: 'Insert article teaser',

            // The command to execute on click.
            command: 'articleTeaser',

            // The button placement in the toolbar (toolbar group name).
            toolbar: 'insert',
        })

        CKEDITOR.dialog.add('articleTeaserDialog', this.path + 'dialogs/articleTeaser.js')
    },
})
