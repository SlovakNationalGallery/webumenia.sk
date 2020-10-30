function prepareSrcSet(basePath){
	return `${basePath.replace('WIDTH', 600)} 600w,
			${basePath.replace('WIDTH', 220)} 220w,
			${basePath.replace('WIDTH', 300)} 300w,
			${basePath.replace('WIDTH', 600)} 600w,
			${basePath.replace('WIDTH', 800)} 800w`;
}

const slickDefaults = {
	images: `SVK:GUS.M_1186(http://webumenia.sk)\nSVK:GUS.M_1186\nhttps://picsum.photos/500/500(http://webumenia.sk)\nhttps://picsum.photos/500/500`,
	height: '150px'
}

CKEDITOR.dialog.add( 'slickDialog', function( editor ) {
	return {

		// Basic properties of the dialog window: title, minimum size.
		title: editor.lang.slick.title,
		minWidth: 400,
		minHeight: 200,

		// Dialog window content definition.
		contents: [
			{
				// Definition of the Basic Settings dialog tab (page).
				id: 'tab-basic',

				// The tab content.
				elements: [
					{
						// Text input field for the slick text.
						type: 'textarea',
						id: 'title',
						label: editor.lang.slick.title,

						// Validation checking whether the field is not empty.
						validate: CKEDITOR.dialog.validate.notEmpty( editor.lang.slick.iamgesRequired ),

					
						setup: function( element ) {
							this.setValue( element.getAttribute( "data-title" ));
						},

						// Called by the main commitContent method call on dialog confirmation.
						commit: function( element ) {
							element.setAttribute( "data-title", this.getValue());

							$(element.$).find('.content-slick-title').text(this.getValue());
						}
					},
					{
						// Text input field for the slick text.
						type: 'textarea',
						id: 'images',
						label: editor.lang.slick.images,

						// Validation checking whether the field is not empty.
						validate: CKEDITOR.dialog.validate.notEmpty( editor.lang.slick.imagesRequired ),

					
						setup: function( element ) {
							this.setValue( element.getAttribute( "data-images" ));
						},

						onShow: function( element ) {
							this.setValue( slickDefaults.images );
						},

						// Called by the main commitContent method call on dialog confirmation.
						commit: function( element ) {
							element.setAttribute( "data-images", this.getValue());
							$slick = $(element.$).find('.content-slick');
							$slick.html('');

							this.getValue().split('\n').forEach(function(slickImg){
								var basePath = `/dielo/nahlad/ITEMID/WIDTH`;
									
								const res = slickImg.trim().match(/(.*)\((.*)\)/);
								
								if (res && res[1]){
									
									if (res[1].indexOf('http') < 0){
										basePath = basePath.replace('ITEMID', res[1].trim());
										srcset= prepareSrcSet(basePath);
										$slick.append(`<li class="slick-cell"><a href="${res[2]}"><img src="${basePath.replace('WIDTH', 800)}" srcset="${srcset}"/></a></li>`);
									}else {
										$slick.append(`<li class="slick-cell"><a href="${res[2]}" target="_blank"><img src="${res[1]}"/></a></li>`);
									}
								} else {
									if (slickImg.indexOf('http') < 0){
										basePath = basePath.replace('ITEMID', slickImg.trim());
										itemPath= `/dielo/${slickImg.trim()}`;
										srcset = prepareSrcSet(basePath);
										$slick.append(`<li class="slick-cell"><a href="${itemPath}"><img src="${basePath.replace('WIDTH', 800)}" srcset="${srcset}"/></a></li>`);
									}else {
										$slick.append(`<li class="slick-cell"><img src="${slickImg.trim()}"/></li>`);
									}
								}
							});
						}
					},
					{
						// Text input field for the slick title (explanation).
						type: 'text',
						id: 'height',
						label: editor.lang.slick.height,

						// Require the title attribute to be enabled.
						validate: CKEDITOR.dialog.validate.notEmpty( editor.lang.slick.heightRequired ),

						// Called by the main setupContent method call on dialog initialization.
						setup: function( element ) {
							this.setValue( element.getAttribute( "data-height" ) || slickDefaults.height);
						},
						onShow: function( element ) {
							this.setValue(  slickDefaults.height );
						},

						// Called by the main commitContent method call on dialog confirmation.
						commit: function( element ) {
							element.setAttribute( "data-height", this.getValue());
							$(element.$).find('.content-slick').attr( "style", `height: ${this.getValue()};` );
						}
					}
				]
			},
		],

		// Invoked when the dialog is loaded.
		onShow: function() {

			// Get the selection from the editor.
			var selection = editor.getSelection();

			// Get the element at the start of the selection.
			var element = selection.getStartElement();


			// Get the <p> element closest to the selection, if it exists.
			if ( element )
				element = element.getAscendant( 'div' );

			// Create a new <p> element if it does not exist.
			if ( !element || element.getName() != 'div' ) {
				element = editor.document.createElement( 'div' );
				element.setAttribute('class','content-slick-container');
				element.appendHtml('<span class="content-slick-title"></span> <ul class="content-slick"></ul>');
			

				// Flag the insertion mode for later use.
				this.insertMode = true;
			}
			else
				this.insertMode = false;

			// Store the reference to the <p> element in an internal property, for later use.
			this.element = element;

			console.log(element);
			// Invoke the setup methods of all dialog window elements, so they can load the element attributes.
			if ( !this.insertMode )
				this.setupContent( this.element );
		},

		// This method is invoked once a user clicks the OK button, confirming the dialog.
		onOk: function() {

			// Invoke the commit methods of all dialog window elements, so the <div> element gets modified.
			this.commitContent( this.element );

			// Finally, if in insert mode, insert the element into the editor at the caret position.
			if ( this.insertMode )
				editor.insertElement( this.element );
		}
	};
});