CKFinder.addPlugin( 'imagedesc', {

	lang : [ 'en', 'pl' ],

	appReady : function( api ) {
		CKFinder.dialog.add( 'descdialog', function( api )
			{
				// CKFinder.dialog.definition
				var dialogDefinition =
				{
					title : api.lang.imagedesc.title,
					minWidth : 390,
					minHeight : 230,
					onOk : function() {
						// "this" is now a CKFinder.dialog object.
						var imgdesc = this.getValueOf( 'tab1', 'textareaId' );
						var fileName = api.getSelectedFile().name;
						var altText = this.getValueOf('tab1', 'imgaltid');
						var titleText = this.getValueOf('tab1', 'imgtitleid');

						if ( !imgdesc ) {
							api.openMsgDialog( '', api.lang.imagedesc.typeText );
							return false;
						}
						else {

							if(imgdesc) {
								//alert( "You have entered: " + imgdesc );
								alert( "Confirm Save");
								//return true;
								api.connector.sendCommand('ImageDescInfo', {imgdesc : this.getValueOf( 'tab1', 'textareaId' ), fileName : fileName, titleText : titleText, altText : altText }, function(xml)
								{
									var desc = xml.selectSingleNode('Connector/ImageDescInfo/@desc');
									var altText = xml.selectSingleNode('Connector/ImageDescInfo/@altText');
									var titleText = xml.selectSingleNode('Connector/ImageDescInfo/@titleText');
									var fileName = xml.selectSingleNode('Connector/ImageDescInfo/@fileName');
								}
								);

							}
						}
					},
					contents : [
						{
							id : 'tab1',
							label : '',
							title : '',
							expand : true,
							padding : 0,
							elements :
							[
								{
									type : 'html',
									html : '<h3>' +  api.lang.imagedesc.typeText + '</h3>'
								},
								{
									type : 'text',
									id :   'imgtitleid',
									label : api.lang.imagedesc.imgTitle,
								},
								{
									type : 'text',
									id : 'imgaltid',
									label : api.lang.imagedesc.imgAlt,
								},
								{
									type : 'textarea',
									id : 'textareaId',
									rows : 10,
									cols : 40,
									label : api.lang.imagedesc.imgDescText,
								}
							]
						}
					],
					buttons : [ CKFinder.dialog.cancelButton, CKFinder.dialog.okButton ]
				};

				return dialogDefinition;
			} );

		api.addFileContextMenuOption( { label : api.lang.imagedesc.menuItem, command : "ImageDesc" } , function( api, file )
		{
			api.openDialog('descdialog');
		});
	}
});
