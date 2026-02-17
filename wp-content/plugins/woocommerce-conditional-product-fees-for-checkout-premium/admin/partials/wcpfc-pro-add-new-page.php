<?php
/**
 * Handles plugin rules settings
 * 
 * @package Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @since   3.9.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
global $ds_analytics;
$wcpfc_admin_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( '', '' );
$wcpfc_object       = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro( '', '' );
$allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
if ( isset( $_REQUEST['action'], $_REQUEST['id'] ) && 'edit' === $_REQUEST['action'] ) { // phpcs:ignore
	$request_post_id    		    = sanitize_text_field( $_REQUEST['id'] ); //phpcs:ignore
	$btnValue           		    = esc_html__( 'Update Fee', 'woocommerce-conditional-product-fees-for-checkout' );
	$fee_title          		    = esc_html( get_the_title( $request_post_id ) );
	$getFeesCost        		    = get_post_meta( $request_post_id, 'fee_settings_product_cost', true );
	$getFeesType        		    = get_post_meta( $request_post_id, 'fee_settings_select_fee_type', true );
	$wcpfc_tooltip_desc 		    = get_post_meta( $request_post_id, 'fee_settings_tooltip_desc', true );
	$wcpfc_price_message_on_cart    = get_post_meta( $request_post_id, 'fee_settings_price_message_on_cart', true );
	$getFeesStartDate   		    = get_post_meta( $request_post_id, 'fee_settings_start_date', true );
	$getFeesEndDate     		    = get_post_meta( $request_post_id, 'fee_settings_end_date', true );
	$getFeesTaxable     		    = get_post_meta( $request_post_id, 'fee_settings_select_taxable', true );
	$getFeesTaxableType     	    = get_post_meta( $request_post_id, 'fee_settings_taxable_type', true );
	$displayFeesSingleProduct       = get_post_meta( $request_post_id, 'display_fees_in_product_page', true );
	$ds_time_from       		    = get_post_meta( $request_post_id, 'ds_time_from', true );
	$ds_time_to         		    = get_post_meta( $request_post_id, 'ds_time_to', true );
	$fee_show_on_checkout_only	    = get_post_meta( $request_post_id, 'fee_show_on_checkout_only', true );
	$fees_on_cart_total			    = get_post_meta( $request_post_id, 'fees_on_cart_total', true );
	$ds_select_day_of_week          = get_post_meta( $request_post_id, 'ds_select_day_of_week', true );

	if ( is_serialized( $ds_select_day_of_week ) ) {
		$ds_select_day_of_week = maybe_unserialize( $ds_select_day_of_week );
	} else {
		$ds_select_day_of_week = $ds_select_day_of_week;
	}

	$getFeesStatus      = get_post_status( $request_post_id );
	$productFeesArray   = get_post_meta( $request_post_id, 'product_fees_metabox', true );
	if ( is_serialized( $productFeesArray ) ) {
		$productFeesArray = maybe_unserialize( $productFeesArray );
	} else {
		$productFeesArray = $productFeesArray;
	}
	if ( wcpffc_fs()->is__premium_only() ) {
		if ( wcpffc_fs()->can_use_premium_code() ) {
			$getFeesOptional     						= get_post_meta( $request_post_id, 'fee_settings_select_optional', true );
			$getFeesOptionalType     					= get_post_meta( $request_post_id, 'fee_settings_optional_type', true );
			$getFeesOptionalDetails    					= get_post_meta( $request_post_id, 'fee_settings_optional_description', true );
			$default_optional_checked		  			= get_post_meta( $request_post_id, 'default_optional_checked', true );
			$optional_fee_title				  			= get_post_meta( $request_post_id, 'optional_fee_title', true );
			$getFeesPerQtyFlag                      	= get_post_meta( $request_post_id, 'fee_chk_qty_price', true );
			$getFeesPerQty                          	= get_post_meta( $request_post_id, 'fee_per_qty', true );
			$extraProductCost                       	= get_post_meta( $request_post_id, 'extra_product_cost', true );
			$ap_rule_status                         	= get_post_meta( $request_post_id, 'ap_rule_status', true );
			$cost_on_product_status                 	= get_post_meta( $request_post_id, 'cost_on_product_status', true );
			$display_product_ap_rule_in_single_product	= get_post_meta( $request_post_id, 'display_product_ap_rule_in_single_product', true );
			$display_category_ap_rule_in_single_product	= get_post_meta( $request_post_id, 'display_category_ap_rule_in_single_product', true );
			$cost_on_product_weight_status          	= get_post_meta( $request_post_id, 'cost_on_product_weight_status', true );
			$cost_on_product_subtotal_status        	= get_post_meta( $request_post_id, 'cost_on_product_subtotal_status', true );
			$cost_on_category_status                	= get_post_meta( $request_post_id, 'cost_on_category_status', true );
			$cost_on_category_weight_status         	= get_post_meta( $request_post_id, 'cost_on_category_weight_status', true );
			$cost_on_category_subtotal_status       	= get_post_meta( $request_post_id, 'cost_on_category_subtotal_status', true );
			$cost_on_total_cart_qty_status          	= get_post_meta( $request_post_id, 'cost_on_total_cart_qty_status', true );
			$cost_on_total_cart_qty_n_combination   	= get_post_meta( $request_post_id, 'cost_on_total_cart_qty_n_combination', true );
			$cost_on_total_cart_weight_status       	= get_post_meta( $request_post_id, 'cost_on_total_cart_weight_status', true );
			$cost_on_total_cart_subtotal_status     	= get_post_meta( $request_post_id, 'cost_on_total_cart_subtotal_status', true );
			$cost_on_shipping_class_subtotal_status 	= get_post_meta( $request_post_id, 'cost_on_shipping_class_subtotal_status', true );
			$sm_metabox_ap_product                  	= get_post_meta( $request_post_id, 'sm_metabox_ap_product', true );
			$is_allow_custom_weight_base  				= get_post_meta( $request_post_id, 'is_allow_custom_weight_base', true );
			$sm_custom_weight_base_cost  				= get_post_meta( $request_post_id, 'sm_custom_weight_base_cost', true );
			$sm_custom_weight_base_per_each  			= get_post_meta( $request_post_id, 'sm_custom_weight_base_per_each', true );
			$sm_custom_weight_base_over  				= get_post_meta( $request_post_id, 'sm_custom_weight_base_over', true );
			$first_order_for_user		  				= get_post_meta( $request_post_id, 'first_order_for_user', true );
			$fee_settings_recurring		  				= get_post_meta( $request_post_id, 'fee_settings_recurring', true );
			$optional_fees_in_cart		  				= get_post_meta( $request_post_id, 'optional_fees_in_cart', true );
			
			if ( is_serialized( $sm_metabox_ap_product ) ) {
				$sm_metabox_ap_product = maybe_unserialize( $sm_metabox_ap_product );
			} else {
				$sm_metabox_ap_product = $sm_metabox_ap_product;
			}
			$sm_metabox_ap_product_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_product_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_product_subtotal ) ) {
				$sm_metabox_ap_product_subtotal = maybe_unserialize( $sm_metabox_ap_product_subtotal );
			} else {
				$sm_metabox_ap_product_subtotal = $sm_metabox_ap_product_subtotal;
			}
			$sm_metabox_ap_product_weight = get_post_meta( $request_post_id, 'sm_metabox_ap_product_weight', true );
			if ( is_serialized( $sm_metabox_ap_product_weight ) ) {
				$sm_metabox_ap_product_weight = maybe_unserialize( $sm_metabox_ap_product_weight );
			} else {
				$sm_metabox_ap_product_weight = $sm_metabox_ap_product_weight;
			}
			$sm_metabox_ap_category = get_post_meta( $request_post_id, 'sm_metabox_ap_category', true );
			if ( is_serialized( $sm_metabox_ap_category ) ) {
				$sm_metabox_ap_category = maybe_unserialize( $sm_metabox_ap_category );
			} else {
				$sm_metabox_ap_category = $sm_metabox_ap_category;
			}
			$sm_metabox_ap_category_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_category_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_category_subtotal ) ) {
				$sm_metabox_ap_category_subtotal = maybe_unserialize( $sm_metabox_ap_category_subtotal );
			} else {
				$sm_metabox_ap_category_subtotal = $sm_metabox_ap_category_subtotal;
			}
			$sm_metabox_ap_category_weight = get_post_meta( $request_post_id, 'sm_metabox_ap_category_weight', true );
			if ( is_serialized( $sm_metabox_ap_category_weight ) ) {
				$sm_metabox_ap_category_weight = maybe_unserialize( $sm_metabox_ap_category_weight );
			} else {
				$sm_metabox_ap_category_weight = $sm_metabox_ap_category_weight;
			}
			$sm_metabox_ap_total_cart_qty = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_qty', true );
			if ( is_serialized( $sm_metabox_ap_total_cart_qty ) ) {
				$sm_metabox_ap_total_cart_qty = maybe_unserialize( $sm_metabox_ap_total_cart_qty );
			} else {
				$sm_metabox_ap_total_cart_qty = $sm_metabox_ap_total_cart_qty;
			}
			$sm_metabox_ap_total_cart_weight = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_weight', true );
			if ( is_serialized( $sm_metabox_ap_total_cart_weight ) ) {
				$sm_metabox_ap_total_cart_weight = maybe_unserialize( $sm_metabox_ap_total_cart_weight );
			} else {
				$sm_metabox_ap_total_cart_weight = $sm_metabox_ap_total_cart_weight;
			}
			$sm_metabox_ap_total_cart_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_total_cart_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_total_cart_subtotal ) ) {
				$sm_metabox_ap_total_cart_subtotal = maybe_unserialize( $sm_metabox_ap_total_cart_subtotal );
			} else {
				$sm_metabox_ap_total_cart_subtotal = $sm_metabox_ap_total_cart_subtotal;
			}
			$sm_metabox_ap_shipping_class_subtotal = get_post_meta( $request_post_id, 'sm_metabox_ap_shipping_class_subtotal', true );
			if ( is_serialized( $sm_metabox_ap_shipping_class_subtotal ) ) {
				$sm_metabox_ap_shipping_class_subtotal = maybe_unserialize( $sm_metabox_ap_shipping_class_subtotal );
			} else {
				$sm_metabox_ap_shipping_class_subtotal = $sm_metabox_ap_shipping_class_subtotal;
			}
			$cost_rule_match = get_post_meta( $request_post_id, 'cost_rule_match', true );
			if ( ! empty( $cost_rule_match ) ) {
				if ( is_serialized( $cost_rule_match ) ) {
					$cost_rule_match = maybe_unserialize( $cost_rule_match );
				} else {
					$cost_rule_match = $cost_rule_match;
				}
				if ( array_key_exists( 'general_rule_match', $cost_rule_match ) ) {
					$general_rule_match = $cost_rule_match['general_rule_match'];
				} else {
					$general_rule_match = 'all';
				}
				if ( array_key_exists( 'cost_on_product_rule_match', $cost_rule_match ) ) {
					$cost_on_product_rule_match = $cost_rule_match['cost_on_product_rule_match'];
				} else {
					$cost_on_product_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_product_weight_rule_match', $cost_rule_match ) ) {
					$cost_on_product_weight_rule_match = $cost_rule_match['cost_on_product_weight_rule_match'];
				} else {
					$cost_on_product_weight_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_product_subtotal_rule_match', $cost_rule_match ) ) {
					$cost_on_product_subtotal_rule_match = $cost_rule_match['cost_on_product_subtotal_rule_match'];
				} else {
					$cost_on_product_subtotal_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_category_rule_match', $cost_rule_match ) ) {
					$cost_on_category_rule_match = $cost_rule_match['cost_on_category_rule_match'];
				} else {
					$cost_on_category_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_category_weight_rule_match', $cost_rule_match ) ) {
					$cost_on_category_weight_rule_match = $cost_rule_match['cost_on_category_weight_rule_match'];
				} else {
					$cost_on_category_weight_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_category_subtotal_rule_match', $cost_rule_match ) ) {
					$cost_on_category_subtotal_rule_match = $cost_rule_match['cost_on_category_subtotal_rule_match'];
				} else {
					$cost_on_category_subtotal_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_total_cart_qty_rule_match', $cost_rule_match ) ) {
					$cost_on_total_cart_qty_rule_match = $cost_rule_match['cost_on_total_cart_qty_rule_match'];
				} else {
					$cost_on_total_cart_qty_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_total_cart_weight_rule_match', $cost_rule_match ) ) {
					$cost_on_total_cart_weight_rule_match = $cost_rule_match['cost_on_total_cart_weight_rule_match'];
				} else {
					$cost_on_total_cart_weight_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_total_cart_subtotal_rule_match', $cost_rule_match ) ) {
					$cost_on_total_cart_subtotal_rule_match = $cost_rule_match['cost_on_total_cart_subtotal_rule_match'];
				} else {
					$cost_on_total_cart_subtotal_rule_match = 'any';
				}
				if ( array_key_exists( 'cost_on_shipping_class_subtotal_rule_match', $cost_rule_match ) ) {
					$cost_on_shipping_class_subtotal_rule_match = $cost_rule_match['cost_on_shipping_class_subtotal_rule_match'];
				} else {
					$cost_on_shipping_class_subtotal_rule_match = 'any';
				}
			} else {
				$general_rule_match                         = 'all';				
				$cost_on_product_rule_match                 = 'any';
				$cost_on_product_weight_rule_match          = 'any';
				$cost_on_product_subtotal_rule_match        = 'any';
				$cost_on_category_rule_match                = 'any';
				$cost_on_category_weight_rule_match         = 'any';
				$cost_on_category_subtotal_rule_match       = 'any';
				$cost_on_total_cart_qty_rule_match          = 'any';
				$cost_on_total_cart_weight_rule_match       = 'any';
				$cost_on_total_cart_subtotal_rule_match     = 'any';
				$cost_on_shipping_class_subtotal_rule_match = 'any';
			}
		}
	}
} else {
	$request_post_id    			  = '';
	$btnValue           			  = __( 'Save Fee', 'woocommerce-conditional-product-fees-for-checkout' );
	$fee_title          			  = '';
	$getFeesCost        			  = '';
	$getFeesType        			  = '';
	$wcpfc_tooltip_desc 			  = '';
	$wcpfc_price_message_on_cart      = '';
	$getFeesStartDate   			  = '';
	$getFeesEndDate     			  = '';
	$displayFeesSingleProduct   	  = '';
	$getFeesTaxable     			  = '';
	$getFeesTaxableType     	      = '';
	$getFeesOptional    			  = '';
	$getFeesOptionalType   			  = '';
    $getFeesOptionalDetails     	  = '';
	$getFeesStatus      			  = '';
	$ds_time_from 					  = "";
	$ds_time_to						  = "";
	$fee_show_on_checkout_only		  = "";
	$fees_on_cart_total				  = "";
	$ds_select_day_of_week			  = "";
	$productFeesArray   = array();
	if ( wcpffc_fs()->is__premium_only() ) {
		if ( wcpffc_fs()->can_use_premium_code() ) {
			$getFeesPerQtyFlag                          = '';
			$getFeesPerQty                              = '';
			$extraProductCost                           = '';
			$ap_rule_status                             = '';
			$cost_on_product_status                     = '';
			$display_product_ap_rule_in_single_product 	= '';
			$display_category_ap_rule_in_single_product = '';
			$cost_on_category_status                    = '';
			$cost_on_total_cart_qty_status              = '';
			$cost_on_total_cart_qty_n_combination       = '';
			$cost_on_product_weight_status              = '';
			$cost_on_category_weight_status             = '';
			$cost_on_total_cart_weight_status           = '';
			$cost_on_total_cart_subtotal_status         = '';
			$cost_on_shipping_class_subtotal_status     = '';
			$sm_metabox_ap_product                      = array();
			$sm_metabox_ap_category                     = array();
			$sm_metabox_ap_total_cart_qty               = array();
			$sm_metabox_ap_product_weight               = array();
			$sm_metabox_ap_category_weight              = array();
			$sm_metabox_ap_total_cart_weight            = array();
			$sm_metabox_ap_total_cart_subtotal          = array();
			$sm_metabox_ap_shipping_class_subtotal      = array();
			$general_rule_match                         = 'all';
			$cost_on_product_rule_match                 = 'any';
			$cost_on_product_weight_rule_match          = 'any';
			$cost_on_product_subtotal_rule_match        = 'any';
			$cost_on_category_rule_match                = 'any';
			$cost_on_category_weight_rule_match         = 'any';
			$cost_on_category_subtotal_rule_match       = 'any';
			$cost_on_total_cart_qty_rule_match          = 'any';
			$cost_on_total_cart_weight_rule_match       = 'any';
			$cost_on_total_cart_subtotal_rule_match     = 'any';
			$cost_on_shipping_class_subtotal_rule_match = 'any';
			$is_allow_custom_weight_base 				= "";
			$sm_custom_weight_base_cost 				= "";
			$sm_custom_weight_base_per_each 			= "";
			$sm_custom_weight_base_over 				= "";
			$first_order_for_user						= "";
			$fee_settings_recurring						= "";
			$default_optional_checked					= "";
			$optional_fee_title							= __( 'Optional fee(s)', 'woocommerce-conditional-product-fees-for-checkout' );
			$optional_fees_in_cart						= "";
		}
	}
}
$sm_status = ( ( ! empty( $getFeesStatus ) && 'publish' === $getFeesStatus ) || empty( $getFeesStatus ) ) ? 'checked' : '';
if ( wcpffc_fs()->is__premium_only() ) {
	if ( wcpffc_fs()->can_use_premium_code() ) {
		$ap_rule_status                         = ( ! empty( $ap_rule_status ) && 'on' === $ap_rule_status && "" !== $ap_rule_status ) ? 'checked' : '';
		$cost_on_product_status                 = ( ! empty( $cost_on_product_status ) && 'on' === $cost_on_product_status && "" !== $cost_on_product_status ) ? 'checked' : '';
		$display_product_ap_rule_in_single_product		= ( ! empty( $display_product_ap_rule_in_single_product ) && 'on' === $display_product_ap_rule_in_single_product && "" !== $display_product_ap_rule_in_single_product ) ? 'checked' : '';
		$display_category_ap_rule_in_single_product		= ( ! empty( $display_category_ap_rule_in_single_product ) && 'on' === $display_category_ap_rule_in_single_product && "" !== $display_category_ap_rule_in_single_product ) ? 'checked' : '';
		$cost_on_product_weight_status          = ( ! empty( $cost_on_product_weight_status ) && 'on' === $cost_on_product_weight_status && "" !== $cost_on_product_weight_status ) ? 'checked' : '';
		$cost_on_product_subtotal_status        = ( ! empty( $cost_on_product_subtotal_status ) && 'on' === $cost_on_product_subtotal_status && "" !== $cost_on_product_subtotal_status ) ? 'checked' : '';
		$cost_on_category_status                = ( ! empty( $cost_on_category_status ) && 'on' === $cost_on_category_status && "" !== $cost_on_category_status ) ? 'checked' : '';
		$cost_on_category_weight_status         = ( ! empty( $cost_on_category_weight_status ) && 'on' === $cost_on_category_weight_status && "" !== $cost_on_category_weight_status ) ? 'checked' : '';
		$cost_on_category_subtotal_status       = ( ! empty( $cost_on_category_subtotal_status ) && 'on' === $cost_on_category_subtotal_status && "" !== $cost_on_category_subtotal_status ) ? 'checked' : '';
		$cost_on_total_cart_qty_status          = ( ! empty( $cost_on_total_cart_qty_status ) && 'on' === $cost_on_total_cart_qty_status && "" !== $cost_on_total_cart_qty_status ) ? 'checked' : '';
		$cost_on_total_cart_qty_n_combination   = ( ! empty( $cost_on_total_cart_qty_n_combination ) && 'on' === $cost_on_total_cart_qty_n_combination && "" !== $cost_on_total_cart_qty_n_combination ) ? 'checked' : '';
		$cost_on_total_cart_weight_status       = ( ! empty( $cost_on_total_cart_weight_status ) && 'on' === $cost_on_total_cart_weight_status && "" !== $cost_on_total_cart_weight_status ) ? 'checked' : '';
		$cost_on_total_cart_subtotal_status     = ( ! empty( $cost_on_total_cart_subtotal_status ) && 'on' === $cost_on_total_cart_subtotal_status && "" !== $cost_on_total_cart_subtotal_status ) ? 'checked' : '';
		$cost_on_shipping_class_subtotal_status = ( ! empty( $cost_on_shipping_class_subtotal_status ) && 'on' === $cost_on_shipping_class_subtotal_status && "" !== $cost_on_shipping_class_subtotal_status ) ? 'checked' : '';
	}
}

$get_weight_unit = get_option( 'woocommerce_weight_unit' );
$get_weight_unit = isset( $get_weight_unit ) && !empty( $get_weight_unit ) ? $get_weight_unit : 'kg';
$weight_unit = '(' . $get_weight_unit . ')';
?>
<div class="wcpfc-plugin-modal-main">
	<div class="wcpfc-plugin-modal-outer">
		<div class="wcpfc-plugin-modal-inner">
			<div class="wcpfc-plugin-modal-wrap">
				<div class="wcpfc-plugin-modal-header">
					<span class="dashicons dashicons-no-alt modal-close-btn"></span>
				</div>
				<div class="wcpfc-plugin-modal-body">
					<h3 class="wcpfc-modal-title"><?php esc_html_e( 'Oops! It looks like you have entered a negative value in the price box.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h3>
					<p><?php echo wp_kses( __( 'Are you perhaps looking to offer a discount to your customers instead? Our "<strong>Discount Plugin</strong>" can help you easily and best way to set up and manage discounts for your products or services. It\'s a quick and easy solution that can save you time and hassle.', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                            'strong' => array(),
                        )
					); ?></p>
					<p><?php esc_html_e('Simply click the link below to learn more about our Discount Plugin and how it can benefit your business.', 'woocommerce-conditional-product-fees-for-checkout') ?></p>
				</div>
				<div class="wcpfc-plugin-modal-footer">
					<a class="wcpfc-modal-more-btn" href="<?php echo esc_url('https://www.thedotstore.com/woocommerce-conditional-discount-rules-for-checkout/'); ?>" target="_blank"><?php esc_html_e( 'Learn More', 'woocommerce-conditional-product-fees-for-checkout' ) ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="wcpfc-section-left">
	<div class="wcpfc-main-table res-cl wcpfc-add-rule-page">
		<?php wp_nonce_field( 'wcpfc_pro_product_fees_conditions_values_ajax_action', 'wcpfc_pro_product_fees_conditions_values_ajax' ); ?>
		<h2><?php esc_html_e( 'Fee Configuration', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
		<form method="POST" name="feefrm" action="">
			<?php wp_nonce_field( 'wcpfc_pro_fees_conditions_save_action', 'wcpfc_pro_fees_conditions_save' ); ?>
			<input type="hidden" name="post_type" value="wc_conditional_fee">
			<input type="hidden" name="fee_post_id" value="<?php echo esc_attr( $request_post_id ) ?>">
			<div class="wcpfc-rule-general-settings wcpfc-table-tooltip">
				<table class="form-table table-outer product-fee-table">
					<tbody>
						<tr valign="top">
							<th class="titledesc" scope="row">
		                        <label for="onoffswitch">
		                        	<?php esc_html_e( 'Status', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                        	<?php echo wp_kses( wc_help_tip( esc_html__( 'Enable or Disable this fee using this button (This fee will be visible to customers only if it is enabled).', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
	                        	</label>
							</th>
							<td class="forminp">
								<label class="switch">
									<input type="checkbox" name="fee_settings_status"
									       value="on" <?php echo esc_attr( $sm_status ); ?>>
									<div class="slider round"></div>
								</label>
							</td>
						</tr>

						<tr valign="top">
							<th class="titledesc" scope="row">
		                        <label for="fee_settings_product_fee_title">
		                            <?php esc_html_e( 'Fee Title', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									<span class="required-star">*</span>
									<?php echo wp_kses( wc_help_tip( esc_html__( 'This name will be visible to the customer at the time of checkout. This should convey the purpose of the fee you are applying to the order. For example "Deposit Fee", "Night Charges", "Insurance Fee", etc.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
		                    </th>
							<td class="forminp">
								<input type="text" name="fee_settings_product_fee_title" class="text-class" id="fee_settings_product_fee_title" value="<?php echo isset( $fee_title ) ? esc_attr( $fee_title ) : ''; ?>" required="1" placeholder="<?php esc_attr_e( 'Enter fee title', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
							</td>
						</tr>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="fee_settings_select_fee_type">
		                            <?php esc_html_e( 'Fee Type', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            <?php echo wp_kses( wc_help_tip( esc_html__( 'You can apply extra fees as a fixed or percentage price.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
							</th>
							<td class="forminp">
								<select name="fee_settings_select_fee_type" id="fee_settings_select_fee_type" class="">
									<option value="fixed" <?php echo isset( $getFeesType ) && 'fixed' === $getFeesType ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Fixed', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
									<option value="percentage" <?php echo isset( $getFeesType ) && 'percentage' === $getFeesType ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Percentage', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
									<option value="both" <?php echo isset( $getFeesType ) && 'both' === $getFeesType ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Percentage + Fee', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
								</select>
								<span class="fees_on_cart_total_wrap">
									<input type="checkbox" name="fees_on_cart_total" id="fees_on_cart_total" value="on" <?php checked( $fees_on_cart_total, 'on' ); ?>/>
									<label for="fees_on_cart_total"><strong><?php esc_html_e( 'Apply fee on cart total', 'woocommerce-conditional-product-fees-for-checkout' ); ?></strong></label>
								</span>
							</td>
						</tr>
                        <?php 
                            // Display dotstore marketing field for pro plugins
                            if ( wcpffc_fs()->is__premium_only() ) {
                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                    echo $ds_analytics->dsmrkt_marketing_html__premium_only(3790); // phpcs:ignore
                                }
                            }
                            ?>
						<tr valign="top">
							<th class="titledesc" scope="row">
		                        <label for="fee_settings_product_cost">
		                            <?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									<span class="required-star">*</span>
									<?php echo wp_kses( wc_help_tip( esc_html__( 'You can add a fixed/percentage fee based on the selection above.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
		                    </th>
							<td class="forminp">
								<div class="product_cost_left_div">
									<?php if( isset( $getFeesType ) && 'percentage' === $getFeesType ){ ?>
										<input type="number" name="fee_settings_product_cost" required="1" class="text-class" id="fee_settings_product_cost" value="<?php echo isset( $getFeesCost ) ? esc_attr( $getFeesCost ) : ''; ?>" placeholder="%" autocomplete="off" step="0.01" />
									<?php } else { ?>
										<input type="text" name="fee_settings_product_cost" required="1" class="text-class" id="fee_settings_product_cost" value="<?php echo isset( $getFeesCost ) ? esc_attr( $getFeesCost ) : ''; ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>" autocomplete="off" />
									<?php } ?>
								</div>
								<?php
								if ( wcpffc_fs()->is__premium_only() ) {
									if ( wcpffc_fs()->can_use_premium_code() ) {
										?>
										<div class="product_cost_right_div">
											<div class="applyperqty-boxone">
												<div class="applyperqty-box">
													<label for="fee_chk_qty_price"><?php esc_html_e( 'Apply Per Additional Unit', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
														<?php echo wp_kses( wc_help_tip( esc_html__( 'This will charge incrementally based on each extra count or quantity beyond the base amount.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</label>
													<input type="checkbox" name="fee_chk_qty_price" id="fee_chk_qty_price" class="chk_qty_price_class" value="on" <?php checked( $getFeesPerQtyFlag, 'on' ); ?> />
												</div>
											</div>
											<div class="applyperqty-boxtwo">
												<div class="applyperqty-box">
													<label for="apply_per_qty_type"><?php esc_html_e( 'Calculate Based On', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                                    <?php 
                                                    $html = sprintf( '%s<a href=%s target="_blank">%s</a>',
                                                        esc_html__( 'Select the calculation type for your additional fee. To learn more about how it works ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                        esc_url( 'https://docs.thedotstore.com/article/357-how-to-add-product-specific-per-quantity-fee-rules' ),
                                                        esc_html__( 'Click here', 'woocommerce-conditional-product-fees-for-checkout' )
                                                    );
                                                    echo wp_kses( wc_help_tip( wp_kses_post( $html ) ), array( 'span' => $allowed_tooltip_html ) );
                                                    ?>
													</label>
													<select name="fee_per_qty" id="price_cartqty_based" class="chk_qty_price_class" id="apply_per_qty_type">
														<option value="qty_cart_based" <?php selected( $getFeesPerQty, 'qty_cart_based' ); ?>><?php esc_html_e( 'Cart Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
														<option value="qty_product_based" <?php selected( $getFeesPerQty, 'qty_product_based' ); ?>><?php esc_html_e( 'Product Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
														<option value="count_cart_based" <?php selected( $getFeesPerQty, 'count_cart_based' ); ?>><?php esc_html_e( 'Cart Item Count', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													</select>
												</div>
											</div>
											<div class="applyperqty-boxthree">
												<div class="applyperqty-box">
													<label for="extra_product_cost"><?php 
                                                    /* translators: %s: currency symbol */
                                                    printf( esc_html__( 'Fee Per Additional Unit (%s) ', 'woocommerce-conditional-product-fees-for-checkout' ), esc_html( get_woocommerce_currency_symbol() ) ); 
                                                    ?>
			                                        <span class="required-star">*</span>
			                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Set a fee here to be applied for each additional quantity or count, ensuring fair and accurate charges.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                                    </label>
													<input type="text" name="extra_product_cost" class="text-class" id="extra_product_cost" required value="<?php echo isset( $extraProductCost ) ? esc_attr( $extraProductCost ) : ''; ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
												</div>
											</div>
										</div>
										<div class="description wcpfc_dynamic_rules_tooltips">
											<p><?php echo wp_kses( __('<strong>Dynamic Charges:</strong> You can enter fixed amount or make it dynamic using the below short code parameters:', 'woocommerce-conditional-product-fees-for-checkout'), array('strong' => array())); ?></p>
											<div class="wcpfc_dynamic_rules_content">
												<?php
												echo sprintf( wp_kses( __( '&nbsp;&nbsp;&nbsp;<span>[qty]</span> - total number of items in cart<br>&nbsp;&nbsp;&nbsp;<span>[cost]</span> - cost of items<br>&nbsp;&nbsp;&nbsp;<span>[weight]</span> - weight of items<br>&nbsp;&nbsp;&nbsp;<span>[fee min_fee=20]</span> - Minimum fee to apply<br>&nbsp;&nbsp;&nbsp;<span>[fee max_fee=20]</span> - Maximum fee to apply<br><br>Below are some examples:<br>&nbsp;&nbsp;&nbsp;<strong>i.</strong> 10.00 -> To add flat 10.00 fee charge.<br>&nbsp;&nbsp;&nbsp;<strong>ii.</strong> 10.00 * <span>[qty]</span> - To charge 10.00 per quantity in the cart. It will be 50.00 if the cart has 5 quantity.<br>&nbsp;&nbsp;&nbsp;<strong>iii.</strong> <span>[fee min_fee=20]</span> - This means minimum 20 fee charge will be applicable.<br>&nbsp;&nbsp;&nbsp;<strong>iv.</strong> <span>[fee max_fee=20]</span> - This means cart subtotal charge greater than max_fee then maximum 20 charge will be applicable.<br>', 'woocommerce-conditional-product-fees-for-checkout' )
												, array( 'br' => array(),'a' => array('href' => array(),'title' => array(),'target' => array(),'class' => array()), 'span' => array( 'class' => array() ), 'strong'   => array() ) ) );
												?>
											</div>
										</div>
										<?php
									} else {
                                        ?>
                                        <div class="product_cost_right_div">
											<div class="applyperqty-boxone">
												<div class="applyperqty-box">
													<label for="fee_chk_qty_price">
                                                        <?php esc_html_e( 'Apply Per Additional Unit', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                                        <span class="wcpfc-pro-label"></span>
														<?php echo wp_kses( wc_help_tip( esc_html__( 'This will charge incrementally based on each extra count or quantity beyond the base amount.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</label>
													<input type="checkbox" name="fee_chk_qty_price" id="fee_chk_qty_price" class="chk_qty_price_class" checked disabled />
												</div>
											</div>
											<div class="applyperqty-boxtwo">
												<div class="applyperqty-box">
													<label for="apply_per_qty_type">
                                                        <?php esc_html_e( 'Calculate Based On', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                                        <span class="wcpfc-pro-label"></span>
                                                        <?php 
                                                        $html = sprintf( '%s<a href=%s target="_blank">%s</a>',
                                                            esc_html__( 'Select the calculation type for your additional fee. To learn more about how it works ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                            esc_url( 'https://docs.thedotstore.com/article/357-how-to-add-product-specific-per-quantity-fee-rules' ),
                                                            esc_html__( 'Click here', 'woocommerce-conditional-product-fees-for-checkout' )
                                                        );
                                                        echo wp_kses( wc_help_tip( wp_kses_post( $html ) ), array( 'span' => $allowed_tooltip_html ) );
                                                        ?>
													</label>
													<select name="fee_per_qty" id="price_cartqty_based" class="chk_qty_price_class" id="apply_per_qty_type" disabled>
														<option><?php esc_html_e( 'Cart Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
														<option><?php esc_html_e( 'Product Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
														<option><?php esc_html_e( 'Cart Item Count', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													</select>
												</div>
											</div>
											<div class="applyperqty-boxthree">
												<div class="applyperqty-box">
													<label for="extra_product_cost"><?php 
                                                        /* translators: %s: currency symbol */
                                                        printf( esc_html__( 'Fee Per Additional Unit (%s) ', 'woocommerce-conditional-product-fees-for-checkout' ), esc_html( get_woocommerce_currency_symbol() ) ); ?>
                                                        <span class="wcpfc-pro-label"></span>
                                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Set a fee here to be applied for each additional quantity or count, ensuring fair and accurate charges.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                    </label>
                                                    <input type="text" name="extra_product_cost" class="text-class" id="extra_product_cost" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>" disabled />
												</div>
											</div>
										</div>
                                        <?php
                                    }
								} else {
                                    ?>
                                    <div class="product_cost_right_div">
                                        <div class="applyperqty-boxone">
                                            <div class="applyperqty-box">
                                                <label for="fee_chk_qty_price">
                                                    <?php esc_html_e( 'Apply Per Additional Unit', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                                    <span class="wcpfc-pro-label"></span>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'This will charge incrementally based on each extra count or quantity beyond the base amount.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                </label>
                                                <input type="checkbox" name="fee_chk_qty_price" id="fee_chk_qty_price" class="chk_qty_price_class" checked disabled />
                                            </div>
                                        </div>
                                        <div class="applyperqty-boxtwo">
                                            <div class="applyperqty-box">
                                                <label for="apply_per_qty_type">
                                                    <?php esc_html_e( 'Calculate Based On', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                                    <span class="wcpfc-pro-label"></span>
                                                    <?php 
                                                    $html = sprintf( '%s<a href=%s target="_blank">%s</a>',
                                                        esc_html__( 'Select the calculation type for your additional fee. To learn more about how it works ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                        esc_url( 'https://docs.thedotstore.com/article/357-how-to-add-product-specific-per-quantity-fee-rules' ),
                                                        esc_html__( 'Click here', 'woocommerce-conditional-product-fees-for-checkout' )
                                                    );
                                                    echo wp_kses( wc_help_tip( wp_kses_post( $html ) ), array( 'span' => $allowed_tooltip_html ) );
                                                    ?>
                                                </label>
                                                <select name="fee_per_qty" id="price_cartqty_based" class="chk_qty_price_class" id="apply_per_qty_type" disabled>
                                                    <option><?php esc_html_e( 'Cart Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option><?php esc_html_e( 'Product Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option><?php esc_html_e( 'Cart Item Count', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="applyperqty-boxthree">
                                            <div class="applyperqty-box">
                                                <label for="extra_product_cost"><?php 
                                                    /* translators: %s: currency symbol */
                                                    printf( esc_html__( 'Fee Per Additional Unit (%s) ', 'woocommerce-conditional-product-fees-for-checkout' ), esc_html( get_woocommerce_currency_symbol() ) ); ?>
                                                    <span class="wcpfc-pro-label"></span>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Set a fee here to be applied for each additional quantity or count, ensuring fair and accurate charges.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                </label>
                                                <input type="text" name="extra_product_cost" class="text-class" id="extra_product_cost" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
								?>
								<a href="javascript:void(0);" class="wcpffc_chk_advanced_settings">Advance settings</a>
							</td>
						</tr>
						<tr valign="top" class="wcpffc_advanced_setting_section">
							<th class="titledesc" scope="row">
		                        <label for="fee_settings_start_date">
		                            <?php esc_html_e( 'Start Date', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            <?php echo wp_kses( wc_help_tip( esc_html__( 'Select the Start Date on which you want to enable the fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
							</th>
							<td class="forminp">
								<input type="text" name="fee_settings_start_date" class="text-class" id="fee_settings_start_date" value="<?php echo isset( $getFeesStartDate ) ? esc_attr( $getFeesStartDate ) : ''; ?>" placeholder="<?php esc_attr_e( 'Select start date', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
							</td>
						</tr>
						<tr valign="top" class="wcpffc_advanced_setting_section">
							<th class="titledesc" scope="row">
		                        <label for="fee_settings_end_date">
		                            <?php esc_html_e( 'End Date', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            <?php echo wp_kses( wc_help_tip( esc_html__( 'Select the End Date on which you want to disable the fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
							</th>
							<td class="forminp">
								<input type="text" name="fee_settings_end_date" class="text-class" id="fee_settings_end_date" value="<?php echo isset( $getFeesEndDate ) ? esc_attr( $getFeesEndDate ) : ''; ?>" placeholder="<?php esc_attr_e( 'Select end date', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
							</td>
						</tr>
						<tr valign="top" class="wcpffc_advanced_setting_section">
							<th class="titledesc" scope="row">
								<label for="ds_select_day_of_week">
		                            <?php esc_html_e( 'Day Of The Week', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            <?php $html = sprintf( '%s<a href=%s target="_blank">%s</a>',
										esc_html__( 'Select the days on which you want to enable fees on your website. This rule will match with the current day which is set by WordPress ', 'woocommerce-conditional-product-fees-for-checkout' ),
										esc_url( admin_url( 'options-general.php' ) ),
										esc_html__( 'Timezone', 'woocommerce-conditional-product-fees-for-checkout' )
									);
		                        	echo wp_kses( wc_help_tip( wp_kses_post( $html ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
							</th>
							<td class="forminp">
								<?php
								$select_day_week_array = array(
									'sun' => esc_html__( 'Sunday', 'woocommerce-conditional-product-fees-for-checkout' ),
									'mon' => esc_html__( 'Monday', 'woocommerce-conditional-product-fees-for-checkout' ),
									'tue' => esc_html__( 'Tuesday', 'woocommerce-conditional-product-fees-for-checkout' ),
									'wed' => esc_html__( 'Wednesday', 'woocommerce-conditional-product-fees-for-checkout' ),
									'thu' => esc_html__( 'Thursday', 'woocommerce-conditional-product-fees-for-checkout' ),
									'fri' => esc_html__( 'Friday', 'woocommerce-conditional-product-fees-for-checkout' ),
									'sat' => esc_html__( 'Saturday', 'woocommerce-conditional-product-fees-for-checkout' ),
								);
								?>
								<select name="ds_select_day_of_week[]" id="ds_select_day_of_week" class="ds_select_day_of_week wcpfc_select" multiple="multiple" placeholder='<?php echo esc_attr( 'Select day of the Week', 'woocommerce-conditional-product-fees-for-checkout' ); ?>'>
									<?php foreach ( $select_day_week_array as $value => $name ) { ?>
										<option value="<?php echo esc_attr( $value ); ?>" <?php echo ! empty( $ds_select_day_of_week ) && in_array( $value, $ds_select_day_of_week, true ) ? 'selected="selected"' : '' ?>><?php echo esc_html( $name ); ?></option>
		                            <?php } ?>
								</select>
							</td>
						</tr>
						<tr valign="top" class="wcpffc_advanced_setting_section">
							<th class="titledesc" scope="row">
								<label for="fee_show_on_checkout_only">
		                            <?php esc_html_e( 'Time', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            <?php
			                        $html = sprintf( '%s<a href=%s target="_blank">%s</a>',
			                            esc_html__( 'Select the time at which you want to enable fees on your website. This rule will match with the current day which is set by WordPress ', 'woocommerce-conditional-product-fees-for-checkout' ),
			                            esc_url( admin_url( 'options-general.php' ) ),
			                            esc_html__( 'Timezone', 'woocommerce-conditional-product-fees-for-checkout' )
			                        );
			                        echo wp_kses( wc_help_tip( wp_kses_post( $html ) ), array( 'span' => $allowed_tooltip_html ) );
			                        ?>
		                        </label>
							</th>
							<td class="forminp">
		                        <div class="ds_time_wrap">
		                            <span class="ds_time_from"><?php esc_html_e( 'From:', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
		                            <input type="text" name="ds_time_from" class="text-class" id="ds_time_from" value="<?php echo esc_attr( $ds_time_from ); ?>" placeholder='<?php echo esc_attr( 'Select start time', 'woocommerce-conditional-product-fees-for-checkout' ); ?>' autocomplete="off">
		                        </div>
		                        <div class="ds_time_wrap">
		                            <span class="ds_time_to"><?php esc_html_e( 'To:', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
		                            <input type="text" name="ds_time_to" class="text-class" id="ds_time_to" value="<?php echo esc_attr( $ds_time_to ); ?>" placeholder='<?php echo esc_attr( 'Select end time', 'woocommerce-conditional-product-fees-for-checkout' ); ?>' autocomplete="off">
		                        </div>
								<a href="javascript:void(0)" class="ds_reset_time"></a>
							</td>
						</tr>
						<?php 
						if ( wcpffc_fs()->is__premium_only() ) {
							if ( wcpffc_fs()->can_use_premium_code() ) {
								?>
								<tr valign="top" class="wcpffc_advanced_setting_section">
									<th class="titledesc" scope="row">
		                                <label for="first_order_for_user">
		                                    <?php esc_html_e( 'User\'s First Order', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Apply the fee for the user\'s first order only.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                                </label>
									</th>
									<td class="forminp">
										<input type="checkbox" name="first_order_for_user" id="first_order_for_user" class="chk_qty_price_class" value="on" <?php checked( $first_order_for_user, 'on' ); ?>>
									</td>
								</tr>
								<?php 
								if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
									?>
									<tr valign="top" class="wcpffc_advanced_setting_section">
				                        <th class="titledesc" scope="row">
				                            <label for="fee_settings_recurring">
				                                <?php esc_html_e( 'Set fee as recurring payment', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
				                                <?php 
                                                /* translators: %1$s: anchor tag start, %2$s anchor tag close */
                                                echo wp_kses( wc_help_tip( sprintf( esc_html__( 'Once selected it will allow fees on recurring payments as well. This option only works with %1$sWooCommerce Subscriptions%2$s plugin\'s products.','woocommerce-conditional-product-fees-for-checkout' ), '<a href="https://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">', '</a>' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
				                            </label>
				                        </th>
				                        <td class="forminp">
				                            <input type="checkbox" name="fee_settings_recurring" id="fee_settings_recurring" class="fee_settings_recurring" value="on" <?php checked( $fee_settings_recurring, 'on' ); ?>>
				                        </td>
				                    </tr>
				                    <?php
								}
		                    } else {
		                    	?>
								<tr valign="top" class="wcpffc_advanced_setting_section">
									<th class="titledesc" scope="row">
		                                <label for="first_order_for_user">
		                                    <?php esc_html_e( 'User\'s First Order', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                    <span class="wcpfc-pro-label"></span>
		                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Apply the fee for the user\'s first order only.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                                </label>
									</th>
									<td class="forminp">
										<input type="checkbox" name="first_order_for_user" id="first_order_for_user" class="chk_qty_price_class" value="" disabled>
									</td>
								</tr>
								<?php 
								if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
									?>
									<tr valign="top" class="wcpffc_advanced_setting_section">
				                        <th class="titledesc" scope="row">
				                            <label for="fee_settings_recurring">
				                                <?php esc_html_e( 'Set fee as recurring payment', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
				                                <span class="wcpfc-pro-label"></span>
				                                <?php 
                                                /* translators: %1$s: anchor tag start, %2$s anchor tag close */
                                                echo wp_kses( wc_help_tip( sprintf( esc_html__( 'Once selected it will allow fees on recurring payments as well. This option only works with %1$sWooCommerce Subscriptions%2$s plugin\'s products.','woocommerce-conditional-product-fees-for-checkout' ), '<a href="https://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">', '</a>' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
				                            </label>
				                        </th>
				                        <td class="forminp">
				                            <input type="checkbox" name="fee_settings_recurring" id="fee_settings_recurring" class="fee_settings_recurring" value="" disabled>
				                        </td>
				                    </tr>
				                    <?php
								}
		                    }
		               	} else {
		               		?>
							<tr valign="top" class="wcpffc_advanced_setting_section">
								<th class="titledesc" scope="row">
	                                <label for="first_order_for_user">
	                                    <?php esc_html_e( 'User\'s First Order', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
	                                    <span class="wcpfc-pro-label"></span>
	                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Apply the fee for the user\'s first order only.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
	                                </label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="first_order_for_user" id="first_order_for_user" class="chk_qty_price_class" value="" disabled>
								</td>
							</tr>
							<?php 
							if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
								?>
								<tr valign="top" class="wcpffc_advanced_setting_section">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_recurring">
			                                <?php esc_html_e( 'Set fee as recurring payment', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php 
                                            /* translators: %1$s: anchor tag start, %2$s anchor tag close */
                                            echo wp_kses( wc_help_tip( sprintf( esc_html__( 'Once selected it will allow fees on recurring payments as well. This option only works with %1$sWooCommerce Subscriptions%2$s plugin\'s products.','woocommerce-conditional-product-fees-for-checkout' ), '<a href="https://woocommerce.com/products/woocommerce-subscriptions/" target="_blank">', '</a>' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="checkbox" name="fee_settings_recurring" id="fee_settings_recurring" class="fee_settings_recurring" value="" disabled>
			                        </td>
			                    </tr>
			                    <?php
							}
		               	}
			            ?>
						<tr valign="top" class="wcpffc_advanced_setting_section">
							<th class="titledesc" scope="row">
								<label for="fee_show_on_checkout_only">
		                            <?php esc_html_e( 'Showcase Fee On Checkout Only', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            <?php echo wp_kses( wc_help_tip( esc_html__( 'This option will show added fees on checkout page only.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                        </label>
							</th>
							<td class="forminp">
								<input type="checkbox" name="fee_show_on_checkout_only" id="fee_show_on_checkout_only" class="fee_show_on_checkout_only" value="on" <?php checked( $fee_show_on_checkout_only, 'on' ); ?>>
							</td>
						</tr>
						<?php 
						if ( wcpffc_fs()->is__premium_only() ) {
							if ( wcpffc_fs()->can_use_premium_code() ) {
								?>
								<tr valign="top" class="wcpffc_advanced_setting_section">
									<th class="titledesc" scope="row">
										<label for="display_fees_in_product_page">
											<?php esc_html_e( 'Display Fees in Product Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'If "Yes" is selected, this fee will be displayed in the product page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp wcpfc-radio-section">
										<label>
											<input name="display_fees_in_product_page" class="display_fees_in_product_page" type="radio" value="yes" <?php checked( $displayFeesSingleProduct, 'yes' ); ?>>
											<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
										<label>
											<input name="display_fees_in_product_page" class="display_fees_in_product_page" type="radio" value="no" <?php empty($displayFeesSingleProduct) ? checked( $displayFeesSingleProduct, '' ) : checked( $displayFeesSingleProduct, 'no' ); ?>>
											<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
									</td>
								</tr>
                                <?php 
                                // Display dotstore marketing field for pro 
                                if ( wcpffc_fs()->is__premium_only() ) {
                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                        echo $ds_analytics->dsmrkt_marketing_html__premium_only(3495); // phpcs:ignore
                                    }
                                }
                                ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="sm_custom_weight_base">
	                                        <?php esc_html_e( 'Each Weight Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
	                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Enable/Disable additional rules per weight on the cart page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
	                                    </label>
									</th>
									<td class="forminp">
										<input type="checkbox" name="is_allow_custom_weight_base" id="is_allow_custom_weight_base" class="is_allow_custom_weight_base" value="on" <?php checked( $is_allow_custom_weight_base, 'on' ); ?>>
									</td>
								</tr>
								<tr valign="top" class="depend_of_custom_weight_base">
			                        <th class="titledesc" scope="row">
			                            <label for="sm_custom_weight_base_cost">
			                                <?php esc_html_e( 'Weight - Rate', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set the amount of the fee which you want to apply per weight.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="text" name="sm_custom_weight_base_cost" class="text-class" id="sm_custom_weight_base_cost" value="<?php echo esc_attr( $sm_custom_weight_base_cost ); ?>" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>">
			                        </td>
			                    </tr>
			                    <tr valign="top" class="depend_of_custom_weight_base">
			                        <th class="titledesc" scope="row">
			                            <label for="sm_custom_weight_base_per_each">
			                                <?php esc_html_e( 'Weight - Per Each', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set per each weight you want to apply the additional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="text" name="sm_custom_weight_base_per_each" class="text-class" id="sm_custom_weight_base_per_each" value="<?php echo esc_attr( $sm_custom_weight_base_per_each ); ?>" placeholder="<?php echo esc_attr( $get_weight_unit ); ?>">
			                        </td>
			                    </tr>
			                    <tr valign="top" class="depend_of_custom_weight_base">
			                        <th class="titledesc" scope="row">
			                            <label for="sm_custom_weight_base_over">
			                                <?php esc_html_e( 'Weight - Over', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'The weight-based fee will apply in front when the cart weight reach over the configured here.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="text" name="sm_custom_weight_base_over" class="text-class" id="sm_custom_weight_base_over" value="<?php echo esc_attr( $sm_custom_weight_base_over ); ?>" placeholder="<?php echo esc_attr( $get_weight_unit ); ?>">
			                        </td>
			                    </tr>
                                <?php 
                                // Display dotstore marketing field for pro plugins
                                if ( wcpffc_fs()->is__premium_only() ) {
                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                        echo $ds_analytics->dsmrkt_marketing_html__premium_only(3379); // phpcs:ignore
                                    }
                                }
                                ?>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wcpfc_tooltip_desc">
											<?php esc_html_e( 'Tooltip Description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'As a tooltip, provide short information for this fee to your customers.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
		                            	<textarea name="wcpfc_tooltip_desc" rows="3" cols="70" id="wcpfc_tooltip_desc" placeholder="<?php esc_attr_e( 'Enter tooltip short description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" maxlength="<?php echo esc_attr( apply_filters( 'wcpfc_set_fee_tooltip_maxlength', 25 ) ); ?>"><?php echo esc_textarea( $wcpfc_tooltip_desc ); ?></textarea>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wcpfc_price_message_on_cart">
											<?php esc_html_e( 'Price Message on Cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'Message to show on the cart page regarding the extra fees added.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
		                            	<textarea name="wcpfc_price_message_on_cart" rows="3" cols="70" id="wcpfc_price_message_on_cart" placeholder="<?php esc_attr_e( 'Enter price message on cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" maxlength="<?php echo esc_attr( apply_filters( 'wcpfc_set_fee_tooltip_maxlength', 25 ) ); ?>"><?php echo esc_textarea( $wcpfc_price_message_on_cart ); ?></textarea>
									</td>
								</tr>
								<?php
							} else {
								?>
								<tr valign="top" class="wcpffc_advanced_setting_section">
									<th class="titledesc" scope="row">
										<label for="display_fees_in_product_page">
											<?php esc_html_e( 'Display Fees in Product Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<span class="wcpfc-pro-label"></span>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'If "Yes" is selected, this fee will be displayed in the product page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp wcpfc-radio-section">
										<label>
											<input name="display_fees_in_product_page" class="display_fees_in_product_page" type="radio" value="" disabled>
											<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
										<label>
											<input name="display_fees_in_product_page" class="display_fees_in_product_page" type="radio" value="" disabled>
											<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="sm_custom_weight_base">
	                                        <?php esc_html_e( 'Each Weight Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
	                                        <span class="wcpfc-pro-label"></span>
	                                        <?php echo wp_kses( wc_help_tip( esc_html__( 'Enable/Disable additional rules per weight on the cart page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
	                                    </label>
									</th>
									<td class="forminp">
										<input type="checkbox" name="is_allow_custom_weight_base" id="is_allow_custom_weight_base" class="is_allow_custom_weight_base" value="">
									</td>
								</tr>
								<tr valign="top" class="depend_of_custom_weight_base">
			                        <th class="titledesc" scope="row">
			                            <label for="sm_custom_weight_base_cost">
			                                <?php esc_html_e( 'Weight - Rate', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set the amount of the fee which you want to apply per weight.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="text" name="sm_custom_weight_base_cost" class="text-class" id="sm_custom_weight_base_cost" value="" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>" disabled />
			                        </td>
			                    </tr>
			                    <tr valign="top" class="depend_of_custom_weight_base">
			                        <th class="titledesc" scope="row">
			                            <label for="sm_custom_weight_base_per_each">
			                                <?php esc_html_e( 'Weight - Per Each', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set per each weight you want to apply the additional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="text" name="sm_custom_weight_base_per_each" class="text-class" id="sm_custom_weight_base_per_each" value="" placeholder="<?php echo esc_attr( $get_weight_unit ); ?>" disabled />
			                        </td>
			                    </tr>
			                    <tr valign="top" class="depend_of_custom_weight_base">
			                        <th class="titledesc" scope="row">
			                            <label for="sm_custom_weight_base_over">
			                                <?php esc_html_e( 'Weight - Over', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'The weight-based fee will apply in front when the cart weight reach over the configured here.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="text" name="sm_custom_weight_base_over" class="text-class" id="sm_custom_weight_base_over" value="" placeholder="<?php echo esc_attr( $get_weight_unit ); ?>" disabled />
			                        </td>
			                    </tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wcpfc_tooltip_desc">
											<?php esc_html_e( 'Tooltip Description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<span class="wcpfc-pro-label"></span>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'As a tooltip, provide short information for this fee to your customers.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
		                            	<textarea name="wcpfc_tooltip_desc" rows="3" cols="70" id="wcpfc_tooltip_desc" placeholder="<?php esc_attr_e( 'Enter tooltip short description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" maxlength="<?php echo esc_attr( apply_filters( 'wcpfc_set_fee_tooltip_maxlength', 25 ) ); ?>" disabled></textarea>
									</td>
								</tr>
								<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wcpfc_price_message_on_cart">
											<?php esc_html_e( 'Price Message on Cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<span class="wcpfc-pro-label"></span>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'Message to show on the cart page regarding the extra fees added.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
		                            	<textarea name="wcpfc_price_message_on_cart" rows="3" cols="70" id="wcpfc_price_message_on_cart" placeholder="<?php esc_attr_e( 'Enter price message on cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" maxlength="<?php echo esc_attr( apply_filters( 'wcpfc_set_fee_tooltip_maxlength', 25 ) ); ?>" disabled><?php echo esc_textarea( $wcpfc_price_message_on_cart ); ?></textarea>
									</td>
								</tr>
								<?php
							}
						} else {
							?>
							<tr valign="top" class="wcpffc_advanced_setting_section">
								<th class="titledesc" scope="row">
									<label for="display_fees_in_product_page">
										<?php esc_html_e( 'Display Fees in Product Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										<span class="wcpfc-pro-label"></span>
										<?php echo wp_kses( wc_help_tip( esc_html__( 'If "Yes" is selected, this fee will be displayed in the product page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
									</label>
								</th>
								<td class="forminp wcpfc-radio-section">
									<label>
										<input name="display_fees_in_product_page" class="display_fees_in_product_page" type="radio" value="" disabled>
										<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									</label>
									<label>
										<input name="display_fees_in_product_page" class="display_fees_in_product_page" type="radio" value="" disabled>
										<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									</label>
								</td>
							</tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="sm_custom_weight_base">
	                                    <?php esc_html_e( 'Each Weight Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
	                                    <span class="wcpfc-pro-label"></span>
	                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Enable/Disable additional rules per weight on the cart page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
	                                </label>
								</th>
								<td class="forminp">
									<input type="checkbox" name="is_allow_custom_weight_base" id="is_allow_custom_weight_base" class="is_allow_custom_weight_base" value="">
								</td>
							</tr>
							<tr valign="top" class="depend_of_custom_weight_base">
		                        <th class="titledesc" scope="row">
		                            <label for="sm_custom_weight_base_cost">
		                                <?php esc_html_e( 'Weight - Rate', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set the amount of the fee which you want to apply per weight.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                            <input type="text" name="sm_custom_weight_base_cost" class="text-class" id="sm_custom_weight_base_cost" value="" placeholder="<?php echo esc_attr( get_woocommerce_currency_symbol() ); ?>" disabled>
		                        </td>
		                    </tr>
		                    <tr valign="top" class="depend_of_custom_weight_base">
		                        <th class="titledesc" scope="row">
		                            <label for="sm_custom_weight_base_per_each">
		                                <?php esc_html_e( 'Weight - Per Each', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Set per each weight you want to apply the additional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                            <input type="text" name="sm_custom_weight_base_per_each" class="text-class" id="sm_custom_weight_base_per_each" value="" placeholder="<?php echo esc_attr( $get_weight_unit ); ?>" disabled />
		                        </td>
		                    </tr>
		                    <tr valign="top" class="depend_of_custom_weight_base">
		                        <th class="titledesc" scope="row">
		                            <label for="sm_custom_weight_base_over">
		                                <?php esc_html_e( 'Weight - Over', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php echo wp_kses( wc_help_tip( esc_html__( 'The weight-based fee will apply in front when the cart weight reach over the configured here.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                            <input type="text" name="sm_custom_weight_base_over" class="text-class" id="sm_custom_weight_base_over" value="" placeholder="<?php echo esc_attr( $get_weight_unit ); ?>" disabled />
		                        </td>
		                    </tr>
							<tr valign="top">
								<th class="titledesc" scope="row">
									<label for="wcpfc_tooltip_desc">
										<?php esc_html_e( 'Tooltip Description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										<span class="wcpfc-pro-label"></span>
										<?php echo wp_kses( wc_help_tip( esc_html__( 'As a tooltip, provide short information for this fee to your customers.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
									</label>
								</th>
								<td class="forminp">
	                            	<textarea name="wcpfc_tooltip_desc" rows="3" cols="70" id="wcpfc_tooltip_desc" placeholder="<?php esc_attr_e( 'Enter tooltip short description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" maxlength="<?php echo esc_attr( apply_filters( 'wcpfc_set_fee_tooltip_maxlength', 25 ) ); ?>" disabled></textarea>
								</td>
							</tr>
							<tr valign="top">
									<th class="titledesc" scope="row">
										<label for="wcpfc_price_message_on_cart">
											<?php esc_html_e( 'Price Message on Cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<span class="wcpfc-pro-label"></span>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'Message to show on the cart page regarding the extra fees added.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
		                            	<textarea name="wcpfc_price_message_on_cart" rows="3" cols="70" id="wcpfc_price_message_on_cart" placeholder="<?php esc_attr_e( 'Enter price message on cart', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" maxlength="<?php echo esc_attr( apply_filters( 'wcpfc_set_fee_tooltip_maxlength', 25 ) ); ?>" disabled><?php echo esc_textarea( $wcpfc_price_message_on_cart ); ?></textarea>
									</td>
								</tr>
							<?php
						}
						?>
						<tr valign="top">
							<th class="titledesc" scope="row">
								<label for="fee_settings_select_taxable">
									<?php esc_html_e( 'Is Amount Taxable ?', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									<?php echo wp_kses( wc_help_tip( esc_html__( 'If "Yes" is selected, this fee will be calculated as taxable.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
								</label>
							</th>
							<td class="forminp wcpfc-radio-section">
								<label>
									<input name="fee_settings_select_taxable" class="fee_settings_select_taxable" type="radio" value="yes" <?php checked( $getFeesTaxable, 'yes' ); ?>>
									<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
								</label>
								<label>
									<input name="fee_settings_select_taxable" class="fee_settings_select_taxable" type="radio" value="no" <?php empty($getFeesTaxable) ? checked( $getFeesTaxable, '' ) : checked( $getFeesTaxable, 'no' ); ?>>
									<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
								</label>
							</td>
						</tr>
						<?php
						if ( wcpffc_fs()->is__premium_only() ) {
							if ( wcpffc_fs()->can_use_premium_code() ) {
								?>
								<tr valign="top" class="enable_taxable_checked">
									<th class="titledesc" scope="row">
										<label for="fee_settings_taxable_type">
											<?php esc_html_e( 'Tax Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'Select the Tax Class.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
										<select name="fee_settings_taxable_type" id="fee_settings_taxable_type"><?php
											// Get all tax classes including the default 'Standard' tax class
											$tax_classes = WC_Tax::get_tax_classes();
											// Prepend 'Standard' as the default tax class
											array_unshift( $tax_classes, 'Standard' );
											// Loop through each tax class and create an option
											foreach ( $tax_classes as $tax_class ) {
												// If it's the default tax class, set the label to 'Standard'
												$tax_class_label = ( 'Standard' === $tax_class ) ? __( 'Standard', 'woocommerce-conditional-product-fees-for-checkout' ) : $tax_class;?>
												<option value="<?php echo esc_attr( sanitize_title( $tax_class ) ) ?>" <?php echo selected( $getFeesTaxableType, sanitize_title( $tax_class ), false ) ?>><?php echo esc_html( $tax_class_label ) ?></option><?php 
											} ?>
										</select>
									</td>
								</tr><?php
							} else { ?>
								<tr valign="top" class="enable_taxable_checked">
									<th class="titledesc" scope="row">
										<label for="fee_settings_taxable_type">
											<?php esc_html_e( 'Tax Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
											<span class="wcpfc-pro-label"></span>
											<?php echo wp_kses( wc_help_tip( esc_html__( 'Select the Tax Class.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										</label>
									</th>
									<td class="forminp">
										<select name="fee_settings_taxable_type" id="fee_settings_taxable_type" disabled>
											<option value=""><?php esc_html_e( 'Standard', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
										</select>
									</td>
								</tr><?php
							}
						} 

						if ( wcpffc_fs()->is__premium_only() ) {
							if ( wcpffc_fs()->can_use_premium_code() ) {
			                    ?>
			                    <tr valign="top">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_select_optional">
			                            	<?php esc_html_e( 'Is Fee Optional ?', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                            	<?php 
                                            /* translators: %1$s: break tag, %2$s break tag, %3$s strong tag start, %4$s strong tag end */
                                            echo sprintf( wp_kses( wc_help_tip( esc_html__( 'This will not apply fee by default. %1$s %2$s %3$sNote: %4$sOnce you select this optional fee to "Yes", It will always show the fee on checkout page as optional.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span'   => $allowed_tooltip_html,
				                                	'strong' => array(),
				                                	'br' 	 => array(),
				                                 ) ), '</br>', '</br>', '<strong>', '</strong>' ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp wcpfc-radio-section">
			                        	<label>
											<input name="fee_settings_select_optional" type="radio" class="fee_settings_select_optional" value="yes" <?php checked( $getFeesOptional, 'yes' ); ?>>
											<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
										<label>
											<input name="fee_settings_select_optional" type="radio" class="fee_settings_select_optional" value="no" <?php empty($getFeesOptional) ? checked( $getFeesOptional, '' ) : checked( $getFeesOptional, 'no' ); ?>>
											<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_optional_type">
			                                <?php esc_html_e( 'Optional Fee Selection Type', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Select the display type of the optional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <select name="fee_settings_optional_type" id="fee_settings_optional_type">
			                                <option value="checkbox" <?php selected( $getFeesOptionalType, 'checkbox' ); ?>><?php esc_html_e( 'Checkbox', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
			                                <option value="dropdown" <?php selected( $getFeesOptionalType, 'dropdown' ); ?>><?php esc_html_e( 'Dropdown', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											<option value="radio-button" <?php selected( $getFeesOptionalType, 'radio-button' ); ?>><?php esc_html_e( 'Radio Button', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
			                            </select>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="default_optional_checked">
			                                <?php esc_html_e( 'Default Optional Fee Checked', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the optional fee will be checked/active by default on the checkout page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="checkbox" name="default_optional_checked" id="default_optional_checked" class="chk_qty_price_class" value="on" <?php checked( $default_optional_checked, 'on' ); ?>>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="optional_fee_title">
			                                <?php esc_html_e( 'Optional Fee Section Title', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php 
                                            /* translators: %1$s: break tag, %2$s break tag, %3$s strong tag start, %4$s strong tag end */
                                            echo sprintf( wp_kses( wc_help_tip( esc_html__( 'Set the optional fee section title that will display before the payment section on the checkout page. %1$s %2$s %3$sNote: %4$sWe are removing this field from upcoming update. From now you can change it by use of  "wcpfc_optional_fee_text" filter hook.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span'   => $allowed_tooltip_html,
				                                	'strong' => array(),
				                                	'br' 	 => array(),
				                                 ) ), '</br>', '</br>', '<strong>', '</strong>' ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                        	<?php $optional_fees_title = isset($optional_fee_title) && !empty($optional_fee_title) ? $optional_fee_title : 'Optional fee(s)' ?>
			                            <input type="text" name="optional_fee_title" id="optional_fee_title" class="text-class" value="<?php echo esc_attr($optional_fees_title); ?>" readonly/>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_optional_description">
			                                <?php esc_html_e( 'Optional Fee Description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Add a detailed note or description about the optional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <textarea name="fee_settings_optional_description" id="fee_settings_optional_description" rows="3" cols="70" maxlength="150" placeholder="<?php esc_attr_e( 'Enter optional fee description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"><?php echo esc_html($getFeesOptionalDetails); ?></textarea>
			                        </td>
			                    </tr>
								<tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="optional_fees_in_cart">
			                                <?php esc_html_e( 'Enable Optional Fees in Cart Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the optional fees will show case in cart page as well.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="checkbox" name="optional_fees_in_cart" id="optional_fees_in_cart" class="optional_fees_in_cart" value="on" <?php checked( $optional_fees_in_cart, 'on' ); ?>>
			                        </td>
			                    </tr>
			                    <?php 
							} else {
								?>
			                    <tr valign="top">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_select_optional">
			                            	<?php esc_html_e( 'Is Fee Optional ?', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                            	<span class="wcpfc-pro-label"></span>
			                            	<?php 
                                            /* translators: %1$s: break tag, %2$s break tag, %3$s strong tag start, %4$s strong tag end */
                                            echo sprintf( wp_kses( wc_help_tip( esc_html__( 'This will not apply fee by default. %1$s %2$s %3$sNote: %4$sOnce you select this optional fee to "Yes", It will always show the fee on checkout page as optional.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span'   => $allowed_tooltip_html,
				                                	'strong' => array(),
				                                	'br' 	 => array(),
				                                 ) ), '</br>', '</br>', '<strong>', '</strong>' ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp wcpfc-radio-section">
			                        	<label>
											<input name="fee_settings_select_optional" type="radio" class="fee_settings_select_optional" value="yes">
											<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
										<label>
											<input name="fee_settings_select_optional" type="radio" class="fee_settings_select_optional" value="no" checked>
											<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										</label>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_optional_type">
			                                <?php esc_html_e( 'Optional Fee Selection Type', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Select the display type of the optional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <select name="fee_settings_optional_type" id="fee_settings_optional_type" disabled>
			                                <option value="checkbox"><?php esc_html_e( 'Checkbox', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
			                                <option value="dropdown"><?php esc_html_e( 'Dropdown', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											<option value="radio-button"><?php esc_html_e( 'Radio Button', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
			                            </select>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="default_optional_checked">
			                                <?php esc_html_e( 'Default Optional Fee Checked', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the optional fee will be checked/active by default on the checkout page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="checkbox" name="default_optional_checked" id="default_optional_checked" class="chk_qty_price_class" value="" disabled>
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="optional_fee_title">
			                                <?php esc_html_e( 'Optional Fee Section Title', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php 
                                            /* translators: %1$s: break tag, %2$s break tag, %3$s strong tag start, %4$s strong tag end */
                                            echo sprintf( wp_kses( wc_help_tip( esc_html__( 'Set the optional fee section title that will display before the payment section on the checkout page. %1$s %2$s %3$sNote: %4$sWe are removing this field from upcoming update. From now you can change it by use of  "wcpfc_optional_fee_text" filter hook.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span'   => $allowed_tooltip_html,
				                                	'strong' => array(),
				                                	'br' 	 => array(),
				                                 ) ), '</br>', '</br>', '<strong>', '</strong>' ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                        	<?php $optional_fees_title = isset($optional_fee_title) && !empty($optional_fee_title) ? $optional_fee_title : 'Optional fee(s)' ?>
			                            <input type="text" name="optional_fee_title" id="optional_fee_title" class="text-class" value="<?php echo esc_attr($optional_fees_title); ?>" readonly disabled />
			                        </td>
			                    </tr>
			                    <tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="fee_settings_optional_description">
			                                <?php esc_html_e( 'Optional Fee Description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Add a detailed note or description about the optional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <textarea name="fee_settings_optional_description" id="fee_settings_optional_description" rows="3" cols="70" maxlength="150" placeholder="<?php esc_attr_e( 'Enter optional fee description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" disabled></textarea>
			                        </td>
			                    </tr>
								<tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="optional_fees_in_cart">
			                                <?php esc_html_e( 'Enable Optional Fees in Cart Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the optional fees will show case in cart page as well.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="checkbox" name="optional_fees_in_cart" id="optional_fees_in_cart" class="optional_fees_in_cart" value="" disabled>
			                        </td>
			                    </tr>
			                    <?php 
							}
						} else {
							?>
							<tr valign="top" class="enable_taxable_checked">
								<th class="titledesc" scope="row">
									<label for="fee_settings_taxable_type">
										<?php esc_html_e( 'Tax Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
										<span class="wcpfc-pro-label"></span>
										<?php echo wp_kses( wc_help_tip( esc_html__( 'Select the Tax Class.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
									</label>
								</th>
								<td class="forminp">
									<select name="fee_settings_taxable_type" id="fee_settings_taxable_type" disabled>
										<option value=""><?php esc_html_e( 'Standard', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
									</select>
								</td>
							</tr>
		                    <tr valign="top">
		                        <th class="titledesc" scope="row">
		                            <label for="fee_settings_select_optional">
		                            	<?php esc_html_e( 'Is Fee Optional ?', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                            	<span class="wcpfc-pro-label"></span>
		                            	<?php 
                                        /* translators: %1$s: break tag, %2$s break tag, %3$s strong tag start, %4$s strong tag end */
                                        echo sprintf( wp_kses( wc_help_tip( esc_html__( 'This will not apply fee by default. %1$s %2$s %3$sNote: %4$sOnce you select this optional fee to "Yes", It will always show the fee on checkout page as optional.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span'   => $allowed_tooltip_html,
			                                	'strong' => array(),
			                                	'br' 	 => array(),
			                                 ) ), '</br>', '</br>', '<strong>', '</strong>' ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp wcpfc-radio-section">
		                        	<label>
										<input name="fee_settings_select_optional" type="radio" class="fee_settings_select_optional" value="yes">
										<?php esc_html_e( 'Yes', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									</label>
									<label>
										<input name="fee_settings_select_optional" type="radio" class="fee_settings_select_optional" value="no" checked>
										<?php esc_html_e( 'No', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
									</label>
		                        </td>
		                    </tr>
		                    <tr valign="top" class="enable_optional_checked">
		                        <th class="titledesc" scope="row">
		                            <label for="fee_settings_optional_type">
		                                <?php esc_html_e( 'Optional Fee Selection Type', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Select the display type of the optional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                            <select name="fee_settings_optional_type" id="fee_settings_optional_type" disabled>
		                                <option value="checkbox"><?php esc_html_e( 'Checkbox', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
		                                <option value="dropdown"><?php esc_html_e( 'Dropdown', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
										<option value="radio-button"><?php esc_html_e( 'Radio Button', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
		                            </select>
		                        </td>
		                    </tr>
		                    <tr valign="top" class="enable_optional_checked">
		                        <th class="titledesc" scope="row">
		                            <label for="default_optional_checked">
		                                <?php esc_html_e( 'Default Optional Fee Checked', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the optional fee will be checked/active by default on the checkout page.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                            <input type="checkbox" name="default_optional_checked" id="default_optional_checked" class="chk_qty_price_class" value="" disabled>
		                        </td>
		                    </tr>
		                    <tr valign="top" class="enable_optional_checked">
		                        <th class="titledesc" scope="row">
		                            <label for="optional_fee_title">
		                                <?php esc_html_e( 'Optional Fee Section Title', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php 
                                        /* translators: %1$s: break tag, %2$s break tag, %3$s strong tag start, %4$s strong tag end */
                                        echo sprintf( wp_kses( wc_help_tip( esc_html__( 'Set the optional fee section title that will display before the payment section on the checkout page. %1$s %2$s %3$sNote: %4$sWe are removing this field from upcoming update. From now you can change it by use of  "wcpfc_optional_fee_text" filter hook.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span'   => $allowed_tooltip_html,
			                                	'strong' => array(),
			                                	'br' 	 => array(),
			                                 ) ), '</br>', '</br>', '<strong>', '</strong>' ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                        	<?php $optional_fees_title = isset($optional_fee_title) && !empty($optional_fee_title) ? $optional_fee_title : 'Optional fee(s)' ?>
		                            <input type="text" name="optional_fee_title" id="optional_fee_title" class="text-class" value="<?php echo esc_attr($optional_fees_title); ?>" readonly disabled />
		                        </td>
		                    </tr>
		                    <tr valign="top" class="enable_optional_checked">
		                        <th class="titledesc" scope="row">
		                            <label for="fee_settings_optional_description">
		                                <?php esc_html_e( 'Optional Fee Description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
		                                <span class="wcpfc-pro-label"></span>
		                                <?php echo wp_kses( wc_help_tip( esc_html__( 'Add a detailed note or description about the optional fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
		                            </label>
		                        </th>
		                        <td class="forminp">
		                            <textarea name="fee_settings_optional_description" id="fee_settings_optional_description" rows="3" cols="70" maxlength="150" placeholder="<?php esc_attr_e( 'Enter optional fee description', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" disabled></textarea>
		                        </td>
		                    </tr>
							<tr valign="top" class="enable_optional_checked">
			                        <th class="titledesc" scope="row">
			                            <label for="optional_fees_in_cart">
			                                <?php esc_html_e( 'Enable Optional Fees in Cart Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
			                                <span class="wcpfc-pro-label"></span>
			                                <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the optional fees will show case in cart page as well.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
			                            </label>
			                        </th>
			                        <td class="forminp">
			                            <input type="checkbox" name="optional_fees_in_cart" id="optional_fees_in_cart" class="optional_fees_in_cart" value="" disabled>
			                        </td>
			                    </tr>
		                    <?php 
						}
						?>
					</tbody>
				</table>
			</div>
            <div class="fees-rules">
                <div class="sub-title">
                    <h2 class="ap-title"><?php esc_html_e( 'Conditional Fee Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
                    <div class="tap">
                        <a id="fee-add-field" class="button" href="javascript:;">
                            <?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                        </a>
                    </div>
                    <div class="wocfc_match_type">
                        <p class="switch_in_pricing_rules_description_left">
                            <?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                        </p>
                        <select name="cost_rule_match[general_rule_match]" id="general_rule_match" class="arcmt_select">
                        <?php
                        if ( wcpffc_fs()->is__premium_only() ) {
                            if ( wcpffc_fs()->can_use_premium_code() ) {
                                ?>
                                <option value="all" <?php selected( $general_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                <option value="any" <?php selected( $general_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                <?php
                            } else {
                                ?>
                                <option value="all"><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                <option value="any_disabled"><?php esc_html_e( 'Any One 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                <?php
                            }
                        } else {
                            ?>
                                <option value="all"><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                <option value="any_disabled"><?php esc_html_e( 'Any One 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                            <?php
                        }
                        ?>
                        </select>
                        <p class="switch_in_pricing_rules_description">
                            <?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                        </p>
                    </div>
                </div>
                <div class="tap">
                    <table id="tbl-product-fee" class="tbl_product_fee table-outer tap-cas form-table product-fee-table">
                        <tbody>
                        <?php
                        $attribute_taxonomies_name = wc_get_attribute_taxonomy_names();
                        if ( isset( $productFeesArray ) && ! empty( $productFeesArray ) ) {
                            $i = 2;
                            foreach ( $productFeesArray as $key => $productfees ) {
                                $fees_conditions = isset( $productfees['product_fees_conditions_condition'] ) ? $productfees['product_fees_conditions_condition'] : '';
                                $condition_is    = isset( $productfees['product_fees_conditions_is'] ) ? $productfees['product_fees_conditions_is'] : '';
                                $condtion_value  = isset( $productfees['product_fees_conditions_values'] ) ? $productfees['product_fees_conditions_values'] : array();
                                ?>
                                <tr id="row_<?php echo esc_attr( $i ); ?>" valign="top">
                                    <td class="titledesc th_product_fees_conditions_condition" scope="row">
                                        <select rel-id="<?php echo esc_attr( $i ); ?>"
                                                id="product_fees_conditions_condition_<?php echo esc_attr( $i ); ?>"
                                                name="fees[product_fees_conditions_condition][]"
                                                id="product_fees_conditions_condition"
                                                class="product_fees_conditions_condition">
                                            <optgroup label="<?php esc_attr_e( 'Location Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                <option value="country" <?php echo ( 'country' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Country', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="city" <?php echo ( 'city' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'City', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                        ?>
                                                        <option value="state" <?php echo ( 'state' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'State', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="postcode" <?php echo ( 'postcode' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Postcode', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="zone" <?php echo ( 'zone' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Zone', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    } else {
                                                    	?>
                                                        <option value="state_disabled"><?php esc_html_e( 'State 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="postcode_disabled"><?php esc_html_e( 'Postcode 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="zone_disabled"><?php esc_html_e( 'Zone 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                	?>
                                                    <option value="state_disabled"><?php esc_html_e( 'State 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="postcode_disabled"><?php esc_html_e( 'Postcode 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="zone_disabled"><?php esc_html_e( 'Zone 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </optgroup>
                                            <optgroup label="<?php esc_attr_e( 'Product Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                <option value="product" <?php echo ( 'product' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="variableproduct" <?php echo ( 'variableproduct' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains variable product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    	?>
                                                    	<option value="brand" <?php echo ( 'brand' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains brand\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    	<option value="category" <?php echo ( 'category' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains category\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    	<?php
                                                    } else {
                                                    	?>
                                                    	<option value="<?php echo esc_attr( ( 'brand' === $fees_conditions ) ? 'brand' : 'brand_disabled' ); ?>" <?php echo ( 'brand' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains brand\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    	<option value="<?php echo esc_attr( ( 'category' === $fees_conditions ) ? 'category' : 'category_disabled' ); ?>" <?php echo ( 'category' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains category\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    	<?php
                                                    }
                                                } else {
                                                	?>
                                                	<option value="<?php echo esc_attr( ( 'brand' === $fees_conditions ) ? 'brand' : 'brand_disabled' ); ?>" <?php echo ( 'brand' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains brand\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<option value="<?php echo esc_attr( ( 'category' === $fees_conditions ) ? 'category' : 'category_disabled' ); ?>" <?php echo ( 'category' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains category\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<?php
                                                }
                                                ?>
                                                <option value="tag" <?php echo ( 'tag' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains tag\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="product_qty" <?php echo ( 'product_qty' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains product\'s quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    	// Custom Support #104847 - Location based fee
                                                    	$enable_wlf_location = apply_filters( 'wcpfc_enable_wlf_location_condition', false );
                                                    	if ( $enable_wlf_location ) {
                                                    		?>
	                                                    	<option value="wlf_location" <?php echo ( 'wlf_location' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart contains location\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                    	<?php	
                                                    	}
                                                    }
                                                }
                                                ?>
                                            </optgroup>
                                            <optgroup label="<?php esc_attr_e( 'Attribute Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                            	<?php 
                                            	if ( wcpffc_fs()->is__premium_only() && wcpffc_fs()->can_use_premium_code() ) {
                                                    $attribute_taxonomies = wc_get_attribute_taxonomies();
                                                    foreach ( $attribute_taxonomies as $attribute ) {
                                                        $att_label             = $attribute->attribute_label;
                                                        $att_name              = wc_attribute_taxonomy_name( $attribute->attribute_name );
                                                        ?>
                                                        <option value="<?php echo esc_attr( $att_name ); ?>" <?php echo ( $att_name === $fees_conditions ) ? 'selected' : '' ?>><?php echo esc_html( $att_label ); ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                	?>
                                                    <option value="attribute_list_disabled"><?php esc_html_e( 'Color 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </optgroup>
                                            <optgroup label="<?php esc_attr_e( 'User Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                <option value="user" <?php echo ( 'user' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'User', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                        ?>
                                                        <option value="user_role" <?php echo ( 'user_role' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'User Role', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    } else {
                                                    	?>
                                                        <option value="user_role_disabled"><?php esc_html_e( 'User Role 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                	?>
                                                    <option value="user_role_disabled"><?php esc_html_e( 'User Role 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </optgroup>
												<?php
												if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    ?>
                                                        <optgroup label="<?php esc_attr_e( 'Purchase History', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                            <option value="total_spent_order" <?php selected( $fees_conditions, 'total_spent_order' ); ?>><?php esc_html_e( 'Total order spent (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                            <option value="spent_order_count" <?php selected( $fees_conditions, 'spent_order_count' ); ?>><?php esc_html_e( 'Number of orders (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                            <option value="last_spent_order" <?php selected( $fees_conditions, 'last_spent_order' ); ?>><?php esc_html_e( 'Last order spent', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        </optgroup><?php
                                                    } else { ?>
														<optgroup label="<?php esc_attr_e( 'Purchase History', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
															<option value="total_spent_order_disabled"><?php esc_html_e( 'Total order spent (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
															<option value="spent_order_count_disabled"><?php esc_html_e( 'Number of orders (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
															<option value="last_spent_order_disabled"><?php esc_html_e( 'Last order spent', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
														</optgroup><?php
													}
                                                } else { ?>
													<optgroup label="<?php esc_attr_e( 'Purchase History', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
														<option value="total_spent_order_disabled"><?php esc_html_e( 'Total order spent (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
														<option value="spent_order_count_disabled"><?php esc_html_e( 'Number of orders (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
														<option value="last_spent_order_disabled"><?php esc_html_e( 'Last order spent', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
													</optgroup><?php
												}?>
                                            <optgroup label="<?php esc_attr_e( 'Cart Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                <?php
                                                $currency_symbol = get_woocommerce_currency_symbol();
                                                $currency_symbol = ! empty( $currency_symbol ) ? '(' . $currency_symbol . ')' : '';
                                                ?>
                                                <option value="cart_total" <?php echo ( 'cart_total' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart Subtotal (Before Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
                                                <?php
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                        ?>
                                                        <option value="cart_totalafter" <?php echo ( 'cart_totalafter' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart Subtotal (After Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
                                                        <option value="cart_specificproduct" <?php echo ( 'cart_specificproduct' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart Subtotal (Specific products) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
														<option value="cart_totalexclude_tax" <?php echo ( 'cart_totalexclude_tax' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Cart Subtotal (Ex. Taxes) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
														<option value="cart_rowtotal" <?php echo ( 'cart_rowtotal' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Row Total in Cart ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    } else {
                                                    	?>
                                                        <option value="cart_totalafter_disabled"><?php esc_html_e( 'Cart Subtotal (After Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                        <option value="cart_specificproduct_disabled"><?php esc_html_e( 'Cart Subtotal (Specific products) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
														<option value="cart_totalexclude_tax_disabled"><?php esc_html_e( 'Cart Subtotal (Ex. Taxes) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
														<option value="cart_rowtotal_disabled"><?php esc_html_e( 'Row Total in Cart ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                	?>
                                                    <option value="cart_totalafter_disabled"><?php esc_html_e( 'Cart Subtotal (After Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    <option value="cart_specificproduct_disabled"><?php esc_html_e( 'Cart Subtotal (Specific products) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
													<option value="cart_totalexclude_tax_disabled"><?php esc_html_e( 'Cart Subtotal (Ex. Taxes) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
													<option value="cart_rowtotal_disabled"><?php esc_html_e( 'Row Total in Cart ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    <?php
                                                }
                                                ?>
                                                <option value="quantity" <?php echo ( 'quantity' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                        ?>
                                                        <option value="weight" <?php echo ( 'weight' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $weight_unit ); ?></option>
                                                        <option value="coupon" <?php echo ( 'coupon' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Coupon', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="shipping_class" <?php echo ( 'shipping_class' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Shipping Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    } else {
                                                    	?>
                                                        <option value="weight_disabled"><?php esc_html_e( 'Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $weight_unit ); ?><?php echo esc_html( ' 🔒' );?></option>
                                                        <option value="coupon_disabled"><?php esc_html_e( 'Coupon 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="shipping_class_disabled"><?php esc_html_e( 'Shipping Class 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <?php
                                                    }
                                                } else {
                                                	?>
                                                    <option value="weight_disabled"><?php esc_html_e( 'Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $weight_unit ); ?><?php echo esc_html( ' 🔒' );?></option>
                                                    <option value="coupon_disabled"><?php esc_html_e( 'Coupon 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="shipping_class_disabled"><?php esc_html_e( 'Shipping Class 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </optgroup>
                                            <optgroup label="<?php esc_attr_e( 'Payment Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
	                                        	<?php
	                                            if ( wcpffc_fs()->is__premium_only() ) {
	                                                if ( wcpffc_fs()->can_use_premium_code() ) {
	                                                	?>
	                                                	<option value="payment" <?php echo ( 'payment' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Payment Gateway', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                	<?php
	                                                } else {
	                                                	?>
	                                                	<option value="payment_disabled"><?php esc_html_e( 'Payment Gateway 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                	<?php
	                                                }
	                                            } else {
                                                	?>
                                                	<option value="payment_disabled"><?php esc_html_e( 'Payment Gateway 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<?php
                                                }
	                                            ?>
                                        	</optgroup>
                                        	<optgroup label="<?php esc_attr_e( 'Shipping Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                        		<?php
	                                            if ( wcpffc_fs()->is__premium_only() ) {
	                                                if ( wcpffc_fs()->can_use_premium_code() ) {
	                                                    ?>
	                                                    <option value="shipping_method" <?php echo ( 'shipping_method' === $fees_conditions ) ? 'selected' : '' ?>><?php esc_html_e( 'Shipping Method', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                    <?php
	                                                } else {
	                                                	?>
	                                                    <option value="shipping_method_disabled"><?php esc_html_e( 'Shipping Method 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                    <?php
	                                                }
	                                            } else {
                                                	?>
                                                    <option value="shipping_method_disabled"><?php esc_html_e( 'Shipping Method 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
	                                            ?>
                                    		</optgroup>
                                        </select>
                                    </td>
                                    <td class="select_condition_for_in_notin">
                                        <?php if ( 'cart_total' === $fees_conditions || 'cart_totalafter' === $fees_conditions || 'cart_specificproduct' === $fees_conditions || 'cart_totalexclude_tax' === $fees_conditions || 'cart_rowtotal' === $fees_conditions || 'quantity' === $fees_conditions || 'weight' === $fees_conditions || 'product_qty' === $fees_conditions || 'total_spent_order' === $fees_conditions || 'spent_order_count' === $fees_conditions || 'last_spent_order' === $fees_conditions ) { ?>
                                            <select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is_<?php echo esc_attr( $i ); ?>">
                                                <option value="is_equal_to" <?php echo ( 'is_equal_to' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="less_equal_to" <?php echo ( 'less_equal_to' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Less or Equal to ( <= )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="less_then" <?php echo ( 'less_then' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Less than ( < )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="greater_equal_to" <?php echo ( 'greater_equal_to' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Greater or Equal to ( >= )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="greater_then" <?php echo ( 'greater_then' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Greater than ( > )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="not_in" <?php echo ( 'not_in' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            </select>
                                        <?php } else { ?>
                                            <select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is_<?php echo esc_attr( $i ); ?>">
                                                <option value="is_equal_to" <?php echo ( 'is_equal_to' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="not_in" <?php echo ( 'not_in' === $condition_is ) ? 'selected' : '' ?>><?php esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' ); ?> </option>
                                            </select>
                                        <?php } ?>
                                    </td>
                                    <td class="condition-value" id="column_<?php echo esc_attr( $i ); ?>" colspan="2">
                                        <?php
                                        $html = '';
                                        if ( wcpffc_fs()->is__premium_only() ) {
                                            if ( wcpffc_fs()->can_use_premium_code() ) {
                                                if ( 'country' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_country_list( $i, $condtion_value );
                                                } elseif ( 'state' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_states_list__premium_only( $i, $condtion_value );
                                                } elseif ( 'city' === $fees_conditions ) {
                                                    $html .= '<textarea name = "fees[product_fees_conditions_values][value_' . $i . ']">' . $condtion_value . '</textarea>';
                                                    $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b> Please enter each city name in a new line.', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                                'p' => array(),
                                                                'b' => array( 'style' => array() ),
                                                            ) ) );
                                                } elseif ( 'postcode' === $fees_conditions ) {
                                                    $html .= '<textarea name = "fees[product_fees_conditions_values][value_' . $i . ']">' . $condtion_value . '</textarea>';
                                                    $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b> Please enter each postcode/zip code in a new line.', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                                'p' => array(),
                                                                'b' => array( 'style' => array() ),
                                                            ) ) );
                                                } elseif ( 'zone' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_zones_list__premium_only( $i, $condtion_value );
                                                } elseif ( 'product' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_product_list( $i, $condtion_value, 'edit' );
                                                    /* translators: %s: Docs link */
                                                    $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Please make sure that when you add rules in Advanced Fees Price Rules > Cost on Product Section It contains in above selected product list, otherwise it may not apply proper fees. For more detail please view our documentation. <a href="%s" target="_blank">Click Here</a></p>', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                                'p' => array(),
                                                                'b' => array( 'style' => array() ),
                                                                'a' => array( 'href' => array(), 'target' => array() ),
                                                            ) )
                                                        , esc_url( 'https://docs.thedotstore.com/article/198-how-to-add-rules-based-on-simple-product-and-variable-products' ) );
                                                } elseif ( 'variableproduct' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_varible_product_list( $i, $condtion_value, 'edit' );
                                                    /* translators: %s: Docs link */
                                                    $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Please make sure that when you add rules in Advanced Fees Price Rules > Cost on Product Section It contains in above selected product list, otherwise it may not apply proper fees. For more detail please view our documentation. <a href="%s" target="_blank">Click Here</a></p>', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                                'p' => array(),
                                                                'b' => array( 'style' => array() ),
                                                                'a' => array( 'href' => array(), 'target' => array() ),
                                                            ) )
                                                        , esc_url( 'https://docs.thedotstore.com/article/198-how-to-add-rules-based-on-simple-product-and-variable-products' ) );
                                                } elseif ( 'product_qty' === $fees_conditions ) {
                                                    $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
                                                    $html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a></p>',
                                                        esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                        esc_html__( 'This rule will only work if you have selected any one Product Specific option. ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                        esc_url( 'https://docs.thedotstore.com/article/726-product-specific-fee-rules' ),
                                                        esc_html__( 'Click Here', 'woocommerce-conditional-product-fees-for-checkout' )
                                                    ) );
                                                } elseif ( 'brand' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_brand_list__premium_only( $i, $condtion_value );
                                                } elseif ( 'wlf_location' === $fees_conditions ) {
                                                	// Custom Support #104847 - Location based fee
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_wlf_location_list__premium_only( $i, $condtion_value );
                                                } elseif ( 'category' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_category_list__premium_only( $i, $condtion_value );
                                                    /* translators: %s: Docs link */
                                                    $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Please make sure that when you add rules in Advanced Fees Price Rules > Cost on Category Section It contains in above selected category list, otherwise it may not apply proper fees. For more detail please view our documentation. <a href="%s" target="_blank">Click Here</a></p>', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                                'p' => array(),
                                                                'b' => array( 'style' => array() ),
                                                                'a' => array( 'href' => array(), 'target' => array() ),
                                                            ) )
                                                        , esc_url( 'https://docs.thedotstore.com/article/199-how-to-add-category-based-fees' ) );
                                                } elseif ( 'tag' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_tag_list( $i, $condtion_value );
                                                } elseif ( in_array( $fees_conditions, $attribute_taxonomies_name, true ) ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_att_term_list__premium_only( $i, $fees_conditions, $condtion_value );
                                                } elseif ( 'user' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_user_list( $i, $condtion_value, 'edit' );
                                                } elseif ( 'user_role' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_user_role_list__premium_only( $i, $condtion_value );
                                                } elseif ( 'last_spent_order' === $fees_conditions ) {
													$html .= '<input type = "text"  name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
												} elseif ( 'total_spent_order' === $fees_conditions ) {
													$html .= '<input type = "text"  name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
												} elseif ( 'spent_order_count' === $fees_conditions ) {
													$html .= '<input type = "text"  name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
												} elseif ( 'cart_total' === $fees_conditions ) {
                                                    $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . $condtion_value . '">';
                                                } elseif ( 'cart_totalafter' === $fees_conditions ) {
                                                    $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $i . ']" id="product_fees_conditions_values" class="product_fees_conditions_values price-class" value="' . $condtion_value . '">';
                                                    $html .= wp_kses_post(
                                                        sprintf(
                                                            '<p><strong style="color: red;">%s</strong> %s <a href="%s" target="_blank">%s</a></p>',
                                                            esc_html__('Note:', 'woocommerce-conditional-product-fees-for-checkout'),
                                                            esc_html__('This rule will apply when a coupon is applied on the front end.', 'woocommerce-conditional-product-fees-for-checkout'),
                                                            esc_url('https://docs.thedotstore.com/article/209-how-to-add-fee-based-on-after-discount-rule'),
                                                            esc_html__('Click Here', 'woocommerce-conditional-product-fees-for-checkout')
                                                        )
                                                    );
                                                } elseif ( 'cart_specificproduct' === $fees_conditions ) {
                                                    $html .= '<input type="text" name="fees[product_fees_conditions_values][value_' . $i . ']" id="product_fees_conditions_values" class="product_fees_conditions_values price-class" value="' . $condtion_value . '">';
                                                    $html .= wp_kses_post( sprintf( '<p><b style="color: red;">%s</b>%s<a href="%s" target="_blank">%s</a>
                                                                </p>',
                                                        esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                        esc_html__( 'This rule will apply when you would add cart contain product. ', 'woocommerce-conditional-product-fees-for-checkout' ),
                                                        esc_url( 'https://docs.thedotstore.com/article/438-how-to-add-extra-fees-based-on-specific-product-subtotal-range' ),
                                                        esc_html__( 'Click Here', 'woocommerce-conditional-product-fees-for-checkout' )
                                                    ) );
                                                } elseif ( 'cart_totalexclude_tax' === $fees_conditions ) {
                                                    $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
                                                } elseif ( 'cart_rowtotal' === $fees_conditions ) {
                                                    $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
                                                } elseif ( 'quantity' === $fees_conditions ) {
                                                    $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
                                                } elseif ( 'weight' === $fees_conditions ) {
                                                    $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values weight-class" value = "' . $condtion_value . '">';
                                                    /* translators: %s: Docs link */
                                                    $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b>Please make sure that when you add rules in Advanced Fees Price Rules > Cost on Weight Section It contains in above entered weight, otherwise it may not apply proper fees. For more detail please view our documentation. <a href="%s" target="_blank">Click Here</a></p>', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                                'p' => array(),
                                                                'b' => array( 'style' => array() ),
                                                                'a' => array( 'href' => array(), 'target' => array() ),
                                                            ) )
                                                        , esc_url( 'https://docs.thedotstore.com/article/206-how-to-add-weight-based-fee-rules' ) );
                                                } elseif ( 'coupon' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_coupon_list__premium_only( $i, $condtion_value );
                                                } elseif ( 'shipping_class' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_advance_flat_rate_class__premium_only( $i, $condtion_value );
                                                } elseif ( 'payment' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_payment_methods__premium_only( $i, $condtion_value );
                                                } elseif ( 'shipping_method' === $fees_conditions ) {
                                                    $html .= $wcpfc_admin_object->wcpfc_pro_get_active_shipping_methods__premium_only( $i, $condtion_value );
                                                }
                                            }  else {
	                                            if ( 'country' === $fees_conditions ) {
	                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_country_list( $i, $condtion_value );
	                                            } elseif ( 'city' === $fees_conditions ) {
	                                                $html .= '<textarea name = "fees[product_fees_conditions_values][value_' . $i . ']">' . $condtion_value . '</textarea>';
	                                                $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b> Please enter each city name in a new line.', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                            'p' => array(),
                                                            'b' => array( 'style' => array() ),
                                                        ) ) );
	                                            } elseif ( 'product' === $fees_conditions ) {
	                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_product_list( $i, $condtion_value, 'edit' );
	                                            } elseif ( 'variableproduct' === $fees_conditions ) {
	                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_varible_product_list( $i, $condtion_value, 'edit' );
	                                            } elseif ( 'tag' === $fees_conditions ) {
	                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_tag_list( $i, $condtion_value );
	                                            } elseif ( 'product_qty' === $fees_conditions ) {
	                                                $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
	                                            } elseif ( 'user' === $fees_conditions ) {
	                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_user_list( $i, $condtion_value, 'edit' );
	                                            } elseif ( 'cart_total' === $fees_conditions ) {
	                                                $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . $condtion_value . '">';
	                                            } elseif ( 'quantity' === $fees_conditions ) {
	                                                $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
	                                            }
	                                        }
                                        } else {
                                            if ( 'country' === $fees_conditions ) {
                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_country_list( $i, $condtion_value );
                                            } elseif ( 'city' === $fees_conditions ) {
                                                $html .= '<textarea name = "fees[product_fees_conditions_values][value_' . $i . ']">' . $condtion_value . '</textarea>';
                                                $html .= sprintf( wp_kses( __( '<p><b style="color: red;">Note: </b> Please enter each city name in a new line.', 'woocommerce-conditional-product-fees-for-checkout' ), array(
                                                        'p' => array(),
                                                        'b' => array( 'style' => array() ),
                                                    ) ) );
                                            } elseif ( 'product' === $fees_conditions ) {
                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_product_list( $i, $condtion_value, 'edit' );
                                            } elseif ( 'variableproduct' === $fees_conditions ) {
                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_varible_product_list( $i, $condtion_value, 'edit' );
                                            } elseif ( 'tag' === $fees_conditions ) {
                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_tag_list( $i, $condtion_value );
                                            } elseif ( 'product_qty' === $fees_conditions ) {
                                                $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
                                            } elseif ( 'user' === $fees_conditions ) {
                                                $html .= $wcpfc_admin_object->wcpfc_pro_get_user_list( $i, $condtion_value, 'edit' );
                                            } elseif ( 'cart_total' === $fees_conditions ) {
                                                $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values price-class" value = "' . $condtion_value . '">';
                                            } elseif ( 'quantity' === $fees_conditions ) {
                                                $html .= '<input type = "text" name = "fees[product_fees_conditions_values][value_' . $i . ']" id = "product_fees_conditions_values" class = "product_fees_conditions_values qty-class" value = "' . $condtion_value . '">';
                                            }
                                        }
                                        echo wp_kses( $html, Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
                                        ?>
                                        <input type="hidden" name="condition_key[<?php echo 'value_' . esc_attr( $i ); ?>]" value="">
                                    </td>
                                    <td>
                                        <a id="fee-delete-field" rel-id="<?php echo esc_attr( $i ); ?>" class="delete-row" href="javascript:;" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $i ++;
                            }
                        } else {
                            $i = 1;
                            ?>
                            <tr id="row_1" valign="top">
                                <td class="titledesc th_product_fees_conditions_condition" scope="row">
                                    <select rel-id="1" id="product_fees_conditions_condition_1"
                                            name="fees[product_fees_conditions_condition][]"
                                            id="product_fees_conditions_condition"
                                            class="product_fees_conditions_condition">
                                        <optgroup label="<?php esc_attr_e( 'Location Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                            <option value="country"><?php esc_html_e( 'Country', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <option value="city"><?php esc_html_e( 'City', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <?php
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    ?>
                                                    <option value="state"><?php esc_html_e( 'State', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="postcode"><?php esc_html_e( 'Postcode', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="zone"><?php esc_html_e( 'Zone', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                } else {
                                                	?>
                                                	<option value="state_disabled"><?php esc_html_e( 'State 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="postcode_disabled"><?php esc_html_e( 'Postcode 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="zone_disabled"><?php esc_html_e( 'Zone 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<?php
                                                }
                                            } else {
                                            	?>
                                            	<option value="state_disabled"><?php esc_html_e( 'State 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="postcode_disabled"><?php esc_html_e( 'Postcode 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="zone_disabled"><?php esc_html_e( 'Zone 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            	<?php
                                            }
                                            ?>
                                        </optgroup>
                                        <optgroup label="<?php esc_attr_e( 'Product Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                            <option value="product"><?php esc_html_e( 'Cart contains product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <option value="variableproduct"><?php esc_html_e( 'Cart contains variable product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <?php
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                	?>
                                                	<option value="brand"><?php esc_html_e( 'Cart contains brand\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<option value="category"><?php esc_html_e( 'Cart contains category\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<?php
                                                } else {
                                                	?>
                                                	<option value="brand_disabled"><?php esc_html_e( 'Cart contains brand\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<option value="category_disabled"><?php esc_html_e( 'Cart contains category\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<?php
                                                }
                                            } else {
                                            	?>
                                            	<option value="brand_disabled"><?php esc_html_e( 'Cart contains brand\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            	<option value="category_disabled"><?php esc_html_e( 'Cart contains category\'s product 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            	<?php
                                            }
                                            ?>
                                            <option value="tag"><?php esc_html_e( 'Cart contains tag\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <option value="product_qty"><?php esc_html_e( 'Cart contains product\'s quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <?php
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                	// Custom Support #104847 - Location based fee
                                                	$enable_wlf_location = apply_filters( 'wcpfc_enable_wlf_location_condition', false );
                                                	if ( $enable_wlf_location ) {
                                                		?>
	                                                	<option value="wlf_location"><?php esc_html_e( 'Cart contains location\'s product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                	<?php	
                                                	}
                                                }
                                            }
                                        	?>
                                        </optgroup>
                                        <optgroup label="<?php esc_attr_e( 'Attribute Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                        	<?php 
                                        	if ( wcpffc_fs()->is__premium_only() && wcpffc_fs()->can_use_premium_code() ) {
                                                $attribute_taxonomies = wc_get_attribute_taxonomies();
                                                foreach ( $attribute_taxonomies as $attribute ) {
                                                    $att_label             = $attribute->attribute_label;
                                                    $att_name              = wc_attribute_taxonomy_name( $attribute->attribute_name );
                                                    ?>
                                                    <option value="<?php echo esc_attr( $att_name ); ?>"><?php echo esc_html( $att_label ); ?></option>
                                                    <?php
                                                }
                                            } else {
                                            	?>
                                                <option value="attribute_list_disabled"><?php esc_html_e( 'Color 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <optgroup label="<?php esc_attr_e( 'User Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                            <option value="user"><?php esc_html_e( 'User', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <?php
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    ?>
                                                    <option value="user_role"><?php esc_html_e( 'User Role', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                } else {
                                                	?>
                                                    <option value="user_role_disabled"><?php esc_html_e( 'User Role 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
                                            } else {
                                            	?>
                                                <option value="user_role_disabled"><?php esc_html_e( 'User Role 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
											<?php
											if ( wcpffc_fs()->is__premium_only() ) {
												if ( wcpffc_fs()->can_use_premium_code() ) {
												?>
                                                    <optgroup label="<?php esc_attr_e( 'Purchase History', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                        <option value="total_spent_order"><?php esc_html_e( 'Total order spent (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="spent_order_count"><?php esc_html_e( 'Number of orders (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                        <option value="last_spent_order"><?php esc_html_e( 'Last order spent', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    </optgroup><?php
												} else { ?>
                                                    <optgroup label="<?php esc_attr_e( 'Purchase History', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                        <option value="total_spent_order_disabled"><?php esc_html_e( 'Total order spent (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                        <option value="spent_order_count_disabled"><?php esc_html_e( 'Number of orders (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                        <option value="last_spent_order_disabled"><?php esc_html_e( 'Last order spent', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    </optgroup><?php
                                                }
                                            } else { ?>
                                                 <optgroup label="<?php esc_attr_e( 'Purchase History', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                                    <option value="total_spent_order_disabled"><?php esc_html_e( 'Total order spent (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    <option value="spent_order_count_disabled"><?php esc_html_e( 'Number of orders (all time)', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    <option value="last_spent_order_disabled"><?php esc_html_e( 'Last order spent', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                </optgroup><?php
                                            } ?>
                                        <optgroup label="<?php esc_attr_e( 'Cart Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                            <?php
                                            $currency_symbol = get_woocommerce_currency_symbol();
                                            $currency_symbol = ! empty( $currency_symbol ) ? '(' . $currency_symbol . ')' : '';
                                            ?>
                                            <option value="cart_total"><?php esc_html_e( 'Cart Subtotal (Before Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
                                            <?php
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    ?>
                                                    <option value="cart_totalafter"><?php esc_html_e( 'Cart Subtotal (After Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
                                                    <option value="cart_specificproduct"><?php esc_html_e( 'Cart Subtotal (Specific products) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
													<option value="cart_totalexclude_tax"><?php esc_html_e( 'Cart Subtotal (Ex. Taxes) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?></option>
													<option value="cart_rowtotal"><?php esc_html_e( 'Row Total in Cart ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                } else {
                                                	?>
                                                    <option value="cart_totalafter_disabled"><?php esc_html_e( 'Cart Subtotal (After Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    <option value="cart_specificproduct_disabled"><?php esc_html_e( 'Cart Subtotal (Specific products) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
													<option value="cart_totalexclude_tax_disabled"><?php esc_html_e( 'Cart Subtotal (Ex. Taxes) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
													<option value="cart_rowtotal_disabled"><?php esc_html_e( 'Row Total in Cart ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                    <?php
                                                }
                                            } else {
                                            	?>
                                                <option value="cart_totalafter_disabled"><?php esc_html_e( 'Cart Subtotal (After Discount) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                <option value="cart_specificproduct_disabled"><?php esc_html_e( 'Cart Subtotal (Specific products) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
												<option value="cart_totalexclude_tax_disabled"><?php esc_html_e( 'Cart Subtotal (Ex. Taxes) ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $currency_symbol ); ?><?php echo esc_html( ' 🔒' ); ?></option>
												<option value="cart_rowtotal_disabled"><?php esc_html_e( 'Row Total in Cart ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( ' 🔒' ); ?></option>
                                                <?php
                                            }
                                            ?>
                                            <option value="quantity"><?php esc_html_e( 'Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            <?php
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    ?>
                                                    <option value="weight"><?php esc_html_e( 'Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $weight_unit ); ?></option>
                                                    <option value="coupon"><?php esc_html_e( 'Coupon', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="shipping_class"><?php esc_html_e( 'Shipping Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                } else {
                                                	?>
                                                    <option value="weight_disabled"><?php esc_html_e( 'Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $weight_unit ); ?><?php echo esc_html( ' 🔒' );?></option>
                                                    <option value="coupon_disabled"><?php esc_html_e( 'Coupon 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <option value="shipping_class_disabled"><?php esc_html_e( 'Shipping Class 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
                                            } else {
                                            	?>
                                                <option value="weight_disabled"><?php esc_html_e( 'Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?><?php echo esc_html( $weight_unit ); ?><?php echo esc_html( ' 🔒' );?></option>
                                                <option value="coupon_disabled"><?php esc_html_e( 'Coupon 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <option value="shipping_class_disabled"><?php esc_html_e( 'Shipping Class 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <optgroup label="<?php esc_attr_e( 'Payment Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
                                        	<?php
	                                        if ( wcpffc_fs()->is__premium_only() ) {
	                                            if ( wcpffc_fs()->can_use_premium_code() ) {
	                                            	?>
	                                            	<option value="payment"><?php esc_html_e( 'Payment Gateway', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                            	<?php
	                                            } else {
                                                	?>
                                                	<option value="payment_disabled"><?php esc_html_e( 'Payment Gateway 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                	<?php
                                                }
	                                        } else {
                                            	?>
                                            	<option value="payment_disabled"><?php esc_html_e( 'Payment Gateway 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                            	<?php
                                            }
	                                        ?>
                                    	</optgroup>
                                    	<optgroup label="<?php esc_attr_e( 'Shipping Specific', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
	                                        <?php
	                                        if ( wcpffc_fs()->is__premium_only() ) {
	                                            if ( wcpffc_fs()->can_use_premium_code() ) {
	                                                ?>
	                                                <option value="shipping_method"><?php esc_html_e( 'Shipping Method', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
	                                                <?php
	                                            } else {
                                                	?>
                                                    <option value="shipping_method_disabled"><?php esc_html_e( 'Shipping Method 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                    <?php
                                                }
	                                        } else {
                                            	?>
                                                <option value="shipping_method_disabled"><?php esc_html_e( 'Shipping Method 🔒', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                                <?php
                                            }
	                                        ?>
                                        </optgroup>
                                    </select>
                                </td>
                                <td class="select_condition_for_in_notin">
                                    <select name="fees[product_fees_conditions_is][]" class="product_fees_conditions_is product_fees_conditions_is_1">
                                        <option value="is_equal_to"><?php esc_html_e( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                        <option value="not_in"><?php esc_html_e( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
                                    </select>
                                </td>
                                <td id="column_1" class="condition-value" colspan="2">
                                    <?php echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_country_list( 1 ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ); ?>
                                    <input type="hidden" name="condition_key[value_1][]" value="">
                                </td>
                                <td>
	                                <a id="fee-delete-field" rel-id="<?php echo esc_attr( $i ); ?>" class="delete-row" href="javascript:;" title="Delete"><i class="fa fa-trash"></i></a>
	                            </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="total_row" id="total_row" value="<?php echo esc_attr( $i ); ?>">
                </div>
            </div>
			
			<?php
			if ( wcpffc_fs()->is__premium_only() ) {
				if ( wcpffc_fs()->can_use_premium_code() ) {
					?>
					<?php // Advanced Pricing Section start  ?>
					<div id="apm_wrap" class="fees-pricing-rules">
						<div class="ap_title">
							<h2><?php esc_html_e( 'Advanced Fees Price Rules', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
							<label class="switch">
								<input type="checkbox" name="ap_rule_status"
								       value="on" <?php echo esc_attr( $ap_rule_status ); ?>>
								<div class="slider round"></div>
							</label>
							<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						</div>

						<div class="fees_pricing_rules">
							<div class="fees_pricing_rules_tab">
								<ul class="tabs">
									<?php
									$tab_array = array(
										'tab-1'  => esc_html__( 'Cost on Product', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-2'  => esc_html__( 'Cost on Product Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-3'  => esc_html__( 'Cost on Product Weight', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-4'  => esc_html__( 'Cost on Category', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-5'  => esc_html__( 'Cost on Category Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-6'  => esc_html__( 'Cost on Category Weight', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-7'  => esc_html__( 'Cost on Total Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-8'  => esc_html__( 'Cost on Total Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-9'  => esc_html__( 'Cost on Total Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-10' => esc_html__( 'Cost on Shipping Class Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
									);
									if ( ! empty( $tab_array ) ) {
										foreach ( $tab_array as $data_tab => $tab_title ) {
											if ( 'tab-1' === $data_tab ) {
												$class = ' current';
											} else {
												$class = '';
											}
											?>
											<li class="tab-link<?php echo esc_attr( $class ); ?>"
											    data-tab="<?php echo esc_attr( $data_tab ); ?>">
												<?php echo esc_html( $tab_title ); ?>
											</li>
											<?php
										}
									}
									?>
								</ul>
							</div>
							<div class="fees_pricing_rules_tab_content">
								<?php // Advanced Pricing Product Section start here   ?>
								<div class="ap_product_container advance_pricing_rule_box tab-content current"
								     id="tab-1"
								     data-title="<?php esc_attr_e( 'Cost on Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_product_list"></div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-product-add-field" class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_product_status" value="on" <?php echo esc_attr( $cost_on_product_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_product_rule_match]"
												        id="cost_on_product_rule_match" class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_product_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_product_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
											<div class="switch_status_div">
												<label class="switch switch_in_pricing_rules">
													<input type="checkbox" name="display_product_ap_rule_in_single_product" value="on" <?php echo esc_attr( $display_product_ap_rule_in_single_product ); ?>>
													<div class="slider round"></div>
												</label>
												<?php echo wp_kses( wc_help_tip( esc_html__( 'Turn off this button, if you want to hide the Advanced Pricing Rules of "Cost on Product" in Single Product Page', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												<h2 class="ap-title"><?php esc_html_e( 'Display Product AP Rule in Product Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											</div>
										</div>
										<table id="tbl_ap_product_method"
										       class="tbl_product_fee table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                    <label><?php esc_html_e( 'Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the fee amount when the min/max quantity match.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                    <label><?php esc_html_e( 'Min Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product quantity per row before the fee amount is applied.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                                </th>
												<th class="titledesc th_product_fees_conditions_condition" scope="row">
                                                    <label><?php esc_html_e( 'Max Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product quantity per row before the fee amount is applied.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a rel-id="2" title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_arr = array();
											if ( ! empty( $sm_metabox_ap_product ) && is_array( $sm_metabox_ap_product ) ):
												foreach ( $sm_metabox_ap_product as $app_arr ) {
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $app_arr ) || '' !== $app_arr ) {
														if ( ( '' !== $app_arr['ap_fees_products'] && '' !== $app_arr['ap_fees_ap_price_product'] ) && ( '' !== $app_arr['ap_fees_ap_prd_min_qty'] || '' !== $app_arr['ap_fees_ap_prd_max_qty'] ) ) {
															//if condition match than fill in array
															$filled_arr[] = $app_arr;
														}
													}
												}
											endif;
											//check APR exist
											if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
												$cnt_product = 2;
												foreach ( $filled_arr as $key => $productfees ) {
													$fees_ap_fees_products    = isset( $productfees['ap_fees_products'] ) ? $productfees['ap_fees_products'] : '';
													$ap_fees_ap_min_qty       = isset( $productfees['ap_fees_ap_prd_min_qty'] ) ? $productfees['ap_fees_ap_prd_min_qty'] : '';
													$ap_fees_ap_max_qty       = isset( $productfees['ap_fees_ap_prd_max_qty'] ) ? $productfees['ap_fees_ap_prd_max_qty'] : '';
													$ap_fees_ap_price_product = isset( $productfees['ap_fees_ap_price_product'] ) ? $productfees['ap_fees_ap_price_product'] : '';
													$ap_fees_ap_per_product   = isset( $productfees['ap_fees_ap_per_product'] ) && strpos($ap_fees_ap_price_product, '%') ? $productfees['ap_fees_ap_per_product'] : 'no';
													?>
													<tr id="ap_product_row_<?php echo esc_attr( $cnt_product ); ?>"
													    valign="top" class="ap_product_row_tr">
														<td scope="row">
															<select rel-id="<?php echo esc_attr( $cnt_product ); ?>"
															        id="ap_product_fees_conditions_condition_<?php echo esc_attr( $cnt_product ); ?>"
															        name="fees[ap_product_fees_conditions_condition][<?php echo esc_attr( $cnt_product ); ?>][]"
															        id="ap_product_fees_conditions_condition"
															        class="wcpfc_select ap_product product_fees_conditions_values multiselect2"
															        multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_product_options( $cnt_product, $fees_ap_fees_products ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
															<input type="number" name="fees[ap_fees_ap_prd_min_qty][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_prd_min_qty[]"
															       placeholder="<?php esc_attr_e( 'Min Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_min_qty ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
															<input type="number" name="fees[ap_fees_ap_prd_max_qty][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_prd_max_qty[]"
															       placeholder="<?php esc_attr_e( 'Max Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_max_qty ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value" colspan="2">
															<input type="text" name="fees[ap_fees_ap_price_product][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_product[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_product ); ?>">
															<label class="ap-label-checkbox">
																<input type="hidden" name="fees[ap_fees_ap_per_product][]"
															       id="ap_fees_ap_per_product[]"
															       value="<?php echo esc_attr( 'no' ); ?>">
																<input type="checkbox" name="fees[ap_fees_ap_per_product][]"
															       id="ap_fees_ap_per_product[]"
																   <?php checked( $ap_fees_ap_per_product, 'yes' ); ?>
															       value="<?php echo esc_attr( 'yes' ); ?>">
																<span><?php esc_html_e('Apply on Products', 'woocommerce-conditional-product-fees-for-checkout'); ?></span>
															</label>
															<?php
															$first_char = substr( $ap_fees_ap_price_product, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product ); ?> condition-value">
															<a id="ap_product_delete_field"
															   rel-id="<?php echo esc_attr( $cnt_product ); ?>"
															   title="Delete" class="delete-row" href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_product ++;
												}
												?>
												<?php
											} else {
												$cnt_product = 1;
											}
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_product" id="total_row_product" value="<?php echo esc_attr( $cnt_product ); ?>">
									</div>
								</div>
								<?php // Advanced Pricing Product Section end here ?>

								<!-- Advanced Pricing Product Subtotal start here -->
								<div class="ap_product_subtotal_container advance_pricing_rule_box tab-content"
								     id="tab-2"
								     data-title="<?php esc_attr_e( 'Cost on Product Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_cart_subtotal">
												<option
													value="product_subtotal"><?php esc_html_e( 'Product Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Product Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-product-subtotal-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox"
														       name="cost_on_product_subtotal_status"
														       value="on" <?php echo esc_attr( $cost_on_product_subtotal_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_product_subtotal_rule_match]"
												        id="cost_on_product_subtotal_rule_match"
												        class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_product_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_product_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_product_subtotal_method"
										       class="tbl_product_subtotal table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
													<label><?php esc_html_e( 'Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
													<?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the fee amount when the min/max subtotal match.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
													<label><?php esc_html_e( 'Min Subtotal ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row">
													<label><?php esc_html_e( 'Max Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_product_subtotal = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_product_subtotal ) && is_array( $sm_metabox_ap_product_subtotal ) ):
												foreach ( $sm_metabox_ap_product_subtotal as $apcat_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || $apcat_arr !== '' ) {
														if (
															( $apcat_arr['ap_fees_product_subtotal'] !== '' && $apcat_arr['ap_fees_ap_price_product_subtotal'] !== '' ) &&
															( $apcat_arr['ap_fees_ap_product_subtotal_min_subtotal'] !== '' || $apcat_arr['ap_fees_ap_product_subtotal_max_subtotal'] !== '' )
														) {
															$filled_product_subtotal[] = $apcat_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_product_subtotal ) && ! empty( $filled_product_subtotal ) ) {
												$cnt_product_subtotal = 2;
												foreach ( $filled_product_subtotal as $key => $productfees ) {
													$fees_ap_fees_product_subtotal            = isset( $productfees['ap_fees_product_subtotal'] ) ? $productfees['ap_fees_product_subtotal'] : '';
													$ap_fees_ap_product_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_product_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_product_subtotal_min_subtotal'] : '';
													$ap_fees_ap_product_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_product_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_product_subtotal_max_subtotal'] : '';
													$ap_fees_ap_price_product_subtotal        = isset( $productfees['ap_fees_ap_price_product_subtotal'] ) ? $productfees['ap_fees_ap_price_product_subtotal'] : '';
													?>
													<tr id="ap_product_subtotal_row_<?php echo esc_attr( $cnt_product_subtotal ); ?>"
													    valign="top" class="ap_product_subtotal_row_tr">
														<td scope="row">
															<select
																rel-id="<?php echo esc_attr( $cnt_product_subtotal ); ?>"
																id="ap_product_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_product_subtotal ); ?>"
																name="fees[ap_product_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_product_subtotal ); ?>][]"
																class="wcpfc_select ap_product_subtotal product_fees_conditions_values multiselect2"
																multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_product_options( $cnt_product_subtotal, $fees_ap_fees_product_subtotal ), $wcpfc_object::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_product_subtotal_min_subtotal][]"
															       class="text-class price-class"
															       id="ap_fees_ap_product_subtotal_min_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Min Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       step="0.01"
															       value="<?php echo esc_attr( $ap_fees_ap_product_subtotal_min_subtotal ); ?>"
															       min="0.0">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_product_subtotal_max_subtotal][]"
															       class="text-class price-class"
															       id="ap_fees_ap_product_subtotal_max_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Max Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       step="0.01"
															       value="<?php echo esc_attr( $ap_fees_ap_product_subtotal_max_subtotal ); ?>"
															       min="0.0">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_product_subtotal][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_product_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_product_subtotal ); ?>">
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_product_subtotal, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_subtotal ); ?> condition-value">
															<a id="ap-product-subtotal-delete-field"
															   rel-id="<?php echo esc_attr( $cnt_product_subtotal ); ?>"
															   title="Delete" class="delete-row"
															   href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_product_subtotal ++;
												}
												?>
												<?php
											} else {
												$cnt_product_subtotal = 1;
											} ?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_product_subtotal"
										       id="total_row_product_subtotal"
										       value="<?php echo esc_attr( $cnt_product_subtotal ); ?>">
										<!-- Advanced Pricing Category Section end here -->
									</div>
								</div>
								<!-- Advanced Pricing Product Subtotal end here -->
								
								<?php // Advanced Pricing Product Weight start here ?>
								<div class="ap_product_weight_container advance_pricing_rule_box tab-content" id="tab-3"
								     data-title="<?php esc_attr_e( 'Cost on Product Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_product_weight_list"></div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Product Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-product-weight-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_product_weight_status"
														       value="on" <?php echo esc_attr( $cost_on_product_weight_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_product_weight_rule_match]"
												        id="cost_on_product_weight_rule_match" class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_product_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_product_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_product_weight_method"
										       class="tbl_product_weight_fee table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_product_weight_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the fee amount when the min/max weight match.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_weight_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product weight per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_weight_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Max Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product weight per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_product_weight_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$product_weight_filled_arr = array();
											if ( ! empty( $sm_metabox_ap_product_weight ) && is_array( $sm_metabox_ap_product_weight ) ):
												foreach ( $sm_metabox_ap_product_weight as $app_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $app_arr ) || '' !== $app_arr ) {
														if ( ( '' !== $app_arr['ap_fees_product_weight'] && '' !== $app_arr['ap_fees_ap_price_product_weight'] ) && ( '' !== $app_arr['ap_fees_ap_product_weight_min_qty'] || '' !== $app_arr['ap_fees_ap_product_weight_max_qty'] ) ) {
															//if condition match than fill in array
															$product_weight_filled_arr[] = $app_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $product_weight_filled_arr ) && ! empty( $product_weight_filled_arr ) ) {
												$cnt_product_weight = 2;
												foreach ( $product_weight_filled_arr as $key => $product_weight_fees ) {
													$fees_ap_fees_product_weight     = isset( $product_weight_fees['ap_fees_product_weight'] ) ? $product_weight_fees['ap_fees_product_weight'] : '';
													$ap_fees_product_weight_min_qty  = isset( $product_weight_fees['ap_fees_ap_product_weight_min_qty'] ) ? $product_weight_fees['ap_fees_ap_product_weight_min_qty'] : '';
													$ap_fees_product_weight_max_qty  = isset( $product_weight_fees['ap_fees_ap_product_weight_max_qty'] ) ? $product_weight_fees['ap_fees_ap_product_weight_max_qty'] : '';
													$ap_fees_ap_price_product_weight = isset( $product_weight_fees['ap_fees_ap_price_product_weight'] ) ? $product_weight_fees['ap_fees_ap_price_product_weight'] : '';
													?>
													<tr id="ap_product_weight_row_<?php echo esc_attr( $cnt_product_weight ); ?>"
													    valign="top" class="ap_product_weight_row_tr">
														<td scope="row">
															<select
																rel-id="<?php echo esc_attr( $cnt_product_weight ); ?>"
																id="ap_product_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_product_weight ); ?>"
																name="fees[ap_product_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_product_weight ); ?>][]"
																id="ap_product_weight_fees_conditions_condition"
																class="wcpfc_select ap_product_weight product_fees_conditions_values multiselect2"
																multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_product_options( $cnt_product_weight, $fees_ap_fees_product_weight ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
															<input type="text"
															       name="fees[ap_fees_ap_product_weight_min_weight][]"
															       class="text-class weight-class"
															       id="ap_fees_ap_product_weight_min_weight[]"
															       placeholder="<?php esc_attr_e( 'Min Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_product_weight_min_qty ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
															<input type="text"
															       name="fees[ap_fees_ap_product_weight_max_weight][]"
															       class="text-class weight-class"
															       id="ap_fees_ap_product_weight_max_weight[]"
															       placeholder="<?php esc_attr_e( 'Max Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_product_weight_max_qty ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_product_weight][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_product_weight[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_product_weight ); ?>">
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_product_weight, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_product_weight ); ?> condition-value">
															<a id="ap_product_weight_delete_field"
															   rel-id="<?php echo esc_attr( $cnt_product_weight ); ?>"
															   title="Delete" class="delete-row" href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_product_weight ++;
												}
												?>
												<?php
											} else {
												$cnt_product_weight = 1;
											}
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_product_weight"
										       id="total_row_product_weight"
										       value="<?php echo esc_attr( $cnt_product_weight ); ?>">
									</div>
								</div>
								<?php // Advanced PricingProduct Weight end here  ?>
								
								<?php // Advanced Pricing Category Section start here ?>
								<div class="ap_category_container advance_pricing_rule_box tab-content" id="tab-4"
								     data-title="<?php esc_attr_e( 'Cost on Category', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_category_list">
												<?php
												echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_category_options__premium_only( "", $json = true ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
												?>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Category', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-category-add-field" class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_category_status"
														       value="on" <?php echo esc_attr( $cost_on_category_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_category_rule_match]"
												        id="cost_on_category_rule_match" class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_category_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_category_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
											<div class="switch_status_div">
												<label class="switch switch_in_pricing_rules">
													<input type="checkbox" name="display_category_ap_rule_in_single_product" value="on" <?php echo esc_attr( $display_category_ap_rule_in_single_product ); ?>>
													<div class="slider round"></div>
												</label>
												<?php echo wp_kses( wc_help_tip( esc_html__( 'Turn off this button, if you want to hide the Advanced Pricing Rules of "Cost on Category" in Single Product Page', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												<h2 class="ap-title"><?php esc_html_e( 'Display Category AP Rule in Product Page', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											</div>
										</div>
										<table id="tbl_ap_category_method"
										       class="tbl_category_fee table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_category_fees_conditions_condition"
												    scope="row">
                                                    <label><?php esc_html_e( 'Category', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a category to apply the fee amount when the min/max quantity match.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_fees_conditions_condition" scope="row">
													<label><?php esc_html_e( 'Min Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum category quantity per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_fees_conditions_condition" scope="row">
													<label><?php esc_html_e( 'Max Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum category quantity per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_arr = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_category ) && is_array( $sm_metabox_ap_category ) ):
												foreach ( $sm_metabox_ap_category as $apcat_arr ) {
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
														if ( ( '' !== $apcat_arr['ap_fees_categories'] && '' !== $apcat_arr['ap_fees_ap_price_category'] ) &&
														     ( '' !== $apcat_arr['ap_fees_ap_cat_min_qty'] || '' !== $apcat_arr['ap_fees_ap_cat_max_qty'] ) ) {
															//if condition match than fill in array
															$filled_arr[] = $apcat_arr;
														}
													}
												}
											endif;
											//check APR exist
											if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
												$cnt_category = 2;
												foreach ( $filled_arr as $key => $productfees ) {
													$fees_ap_fees_categories   = isset( $productfees['ap_fees_categories'] ) ? $productfees['ap_fees_categories'] : '';
													$ap_fees_ap_cat_min_qty    = isset( $productfees['ap_fees_ap_cat_min_qty'] ) ? $productfees['ap_fees_ap_cat_min_qty'] : '';
													$ap_fees_ap_cat_max_qty    = isset( $productfees['ap_fees_ap_cat_max_qty'] ) ? $productfees['ap_fees_ap_cat_max_qty'] : '';
													$ap_fees_ap_price_category = isset( $productfees['ap_fees_ap_price_category'] ) ? $productfees['ap_fees_ap_price_category'] : '';
                                                    $ap_fees_ap_per_category   = isset( $productfees['ap_fees_ap_per_category'] ) && strpos($ap_fees_ap_price_category, '%') ? $productfees['ap_fees_ap_per_category'] : 'no';
													?>
													<tr id="ap_category_row_<?php echo esc_attr( $cnt_category ); ?>"
													    valign="top" class="ap_category_row_tr">
														<td scope="row">
															<select rel-id="<?php echo esc_attr( $cnt_category ); ?>"
															        id="ap_category_fees_conditions_condition_<?php echo esc_attr( $cnt_category ); ?>"
															        name="fees[ap_category_fees_conditions_condition][<?php echo esc_attr( $cnt_category ); ?>][]"
															        id="ap_category_fees_conditions_condition"
															        class="wcpfc_select ap_category product_fees_conditions_values multiselect2"
															        multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_category_options__premium_only( $fees_ap_fees_categories, $json = false ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_cat_min_qty][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_cat_min_qty[]"
															       placeholder="<?php esc_attr_e( 'Min Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_cat_min_qty ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_cat_max_qty][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_cat_max_qty[]"
															       placeholder="<?php esc_attr_e( 'Max Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_cat_max_qty ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_category][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_category[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_category ); ?>">
                                                            <label class="ap-label-checkbox">
																<input type="hidden" name="fees[ap_fees_ap_per_category][]"
															       id="ap_fees_ap_per_category[]"
															       value="<?php echo esc_attr( 'no' ); ?>">
																<input type="checkbox" name="fees[ap_fees_ap_per_category][]"
															       id="ap_fees_ap_per_category[]"
																   <?php checked( $ap_fees_ap_per_category, 'yes' ); ?>
															       value="<?php echo esc_attr( 'yes' ); ?>">
																<span><?php esc_html_e('Apply on Products', 'woocommerce-conditional-product-fees-for-checkout'); ?></span>
															</label>
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_category, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category ); ?> condition-value">
															<a id="ap_category_delete_field"
															   rel-id="<?php echo esc_attr( $cnt_category ); ?>"
															   title="Delete" class="delete-row" href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_category ++;
												}
												?>
												<?php
											} else {
												$cnt_category = 1;
											}
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_category" id="total_row_category"
										       value="<?php echo esc_attr( $cnt_category ); ?>">
										<!-- Advanced Pricing Category Section end here -->
									</div>
								</div>
								<?php // Advanced Pricing Category Section end here  ?>

								<!-- Advanced Pricing Category Subtotal start here -->
								<div class="ap_category_subtotal_container advance_pricing_rule_box tab-content"
								     id="tab-5"
								     data-title="<?php esc_attr_e( 'Cost on Category Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_cart_subtotal">
												<option
													value="category_subtotal"><?php esc_html_e( 'Category Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Category Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-category-subtotal-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox"
														       name="cost_on_category_subtotal_status"
														       value="on" <?php echo esc_attr( $cost_on_category_subtotal_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_category_subtotal_rule_match]"
												        id="cost_on_category_subtotal_rule_match"
												        class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_category_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_category_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_category_subtotal_method"
										       class="tbl_category_subtotal table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_category_subtotal_fees_conditions_condition"
												    scope="row">
                                                    <label><?php esc_html_e( 'Category', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
													<?php echo wp_kses( wc_help_tip( esc_html__( 'Select a category to apply the fee amount when the min/max subtotal match.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_subtotal_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Subtotal ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_subtotal_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Max Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_category_subtotal = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_category_subtotal ) && is_array( $sm_metabox_ap_category_subtotal ) ):
												foreach ( $sm_metabox_ap_category_subtotal as $apcat_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || $apcat_arr !== '' ) {
														if (
															( $apcat_arr['ap_fees_category_subtotal'] !== '' && $apcat_arr['ap_fees_ap_price_category_subtotal'] !== '' ) &&
															( $apcat_arr['ap_fees_ap_category_subtotal_min_subtotal'] !== '' || $apcat_arr['ap_fees_ap_category_subtotal_max_subtotal'] !== '' )
														) {
															$filled_category_subtotal[] = $apcat_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_category_subtotal ) && ! empty( $filled_category_subtotal ) ) {
												$cnt_category_subtotal = 2;
												foreach ( $filled_category_subtotal as $key => $productfees ) {
													$fees_ap_fees_category_subtotal            = isset( $productfees['ap_fees_category_subtotal'] ) ? $productfees['ap_fees_category_subtotal'] : '';
													$ap_fees_ap_category_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_category_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_category_subtotal_min_subtotal'] : '';
													$ap_fees_ap_category_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_category_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_category_subtotal_max_subtotal'] : '';
													$ap_fees_ap_price_category_subtotal        = isset( $productfees['ap_fees_ap_price_category_subtotal'] ) ? $productfees['ap_fees_ap_price_category_subtotal'] : '';
													?>
													<tr id="ap_category_subtotal_row_<?php echo esc_attr( $cnt_category_subtotal ); ?>"
													    valign="top" class="ap_category_subtotal_row_tr">
														<td scope="row">
															<select
																rel-id="<?php echo esc_attr( $cnt_category_subtotal ); ?>"
																id="ap_category_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_category_subtotal ); ?>"
																name="fees[ap_category_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_category_subtotal ); ?>][]"
																id="ap_category_subtotal_fees_conditions_condition"
																class="wcpfc_select ap_category_subtotal product_fees_conditions_values multiselect2"
																multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_category_options__premium_only( $fees_ap_fees_category_subtotal, $json = false ), $wcpfc_object::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_category_subtotal_min_subtotal][]"
															       class="text-class price-class"
															       id="ap_fees_ap_category_subtotal_min_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Min Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       step="0.01"
															       value="<?php echo esc_attr( $ap_fees_ap_category_subtotal_min_subtotal ); ?>"
															       min="0.0">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_category_subtotal_max_subtotal][]"
															       class="text-class price-class"
															       id="ap_fees_ap_category_subtotal_max_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Max Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       step="0.01"
															       value="<?php echo esc_attr( $ap_fees_ap_category_subtotal_max_subtotal ); ?>"
															       min="0.0">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_category_subtotal][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_category_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_category_subtotal ); ?>">
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_category_subtotal, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_subtotal ); ?> condition-value">
															<a id="ap-category-subtotal-delete-field"
															   rel-id="<?php echo esc_attr( $cnt_category_subtotal ); ?>"
															   title="Delete" class="delete-row"
															   href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_category_subtotal ++;
												}
												?>
												<?php
											} else {
												$cnt_category_subtotal = 1;
											} ?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_category_subtotal"
										       id="total_row_category_subtotal"
										       value="<?php echo esc_attr( $cnt_category_subtotal ); ?>">
										<!-- Advanced Pricing Category Section end here -->

									</div>
								</div>
								<!-- Advanced Pricing Category Subtotal  end here -->
								
								<?php // Advanced Pricing Category Weight start here  ?>
								<div class="ap_category_weight_container advance_pricing_rule_box tab-content"
								     id="tab-6"
								     data-title="<?php esc_attr_e( 'Cost on Category Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_category_weight_list">
												<option
													value=""><?php esc_html_e( 'Select Category', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												<?php
												echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_category_options__premium_only( "", $json = true ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
												?>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Category Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-category-weight-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_category_weight_status"
														       value="on" <?php echo esc_attr( $cost_on_category_weight_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_category_weight_rule_match]"
												        id="cost_on_category_weight_rule_match"
												        class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_category_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_category_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_category_weight_method"
										       class="tbl_category_weight_fee table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_category_weight_fees_conditions_condition"
												    scope="row">
                                                    <label><?php esc_html_e( 'Category', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a category to apply the fee amount when the min/max weight match.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_weight_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum category weight per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_weight_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Max Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum category weight per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_category_weight_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_arr_cat_weight = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_category_weight ) && is_array( $sm_metabox_ap_category_weight ) ):
												foreach ( $sm_metabox_ap_category_weight as $apcat_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
														if ( ( '' !== $apcat_arr['ap_fees_categories_weight'] && '' !== $apcat_arr['ap_fees_categories_weight'] ) &&
														     ( '' !== $apcat_arr['ap_fees_ap_category_weight_min_qty'] || '' !== $apcat_arr['ap_fees_ap_category_weight_max_qty'] ) ) {
															//if condition match than fill in array
															$filled_arr_cat_weight[] = $apcat_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_arr_cat_weight ) && ! empty( $filled_arr_cat_weight ) ) {
												$cnt_category_weight = 2;
												foreach ( $filled_arr_cat_weight as $key => $productfees ) {
													$fees_ap_fees_categories_weight     = isset( $productfees['ap_fees_categories_weight'] ) ? $productfees['ap_fees_categories_weight'] : '';
													$ap_fees_ap_category_weight_min_qty = isset( $productfees['ap_fees_ap_category_weight_min_qty'] ) ? $productfees['ap_fees_ap_category_weight_min_qty'] : '';
													$ap_fees_ap_category_weight_max_qty = isset( $productfees['ap_fees_ap_category_weight_max_qty'] ) ? $productfees['ap_fees_ap_category_weight_max_qty'] : '';
													$ap_fees_ap_price_category_weight   = isset( $productfees['ap_fees_ap_price_category_weight'] ) ? $productfees['ap_fees_ap_price_category_weight'] : '';
													?>
													<tr id="ap_category_weight_row_<?php echo esc_attr( $cnt_category_weight ); ?>"
													    valign="top" class="ap_category_weight_row_tr">
														<td scope="row">
															<select
																rel-id="<?php echo esc_attr( $cnt_category_weight ); ?>"
																id="ap_category_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_category_weight ); ?>"
																name="fees[ap_category_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_category_weight ); ?>][]"
																id="ap_category_weight_fees_conditions_condition"
																class="wcpfc_select ap_category_weight product_fees_conditions_values multiselect2"
																multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_category_options__premium_only( $fees_ap_fees_categories_weight, $json = false ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
															<input type="text"
															       name="fees[ap_fees_ap_category_weight_min_weight][]"
															       class="text-class weight-class"
															       id="ap_fees_ap_category_weight_min_weight[]"
															       placeholder="<?php esc_attr_e( 'Min Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_category_weight_min_qty ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
															<input type="text"
															       name="fees[ap_fees_ap_category_weight_max_weight][]"
															       class="text-class weight-class"
															       id="ap_fees_ap_category_weight_max_weight[]"
															       placeholder="<?php esc_attr_e( 'Max Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_category_weight_max_qty ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_category_weight][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_category_weight[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_category_weight ); ?>">
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_category_weight, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_category_weight ); ?> condition-value">
															<a id="ap_category_weight_delete_field"
															   rel-id="<?php echo esc_attr( $cnt_category_weight ); ?>"
															   title="Delete" class="delete-row" href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_category_weight ++;
												}
												?>
												<?php
											} else {
												$cnt_category_weight = 1;
											}
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_category_weight"
										       id="total_row_category_weight"
										       value="<?php echo esc_attr( $cnt_category_weight ); ?>">
										<!-- Advanced Pricing Category Section end here -->
									</div>
								</div>
								<?php // Advanced Pricing Category Weight end here  ?>
								
								<?php // Advanced Pricing Total QTY start here  ?>
								<div class="ap_total_cart_container advance_pricing_rule_box tab-content" id="tab-7"
								     data-title="<?php esc_attr_e( 'Cost on Total Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_cart_qty">
												<option
													value="total_cart_qty"><?php esc_html_e( 'Total Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Total Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-total-cart-qty-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_total_cart_qty_status"
														       value="on" <?php echo esc_attr( $cost_on_total_cart_qty_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_total_cart_qty_rule_match]"
												        id="cost_on_total_cart_qty_rule_match" class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_total_cart_qty_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_total_cart_qty_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
											<?php
											if ( apply_filters( 'wcpfc_ap_total_cart_qty_n_combination', false ) ) {
												?>
												<div class="calculate_n_combination">
													<label class="ap-label-checkbox">
														<input type="checkbox" id="cost_on_total_cart_qty_n_combination" name="cost_on_total_cart_qty_n_combination" value="on" <?php echo esc_attr( $cost_on_total_cart_qty_n_combination ); ?>>
														<?php esc_html_e( 'Calculate combination up to N', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
													</label>
												</div>
												<?php
											}
											?>
										</div>
										<table id="tbl_ap_total_cart_qty_method"
										       class="tbl_total_cart_qty table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_total_cart_qty_fees_conditions_condition"
												    scope="row"><?php esc_html_e( 'Total Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</th>
												<th class="titledesc th_total_cart_qty_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart quantity per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_total_cart_qty_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Max Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart quantity per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_total_cart_qty_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_total_cart_qty = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_total_cart_qty ) && is_array( $sm_metabox_ap_total_cart_qty ) ):
												foreach ( $sm_metabox_ap_total_cart_qty as $apcat_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
														if ( ( '' !== $apcat_arr['ap_fees_total_cart_qty'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_qty'] ) &&
														     ( '' !== $apcat_arr['ap_fees_ap_total_cart_qty_min_qty'] || '' !== $apcat_arr['ap_fees_ap_total_cart_qty_max_qty'] ) ) {
															//if condition match than fill in array
															$filled_total_cart_qty[] = $apcat_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_total_cart_qty ) && ! empty( $filled_total_cart_qty ) ) {
												$cnt_total_cart_qty = 2;
												foreach ( $filled_total_cart_qty as $key => $productfees ) {
													$fees_ap_fees_total_cart_qty       = isset( $productfees['ap_fees_total_cart_qty'] ) ? $productfees['ap_fees_total_cart_qty'] : '';
													$ap_fees_ap_total_cart_qty_min_qty = isset( $productfees['ap_fees_ap_total_cart_qty_min_qty'] ) ? $productfees['ap_fees_ap_total_cart_qty_min_qty'] : '';
													$ap_fees_ap_total_cart_qty_max_qty = isset( $productfees['ap_fees_ap_total_cart_qty_max_qty'] ) ? $productfees['ap_fees_ap_total_cart_qty_max_qty'] : '';
													$ap_fees_ap_price_total_cart_qty   = isset( $productfees['ap_fees_ap_price_total_cart_qty'] ) ? $productfees['ap_fees_ap_price_total_cart_qty'] : '';
													?>
													<tr id="ap_total_cart_qty_row_<?php echo esc_attr( $cnt_total_cart_qty ); ?>"
													    valign="top" class="ap_total_cart_qty_row_tr">
														<td scope="row">
															<label><?php echo esc_html_e( 'Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
															<input type="hidden"
															       name="fees[ap_total_cart_qty_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_qty ); ?>][]"
															       id="ap_total_cart_qty_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_qty ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_total_cart_qty_min_qty][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_total_cart_qty_min_qty[]"
															       placeholder="<?php esc_attr_e( 'Min Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_total_cart_qty_min_qty ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_total_cart_qty_max_qty][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_total_cart_qty_max_qty[]"
															       placeholder="<?php esc_attr_e( 'Max Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_total_cart_qty_max_qty ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_total_cart_qty][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_total_cart_qty[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_qty ); ?>">
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_total_cart_qty, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_qty ); ?> condition-value">
															<a id="ap_total_cart_qty_delete-field"
															   rel-id="<?php echo esc_attr( $cnt_total_cart_qty ); ?>"
															   title="Delete" class="delete-row" href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_total_cart_qty ++;
												}
												?>
												<?php
											} else {
												$cnt_total_cart_qty = 1;
												?>
											<?php }
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_total_cart_qty"
										       id="total_row_total_cart_qty"
										       value="<?php echo esc_attr( $cnt_total_cart_qty ); ?>">
										<!-- Advanced Pricing Category Section end here -->
									</div>
								</div>
								<?php // Advanced Pricing Total QTY end here ?>
								
								<?php // Advanced Pricing Total Cart Weight start here  ?>
								<div class="ap_total_cart_weight_container advance_pricing_rule_box tab-content"
								     id="tab-8"
								     data-title="<?php esc_attr_e( 'Cost on Total Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_cart_weight">
												<option
													value="total_cart_weight"><?php esc_html_e( 'Total Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Total Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-total-cart-weight-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_total_cart_weight_status"
														       value="on" <?php echo esc_attr( $cost_on_total_cart_weight_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_total_cart_weight_rule_match]"
												        id="cost_on_total_cart_weight_rule_match"
												        class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_total_cart_weight_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_total_cart_weight_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_total_cart_weight_method"
										       class="tbl_total_cart_weight table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_total_cart_weight_fees_conditions_condition"
												    scope="row"><?php esc_html_e( 'Total Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</th>
												<th class="titledesc th_total_cart_weight_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Weight ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart weight per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
										        </th>
												<th class="titledesc th_total_cart_weight_fees_conditions_condition"
												    scope="row">
													<lanel><?php esc_html_e( 'Max Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart weight per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_total_cart_weight_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_total_cart_weight = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_total_cart_weight ) && is_array( $sm_metabox_ap_total_cart_weight ) ):
												foreach ( $sm_metabox_ap_total_cart_weight as $apcat_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || '' !== $apcat_arr ) {
														if ( ( '' !== $apcat_arr['ap_fees_total_cart_weight'] && '' !== $apcat_arr['ap_fees_ap_price_total_cart_weight'] ) &&
														     ( '' !== $apcat_arr['ap_fees_ap_total_cart_weight_min_weight'] || '' !== $apcat_arr['ap_fees_ap_total_cart_weight_max_weight'] ) ) {
															//if condition match than fill in array
															$filled_total_cart_weight[] = $apcat_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_total_cart_weight ) && ! empty( $filled_total_cart_weight ) ) {
												$cnt_total_cart_weight = 2;
												foreach ( $filled_total_cart_weight as $key => $productfees ) {
													$fees_ap_fees_total_cart_weight          = isset( $productfees['ap_fees_total_cart_weight'] ) ? $productfees['ap_fees_total_cart_weight'] : '';
													$ap_fees_ap_total_cart_weight_min_weight = isset( $productfees['ap_fees_ap_total_cart_weight_min_weight'] ) ? $productfees['ap_fees_ap_total_cart_weight_min_weight'] : '';
													$ap_fees_ap_total_cart_weight_max_weight = isset( $productfees['ap_fees_ap_total_cart_weight_max_weight'] ) ? $productfees['ap_fees_ap_total_cart_weight_max_weight'] : '';
													$ap_fees_ap_price_total_cart_weight      = isset( $productfees['ap_fees_ap_price_total_cart_weight'] ) ? $productfees['ap_fees_ap_price_total_cart_weight'] : '';
													?>
													<tr id="ap_total_cart_weight_row_<?php echo esc_attr( $cnt_total_cart_weight ); ?>"
													    valign="top" class="ap_total_cart_weight_row_tr">
														<td scope="row">
															<label><?php echo esc_html_e( 'Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
															<input type="hidden"
															       name="fees[ap_total_cart_weight_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_weight ); ?>][]"
															       id="ap_total_cart_weight_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_weight ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
															<input type="text"
															       name="fees[ap_fees_ap_total_cart_weight_min_weight][]"
															       class="text-class weight-class"
															       id="ap_fees_ap_total_cart_weight_min_weight[]"
															       placeholder="<?php esc_attr_e( 'Min Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_total_cart_weight_min_weight ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
															<input type="text"
															       name="fees[ap_fees_ap_total_cart_weight_max_weight][]"
															       class="text-class weight-class"
															       id="ap_fees_ap_total_cart_weight_max_weight[]"
															       placeholder="<?php esc_attr_e( 'Max Weight', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_total_cart_weight_max_weight ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_total_cart_weight][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_total_cart_weight[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_weight ); ?>">
															<?php
															$first_char = substr( $ap_fees_ap_price_total_cart_weight, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_weight ); ?> condition-value">
															<a id="ap_total_cart_weight_delete_field"
															   rel-id="<?php echo esc_attr( $cnt_total_cart_weight ); ?>"
															   title="Delete" class="delete-row" href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_total_cart_weight ++;
												}
												?>
												<?php
											} else {
												$cnt_total_cart_weight = 1;
											}
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_total_cart_weight"
										       id="total_row_total_cart_weight"
										       value="<?php echo esc_attr( $cnt_total_cart_weight ); ?>">
										<!-- Advanced Pricing Category Section end here -->
									</div>
								</div>
								<?php //Advanced Pricing Total Cart Weight end here  ?>

								<!-- Advanced Pricing Total Cart Subtotal start here -->
								<div class="ap_total_cart_subtotal_container advance_pricing_rule_box tab-content"
								     id="tab-9"
								     data-title="<?php esc_attr_e( 'Cost on Total Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_cart_subtotal">
												<option
													value="total_cart_subtotal"><?php esc_html_e( 'Total Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Total Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-total-cart-subtotal-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox"
														       name="cost_on_total_cart_subtotal_status"
														       value="on" <?php echo esc_attr( $cost_on_total_cart_subtotal_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_total_cart_subtotal_rule_match]"
												        id="cost_on_total_cart_subtotal_rule_match"
												        class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_total_cart_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_total_cart_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_total_cart_subtotal_method"
										       class="tbl_total_cart_subtotal table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_total_cart_subtotal_fees_conditions_condition"
												    scope="row">
                                                    <label><?php esc_html_e( 'Total Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
												</th>
												<th class="titledesc th_total_cart_subtotal_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Subtotal ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum total cart subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_total_cart_subtotal_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Max Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum total cart subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_total_cart_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_total_cart_subtotal = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_total_cart_subtotal ) && is_array( $sm_metabox_ap_total_cart_subtotal ) ):
												foreach ( $sm_metabox_ap_total_cart_subtotal as $apcat_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apcat_arr ) || $apcat_arr !== '' ) {
														if (
															( $apcat_arr['ap_fees_total_cart_subtotal'] !== '' && $apcat_arr['ap_fees_ap_price_total_cart_subtotal'] !== '' ) &&
															( $apcat_arr['ap_fees_ap_total_cart_subtotal_min_subtotal'] !== '' || $apcat_arr['ap_fees_ap_total_cart_subtotal_max_subtotal'] !== '' )
														) {
															$filled_total_cart_subtotal[] = $apcat_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_total_cart_subtotal ) && ! empty( $filled_total_cart_subtotal ) ) {
												$cnt_total_cart_subtotal = 2;
												foreach ( $filled_total_cart_subtotal as $key => $productfees ) {
													$fees_ap_fees_total_cart_subtotal            = isset( $productfees['ap_fees_total_cart_subtotal'] ) ? $productfees['ap_fees_total_cart_subtotal'] : '';
													$ap_fees_ap_total_cart_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_total_cart_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_total_cart_subtotal_min_subtotal'] : '';
													$ap_fees_ap_total_cart_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_total_cart_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_total_cart_subtotal_max_subtotal'] : '';
													$ap_fees_ap_price_total_cart_subtotal        = isset( $productfees['ap_fees_ap_price_total_cart_subtotal'] ) ? $productfees['ap_fees_ap_price_total_cart_subtotal'] : '';
													?>
													<tr id="ap_total_cart_subtotal_row_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>"
													    valign="top" class="ap_total_cart_subtotal_row_tr">
														<td scope="row">
															<label><?php echo esc_html_e( 'Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
															<input type="hidden"
															       name="fees[ap_total_cart_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>][]"
															       id="ap_total_cart_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_total_cart_subtotal_min_subtotal][]"
															       class="text-class price-class"
															       id="ap_fees_ap_total_cart_subtotal_min_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Min Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       step="0.01"
															       value="<?php echo esc_attr( $ap_fees_ap_total_cart_subtotal_min_subtotal ); ?>"
															       min="0.0">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_total_cart_subtotal_max_subtotal][]"
															       class="text-class price-class"
															       id="ap_fees_ap_total_cart_subtotal_max_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Max Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       step="0.01"
															       value="<?php echo esc_attr( $ap_fees_ap_total_cart_subtotal_max_subtotal ); ?>"
															       min="0.0">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_total_cart_subtotal][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_total_cart_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_total_cart_subtotal ); ?>">
															<?php
															$first_char = substr( $ap_fees_ap_price_total_cart_subtotal, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_total_cart_subtotal ); ?> condition-value">
															<a id="ap-total-cart-subtotal-delete-field"
															   rel-id="<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>"
															   title="Delete" class="delete-row"
															   href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_total_cart_subtotal ++;
												}
												?>
												<?php
											} else {
												$cnt_total_cart_subtotal = 1;
											} ?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_total_cart_subtotal"
										       id="total_row_total_cart_subtotal"
										       value="<?php echo esc_attr( $cnt_total_cart_subtotal ); ?>">
										<!-- Advanced Pricing Category Section end here -->

									</div>
								</div>
								<!-- Advanced Pricing Total Cart Subtotal end here -->
								
								<?php // Advanced Pricing Shipping Class Subtotal Section start here ?>
								<div class="ap_shipping_class_subtotal_container advance_pricing_rule_box tab-content"
								     id="tab-10"
								     data-title="<?php esc_attr_e( 'Cost on Shipping Class Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_shipping_class_list">
												<?php
												echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_class_options__premium_only( '', $json = true ), Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() );
												?>
											</div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Shipping Class Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-shipping-class-subtotal-add-field"
												   class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox"
														       name="cost_on_shipping_class_subtotal_status"
														       value="on" <?php echo esc_attr( $cost_on_shipping_class_subtotal_status ); ?>>
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select
													name="cost_rule_match[cost_on_shipping_class_subtotal_rule_match]"
													id="cost_on_shipping_class_subtotal_rule_match"
													class="arcmt_select">
													<option
														value="any" <?php selected( $cost_on_shipping_class_subtotal_rule_match, 'any' ) ?>><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option
														value="all" <?php selected( $cost_on_shipping_class_subtotal_rule_match, 'all' ) ?>><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_shipping_class_subtotal_method"
										       class="tbl_shipping_class_subtotal_fee table-outer tap-cas form-table fees-table">
											<tbody>
											<tr class="heading">
												<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition"
												    scope="row">
                                                    <label><?php esc_html_e( 'Shipping Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a shipping class to apply the fee amount when the min/max quantity match.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Min Subtotal ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum shipping class subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition"
												    scope="row">
													<label><?php esc_html_e( 'Max Subtotal ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum shipping class subtotal per row before the fee amount is applied.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th class="titledesc th_shipping_class_subtotal_fees_conditions_condition" scope="row" colspan="2">
                                                    <label><?php esc_html_e( 'Fee amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5) percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.',  'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</th>
												<th>
													<a title="Delete" class="delete-row" href="javascript:;">
														<i class="fa fa-trash"></i>
													</a>
												</th>
											</tr>
											<?php
											//check advanced pricing value fill proper or unset if not
											$filled_arr = array();
											//check if category AP rules exist
											if ( ! empty( $sm_metabox_ap_shipping_class_subtotal ) && is_array( $sm_metabox_ap_shipping_class_subtotal ) ):
												foreach ( $sm_metabox_ap_shipping_class_subtotal as $apscs_arr ):
													//check that if required field fill or not once save the APR,  if match than fill in array
													if ( ! empty( $apscs_arr ) || '' !== $apscs_arr ) {
														if ( ( '' !== $apscs_arr['ap_fees_shipping_class_subtotals'] && '' !== $apscs_arr['ap_fees_ap_price_shipping_class_subtotal'] ) &&
														     ( '' !== $apscs_arr['ap_fees_ap_shipping_class_subtotal_min_subtotal'] || '' !== $apscs_arr['ap_fees_ap_shipping_class_subtotal_max_subtotal'] ) ) {
															//if condition match than fill in array
															$filled_arr[] = $apscs_arr;
														}
													}
												endforeach;
											endif;
											//check APR exist
											if ( isset( $filled_arr ) && ! empty( $filled_arr ) ) {
												$cnt_shipping_class_subtotal = 2;
												foreach ( $filled_arr as $key => $productfees ) {
													$fees_ap_fees_shipping_class_subtotals           = isset( $productfees['ap_fees_shipping_class_subtotals'] ) ? $productfees['ap_fees_shipping_class_subtotals'] : '';
													$ap_fees_ap_shipping_class_subtotal_min_subtotal = isset( $productfees['ap_fees_ap_shipping_class_subtotal_min_subtotal'] ) ? $productfees['ap_fees_ap_shipping_class_subtotal_min_subtotal'] : '';
													$ap_fees_ap_shipping_class_subtotal_max_subtotal = isset( $productfees['ap_fees_ap_shipping_class_subtotal_max_subtotal'] ) ? $productfees['ap_fees_ap_shipping_class_subtotal_max_subtotal'] : '';
													$ap_fees_ap_price_shipping_class_subtotal        = isset( $productfees['ap_fees_ap_price_shipping_class_subtotal'] ) ? $productfees['ap_fees_ap_price_shipping_class_subtotal'] : '';
													?>
													<tr id="ap_shipping_class_subtotal_row_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
													    valign="top"
													    class="ap_shipping_class_subtotal_row_tr">
														<td scope="row">
															<select
																rel-id="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
																id="ap_shipping_class_subtotal_fees_conditions_condition_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
																name="fees[ap_shipping_class_subtotal_fees_conditions_condition][<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>][]"
																id="ap_shipping_class_subtotal_fees_conditions_condition"
																class="wcpfc_select ap_shipping_class_subtotal product_fees_conditions_values multiselect2"
																multiple="multiple">
																<?php
																echo wp_kses( $wcpfc_admin_object->wcpfc_pro_get_class_options__premium_only( $fees_ap_fees_shipping_class_subtotals, $json = false ), $wcpfc_object::allowed_html_tags() );
																?>
															</select>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_shipping_class_subtotal_min_subtotal][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_shipping_class_subtotal_min_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Min quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_shipping_class_subtotal_min_subtotal ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
															<input type="number"
															       name="fees[ap_fees_ap_shipping_class_subtotal_max_subtotal][]"
															       class="text-class qty-class"
															       id="ap_fees_ap_shipping_class_subtotal_max_subtotal[]"
															       placeholder="<?php esc_attr_e( 'Max quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_shipping_class_subtotal_max_subtotal ); ?>"
															       min="1">
														</td>
														<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value" colspan="2">
															<input type="text"
															       name="fees[ap_fees_ap_price_shipping_class_subtotal][]"
															       class="text-class number-field"
															       id="ap_fees_ap_price_shipping_class_subtotal[]"
															       placeholder="<?php esc_attr_e( 'amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>"
															       value="<?php echo esc_attr( $ap_fees_ap_price_shipping_class_subtotal ); ?>">
															<?php
															//get first character for check is minus sign or not
															$first_char = substr( $ap_fees_ap_price_shipping_class_subtotal, 0, 1 );
															if ( '-' === $first_char ) {
																$html = sprintf(
																	'<p><b style="color: red;">%s</b>%s',
																	esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
																	esc_html__( 'If entered fee amount is less than cart subtotal it will reflect with minus sign (EX: $ -10.00) OR If entered fee amount is more than cart subtotal then the total amount shown as zero (EX: Total: 0): ', 'woocommerce-conditional-product-fees-for-checkout' )
																);
																echo wp_kses_post( $html );
															}
															?>
														</td>
														<td class="column_<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?> condition-value">
															<a id="ap-shipping-class-subtotal-delete-field"
															   rel-id="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>"
															   title="Delete" class="delete-row"
															   href="javascript:;">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													<?php
													$cnt_shipping_class_subtotal ++;
												}
												?>
												<?php
											} else {
												$cnt_shipping_class_subtotal = 1;
											}
											?>
											</tbody>
										</table>
										<input type="hidden" name="total_row_shipping_class_subtotal"
										       id="total_row_shipping_class_subtotal"
										       value="<?php echo esc_attr( $cnt_shipping_class_subtotal ); ?>">
										<!-- Advanced Pricing Category Section end here -->
									</div>
								</div>
								<?php // Advanced Pricing Shipping Class Subtotal Section end here  ?>
							</div>
						</div>
					</div>
					<?php // Advanced Pricing Section end  ?>
					<?php
				} else {
					?>
					<?php // Advanced Pricing Section start  ?>
					<div id="apm_wrap" class="fees-pricing-rules">
						<div class="ap_title">
							<h2><?php esc_html_e( 'Advanced Fees Price Rules', 'woocommerce-conditional-product-fees-for-checkout' ); ?><span class="wcpfc-pro-label"></span></h2>
							<label class="switch">
								<input type="checkbox" name="ap_rule_status" value="on">
								<div class="slider round"></div>
							</label>
							<span class="woocommerce_conditional_product_fees_checkout_tab_description"></span>
						</div>
						<div class="fees_pricing_rules wcpfc-upgrade-pro-to-unlock">
							<div class="fees_pricing_rules_tab">
								<ul class="tabs">
									<?php
									$tab_array = array(
										'tab-1'  => esc_html__( 'Cost on Product', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-2'  => esc_html__( 'Cost on Product Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-3'  => esc_html__( 'Cost on Product Weight', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-4'  => esc_html__( 'Cost on Category', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-5'  => esc_html__( 'Cost on Category Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-6'  => esc_html__( 'Cost on Category Weight', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-7'  => esc_html__( 'Cost on Total Cart Qty', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-8'  => esc_html__( 'Cost on Total Cart Weight', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-9'  => esc_html__( 'Cost on Total Cart Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
										'tab-10' => esc_html__( 'Cost on Shipping Class Subtotal', 'woocommerce-conditional-product-fees-for-checkout' ),
									);
									if ( ! empty( $tab_array ) ) {
										foreach ( $tab_array as $data_tab => $tab_title ) {
											if ( 'tab-1' === $data_tab ) {
												$class = ' current';
											} else {
												$class = '';
											}
											?>
											<li class="tab-link<?php echo esc_attr( $class ); ?>"
											    data-tab="<?php echo esc_attr( $data_tab ); ?>">
												<?php echo esc_html( $tab_title ); ?>
											</li>
											<?php
										}
									}
									?>
								</ul>
							</div>
							<div class="fees_pricing_rules_tab_content">
								<?php // Advanced Pricing Product Section start here   ?>
								<div class="ap_product_container advance_pricing_rule_box tab-content current" id="tab-1" data-title="<?php esc_attr_e( 'Cost on Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
									<div class="tap-class">
										<div class="predefined_elements">
											<div id="all_product_list"></div>
										</div>
										<div class="sub-title">
											<h2 class="ap-title"><?php esc_html_e( 'Cost on Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
											<div class="tap">
												<a id="ap-product-add-field" class="button"
												   href="javascript:;"><?php esc_html_e( '+ Add Rule', 'woocommerce-conditional-product-fees-for-checkout' ); ?></a>
												<div class="switch_status_div">
													<label class="switch switch_in_pricing_rules">
														<input type="checkbox" name="cost_on_product_status" value="on">
														<div class="slider round"></div>
													</label>
                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can turn off this button, if you do not need to apply this fee amount.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
												</div>
											</div>
											<div class="wocfc_match_type">
												<p class="switch_in_pricing_rules_description_left">
													<?php esc_html_e( 'below', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
												<select name="cost_rule_match[cost_on_product_rule_match]" id="cost_on_product_rule_match" class="arcmt_select">
													<option value="any"><?php esc_html_e( 'Any One', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
													<option value="all"><?php esc_html_e( 'All', 'woocommerce-conditional-product-fees-for-checkout' ); ?></option>
												</select>
												<p class="switch_in_pricing_rules_description">
													<?php esc_html_e( 'rule match', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
												</p>
											</div>
										</div>
										<table id="tbl_ap_product_method" class="tbl_product_fee table-outer tap-cas form-table fees-table">
											<tbody>
												<tr class="heading">
													<th class="titledesc th_product_fees_conditions_condition" scope="row">
	                                                    <label><?php esc_html_e( 'Product', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
	                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select a product to apply the fee amount when the min/max quantity match.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_product_fees_conditions_condition" scope="row">
	                                                    <label><?php esc_html_e( 'Min Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
	                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a minimum product quantity per row before the fee amount is applied.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
	                                                </th>
													<th class="titledesc th_product_fees_conditions_condition" scope="row">
	                                                    <label><?php esc_html_e( 'Max Quantity ', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
	                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'You can set a maximum product quantity per row before the fee amount is applied.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th class="titledesc th_product_fees_conditions_condition" scope="row" colspan="2">
	                                                    <label><?php esc_html_e( 'Fee amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
	                                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. Percentage and minus amount will apply based on the cart subtotal.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
													</th>
													<th>
														<a rel-id="2" title="Delete" class="delete-row" href="javascript:;">
															<i class="fa fa-trash"></i>
														</a>
													</th>
												</tr>
												<tr id="ap_product_row_0" valign="top" class="ap_product_row_tr">
													<td scope="row">
														<select rel-id="0" id="ap_product_fees_conditions_condition_0" name="fees[ap_product_fees_conditions_condition][0][]" id="ap_product_fees_conditions_condition" class="wcpfc_select ap_product product_fees_conditions_values multiselect2" multiple="multiple">
															<option><?php esc_html_e('Select a product', 'woocommerce-conditional-product-fees-for-checkout'); ?></option>
														</select>
													</td>
													<td class="column_0 condition-value">
														<input type="number" name="fees[ap_fees_ap_prd_min_qty][]" class="text-class qty-class" id="ap_fees_ap_prd_min_qty[]" placeholder="<?php esc_attr_e( 'Min Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" value="" min="1">
													</td>
													<td class="column_0 condition-value">
														<input type="number" name="fees[ap_fees_ap_prd_max_qty][]" class="text-class qty-class" id="ap_fees_ap_prd_max_qty[]" placeholder="<?php esc_attr_e( 'Max Quantity', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" value="" min="1">
													</td>
													<td class="column_0 condition-value" colspan="2">
														<input type="text" name="fees[ap_fees_ap_price_product][]" class="text-class number-field" id="ap_fees_ap_price_product[]" placeholder="<?php esc_attr_e( 'Amount', 'woocommerce-conditional-product-fees-for-checkout' ); ?>" value="">
														<label class="ap-label-checkbox">
															<input type="hidden" name="fees[ap_fees_ap_per_product][]" id="ap_fees_ap_per_product[]" value="<?php echo esc_attr( 'no' ); ?>">
															<input type="checkbox" name="fees[ap_fees_ap_per_product][]" id="ap_fees_ap_per_product[]" value="">
															<span><?php esc_html_e('Apply on Products', 'woocommerce-conditional-product-fees-for-checkout'); ?></span>
														</label>
													</td>
													<td class="column_0 condition-value">
														<a id="ap_product_delete_field" rel-id="0" title="Delete" class="delete-row" href="javascript:;">
															<i class="fa fa-trash"></i>
														</a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			} else {

			}
            wp_nonce_field( 'edit_' . $request_post_id, '_wpnonce' );
			?>
			<p class="submit"><input type="submit" name="submitFee" class="button button-primary" value="<?php echo esc_attr( $btnValue ); ?>"></p>
		</form>
	</div>
</div>
</div>
</div>
</div>
</div>
<?php
