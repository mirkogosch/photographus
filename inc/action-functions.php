<?php
/**
 * Theme functions that get hooked to actions.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

if ( ! function_exists( 'photographus_load_translation' ) ) {
	/**
	 * Load translation from languages directory
	 */
	function photographus_load_translation() {
		if ( ( ! defined( 'DOING_AJAX' ) && ! 'DOING_AJAX' ) || ! photographus_is_login_page() || ! photographus_is_wp_comments_post() ) {
			load_theme_textdomain( 'photographus', get_template_directory() . '/languages' );
		}
	}
}

add_action( 'after_setup_theme', 'photographus_load_translation' );

if ( ! function_exists( 'photographus_set_content_width' ) ) {
	/**
	 * Set the content width.
	 */
	function photographus_set_content_width() {
		$content_width = 751;
	}
}

add_action( 'after_setup_theme', 'photographus_set_content_width' );

if ( ! function_exists( 'photographus_add_theme_support' ) ) {
	/**
	 * Adds theme support for feed links, custom head, html5, post formats, post thumbnails, title element and custom logo
	 */
	function photographus_add_theme_support() {
		/**
		 * Add theme support for custom header image (only use height so that WordPress does not
		 * show a recommended height of 0).
		 */
		add_theme_support( 'custom-header', [
			'height' => 1000,
		] );

		/**
		 * Add theme support for feed links (blog feed, comment feeds, …)
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Add theme support for the title tag.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Add theme support for the post formats.
		 */
		add_theme_support( 'post-formats', [
			'aside',
			'link',
			'gallery',
			'status',
			'quote',
			'image',
			'video',
			'audio',
			'chat',
		] );

		/**
		 * Add theme support for HTML5 markup in Core elements.
		 */
		add_theme_support( 'html5', [
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
		] );

		/**
		 * Add theme support for post thumbnails.
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add theme support for the custom logo feature.
		 */
		add_theme_support( 'custom-logo' );

		/**
		 * Add theme support for selective refresh for widgets.
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Create starter content for new sites.
		 */
		add_theme_support( 'starter-content', [
			/**
			 * Add attachments that are used by posts and pages.
			 */
			'attachments' => [
				'featured-image-about-page' => [
					'post_title' => 'Featured image for about page',
					'file'       => 'assets/images/starter-content/featured-image-about-page.jpg',
				],
			],

			/**
			 * Create and modify posts and pages.
			 */
			'posts'       => [
				'home'            => [
					'post_content' => __( 'Welcome to your site! This is your homepage, which is what most visitors will see when they come to your site for the first time.
					
					The »Photographus« theme lets you use different areas for the front page, so-called »panels«. With that, you can display different content types on the front page: You can choose from a grid of your latest gallery and image posts, a list of your latest posts or a single page/post.
					
					To edit the panels you see here, just click on the pen icon on the left.', 'photographus' ),
				],
				'about'           => [
					'template'     => 'templates/large-portrait-featured-image.php',
					'thumbnail'    => '{{featured-image-about-page}}',
					'post_content' => __( 'Just introduce yourself! This page uses the template with a large portrait featured image. If you do not use a sidebar, the image is displayed next to the content on large viewports.', 'photographus' ),
				],
				'blog',
				'snowy-landscape' => [
					'post_type'  => 'post',
					'post_name'  => 'snowy-landscape',
					'post_title' => 'Snowy Landscape',
				],
			],

			/**
			 * Remove default core starter content widgets
			 */
			'widgets'     => [
				'sidebar-1' => [
					'search',
					'text_about',
				],
			],

			/**
			 * Set options
			 */
			'options'     => [
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
				'header_image'   => get_theme_file_uri( 'assets/images/starter-content/featured-image-about-page.jpg' ),
			],

			/**
			 * Fill nav menus
			 */
			'nav_menus'   => [
				'primary' => [
					'name'  => __( 'Primary Menu', 'photographus' ),
					'items' => [
						'page_home',
						'page_about',
						'page_blog',
					],
				],
			],

			/**
			 * Set values for theme mods
			 */
			'theme_mods'  => [
				/**
				 * Set the values for the first front page panel.
				 */
				'photographus_panel_1_content_type'               => 'latest-posts',
				'photographus_panel_1_latest_posts_short_version' => true,

				/**
				 * Set the values for the second front page panel.
				 */
				'photographus_panel_2_content_type'               => 'post',
				'photographus_panel_2_post'                       => '{{snowy-landscape}}',
			],
		] );
	}
}

add_action( 'after_setup_theme', 'photographus_add_theme_support' );

if ( ! function_exists( 'photographus_add_editor_style' ) ) {
	/**
	 * Adds editor styles for the backend editor.
	 */
	function photographus_add_editor_style() {
		/**
		 * Add stylesheet and font.
		 */
		if ( is_rtl() ) {
			add_editor_style( [
				'assets/css/editor-style-rtl.css',
				photographus_fonts_url(),
			] );
		} else {
			add_editor_style( [
				'assets/css/editor-style.css',
				photographus_fonts_url(),
			] );
		}

	}
}

add_action( 'after_setup_theme', 'photographus_add_editor_style' );


if ( ! function_exists( 'photographus_register_menus' ) ) {
	/**
	 * Register Menus
	 */
	function photographus_register_menus() {
		register_nav_menus( [
			/* translators: Name of menu position in the header */
			'primary' => __( 'Primary Menu', 'photographus' ),
			/* translators: Name of menu position in the footer */
			'footer'  => __( 'Footer Menu', 'photographus' ),
		] );
	}
}

add_action( 'init', 'photographus_register_menus' );

if ( ! function_exists( 'photographus_register_sidebars' ) ) {
	/**
	 * Register sidebars
	 */
	function photographus_register_sidebars() {
		/**
		 * Registering the main sidebar which is displayed next to the content on larger viewports
		 */
		register_sidebar( [
			'name'          => __( 'Main Sidebar', 'photographus' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets in this area will be displayed on all posts and pages by default.', 'photographus' ),
			'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		] );

		/**
		 * Registering the widget area for the footer
		 */
		register_sidebar( [
			'name'          => __( 'Footer Sidebar', 'photographus' ),
			'id'            => 'sidebar-footer',
			'description'   => __( 'Widgets will be displayed in the footer.', 'photographus' ),
			'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		] );
	}
}

add_action( 'widgets_init', 'photographus_register_sidebars' );

if ( ! function_exists( 'photographus_scripts_styles' ) ) {
	/**
	 * Adds the scripts and styles to the header
	 */
	function photographus_scripts_styles() {
		/**
		 * Enqueue script so if a answer to a comment is written, the comment form appears
		 * directly below this comment.
		 * Only enqueue it if in single view with open comments and threaded comments enabled.
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		/**
		 * Enqueue the Photographus stylesheet.
		 */
		if ( is_rtl() ) {
			wp_enqueue_style( 'photographus-style', get_theme_file_uri( 'assets/css/photographus-rtl.css' ), [], null );
		} else {
			wp_enqueue_style( 'photographus-style', get_theme_file_uri( 'assets/css/photographus.css' ), [], null );
		}

		/**
		 * Enqueue the PT Serif font from Google fonts.
		 */
		wp_enqueue_style( 'photographus-font', photographus_fonts_url(), [], null );

		/**
		 * Enqueue the Masonry script. This is a newer version than in core and additionally we do not need the
		 * »imagesloaded« dependency which would be loaded if we would use the core masonry.
		 */
		wp_enqueue_script( 'photographus-masonry', get_theme_file_uri( 'assets/js/masonry.js' ), [], null, true );

		/**
		 * Enqueue the Photographus JavaScript functions.
		 */
		wp_enqueue_script( 'photographus-script', get_theme_file_uri( 'assets/js/functions.js' ), [ 'photographus-masonry' ], null, true );

		/**
		 * Enqueue the Photographus JavaScript functions.
		 */
		wp_enqueue_script( 'photographus-customize-preview-script', get_theme_file_uri( 'assets/js/customize-preview.js' ), [ 'photographus-script' ], null, true );

		/**
		 * Remove box shadow from links in admin bar.
		 */
		if ( is_admin_bar_showing() ) {
			wp_add_inline_style( 'photographus-style', '#wpadminbar a {box-shadow: none}' );
		}

		/**
		 * Adding dark mode styles for html inline, because the conditional .-dark-mode class
		 * is on the body element.
		 */
		$dark_mode = get_theme_mod( 'photographus_dark_mode', false );
		if ( true === $dark_mode ) {
			wp_add_inline_style( 'photographus-style', 'html{ background: #222; color: #eee } ' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'photographus_scripts_styles' );
