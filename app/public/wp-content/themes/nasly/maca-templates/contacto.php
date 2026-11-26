<?php
/**
 * Contacto estático.
 *
 * @package Nasly
 */
add_filter(
	'pre_get_document_title',
	static function () {
		return 'Contacto — MACA';
	},
	20
);
require __DIR__ . '/_shell-start.php';
?>
<main id="wp--skip-link--target" class="wp-block-group nasly-main" style="max-width:720px;margin-inline:auto;padding:clamp(48px,8vw,96px) clamp(20px,4vw,40px)">
	<p class="is-style-eyebrow">Hablemos</p>
	<h1 class="nasly-titular">Contacto</h1>
	<p class="is-style-entrada">¿Tienes una idea que quieras hacer visible? Escríbeme.</p>
	<p><a class="wp-block-button__link wp-element-button" href="mailto:hello@justcamm.online">hello@justcamm.online</a></p>
</main>
<?php
require __DIR__ . '/_shell-end.php';
