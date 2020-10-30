
// Register the plugin within the editor.
CKEDITOR.plugins.add( 'slick', {

	// Register the icons.
	icons: 'slick',
	lang: 'sk,en,cs',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {
		
		editor.addContentsCss( this.path + 'css/slick.css' );
		// Define an editor command that opens our dialog window.
		editor.addCommand( 'slick', new CKEDITOR.dialogCommand( 'slickDialog', {

			// Allow the slick tag with an optional title attribute.
			allowedContent: 'slick[data-title,data-height,data-images]',


			contentForms: [
				'slick'
			]
		} ) );

		// Create a toolbar button that executes the above command.
		editor.ui.addButton( 'slick', {

			// The text part of the button (if available) and the tooltip.
			label: 'Insert Slick',

			// The command to execute on click.
			command: 'slick',

			// The button placement in the toolbar (toolbar group name).
			toolbar: 'insert'
		});

		if ( editor.contextMenu ) {

			// Add a context menu group with the Edit slickeviation item.
			editor.addMenuGroup( 'slickGroup' );
			editor.addMenuItem( 'slickItem', {
				label: 'Edit slickeviation',
				icon: this.path + 'icons/slick.png',
				command: 'slick',
				group: 'slickGroup'
			});

			editor.contextMenu.addListener( function( element ) {
				if ( element.getAscendant( 'slick', true ) ) {
					return { slickItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}

		// Register our dialog file -- this.path is the plugin folder path.
		CKEDITOR.dialog.add( 'slickDialog', this.path + 'dialogs/slick.js' );
	}
});