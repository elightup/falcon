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
	}

	document.querySelector( '.e-tabs' ).addEventListener( 'click', clickHandle );
	document.querySelector( '#smtp-test' ).addEventListener( 'click', sendTestEmail );
}