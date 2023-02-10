// Register the plugin within the editor.
CKEDITOR.plugins.add('teaser', {
    // Register the icons.
    icons: 'teaser',
    lang: 'sk',

    // The plugin initialization logic goes inside this method.
    init: function (editor) {
        // Define an editor command that opens our dialog window.
        editor.addCommand('teaser', new CKEDITOR.dialogCommand('teaserDialog'))

        // Create a toolbar button that executes the above command.
        editor.ui.addButton('teaser', {
            // The text part of the button (if available) and the tooltip.
            label: 'Insert teaser',

            // The command to execute on click.
            command: 'teaser',

            // The button placement in the toolbar (toolbar group name).
            toolbar: 'insert',
        })

        CKEDITOR.dialog.add('teaserDialog', this.path + 'dialogs/teaser.js')
    },
})
