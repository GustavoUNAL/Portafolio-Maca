<?php
/**
 * Archivo Visual Journal (estático).
 *
 * @package Nasly
 */
require __DIR__ . '/_shell-start.php';
?>
<main id="wp--skip-link--target" class="wp-block-group nasly-main maca-journal" style="max-width:1180px;margin-inline:auto;padding-inline:clamp(20px,4vw,40px)">
	<div class="maca-journal__hero">
		<p class="is-style-eyebrow">MACA</p>
		<h1 class="nasly-titular maca-journal__title">Visual <em>Journal</em></h1>
		<p class="is-style-entrada nasly-estrecho">Un espacio editorial para explorar ideas, procesos y formas de expresión visual.</p>
	</div>
	<?php echo maca_render_journal_list(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</main>
<?php
require __DIR__ . '/_shell-end.php';
