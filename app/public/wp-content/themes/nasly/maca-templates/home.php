<?php
/**
 * Portada MACA — 100% estática (ignora posts de WordPress).
 *
 * @package Nasly
 */
add_filter(
	'pre_get_document_title',
	static function () {
		return 'MACA — Visual Journal';
	},
	20
);

require __DIR__ . '/_shell-start.php';
?>
<main id="wp--skip-link--target" class="wp-block-group nasly-main maca-home">
	<section class="nasly-hero nasly-hero--portada">
		<div class="nasly-kicker">
			<p class="nasly-chip nasly-chip--hot">MACA</p>
			<p class="nasly-chip">Visual Journal</p>
		</div>
		<h1 class="nasly-titular maca-hero-titular">Diseño que comunica.<br><em>Ideas que conectan.</em></h1>
		<p class="is-style-entrada nasly-estrecho">Soy una creativa interesada en el diseño gráfico, la comunicación visual y el desarrollo de estrategias digitales que conectan marcas con personas.</p>
		<p class="is-style-entrada nasly-estrecho">Transformo ideas en experiencias visuales con intención, explorando la creatividad como una herramienta para comunicar, generar valor y construir conexiones.</p>
	</section>

	<section class="nasly-seccion maca-home__journal">
		<div class="nasly-encabezado">
			<h2 class="wp-block-heading">Visual Journal</h2>
			<p class="nasly-ver-todo"><a href="<?php echo esc_url( home_url( '/visual-journal/' ) ); ?>">Ver todas las entradas →</a></p>
		</div>
		<?php echo maca_render_journal_list(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</section>

	<section class="is-style-panel nasly-cta maca-home__cta">
		<div>
			<p class="is-style-eyebrow">Hablemos</p>
			<h2 class="wp-block-heading">¿Tienes una idea que quieras hacer visible?</h2>
		</div>
		<div class="wp-block-buttons">
			<div class="wp-block-button">
				<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Escríbeme</a>
			</div>
		</div>
	</section>
</main>
<?php
require __DIR__ . '/_shell-end.php';
