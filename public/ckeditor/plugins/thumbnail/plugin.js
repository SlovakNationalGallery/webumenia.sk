
// Register the plugin within the editor.
CKEDITOR.plugins.add( 'thumbnail', {

	// Register the icons.
	icons: 'thumbnail',
	lang: 'sk,en,cs',

	// The plugin initialization logic goes inside this method.
	init: function (editor) {
		editor.addCommand( 'thumbnail', new CKEDITOR.dialogCommand( 'thumbnailDialog' ) );	

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'thumbnail', {

		// The text part of the button (if available) and the tooltip.
		label: 'Insert thumbnail',

		// The command to execute on click.
		command: 'thumbnail',

		// The button placement in the toolbar (toolbar group name).
		toolbar: 'insert'
		});
		
		CKEDITOR.dialog.add( 'thumbnailDialog', this.path + 'dialogs/thumbnail.js' );
	}
});