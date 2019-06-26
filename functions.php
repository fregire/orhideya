<?php
/**
 * Leto functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Leto
 */

if ( ! function_exists( 'leto_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function leto_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Leto, use a find and replace
	 * to change 'leto' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'leto', get_template_directory() . '/languages' );

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

	add_image_size( 'leto-large-thumb', 780 );
	add_image_size( 'leto-medium-thumb', 400 );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' 		=> esc_html__( 'Primary menu', 'leto' ),
		'footer_menu' 	=> esc_html__( 'Footer menu', 'leto' ),

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
	add_theme_support( 'custom-background', apply_filters( 'leto_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 100,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'leto_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function leto_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'leto_content_width', 640 );
}
add_action( 'after_setup_theme', 'leto_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function leto_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'leto' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'leto' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	for ( $i=1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => __( 'Footer ', 'leto' ) . $i,
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}	

	if ( class_exists('WooCommerce') ) {
		register_widget( 'Leto_Product_Loop' );
	}

	register_widget( 'Leto_Facts' );
	register_widget( 'Leto_Featured_Boxes');
	register_widget( 'Leto_Blog' );
	register_widget( 'Leto_Sidebar_Posts' );
	register_widget( 'Leto_Gallery' );
	register_widget( 'Leto_Social' );
}
add_action( 'widgets_init', 'leto_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function leto_scripts() {
	wp_enqueue_style( 'leto-style', get_stylesheet_uri() );

	wp_enqueue_style( 'leto-fonts', '//fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i', array(), null );

	wp_enqueue_script( 'leto-scripts', get_template_directory_uri() . '/js/plugins.js', array('jquery'),'20170711', true );

	wp_enqueue_script( 'leto-main', get_template_directory_uri() . '/js/main.js', array('jquery', 'imagesloaded'), '20171108', true );

	wp_enqueue_style( 'ionicons', get_template_directory_uri() . '/css/ionicons.min.css' );

	wp_enqueue_style( 'leto-plugins-css', get_template_directory_uri() . '/css/plugins.css' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'leto_scripts' );

/**
 * Enqueue Bootstrap
 */
function leto_enqueue_bootstrap() {
	wp_enqueue_style( 'leto-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.min.css', array(), true );
}
add_action( 'wp_enqueue_scripts', 'leto_enqueue_bootstrap', 9 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/orchideya/footer-functions.php';
require get_template_directory() . '/inc/orchideya/header-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Woocommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Load Page Builder compatibility file.
 */
require get_template_directory() . '/inc/page-builder.php';


/**
 * Widgets
 */
require get_template_directory() . '/widgets/class-leto-facts.php';
require get_template_directory() . '/widgets/class-leto-product-loop.php';
require get_template_directory() . '/widgets/class-leto-featured-boxes.php';
require get_template_directory() . '/widgets/class-leto-blog.php';
require get_template_directory() . '/widgets/class-leto-sidebar-posts.php';
require get_template_directory() . '/widgets/class-leto-gallery.php';
require get_template_directory() . '/widgets/class-leto-social.php';


/**
 * Recommend plugins
 */
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'leto_recommended_plugins' );
function leto_recommended_plugins() {

    $plugins[] = array(
            'name'               => 'Page Builder by SiteOrigin',
            'slug'               => 'siteorigin-panels',
            'required'           => false,
    );

    $plugins[] = array(
            'name'               => 'WooCommerce',
            'slug'               => 'woocommerce',
            'required'           => false,
    ); 

    $plugins[] = array(
            'name'               => 'Kirki',
            'slug'               => 'kirki',
            'required'           => false,
    );      

    tgmpa( $plugins);

}

/* Orchideya */
// add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98);
// function sb_woo_remove_reviews_tab($tabs) {

// 	unset($tabs['reviews']);

// 	return $tabs;
// }

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );


add_filter('woocommerce_get_image_size_thumbnail','add_thumbnail_size',1,10);
function add_thumbnail_size($size){

    $size['width'] = 300;
    $size['height'] = 400;
    $size['crop']   = 1; //0 - не обрезаем, 1 - обрезка
    return $size;
}

add_filter('woocommerce_get_image_size_gallery_thumbnail','add_gallery_thumbnail_size',1,10);
function add_gallery_thumbnail_size($size){

    $size['width'] = 1000;
    $size['height'] = 2000;
    $size['crop']   = 1;
    return $size;
}


// add_filter('woocommerce_get_image_size_single','add_single_size',1,10);
// function add_single_size($size){

//     $size['width'] = 400;
//     $size['height'] = 600;
//     $size['crop']   = 0;
//     return $size;
// }



// Деактивация стандартного виджета WC
add_action( 'widgets_init', 'override_woocommerce_widgets', 15 );

function override_woocommerce_widgets() {

  if ( class_exists( 'WC_Widget_Layered_Nav_Filters' ) ) {
    unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
 
    include_once( 'widgets/class-wc-widget-layered-nav-filters.php' );
 
    register_widget( 'Custom_WC_Widget_Layered_Nav_Filters' );
  }

}


// Вывод меню сайта вместе с scheme.org
class True_Walker_Nav_Menu extends Walker_Nav_Menu {
	/*
	 * Позволяет перезаписать <ul class="sub-menu">
	 */
	function start_lvl(&$output, $depth) {
		/*
		 * $depth – уровень вложенности, например 2,3 и т д
		 */ 
		$output .= '<ul class="menu_sublist">';
	}
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output
	 * @param object $item Объект элемента меню, подробнее ниже.
	 * @param int $depth Уровень вложенности элемента меню.
	 * @param object $args Параметры функции wp_nav_menu
	 */
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;           
		/*
		 * Некоторые из параметров объекта $item
		 * ID - ID самого элемента меню, а не объекта на который он ссылается
		 * menu_item_parent - ID родительского элемента меню
		 * classes - массив классов элемента меню
		 * post_date - дата добавления
		 * post_modified - дата последнего изменения
		 * post_author - ID пользователя, добавившего этот элемент меню
		 * title - заголовок элемента меню
		 * url - ссылка
		 * attr_title - HTML-атрибут title ссылки
		 * xfn - атрибут rel
		 * target - атрибут target
		 * current - равен 1, если является текущим элементом
		 * current_item_ancestor - равен 1, если текущим (открытым на сайте) является вложенный элемент данного
		 * current_item_parent - равен 1, если текущим (открытым на сайте) является родительский элемент данного
		 * menu_order - порядок в меню
		 * object_id - ID объекта меню
		 * type - тип объекта меню (таксономия, пост, произвольно)
		 * object - какая это таксономия / какой тип поста (page /category / post_tag и т д)
		 * type_label - название данного типа с локализацией (Рубрика, Страница)
		 * post_parent - ID родительского поста / категории
		 * post_title - заголовок, который был у поста, когда он был добавлен в меню
		 * post_name - ярлык, который был у поста при его добавлении в меню
		 */
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		/*
		 * Генерируем строку с CSS-классами элемента меню
		 */
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
 
		// функция join превращает массив в строку
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
 
		/*
		 * Генерируем ID элемента
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
 	
 		/*
			Scheme разметка
 		*/
		$itempropName = ' itemprop="name" ';
		$itempropUrl = ' itemprop="url" ';
		/*
		 * Генерируем элемент меню
		 */
		$output .= $indent . '<li'  . $itempropName . $id . $value . $class_names .'>';
 
		// атрибуты элемента, title="", rel="", target="" и href=""
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
 
		// ссылка и околоссылочный текст
		$item_output = $args->before;
		$item_output .= '<a '. $itempropUrl . $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
 
 		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
