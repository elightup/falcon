{
	const tabs = document.querySelectorAll( '.e-tab' );
	const panes = document.querySelectorAll( '.e-tabPane' );

	const clickHandle = e => {
		if ( !e.target.classList.contains( 'e-tab' ) ) {
			return;
		}

		e.preventDefault();

		history.pushState( null, null, e.target.getAttribute( 'href' ) );

		tabs.forEach( tab => tab.classList.remove( 'e-tab-active' ) );
		e.target.classList.add( 'e-tab-active' );

		panes.forEach( pane => pane.classList.remove( 'e-tabPane-active' ) );
		document.querySelector( `.e-tabPane[data-tab="${ e.target.dataset.tab }"]` ).classList.add( 'e-tabPane-active' );
	};

	const activateFirstTab = () => {
		const tab = location.hash ? location.hash.substring( 1 ) : tabs[ 0 ].dataset.tab;

		document.querySelector( `.e-tab[data-tab="${ tab }"]` ).classList.add( 'e-tab-active' );
		document.querySelector( `.e-tabPane[data-tab="${ tab }"]` ).classList.add( 'e-tabPane-active' );
	};

	const sendTestEmail = e => {
		e.target.disabled = true;

		fetch( `${ ajaxurl }?action=falcon_test_smtp&_ajax_nonce=${ Falcon.nonce_email }` )
			.then( response => response.json() )
			.then( response => {
				alert( response.data );
				e.target.disabled = false;
			} );
	};

	const submit = e => {
		e.preventDefault();

		const submitButton = document.querySelector( '#submit' );
		const message = submitButton.previousElementSibling;

		submitButton.disabled = true;
		submitButton.value = Falcon.saving;

		let formData = new FormData( e.target );
		formData.append( 'action', 'falcon_save_settings' );
		formData.append( '_ajax_nonce', Falcon.nonce );
		fetch( ajaxurl, { method: 'POST', body: formData } )
			.then( response => response.json() )
			.then( response => {
				submitButton.disabled = false;
				submitButton.value = Falcon.save;

				message.textContent = response.data;
				message.classList.remove( 'hidden' );

				setTimeout( () => {
					message.classList.add( 'hidden' );
				}, 3000 );
			} );
	};

	const importSettings = e => {
		const file = e.target.files[0];
		if ( ! file ) {
			return;
		}

		const formData = new FormData();
		formData.append( 'action', 'falcon_import_settings' );
		formData.append( '_ajax_nonce', Falcon.nonce_import );
		formData.append( 'file', file );

		fetch( ajaxurl, { method: 'POST', body: formData } )
			.then( response => response.json() )
			.then( response => {
				if ( response.success ) {
					alert( response.data );
					location.reload();
				} else {
					alert( response.data );
				}
			} )
			.catch( error => {
				alert( error.message );
			} );
	};

	document.querySelector( '.e-tabs' ).addEventListener( 'click', clickHandle );
	activateFirstTab();

	document.querySelector( '#smtp-test' ).addEventListener( 'click', sendTestEmail );
	document.querySelector( '#settings-form' ).addEventListener( 'submit', submit );

	document.querySelector( '#import' ).addEventListener( 'change', importSettings );
}