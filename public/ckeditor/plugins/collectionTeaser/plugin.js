// Register the plugin within the editor.
CKEDITOR.plugins.add('collectionTeaser', {
    // Register the icons.
    icons: 'collectionTeaser',
    lang: 'sk',

    // The plugin initialization logic goes inside this method.
    init: function (editor) {
        editor.addCommand('collectionTeaser', new CKEDITOR.dialogCommand('collectionTeaserDialog'))

        // Create a toolbar button that executes the above command.
        editor.ui.addButton('collectionTeaser', {
            // The text part of the button (if available) and the tooltip.
            label: 'Insert collection teaser',

            // The command to execute on click.
            command: 'collectionTeaser',

            // The button placement in the toolbar (toolbar group name).
            toolbar: 'insert',
        })

        CKEDITOR.dialog.add('collectionTeaserDialog', this.path + 'dialogs/collectionTeaser.js')
    },
})
