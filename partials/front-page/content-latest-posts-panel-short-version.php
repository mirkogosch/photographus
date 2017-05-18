<?php
/**
 * Template part for displaying content of latest posts front page panel in short version (just title and meta).
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

?>
<article>
	<header class="entry-header -inverted-link-style">
		<div>
			<?php echo photographus_get_the_title( $heading_element, true );
			echo photographus_get_the_entry_header_meta(); ?>
		</div>
	</header>
</article>

