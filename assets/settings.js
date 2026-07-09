{
	const groupTabs = document.querySelectorAll( '.e-tab' );
	const subTabs = document.querySelectorAll( '.e-subTab' );
	const subTabBars = document.querySelectorAll( '.e-subTabs' );
	const panes = document.querySelectorAll( '.e-tabPane' );

	const parseHash = () => {
		const hash = location.hash.substring( 1 );
		if ( ! hash ) {
			return { group: groupTabs[ 0 ]?.dataset.group, tab: null };
		}

		const parts = hash.split( '/' );
		return { group: parts[ 0 ], tab: parts[ 1 ] || null };
	};

	const getPane = ( group, tab ) => {
		if ( tab ) {
			return document.querySelector( `.e-tabPane[data-group="${ group }"][data-tab="${ tab }"]` );
		}
		return document.querySelector( `.e-tabPane[data-group="${ group }"]:not([data-tab])` );
	};

	const activate = ( group, tab = null ) => {
		const subTabBar = document.querySelector( `.e-subTabs[data-group="${ group }"]` );
		const hasSubTabs = Boolean( subTabBar );
		const subTabsBar = document.querySelector( '.e-subTabsBar' );

		if ( hasSubTabs && ! tab ) {
			tab = subTabBar.querySelector( '.e-subTab' )?.dataset.tab;
		}

		const hash = tab ? `${ group }/${ tab }` : group;
		history.replaceState( null, null, `#${ hash }` );

		groupTabs.forEach( el => el.classList.toggle( 'e-tab-active', el.dataset.group === group ) );
		subTabBars.forEach( el => el.classList.toggle( 'e-subTabs-active', el.dataset.group === group ) );
		subTabs.forEach( el => el.classList.toggle( 'e-subTab-active', el.dataset.group === group && el.dataset.tab === tab ) );
		if ( subTabsBar ) {
			subTabsBar.classList.toggle( 'e-subTabsBar-active', hasSubTabs );
		}

		panes.forEach( pane => pane.classList.remove( 'e-tabPane-active' ) );
		getPane( group, tab )?.classList.add( 'e-tabPane-active' );
	};

	const clickGroupTab = e => {
		const tabEl = e.target.closest( '.e-tab' );
		if ( ! tabEl ) {
			return;
		}

		e.preventDefault();
		activate( tabEl.dataset.group );
	};

	const clickSubTab = e => {
		const tabEl = e.target.closest( '.e-subTab' );
		if ( ! tabEl ) {
			return;
		}

		e.preventDefault();
		activate( tabEl.dataset.group, tabEl.dataset.tab );
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

	const { group, tab } = parseHash();
	activate( group, tab );

	document.querySelector( '.e-tabs' ).addEventListener( 'click', clickGroupTab );
	document.querySelector( '.e-tabPanes .e-subTabsBar' )?.addEventListener( 'click', clickSubTab );
	window.addEventListener( 'hashchange', () => {
		const locationHash = parseHash();
		activate( locationHash.group, locationHash.tab );
	} );

	const smtpTest = document.querySelector( '#smtp-test' );
	if ( smtpTest ) {
		smtpTest.addEventListener( 'click', sendTestEmail );
	}

	document.querySelector( '#settings-form' ).addEventListener( 'submit', submit );
	document.querySelector( '#import' ).addEventListener( 'change', importSettings );
}
