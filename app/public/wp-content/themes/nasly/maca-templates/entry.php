<?php
/**
 * Entrada individual — post de WordPress o fallback estático.
 *
 * @package Nasly
 */

$wp_post = get_post();
$slug    = '';
if ( $wp_post instanceof WP_Post && 'post' === $wp_post->post_type ) {
	$slug = (string) get_post_meta( $wp_post->ID, '_maca_journal_slug', true );
	if ( ! $slug ) {
		$slug = $wp_post->post_name;
	}
} else {
	$slug = (string) get_query_var( 'maca_entry' );
}

$entry = $slug ? maca_get_entry( $slug ) : null;
$posts = maca_get_journal_posts();
$next  = null;

if ( $wp_post instanceof WP_Post ) {
	foreach ( $posts as $i => $p ) {
		if ( (int) $p->ID === (int) $wp_post->ID && isset( $posts[ $i + 1 ] ) ) {
			$next = $posts[ $i + 1 ];
			break;
		}
	}
} elseif ( $entry ) {
	$all = maca_journal_entries();
	foreach ( $all as $i => $e ) {
		if ( $e['slug'] === $slug && isset( $all[ $i + 1 ] ) ) {
			$next = $all[ $i + 1 ];
			break;
		}
	}
}

$title = $wp_post instanceof WP_Post ? get_the_title( $wp_post ) : ( $entry['title'] ?? 'Entrada' );
$num   = $wp_post instanceof WP_Post
	? (string) get_post_meta( $wp_post->ID, '_maca_journal_num', true )
	: ( $entry['num'] ?? '' );

add_filter(
	'pre_get_document_title',
	static function () use ( $title ) {
		return $title . ' — MACA';
	},
	20
);

require __DIR__ . '/_shell-start.php';
?>
<main id="wp--skip-link--target" class="wp-block-group nasly-main maca-single" style="max-width:1180px;margin-inline:auto;padding-inline:clamp(20px,4vw,40px)">
	<div class="maca-single__hero">
		<div class="nasly-kicker">
			<p class="nasly-chip nasly-chip--hot">Visual Journal<?php echo $num ? ' · ' . esc_html( $num ) : ''; ?></p>
		</div>
		<h1 class="nasly-titular maca-single__title"><?php echo esc_html( $title ); ?></h1>
	</div>
	<div class="maca-single__body">
		<?php
		if ( $wp_post instanceof WP_Post && $wp_post->post_content ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'the_content', $wp_post->post_content );
		} elseif ( $entry ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo maca_render_entry_body( $entry );
		}
		?>
	</div>
	<div class="maca-single__nav">
		<p><a href="<?php echo esc_url( home_url( '/visual-journal/' ) ); ?>">← Visual Journal</a></p>
		<?php if ( $next instanceof WP_Post ) : ?>
			<p><a href="<?php echo esc_url( get_permalink( $next ) ); ?>">Siguiente: <?php echo esc_html( get_the_title( $next ) ); ?> →</a></p>
		<?php elseif ( is_array( $next ) ) : ?>
			<p><a href="<?php echo esc_url( maca_entry_url( $next['slug'] ) ); ?>">Siguiente: <?php echo esc_html( $next['title'] ); ?> →</a></p>
		<?php endif; ?>
	</div>
</main>
<?php
require __DIR__ . '/_shell-end.php';
