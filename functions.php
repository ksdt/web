<?php
/**
 * xx functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package xx
 */

if ( ! function_exists( 'xx_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function xx_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on xx, use a find and replace
	 * to change 'xx' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'xx', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'xx' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'xx_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'xx_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function xx_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'xx_content_width', 640 );
}
add_action( 'after_setup_theme', 'xx_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function xx_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'xx' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'xx' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'xx_widgets_init' );

show_admin_bar(false);

/* https://codex.wordpress.org/Rewrite_API/add_rewrite_rule */
function custom_rewrite_basic() {
	
	/* playlist/1015 => playlist/?playlist=1015 
	

	  doesn't work
		playlist/1015 => ksdt.org/playlist => index.php?page_id=69
		
		http://regexr.com/
	*/
  add_rewrite_rule('^playlist\/([0-9]+)\/?$', 'index.php?pagename=playlist&playlist=$matches[1]', 'top');
  
  /* show/Burger Town => show/?showName=Burger Town */
  /* http://regexr.com/3e2gg */
  add_rewrite_rule('^show\/(.*)/?$', 'index.php?pagename=show&showName=$matches[1]', 'top');
  
  add_rewrite_rule('^writings/blog', 'index.php?pagename=posts', 'top');
}
add_action('init', 'custom_rewrite_basic');



function add_query_vars_filter( $vars ){
  array_push($vars, "showName", "playlist");
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


function create_post_type() {
	
  /* creates custom 'album review' posts */
  register_post_type( 'albumreviews',
    array(
      'labels' => array(
        'name' => __( 'Album Reviews' ),
        'singular_name' => __( 'Album Review' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'writings/albumreviews', 'with_front' => false),
      'hierarchical' => true,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'trackbacks', 'excerpt'),
			/* supports are the properties that will show up in the backend wordpress menu */
			'taxonomies' => array('category'),
    )
  );
  
  /* creates custom 'weeklypicks' posts */
  register_post_type( 'weeklypicks',
    array(
      'labels' => array(
        'name' => __( 'Weekly Picks' ),
        'singular_name' => __( 'Weekly Picks' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'writings/weeklypicks', 'with_front' => false),
      'hierarchical' => true,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'trackbacks', 'excerpt'),
			/* supports are the properties that will show up in the backend wordpress menu */
			'taxonomies' => array('category'),
    )
  );
  
  /* creates custom 'concertreviews' posts */
  register_post_type( 'concertreviews',
    array(
      'labels' => array(
        'name' => __( 'Concert Reviews' ),
        'singular_name' => __( 'Concert Reviews' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'writings/concertreviews', 'with_front' => false),
      'hierarchical' => true,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'trackbacks', 'excerpt'),
			/* supports are the properties that will show up in the backend wordpress menu */
			'taxonomies' => array('category'),
    )
  );
  
  
}

add_action( 'init', 'create_post_type' );

/* add full frame iframe setting */
function mytheme_customize_register( $wp_customize ) {
		$wp_customize->add_setting( 'fulliframe' , array(
				'default'     => '',
				'transport'   => 'refresh',
				'type' => 'theme_mod'
		) );
		$wp_customize->add_control( 'fulliframe', array(
			  'label' => __( 'Index Full Page iFrame' ),
			  'description' => __( 'Enter a url (must be https), and it will show on the right of the index page. If it is blank, instagram posts will show.' ),
			  'type' => 'textarea',
			  'section' => 'custom_settings',
		) );
		$wp_customize->add_section( 'custom_settings', array(
			  'title' => __( 'Theme Settings' ),
			  'description' => __( 'Theme specific stuff here.' ),
			  'priority' => 160,
			  'capability' => 'edit_theme_options'
		) );
}
add_action( 'customize_register', 'mytheme_customize_register' );

/**
 * Enqueue scripts and styles.
 */
function xx_scripts() {
	wp_enqueue_style( 'xx-style', get_stylesheet_uri() );
	wp_enqueue_style( 'xx-sass-styles', get_template_directory_uri() . '/sass/index.css');

	wp_enqueue_script( 'zoom', get_template_directory_uri() . '/js/zoom.min.js', array('jquery'), '', true);
	wp_enqueue_script( 'xx-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'xx-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'xx_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
