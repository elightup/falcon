( function ( document ) {
	const tabs = document.querySelectorAll( '.nav-tab' );
	const panes = document.querySelectorAll( '.tab-pane' );

	function clickHandle( e ) {
		if ( ! e.target.classList.contains( 'nav-tab' ) ) {
			return;
		}

		e.preventDefault();

		tabs.forEach( tab => tab.classList.remove( 'nav-tab-active' ) );
		e.target.classList.add( 'nav-tab-active' );

		panes.forEach( pane => pane.classList.add( 'hidden' ) );
		document.querySelector( e.target.getAttribute( 'href' ) ).classList.remove( 'hidden' );
	}

	document.querySelector( '.nav-tab-wrapper' ).addEventListener( 'click', clickHandle );
} )( document );