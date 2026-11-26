<?php
/**
 * Inicio de documento para plantillas estáticas MACA.
 *
 * @package Nasly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php echo do_blocks( '<!-- wp:template-part {"slug":"header","area":"header"} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
