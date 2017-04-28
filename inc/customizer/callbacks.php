<?php
/**
 * Callback functions for the customizer controls and settings.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

/**
 * Checkbox sanitization callback example.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 */
function photographia_sanitize_checkbox( $checked ) {
	/**
	 * Boolean check.
	 */
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, radio
 *
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @see  sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see  $wp_customize->get_control()
 *       https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 *
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function photographia_sanitize_select( $input, $setting ) {
	/**
	 * Ensure input is a slug.
	 */
	$input = sanitize_key( $input );

	/**
	 * Get list of choices from the control associated with the setting.
	 */
	$choices = $setting->manager->get_control( $setting->id )->choices;

	/**
	 * If the input is a valid key, return it; otherwise, return the default.
	 */
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Check if we need to display the dropdown-pages control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_page_panel( $control ) {
	/**
	 * Check if we are on the front page, otherwise we do not need the control.
	 */
	if ( ! photographia_is_static_front_page( $control ) ) {
		return false;
	}

	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'page' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if we need to display the posts control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_post_panel( $control ) {
	/**
	 * Check if we are on the front page, otherwise we do not need the control.
	 */
	if ( ! photographia_is_static_front_page( $control ) ) {
		return false;
	}

	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'post' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if we need to display the latest posts control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_latest_posts_panel( $control ) {
	/**
	 * Check if we are on the front page, otherwise we do not need the control.
	 */
	if ( ! photographia_is_static_front_page( $control ) ) {
		return false;
	}

	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'latest-posts' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if we need to display the latest posts control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_post_grid_panel( $control ) {
	/**
	 * Check if we are on the front page, otherwise we do not need the control.
	 */
	if ( ! photographia_is_static_front_page( $control ) ) {
		return false;
	}

	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'post-grid' === $content_type ) {
		return true;
	} else {
		return false;
	}
}


/**
 * Check if we are on a static front page.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_static_front_page( $control ) {
	/**
	 * Return true if is static front page.
	 */
	if ( is_front_page() && is_page() ) {
		return true;
	} else {
		return false;
	}
}