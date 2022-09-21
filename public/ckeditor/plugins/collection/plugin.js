
// Register the plugin within the editor.
CKEDITOR.plugins.add( 'collection', {

	// Register the icons.
	icons: 'collection',
	lang: 'sk,en,cs',

	// The plugin initialization logic goes inside this method.
	init: function (editor) {
		editor.addCommand( 'collection', new CKEDITOR.dialogCommand( 'collectionDialog' ) );	

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'collection', {

		// The text part of the button (if available) and the tooltip.
		label: 'Insert collection',

		// The command to execute on click.
		command: 'collection',

		// The button placement in the toolbar (toolbar group name).
		toolbar: 'insert'
		});
		
		CKEDITOR.dialog.add( 'collectionDialog', this.path + 'dialogs/collection.js' );
	}
});