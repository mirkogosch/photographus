<?php
/**
 * Template for displaying the header
 *
 * @version 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( is_singular() && pings_open() ) { ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php }
    wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
    Header
</header>