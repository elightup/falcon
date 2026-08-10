<?php
defined( 'ABSPATH' ) || die;

use Falcon\Components\Cleanup;

$cleanup = Cleanup::instance();
$labels  = $cleanup->get_labels();
$counts  = $cleanup->get_counts();
$items   = array_keys( $labels );
$keep    = defined( 'WP_POST_REVISIONS' ) && is_numeric( WP_POST_REVISIONS ) ? (int) WP_POST_REVISIONS : '';
?>
<p><?php esc_html_e( 'Select items to clean up from your database. All items are selected by default.', 'falcon' ); ?></p>

<?php foreach ( $items as $item ) : ?>
	<div class="featureBox">
		<label class="featureBox_switch">
			<input class="featureBox_input cleanup-checkbox" type="checkbox" name="cleanup_items[]" value="<?php echo esc_attr( $item ); ?>" checked>
			<span class="featureBox_icon"></span>
		</label>
		<div class="featureBox_body">
			<div class="featureBox_title">
				<?php echo esc_html( $labels[ $item ] ); ?>
				<span class="cleanup-count">(<?php echo esc_html( $counts[ $item ] ); ?>)</span>
			</div>
			<?php if ( $item === 'revisions' ) : ?>
				<div class="featureBox_description">
					<?php
					printf(
						/* translators: %s: number input */
						esc_html__( 'Keep latest %s revisions per post (leave empty to delete all)', 'falcon' ),
						'<input type="text" class="e-inlineInput" id="revisions-keep" value="' . esc_attr( $keep ) . '" inputmode="numeric" pattern="[0-9]*" size="2">'
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endforeach; ?>

<div style="margin-top: 20px; display: flex; align-items: center; gap: 10px;">
	<button type="button" id="run-cleanup" class="button button-primary">
		<?php esc_html_e( 'Run Cleanup', 'falcon' ); ?>
	</button>
	<span class="spinner" style="float: none; margin: 0;"></span>
</div>
<span id="cleanup-message" class="message hidden" style="margin-top: 10px; display: block;"></span>

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
		message.className = 'message';
		message.classList.add( 'hidden' );

		const formData = new FormData();
		formData.append( 'action', 'falcon_run_cleanup' );
		formData.append( '_ajax_nonce', Falcon.nonce_cleanup );
		formData.append( 'revisions_keep', document.getElementById( 'revisions-keep' )?.value ?? '' );
		items.forEach( item => formData.append( 'items[]', item ) );

		fetch( ajaxurl, { method: 'POST', body: formData } )
			.then( response => response.json() )
			.then( response => {
				button.disabled = false;
				button.classList.remove( 'disabled' );
				spinner.classList.remove( 'is-active' );

				message.className = 'message';
				message.classList.add( 'notice', response.success ? 'notice-success' : 'notice-error' );
				message.innerHTML = response.data.message ?? response.data;

				if ( response.success && response.data.counts ) {
					document.querySelectorAll( '.cleanup-count' ).forEach( el => {
						const checkbox = el.closest( '.featureBox' ).querySelector( '.cleanup-checkbox' );
						const newCount = response.data.counts[ checkbox.value ] ?? 0;
						el.textContent = `(${ newCount })`;
					} );
				}
			} );
	} );
}
</script>
