<?php
/**
 * Entrada individual del Visual Journal (estática).
 *
 * @package Nasly
 */
$slug  = (string) get_query_var( 'maca_entry' );
$entry = maca_get_entry( $slug );
if ( ! $entry ) {
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	return;
}

$entries = maca_journal_entries();
$next    = null;
foreach ( $entries as $i => $e ) {
	if ( $e['slug'] === $slug && isset( $entries[ $i + 1 ] ) ) {
		$next = $entries[ $i + 1 ];
		break;
	}
}

add_filter(
	'pre_get_document_title',
	static function () use ( $entry ) {
		return $entry['title'] . ' — MACA';
	},
	20
);

require __DIR__ . '/_shell-start.php';
?>
<main id="wp--skip-link--target" class="wp-block-group nasly-main maca-single" style="max-width:1180px;margin-inline:auto;padding-inline:clamp(20px,4vw,40px)">
	<div class="maca-single__hero">
		<div class="nasly-kicker">
			<p class="nasly-chip nasly-chip--hot">Visual Journal · <?php echo esc_html( $entry['num'] ); ?></p>
		</div>
		<h1 class="nasly-titular maca-single__title"><?php echo esc_html( $entry['title'] ); ?></h1>
	</div>
	<div class="maca-single__body">
		<?php echo maca_render_entry_body( $entry ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="maca-single__nav">
		<p><a href="<?php echo esc_url( home_url( '/visual-journal/' ) ); ?>">← Visual Journal</a></p>
		<?php if ( $next ) : ?>
			<p><a href="<?php echo esc_url( maca_entry_url( $next['slug'] ) ); ?>">Siguiente: <?php echo esc_html( $next['title'] ); ?> →</a></p>
		<?php endif; ?>
	</div>
</main>
<?php
require __DIR__ . '/_shell-end.php';
