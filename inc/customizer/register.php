<?php
/**
 * Registers the customizer settings and controls.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

/**
 * Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographia_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_textcolor' );

	$wp_customize->add_section( 'photographia_options', [
		'title' => __( 'Theme options', 'photographia' ),
	] );

	/**
	 * Add setting for alternative header layout.
	 */
	$wp_customize->add_setting( 'photographia_header_layout', [
		'default'           => false,
		'sanitize_callback' => 'photographia_sanitize_checkbox',
	] );

	/**
	 * Add control for alternative header layout.
	 */
	$wp_customize->add_control( 'photographia_header_layout', [
		'type'    => 'checkbox',
		'section' => 'photographia_options',
		'label'   => __( 'Alternative header layout', 'photographia' ),
	] );

	/**
	 * Add setting for hiding the content of the static front page if panels are used.
	 */
	$wp_customize->add_setting( 'photographia_hide_static_front_page_content', [
		'default'           => false,
		'sanitize_callback' => 'photographia_sanitize_checkbox',
	] );

	/**
	 * Add control for hiding the content of the static front page if panels are used.
	 */
	$wp_customize->add_control( 'photographia_hide_static_front_page_content', [
		'type'            => 'checkbox',
		'section'         => 'photographia_options',
		'label'           => __( 'Hide the content of the static front page if panels are used.', 'photographia' ),
		'active_callback' => 'photographia_is_static_front_page',
	] );


	/**
	 * Front page panels. Inspired by https://core.trac.wordpress.org/browser/tags/4.7.3/src/wp-content/themes/twentyseventeen/inc/customizer.php#L88
	 */

	/**
	 * Filter number of front page sections in Photographia.
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	$num_sections = apply_filters( 'photographia_front_page_sections', 4 );


	/**
	 * Filter number of posts which are displayed in the post panel dropdown.
	 *
	 * @param int $post_number Number of posts.
	 */
	$post_number = apply_filters( 'photographia_front_page_posts_number', 500 );

	/**
	 * Get the last posts for the dropdown menu for post panels.
	 */
	$post_panel_posts = new WP_Query( [
		'post_type'      => 'post',
		'posts_per_page' => $post_number,
		'no_found_rows'  => true,
	] );

	/**
	 * Build the choices array for the post panel.
	 */
	$post_panel_choices = [];
	if ( $post_panel_posts->have_posts() ) {
		while ( $post_panel_posts->have_posts() ) {
			$post_panel_posts->the_post();

			$post_panel_choices[ get_the_ID() ] = get_the_title();
		}
	}

	/**
	 * Build the choices array for the post grid panel category.
	 *
	 * @link https://blog.josemcastaneda.com/2015/05/13/customizer-dropdown-category-selection/
	 */
	$cats = [];
	foreach ( get_categories() as $categories => $category ) {
		$cats[ $category->term_id ] = $category->name;
	}

	/**
	 * Add a value to the array so the user can choose a neutral value when he does not
	 * want to show a post.
	 */
	$first_select_value = [ 0 => __( '— Select —', 'photographia' ) ];
	$cats               = $first_select_value + $cats;

	/**
	 * Create a setting and control for each of the sections available in the theme.
	 */
	for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
		/**
		 * Create setting for saving the content type choice.
		 */
		$wp_customize->add_setting( "photographia_panel_{$i}_content_type", [
			'default'           => 0,
			'sanitize_callback' => 'photographia_sanitize_select',
		] );

		/**
		 * Create control for content choice.
		 */
		$wp_customize->add_control( "photographia_panel_{$i}_content_type", [
			/* translators: d = number of panel in customizer */
			'label'           => sprintf( __( "Panel %d", 'photographia' ), $i ),
			'type'            => 'select',
			'section'         => 'photographia_options',
			'choices'         => [
				0              => __( '— Select —', 'photographia' ),
				'page'         => __( 'Page', 'photographia' ),
				'post'         => __( 'Post', 'photographia' ),
				'latest-posts' => __( 'Latest Posts', 'photographia' ),
				'post-grid'    => __( 'Post Grid', 'photographia' ),
			],
			'active_callback' => 'photographia_is_static_front_page',
		] );

		/**
		 * Create setting for page.
		 */
		$wp_customize->add_setting( "photographia_panel_{$i}_page", [
			'default'           => false,
			'sanitize_callback' => 'absint',
		] );

		/**
		 * Create control for page.
		 */
		$wp_customize->add_control( "photographia_panel_{$i}_page", [
			'label'           => __( 'Select page', 'photographia' ),
			'section'         => 'photographia_options',
			'type'            => 'dropdown-pages',
			'allow_addition'  => true,
			'active_callback' => 'photographia_is_page_panel',
			'input_attrs'     => [
				'data-panel-number' => $i,
			],
		] );

		/**
		 * Check if we have posts to show, before creating the post controls and settings.
		 */
		if ( ! empty( $post_panel_choices ) ) {
			/**
			 * Add a value to the array so the user can choose a neutral value when he does not
			 * want to show a post.
			 */
			$first_select_value = [ 0 => __( '— Select —', 'photographia' ) ];
			$post_panel_choices = $first_select_value + $post_panel_choices;

			/**
			 * Create setting for post.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post", [
				'default'           => 0,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for post.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post", [
				'label'           => __( 'Select post', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'select',
				'choices'         => $post_panel_choices,
				'active_callback' => 'photographia_is_post_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting for latest posts section title.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_latest_posts_title", [
				'default'           => __( 'Latests posts', 'photographia' ),
				'sanitize_callback' => 'sanitize_text_field',
			] );

			/**
			 * Create control for latest posts section title.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_latest_posts_title", [
				'label'           => __( 'Section title', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'text',
				'active_callback' => 'photographia_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting for latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_latest_posts_number", [
				'default'           => 5,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_latest_posts_number", [
				'label'           => __( 'Number of posts', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'number',
				'active_callback' => 'photographia_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting to only show title and header meta of latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_latest_posts_short_version", [
				'default'           => false,
				'sanitize_callback' => 'photographia_sanitize_checkbox',
			] );

			/**
			 * Create control to only show title and header meta of latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_latest_posts_short_version", [
				'label'           => __( 'Only show title and header meta of posts.', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographia_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting for post grid section title.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_title", [
				'default'           => __( 'Post grid', 'photographia' ),
				'sanitize_callback' => 'sanitize_text_field',
			] );

			/**
			 * Create control for post grid section title.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_title", [
				'label'           => __( 'Section title', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'text',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting for post grid number of posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_number", [
				'default'           => 20,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for post grid number of posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_number", [
				'label'           => __( 'Number of posts (skips posts without post thumbnail)', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'text',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting to only show title and header meta of latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_hide_title", [
				'default'           => false,
				'sanitize_callback' => 'photographia_sanitize_checkbox',
			] );

			/**
			 * Create control to only show title and header meta of latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_hide_title", [
				'label'           => __( 'Hide post titles.', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting to only show title and header meta of latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_only_gallery_and_image_posts", [
				'default'           => false,
				'sanitize_callback' => 'photographia_sanitize_checkbox',
			] );

			/**
			 * Create control to only show title and header meta of latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_only_gallery_and_image_posts", [
				'label'           => __( 'Only display posts with post format »Gallery« or »Image«.', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );


			/**
			 * Create setting for post grid category.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_category", [
				'default'           => 0,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for post grid category.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_category", [
				'label'           => __( 'Only show posts from one category:', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'select',
				'choices'         => $cats,
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );
		} // End if().
	} // End for().

	/**
	 * Change transport to refresh
	 */
	$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
}

add_action( 'customize_register', 'photographia_customize_register', 11 );

/**
 * Include the file with the callback functions (sanitize callbacks and
 * active callbacks).
 */
require_once locate_template( 'inc/customizer/callbacks.php' );


/**
 * Include the file with the functions to print CSS.
 */
require_once locate_template( 'inc/customizer/print-styles.php' );
