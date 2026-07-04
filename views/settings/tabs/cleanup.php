<?php
use Falcon\Components\Cleanup;

$labels = Cleanup::get_labels();
$counts = Cleanup::get_counts();
$items  = array_keys( $labels );
?>
<p><?php esc_html_e( 'Select items to clean up from your database. All items are selected by default.', 'falcon' ); ?></p>

<?php foreach ( $items as $item ) : ?>
	<div class="featureBox">
		<label class="featureBox_switch">
			<input class="featureBox_input cleanup-checkbox" type="checkbox" name="cleanup_items[]" value="<?= esc_attr( $item ); ?>" checked>
			<span class="featureBox_icon"></span>
		</label>
		<div class="featureBox_body">
			<div class="featureBox_title">
				<?= esc_html( $labels[ $item ] ); ?>
				<span class="cleanup-count">(<?= esc_html( $counts[ $item ] ); ?>)</span>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<div style="margin-top: 20px; display: flex; align-items: center; gap: 10px;">
	<button type="button" id="run-cleanup" class="button button-primary">
		<?php esc_html_e( 'Run Cleanup', 'falcon' ); ?>
	</button>
	<span id="cleanup-message" class="message hidden"></span>
	<span class="spinner" style="float: none; margin: 0;"></span>
</div>

<script>
{
	const button = document.getElementById( 'run-cleanup' );
	const message = document.getElementById( 'cleanup-message' );
	const spinner = button.parentElement.querySelector( '.spinner' );

	button.addEventListener( 'click', () => {
		const checkboxes = document.querySelectorAll( '.cleanup-checkbox:checked' );
		const items = Array.from( checkboxes ).map( cb => cb.value );

		if ( items.length === 0 ) {
			alert( '<?php echo esc_js( __( 'Please select at least one item to clean.', 'falcon' ) ); ?>' );
			return;
		}

		button.disabled = true;
		button.classList.add( 'disabled' );
		spinner.classList.add( 'is-active' );
		message.classList.remove( 'hidden' );
		message.textContent = '<?php echo esc_js( __( 'Cleaning...', 'falcon' ) ); ?>';
		message.className = 'message';

		const formData = new FormData();
		formData.append( 'action', 'falcon_run_cleanup' );
		formData.append( '_ajax_nonce', Falcon.nonce_cleanup );
		items.forEach( item => formData.append( 'items[]', item ) );

		fetch( ajaxurl, { method: 'POST', body: formData } )
			.then( response => response.json() )
			.then( response => {
				button.disabled = false;
				button.classList.remove( 'disabled' );
				spinner.classList.remove( 'is-active' );

				message.className = 'message';
				message.classList.add( 'notice', response.success ? 'notice-success' : 'notice-error' );
				message.innerHTML = response.data;

				if ( response.success ) {
					const fd = new FormData();
					fd.append( 'action', 'falcon_cleanup_counts' );
					fd.append( '_ajax_nonce', Falcon.nonce_cleanup );
					fetch( ajaxurl, { method: 'POST', body: fd } )
						.then( r => r.json() )
						.then( data => {
							if ( data.success ) {
								document.querySelectorAll( '.cleanup-count' ).forEach( el => {
									const checkbox = el.closest( '.featureBox' ).querySelector( '.cleanup-checkbox' );
									const newCount = data.data[ checkbox.value ] ?? 0;
									el.textContent = `(${ newCount })`;
								} );
							}
						} );
				}
			} );
	} );
}
</script>
