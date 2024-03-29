<?php
/**
 * Header functions
 *
 * @package Leto
 */


/**
 * Site branding
 */
function leto_branding() {
	?>
		<div class="site-branding">
			<?php the_custom_logo(); ?>

			<div class="site-branding__content">
			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
			</div>
		</div><!-- .site-branding -->
		<?php
}
add_action( 'leto_inside_header', 'leto_branding', 8 );

/**
 * Main navigation
 */
function leto_main_navigation() {
	?>
		<nav id="site-navigation" class="main-navigation">
			<?php
				wp_nav_menu( array(
					'items_wrap'	 => '<ul itemscope itemtype="http://www.schema.org/SiteNavigationElement" id="%1$s" class="%2$s">%3$s</ul>',
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
					'walker'		 => new True_Walker_Nav_Menu()
				) );
			?>
		</nav><!-- #site-navigation -->	

		<div class="header-mobile-menu">
			<div class="header-mobile-menu__inner">
				<button class="toggle-mobile-menu">
					<span><?php esc_html_e( 'Toggle menu', 'leto' ); ?></span>
				</button>
			</div>
			<div class="header-mobile-menu__tip website-tip">
				Чтобы открыть меню сайта, нажмите сюда
			</div>
		</div><!-- /.header-mobile-menu -->		


		<?php $show_menu_additions = get_theme_mod( 'leto_show_menu_additions', 1 ); ?>
		<?php if ( $show_menu_additions ) : ?>
		<ul class="nav-link-right">
			<li class="nav-link-tel nav-link-tel--desktop">
				<a href="tel:89226048574">8 (922) 604-85-74</a>
			</li>
			<?php
			$enable_search = get_theme_mod( 'leto_enable_search', 1 );
			if ( $enable_search ) : ?>
			<li class="nav-link-search">
				<a href="#" class="toggle-search-box">
					<i class="ion-ios-search"></i>
				</a>
			</li>
			<?php endif; ?>

		</ul>
		<?php endif; ?>

	<?php
}
add_action( 'leto_inside_header', 'leto_main_navigation', 9 );

/**
 * Mobile menu
 */
function leto_mobile_menu() {
	?>
	<div class="mobile-menu">
		<div class="container-full">

			<nav class="mobile-menu__navigation">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				) );
			?>
			</nav><!-- /.mobile-menu__navigation -->
			<p>
				<a href="tel:89226048574">8 (922) 604-85-74</a>
			</p>
			<p>
				<a href="mailto:orchideyaplus@gmail.com">orchideyaplus@gmail.com</a>
			</p>
			<p>
				ТРЦ Мегаполис, 2 этаж, бутик А-210<br>
				8 Марта 198 
			</p>
			<p>
				Часы работы: 10:00-22:00
			</p>
			<div class="mobile-menu__search">
				
				<?php get_search_form(); ?>

			</div><!-- /.mobile-menu__search -->
		</div>
	</div><!-- /.mobile-menu -->
	<?php
}
add_action( 'leto_before_page', 'leto_mobile_menu' );

/**
 * Banner for pages and shop archives
 */
function leto_page_banner() {
	
	if ( is_home() || is_front_page() || is_page_template( 'page-templates/template_page-builder.php') ) {
		return;
	}

	$wc_check = class_exists( 'WooCommerce' );

	if ( $wc_check && is_shop() ) {	
		$hero = get_the_post_thumbnail_url( get_option( 'woocommerce_shop_page_id' ) );
	} elseif ( $wc_check &&  ( is_product_category() || is_product_tag() ) ) {
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $hero = wp_get_attachment_url( $thumbnail_id );
	} elseif ( is_page() ) {
		global $post;
		$hero = get_the_post_thumbnail_url( $post->ID );
	} else {
		return;
	}

	if ( $hero != '' ) {
		$has_background = 'has-background';
	} else {
		$has_background = '';
	}

	?>
	<div class="page-header text-center">
		<div class="container">
			<?php if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) : ?>
			<div class="page-breadcrumbs">
				<nav class="breadcrumbs">
					<?php woocommerce_breadcrumb(); ?>
				</nav>
			</div>
			<?php endif; ?>
			<?php if ( is_home() ) : ?>
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			<?php elseif ( is_page() ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php elseif ( class_exists( 'Woocommerce') && is_woocommerce() ) : ?>
				<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
			<?php endif; ?>
		</div>
	</div>		
	<?php
}
add_action( 'leto_after_header', 'leto_page_banner' );

/**
 * Page header for archives
 */
function leto_archives_header() {

	if ( ( !is_home() && !is_archive() ) || is_front_page() || ( class_exists( 'WooCommerce' ) && is_woocommerce() ) ) {
		return;
	}

	?>
	<div class="page-header text-center">
		<div class="container">
		<?php if ( is_archive() ) : ?>
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php elseif ( is_home() ) : ?>
			<h1 class="entry-title"><?php single_post_title(); ?></h1>
		<?php endif; ?>		
		</div>
	</div><!-- /.page-header -->

	<?php
}
add_action( 'leto_after_header', 'leto_archives_header', 11 );


/**
 * Slider defaults
 */
function leto_slider_defaults() {
	$defaults = array(
		array(
			'slider_image' 			=> get_stylesheet_directory_uri() . '/images/1.jpeg',
			'slider_text'			=> __( 'Welcome to our site', 'leto'),
			'slider_smalltext'		=> __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'leto'),
			'slider_button_link'	=> '#primary',
			'slider_button_text'	=> __( 'I am ready', 'leto'),
		),
		array(
			'slider_image' 			=> get_stylesheet_directory_uri() . '/images/2.jpg',
			'slider_text'			=> __( 'Welcome to our site', 'leto'),
			'slider_smalltext'		=> __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'leto'),
			'slider_button_link'	=> '#primary',
			'slider_button_text'	=> __( 'I am ready', 'leto'),	
		),			
	);
	return $defaults;
}

/**
 * Hero area for the homepage
 */
function leto_hero_slider() {

	if ( !is_front_page() ) {
		return;
	}

	echo '<div class="hero-area">';
		the_custom_header_markup();
	echo '</div>';		
}
add_action( 'leto_after_header', 'leto_hero_slider' );
