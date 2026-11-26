<?php
/**
 * Cierre de documento para plantillas estáticas MACA.
 *
 * @package Nasly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
echo do_blocks( '<!-- wp:template-part {"slug":"footer","area":"footer"} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
wp_footer();
?>
</body>
</html>
