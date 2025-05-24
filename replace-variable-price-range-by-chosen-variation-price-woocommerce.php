<?php
/*
 * Plugin Name: WooCommerce Variation Price Fix
 * Plugin URI: https://dennisvandalen.com
 * Description: Custom fix for showing chosen variation price instead of price range in WooCommerce.
 * Author: Dennis
 * Version: 1.0
 * Author URI: https://dennisvandalen.com
 * Text Domain: woocommerce-variation-price-fix
 * License: GPLv2+
 *
 * Based on 'Replace the Variable Price range by the chosen variation price in WooCommerce' by Laura Díaz
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function replace_variable_price_range_by_chosen_variation_price_woocommerce() {
    load_plugin_textdomain( 'replace-variable-price-range-by-chosen-variation-price-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'replace_variable_price_range_by_chosen_variation_price_woocommerce' );

add_action( 'woocommerce_before_single_product', 'check_if_variable_first' );
function check_if_variable_first(){
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product->is_type( 'variable' ) ) {
            // Remove the default price display for variable products
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

            // Add our custom price display
            add_action( 'woocommerce_single_product_summary', 'custom_wc_template_single_price', 10 );
            function custom_wc_template_single_price(){
                global $product;

                if ( $product->is_type('variable') ) :

                    // Main Price Range
                    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
                    $price = $prices[0] !== $prices[1] ? sprintf( __( 'v.a. %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

                    // Regular (sale) Price Range
                    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
                    sort( $prices );
                    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'v.a. %1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

                    if ( $price !== $saleprice && $product->is_on_sale() ) {
                        $price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
                    }

                    ?>
                    <style>
                        div.woocommerce-variation-price,
                        div.woocommerce-variation-availability,
                        div.hidden-variable-price {
                            height: 0 !important;
                            overflow: hidden;
                            position: relative;
                            line-height: 0 !important;
                            font-size: 0 !important;
                        }
                    </style>

                    <div class="hidden-variable-price" style="display:none;"><?php echo wp_kses_post($price); ?></div>

                    <script>
                    jQuery(function($){
                        $('.variations_form').on('found_variation', function(event, variation){
                            if (variation && variation.price_html) {
                                // Update price
                                $('p.price').html(variation.price_html);

                                // Update availability
                                if (variation.availability_html) {
                                    if ($('p.availability').length) {
                                        $('p.availability').html(variation.availability_html);
                                    } else {
                                        $('p.price').append('<p class="availability">' + variation.availability_html + '</p>');
                                    }
                                } else {
                                    $('p.availability').remove();
                                }
                                console.log('Variation ID:', variation.variation_id);
                            } else {
                                // Reset to original price range when no variation selected
                                $('p.price').html($('.hidden-variable-price').html());
                                $('p.availability').remove();
                                console.log('No variation selected');
                            }
                        });
                    });
                    </script>
                    <?php

                endif;
            }
        }
    }
}

// Disable out-of-stock variations
add_filter( 'woocommerce_variation_is_active', 'desactivar_variaciones_sin_stock', 10, 2 );
function desactivar_variaciones_sin_stock( $is_active, $variation ) {
    if ( ! $variation->is_in_stock() ) return false;
    return $is_active;
}