<?php
/**
 * Layered Navigation Filters Widget.
 *
 * @package WooCommerce/Widgets
 * @version 2.3.0
 */

defined( 'ABSPATH' ) || exit;
/**
 * Widget layered nav filters.
 */
class Custom_WC_Widget_Layered_Nav_Filters extends WC_Widget_Layered_Nav_Filters {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_layered_nav_filters';
		$this->widget_description = __( 'Display a list of active product filters.', 'woocommerce' );
		$this->widget_id          = 'woocommerce_layered_nav_filters';
		$this->widget_name        = __( 'Active Product Filters', 'woocommerce' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __( 'Active filters', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! is_shop() && ! is_product_taxonomy() ) {
			return;
		}

		$cross_icon = '<?xml version="1.0" encoding="iso-8859-1"?>
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 475 500" style="enable-background:new 0 0 475.2 475.2;" xml:space="preserve" fill="#999999">
						<g>
							<g>
								<path d="M405.6,69.6C360.7,24.7,301.1,0,237.6,0s-123.1,24.7-168,69.6S0,174.1,0,237.6s24.7,123.1,69.6,168s104.5,69.6,168,69.6
									s123.1-24.7,168-69.6s69.6-104.5,69.6-168S450.5,114.5,405.6,69.6z M386.5,386.5c-39.8,39.8-92.7,61.7-148.9,61.7
									s-109.1-21.9-148.9-61.7c-82.1-82.1-82.1-215.7,0-297.8C128.5,48.9,181.4,27,237.6,27s109.1,21.9,148.9,61.7
									C468.6,170.8,468.6,304.4,386.5,386.5z"/>
								<path d="M342.3,132.9c-5.3-5.3-13.8-5.3-19.1,0l-85.6,85.6L152,132.9c-5.3-5.3-13.8-5.3-19.1,0c-5.3,5.3-5.3,13.8,0,19.1
									l85.6,85.6l-85.6,85.6c-5.3,5.3-5.3,13.8,0,19.1c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4l85.6-85.6l85.6,85.6c2.6,2.6,6.1,4,9.5,4
									c3.5,0,6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1l-85.4-85.6l85.6-85.6C347.6,146.7,347.6,138.2,342.3,132.9z"/>
							</g>
						</g>
						</svg>';
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
		$base_link          = $this->get_current_page_url();

		if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $rating_filter ) ) {

			$this->widget_start( $args, $instance );

			echo '<ul>';

			// Attributes.
			if ( ! empty( $_chosen_attributes ) ) {
				foreach ( $_chosen_attributes as $taxonomy => $data ) {
					foreach ( $data['terms'] as $term_slug ) {
						$term = get_term_by( 'slug', $term_slug, $taxonomy );
						if ( ! $term ) {
							continue;
						}

						$filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
						$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
						$current_filter = array_map( 'sanitize_title', $current_filter );
						$new_filter     = array_diff( $current_filter, array( $term_slug ) );

						$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );

						if ( count( $new_filter ) > 0 ) {
							$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
						}

						echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . $cross_icon . ' ' . esc_html( $term->name ) . '</a></li>';
					}
				}
			}

			if ( $min_price ) {
				$link = remove_query_arg( 'min_price', $base_link );
				/* translators: %s: minimum price */
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . $cross_icon . sprintf( __( 'Min %s', 'woocommerce' ), wc_price( $min_price ) ) . '</a></li>'; // WPCS: XSS ok.
			}

			if ( $max_price ) {
				$link = remove_query_arg( 'max_price', $base_link );
				/* translators: %s: maximum price */
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . $cross_icon . sprintf( __( 'Max %s', 'woocommerce' ), wc_price( $max_price ) ) . '</a></li>'; // WPCS: XSS ok.
			}

			if ( ! empty( $rating_filter ) ) {
				foreach ( $rating_filter as $rating ) {
					$link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
					$link         = $link_ratings ? add_query_arg( 'rating_filter', $link_ratings ) : remove_query_arg( 'rating_filter', $base_link );

					/* translators: %s: rating */
					echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'woocommerce' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Rated %s out of 5', 'woocommerce' ), esc_html( $rating ) ) . '</a></li>';
				}
			}

			echo '</ul>';

			$this->widget_end( $args );
		}
	}
}
