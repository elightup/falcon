{
	const tabs = document.querySelectorAll( '.e-tab' );
	const panes = document.querySelectorAll( '.e-tabPane' );

	const clickHandle = e => {
		if ( !e.target.classList.contains( 'e-tab' ) ) {
			return;
		}

		e.preventDefault();

		tabs.forEach( tab => tab.classList.remove( 'e-tab-active' ) );
		e.target.classList.add( 'e-tab-active' );

		panes.forEach( pane => pane.classList.add( 'hidden' ) );
		document.querySelector( `.e-tabPane[data-tab="${ e.target.dataset.tab }"]` ).classList.remove( 'hidden' );
	};

	const sendTestEmail = e => {
		e.target.disabled = true;

		fetch( `${ ajaxurl }?action=falcon_test_smtp` )
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

	document.querySelector( '.e-tabs' ).addEventListener( 'click', clickHandle );
	document.querySelector( '#smtp-test' ).addEventListener( 'click', sendTestEmail );
	document.querySelector( '#settings-form' ).addEventListener( 'submit', submit );
}