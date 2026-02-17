<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @subpackage Woocommerce_Conditional_Product_Fees_For_Checkout_Pro/public
 * @author     Multidots <inquiry@multidots.in>
 */
class Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public {

	private static $admin_object = null;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Extra fee cost
	 *
	 * @since    3.9.5
	 * @access   private
	 */
	private $fee_cost;

	/**
	 * This is Optional Fees at Checkout meta key.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wcpfc_fee_revenue   This is Optional Fees at checkout meta key.
	 */
    private $wcpfc_fee_revenue = '_wcpfc_fee_revenue';

	/** 
		 * The current instance of the plugin
		 * 
		 * @since   1.0.0
		 * @access  protected
		 * @var     \Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public single instance of this plugin 
		 */
		protected static $instance;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		self::$admin_object = new Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin( '', '' );
		$this->include_modules();
	}

	/**
	 * Include public classes and objects.
	 *
	 * @since 1.0.0
	 */
	private function include_modules() {
        
        // Fees public edit screens
        require_once( plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-woocommerce-product-fees-conditional-rules.php' );        
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wcpfc_public_enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-conditional-product-fees-for-checkout-public.css', array(), $this->version, 'all' );
		if( is_cart() || is_checkout() ){
			wp_enqueue_style( $this->plugin_name . 'font-awesome', WCPFC_PRO_PLUGIN_URL . 'admin/css/font-awesome.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wcpfc_public_enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', [ 'jquery' ], WC_VERSION, true );

        if ( wcpffc_fs()->is__premium_only() ) {
            if ( wcpffc_fs()->can_use_premium_code() ) {
                wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-conditional-product-fees-for-checkout-public__premium_only.js', array( 'jquery', 'jquery-tiptip' ), $this->version, false );
            } else {
                wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-conditional-product-fees-for-checkout-public.js', array( 'jquery', 'jquery-tiptip' ), $this->version, false );
            }
        } else {
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-conditional-product-fees-for-checkout-public.js', array( 'jquery', 'jquery-tiptip' ), $this->version, false );
        }

        wp_localize_script( $this->plugin_name, 'wcpfc_public_vars', array(
            'fee_tooltip_data' => $this->wcpfc_all_fee_tooltip_data()
        ) 
    );
		
	}

	/**
	 * Override WooCommerce file in our plugin
	 *
	 * @param string $template
	 * @param string $template_name
	 * @param mixed  $template_path
	 *
	 * @return string $template
	 * @since    1.0.0
	 *
	 *
	 */
	public function wcpfc_wc_locate_template_product_fees_conditions( $template, $template_name, $template_path ) {
		global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}
		$plugin_path = wcpfc_pro_path() . '/woocommerce/';
		$template    = locate_template(
			array(
				$template_path . $template_name,
				$template_name,
			)
		);
		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}
		if ( ! $template ) {
			$template = $_template;
		}

		// Return what we found
		return $template;
	}

	/**
	 * Filter data
	 *
	 * @param string $string
	 *
	 * @since    3.9.3
	 *
	 */
	public function wcpfc_filter_sanitize_string( $string ) {
	    $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
	    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
	}

	/* Classic cart page notice message */
	public function wcpfc_pro_cart_notice_message__premium_only() {

		$fees = $this->wcpfc_pro_get_applied_fees();
	
		$currency_symbol = get_woocommerce_currency_symbol();
		$currency_symbol = ! empty( $currency_symbol ) ?  $currency_symbol : '';
	
		if ( ! empty( $fees ) ) {
			foreach ( $fees as $fee ) {
		
				$user_id = get_current_user_id() ?: wp_get_session_token();
				$transient_name = 'wcpfc_custom_fee_notice_shown_' . $user_id . '_' . sanitize_title( $fee->name );
	
				// Check if the notice for this specific fee has already been shown
				if ( get_transient( $transient_name ) ) {
					continue; // Skip to the next fee if the notice has already been shown
				}
	
				$args = array(
					'post_type'      => 'wc_conditional_fee',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'title'          => sanitize_text_field( $fee->name ),
				);
	
				$query = new WP_Query( $args );
	
				if ( $query->have_posts() ) {
					$fee_post_id = $query->posts[0];
	
					// Fetch the fee settings price message from the post meta
					$fee_settings_price_message_on_cart = get_post_meta( $fee_post_id, 'fee_settings_price_message_on_cart', true );
	
					// Fetch the total fee amount for the current fee
					$total_fees = $fee->amount;
	
					// Add the total fees and currency symbol to the message
					if ( ! empty( $fee_settings_price_message_on_cart ) ) {

						$fee_message_with_total = sprintf( '%s %s%s', $fee_settings_price_message_on_cart, $currency_symbol, number_format( $total_fees, 2 ) );
	
						// Add the custom message as a WooCommerce notice
						wc_add_notice( $fee_message_with_total, 'notice' );
	
						// Set the transient to mark the notice as shown
						set_transient( $transient_name, true, DAY_IN_SECONDS );
					}
				}
	
				wp_reset_postdata();
			}
		}
	}

	public function wcpfc_clear_custom_fee_notice_transients_if_cart_empty__premium_only() {
		// Check if the cart is empty
		if ( WC()->cart->is_empty() ) {
			$user_id = get_current_user_id() ?: wp_get_session_token();
			
			global $wpdb;
		
			// Find all transients related to this user
			$transients = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s", '_transient_wcpfc_custom_fee_notice_shown_' . $user_id . '_%' ) ); //phpcs:ignore
		
			// Loop through and delete each transient
			foreach ( $transients as $transient ) {
				$key = str_replace( '_transient_', '', $transient );
				delete_transient( $key );
			}
		}
	}

	// Filter to modify WooCommerce cart block content
	public function wcpfc_modify_woocommerce_cart_block_content__premium_only($block_content, $block) {

		$allowed_html = $this->wcpfc_get_allowed_html();

		if ($block['blockName'] === 'woocommerce/cart') {
			ob_start();
			do_action('before_woocommerce_cart_block');
			echo wp_kses( $block_content, $allowed_html );
			do_action('after_woocommerce_cart_block');
			$block_content = ob_get_clean();
		}
		return $block_content;
	}

	// Action to add custom notices to the WooCommerce cart block.
	public function wcpfc_pro_block_cart_notice_message__premium_only() {

		$fees = $this->wcpfc_pro_get_applied_fees();
		$currency_symbol = get_woocommerce_currency_symbol() ?: '';
	
		if ($fees) {
			foreach ($fees as $fee) {
				$fee_name = sanitize_text_field($fee->name);
				$user_id = get_current_user_id() ?: wp_get_session_token();
				$transient_name = 'wcpfc_custom_fee_notice_shown_' . $user_id . '_' . sanitize_title($fee_name);
	
				if (get_transient($transient_name)) {
					continue;
				}
	
				$args = array(
					'post_type'      => 'wc_conditional_fee',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'title'          => $fee_name,
				);
	
				$query = new WP_Query($args);
	
				if ($query->have_posts()) {
					$fee_post_id = $query->posts[0];
					$fee_message = get_post_meta($fee_post_id, 'fee_settings_price_message_on_cart', true);
					$total_fees = $fee->amount;
	
					if ($fee_message) {
						$fee_message_with_total = sprintf('%s %s%s', $fee_message, $currency_symbol, number_format($total_fees, 2));
						$custom_html = sprintf(
							'<div class="wc-block-components-notice-banner is-info" role="alert">
								<div class="wc-block-components-notice-banner__content">%s</div>
							</div>',
							$fee_message_with_total
						);
	
						echo wp_kses_post($custom_html);

						set_transient($transient_name, true, DAY_IN_SECONDS);
					}
				}
				wp_reset_postdata();
			}
		}
	}

	/**
	 * Display product price with handling fee on single product page.
	 */
	public function wcpfc_pro_conditional_fee_single_product__premium_only() {
		global $woocommerce_wpml, $sitepress, $product;
	
		$default_lang = self::$admin_object->wcpfc_pro_get_default_langugae_with_sitpress();
	
		$get_all_fees = get_transient('get_all_fees');
		if (false === $get_all_fees) {
			$fees_args = array(
				'post_type'        => 'wc_conditional_fee',
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
				'suppress_filters' => false,
				'fields'           => 'ids',
				'order'            => 'DESC',
				'orderby'          => 'ID',
			);
			$get_all_fees_query = new WP_Query($fees_args);
			$get_all_fees       = $get_all_fees_query->get_posts();
			set_transient('get_all_fees', $get_all_fees);
		}

		$product_id = $product->get_id();
	
		// Initialize the total handling charge
		$total_handling_charge = 0;
		$displayPayableAmount = false;
	
		if ( !empty($get_all_fees) ) {

			// Flag to check if the table has been opened
			$table_opened = false;

			foreach ($get_all_fees as $fees) {

                // For Old version plugin compatibility, we have to check object here.
                $fees = is_object( $fees ) && isset( $fees->ID ) ? $fees->ID : $fees;

				if (!empty($sitepress)) {
					$fees_id = apply_filters('wpml_object_id', $fees, 'wc_conditional_fee', true, $default_lang);
				} else {
					$fees_id = $fees;
				}
	
				if (!empty($sitepress)) {
					if (version_compare(ICL_SITEPRESS_VERSION, '3.2', '>=')) {
						$language_information = apply_filters('wpml_post_language_details', null, $fees_id);
					} else {
						$language_information = wpml_get_language_information($fees_id); // @phpstan-ignore-line
					}
	
					if (is_array($language_information) && isset($language_information['language_code'])) {
						$post_id_language_code = $language_information['language_code'];
					} else {
						$post_id_language_code = $default_lang;
					}
				} else {
					$post_id_language_code = $default_lang;
				}

				if ( $post_id_language_code === $default_lang ) {
					$is_passed                    	  = array();
					$final_is_passed_general_rule 	  = array();
					$new_is_passed                	  = array();
					$final_passed                 	  = array();
	
					$fee_title           = get_the_title($fees_id);
					$title               = !empty($fee_title) ? esc_html($fee_title, 'woocommerce-conditional-product-fees-for-checkout') : esc_html('Fee', 'woocommerce-conditional-product-fees-for-checkout');
					$getFeesCostOriginal = get_post_meta($fees_id, 'fee_settings_product_cost', true);
					
					$getFeeType          = get_post_meta($fees_id, 'fee_settings_select_fee_type', true);
		
					if ( isset($woocommerce_wpml) && !empty($woocommerce_wpml->multi_currency) ) {
						if ( !empty($getFeeType) && 'fixed' === $getFeeType ) {
							$getFeesCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount($getFeesCostOriginal);
						} else {
							$getFeesCost = $getFeesCostOriginal;
						}
					} else if ('both' === $getFeeType) {
						$getFeesCost = get_post_meta($fees_id, 'fee_settings_product_cost', true);
					} else {
						$getFeesCost = $getFeesCostOriginal;
					}
					
					$displayFeesSingleProduct   		= get_post_meta( $fees_id, 'display_fees_in_product_page', true );
					$getFeeStartDate 					= get_post_meta( $fees_id, 'fee_settings_start_date', true );
					$getFeeEndDate   					= get_post_meta( $fees_id, 'fee_settings_end_date', true );
					$getFeeStartTime            		= get_post_meta( $fees_id, 'ds_time_from', true );
					$getFeeEndTime              		= get_post_meta( $fees_id, 'ds_time_to', true );
					$getFeeStatus    					= get_post_meta( $fees_id, 'fee_settings_status', true );
					if ( isset( $getFeeStatus ) && 'off' === $getFeeStatus ) {
						continue;
					}
					$fees_cost           = $getFeesCost;
					$get_condition_array = get_post_meta( $fees_id, 'product_fees_metabox', true );
					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {

							$cost_rule_match = get_post_meta( $fees_id, 'cost_rule_match', true );
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
							} else {
								$general_rule_match  = 'all';
							}
							
							$ap_rule_status          = get_post_meta( $fees_id, 'ap_rule_status', true );

                            $product_price = intval( !empty( $product->get_price() ) ? $product->get_price() : 0 );
							if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {
								$fees_cost = ($product_price * $getFeesCost) / 100;
							} else if( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'both' && strpos($getFeesCost, '+') !== false ) {
								$newamount = explode('+', $getFeesCost);
								if (is_numeric($newamount[0]) && is_numeric($newamount[1])) {
									$peramount = ( $product_price * $newamount[0] ) / 100;
									$fees_cost = $peramount + $newamount[1];
								}
							} else {
								$fees_cost = $getFeesCost;
							}
							
						 }
					 } 
									
					if ( isset( $get_condition_array ) && ! empty( $get_condition_array ) ) {
						$product_array    		= array();
						$variableproduct_array 	= array();
						$tag_array        		= array();
						$user_array       		= array();
						if ( wcpffc_fs()->is__premium_only() ) {
							if ( wcpffc_fs()->can_use_premium_code() ) {
                                $brand_array            = array();
                                $wlf_location_array     = array();
						        $category_array        	= array();
								$user_role_array       		= array();
								$weight_array          		= array();
								$attribute_taxonomies  		= wc_get_attribute_taxonomies();
								$atta_name                  = array();
							}
						}

						foreach ( $get_condition_array as $key => $value ) {
							
							if ( array_search( 'product', $value, true ) ) {
								$product_array[ $key ] = $value;
							}
							if ( array_search( 'variableproduct', $value, true ) ) {
								$variableproduct_array[ $key ] = $value;
							}
							if ( array_search( 'tag', $value, true ) ) {
								$tag_array[ $key ] = $value;
							}
							if ( array_search( 'user', $value, true ) ) {
								$user_array[ $key ] = $value;
							}	
							
							if ( wcpffc_fs()->is__premium_only() ) {
								if ( wcpffc_fs()->can_use_premium_code() ) {
									if ( array_search( 'brand', $value, true ) ) {
                                        $brand_array[ $key ] = $value;
                                    }

                                    if ( array_search( 'wlf_location', $value, true ) ) {
                                        $wlf_location_array[ $key ] = $value;
                                    }

                                    if ( array_search( 'category', $value, true ) ) {
                                        $category_array[ $key ] = $value;
                                    }

									if ( array_search( 'user_role', $value, true ) ) {
										$user_role_array[ $key ] = $value;
									}
									
									if ( array_search( 'weight', $value, true ) ) {
										$weight_array[ $key ] = $value;
									}
									
									if ( isset( $attribute_taxonomies ) && !empty( $attribute_taxonomies ) ) {
										foreach ( $attribute_taxonomies as $attribute ) {
											$att_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
											if ( array_search( $att_name, $value, true ) ) {
												$atta_name[ 'att_' . $att_name ] = $value;
											}
										}
									}
								}
							}

							//Check if is product exist
							$cart_product_ids_array = array($product_id);
							if ( isset( $product_array ) && ! empty( $product_array ) && is_array( $product_array ) && ! empty( $cart_product_ids_array ) ) {
								$product_passed = $this->wcpfc_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match );
								if ( 'yes' === $product_passed ) {
									$is_passed['has_fee_based_on_product'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_product'] = 'no';
								}
							}

							//Check if is variable product exist
							if ( isset( $variableproduct_array ) && ! empty( $variableproduct_array ) && is_array( $variableproduct_array ) && ! empty( $cart_product_ids_array ) ) {

                                $current_product_variation_array = $this->wcpfc_get_all_variation_ids( $product_id );
								$variable_prd_passed = $this->wcpfc_pro_match_variable_products_rule( $current_product_variation_array, $variableproduct_array, $general_rule_match );
								if ( 'yes' === $variable_prd_passed ) {
									$is_passed['has_fee_based_on_variable_prd'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_variable_prd'] = 'no';
								}
							}
							
							//Check if is tag exist
							if ( isset( $tag_array ) && ! empty( $tag_array ) && is_array( $tag_array ) && ! empty( $cart_product_ids_array ) ) {
								$tag_passed = $this->wcpfc_pro_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match );
								if ( 'yes' === $tag_passed ) {
									$is_passed['has_fee_based_on_tag'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_tag'] = 'no';
								}
							}
							
							//Check if is user exist
							if ( isset( $user_array ) && ! empty( $user_array ) && is_array( $user_array ) ) {
								$user_passed = $this->wcpfc_pro_match_user_rule( $user_array, $general_rule_match );
								if ( 'yes' === $user_passed ) {
									$is_passed['has_fee_based_on_user'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_user'] = 'no';
								}
							}
							
							if ( wcpffc_fs()->is__premium_only() ) {
								if ( wcpffc_fs()->can_use_premium_code() ) {
									
                                    //Check if is Brand exist
                                    if ( isset( $brand_array ) && ! empty( $brand_array ) && is_array( $brand_array ) && ! empty( $cart_product_ids_array ) ) {
                                        $brand_passed = $this->wcpfc_pro_match_brand_rule__premium_only( $cart_product_ids_array, $brand_array, $general_rule_match );
                                        if ( 'yes' === $brand_passed ) {
                                            $is_passed['has_fee_based_on_brand'] = 'yes';
                                        } else {
                                            $is_passed['has_fee_based_on_brand'] = 'no';
                                        }
                                    }

                                    //Check if is wlf_location exist (Custom Support #104847 - Location based fee)
                                    if ( isset( $wlf_location_array ) && ! empty( $wlf_location_array ) && is_array( $wlf_location_array ) && ! empty( $cart_product_ids_array ) ) {
                                        $wlf_location_passed = $this->wcpfc_pro_match_wlf_location_rule__premium_only( $cart_product_ids_array, $wlf_location_array, $general_rule_match );
                                        if ( 'yes' === $wlf_location_passed ) {
                                            $is_passed['has_fee_based_on_wlf_location'] = 'yes';
                                        } else {
                                            $is_passed['has_fee_based_on_wlf_location'] = 'no';
                                        }
                                    }

                                    //Check if is Category exist
                                    if ( isset( $category_array ) && ! empty( $category_array ) && is_array( $category_array ) && ! empty( $cart_product_ids_array ) ) {
                                        $category_passed = $this->wcpfc_pro_match_category_rule__premium_only( $cart_product_ids_array, $category_array, $general_rule_match );
                                        if ( 'yes' === $category_passed ) {
                                            $is_passed['has_fee_based_on_category'] = 'yes';
                                        } else {
                                            $is_passed['has_fee_based_on_category'] = 'no';
                                        }
                                    }

									//Check if is user role exist
									if ( isset( $user_role_array ) && ! empty( $user_role_array ) && is_array( $user_role_array )) {
										$user_role_passed = $this->wcpfc_pro_match_user_role_rule__premium_only( $user_role_array, $general_rule_match );
										if ( 'yes' === $user_role_passed ) {
											$is_passed['has_fee_based_on_user_role'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_user_role'] = 'no';
										}
									}
									
								}
							}
						}

						if ( isset( $is_passed ) && ! empty( $is_passed ) && is_array( $is_passed ) ) {
							$fnispassed = array();
							foreach ( $is_passed as $val ) {
								if ( '' !== $val ) {
									$fnispassed[] = $val;
								}
							}

							if ( 'all' === $general_rule_match ) {
								if ( in_array( 'no', $fnispassed, true ) ) {
									$final_is_passed_general_rule['passed'] = 'no';
								} else {
									$final_is_passed_general_rule['passed'] = 'yes';
								}
							} else {
								if ( in_array( 'yes', $fnispassed, true ) ) {
									$final_is_passed_general_rule['passed'] = 'yes';
								} else {
									$final_is_passed_general_rule['passed'] = 'no';
								}
							}
						} else {
							$final_is_passed_general_rule['passed'] = 'yes';
						}
					}
					
					if ( empty( $final_is_passed_general_rule ) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
						$new_is_passed['passed'] = 'no';
					} else if ( ! empty( $final_is_passed_general_rule ) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
						$new_is_passed['passed'] = 'no';
					} else if ( empty( $final_is_passed_general_rule ) && in_array( '', $final_is_passed_general_rule, true ) ) {
						$new_is_passed['passed'] = 'no';
					} else if ( ! empty( $final_is_passed_general_rule ) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
						$new_is_passed['passed'] = 'yes';
					}

					if ( in_array( 'no', $new_is_passed, true ) ) {
						$final_passed['passed'] = 'no';
					} else {
						$final_passed['passed'] = 'yes';
					}


					if ( isset( $final_passed ) && ! empty( $final_passed ) && is_array( $final_passed ) ) {
						if ( ! in_array( 'no', $final_passed, true ) ) {
							$displayFeesSingleProduct   		= ( isset( $displayFeesSingleProduct ) && ! empty( $displayFeesSingleProduct ) && 'yes' === $displayFeesSingleProduct ) ? true : false;
							$currentDate  						= strtotime( gmdate( 'd-m-Y' ) );
							$feeStartDate 						= isset( $getFeeStartDate ) && '' !== $getFeeStartDate ? strtotime( $getFeeStartDate ) : '';
							$feeEndDate   						= isset( $getFeeEndDate ) && '' !== $getFeeEndDate ? strtotime( $getFeeEndDate ) : '';
							/*Check for time*/
							$local_nowtimestamp 				= current_time( 'timestamp' );
							$feeStartTime       				= ( isset( $getFeeStartTime ) && ! empty( $getFeeStartTime ) ) ? strtotime( $getFeeStartTime ) : '';
							$feeEndTime         				= ( isset( $getFeeEndTime ) && ! empty( $getFeeEndTime ) ) ? strtotime( $getFeeEndTime ) : '';
							$fees_cost    						= $this->wcpfc_pro_price_format( $fees_cost );
							$today 								=  strtolower( gmdate( "D" ) );
							$ds_select_day_of_week  			= get_post_meta( $fees_id, 'ds_select_day_of_week', true ) ? get_post_meta( $fees_id, 'ds_select_day_of_week', true ) : array();
							
							if ( ( $currentDate >= $feeStartDate || '' === $feeStartDate ) && ( $currentDate <= $feeEndDate || '' === $feeEndDate ) && ( $local_nowtimestamp >= $feeStartTime || '' === $feeStartTime ) && ( $local_nowtimestamp <= $feeEndTime || '' === $feeEndTime ) && ( in_array($today, $ds_select_day_of_week, true) || empty($ds_select_day_of_week) ) ) {
								
								if ( '' !== $fees_cost && isset($displayFeesSingleProduct) && $displayFeesSingleProduct ) {

									// Display the fees in single product page.
									if ( wcpffc_fs()->is__premium_only() ) {
										if ( wcpffc_fs()->can_use_premium_code() ) {
											/* Start Advance Pricing Rules */
											if ( 'on' === $ap_rule_status ) {
												$cost_on_product_status                         = get_post_meta( $fees_id, 'cost_on_product_status', true );
												$cost_on_category_status                        = get_post_meta( $fees_id, 'cost_on_category_status', true );
												$get_condition_array_ap_product                 = get_post_meta( $fees_id, 'sm_metabox_ap_product', true );
												$get_condition_array_ap_category                = get_post_meta( $fees_id, 'sm_metabox_ap_category', true );
												$display_product_ap_rule_in_single_product 		= get_post_meta( $fees_id, 'display_product_ap_rule_in_single_product', true );
												$display_category_ap_rule_in_single_product 	= get_post_meta( $fees_id, 'display_category_ap_rule_in_single_product', true );
											

												// Loop through each condition in $get_condition_array_ap_product
                                                if( !empty( $get_condition_array_ap_product ) ) {
                                                    foreach ($get_condition_array_ap_product as $condition) {
                                                        foreach ($condition['ap_fees_products'] as $fee_product_id) {
                                                            $product = wc_get_product($fee_product_id);

                                                            // Check if the product exists
                                                            if ($product) {
                                                                // Check if the product is a variation
                                                                if ($product->is_type('variation')) {
                                                                    // Get the parent product ID
                                                                    $parent_product_id = $product->get_parent_id();

                                                                    // Check if the parent product ID matches the current product ID
                                                                    if ($parent_product_id === $product_id) {
                                                                        if ('on' === $cost_on_product_status && 'on' === $display_product_ap_rule_in_single_product) {
                                                                            $this->display_advanced_product_pricing_fees($get_condition_array_ap_product, true);
                                                                        }
                                                                        break 2; // Break both loops as the product has been found
                                                                    }
                                                                } else {
                                                                    // For simple products, directly check the product ID
                                                                    if ( (int) $fee_product_id === (int) $product_id) {
                                                                        if ('on' === $cost_on_product_status && 'on' === $display_product_ap_rule_in_single_product) {
                                                                            $this->display_advanced_product_pricing_fees($get_condition_array_ap_product, true);
                                                                        }
                                                                        break 2; // Break both loops as the product has been found
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }


												// Get the product object
												$product = wc_get_product($product_id);

												// Fetch the category IDs for the product
												$product_category_ids = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));

												// Loop through each condition in $get_condition_array_ap_product
                                                if( !empty( $get_condition_array_ap_category ) ) {
                                                    foreach ($get_condition_array_ap_category as $condition) {
                                                        // Check if there is any overlap between product categories and 'ap_fees_category'
                                                        if (!empty($condition['ap_fees_categories']) && array_intersect($product_category_ids, $condition['ap_fees_categories'])) {
                                                            if ( 'on' === $cost_on_category_status ) {
                                                                if ( 'on' === $display_category_ap_rule_in_single_product ) {
                                                                    $this->display_advanced_product_pricing_fees( $get_condition_array_ap_category, true );
                                                                }
                                                            }
                                                            break; // Optional: Stop the loop if you only need to find the first match
                                                        }
                                                    }
                                                }
											
											}

											$displayPayableAmount = true;

											$has_fees = false; // Flag to check if there are any fees

											// Accumulate the handling charge
											$handling_charge = $fees_cost;

											// Check if the handling charge is greater than 0
											if ($handling_charge > 0) {
												$total_handling_charge += $handling_charge;
												$has_fees = true; // Set the flag to true since we have a fee
											}
								
											// Only proceed if there are fees to display
											if ($has_fees) {
												// Format the handling charge with WooCommerce currency formatting
												$formatted_handling_charge = wc_price($handling_charge);
												
												// Open the table only once
												if ( ! $table_opened ) {
													echo '<table class="extra-fee-table">';
													$table_opened = true; // Set the flag to true
												}
												echo '<tr>';
													echo '<td>';
														// Display the basic fee label
														echo esc_html($title);
													echo '</td>';
													echo '<td>';
														// Display the formatted total charge
														echo wp_kses_post( $formatted_handling_charge );
													echo '</td>';
												echo '</tr>';	
											}
										}
									}
								}
							}
							
						} 
					}					

				}
				
			}
	
			if ( '' !== $fees_cost && isset($displayPayableAmount) && $displayPayableAmount ) {
				
				// Get the original product price
                if ( $product->is_type( 'variable' ) ) {
                    // For variable products, get the minimum and maximum prices
                    $min_price = $product->get_variation_price( 'min', true );
                    $max_price = $product->get_variation_price( 'max', true );

                    // Calculate the total charge by adding the handling charges to both min and max prices
                    $min_total_charge = $min_price + $total_handling_charge;
                    $max_total_charge = $max_price + $total_handling_charge;

                    // Format the total charge with WooCommerce currency formatting
                    $formatted_total_charge = wc_price( $min_total_charge ) . ' - ' . wc_price( $max_total_charge );
                } else {
                    // For simple products, get the regular price and calculate the total charge
                    $original_price = intval( !empty( $product->get_price() ) ? $product->get_price() : 0 );
                    $total_charge = $original_price + $total_handling_charge;
                    $formatted_total_charge = wc_price( $total_charge );
                }
				// Only proceed if there are fees to display
				if ($has_fees) {
					echo '<tr>';
						echo '<td>';
							// Display the total payable amount label
							echo esc_html__( 'Total Payable Amount', 'woocommerce-conditional-product-fees-for-checkout' );
						echo '</td>';
						echo '<td>';
							// Display the formatted total charge
							echo wp_kses_post( $formatted_total_charge );
						echo '</td>';
					echo '</tr>';
				}
			}

			// Close the table if it was opened
			if ( $table_opened ) {
				echo '</table>';
			}

			// Finally, render the table after collecting all rows
			$this->display_advanced_product_pricing_fees([], false);
		}
	}

	/**
	 * Display a table of advanced product pricing fees based on a given array of product IDs or category IDs.
	 *
	 * This function takes an array of fee details, checks if the current product matches 
	 * any product ID or belongs to any specified category, and displays the fees in a 
	 * table format with corresponding minimum and maximum quantities and fee amounts.
	 *
	 * @param array $fee_array Array containing fee details for products and categories.
	 */
	public function display_advanced_product_pricing_fees($fee_array, $collect_only = false) {
		// Use a static variable to hold the rows across multiple calls
		static $rows = [];
	
		// Get the current product ID
		$current_product_id = get_the_ID();
		
		$currency_symbol = get_woocommerce_currency_symbol() ?: '';

		// Loop through each fee structure in the array
		foreach ($fee_array as $fee) {
			// Loop through each product ID in the 'ap_fees_products' array
			if (isset($fee['ap_fees_products'])) {

				foreach ($fee['ap_fees_products'] as $fee_product_id) {
					// Get the product object
					$product = wc_get_product($fee_product_id);
	
					if ($product) {
						// Check if the product is a variation and get the parent product ID
						if ($product->is_type('variation')) {
							$parent_product_id = $product->get_parent_id();
						} else {
							$parent_product_id = $fee_product_id; // Simple product, no parent
						}
	
						// Check if the current product ID matches the parent product ID
						if ( (int) $parent_product_id === (int) $current_product_id ) {
							// Format each row with product details
							$min_qty = $fee['ap_fees_ap_prd_min_qty'];
							$max_qty = $fee['ap_fees_ap_prd_max_qty'] ? $fee['ap_fees_ap_prd_max_qty'] : '';
							$fee_amount = $fee['ap_fees_ap_price_product'];
	
							$rows[] = sprintf(
								'<tr><td>%s</td><td>%s</td><td>%s%s</td></tr>',
								esc_html($min_qty),
								esc_html($max_qty),
								esc_html($currency_symbol),
								esc_html($fee_amount)
							);
						}
					}
				}
			}
	
			// Check if the current product belongs to any of the specified categories
			if (isset($fee['ap_fees_categories']) && has_term($fee['ap_fees_categories'], 'product_cat', $current_product_id)) {
				$min_qty = $fee['ap_fees_ap_cat_min_qty'];
				$max_qty = $fee['ap_fees_ap_cat_max_qty'] ? $fee['ap_fees_ap_cat_max_qty'] : '';
				$fee_amount = $fee['ap_fees_ap_price_category'];
	
				// Format each row with category details
				$rows[] = sprintf(
					'<tr><td>%s</td><td>%s</td><td>%s%s</td></tr>',
					esc_html($min_qty),
					esc_html($max_qty),
					esc_html($currency_symbol),
					esc_html($fee_amount)
				);
			}
		}
	
		// If we are collecting rows only, do not render the table yet
		if ($collect_only) {
			return;
		}
	
		// Display the table if rows were added
		if (!empty($rows)) {
			echo '<table class="product-fees-table">';
			echo '<tr><th>' . esc_html__('Min Quantity', 'woocommerce-conditional-product-fees-for-checkout') . '</th>';
			echo '<th>' . esc_html__('Max Quantity', 'woocommerce-conditional-product-fees-for-checkout') . '</th>';
			echo '<th>' . esc_html__('Fee Amount', 'woocommerce-conditional-product-fees-for-checkout') . '</th></tr>';
			// Escape each row before output
			foreach ($rows as $row) {
				echo wp_kses_post($row);
			}
			echo '</table>';
	
			// Reset the static rows after displaying the table
			$rows = [];
		}
	}
	
	

	/**
	 * Add fees in cart based on rule
	 *
	 * @since    1.0.0
	 *
	 * @uses     Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin::wcpfc_pro_get_default_langugae_with_sitpress()
	 * @uses     wcpfc_pro_get_woo_version_number()
	 * @uses     WC_Cart::get_cart()
	 * @uses     wcpfc_pro_fees_per_qty_on_ap_rules_off()
	 * @uses     wcpfc_pro_cart_subtotal_before_discount_cost()
	 * @uses     wcpfc_pro_cart_subtotal_after_discount_cost()
	 * @uses     wcpfc_pro_match_country_rules()
	 * @uses     wcpfc_pro_match_city_rules()
	 * @uses     wcpfc_pro_match_state_rules__premium_only()
	 * @uses     wcpfc_pro_match_postcode_rules__premium_only()
	 * @uses     wcpfc_pro_match_zone_rules__premium_only()
	 * @uses     wcpfc_pro_match_variable_products_rule()
	 * @uses     wcpfc_pro_match_simple_products_rule()
	 * @uses     wcpfc_pro_match_category_rule__premium_only()
	 * @uses     wcpfc_pro_match_tag_rule()
	 * @uses 	 wcpfc_pro_match_product_qty_rule()
	 * @uses     wcpfc_pro_match_user_rule()
	 * @uses     wcpfc_pro_match_user_role_rule__premium_only()
	 * @uses     wcpfc_pro_match_coupon_rule__premium_only()
	 * @uses     wcpfc_pro_match_cart_subtotal_before_discount_rule()
	 * @uses     wcpfc_pro_match_cart_subtotal_after_discount_rule__premium_only()
	 * @uses	 wcpfc_pro_match_cart_subtotal_specific_product_rule__premium_only()
	 * @uses     wcpfc_pro_match_cart_total_cart_qty_rule()
	 * @uses     wcpfc_pro_match_cart_total_weight_rule__premium_only()
	 * @uses     wcpfc_pro_match_shipping_class_rule__premium_only()
	 * @uses     wcpfc_pro_match_payment_gateway_rule__premium_only()
	 * @uses     wcpfc_pro_match_shipping_method_rule__premium_only()
	 * @uses     wcpfc_pro_match_product_per_qty__premium_only()
	 * @uses     wcpfc_pro_match_category_per_qty__premium_only()
	 * @uses     wcpfc_pro_match_total_cart_qty__premium_only()
	 * @uses     wcpfc_pro_match_product_per_weight__premium_only()
	 * @uses     wcpfc_pro_match_category_per_weight__premium_only()
	 * @uses     wcpfc_pro_match_total_cart_weight__premium_only()
	 * @uses     wcpfc_pro_calculate_advance_pricing_rule_fees()
	 *
	 */
	public function wcpfc_pro_conditional_fee_add_to_cart( $cart ) {
		global $woocommerce_wpml, $sitepress, $woocommerce;

		$wcpfc_checkout_data = filter_input( INPUT_POST, 'post_data', FILTER_CALLBACK, array('options' => array( $this, 'wcpfc_filter_sanitize_string' ) ) );

		if ( isset( $wcpfc_checkout_data ) ) {
	        parse_str( $wcpfc_checkout_data, $post_data );
	    } else {
	        $post_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	    }

		$default_lang = self::$admin_object->wcpfc_pro_get_default_langugae_with_sitpress();
		
		$get_all_fees = get_transient( 'get_all_fees' );
		if ( false === $get_all_fees ) {
			$fees_args    = array(
				'post_type'        	=> 'wc_conditional_fee',
				'post_status'      	=> 'publish',
				'posts_per_page'   	=> - 1,
				'suppress_filters' 	=> false,
				'fields'        	=> 'ids',
				'order'          	=> 'DESC',
				'orderby'        	=> 'ID',
			);
			$get_all_fees_query = new WP_Query( $fees_args );
			$get_all_fees       = $get_all_fees_query->get_posts();
			set_transient( 'get_all_fees', $get_all_fees );
		}
		
		$wc_curr_version             = $this->wcpfc_pro_get_woo_version_number();
		$cart_array                  = $this->wcpfc_pro_get_cart();
		$cart_main_product_ids_array = $this->wcpfc_pro_get_main_prd_id( $sitepress, $default_lang );
		$cart_product_ids_array      = $this->wcpfc_pro_get_prd_var_id( $sitepress, $default_lang );
        
        /**
         * We have commented below line because we are already getting cart object in function parameter.and that give us updated price of cart subtotal.
         * and WC()->cart->cart_contents_total is not giving updated price of cart subtotal.
         */
		$cart_sub_total              = $cart->cart_contents_total;
		$total_fee                   = 0;
		$chk_enable_custom_fun       = get_option( 'chk_enable_custom_fun' );
		$getFeesOptional             = '';
		$general_rule_match			 = '';
		$total_cart_qty_n_combination = array();

		// Query start for tax calculation based on the selected class from products
		$items = $woocommerce->cart->get_cart(); // Get the cart items
		$item_tax_class = array();
		if ( !empty( $items ) && is_array( $items ) ) {
			foreach ($items as $item) {
		        $product = $item['data']; // Get the product object
		        $item_tax_class[] = $product->get_tax_class(); // Get the tax class
		    }
		}
		$items_tax_classes = !empty($item_tax_class) && is_array($item_tax_class) ? $item_tax_class : array();

		$new_item_tax_class = array();
		if ( empty( $items_tax_classes ) ) {
			$new_item_tax_class = array();
		} else {
			$new_item_tax_class = array_unique($items_tax_classes);
		}

		$all_wc_tax_class = array();
		$wc_tax_classes = WC_Tax::get_tax_rate_classes();
		if ( !empty( $wc_tax_classes ) && is_array( $wc_tax_classes ) ) {
			foreach( $wc_tax_classes as $wc_tax_class ) {
				$all_wc_tax_class[] = $wc_tax_class->slug;
			}
		}

		$final_item_tax_class = '';
		if ( in_array( '', $new_item_tax_class, true ) ) {
			$final_item_tax_class = '';
		} else {
			if ( !empty( $new_item_tax_class ) && is_array( $new_item_tax_class ) ) {
				foreach( $new_item_tax_class as $new_item_tax ) {
					if ( in_array( $new_item_tax, $all_wc_tax_class, true ) ) {
						$final_item_tax_class = $new_item_tax;
					}
				}
			}
		}

		// Query end for tax calculation based on the selected class from products

		if ( isset( $get_all_fees ) && ! empty( $get_all_fees ) ) {
            
			foreach ( $get_all_fees as $fees ) {

                // For Old version plugin compatibility, we have to check object here.
                $fees = is_object( $fees ) && isset( $fees->ID ) ? $fees->ID : $fees;
				
                if ( ! empty( $sitepress ) ) {
					$fees_id = apply_filters( 'wpml_object_id', $fees, 'wc_conditional_fee', true, $default_lang );
				} else {
					$fees_id = $fees;
				}

				$optional_fee_array = ( isset($post_data['wef_fees_id_array_'.$fees_id]) && !empty($post_data['wef_fees_id_array_'.$fees_id]) ) ? array_map('intval', $post_data['wef_fees_id_array_'.$fees_id] ) : array();

                // Code for optional fee compatibility with FunnelKit plugins
				if ( isset($post_data['_wfacp_post_id']) && !empty($post_data['_wfacp_post_id']) ) {
					$optional_fee_array = ( isset($post_data['wef_fees_id_array_'.$fees_id]) && !empty($post_data['wef_fees_id_array_'.$fees_id]) ) ? array( intval( $fees_id ) ) : array();
				}

				if ( wcpffc_fs()->is__premium_only() ) {
					if ( wcpffc_fs()->can_use_premium_code() ) {
						// Fetch the tax class type for the current fee
						$taxClassType = get_post_meta( $fees_id, 'fee_settings_taxable_type', true );

						// Check if the tax class type is different from 'standard'
						if ( ! empty( $taxClassType ) ) {
							// Update $final_item_tax_class based on the condition
							$final_item_tax_class = ( 'standard' !== $final_item_tax_class ) ? $taxClassType : $final_item_tax_class;
						}
					}
				}

				if ( ! empty( $sitepress ) ) {
					if ( version_compare( ICL_SITEPRESS_VERSION, '3.2', '>=' ) ) {
						$language_information = apply_filters( 'wpml_post_language_details', null, $fees_id );
					} else {
						$language_information = wpml_get_language_information( $fees_id ); // @phpstan-ignore-line
					}
					if( is_array( $language_information ) && isset( $language_information['language_code'] ) ){
						$post_id_language_code = $language_information['language_code'];
					} else {
						$post_id_language_code = $default_lang;
					}
				} else {
					$post_id_language_code = $default_lang;
				}
				if ( $post_id_language_code === $default_lang ) {
					$is_passed                    	  = array();
					$final_is_passed_general_rule 	  = array();
					$new_is_passed                	  = array();
					$final_passed                 	  = array();
					$cart_based_qty               	  = 0;
					$cart_based_weight				  = 0;
                    $cart_items_based_count			  = count($items);
					$apply_rule_for_optional 	  	  = false;
					$display_optional_fee_on_checkout = 'on';
                    
					if( in_array( $fees_id, $optional_fee_array, true) ){
						$apply_rule_for_optional = true;
					}

					if ( isset( $cart_array) && !empty( $cart_array ) ) {
						foreach ( $cart_array as $woo_cart_item_for_qty ) {
							$product_id = $woo_cart_item_for_qty['product_id'];
							$product_type = WC_Product_Factory::get_product_type($product_id);
							if( "bundle" === $product_type ){
								continue;
							}
							if( !empty($woo_cart_item_for_qty['data']->get_weight()) ){
								$cart_based_weight += $woo_cart_item_for_qty['quantity'] * $woo_cart_item_for_qty['data']->get_weight();
							}
							$cart_based_qty += $woo_cart_item_for_qty['quantity'];
						}	
					}
					$fee_title           = get_the_title( $fees_id );
					$title               = ! empty( $fee_title ) ? esc_html( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ) : esc_html( 'Fee', 'woocommerce-conditional-product-fees-for-checkout' );
					$getFeesCostOriginal = get_post_meta( $fees_id, 'fee_settings_product_cost', true );
					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {
							$getFeesCostOriginal = $this->wcpfc_evaluate_cost__premium_only( $getFeesCostOriginal, array( $cart_based_qty, $cart_sub_total, $cart_based_weight ) );
						}
					}
					$getFeeType          = get_post_meta( $fees_id, 'fee_settings_select_fee_type', true );
					if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
						if ( ! empty( $getFeeType ) && 'fixed' === $getFeeType ) {
							$getFeesCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $getFeesCostOriginal );
						} else {    
							$getFeesCost = $getFeesCostOriginal;
						}
					} else if( 'both' === $getFeeType ) {
                        // Refetch it here because 'wcpfc_evaluate_cost__premium_only' function above make it sum.
						$getFeesCost = get_post_meta( $fees_id, 'fee_settings_product_cost', true );
					} else {
						$getFeesCost = $getFeesCostOriginal;
					}
					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {
							$getFeesPerQtyFlag        	= get_post_meta( $fees_id, 'fee_chk_qty_price', true );
							$getFeesPerQty            	= get_post_meta( $fees_id, 'fee_per_qty', true );
							$extraProductCostOriginal 	= get_post_meta( $fees_id, 'extra_product_cost', true );
							
                            // Need to check for any dynamic notation in the extra product cost
                            $extraProductCostOriginal = $this->wcpfc_evaluate_cost__premium_only( $extraProductCostOriginal );

							if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
								$extraProductCost = $woocommerce_wpml->multi_currency->prices->convert_price_amount( $extraProductCostOriginal );
							} else {
                                // Convert the extra product cost to the selected currency using CURCY plugin
                                $extraProductCost = $this->wcpfc_pro_convert_currency($extraProductCostOriginal);
							}
							$getFirstOrderForUser   	= get_post_meta( $fees_id, 'first_order_for_user', true );
							$firstOrderForUser   		= ( isset( $getFirstOrderForUser ) && ! empty( $getFirstOrderForUser ) && 'on' === $getFirstOrderForUser ) ? true : false;
							if( $firstOrderForUser && is_user_logged_in() ){
								$current_user_id = get_current_user_id();
								$check_for_user = $this->wcpfc_check_first_order_for_user__premium_only( $current_user_id );
								if( !$check_for_user ){
									update_post_meta( $fees_id, '_wcpfc_display_optional_fee_on_checkout', 'off' );
									continue;
								}
							}
						}
					}
					$getFeetaxable   			= get_post_meta( $fees_id, 'fee_settings_select_taxable', true );
					$getFeeStartDate 			= get_post_meta( $fees_id, 'fee_settings_start_date', true );
					$getFeeEndDate   			= get_post_meta( $fees_id, 'fee_settings_end_date', true );
					$getFeeStartTime            = get_post_meta( $fees_id, 'ds_time_from', true );
					$getFeeEndTime              = get_post_meta( $fees_id, 'ds_time_to', true );
					$getFeeStatus    			= get_post_meta( $fees_id, 'fee_settings_status', true );
					if ( isset( $getFeeStatus ) && 'off' === $getFeeStatus ) {
						continue;
					}
					$fees_cost           = $getFeesCost;
					$get_condition_array = get_post_meta( $fees_id, 'product_fees_metabox', true );
					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {
							$cost_rule_match = get_post_meta( $fees_id, 'cost_rule_match', true );
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
							
							$ap_rule_status          = get_post_meta( $fees_id, 'ap_rule_status', true );
							$fees_on_cart_total 	 = get_post_meta( $fees_id, 'fees_on_cart_total', true );
							$products_based_qty      = 0;
							$products_based_subtotal = 0;
							if( 'on' === $fees_on_cart_total ) {
								if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {
									$cart_total = $this->wcpfc_cart_total();
									$fees_cost = ( $cart_total * $getFeesCost ) / 100;
								} else if( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'both' && strpos($getFeesCost, '+') !== false  ) {
										$newamount = explode('+', $getFeesCost);
										if (is_numeric($newamount[0]) && is_numeric($newamount[1])) {
											
                                            $cart_total = $this->wcpfc_cart_total();

											$peramount = ( $cart_total * $newamount[0] ) / 100;
                                            $newamount[1] = $this->wcpfc_pro_convert_currency($newamount[1]);

											$fees_cost = $peramount + $newamount[1];
										}
								} else {
									$fees_cost = $getFeesCost;
								}
							} else {
								//add new condition for apply per quantity only apply if advanced pricing rule disabled
								if ( 'on' === $getFeesPerQtyFlag && 'on' !== $ap_rule_status ) {
									$products_based_rule = $this->wcpfc_pro_fees_per_qty_on_ap_rules_off( $fees_id, $cart_array, $products_based_qty, $products_based_subtotal, $sitepress, $default_lang, $general_rule_match );
                                    
									if ( ! empty( $products_based_rule ) ) {
										if ( array_key_exists( '0', $products_based_rule ) ) {
											$products_based_qty = $products_based_rule[0];
										}
										if ( array_key_exists( '1', $products_based_rule ) ) {
											$products_based_subtotal = $products_based_rule[1];
										}
									}
									
                                    if( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'both' && strpos($getFeesCost, '+') !== false  ) {
										$newamount = explode('+', $getFeesCost);
										if ( is_numeric($newamount[0]) && is_numeric($newamount[1]) ) {
                                            if ( 'qty_product_based' === $getFeesPerQty ) {
                                                $peramount = ( $products_based_subtotal * $newamount[0] ) / 100;
                                            } else {
                                                $peramount = ( $cart_sub_total * $newamount[0] ) / 100;
                                            }

                                            $newamount[1] = $this->wcpfc_pro_convert_currency($newamount[1]);

											$getFeesCost = $peramount + $newamount[1];
										}
									}

									if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {
                                        if ( 'qty_product_based' === $getFeesPerQty ) {
                                            $getFeesCost = ( $products_based_subtotal * $getFeesCost ) / 100;
                                        } else {
                                            $getFeesCost = ( $cart_sub_total * $getFeesCost ) / 100;
                                        }
									}
                                    
									if ( 'qty_cart_based' === $getFeesPerQty ) {
										$fees_cost = $getFeesCost + ( ( $cart_based_qty - 1 ) * $extraProductCost );
									} else if ( 'qty_product_based' === $getFeesPerQty ) {
										$fees_cost = $getFeesCost + ( ( $products_based_qty - 1 ) * $extraProductCost );
									} else if ( 'count_cart_based' === $getFeesPerQty ) {
										$fees_cost = $getFeesCost + ( ( $cart_items_based_count - 1 ) * $extraProductCost );
									}
									// Per Qty Condition end
								} else {
									if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {
										$fees_cost = ( $cart_sub_total * $getFeesCost ) / 100;
									} else if( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'both' && strpos($getFeesCost, '+') !== false ) {
										$newamount = explode('+', $getFeesCost);
										if (is_numeric($newamount[0]) && is_numeric($newamount[1])) {

											$peramount = ( $cart_sub_total * $newamount[0] ) / 100;
                                            $newamount[1] = $this->wcpfc_pro_convert_currency($newamount[1]);
											$fees_cost = $peramount + $newamount[1];
										}
									} else {
										$fees_cost = $getFeesCost;
									}
								}
							}
						} else {
							$general_rule_match = 'all';
							$fees_on_cart_total 	 = get_post_meta( $fees_id, 'fees_on_cart_total', true );
							if( 'on' === $fees_on_cart_total ) {
								$cart_sub_total = $this->wcpfc_cart_total();
							}
							if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {
								$fees_cost = ( $cart_sub_total * $getFeesCost ) / 100;
							} else if( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'both' && strpos($getFeesCost, '+') !== false ) {
								$newamount = explode('+', $getFeesCost);
								if (is_numeric($newamount[0]) && is_numeric($newamount[1])) {

									$peramount = ( $cart_sub_total * $newamount[0] ) / 100;
                                    $newamount[1] = $this->wcpfc_pro_convert_currency($newamount[1]);
									$fees_cost = $peramount + $newamount[1];
								}
							} else {
								$fees_cost = $getFeesCost;
							}
						}
					} else {
						$general_rule_match = 'all';
						$fees_on_cart_total 	 = get_post_meta( $fees_id, 'fees_on_cart_total', true );
						if( 'on' === $fees_on_cart_total ) {
							$cart_sub_total = $this->wcpfc_cart_total();
						}
						if ( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'percentage' ) {
							$fees_cost = ( $cart_sub_total * $getFeesCost ) / 100;
						} else if( isset( $getFeeType ) && ! empty( $getFeeType ) && $getFeeType === 'both' && strpos($getFeesCost, '+') !== false ) {
							$newamount = explode('+', $getFeesCost);
							if (is_numeric($newamount[0]) && is_numeric($newamount[1])) {

								$peramount = ( $cart_sub_total * $newamount[0] ) / 100;
                                $newamount[1] = $this->wcpfc_pro_convert_currency($newamount[1]);
								$fees_cost = $peramount + $newamount[1];
							}
						} else {
							$fees_cost = $getFeesCost;
						}
					}
                    
					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {
							/*** allow each weight rule ***/
							$is_allow_custom_weight_base = get_post_meta( $fees_id, 'is_allow_custom_weight_base', true );
							if("on" === $is_allow_custom_weight_base){

								$total_cart_weights = WC()->cart->get_cart_contents_weight();

								$sm_custom_weight_base_cost = get_post_meta( $fees_id, 'sm_custom_weight_base_cost', true );
								$sm_custom_weight_base_per_each = get_post_meta( $fees_id, 'sm_custom_weight_base_per_each', true );
								$sm_custom_weight_base_over = get_post_meta( $fees_id, 'sm_custom_weight_base_over', true );
								$sm_custom_weight_base_cost_shipping = 0;
								if( ($total_cart_weights > 0) && ($total_cart_weights >= $sm_custom_weight_base_per_each) ){
									if( '' !== $sm_custom_weight_base_over ){
										if( $total_cart_weights >= $sm_custom_weight_base_over ){
											$total_cart_weights = ($total_cart_weights - $sm_custom_weight_base_over);
											$sm_custom_weight_base_cost_part = (int)( $total_cart_weights / $sm_custom_weight_base_per_each );
											$sm_custom_weight_base_cost_shipping = ( $sm_custom_weight_base_cost * $sm_custom_weight_base_cost_part );
										}
									}else{
										$sm_custom_weight_base_cost_part = (int)( $total_cart_weights / $sm_custom_weight_base_per_each );
										$sm_custom_weight_base_cost_shipping = ( $sm_custom_weight_base_cost * $sm_custom_weight_base_cost_part );
									}
									$fees_cost += $sm_custom_weight_base_cost_shipping;
								}
							}
						}
					}
					
					if ( isset( $get_condition_array ) && ! empty( $get_condition_array ) ) {
						$country_array    		= array();
						$city_array		  		= array();
						$product_array    		= array();
						$tag_array        		= array();
						$user_array       		= array();
						$cart_total_array 		= array();
						$quantity_array   		= array();
						$variableproduct_array 	= array();
						$product_qty_array     	= array();
						if ( wcpffc_fs()->is__premium_only() ) {
                            if ( wcpffc_fs()->can_use_premium_code() ) {
                                $state_array           		  = array();
								$postcode_array        		  = array();
								$zone_array            		  = array();
                                $brand_array       		      = array();
                                $wlf_location_array       	  = array();
                                $category_array        	      = array();
								$user_role_array       		  = array();
								$total_spent_order_array      = array();
								$spent_order_count_array      = array();
								$last_spent_order_array       = array();
								$cart_totalafter_array 		  = array();
								$cart_specificproduct_array   = array();
								$cart_totalexclude_tax_array  = array();
								$cart_rowtotal_array  		  = array();
								$weight_array          		  = array();
								$coupon_array          		  = array();
								$shipping_class_array  		  = array();
								$payment_gateway       		  = array();
								$shipping_methods      		  = array();
                                $attribute_taxonomies  		  = wc_get_attribute_taxonomies();
                                $atta_name                    = array();
							}
						}
						foreach ( $get_condition_array as $key => $value ) {
							if ( array_search( 'country', $value, true ) ) {
								$country_array[ $key ] = $value;
							}
							if ( array_search( 'city', $value, true ) ) {
								$city_array[ $key ] = $value;
							}
							if ( array_search( 'product', $value, true ) ) {
								$product_array[ $key ] = $value;
							}
							if ( array_search( 'variableproduct', $value, true ) ) {
								$variableproduct_array[ $key ] = $value;
							}
							if ( array_search( 'tag', $value, true ) ) {
								$tag_array[ $key ] = $value;
							}
							if ( array_search( 'product_qty', $value, true ) ) {
								$product_qty_array[ $key ] = $value;
							}
							if ( array_search( 'user', $value, true ) ) {
								$user_array[ $key ] = $value;
							}
							if ( array_search( 'cart_total', $value, true ) ) {
								$cart_total_array[ $key ] = $value;
							}
							if ( array_search( 'quantity', $value, true ) ) {
								$quantity_array[ $key ] = $value;
							}
							if ( wcpffc_fs()->is__premium_only() ) {
								if ( wcpffc_fs()->can_use_premium_code() ) {
                                    if ( array_search( 'brand', $value, true ) ) {
                                        $brand_array[ $key ] = $value;
                                    }
                                    if ( array_search( 'wlf_location', $value, true ) ) {
                                        $wlf_location_array[ $key ] = $value;
                                    }
                                    if ( array_search( 'category', $value, true ) ) {
                                        $category_array[ $key ] = $value;
                                    }
									if ( array_search( 'state', $value, true ) ) {
										$state_array[ $key ] = $value;
									}
									if ( array_search( 'postcode', $value, true ) ) {
										$postcode_array[ $key ] = $value;
									}
									if ( array_search( 'zone', $value, true ) ) {
										$zone_array[ $key ] = $value;
									}
									if ( array_search( 'user_role', $value, true ) ) {
										$user_role_array[ $key ] = $value;
									}
									if ( array_search( 'total_spent_order', $value,true ) ) {
										$total_spent_order_array[ $key ] = $value;
									}
									if ( array_search( 'spent_order_count', $value,true ) ) {
										$spent_order_count_array[ $key ] = $value;
									}
									if ( array_search( 'last_spent_order', $value,true ) ) {
										$last_spent_order_array[ $key ] = $value;
									}
									if ( array_search( 'cart_totalafter', $value, true ) ) {
										$cart_totalafter_array[ $key ] = $value;
									}
									if ( array_search( 'cart_specificproduct', $value, true ) ) {
										$cart_specificproduct_array[ $key ] = $value;
									}
									if ( array_search( 'cart_totalexclude_tax', $value, true ) ) {
										$cart_totalexclude_tax_array[ $key ] = $value;
									}
									if ( array_search( 'cart_rowtotal', $value, true ) ) {
										$cart_rowtotal_array[ $key ] = $value;
									}
									if ( array_search( 'weight', $value, true ) ) {
										$weight_array[ $key ] = $value;
									}
									if ( array_search( 'coupon', $value, true ) ) {
										$coupon_array[ $key ] = $value;
									}
									if ( array_search( 'shipping_class', $value, true ) ) {
										$shipping_class_array[ $key ] = $value;
									}
									if ( array_search( 'payment', $value, true ) ) {
										$payment_gateway[ $key ] = $value;
									}
									if ( array_search( 'shipping_method', $value, true ) ) {
										$shipping_methods[ $key ] = $value;
									}
                                    if ( isset( $attribute_taxonomies ) && !empty( $attribute_taxonomies ) ) {
                                        foreach ( $attribute_taxonomies as $attribute ) {
                                            $att_name = wc_attribute_taxonomy_name( $attribute->attribute_name );
                                            if ( array_search( $att_name, $value, true ) ) {
                                                $atta_name[ 'att_' . $att_name ] = $value;
                                            }
                                        }
                                    }
								}
							}
                            
							//Check if is country exist
							if ( isset( $country_array ) && ! empty( $country_array ) && is_array( $country_array ) && ! empty( $cart_array ) ) {
								$country_passed = $this->wcpfc_pro_match_country_rules( $country_array, $general_rule_match );
								if ( 'yes' === $country_passed ) {
									$is_passed['has_fee_based_on_country'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_country'] = 'no';
								}
							}
							//Check if is city exist
							if ( isset( $city_array ) && ! empty( $city_array ) && is_array( $city_array ) && ! empty( $cart_array ) ) {
								$city_passed = $this->wcpfc_pro_match_city_rules( $city_array, $general_rule_match );
								if ( 'yes' === $city_passed ) {
									$is_passed['has_fee_based_on_city'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_city'] = 'no';
								}
							}
							//Check if is product exist
							if ( isset( $product_array ) && ! empty( $product_array ) && is_array( $product_array ) && ! empty( $cart_product_ids_array ) ) {
								$product_passed = $this->wcpfc_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match );
								if ( 'yes' === $product_passed ) {
									$is_passed['has_fee_based_on_product'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_product'] = 'no';
								}
							}
							//Check if is variable product exist
							if ( isset( $variableproduct_array ) && ! empty( $variableproduct_array ) && is_array( $variableproduct_array ) && ! empty( $cart_product_ids_array ) ) {
								$variable_prd_passed = $this->wcpfc_pro_match_variable_products_rule( $cart_product_ids_array, $variableproduct_array, $general_rule_match );
								if ( 'yes' === $variable_prd_passed ) {
									$is_passed['has_fee_based_on_variable_prd'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_variable_prd'] = 'no';
								}
							}
							//Check if is tag exist
							if ( isset( $tag_array ) && ! empty( $tag_array ) && is_array( $tag_array ) && ! empty( $cart_main_product_ids_array ) ) {
								$tag_passed = $this->wcpfc_pro_match_tag_rule( $cart_main_product_ids_array, $tag_array, $general_rule_match );
								if ( 'yes' === $tag_passed ) {
									$is_passed['has_fee_based_on_tag'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_tag'] = 'no';
								}
							}
							//Check if product quantity exist
							if ( isset( $product_qty_array ) && ! empty( $product_qty_array ) && is_array( $product_qty_array ) && ! empty( $cart_product_ids_array ) ) {
								$product_qty_passed = $this->wcpfc_pro_match_product_qty_rule( $fees_id, $cart_array, $product_qty_array, $general_rule_match, $sitepress, $default_lang );
								
								if ( 'yes' === $product_qty_passed ) {
									$is_passed['has_fee_based_on_product_qty'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_product_qty'] = 'no';
								}
							}
							//Check if is user exist
							if ( isset( $user_array ) && ! empty( $user_array ) && is_array( $user_array ) && ! empty( $cart_array ) ) {
								$user_passed = $this->wcpfc_pro_match_user_rule( $user_array, $general_rule_match );
								if ( 'yes' === $user_passed ) {
									$is_passed['has_fee_based_on_user'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_user'] = 'no';
								}
							}
							//Check if is Cart Subtotal (Before Discount) exist
							if ( isset( $cart_total_array ) && ! empty( $cart_total_array ) && is_array( $cart_total_array ) && ! empty( $cart_array ) ) {
								$cart_total_before_passed = $this->wcpfc_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match );
								if ( 'yes' === $cart_total_before_passed ) {
									$is_passed['has_fee_based_on_cart_total_before'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_cart_total_before'] = 'no';
								}
							}
							//Check if is quantity exist
							if ( isset( $quantity_array ) && ! empty( $quantity_array ) && is_array( $quantity_array ) && ! empty( $cart_array ) ) {
								$quantity_passed = $this->wcpfc_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match );
								if ( 'yes' === $quantity_passed ) {
									$is_passed['has_fee_based_on_quantity'] = 'yes';
								} else {
									$is_passed['has_fee_based_on_quantity'] = 'no';
								}
							}
							if ( wcpffc_fs()->is__premium_only() ) {
								if ( wcpffc_fs()->can_use_premium_code() ) {
                                    //Check if is Brand exist
                                    if ( isset( $brand_array ) && ! empty( $brand_array ) && is_array( $brand_array ) && ! empty( $cart_main_product_ids_array ) ) {
                                        $brand_passed = $this->wcpfc_pro_match_brand_rule__premium_only( $cart_main_product_ids_array, $brand_array, $general_rule_match );
                                        if ( 'yes' === $brand_passed ) {
                                            $is_passed['has_fee_based_on_brand'] = 'yes';
                                        } else {
                                            $is_passed['has_fee_based_on_brand'] = 'no';
                                        }
                                    }
                                    //Check if is wlf_location exist (Custom Support #104847 - Location based fee)
                                    if ( isset( $wlf_location_array ) && ! empty( $wlf_location_array ) && is_array( $wlf_location_array ) && ! empty( $cart_main_product_ids_array ) ) {
                                        $wlf_location_passed = $this->wcpfc_pro_match_wlf_location_rule__premium_only( $cart_main_product_ids_array, $wlf_location_array, $general_rule_match );
                                        if ( 'yes' === $wlf_location_passed ) {
                                            $is_passed['has_fee_based_on_wlf_location'] = 'yes';
                                        } else {
                                            $is_passed['has_fee_based_on_wlf_location'] = 'no';
                                        }
                                    }
                                    //Check if is Category exist
                                    if ( isset( $category_array ) && ! empty( $category_array ) && is_array( $category_array ) && ! empty( $cart_main_product_ids_array ) ) {
                                        $category_passed = $this->wcpfc_pro_match_category_rule__premium_only( $cart_main_product_ids_array, $category_array, $general_rule_match );
                                        if ( 'yes' === $category_passed ) {
                                            $is_passed['has_fee_based_on_category'] = 'yes';
                                        } else {
                                            $is_passed['has_fee_based_on_category'] = 'no';
                                        }
                                    }
									//Check if is state exist
									if ( isset( $state_array ) && ! empty( $state_array ) && is_array( $state_array ) && ! empty( $cart_array ) ) {
										$state_passed = $this->wcpfc_pro_match_state_rules__premium_only( $state_array, $general_rule_match );
										if ( 'yes' === $state_passed ) {
											$is_passed['has_fee_based_on_state'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_state'] = 'no';
										}
									}
									//Check if is postcode exist
									if ( isset( $postcode_array ) && ! empty( $postcode_array ) && is_array( $postcode_array ) && ! empty( $cart_array ) ) {
										$postcode_passed = $this->wcpfc_pro_match_postcode_rules__premium_only( $postcode_array, $general_rule_match );
										if ( 'yes' === $postcode_passed ) {
											$is_passed['has_fee_based_on_postcode'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_postcode'] = 'no';
										}
									}
									//Check if is zone exist
									if ( isset( $zone_array ) && ! empty( $zone_array ) && is_array( $zone_array ) && ! empty( $cart_array ) ) {
										$zone_passed = $this->wcpfc_pro_match_zone_rules__premium_only( $zone_array, $general_rule_match );
										if ( 'yes' === $zone_passed ) {
											$is_passed['has_fee_based_on_zone'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_zone'] = 'no';
										}
									}
									//Check if is user role exist
									if ( isset( $user_role_array ) && ! empty( $user_role_array ) && is_array( $user_role_array ) && ! empty( $cart_array ) ) {
										$user_role_passed = $this->wcpfc_pro_match_user_role_rule__premium_only( $user_role_array, $general_rule_match );
										if ( 'yes' === $user_role_passed ) {
											$is_passed['has_fee_based_on_user_role'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_user_role'] = 'no';
										}
									}

									/**
									 * Purchase History Start
									 */
								
									//Check if is Total order spent exist
									if ( is_array( $total_spent_order_array ) && isset( $total_spent_order_array ) && ! empty( $total_spent_order_array ) && ! empty( $cart_array ) && is_user_logged_in() ) {
										$total_spent_order_passed = $this->wcpfc_pro_match_total_spent_order_rule__premium_only( $total_spent_order_array, $general_rule_match );
										if ( 'yes' === $total_spent_order_passed ) {
											$is_passed['has_fee_based_on_total_spent_order'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_total_spent_order'] = 'no';
										}
									}

									//Check if is Total order count exist
									if ( is_array( $spent_order_count_array ) && isset( $spent_order_count_array ) && ! empty( $spent_order_count_array ) && ! empty( $cart_array ) && is_user_logged_in() ) {
										$spent_order_count_passed = $this->wcpfc_pro_match_spent_order_count_rule__premium_only( $spent_order_count_array, $general_rule_match );
										if ( 'yes' === $spent_order_count_passed ) {
											$is_passed['has_fee_based_on_spent_order_count'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_spent_order_count'] = 'no';
										}
									}

									//Check if is Last order spent exist
									if ( is_array( $last_spent_order_array ) && isset( $last_spent_order_array ) && ! empty( $last_spent_order_array ) && ! empty( $cart_array ) && is_user_logged_in() ) {
										$last_spent_order_passed = $this->wcpfc_pro_match_last_spent_order_rule__premium_only( $last_spent_order_array, $general_rule_match );
										if ( 'yes' === $last_spent_order_passed ) {
											$is_passed['has_fee_based_on_last_spent_order'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_last_spent_order'] = 'no';
										}
									}
									
									/**
									 * Purchase History End
									 */

									//Check if is coupon exist
									if ( isset( $coupon_array ) && ! empty( $coupon_array ) && is_array( $coupon_array ) && ! empty( $cart_array ) ) {
										$coupon_passed = $this->wcpfc_pro_match_coupon_rule__premium_only( $wc_curr_version, $coupon_array, $general_rule_match );
										if ( 'yes' === $coupon_passed ) {
											$is_passed['has_fee_based_on_coupon'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_coupon'] = 'no';
										}
									}
									//Check if is Cart Subtotal (After Discount) exist
									if ( isset( $cart_totalafter_array ) && ! empty( $cart_totalafter_array ) && is_array( $cart_totalafter_array ) && ! empty( $cart_array ) ) {
										$cart_total_after_passed = $this->wcpfc_pro_match_cart_subtotal_after_discount_rule__premium_only( $wc_curr_version, $cart_totalafter_array, $general_rule_match );
										if ( 'yes' === $cart_total_after_passed ) {
											$is_passed['has_fee_based_on_cart_total_after'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_cart_total_after'] = 'no';
										}
									}
									//Check if is Cart Subtotal (Specific products) exist
									if ( isset( $cart_specificproduct_array ) && ! empty( $cart_specificproduct_array ) && is_array( $cart_specificproduct_array ) && ! empty( $cart_array ) ) {

                                        $products_based_counts = $this->wcpfc_pro_fees_per_qty_on_ap_rules_off( $fees_id, $cart_array, 0, 0, $sitepress, $default_lang, $general_rule_match );
										$cart_specific_product_passed = $this->wcpfc_pro_match_cart_subtotal_specific_product_rule__premium_only( $wc_curr_version, $cart_specificproduct_array, $general_rule_match, $products_based_counts );
										if ( 'yes' === $cart_specific_product_passed ) {
											$is_passed['has_fee_based_on_cart_specific_product'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_cart_specific_product'] = 'no';
										}
									}

									//Check if is Cart Subtotal excluding tax
									if ( isset( $cart_totalexclude_tax_array ) && ! empty( $cart_totalexclude_tax_array ) && is_array( $cart_totalexclude_tax_array ) && ! empty( $cart_array ) ) {
										$cart_total_exclude_tax_passed = $this->wcpfc_pro_match_cart_subtotal_excluding_tax_rule__premium_only( $wc_curr_version, $cart_totalexclude_tax_array, $general_rule_match );
										if ( 'yes' === $cart_total_exclude_tax_passed ) {
											$is_passed['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_cart_total_excluding_tax'] = 'no';
										}
									}

									//Check if is Cart Row Total
									if ( isset( $cart_rowtotal_array ) && ! empty( $cart_rowtotal_array ) && is_array( $cart_rowtotal_array ) && ! empty( $cart_array ) ) {
										$cart_row_total_passed = $this->wcpfc_pro_match_cart_row_total_rule__premium_only( $wc_curr_version, $cart_rowtotal_array, $general_rule_match );
										if ( 'yes' === $cart_row_total_passed ) {
											$is_passed['has_fee_based_on_cart_row_total'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_cart_row_total'] = 'no';
										}
									}

									//Check if is weight exist
									if ( isset( $weight_array ) && ! empty( $weight_array ) && is_array( $weight_array ) && ! empty( $cart_array ) ) {
										$weight_passed = $this->wcpfc_pro_match_cart_total_weight_rule__premium_only( $cart_array, $weight_array, $general_rule_match );
										if ( 'yes' === $weight_passed ) {
											$is_passed['has_fee_based_on_weight'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_weight'] = 'no';
										}
									}
									//Check if is shipping class exist
									if ( isset( $shipping_class_array ) && ! empty( $shipping_class_array ) && is_array( $shipping_class_array ) && ! empty( $cart_product_ids_array ) ) {
										$shipping_class_passed = $this->wcpfc_pro_match_shipping_class_rule__premium_only( $cart_product_ids_array, $shipping_class_array, $general_rule_match );
										if ( 'yes' === $shipping_class_passed ) {
											$is_passed['has_fee_based_on_shipping_class'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_shipping_class'] = 'no';
										}
									}
									//Check if is payment gateway exist
									if ( isset( $payment_gateway ) && ! empty( $payment_gateway ) && is_array( $payment_gateway ) && ! empty( $cart_array ) ) {
										$payment_gateway_passed = $this->wcpfc_pro_match_payment_gateway_rule__premium_only( $payment_gateway, $general_rule_match );
										if ( 'yes' === $payment_gateway_passed ) {
											$is_passed['has_fee_based_on_payment_gateway'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_payment_gateway'] = 'no';
										}
									}
									//Check if is shipping method exist
									if ( isset( $shipping_methods ) && ! empty( $shipping_methods ) && is_array( $shipping_methods ) && ! empty( $cart_array ) ) {
										$shipping_method_passed = $this->wcpfc_pro_match_shipping_method_rule__premium_only( $wc_curr_version, $shipping_methods, $general_rule_match );
										if ( 'yes' === $shipping_method_passed ) {
											$is_passed['has_fee_based_on_shipping_method'] = 'yes';
										} else {
											$is_passed['has_fee_based_on_shipping_method'] = 'no';
										}
									}
                                    //Check if is attribute exist
                                    if ( isset( $attribute_taxonomies ) && !empty( $attribute_taxonomies ) ) {
                                        if ( isset( $atta_name ) && ! empty( $atta_name ) && is_array( $atta_name ) ) {
                                            // Cart product attribute data
                                            $cart_product_attributes = $this->wcpfc_pro_get_var_name__premium_only( $sitepress, $default_lang );

                                            $attribute_passed = $this->wcpfc_pro_match_attribute_rule__premium_only( $cart_product_attributes, $atta_name, $general_rule_match );
                                            if ( 'yes' === $attribute_passed ) {
                                                $is_passed['has_fee_based_on_product_att'] = 'yes';
                                            } else {
                                                $is_passed['has_fee_based_on_product_att'] = 'no';
                                            }
                                        }
                                    }
									
									/**** UPS plugin compatibility code start */
									$ups_specific_fee_filter = apply_filters('ups_specific_fee_filter', $args = array('flag'=> 0, 'fee_list' => array(), 'allowed_shipping_ids' => array()));
									
									/**Convert all the argument to variables */
									$custom_condition_flag 	= $ups_specific_fee_filter['flag'];
									$allowed_fee_list 		= $ups_specific_fee_filter['fee_list'];
									$allowed_shipping_ids 	= $ups_specific_fee_filter['allowed_shipping_ids'];
									
									/** Check if filter code enable or not */
									if( 1 === $custom_condition_flag ){

										/** Check if there is selected fees want to chcek or not */
										if(isset($allowed_fee_list) && !empty($allowed_fee_list)){
											if( in_array( $fees_id, $allowed_fee_list, true ) ){
												if ( $wc_curr_version >= 3.0 ) {
													$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
												} else {
													$chosen_shipping_methods = $woocommerce->session->chosen_shipping_methods;
												}
												if ( ! empty( $chosen_shipping_methods ) ) {
													$chosen_shipping_methods_explode = explode( ':', $chosen_shipping_methods[0] );
													
													$selected_shipping_id = $chosen_shipping_methods_explode[1];
		
													/** Check if selected shipping match with allowed shipping Ids or not */
													if( in_array( $selected_shipping_id, $allowed_shipping_ids, true ) ){
														$is_passed['has_fee_based_on_shipping_method'] = 'yes';
													} else {
														$is_passed['has_fee_based_on_shipping_method'] = 'no';
													}
												}
											}
										} else {
											/** If there is no allowed fee added then apply on all the fees */
											if ( $wc_curr_version >= 3.0 ) {
												$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
											} else {
												$chosen_shipping_methods = $woocommerce->session->chosen_shipping_methods;
											}
											if ( ! empty( $chosen_shipping_methods ) ) {
												$chosen_shipping_methods_explode = explode( ':', $chosen_shipping_methods[0] );
												
												$selected_shipping_id = $chosen_shipping_methods_explode[1];
	
												if( in_array( $selected_shipping_id, $allowed_shipping_ids, true ) ){
													$is_passed['has_fee_based_on_shipping_method'] = 'yes';
												} else {
													$is_passed['has_fee_based_on_shipping_method'] = 'no';
												}
											}
										}
										
									}
									/**** UPS plugin compatibility code end */
								}
							}
						}
						if ( isset( $is_passed ) && ! empty( $is_passed ) && is_array( $is_passed ) ) {
							$fnispassed = array();
							foreach ( $is_passed as $val ) {
								if ( '' !== $val ) {
									$fnispassed[] = $val;
								}
							}
							if ( 'all' === $general_rule_match ) {
								if ( in_array( 'no', $fnispassed, true ) ) {
									$final_is_passed_general_rule['passed'] = 'no';
								} else {
									$final_is_passed_general_rule['passed'] = 'yes';
								}
							} else {
								if ( in_array( 'yes', $fnispassed, true ) ) {
									$final_is_passed_general_rule['passed'] = 'yes';
								} else {
									$final_is_passed_general_rule['passed'] = 'no';
								}
							}
						}
					}
                    
					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {
							/* Start Advance Pricing Rules */
							if ( 'on' === $ap_rule_status ) {
								$cost_on_product_status                         = get_post_meta( $fees_id, 'cost_on_product_status', true );
								$cost_on_category_status                        = get_post_meta( $fees_id, 'cost_on_category_status', true );
								$cost_on_total_cart_qty_status                  = get_post_meta( $fees_id, 'cost_on_total_cart_qty_status', true );
								$cost_on_product_weight_status                  = get_post_meta( $fees_id, 'cost_on_product_weight_status', true );
								$cost_on_category_weight_status                 = get_post_meta( $fees_id, 'cost_on_category_weight_status', true );
								$cost_on_total_cart_weight_status               = get_post_meta( $fees_id, 'cost_on_total_cart_weight_status', true );
								$cost_on_total_cart_subtotal_status             = get_post_meta( $fees_id, 'cost_on_total_cart_subtotal_status', true );
								$cost_on_product_subtotal_status                = get_post_meta( $fees_id, 'cost_on_product_subtotal_status', true );
								$cost_on_category_subtotal_status               = get_post_meta( $fees_id, 'cost_on_category_subtotal_status', true );
								$cost_on_shipping_class_subtotal_status         = get_post_meta( $fees_id, 'cost_on_shipping_class_subtotal_status', true );
								$get_condition_array_ap_product                 = get_post_meta( $fees_id, 'sm_metabox_ap_product', true );
								$get_condition_array_ap_category                = get_post_meta( $fees_id, 'sm_metabox_ap_category', true );
								$get_condition_array_ap_total_cart_qty          = get_post_meta( $fees_id, 'sm_metabox_ap_total_cart_qty', true );
								$get_condition_array_ap_product_weight          = get_post_meta( $fees_id, 'sm_metabox_ap_product_weight', true );
								$get_condition_array_ap_category_weight         = get_post_meta( $fees_id, 'sm_metabox_ap_category_weight', true );
								$get_condition_array_ap_total_cart_weight       = get_post_meta( $fees_id, 'sm_metabox_ap_total_cart_weight', true );
								$get_condition_array_ap_total_cart_subtotal     = get_post_meta( $fees_id, 'sm_metabox_ap_total_cart_subtotal', true );
								$get_condition_array_ap_product_subtotal        = get_post_meta( $fees_id, 'sm_metabox_ap_product_subtotal', true );
								$get_condition_array_ap_category_subtotal       = get_post_meta( $fees_id, 'sm_metabox_ap_category_subtotal', true );
								$get_condition_array_ap_shipping_class_subtotal = get_post_meta( $fees_id, 'sm_metabox_ap_shipping_class_subtotal', true );
								$match_advance_rule                             = array();
								if ( 'on' === $cost_on_product_status ) {
									$match_advance_rule['hfbopq'] = $this->wcpfc_pro_match_product_per_qty__premium_only( $get_condition_array_ap_product, $cart_array, $sitepress, $default_lang, $cost_on_product_rule_match );
								}
								if ( 'on' === $cost_on_product_subtotal_status ) {
									$match_advance_rule['hfbops'] = $this->wcpfc_pro_match_product_subtotal__premium_only( $get_condition_array_ap_product_subtotal, $cart_array, $cost_on_product_subtotal_rule_match, $sitepress, $default_lang );
								}
								if ( 'on' === $cost_on_product_weight_status ) {
									$match_advance_rule['hfbopw'] = $this->wcpfc_pro_match_product_per_weight__premium_only( $get_condition_array_ap_product_weight, $cart_array, $sitepress, $default_lang, $cost_on_product_weight_rule_match );
								}
								if ( 'on' === $cost_on_category_status ) {
									$match_advance_rule['hfbocs'] = $this->wcpfc_pro_match_category_per_qty__premium_only( $get_condition_array_ap_category, $cart_array, $sitepress, $default_lang, $cost_on_category_rule_match );
								}
								if ( 'on' === $cost_on_category_subtotal_status ) {
									$match_advance_rule['hfbocs'] = $this->wcpfc_pro_match_category_subtotal__premium_only( $get_condition_array_ap_category_subtotal, $cart_array, $cost_on_category_subtotal_rule_match, $sitepress, $default_lang );
								}
								if ( 'on' === $cost_on_category_weight_status ) {
									$match_advance_rule['hfbocw'] = $this->wcpfc_pro_match_category_per_weight__premium_only( $get_condition_array_ap_category_weight, $cart_array, $sitepress, $default_lang, $cost_on_category_weight_rule_match );
								}
								if ( 'on' === $cost_on_total_cart_qty_status ) {
									// Calculate N combination Fee based on Cart Total QTY rule
									$match_advance_rule['hfbotcq'] = $this->wcpfc_pro_match_total_cart_qty__premium_only( $get_condition_array_ap_total_cart_qty, $cart_array, $cost_on_total_cart_qty_rule_match );

									$total_cart_qty_n_combination = $match_advance_rule['hfbotcq'];
								}
								if ( 'on' === $cost_on_total_cart_weight_status ) {
									$match_advance_rule['hfbotcw'] = $this->wcpfc_pro_match_total_cart_weight__premium_only( $get_condition_array_ap_total_cart_weight, $cart_array, $cost_on_total_cart_weight_rule_match );
								}
								if ( 'on' === $cost_on_total_cart_subtotal_status ) {
									$match_advance_rule['hfbotcs'] = $this->wcpfc_pro_match_total_cart_subtotal__premium_only( $get_condition_array_ap_total_cart_subtotal, $cart_array, $cost_on_total_cart_subtotal_rule_match );
								}
								if ( 'on' === $cost_on_shipping_class_subtotal_status ) {
									$match_advance_rule['hfbscs'] = $this->wcpfc_pro_match_shipping_class_subtotal__premium_only( $get_condition_array_ap_shipping_class_subtotal, $cart_array, $cost_on_shipping_class_subtotal_rule_match, $sitepress, $default_lang );
								}
								$advance_pricing_rule_cost = 0;
								if ( isset( $match_advance_rule ) && ! empty( $match_advance_rule ) && is_array( $match_advance_rule ) ) {
									foreach ( $match_advance_rule as $val ) {
										if ( isset($val['flag']) && '' !== $val['flag'] && 'yes' === $val['flag'] ) {
											$advance_pricing_rule_cost += $val['total_amount'];
										}
									}
								}

								$advance_pricing_rule_cost = $this->wcpfc_pro_price_format( $advance_pricing_rule_cost );
								$fees_cost                 += $advance_pricing_rule_cost;
							}
						}
					}

					if ( empty( $final_is_passed_general_rule ) || '' === $final_is_passed_general_rule || null === $final_is_passed_general_rule ) {
						$new_is_passed['passed'] = 'no';
					} else if ( ! empty( $final_is_passed_general_rule ) && in_array( 'no', $final_is_passed_general_rule, true ) ) {
						$new_is_passed['passed'] = 'no';
					} else if ( empty( $final_is_passed_general_rule ) && in_array( '', $final_is_passed_general_rule, true ) ) {
						$new_is_passed['passed'] = 'no';
					} else if ( ! empty( $final_is_passed_general_rule ) && in_array( 'yes', $final_is_passed_general_rule, true ) ) {
						$new_is_passed['passed'] = 'yes';
					}
					if ( in_array( 'no', $new_is_passed, true ) ) {
						$final_passed['passed'] = 'no';
					} else {
						$final_passed['passed'] = 'yes';
					}

					if ( isset( $final_passed ) && ! empty( $final_passed ) && is_array( $final_passed ) ) {
						if ( ! in_array( 'no', $final_passed, true ) ) {
							$texable      				= ( isset( $getFeetaxable ) && ! empty( $getFeetaxable ) && 'yes' === $getFeetaxable ) ? true : false;
							$currentDate  				= strtotime( gmdate( 'd-m-Y' ) );
							$feeStartDate 				= isset( $getFeeStartDate ) && '' !== $getFeeStartDate ? strtotime( $getFeeStartDate ) : '';
							$feeEndDate   				= isset( $getFeeEndDate ) && '' !== $getFeeEndDate ? strtotime( $getFeeEndDate ) : '';
							/*Check for time*/
							$local_nowtimestamp 		= current_time( 'timestamp' );
							$feeStartTime       		= ( isset( $getFeeStartTime ) && ! empty( $getFeeStartTime ) ) ? strtotime( $getFeeStartTime ) : '';
							$feeEndTime         		= ( isset( $getFeeEndTime ) && ! empty( $getFeeEndTime ) ) ? strtotime( $getFeeEndTime ) : '';
                            
							// Calculate N combination fees on Total Cart Quantity
							$cart_qty_n_combination = get_post_meta( $fees_id, 'cost_on_total_cart_qty_n_combination', true );
							if ( ! empty( $cart_qty_n_combination ) && 'on' === $cart_qty_n_combination ) {
								$nc_total_qty = WC()->cart->get_cart_contents_count();
								$fee_for_single_container = 0;
							    $fee_for_double_containers = 0;
								if ( isset( $total_cart_qty_n_combination['n_combination'] ) && ! empty( $total_cart_qty_n_combination['n_combination'] ) ) {
									$fee_for_single_container = $total_cart_qty_n_combination['n_combination'][0]['has_fee_based_on_tcq_price'][0];
									$fee_for_double_containers = $total_cart_qty_n_combination['n_combination'][1]['has_fee_based_on_tcq_price'][1];
								}

								$nc_total_fee = 0;
								if ($nc_total_qty === 1) {
						        	$nc_total_fee = $fee_for_single_container;
							    } else if ($nc_total_qty === 2) {
							        $nc_total_fee = $fee_for_double_containers;
							    } else {
							        $full_double_container_sets = intdiv($nc_total_qty, 2); // Number of full sets of 2 containers
							        $remaining_single_containers = $nc_total_qty % 2; // Remaining single container
							        
							        $nc_total_fee = ($full_double_container_sets * $fee_for_double_containers) + ($remaining_single_containers * $fee_for_single_container);
							    }
							    $fees_cost = $nc_total_fee;
							}
                            // Curcy converison apply globally
                            if( 'fixed' === $getFeeType ) {
                                $fees_cost = $this->wcpfc_pro_convert_currency($fees_cost);
                            }
							$fees_cost    				= $this->wcpfc_pro_price_format( $fees_cost );
							
							$fee_show_on_checkout_only	= get_post_meta( $fees_id, 'fee_show_on_checkout_only', true ) ? get_post_meta( $fees_id, 'fee_show_on_checkout_only', true ) : '';

							$today =  strtolower( gmdate( "D" ) );
							$ds_select_day_of_week  	= get_post_meta( $fees_id, 'ds_select_day_of_week', true ) ? get_post_meta( $fees_id, 'ds_select_day_of_week', true ) : array();
                            
							if ( ( $currentDate >= $feeStartDate || '' === $feeStartDate ) && ( $currentDate <= $feeEndDate || '' === $feeEndDate ) && ( $local_nowtimestamp >= $feeStartTime || '' === $feeStartTime ) && ( $local_nowtimestamp <= $feeEndTime || '' === $feeEndTime ) && ( in_array($today, $ds_select_day_of_week, true) || empty($ds_select_day_of_week) ) ) {
                                
                                $fee_is_recurring = 'off';
                                if ( wcpffc_fs()->is__premium_only() ) {
                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                        $fee_is_recurring = get_post_meta( $fees_id, 'fee_settings_recurring', true );
                                    }
                                }

								if ( '' !== $fees_cost ) {
                                    
									$chk_enable_coupon_fee = get_option( 'chk_enable_coupon_fee' );
									if ( 'on' === $chk_enable_coupon_fee ) {
										if ( $wc_curr_version >= 3.0 ) {
											$cart_coupon = WC()->cart->get_coupons();
											$get_cart_subtotal = $woocommerce->cart->get_cart_subtotal();
										} else {
											$cart_coupon = isset( $woocommerce->cart->coupons ) && ! empty( $woocommerce->cart->coupons ) ? $woocommerce->cart->coupons : array();
											$get_cart_subtotal = $woocommerce->cart->get_cart_subtotal();
										}
                                        $my = 1;
										if ( !empty( $cart_coupon ) && is_array( $cart_coupon ) ) {
											foreach ( $cart_coupon as $coupon ) {
												$coupon_type   = $coupon->get_discount_type();
												$coupon_amount = intval($coupon->get_amount());
												$cart_subtotal_amount = $this->wcpfc_pro_remove_currency_symbol( $get_cart_subtotal );

												if( (('percent' === $coupon_type) && !(100 === $coupon_amount)) || ('percent' !== $coupon_type && (floatval($cart_subtotal_amount) !== floatval($coupon->get_amount()) && floatval($cart_subtotal_amount) > floatval($coupon->get_amount()))) ){
													/** @var add the total fee value $total_fee */
													if ( wcpffc_fs()->is__premium_only() ) {
														if ( wcpffc_fs()->can_use_premium_code() ) {
															$getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );
															$getFeesOptional = apply_filters('is_fee_optional_default', $getFeesOptional);
														}
													}
                                                    $merge_fee_flag = apply_filters('merge_fee_flag',true, $fees_id);
													if ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) && true === $merge_fee_flag ) {
														if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ){
															$total_fee = $total_fee + $fees_cost;
														}
													} else {
                                                        if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ) {
                                                            if( is_checkout() || empty($fee_show_on_checkout_only) ){
                                                                // Note: We are using the function cart object to add the fee to apply fee 
                                                                // in subscription after disacount coupon applied
                                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                                        if( 'on' === $fee_is_recurring ) {
                                                                            $cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                        } else {
                                                                            WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                        }
                                                                    } else {
                                                                        WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                    }
                                                                } else {
                                                                    WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                }
                                                            }
                                                        }
													}
												}
											}
										} else {
											/** @var add the total fee value $total_fee */
											if ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) ) {
												if ( wcpffc_fs()->is__premium_only() ) {
													if ( wcpffc_fs()->can_use_premium_code() ) {
														$getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );
														$getFeesOptional = apply_filters('is_fee_optional_default', $getFeesOptional);
													}
												}
    											$merge_fee_flag = apply_filters('merge_fee_flag',true, $fees_id);
    											if(true === $merge_fee_flag){
													if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ) {
														$total_fee = $total_fee + $fees_cost;
													}
    											} else {
													if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ){
														if( is_checkout() || empty($fee_show_on_checkout_only) ){
															if ( wcpffc_fs()->is__premium_only() ) {
                                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                                    if( 'on' === $fee_is_recurring ) {
                                                                        $cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                    } else {
                                                                        WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                    }
                                                                } else {
                                                                    WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                }
                                                            } else {
                                                                WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                            }
														}
													}
    											}
    										} else {
                                                if ( wcpffc_fs()->is__premium_only() ) {
                                                    if ( wcpffc_fs()->can_use_premium_code() ) {
                                                        $getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );
                                                        $getFeesOptional = apply_filters('is_fee_optional_default', $getFeesOptional);
                                                    }
                                                }
                                                if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ) {
                                                    if( is_checkout() || empty($fee_show_on_checkout_only) ) {
                                                        if ( wcpffc_fs()->is__premium_only() ) {
                                                            if ( wcpffc_fs()->can_use_premium_code() ) {
                                                                if( 'on' === $fee_is_recurring ) {
                                                                    $cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                } else {
                                                                    WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                }
                                                            } else {
                                                                WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                            }
                                                        } else {
                                                            WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                        }
                                                    }
                                                }
											}
										}
									} else {
										/** @var add the total fee value $total_fee */
										if ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) ) {
											
											if ( wcpffc_fs()->is__premium_only() ) {
												if ( wcpffc_fs()->can_use_premium_code() ) {
													$getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );
													$getFeesOptional = apply_filters('is_fee_optional_default', $getFeesOptional);
												}
											}
											$merge_fee_flag = apply_filters('merge_fee_flag',true, $fees_id);
											
											if(true === $merge_fee_flag){
												if('yes' !== $getFeesOptional || $apply_rule_for_optional ){
													$total_fee = $total_fee + $fees_cost;
												}
											} else {
												if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ){
													if( is_checkout() || empty($fee_show_on_checkout_only) ){
														if ( wcpffc_fs()->is__premium_only() ) {
                                                            if ( wcpffc_fs()->can_use_premium_code() ) {
                                                                if( 'on' === $fee_is_recurring ) {
                                                                    $cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                } else {
                                                                    WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                }
                                                            } else {
                                                                WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                            }
                                                        } else {
                                                            WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                        }
													}
												}
											}
											
										} else {
                                            if ( wcpffc_fs()->is__premium_only() ) {
                                                if ( wcpffc_fs()->can_use_premium_code() ) {
                                                    $getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );
                                                    $getFeesOptional = apply_filters('is_fee_optional_default', $getFeesOptional);
                                                }
                                            }
                                            if( 'yes' !== $getFeesOptional || $apply_rule_for_optional ){
                                                if ( is_checkout() || empty($fee_show_on_checkout_only) ){
                                                    if ( wcpffc_fs()->is__premium_only() ) {
                                                        if ( wcpffc_fs()->can_use_premium_code() ) {
                                                            if( 'on' === $fee_is_recurring ) {
                                                                $cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                            } else {
                                                                WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                            }
                                                        } else {
                                                            WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                        }
                                                    } else {
                                                        WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                    }
                                                } else {
													if(  ! is_cart() && !empty($fee_show_on_checkout_only) ){
														if ( wcpffc_fs()->is__premium_only() ) {
                                                            if ( wcpffc_fs()->can_use_premium_code() ) {
                                                                if( 'on' === $fee_is_recurring ) {
                                                                    $cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                } else {
                                                                    WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                                }
                                                            } else {
                                                                WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                            }
                                                        } else {
                                                            WC()->cart->add_fee( $title, $fees_cost, $texable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees));
                                                        }
													}
												}
                                            }
										}
									}
								}
							}
							$display_optional_fee_on_checkout = 'on';
						} else {
							$display_optional_fee_on_checkout = 'off';
						}
						update_post_meta( $fees_id, '_wcpfc_display_optional_fee_on_checkout', $display_optional_fee_on_checkout );
					}
				}
			}
			/**
			 * Add one time fee with total applied fees count
			 */
			if ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) ) {
				if ( isset( $total_fee ) && 0 < $total_fee ) {
					$chk_enable_all_fee_tax     = ( 'on' === get_option( 'chk_enable_all_fee_tax' ) && !empty( get_option( 'chk_enable_all_fee_tax' ) ) ) ? true : false;
					$fee_title 					= apply_filters('wcpfc_all_fee_title','Fees');

					// Fetch the tax class type for the merged fee
					$taxClassType = get_option( 'merge_fee_settings_taxable_type' );

					if ( wcpffc_fs()->is__premium_only() ) {
						if ( wcpffc_fs()->can_use_premium_code() ) {

							// Check if the tax class type is different from 'standard'
							if ( ! empty( $taxClassType ) ) {
								// Update $final_item_tax_class based on the condition
								$final_item_tax_class = ( 'standard' !== $final_item_tax_class ) ? $taxClassType : $final_item_tax_class;
							}

						}
					}

					WC()->cart->add_fee( wp_kses_post( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ), $total_fee, $chk_enable_all_fee_tax, apply_filters('wcpfc_tax_class', $final_item_tax_class, -1)); //-1 for combined fees id
				}
			}
		}
	}
	
	/**
	 * Check user's have first order or not
	 *
	 * @return boolean $order_check
	 * @since 3.7.0
	 *
	 */
	public function wcpfc_check_first_order_for_user__premium_only( $user_id ) {
		$user_id = !empty($user_id) ? $user_id : get_current_user_id();

        $args = array(
            'customer_id'   => $user_id,
            'limit'         => 1, 
            'status'        => array( 'wc-completed', 'wc-processing' ), 
            'return'    => 'ids',
        );
        $customer_orders = wc_get_orders($args);
        
		// return "true" when customer has already at least one order (false if not)
	   return count($customer_orders) > 0 ? false : true; 
	   
	}

	/**
	 * Store fees revenue data for tracking and anylysis
	 *
	 * @since 3.7.0
	 *
	 */
	public function wcpfc_add_fee_details_with_order_for_track( $order ){
		if( !empty($order->get_fees()) ){
			$extra_fee_arr = array();
			foreach($order->get_fees() as $fee_detail ){
				$fees_id = !empty($fee_detail->legacy_fee->id) ? $fee_detail->legacy_fee->id : 0;
				$fee_amount = 0;
				if( $fees_id > 0 ){
					$fee_revenue = get_post_meta($fees_id, '_wcpfc_fee_revenue', true) ? get_post_meta($fees_id, '_wcpfc_fee_revenue', true) : 0;
					$fee_amount = !empty($fee_detail->legacy_fee->total) ? $fee_detail->legacy_fee->total : 0;
					if( !empty($fee_detail->legacy_fee->taxable) && $fee_detail->legacy_fee->taxable ){
						$fee_amount += ($fee_detail->legacy_fee->tax > 0) ? $fee_detail->legacy_fee->tax : 0;
					}
					$fee_revenue += $fee_amount;
					if( $fee_revenue > 0 ){
						update_post_meta($fees_id, '_wcpfc_fee_revenue', $fee_revenue);
					}
					array_push($extra_fee_arr, $fee_detail->legacy_fee);
				}
			}
			if( !empty($extra_fee_arr) ) {
				$order->update_meta_data( '_wcpfc_fee_summary', $extra_fee_arr );
			}
		}
	}

	// Function to get all variation IDs for a product
	public function wcpfc_get_all_variation_ids( $product_id ) {
		// Ensure the product exists and is a variable product
		$product = wc_get_product( $product_id );
		
		if ( $product && $product->is_type( 'variable' ) ) {
			// Get all variations of the product
			$variation_ids = $product->get_children();
			return $variation_ids;
		}
		
		return array(); // Return empty array if product is not variable
	}

	/**
	 * Optional fee callback function
	 */
	public function wcpfc_add_option_to_checkout__premium_only( $payment_fragments ){

		$final_output = '';

        $get_optional_fee_data = $this->wcpfc_pro_get_optional_fee_data__premium_only();

        if( empty( $get_optional_fee_data ) ) {
            // reset optional fee container for readd on checkout
            $payment_fragments['.optional_fee_container'] = '<div class="optional_fee_container"></div>';

            return $payment_fragments;
        }

        ob_start();
        
        // Optional Fee at Checkout HTML
        wcpfc_pro()->include_template( 'public/partials/woocommerce-conditional-optional-fee.php', array( 
				'optional_fee_data' => $get_optional_fee_data,
				'page' => 'checkout'
			) 
		);

        $final_output .= ob_get_clean();

        if( !empty( $final_output ) ) {
		    $payment_fragments['.optional_fee_container'] = $final_output;
        }

		return $payment_fragments;
	} 

	public function wcpfc_add_option_to_checkout_fragment__premium_only(){
		echo '<div class="optional_fee_container"></div>';
	}

	/**
	 *  Optional fee ajax for cart page
	 */
	public function wcpfc_pro_cart_optional_fees_ajax__premium_only() {
		
		$fees_ids = filter_input( INPUT_POST, 'fees_ids', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY ); 

		WC()->session->set('fees_ids', $fees_ids); // Store the array of fee IDs in session

		// Recalculate cart totals
		WC()->cart->calculate_totals();

	}
	
	/**
	 *  Add Optional fee for cart page
	 */
	public function wcpfc_pro_add_optional_fees_to_cart__premium_only() {

		if ( is_cart() && WC()->cart && ! WC()->cart->is_empty() ) {
			
			$fees_ids = WC()->session->get('fees_ids'); // Retrieve the array of fee IDs from session
	
			// If no fees are selected, no need to add any fees
			if (empty($fees_ids)) {
				return;
			}

			$get_optional_fee_data = $this->wcpfc_pro_get_optional_fee_data__premium_only();

			if (empty($get_optional_fee_data)) {
				return;
			}

			$chk_enable_custom_fun  = get_option( 'chk_enable_custom_fun' );
			$combined_fees = 0;
			$final_item_tax_class = '';

			if ($fees_ids && is_array($fees_ids)) {
				foreach ($fees_ids as $fees_id) {

					$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );
					$taxable = $conditional_fee->get_fee_settings_select_taxable();

					// Remove all fees if merge fee global option is enable, we will combine them and add as one fee
					if( 'on' === $chk_enable_custom_fun ) {
						$combined_fees += $get_optional_fee_data[$fees_id]['fee_cost'];
						continue;
					}

					// Fetch the tax class type for the current fee
					$taxClassType = get_post_meta( $fees_id, 'fee_settings_taxable_type', true );

					// Check if the tax class type is different from 'standard'
					if ( ! empty( $taxClassType ) ) {
						// Update $final_item_tax_class based on the condition
						$final_item_tax_class = ( 'standard' !== $final_item_tax_class ) ? $taxClassType : $final_item_tax_class;
					}

					$fee_show_on_checkout_only	= get_post_meta( $fees_id, 'fee_show_on_checkout_only', true ) ? get_post_meta( $fees_id, 'fee_show_on_checkout_only', true ) : '';

					if( empty($fee_show_on_checkout_only) ){
						$fee_title = get_the_title($fees_id);
						$title = !empty($fee_title) ? esc_html($fee_title, 'woocommerce-conditional-product-fees-for-checkout') : esc_html('Fee', 'woocommerce-conditional-product-fees-for-checkout');
						// Check if the fee data exists and the fee cost is set
						if (isset($get_optional_fee_data[$fees_id]) && isset($get_optional_fee_data[$fees_id]['fee_cost'])) {
							$total_fees = $get_optional_fee_data[$fees_id]['fee_cost'];
							
							if ($total_fees) {
								WC()->cart->add_fee($title, $total_fees, $taxable, apply_filters('wcpfc_tax_class', $final_item_tax_class, $fees_id));
							}
						}
					}
				}
				
			}

			// Apply combined fee
			if ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) ) {
				if ( isset( $combined_fees ) && 0 < $combined_fees ) {
					$chk_enable_all_fee_tax   = ( 'on' === get_option( 'chk_enable_all_fee_tax', 'no' ) ) ? true : false;
					$fee_title              = apply_filters( 'wcpfc_all_fee_title', __( ' Fees', 'woocommerce-conditional-product-fees-for-checkout' ) );

					// Fetch the tax class type for the merged fee
					$taxClassType = get_option( 'merge_fee_settings_taxable_type' );

					// Check if the tax class type is different from 'standard'
					if ( ! empty( $taxClassType ) ) {
						// Update $final_item_tax_class based on the condition
						$final_item_tax_class = ( 'standard' !== $final_item_tax_class ) ? $taxClassType : $final_item_tax_class;
					}

					WC()->cart->add_fee( wp_kses_post( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ), $combined_fees, $chk_enable_all_fee_tax, apply_filters('wcpfc_tax_class', $final_item_tax_class, -1)); //-1 for combined fees id
				}
			}
		}
	}

	/**
	 * Optional fee callback function for cart page
	 */
	public function wcpfc_add_option_to_cart__premium_only(){

		$final_output = '';
		$valid_fees = []; // Array to hold only valid fees

		$allowed_html = $this->wcpfc_get_allowed_html();

		// Fetch the optional fee data
		$get_optional_fee_data = $this->wcpfc_pro_get_optional_fee_data__premium_only();

		// Iterate over the optional fee data
		foreach($get_optional_fee_data as $key => $value){

			// Retrieve the meta value for 'optional_fees_in_cart'
			$getOptionalFeesCartPage = get_post_meta( $key, 'optional_fees_in_cart', true );
			
			// Skip this iteration if 'optional_fees_in_cart' is not set or is empty
			if( empty($getOptionalFeesCartPage) ) {
				continue;
			}

			// Add valid fees to the $valid_fees array
			$valid_fees[$key] = $value;

		}

		// Only proceed if there are valid fees to display
		if( !empty($valid_fees) ) {

			// Start output buffering for the optional fee at Cart HTML
			ob_start();

			// Include the template with the AJAX value
			wcpfc_pro()->include_template( 'public/partials/woocommerce-conditional-optional-fee.php', array( 
				'optional_fee_data' => $valid_fees,
				'page' => 'cart',
			) );

			$final_output .= ob_get_clean();
		}

		// Echo the final output only if there is something to display
		if( !empty($final_output) ) {
			echo wp_kses( $final_output, $allowed_html );
		}
	}

	
	public function wcpfc_pro_get_woo_version_number() {
		// If get_plugins() isn't available, require it
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		// Create the plugins folder and file variables
		$plugin_folder = get_plugins( '/' . 'woocommerce' );
		$plugin_file   = 'woocommerce.php';
		// If the plugin version number is set, return it
		if ( isset( $plugin_folder[ $plugin_file ]['Version'] ) ) {
			return $plugin_folder[ $plugin_file ]['Version'];
		} else {
			return null;
		}
	}

	/**
	 * Get product id and variation id from cart
	 *
	 * @return array $cart_array
	 * @since 1.0.0
	 *
	 */
	public function wcpfc_pro_get_cart() {

		// Ensure WooCommerce is initialized and the cart object is available
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return array(); // Return an empty array or handle the error as needed
		}

		$cart_array = WC()->cart->get_cart();

		return $cart_array;
	}

	/**
	 * Get product id and variation id from cart
	 *
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $cart_main_product_ids_array
	 * @uses  wcpfc_pro_get_cart();
	 *
	 * @since 1.0.0
	 *
	 */
	public function wcpfc_pro_get_main_prd_id( $sitepress, $default_lang ) {
		$cart_array                  = $this->wcpfc_pro_get_cart();
		$cart_main_product_ids_array = array();
		if ( isset($cart_array) && !empty($cart_array) ) {
			foreach ( $cart_array as $woo_cart_item ) {
				$product_id = $woo_cart_item['product_id'] ? $woo_cart_item['product_id'] : 0;
				settype( $product_id, 'integer' );
				if ( ! empty( $sitepress ) ) {
					$cart_main_product_ids_array[] = apply_filters( 'wpml_object_id', $product_id, 'product', true, $default_lang );
				} else {
					$cart_main_product_ids_array[] = $product_id;
				}
			}	
		}

		return $cart_main_product_ids_array;
	}

	/**
	 * Get product id and variation id from cart
	 *
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $cart_product_ids_array
	 * @uses  wcpfc_pro_get_cart();
	 *
	 * @since 1.0.0
	 *
	 */
	public function wcpfc_pro_get_prd_var_id( $sitepress, $default_lang ) {
		$cart_array             = $this->wcpfc_pro_get_cart();
		$cart_product_ids_array = array();
		if ( isset($cart_array) && !empty($cart_array) ) {
			foreach ( $cart_array as $woo_cart_item ) {
				$product_id = ( isset($woo_cart_item['variation_id']) && !empty($woo_cart_item['variation_id']) && $woo_cart_item['variation_id'] > 0 ) ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
				settype( $product_id, 'integer' );
				
				if ( ! empty( $sitepress ) ) {
					$cart_product_ids_array[] = apply_filters( 'wpml_object_id', $product_id, 'product', true, $default_lang );
				} else {
					$cart_product_ids_array[] = $product_id;
				}
			}
		}

		return $cart_product_ids_array;
	}

	/**
	 * Count qty for product based and cart based when apply per qty option is on. This rule will apply when advance pricing rule will disable
	 *
	 * @param int    $fees_id
	 * @param array  $cart_array
	 * @param int    $products_based_qty
	 * @param float  $products_based_subtotal
	 * @param string $sitepress
	 * @param string $default_lang
     * @param string $general_rule_match
	 *
	 * @return array $products_based_qty, $products_based_subtotal
	 * @since 1.3.3
	 *
	 * @uses  get_post_meta()
	 * @uses  get_post()
	 * @uses  get_terms()
	 *
	 */
	public function wcpfc_pro_fees_per_qty_on_ap_rules_off( $fees_id, $cart_array, $products_based_qty, $products_based_subtotal, $sitepress, $default_lang, $general_rule_match ) {

		$all_rule_check   = array();
		$productFeesArray = get_post_meta( $fees_id, 'product_fees_metabox', true );
        
		if ( ! empty( $productFeesArray ) ) {
			foreach ( $productFeesArray as $condition ) {

                // Product Condition
				if ( array_search( 'product', $condition, true ) ) {
					
					$cart_final_products_array = array();
                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
                    if ( ! empty( $condition_value ) ) {
					    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                            foreach ( $cart_array as $value ) {
                                
                                $product_id_lan = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
                                $_product = wc_get_product( $product_id_lan );

                                $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
                                if ( ! empty( $sitepress ) ) {
                                    $product_id_lan = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
                                }
                                if ( false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    if ( in_array( $product_id_lan, $condition_value, true ) ) {
                                        $prod_qty = $value['quantity'] ? $value['quantity'] : 0;
                                        if( array_key_exists($product_id_lan, $cart_final_products_array) ){
                                            $product_data_explode   = explode( "||", $cart_final_products_array[ $product_id_lan ] );
                                            $cart_product_qty   	= json_decode( $product_data_explode[0] );
                                            $prod_qty 				+= $cart_product_qty;
                                        }
                                        $cart_final_products_array[ $product_id_lan ] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                } else {
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ){
                                        $prod_qty = 0;
                                        $cart_final_products_array[ $product_id_lan ] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                }
                            }
					    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            foreach ( $cart_array as $value ) {

                                $product_id_lan = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
                                $_product = wc_get_product( $product_id_lan );

                                $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
                                if ( ! empty( $sitepress ) ) {
                                    $product_id_lan = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
                                }
                                if ( false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    if ( ! in_array( $product_id_lan, $condition_value, true ) ) {
                                        $prod_qty = $value['quantity'] ? $value['quantity'] : 0;
                                        if( array_key_exists($product_id_lan, $cart_final_products_array) ){
                                            $product_data_explode   = explode( "||", $cart_final_products_array[ $product_id_lan ] );
                                            $cart_product_qty   	= json_decode( $product_data_explode[0] );
                                            $prod_qty 				+= $cart_product_qty;
                                        } 
                                        $cart_final_products_array[ $product_id_lan ] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                } else {
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ){
                                        $prod_qty = 0;
                                        $cart_final_products_array[ $product_id_lan ] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                }
                            }
						}
					}
					if ( isset( $cart_final_products_array ) && ! empty( $cart_final_products_array ) ) {
						foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
							$cart_item_explode                     = explode( "||", $cart_item );
							$all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
							$all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
						}
                    }
				}

                // Variable Product Condition
				if ( array_search( 'variableproduct', $condition, true ) ) {
					
					$cart_final_var_products_array = array();
					
                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
                    if ( ! empty( $condition_value ) ) {
					    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                            foreach ( $cart_array as $value ) {

                                $product_id_lan = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
                                $_product = wc_get_product( $product_id_lan );

                                $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
                                if ( ! empty( $sitepress ) ) {
                                    $product_id_lan = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
                                }
                                if ( false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    if ( in_array( $product_id_lan, $condition_value, true ) ) {
                                        $prod_qty = $value['quantity'] ? $value['quantity'] : 0;
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                } else {
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ){
                                        $prod_qty = 0;
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                }
                            }
					    } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            foreach ( $cart_array as $value ) {

                                $product_id_lan = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
                                $_product = wc_get_product( $product_id_lan );

                                $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
                                if ( ! empty( $sitepress ) ) {
                                    $product_id_lan = apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang );
                                } 
                                if ( false === strpos( $_product->get_type(), 'bundle' ) ) {
                                    if ( ! in_array( $product_id_lan, $condition_value, true ) ) {
                                        $prod_qty = $value['quantity'] ? $value['quantity'] : 0;
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                } else {
                                    if ( false !== strpos( $_product->get_type(), 'bundle' ) ){
                                        $prod_qty = 0;
                                        $cart_final_var_products_array[] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                }
                            }
						}
					}
					if ( isset( $cart_final_var_products_array ) && ! empty( $cart_final_var_products_array ) ) {
						foreach ( $cart_final_var_products_array as $prd_id => $cart_item ) {
							$cart_item_explode                     = explode( "||", $cart_item );
							$all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
							$all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
						}
					}
				}

                // Brand Condition
                if ( array_search( 'brand', $condition, true ) ) {
                    $final_cart_product_brand_ids  = array();
                    $cart_final_brand_products_array = array();
                    $all_brands                      = get_terms(
                        array(
                            'taxonomy' => 'product_brand',
                            'fields'   => 'ids',
                        )
                    );
                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
                    if ( ! empty( $condition_value ) ) {
                        if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                            foreach ( $condition_value as $brand_id ) {
                                $final_cart_product_brand_ids[] = $brand_id;
                            }
                        } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_product_brand_ids = array_diff( $all_brands, $condition_value );
                        }
                    }
                    $final_cart_product_brand_ids = array_map( 'intval', $final_cart_product_brand_ids );

                    $terms            = array();
                    foreach ( $cart_array as $value ) {

                        $product_id = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
                        $_product = wc_get_product( $product_id );

                        $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
                        $term_ids           = wp_get_post_terms( $value['product_id'], 'product_brand', array( 'fields' => 'ids' ) );
                        if ( !empty($term_ids) ) {
                            foreach ( $term_ids as $term_id ) {
                                $prod_qty = $value['quantity'] ? $value['quantity'] : 0;
                                if( false !== strpos( $_product->get_type(), 'bundle' ) ){
                                    $prod_qty = 0;
                                }
                                $product_id = ( $value['variation_id'] ) ? $value['variation_id'] : $product_id;
                                if ( in_array( $term_id, $final_cart_product_brand_ids, true ) ) {
                                    if( array_key_exists($product_id,$terms) && array_key_exists($term_id,$terms[$product_id]) ){
                                        $term_data_explode  = explode( "||", $terms[ $product_id ][ $term_id ] );
                                        $cart_term_qty      = json_decode( $term_data_explode[0] );
                                        $prod_qty += $cart_term_qty;
                                    }
                                    $terms[ $product_id ][ $term_id ] = $prod_qty . "||" . $line_item_subtotal;
                                }
                            }	
                        }
                    }
                    if ( isset($terms) && !empty($terms) ) {
                        foreach ( $terms as $cart_product_key => $main_term_data ) {
                            foreach ( $main_term_data as $cart_term_id => $term_data ) {
                                $term_data_explode  = explode( "||", $term_data );
                                $cart_term_qty      = json_decode( $term_data_explode[0] );
                                $cart_term_subtotal = json_decode( $term_data_explode[1] );
                                if ( in_array( $cart_term_id, $final_cart_product_brand_ids, true ) ) {
                                    $cart_final_brand_products_array[ $cart_product_key ][ $cart_term_id ] = $cart_term_qty . "||" . $cart_term_subtotal;
                                }
                            }
                        }
                    }
                    if ( isset( $cart_final_brand_products_array ) && ! empty( $cart_final_brand_products_array ) ) {
                        foreach ( $cart_final_brand_products_array as $prd_id => $main_cart_item ) {
                            foreach ( $main_cart_item as $term_id => $cart_item ) {
                                $cart_item_explode                     = explode( "||", $cart_item );
                                $all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
                                $all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
                            }
                        }
                    }
                }

                // wlf_location condition (Custom Support #104847 - Location based fee)
                $block_conditions = array('product', 'variableproduct', 'brand', 'category', 'tag');
                $all_conditions = is_array( $productFeesArray ) ? array_column($productFeesArray, 'product_fees_conditions_condition') : array();
                // Check if any other product specific condition exists
			    $otherProductCondition = true;
			    foreach ($block_conditions as $block_condition) {
			        if ( array_search( $block_condition, $all_conditions, true ) !== false ) {
			            $otherProductCondition = false;
			            break;
			        }
			    }

                // If no other product based condition is found with location, run this code
                if ( $otherProductCondition && in_array( 'wlf_location', $all_conditions, true ) ) {
        			// Run my action
        			$final_cart_product_wlf_location_ids  = array();
                    $cart_final_wlf_location_products_array = array();
                    $all_wlf_locations                      = get_terms(
                        array(
                            'taxonomy' => 'location',
                            'fields'   => 'ids',
                        )
                    );

                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
                    if ( ! empty( $condition_value ) ) {
                        if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                            foreach ( $condition_value as $wlf_location_id ) {
                                $final_cart_product_wlf_location_ids[] = $wlf_location_id;
                            }
                        } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_product_wlf_location_ids = array_diff( $all_wlf_locations, $condition_value );
                        }
                    }
                    $final_cart_product_wlf_location_ids = array_map( 'intval', $final_cart_product_wlf_location_ids );

                    $terms            = array();
                    foreach ( $cart_array as $value ) {

                        $product_id = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
                        $_product = wc_get_product( $product_id );

                        $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
                        $term_ids           = wp_get_post_terms( $value['product_id'], 'location', array( 'fields' => 'ids' ) );

                        if ( !empty($term_ids) ) {
                            foreach ( $term_ids as $term_id ) {
                                $prod_qty = $value['quantity'] ? $value['quantity'] : 0;
                                if( false !== strpos( $_product->get_type(), 'bundle' ) ){
                                    $prod_qty = 0;
                                }
                                $product_id = ( $value['variation_id'] ) ? $value['variation_id'] : $product_id;
                                if ( in_array( $term_id, $final_cart_product_wlf_location_ids, true ) ) {
                                    if( array_key_exists($product_id,$terms) && array_key_exists($term_id,$terms[$product_id]) ){
                                        $term_data_explode  = explode( "||", $terms[ $product_id ][ $term_id ] );
                                        $cart_term_qty      = json_decode( $term_data_explode[0] );
                                        $prod_qty += $cart_term_qty;
                                    }
                                    $terms[ $product_id ][ $term_id ] = $prod_qty . "||" . $line_item_subtotal;
                                }
                            }	
                        }
                    }
                    if ( isset($terms) && !empty($terms) ) {
                        foreach ( $terms as $cart_product_key => $main_term_data ) {
                            foreach ( $main_term_data as $cart_term_id => $term_data ) {
                                $term_data_explode  = explode( "||", $term_data );
                                $cart_term_qty      = json_decode( $term_data_explode[0] );
                                $cart_term_subtotal = json_decode( $term_data_explode[1] );
                                if ( in_array( $cart_term_id, $final_cart_product_wlf_location_ids, true ) ) {
                                    $cart_final_wlf_location_products_array[ $cart_product_key ][ $cart_term_id ] = $cart_term_qty . "||" . $cart_term_subtotal;
                                }
                            }
                        }
                    }
                    if ( isset( $cart_final_wlf_location_products_array ) && ! empty( $cart_final_wlf_location_products_array ) ) {
                        foreach ( $cart_final_wlf_location_products_array as $prd_id => $main_cart_item ) {
                            foreach ( $main_cart_item as $term_id => $cart_item ) {
                                $cart_item_explode                     = explode( "||", $cart_item );
                                $all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
                                $all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
                            }
                        }
                    }
                }

                // Category Condition
				if ( array_search( 'category', $condition, true ) ) {
					$final_cart_products_cats_ids  = array();
					$cart_final_cat_products_array = array();
					$all_cats                      = get_terms(
						array(
							'taxonomy' => 'product_cat',
							'fields'   => 'ids',
						)
					);
                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
                    if ( ! empty( $condition_value ) ) {
					    if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
							foreach ( $condition_value as $category_id ) {
								$final_cart_products_cats_ids[] = $category_id;
							}
                        } elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                            $final_cart_products_cats_ids = array_diff( $all_cats, $condition_value );
                        }
					}
					$final_cart_products_cats_ids = array_map( 'intval', $final_cart_products_cats_ids );

					$terms            = array();
					foreach ( $cart_array as $value ) {

                        $product_id = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
						$_product = wc_get_product( $product_id );

						$line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
						$term_ids           = wp_get_post_terms( $value['product_id'], 'product_cat', array( 'fields' => 'ids' ) );
						if ( !empty($term_ids) ) {
							foreach ( $term_ids as $term_id ) {
								$prod_qty = $value['quantity'] ? $value['quantity'] : 0;
								if( false !== strpos( $_product->get_type(), 'bundle' ) ){
									$prod_qty = 0;
								}
								$product_id = ( $value['variation_id'] ) ? $value['variation_id'] : $product_id;
                                if ( in_array( $term_id, $final_cart_products_cats_ids, true ) ) {
                                    if( array_key_exists($product_id,$terms) && array_key_exists($term_id,$terms[$product_id]) ){
                                        $term_data_explode  = explode( "||", $terms[ $product_id ][ $term_id ] );
                                        $cart_term_qty      = json_decode( $term_data_explode[0] );
                                        $prod_qty += $cart_term_qty;
                                    }
                                    $terms[ $product_id ][ $term_id ] = $prod_qty . "||" . $line_item_subtotal;
                                }
							}	
						}
					}
					if ( isset($terms) && !empty($terms) ) {
						foreach ( $terms as $cart_product_key => $main_term_data ) {
							foreach ( $main_term_data as $cart_term_id => $term_data ) {
								$term_data_explode  = explode( "||", $term_data );
								$cart_term_qty      = json_decode( $term_data_explode[0] );
								$cart_term_subtotal = json_decode( $term_data_explode[1] );
								if ( in_array( $cart_term_id, $final_cart_products_cats_ids, true ) ) {
									$cart_final_cat_products_array[ $cart_product_key ][ $cart_term_id ] = $cart_term_qty . "||" . $cart_term_subtotal;
								}
							}
						}
					}
					if ( isset( $cart_final_cat_products_array ) && ! empty( $cart_final_cat_products_array ) ) {
						foreach ( $cart_final_cat_products_array as $prd_id => $main_cart_item ) {
							foreach ( $main_cart_item as $term_id => $cart_item ) {
								$cart_item_explode                     = explode( "||", $cart_item );
								$all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
								$all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
							}
						}
					}
				}

                // Tag Condition Start
				if ( array_search( 'tag', $condition, true ) ) {
					$final_cart_products_tag_ids   = array();
					$cart_final_tag_products_array = array();
					$all_tags                      = get_terms(
						array(
							'taxonomy' => 'product_tag',
							'fields'   => 'ids',
						)
					);
                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
					if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition_value ) ) {
							foreach ( $condition_value as $tag_id ) {
								$final_cart_products_tag_ids[] = $tag_id;
							}
						}
					} elseif ( 'not_in' === $condition['product_fees_conditions_is'] ) {
						if ( ! empty( $condition_value ) ) {
							$final_cart_products_tag_ids = array_diff( $all_tags, $condition_value );
						}
					}
					$final_cart_products_tag_ids = array_map( 'intval', $final_cart_products_tag_ids );
					$tags                        = array();
					foreach ( $cart_array as $value ) {
                        
                        $product_id = !empty( $value['variation_id'] ) && 0 !== $value['variation_id'] ? intval($value['variation_id']) : intval($value['product_id']);
						$_product = wc_get_product( $product_id );
						
                        $line_item_subtotal = (float) $value['data']->get_price() * (float) $value['quantity'];
						$tag_ids            = wp_get_post_terms( $value['product_id'], 'product_tag', array( 'fields' => 'ids' ) );
						foreach ( $tag_ids as $tag_id ) {
							$prod_qty = $value['quantity'] ? $value['quantity'] : 0;
							if( false !== strpos( $_product->get_type(), 'bundle' ) ){
								$prod_qty = 0;
							}
							$product_id = ( $value['variation_id'] ) ? $value['variation_id'] : $product_id;
							if ( in_array( $tag_id, $final_cart_products_tag_ids, true ) ) {
								if( array_key_exists($product_id,$tags) && array_key_exists($tag_id,$tags[$product_id]) ){
									$term_data_explode  = explode( "||", $tags[ $product_id ][ $tag_id ] );
									$cart_term_qty      = json_decode( $term_data_explode[0] );
									$prod_qty += $cart_term_qty;
								}
								$tags[ $product_id ][ $tag_id ] = $prod_qty . "||" . $line_item_subtotal;
							}
						}
					}
					if ( isset($tags) && !empty($tags) ) {
						foreach ( $tags as $cart_product_key => $main_tag_data ) {
							foreach ( $main_tag_data as $cart_tag_id => $tag_data ) {
								$tag_data_explode  = explode( "||", $tag_data );
								$cart_tag_qty      = json_decode( $tag_data_explode[0] );
								$cart_tag_subtotal = json_decode( $tag_data_explode[1] );
								if ( ! empty( $final_cart_products_tag_ids ) ) {
									if ( in_array( $cart_tag_id, $final_cart_products_tag_ids, true ) ) {
										$cart_final_tag_products_array[ $cart_product_key ][ $cart_tag_id ] = $cart_tag_qty . "||" . $cart_tag_subtotal;
									}
								}
							}
						}
					}
					if ( ! empty( $cart_final_tag_products_array ) ) {
						foreach ( $cart_final_tag_products_array as $prd_id => $main_cart_item ) {
							foreach ( $main_cart_item as $term_id => $cart_item ) {
								$cart_item_explode                     = explode( "||", $cart_item );
								$all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
								$all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
							}
						}
					}
				}

                // Product Attribute Condition 
                if( isset( $condition['product_fees_conditions_condition'] ) && strpos( $condition['product_fees_conditions_condition'], 'pa_' ) === 0 && 'any' === $general_rule_match ) {
                    
                    $cart_final_products_array = [];
                    $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'sanitize_text_field', $condition['product_fees_conditions_values'] ) : array();
                    
                    if ( ! empty( $condition_value ) ) {
                        foreach ( $cart_array as $cart_item ) {

                            $product = $cart_item['data'];
                
                            // Check if product is not a product object then skip the loop
                            if ( ! is_a( $product, 'WC_Product' ) ) {
                                continue;
                            }   
                            
                            $product_id = $product->get_id();
                            $attributes = $product->get_attributes(); // Get product attributes
                            $attributes_data = [];
                            $filtered_attributes = [];
                            
                            foreach ( $attributes as $attribute_slug => $attribute ) {

                                if( $product->is_type( 'variation' ) ) {

                                    // For Variation product
                                    if( empty( $attribute ) ) {
            
                                        // For 'Any' attribute value
                                        $variation_data = $cart_item['variation'];
                                        $selected_value = $variation_data["attribute_$attribute_slug"] ?? '';
            
                                        $terms = wc_get_product_terms($product->get_parent_id(), $attribute_slug, ['fields' => 'slugs']);
                                        if ( !empty($terms) ) {
                                            // If the selected value is one of the valid terms, it's from "Any" options
                                            if ( in_array( $selected_value, $terms, true ) ) {
                                                $attributes_data[$attribute_slug] = $selected_value;
                                            }
                                        }
                                    } else {
            
                                        // For 'Specific' attribute value
                                        $attributes_data[$attribute_slug] = $attribute;
                                    }
                                } else {
            
                                    // For Simple product
                                    foreach( $attribute->get_slugs() as $sj_slugs ){
                                        $attributes_data[$attribute_slug] = $sj_slugs;
                                    }
                                }

                                // Filter only keys that start with 'pa_'
                                $filtered_attributes = array_filter($attributes_data, function($key) {
                                    return strpos($key, 'pa_') === 0;
                                }, ARRAY_FILTER_USE_KEY);
                            }

                            if( isset( $filtered_attributes[ $condition['product_fees_conditions_condition'] ] ) && !empty( $filtered_attributes[ $condition['product_fees_conditions_condition'] ] ) ) {

                                if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                    // is_equal_to condition
                                    if ( in_array( $filtered_attributes[ $condition['product_fees_conditions_condition'] ], $condition_value, true ) ) {
                                        $prod_qty = $cart_item['quantity'] ? $cart_item['quantity'] : 0;
                                        $line_item_subtotal = (float) $cart_item['data']->get_price() * (float) $cart_item['quantity'];
                                        if( array_key_exists($product_id, $cart_final_products_array) ){
                                            $product_data_explode   = explode( "||", $cart_final_products_array[ $product_id ] );

                                            // Quantity
                                            $cart_product_qty   	= json_decode( $product_data_explode[0] );
                                            $prod_qty 				+= $cart_product_qty;

                                            //Subtotal
                                            $cart_product_subtotal  = json_decode( $product_data_explode[1] );
                                            $line_item_subtotal     += $cart_product_subtotal;
                                        }
                                        $cart_final_products_array[ $product_id ] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                }
                                if ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                                    // not_in condition
                                    if ( ! in_array( $filtered_attributes[ $condition['product_fees_conditions_condition'] ], $condition_value, true ) ) {
                                        $prod_qty = $cart_item['quantity'] ? $cart_item['quantity'] : 0;
                                        $line_item_subtotal = (float) $cart_item['data']->get_price() * (float) $cart_item['quantity'];
                                        if( array_key_exists($product_id, $cart_final_products_array) ){
                                            $product_data_explode   = explode( "||", $cart_final_products_array[ $product_id ] );
                                            
                                            // Quantity
                                            $cart_product_qty   	= json_decode( $product_data_explode[0] );
                                            $prod_qty 				+= $cart_product_qty;

                                            //Subtotal
                                            $cart_product_subtotal  = json_decode( $product_data_explode[1] );
                                            $line_item_subtotal     += $cart_product_subtotal;
                                        }
                                        $cart_final_products_array[ $product_id ] = $prod_qty . "||" . $line_item_subtotal;
                                    }
                                }
                            }
                        }
                    }
                    
                    if ( isset( $cart_final_products_array ) && ! empty( $cart_final_products_array ) ) {
						foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
							$cart_item_explode                     = explode( "||", $cart_item );
							$all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
							$all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
						}
					}
                }

			}
		}

        // All rules check for product attributes, as we need to check all attribute rule with cart product acttribute to get the final qty and subtotal
        if( 'all' === $general_rule_match ) {

            $cart_final_products_array = [];
            if( !empty($cart_array) ){
                foreach ( $cart_array as $cart_item ) {

                    $product = $cart_item['data'];
        
                    // Check if product is not a product object then skip the loop
                    if ( ! is_a( $product, 'WC_Product' ) ) {
                        continue;
                    }   
                    
                    $product_id = $product->get_id();
                    $attributes = $product->get_attributes(); // Get product attributes
                    $attributes_data = [];
                    $filtered_attributes = [];
                    
                    foreach ( $attributes as $attribute_slug => $attribute ) {

                        if( $product->is_type( 'variation' ) ) {

                            // For Variation product
                            if( empty( $attribute ) ) {
    
                                // For 'Any' attribute value
                                $variation_data = $cart_item['variation'];
                                $selected_value = $variation_data["attribute_$attribute_slug"] ?? '';
    
                                $terms = wc_get_product_terms($product->get_parent_id(), $attribute_slug, ['fields' => 'slugs']);
                                if ( !empty($terms) ) {
                                    // If the selected value is one of the valid terms, it's from "Any" options
                                    if ( in_array( $selected_value, $terms, true ) ) {
                                        $attributes_data[$attribute_slug] = $selected_value;
                                    }
                                }
                            } else {
    
                                // For 'Specific' attribute value
                                $attributes_data[$attribute_slug] = $attribute;
                            }
                        } else {
    
                            // For Simple product
                            foreach( $attribute->get_slugs() as $sj_slugs ){
                                $attributes_data[$attribute_slug] = $sj_slugs;
                            }
                        }
                    }
                    
                    // Now we will check every product with our fee all rules(only product attribute rules) other rules are already checked above
                    if ( ! empty( $productFeesArray ) ) {
                        $is_passed = [];
                        foreach ( $productFeesArray as $condition_key => $condition ) {
                            if( isset( $condition['product_fees_conditions_condition'] ) && strpos( $condition['product_fees_conditions_condition'], 'pa_' ) === 0 ) {
                                $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'sanitize_text_field', $condition['product_fees_conditions_values'] ) : array();
                                
                                if ( ! empty( $condition_value ) ) {
                                    // If attribute is matched with backend rule set then we will check the condition
                                    if( isset( $attributes_data[ $condition['product_fees_conditions_condition'] ] ) && !empty( $attributes_data[ $condition['product_fees_conditions_condition'] ] ) ) {
                                        if ( 'is_equal_to' === $condition['product_fees_conditions_is'] ) {
                                            // is_equal_to condition
                                            if ( in_array( $attributes_data[ $condition['product_fees_conditions_condition'] ], $condition_value, true ) ) {
                                                $is_passed[$condition_key]['has_all_rules_passed_by_product_attribute'] = 'yes';
                                            } else {
                                                $is_passed[$condition_key]['has_all_rules_passed_by_product_attribute'] = 'no';
                                            }
                                        }
                                        if ( 'not_in' === $condition['product_fees_conditions_is'] ) {
                                            // not_in condition
                                            if ( ! in_array( $attributes_data[ $condition['product_fees_conditions_condition'] ], $condition_value, true ) ) {
                                                $is_passed[$condition_key]['has_all_rules_passed_by_product_attribute'] = 'no';
                                            } else {
                                                $is_passed[$condition_key]['has_all_rules_passed_by_product_attribute'] = 'yes';
                                            }
                                        }
                                    } else {
                                        // Custom attribute or attribute which not match with backend rule set then we will set 'no' for this condition
                                        $is_passed[$condition_key]['has_all_rules_passed_by_product_attribute'] = 'no';
                                    }
                                }
                            }
                        }
                    }

                    $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_all_rules_passed_by_product_attribute', $general_rule_match );
                    if( $main_is_passed === 'yes' ) {
                        $prod_qty = $cart_item['quantity'] ? $cart_item['quantity'] : 0;
                        $line_item_subtotal = (float) $cart_item['data']->get_price() * (float) $cart_item['quantity'];
                        if( array_key_exists($product_id, $cart_final_products_array) ){
                            // Quantity
                            $product_data_explode   = explode( "||", $cart_final_products_array[ $product_id ] );
                            $cart_product_qty   	= json_decode( $product_data_explode[0] );
                            $prod_qty 				+= $cart_product_qty;

                            // Subtotal
                            $cart_product_subtotal  = json_decode( $product_data_explode[1] );
                            $line_item_subtotal     += $cart_product_subtotal;
                        }
                        $cart_final_products_array[ $product_id ] = $prod_qty . "||" . $line_item_subtotal;
                    }
                }
                if ( isset( $cart_final_products_array ) && ! empty( $cart_final_products_array ) ) {
                    foreach ( $cart_final_products_array as $prd_id => $cart_item ) {
                        $cart_item_explode                     = explode( "||", $cart_item );
                        $all_rule_check[ $prd_id ]['qty']      = $cart_item_explode[0];
                        $all_rule_check[ $prd_id ]['subtotal'] = $cart_item_explode[1];
                    }
                }
            }
        }
         
		if ( isset( $all_rule_check ) && ! empty( $all_rule_check ) ) {
			foreach ( $all_rule_check as $cart_item ) {
				$products_based_qty      += isset($cart_item['qty'])?$cart_item['qty']:0;
				$products_based_subtotal += isset($cart_item['subtotal'])?$cart_item['subtotal']:0;
			}
		}
		if ( 0 === $products_based_qty ) {
			$products_based_qty = 1;
		}
        
		return array( $products_based_qty, $products_based_subtotal );
	}

	/**
	 * Match country rules
	 *
	 * @param array  $country_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     WC_Customer::get_shipping_country()
	 *
	 */
	public function wcpfc_pro_match_country_rules( $country_array, $general_rule_match ) {

		if ( ! WC()->customer ) {
			return ''; // Return an empty string or handle the error as needed
		}
		
		$selected_country = WC()->customer->get_shipping_country();
		$is_passed        = array();
		foreach ( $country_array as $key => $country ) {
			if ( 'is_equal_to' === $country['product_fees_conditions_is'] ) {
				if ( ! empty( $country['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'no';
					}
				}
				if ( empty( $country['product_fees_conditions_values'] ) ) {
					$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
				}
			}
			if ( 'not_in' === $country['product_fees_conditions_is'] ) {
				if ( ! empty( $country['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_country, $country['product_fees_conditions_values'], true ) || in_array( 'all', $country['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_country'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_country', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match city rules
	 *
	 * @param array  $city_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     WC_Customer::get_shipping_city()
	 *
	 */
	public function wcpfc_pro_match_city_rules( $city_array, $general_rule_match ) {
		$selected_city = WC()->customer->get_shipping_city();
		$is_passed        = array();
		foreach ( $city_array as $key => $city ) {
			if ( ! empty( $city['product_fees_conditions_values'] ) ) {

				$citystr        = str_replace( PHP_EOL, "<br/>", $city['product_fees_conditions_values'] );
                $citystr        = html_entity_decode($citystr);
				$city_val_array = explode( '<br/>', $citystr );
				$city_val_array = array_map( 'trim', $city_val_array );
				
				if ( 'is_equal_to' === $city['product_fees_conditions_is'] ) {
					if ( in_array( $selected_city, $city_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_city'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_city'] = 'no';
					}
				}
				if ( 'not_in' === $city['product_fees_conditions_is'] ) {
					if ( in_array( $selected_city, $city_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_city'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_city'] = 'yes';
					}
				}
			}
		}
        
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_city', $general_rule_match );

		return $main_is_passed;
	}

    /**
	 * Match attribute rules
	 *
     * @param array  $cart_product_attributes
	 * @param string $att_name
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.9.0
	 *
	 */
	public function wcpfc_pro_match_attribute_rule__premium_only( $cart_product_attributes, $att_name, $general_rule_match ) {
        
		$is_passed      = array();
        
		foreach ( $att_name as $key => $attr_condition ) {

            $attr_term = $attr_condition['product_fees_conditions_condition'];
            $cart_term_data = array_key_exists( $attr_term, $cart_product_attributes ) ? $cart_product_attributes[ $attr_term ] : array();
			
            if ( $attr_condition['product_fees_conditions_is'] === 'is_equal_to' ) {
				if ( ! empty( $attr_condition['product_fees_conditions_values'] ) ) {
					foreach ( $attr_condition['product_fees_conditions_values'] as $attr_term_value ) {
						if ( in_array( $attr_term_value, $cart_term_data, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'no';
						}
					}
				}
			}
			if ( $attr_condition['product_fees_conditions_is'] === 'not_in' ) {
				if ( ! empty( $attr_condition['product_fees_conditions_values'] ) ) {
					foreach ( $attr_condition['product_fees_conditions_values'] as $attr_term_value ) {
						if ( in_array( $attr_term_value, $cart_term_data, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product_att'] = 'yes';
						}
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.9.0
		 *
		 */
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product_att', $general_rule_match );
		return $main_is_passed;
	}

	/**
	 * Find unique id based on given array
	 *
	 * @param array  $is_passed
	 * @param string $has_fee_based
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.6
	 *
	 */
	public function wcpfc_pro_check_all_passed_general_rule( $is_passed, $has_fee_based, $general_rule_match ) {
		$main_is_passed = 'no';
		$flag           = array();
		if ( ! empty( $is_passed ) ) {
			foreach ( $is_passed as $key => $is_passed_value ) {
				if ( 'yes' === $is_passed_value[ $has_fee_based ] ) {
					$flag[ $key ] = true;
				} else {
					$flag[ $key ] = false;
				}
			}
			if ( 'any' === $general_rule_match ) {
				if ( in_array( true, $flag, true ) ) {
					$main_is_passed = 'yes';
				} else {
					$main_is_passed = 'no';
				}
			} else {
				if ( in_array( false, $flag, true ) ) {
					$main_is_passed = 'no';
				} else {
					$main_is_passed = 'yes';
				}
			}
		}

		return $main_is_passed;
	}

	/**
	 * Match simple products rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $product_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_simple_products_rule( $cart_product_ids_array, $product_array, $general_rule_match ) {
		$is_passed = array();
        
		foreach ( $product_array as $key => $product ) {

            $condition_value = !empty( $product['product_fees_conditions_values'] ) && is_array( $product['product_fees_conditions_values'] ) ? array_map( 'intval', $product['product_fees_conditions_values'] ) : array();

            if ( ! empty( $condition_value ) ) {

			    if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
					foreach ( $condition_value as $product_id ) {
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
						}
					}
			    }

                if ( 'not_in' === $product['product_fees_conditions_is'] ) {
                    foreach ( $condition_value as $product_id ) {
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[ $key ]['has_fee_based_on_product'] = 'no';
                            break;
                        } else {
                            $is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
                        }
                    }
                }

			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match tag rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $tag_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @uses     wp_get_post_terms()
	 * @uses     wcpfc_pro_array_flatten()
	 *
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_tag_rule( $cart_product_ids_array, $tag_array, $general_rule_match ) {
		$tagid     = array();
		$is_passed = array();
		foreach ( $cart_product_ids_array as $product ) {
			$cart_product_tag = wp_get_post_terms( $product, 'product_tag', array( 'fields' => 'ids' ) );
			if ( isset( $cart_product_tag ) && ! empty( $cart_product_tag ) && is_array( $cart_product_tag ) ) {
				$tagid[] = $cart_product_tag;
			}
		}
		$get_tag_all = array_unique( $this->wcpfc_pro_array_flatten( $tagid ) );
		foreach ( $tag_array as $key => $tag ) {
			if ( 'is_equal_to' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
						settype( $tag_id, 'integer' );
						if ( in_array( $tag_id, $get_tag_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $tag['product_fees_conditions_is'] ) {
				if ( ! empty( $tag['product_fees_conditions_values'] ) ) {
					foreach ( $tag['product_fees_conditions_values'] as $tag_id ) {
						settype( $tag_id, 'integer' );
						if ( in_array( $tag_id, $get_tag_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_tag'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_tag', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Find unique id based on given array
	 *
	 * @param array $array
	 *
	 * @return array $result if $array is empty it will return false otherwise return array as $result
	 * @since    1.0.0
	 *
	 */
	public function wcpfc_pro_array_flatten( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$result = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = array_merge( $result, $this->wcpfc_pro_array_flatten( $value ) );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	/**
	 * Match user rules
	 *
	 * @param array  $user_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @uses     get_current_user_id()
	 *
	 * @since    1.3.3
	 *
	 * @uses     is_user_logged_in()
	 */
	public function wcpfc_pro_match_user_rule( $user_array, $general_rule_match ) {
		$current_user_id = get_current_user_id();
		$is_passed       = array();
		foreach ( $user_array as $key => $user ) {
			$user['product_fees_conditions_values'] = array_map( 'intval', $user['product_fees_conditions_values'] );
			if ( 'is_equal_to' === $user['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'yes';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'no';
				}
			}
			if ( 'not_in' === $user['product_fees_conditions_is'] ) {
				if ( in_array( $current_user_id, $user['product_fees_conditions_values'], true ) ) {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'no';
				} else {
					$is_passed[ $key ]['has_fee_based_on_user'] = 'yes';
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on cart subtotal before discount
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_total_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @uses     WC_Cart::get_subtotal()
	 *
	 * @since    1.3.3
	 *
	 */


	/**
	 * Match total spent order rules
	 *
	 * @param array  $user_role_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     is_user_logged_in()
	 *
	 */
    public function wcpfc_pro_match_total_spent_order_rule__premium_only( $total_spent_order_array, $general_rule_match ){
        global $current_user, $woocommerce_wpml;
        $resultprice 	= wc_get_customer_total_spent( $current_user->ID );
        $is_passed 	= array();
        if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
            $new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
        } else {
            $new_resultprice = $resultprice;
        }
        
        settype($new_resultprice, 'float');

        // Currency conversion by CURCY plugin
        $new_resultprice = $this->wcpfc_pro_convert_currency($new_resultprice);
        
        foreach ( $total_spent_order_array as $key => $total_spent_order ) {
            settype($total_spent_order['product_fees_conditions_values'], 'float');

            // Currency conversion by CURCY plugin
            $total_spent_order['product_fees_conditions_values'] = $this->wcpfc_pro_convert_currency($total_spent_order['product_fees_conditions_values']);

            if ( $total_spent_order['product_fees_conditions_is'] === 'is_equal_to' ) {
                if ( ! empty( $total_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $total_spent_order['product_fees_conditions_values'] === $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $total_spent_order['product_fees_conditions_is'] === 'less_equal_to' ) {
                if ( ! empty( $total_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $total_spent_order['product_fees_conditions_values'] >= $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $total_spent_order['product_fees_conditions_is'] === 'less_then' ) {
                if ( ! empty( $total_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $total_spent_order['product_fees_conditions_values'] > $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $total_spent_order['product_fees_conditions_is'] === 'greater_equal_to' ) {
                if ( ! empty( $total_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $total_spent_order['product_fees_conditions_values'] <= $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $total_spent_order['product_fees_conditions_is'] === 'greater_then' ) {
                if ( ! empty( $total_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $total_spent_order['product_fees_conditions_values'] < $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $total_spent_order['product_fees_conditions_is'] === 'not_in' ) {
                if ( ! empty( $total_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $new_resultprice === $total_spent_order['product_fees_conditions_values'] ) {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'no';
                        break;
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_total_spent_order'] = 'yes';
                    }
                }
            }
        }

        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_total_spent_order', $general_rule_match );

        return $main_is_passed;
    }

    /**
	 * Match spent order count rules
	 *
	 * @param array  $user_role_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     is_user_logged_in()
	 *
	 */
    public function wcpfc_pro_match_spent_order_count_rule__premium_only( $spent_order_count_array, $general_rule_match ){
        global $current_user;
        $user_id 		= $current_user->ID;
        $resultcount 	= $this->fees_check_order_for_user__premium_only( $user_id, true);
        $is_passed 	= array();

        settype($resultcount, 'integer');
        
        foreach ( $spent_order_count_array as $key => $spent_order_count ) {
            settype($spent_order_count['product_fees_conditions_values'], 'integer');
            if ( $spent_order_count['product_fees_conditions_is'] === 'is_equal_to' ) {
                if ( ! empty( $spent_order_count['product_fees_conditions_values'] ) ) {
                    if ( $spent_order_count['product_fees_conditions_values'] === $resultcount ) {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'no';
                        break;
                    }
                }
            }
            if ( $spent_order_count['product_fees_conditions_is'] === 'less_equal_to' ) {
                if ( ! empty( $spent_order_count['product_fees_conditions_values'] ) ) {
                    if ( $spent_order_count['product_fees_conditions_values'] >= $resultcount ) {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'no';
                        break;
                    }
                }
            }
            if ( $spent_order_count['product_fees_conditions_is'] === 'less_then' ) {
                if ( ! empty( $spent_order_count['product_fees_conditions_values'] ) ) {
                    if ( $spent_order_count['product_fees_conditions_values'] > $resultcount ) {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'no';
                        break;
                    }
                }
            }
            if ( $spent_order_count['product_fees_conditions_is'] === 'greater_equal_to' ) {
                if ( ! empty( $spent_order_count['product_fees_conditions_values'] ) ) {
                    if ( $spent_order_count['product_fees_conditions_values'] <= $resultcount ) {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'no';
                        break;
                    }
                }
            }
            if ( $spent_order_count['product_fees_conditions_is'] === 'greater_then' ) {
                if ( ! empty( $spent_order_count['product_fees_conditions_values'] ) ) {
                    if ( $spent_order_count['product_fees_conditions_values'] < $resultcount ) {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'no';
                        break;
                    }
                }
            }
            if ( $spent_order_count['product_fees_conditions_is'] === 'not_in' ) {
                if ( ! empty( $spent_order_count['product_fees_conditions_values'] ) ) {
                    if ( $resultcount === $spent_order_count['product_fees_conditions_values'] ) {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'no';
                        break;
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_spent_order_count'] = 'yes';
                    }
                }
            }
        }
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_spent_order_count', $general_rule_match );

        return $main_is_passed;
    }

    /**
	 * Match last spent order rules
	 *
	 * @param array  $user_role_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     is_user_logged_in()
	 *
	 */
    public function wcpfc_pro_match_last_spent_order_rule__premium_only( $last_spent_order_array, $general_rule_match){
        global $current_user, $woocommerce_wpml;
        $user_id 		= $current_user->ID;
        $resultprice 	= $this->fees_check_order_for_user__premium_only($user_id);
        $is_passed 	= array();

        if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
            $new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
        } else {
            $new_resultprice = $resultprice;
        }

        settype($new_resultprice, 'float');
        
        // Currency conversion by CURCY plugin
        $new_resultprice = $this->wcpfc_pro_convert_currency($new_resultprice);

        foreach ( $last_spent_order_array as $key => $last_spent_order ) {
            settype($last_spent_order['product_fees_conditions_values'], 'float');

            // Currency conversion by CURCY plugin
            $last_spent_order['product_fees_conditions_values'] = $this->wcpfc_pro_convert_currency($last_spent_order['product_fees_conditions_values']);

            if ( $last_spent_order['product_fees_conditions_is'] === 'is_equal_to' ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] === $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $last_spent_order['product_fees_conditions_is'] === 'less_equal_to' ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] >= $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $last_spent_order['product_fees_conditions_is'] === 'less_then' ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] > $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $last_spent_order['product_fees_conditions_is'] === 'greater_equal_to' ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] <= $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $last_spent_order['product_fees_conditions_is'] === 'greater_then' ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $last_spent_order['product_fees_conditions_values'] < $new_resultprice ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                        break;
                    }
                }
            }
            if ( $last_spent_order['product_fees_conditions_is'] === 'not_in' ) {
                if ( ! empty( $last_spent_order['product_fees_conditions_values'] ) ) {
                    if ( $new_resultprice === $last_spent_order['product_fees_conditions_values'] ) {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'no';
                        break;
                    } else {
                        $is_passed[ $key ]['has_fee_based_on_last_spent_order'] = 'yes';
                    }
                }
            }
        }
        
        $main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_last_spent_order', $general_rule_match );

        return $main_is_passed;
    }	 

	/**
	 * Check order condition for user
	 *
	 * @return boolean $order_check
	 * @since 2.2.0
	 *
	 */
	public function fees_check_order_for_user__premium_only( $user_id, $count = false ) {

		$user_id = !empty($user_id) ? $user_id : get_current_user_id();

		$numberposts = (!$count) ? 1 : -1;

        $args = array( 
            'customer' => $user_id,
            'status' => array( 'wc-completed', 'wc-processing' ),
            'limit' => $numberposts,
            'return' => 'ids'
        );
        $customer_orders = wc_get_orders( $args );

		// return "true" when customer has already at least one order (false if not)
		$total = 0;
		if(!$count){
			foreach ( $customer_orders as $customer_order ) {
				$order = wc_get_order( $customer_order );
				$total += $order->get_total();
			}
			return $total; 
		} else {
			return count($customer_orders);
		}
	}

	public function wcpfc_pro_match_cart_subtotal_before_discount_rule( $wc_curr_version, $cart_total_array, $general_rule_match ) {
		global $woocommerce, $woocommerce_wpml;
		if ( $wc_curr_version >= 3.0 ) {
			$total = $this->wcpfc_pro_get_cart_subtotal();
		} else {
			$total = $woocommerce->cart->subtotal;
		}		
		if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
			$new_total = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $total );
		} else {
			$new_total = $total;
		}
		
        settype( $new_total, 'float' );

		$is_passed = array();
		foreach ( $cart_total_array as $key => $cart_total ) {
			settype( $cart_total['product_fees_conditions_values'], 'float' );

            // Currency conversion by CURCY plugin
            $cart_total['product_fees_conditions_values'] = $this->wcpfc_pro_convert_currency($cart_total['product_fees_conditions_values']);            

			if ( 'is_equal_to' === $cart_total['product_fees_conditions_is'] ) {
				if ( $cart_total['product_fees_conditions_values'] >= 0 || ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] === $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $cart_total['product_fees_conditions_is'] ) {
				if ( $cart_total['product_fees_conditions_values'] >= 0 || ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] >= $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'less_then' === $cart_total['product_fees_conditions_is'] ) {
				if ( $cart_total['product_fees_conditions_values'] >= 0 || ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] > $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $cart_total['product_fees_conditions_is'] ) {
				if ( $cart_total['product_fees_conditions_values'] >= 0 || ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] <= $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $cart_total['product_fees_conditions_is'] ) {
				$cart_total['product_fees_conditions_values'];
				if ( $cart_total['product_fees_conditions_values'] >= 0 || ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $cart_total['product_fees_conditions_values'] < $new_total ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					}
				}
			}
			if ( 'not_in' === $cart_total['product_fees_conditions_is'] ) {
				if ( $cart_total['product_fees_conditions_values'] >= 0 || ! empty( $cart_total['product_fees_conditions_values'] ) ) {
					if ( $new_total === $cart_total['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_cart_total'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_total', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * get cart subtotal
	 *
	 * @return float $cart_subtotal
	 * @since  1.5.2
	 *
	 */
	public function wcpfc_pro_get_cart_subtotal() {
		$get_customer            = WC()->cart->get_customer();
		$get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
		$tax_display_cart        = WC()->cart->get_tax_price_display_mode();
		$wc_prices_include_tax   = wc_prices_include_tax();
		$tax_enable              = wc_tax_enabled();
		$cart_subtotal           = 0;
		if ( true === $tax_enable ) {
			if ( true === $wc_prices_include_tax ) {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
				} else {
					$cart_subtotal += WC()->cart->get_subtotal();
				}
			} else {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();
				} else {
					$cart_subtotal += WC()->cart->get_subtotal();
				}
			}
		} else {
			$cart_subtotal += WC()->cart->get_subtotal();
		}
		return $cart_subtotal;
	}

	/**
	 * Get the product count in each row of the cart.
	 *
	 * @return array An associative array with product IDs as keys and their quantities as values.
	 */
	function get_cart_row_totals() {

		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			return array(); // Return an empty array or handle the error as needed
		}

		$cart_items = WC()->cart->get_cart();
		$product_counts = array();

		foreach ( $cart_items as $cart_item ) {
			$product_id = $cart_item['product_id'];
			$quantity = $cart_item['quantity'];

			// Store the product ID and its quantity
			$product_counts[$product_id] = $quantity;
		}

		return $product_counts;
	}

	/**
	 * Match rule based on total cart quantity
	 *
	 * @param array $quantity_array
	 *
	 * @return array $is_passed
	 * @since    1.3.3
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function wcpfc_pro_match_cart_total_cart_qty_rule( $cart_array, $quantity_array, $general_rule_match ) {
		$quantity_total = 0;
		foreach ( $cart_array as $woo_cart_item ) {
			$quantity_total += $woo_cart_item['quantity'];
		}
		$is_passed = array();
		foreach ( $quantity_array as $key => $quantity ) {
			settype( $quantity['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					}
				}
			}
			if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_quantity'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_quantity', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match state rules
	 *
	 * @param array  $state_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @uses     WC_Customer::get_shipping_state()
	 *
	 * @since    1.3.3
	 *
	 * @uses     WC_Customer::get_shipping_country()
	 */
	public function wcpfc_pro_match_state_rules__premium_only( $state_array, $general_rule_match ) {
		$country        = WC()->customer->get_shipping_country();
		$state          = WC()->customer->get_shipping_state();
		$selected_state = $country . ':' . $state;
		$is_passed      = array();
		foreach ( $state_array as $key => $get_state ) {
			if ( 'is_equal_to' === $get_state['product_fees_conditions_is'] ) {
				if ( ! empty( $get_state['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'no';
					}
				}
			}
			if ( 'not_in' === $get_state['product_fees_conditions_is'] ) {
				if ( ! empty( $get_state['product_fees_conditions_values'] ) ) {
					if ( in_array( $selected_state, $get_state['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_state'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_state', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match postcode rules
	 *
	 * @param array  $postcode_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     WC_Customer::get_shipping_postcode()
	 *
	 */
	public function wcpfc_pro_match_postcode_rules__premium_only( $postcode_array, $general_rule_match ) {
		$selected_postcode = WC()->customer->get_shipping_postcode();
		$is_passed         = array();
		foreach ( $postcode_array as $key => $postcode ) {
			if ( 'is_equal_to' === $postcode['product_fees_conditions_is'] ) {
				if ( ! empty( $postcode['product_fees_conditions_values'] ) ) {
					$postcodestr        = str_replace( PHP_EOL, "<br/>", $postcode['product_fees_conditions_values'] );
					$postcode_val_array = explode( '<br/>', $postcodestr );
					$selected_postcode  = rtrim( $selected_postcode );
					$postcode_val_array = array_map( 'trim', $postcode_val_array );

					if ( in_array( $selected_postcode, $postcode_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';

					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';

					}
				}
			}
			if ( 'not_in' === $postcode['product_fees_conditions_is'] ) {
				if ( ! empty( $postcode['product_fees_conditions_values'] ) ) {
					$postcodestr        = str_replace( PHP_EOL, "<br/>", $postcode['product_fees_conditions_values'] );
					$postcode_val_array = explode( '<br/>', $postcodestr );
					$postcode_val_array = array_map( 'trim', $postcode_val_array );
					if ( in_array( trim( $selected_postcode ), $postcode_val_array, true ) ) {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_postcode'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_postcode', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match zone rules
	 *
	 * @param array  $zone_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     wcpfc_pro_check_zone_available()
	 *
	 */
	public function wcpfc_pro_match_zone_rules__premium_only( $zone_array, $general_rule_match ) {
		$is_passed = array();
		foreach ( $zone_array as $key => $zone ) {
			if ( 'is_equal_to' === $zone['product_fees_conditions_is'] ) {
				if ( ! empty( $zone['product_fees_conditions_values'] ) ) {
					$get_zonelist                           = $this->wcpfc_pro_check_zone_available( $zone['product_fees_conditions_values'] );
					$zone['product_fees_conditions_values'] = array_map( 'intval', $zone['product_fees_conditions_values'] );
					if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'no';
					}
				}
			}
			if ( 'not_in' === $zone['product_fees_conditions_is'] ) {
				if ( ! empty( $zone['product_fees_conditions_values'] ) ) {
					$get_zonelist                           = $this->wcpfc_pro_check_zone_available( $zone['product_fees_conditions_values'] );
					$zone['product_fees_conditions_values'] = array_map( 'intval', $zone['product_fees_conditions_values'] );
					if ( in_array( $get_zonelist, $zone['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_zone'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_zone', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Find a matching zone for a given package.
	 *
	 * @param array $available_zone_id_array
	 *
	 * @return int $return_zone_id
	 * @uses   WC_Customer::get_shipping_state()
	 * @uses   WC_Customer::get_shipping_postcode()
	 * @uses   wc_postcode_location_matcher()
	 *
	 * @since  3.0.0
	 *
	 * @uses   WC_Customer::get_shipping_country()
	 */
	public function wcpfc_pro_check_zone_available( $available_zone_id_array ) {
		$return_zone_id     = '';
		$country            = strtoupper( wc_clean( WC()->customer->get_shipping_country() ) );
		$state              = strtoupper( wc_clean( WC()->customer->get_shipping_state() ) );
		$postcode           = wc_normalize_postcode( wc_clean( WC()->customer->get_shipping_postcode() ) );
		$state_flag         = false;
		$flag               = false;
		$postcode_locations = array();
		$zone_array         = array();
		foreach ( $available_zone_id_array as $zone_id ) {
			$zone_by_id = WC_Shipping_Zones::get_zone_by( 'zone_id', $zone_id );
			$zones = array();
			if ( isset($zone_by_id) && !empty($zone_by_id) ) {
				$zones = $zone_by_id->get_zone_locations();	
			}
			if ( ! empty( $zones ) ) {
				foreach ( $zones as $zone_location ) {
					if ( 'country' === $zone_location->type || 'state' === $zone_location->type ) {
						$zone_array[ $zone_id ][ $zone_location->type ][] = $zone_location->code;
					}
					$location = new stdClass();
					if ( 'postcode' === $zone_location->type ) {
						$location->zone_id       = $zone_id;
						$location->location_code = $zone_location->code;
						if ( false !== strpos( $location->location_code, '...' ) ) {
							$postcode_locations_ex = explode( '...', $location->location_code );
							$start_index           = $postcode_locations_ex[0];
							$end_index             = $postcode_locations_ex[1];
							if ( $start_index < $end_index ) {
								$total_count = $end_index - $start_index;
								$new_index   = $start_index;
								for ( $i = 0; $i <= $total_count; $i ++ ) {
									$desh_location = new stdClass();
									if ( 0 === $i ) {
										$new_index = $start_index;
									} elseif ( $total_count === $i ) {
										$new_index = $end_index;
									} else {
										$new_index += 1;
									}
									$desh_location->zone_id = $zone_id;
									settype( $new_index, 'string' );
									$desh_location->location_code         = $new_index;
									$postcode_locations[ $zone_id ][ $i ] = $desh_location;
								}
							}
						} else {
							$postcode_locations[ $zone_id ][] = $location;
						}
					}
				}
			}
		}
		if ( ! empty( $zone_array ) ) {
			foreach ( $zone_array as $zone_id => $zone_location_detail ) {
				foreach ( $zone_location_detail as $zone_location_type => $zone_location_code ) {
					if ( 'country' === $zone_location_type ) {
						if ( $postcode_locations ) {
							foreach ( $postcode_locations as $post_zone_id => $postcode_location_detail ) {
								if ( $zone_id === $post_zone_id ) {
									if ( in_array( $country, $zone_location_code, true ) ) {
										$flag = 1;
									}
								} else {
									if ( in_array( $country, $zone_location_code, true ) ) {
										$return_zone_id = $zone_id;
									}
								}
							}
						} else {
							if ( in_array( $country, $zone_location_code, true ) ) {
								$return_zone_id = $zone_id;
							}
						}
					}
					if ( 'state' === $zone_location_type ) {
						$state_array = array();
						foreach ( $zone_location_code as $subzone_location_code ) {
							if ( false !== strpos( $subzone_location_code, ':' ) ) {
								$sub_zone_location_code_explode = explode( ':', $subzone_location_code );
							}
							$state_array[] = $sub_zone_location_code_explode[1];
							if ( ! $postcode_locations ) {
								if ( in_array( $state, $state_array, true ) ) {
									$return_zone_id = $zone_id;
									$state_flag     = true;
								}
							} else {
								if ( in_array( $state, $state_array, true ) ) {
									$flag = 1;
								}
							}
						}
					}
				}
			}
		} else {
			if ( $postcode_locations ) {
				$flag = 1;
			}
		}

		if ( true === $state_flag || 1 === $flag ) {
			if ( $postcode_locations ) {
				foreach ( $postcode_locations as $post_zone_id => $postcode_location_detail ) {
					$matches       = wc_postcode_location_matcher( $postcode, $postcode_location_detail, 'zone_id', 'location_code', $country );
					$matches_count = count( $matches );
					if ( 0 !== $matches_count ) {
						$matches_array_key = array_keys( $matches );
						$return_zone_id    = $matches_array_key[0];
					} else {
						$return_zone_id = '';
					}
				}
			}
		}

		return $return_zone_id;
	}

	/**
	 * Match variable products rules
	 *
	 * @param array $cart_product_ids_array
	 * @param array $variableproduct_array
	 * * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_variable_products_rule( $cart_product_ids_array, $variableproduct_array, $general_rule_match ) {

		$is_passed      = array();

		foreach ( $variableproduct_array as $key => $product ) {
            
            $condition_value = !empty( $product['product_fees_conditions_values'] ) && is_array( $product['product_fees_conditions_values'] ) ? array_map( 'intval', $product['product_fees_conditions_values'] ) : array();

            if ( ! empty( $condition_value ) ) {

			    if ( 'is_equal_to' === $product['product_fees_conditions_is'] ) {
					foreach ( $condition_value as $product_id ) {
						if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_product'] = 'no';
						}
					}
				}

                if ( 'not_in' === $product['product_fees_conditions_is'] ) {
                    foreach ( $condition_value as $product_id ) {
                        if ( in_array( $product_id, $cart_product_ids_array, true ) ) {
                            $is_passed[ $key ]['has_fee_based_on_product'] = 'no';
                            break;
                        } else {
                            $is_passed[ $key ]['has_fee_based_on_product'] = 'yes';
                        }
                    }
                }
            }
		}

		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_product', $general_rule_match );

		return $main_is_passed;
	}

    /**
	 * Match Brand rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $brand_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     wcpfc_pro_array_flatten()
	 *
	 * @since    4.3.0
	 *
	 * @uses     wp_get_post_terms()
	 */
	public function wcpfc_pro_match_brand_rule__premium_only( $cart_product_ids_array, $brand_array, $general_rule_match ) {
		$is_passed              = array();
		$cart_brand_id_array = array();

		foreach ( $cart_product_ids_array as $product ) {
			$cart_product_brand = wp_get_post_terms( $product, 'product_brand', array( 'fields' => 'ids' ) );
			if ( isset( $cart_product_brand ) && ! empty( $cart_product_brand ) && is_array( $cart_product_brand ) ) {
				$cart_brand_id_array[] = $cart_product_brand;
			}
		}
		$get_brand_all = array_unique( $this->wcpfc_pro_array_flatten( $cart_brand_id_array ) );
		foreach ( $brand_array as $key => $brand ) {
			if ( 'is_equal_to' === $brand['product_fees_conditions_is'] ) {
				if ( ! empty( $brand['product_fees_conditions_values'] ) ) {
					foreach ( $brand['product_fees_conditions_values'] as $brand_id ) {
						settype( $brand_id, 'integer' );
						if ( in_array( $brand_id, $get_brand_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_brand'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_brand'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $brand['product_fees_conditions_is'] ) {
				if ( ! empty( $brand['product_fees_conditions_values'] ) ) {
					foreach ( $brand['product_fees_conditions_values'] as $brand_id ) {
						settype( $brand_id, 'integer' );
						if ( in_array( $brand_id, $get_brand_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_brand'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_brand'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_brand', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match wlf_location rules (Custom Support #104847 - Location based fee)
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $wlf_location_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     wcpfc_pro_array_flatten()
	 *
	 * @since    4.3.0
	 *
	 * @uses     wp_get_post_terms()
	 */
	public function wcpfc_pro_match_wlf_location_rule__premium_only( $cart_product_ids_array, $wlf_location_array, $general_rule_match ) {
		$is_passed              = array();
		$cart_wlf_location_id_array = array();
		foreach ( $cart_product_ids_array as $product ) {
			$cart_product_wlf_location = wp_get_post_terms( $product, 'location', array( 'fields' => 'ids' ) );
			if ( isset( $cart_product_wlf_location ) && ! empty( $cart_product_wlf_location ) && is_array( $cart_product_wlf_location ) ) {
				$cart_wlf_location_id_array[] = $cart_product_wlf_location;
			}
		}

		$get_wlf_location_all = array_unique( $this->wcpfc_pro_array_flatten( $cart_wlf_location_id_array ) );

		$selected_location = function_exists( 'wlf_get_location_from_cookie' ) ? wlf_get_location_from_cookie() : '';

		// Check if a valid location is selected
		$selected_location_id = 0;
	    if ( ! empty( $selected_location ) && $selected_location !== 'all' ) {
	        $location_term = get_term_by('slug', $selected_location, 'location');
	        if ( $location_term && ! is_wp_error( $location_term ) ) {
	            $selected_location_id = $location_term->term_id;
	        }
	    }

	    // If a valid location is selected, filter out other locations
		if ( ! empty( $selected_location_id ) ) {
			$get_wlf_location_all = array_intersect( $get_wlf_location_all, [ $selected_location_id ] );
		}

		foreach ( $wlf_location_array as $key => $wlf_location ) {
			if ( 'is_equal_to' === $wlf_location['product_fees_conditions_is'] ) {
				if ( ! empty( $wlf_location['product_fees_conditions_values'] ) ) {
					foreach ( $wlf_location['product_fees_conditions_values'] as $wlf_location_id ) {
						settype( $wlf_location_id, 'integer' );
						if ( in_array( $wlf_location_id, $get_wlf_location_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_wlf_location'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_wlf_location'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $wlf_location['product_fees_conditions_is'] ) {
				if ( ! empty( $wlf_location['product_fees_conditions_values'] ) ) {
					foreach ( $wlf_location['product_fees_conditions_values'] as $wlf_location_id ) {
						settype( $wlf_location_id, 'integer' );
						if ( in_array( $wlf_location_id, $get_wlf_location_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_wlf_location'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_wlf_location'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_wlf_location', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match category rules
	 *
	 * @param array  $cart_product_ids_array
	 * @param array  $category_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @uses     wcpfc_pro_array_flatten()
	 *
	 * @since    1.3.3
	 *
	 * @uses     wp_get_post_terms()
	 */
	public function wcpfc_pro_match_category_rule__premium_only( $cart_product_ids_array, $category_array, $general_rule_match ) {
		$is_passed              = array();
		$cart_category_id_array = array();
		foreach ( $cart_product_ids_array as $product ) {
			$cart_product_category = wp_get_post_terms( $product, 'product_cat', array( 'fields' => 'ids' ) );
			if ( isset( $cart_product_category ) && ! empty( $cart_product_category ) && is_array( $cart_product_category ) ) {
				$cart_category_id_array[] = $cart_product_category;
			}
		}
		$get_cat_all = array_unique( $this->wcpfc_pro_array_flatten( $cart_category_id_array ) );
		foreach ( $category_array as $key => $category ) {
			if ( 'is_equal_to' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $category['product_fees_conditions_values'] as $category_id ) {
						settype( $category_id, 'integer' );
						if ( in_array( $category_id, $get_cat_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $category['product_fees_conditions_is'] ) {
				if ( ! empty( $category['product_fees_conditions_values'] ) ) {
					foreach ( $category['product_fees_conditions_values'] as $category_id ) {
						settype( $category_id, 'integer' );
						if ( in_array( $category_id, $get_cat_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_category'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_category', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match specific product quantity rules
	 *
	 * @param int    $shipping_method_id_val
	 * @param array  $cart_array
	 * @param array  $product_qty_array
	 * @param string $general_rule_match
	 *
	 * @param string $default_lang
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 */
	public function wcpfc_pro_match_product_qty_rule( $fees_id, $cart_array, $product_qty_array, $general_rule_match, $sitepress, $default_lang ) {
		$products_based_qty = 0;
		$products_based_qty = $this->wcpfc_pro_fees_per_qty_on_ap_rules_off( $fees_id, $cart_array, $products_based_qty, 0, $sitepress, $default_lang, $general_rule_match );
		$main_is_passed     = $this->wcpfc_pro_match_product_based_qty_rule( $products_based_qty[0], $product_qty_array, $general_rule_match );
		return $main_is_passed;
	}

	/**
	 * Match rule based on product qty
	 *
	 * @param array  $cart_array
	 * @param array  $quantity_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 * @since    3.4
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function wcpfc_pro_match_product_based_qty_rule( $product_qty, $quantity_array, $general_rule_match ) {
		$quantity_total = 0;
		if ( 0 < $product_qty ) {
			$quantity_total = $product_qty;
		}
		$is_passed = array();
		foreach ( $quantity_array as $key => $quantity ) {
			settype( $quantity['product_fees_conditions_values'], 'integer' );
			if ( 'is_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] >= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'less_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] > $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] <= $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity['product_fees_conditions_values'] < $quantity_total ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					}
				}
			}
			if ( 'not_in' === $quantity['product_fees_conditions_is'] ) {
				if ( ! empty( $quantity['product_fees_conditions_values'] ) ) {
					if ( $quantity_total === $quantity['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_product_qty'] = 'yes';
					}
				}
			}
		}
		/**
		 * Filter for matched all passed rules.
		 *
		 * @since  3.8
		 *
		 * @author jb
		 */
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule(
			apply_filters(
				'wcpfc_pro_match_product_based_qty_rule_ft',
				$is_passed,
				$product_qty,
				$quantity_array,
				'has_fee_based_on_product_qty',
				$general_rule_match
			),
			'has_fee_based_on_product_qty',
			$general_rule_match
		);
		return $main_is_passed;
	}

	/**
	 * Match user role rules
	 *
	 * @param array  $user_role_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     is_user_logged_in()
	 *
	 */
	public function wcpfc_pro_match_user_role_rule__premium_only( $user_role_array, $general_rule_match ) {
		/**
		 * check user loggedin or not
		 */
		global $current_user;
		$current_user_role = is_user_logged_in() ? $current_user->roles : ['guest'];
		$is_passed = array();
		foreach ( $user_role_array as $key => $user_role ) {
			foreach ($current_user_role as $crnt_user_role) {
				if ( 'is_equal_to' === $user_role['product_fees_conditions_is'] ) {
					if ( in_array( $crnt_user_role, $user_role['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_user_role'] = 'yes';
						break; 
					} else {
						$is_passed[ $key ]['has_fee_based_on_user_role'] = 'no';
					}
				}
				if ( 'not_in' === $user_role['product_fees_conditions_is'] ) {
					if ( in_array( $crnt_user_role, $user_role['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_user_role'] = 'no';
						break;
					} else {
						$is_passed[ $key ]['has_fee_based_on_user_role'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_user_role', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match coupon role rules
	 *
	 * @param string $wc_curr_version
	 * @param array  $coupon_array
	 * @param string $general_rule_match
	 *
	 * @return string $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 * @uses     WC_Cart::get_coupons()
	 * @uses     WC_Coupon::is_valid()
	 *
	 */
	public function wcpfc_pro_match_coupon_rule__premium_only( $wc_curr_version, $coupon_array, $general_rule_match ) {
		global $woocommerce;
		if ( $wc_curr_version >= 3.0 ) {
			$cart_coupon = WC()->cart->get_coupons();
		} else {
			$cart_coupon = isset( $woocommerce->cart->coupons ) && ! empty( $woocommerce->cart->coupons ) ? $woocommerce->cart->coupons : array();
		}
		$couponId  = array();
		$is_passed = array();
		foreach ( $cart_coupon as $cartCoupon ) {
			if ( $cartCoupon->is_valid() && isset( $cartCoupon ) && ! empty( $cartCoupon ) ) {
				if ( $wc_curr_version >= 3.0 ) {
					$couponId[] = $cartCoupon->get_id();
				} else {
					$couponId[] = $cartCoupon->id;
				}
			}
		}		

		foreach ( $coupon_array as $key => $coupon ) {
			if ( ! empty( $coupon['product_fees_conditions_values'] ) ) {
				$product_fees_conditions_values = array_map( 'intval', $coupon['product_fees_conditions_values'] );

				if ( 'is_equal_to' === $coupon['product_fees_conditions_is'] ) {
					if( in_array( -1, $product_fees_conditions_values, true ) && !empty( $couponId ) ){
						$is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
						break;
					}
					foreach ( $product_fees_conditions_values as $coupon_id ) {
						settype( $coupon_id, 'integer' );
						if ( in_array( $coupon_id, $couponId, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
						}
					}
				}
				if ( 'not_in' === $coupon['product_fees_conditions_is'] ) {
					if( in_array( -1, $product_fees_conditions_values, true ) && !empty( $couponId ) ){
						$is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
						break;
					} else {
                        foreach ( $product_fees_conditions_values as $coupon_id ) {
                            settype( $coupon_id, 'integer' );
                            if ( in_array( $coupon_id, $couponId, true ) ) {
                                $is_passed[ $key ]['has_fee_based_on_coupon'] = 'no';
                                break;
                            } else {
                                $is_passed[ $key ]['has_fee_based_on_coupon'] = 'yes';
                            }
                        }
                    }
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_coupon', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on cart subtotal after discount
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_totalafter_array
	 *
	 * @return array $is_passed
	 * @uses     WC_Cart::get_total_discount()
	 *
	 * @since    1.3.3
	 *
	 * @uses     wcpfc_pro_remove_currency_symbol()
	 * @uses     WC_Cart::get_subtotal()
	 */
	public function wcpfc_pro_match_cart_subtotal_after_discount_rule__premium_only( $wc_curr_version, $cart_totalafter_array, $general_rule_match ) {
		global $woocommerce, $woocommerce_wpml;
        $is_passed = array();

		if ( $wc_curr_version >= 3.0 ) {
			$totalprice = $this->wcpfc_pro_get_cart_subtotal();
			$totaldisc = $this->wcpfc_pro_remove_currency_symbol( WC()->cart->get_total_discount() );
		} else {
			$totalprice = $this->wcpfc_pro_remove_currency_symbol( $woocommerce->cart->get_cart_subtotal() );
			$totaldisc = $this->wcpfc_pro_remove_currency_symbol( $woocommerce->cart->get_total_discount() );
		}

		if ( '' !== $totaldisc && 0.0 !== $totaldisc ) {

			$resultprice = $totalprice - $totaldisc;
			if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
				$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
			} else {
				$new_resultprice = $resultprice;
			}
            
			foreach ( $cart_totalafter_array as $key => $cart_totalafter ) {
				settype( $cart_totalafter['product_fees_conditions_values'], 'float' );

                // Currency conversion by CURCY plugin
                $cart_totalafter['product_fees_conditions_values'] = $this->wcpfc_pro_convert_currency($cart_totalafter['product_fees_conditions_values']);

				if ( 'is_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( $cart_totalafter['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] === $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( $cart_totalafter['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] >= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( $cart_totalafter['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] > $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( $cart_totalafter['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] <= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( $cart_totalafter['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $cart_totalafter['product_fees_conditions_values'] < $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_totalafter['product_fees_conditions_is'] ) {
					if ( $cart_totalafter['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalafter['product_fees_conditions_values'] ) ) {
						if ( $new_resultprice === $cart_totalafter['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_totalafter'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_totalafter', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on cart subtotal for specific products
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_specificproduct_array
	 *
	 * @return array $is_passed
	 * @uses     WC_Cart::get_total_discount()
	 *
	 * @since    1.3.3
	 *
	 * @uses     wcpfc_pro_remove_currency_symbol()
	 * @uses     WC_Cart::get_subtotal()
	 */
	public function wcpfc_pro_match_cart_subtotal_specific_product_rule__premium_only( $wc_curr_version, $cart_specificproduct_array, $general_rule_match, $products_based_counts ) {

		global $woocommerce_wpml;
		$totalprice = 0;
       
        if( is_array($products_based_counts) && isset( $products_based_counts[1]) && !empty( $products_based_counts[1] ) && is_numeric($products_based_counts[1]) ){
            $totalprice = $this->wcpfc_pro_remove_currency_symbol( $products_based_counts[1] );
        }

		$is_passed = array();
		if ( '' !== $totalprice && 0.0 !== $totalprice ) {

			$resultprice = $totalprice;
			if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
				$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
			} else {
				$new_resultprice = $resultprice;
			}
            
			foreach ( $cart_specificproduct_array as $key => $cart_specificproduct ) {
				settype( $cart_specificproduct['product_fees_conditions_values'], 'float' );

                // Currency conversion by CURCY plugin
                $cart_specificproduct['product_fees_conditions_values'] = $this->wcpfc_pro_convert_currency($cart_specificproduct['product_fees_conditions_values']);

				if ( 'is_equal_to' === $cart_specificproduct['product_fees_conditions_is'] ) {
					if ( $cart_specificproduct['product_fees_conditions_values'] >= 0 || ! empty( $cart_specificproduct['product_fees_conditions_values'] ) ) {
						if ( $cart_specificproduct['product_fees_conditions_values'] === $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_specificproduct['product_fees_conditions_is'] ) {
					if ( $cart_specificproduct['product_fees_conditions_values'] >= 0 || ! empty( $cart_specificproduct['product_fees_conditions_values'] ) ) {
						if ( $cart_specificproduct['product_fees_conditions_values'] >= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_specificproduct['product_fees_conditions_is'] ) {
					if ( $cart_specificproduct['product_fees_conditions_values'] >= 0 || ! empty( $cart_specificproduct['product_fees_conditions_values'] ) ) {
						if ( $cart_specificproduct['product_fees_conditions_values'] > $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_specificproduct['product_fees_conditions_is'] ) {
					if ( $cart_specificproduct['product_fees_conditions_values'] >= 0 || ! empty( $cart_specificproduct['product_fees_conditions_values'] ) ) {
						if ( $cart_specificproduct['product_fees_conditions_values'] <= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_specificproduct['product_fees_conditions_is'] ) {
					if ( $cart_specificproduct['product_fees_conditions_values'] >= 0 || ! empty( $cart_specificproduct['product_fees_conditions_values'] ) ) {
						if ( $cart_specificproduct['product_fees_conditions_values'] < $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_specificproduct['product_fees_conditions_is'] ) {
					if ( $cart_specificproduct['product_fees_conditions_values'] >= 0 || ! empty( $cart_specificproduct['product_fees_conditions_values'] ) ) {
						if ( $new_resultprice === $cart_specificproduct['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_specificproduct'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_specificproduct', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on cart subtotal excluding taxes
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_totalexclude_tax
	 *
	 */
	public function wcpfc_pro_match_cart_subtotal_excluding_tax_rule__premium_only( $wc_curr_version, $cart_totalexclude_tax_array, $general_rule_match ) {

		global $woocommerce, $woocommerce_wpml;
        $is_passed = array();
        $tax_amount = 0;

		if ( $wc_curr_version >= 3.0 ) {
			$totalprice = $this->wcpfc_pro_get_cart_subtotal();
		} else {
			$totalprice = $this->wcpfc_pro_remove_currency_symbol( $woocommerce->cart->get_cart_subtotal() );
		}
		$tax_totals = WC()->cart->get_tax_totals();
		// Accessing the amount
		foreach ( $tax_totals as $tax ) {
			$tax_amount = $tax->amount;
		}

		if ( '' !== $tax_amount && 0.0 !== $tax_amount ) {
			$resultprice = $totalprice - $tax_amount;
			if ( isset( $woocommerce_wpml ) && ! empty( $woocommerce_wpml->multi_currency ) ) {
				$new_resultprice = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $resultprice );
			} else {
				$new_resultprice = $resultprice;
			}
            
			foreach ( $cart_totalexclude_tax_array as $key => $cart_totalexclude_tax ) {
				settype( $cart_totalexclude_tax['product_fees_conditions_values'], 'float' );

                // Currency conversion by CURCY plugin
                $cart_totalexclude_tax['product_fees_conditions_values'] = $this->wcpfc_pro_convert_currency($cart_totalexclude_tax['product_fees_conditions_values']);

				if ( 'is_equal_to' === $cart_totalexclude_tax['product_fees_conditions_is'] ) {
					if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalexclude_tax['product_fees_conditions_values'] ) ) {
						if ( $cart_totalexclude_tax['product_fees_conditions_values'] === $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_totalexclude_tax['product_fees_conditions_is'] ) {
					if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalexclude_tax['product_fees_conditions_values'] ) ) {
						if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_totalexclude_tax['product_fees_conditions_is'] ) {
					if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalexclude_tax['product_fees_conditions_values'] ) ) {
						if ( $cart_totalexclude_tax['product_fees_conditions_values'] > $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_totalexclude_tax['product_fees_conditions_is'] ) {
					if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalexclude_tax['product_fees_conditions_values'] ) ) {
						if ( $cart_totalexclude_tax['product_fees_conditions_values'] <= $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_totalexclude_tax['product_fees_conditions_is'] ) {
					if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalexclude_tax['product_fees_conditions_values'] ) ) {
						if ( $cart_totalexclude_tax['product_fees_conditions_values'] < $new_resultprice ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_totalexclude_tax['product_fees_conditions_is'] ) {
					if ( $cart_totalexclude_tax['product_fees_conditions_values'] >= 0 || ! empty( $cart_totalexclude_tax['product_fees_conditions_values'] ) ) {
						if ( $new_resultprice === $cart_totalexclude_tax['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_total_excluding_tax'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_total_excluding_tax', $general_rule_match );

		return $main_is_passed;

	}

	/**
	 * Match rule based on cart subtotal excluding taxes
	 *
	 * @param string $wc_curr_version
	 * @param array  $cart_rowtotal
	 *
	 */
	public function wcpfc_pro_match_cart_row_total_rule__premium_only( $wc_curr_version, $cart_rowtotal_array, $general_rule_match ) {

        $is_passed = array();

		$product_counts = count($this->get_cart_row_totals());
		

		if ( '' !== $product_counts && 0 !== $product_counts ) {
			
			foreach ( $cart_rowtotal_array as $key => $cart_rowtotal ) {

				settype( $cart_rowtotal['product_fees_conditions_values'], 'integer' );

				if ( 'is_equal_to' === $cart_rowtotal['product_fees_conditions_is'] ) {
					if ( $cart_rowtotal['product_fees_conditions_values'] >= 0 || ! empty( $cart_rowtotal['product_fees_conditions_values'] ) ) {
						if ( $cart_rowtotal['product_fees_conditions_values'] === $product_counts ) {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'no';
						}
					}
				}
				if ( 'less_equal_to' === $cart_rowtotal['product_fees_conditions_is'] ) {
					if ( $cart_rowtotal['product_fees_conditions_values'] >= 0 || ! empty( $cart_rowtotal['product_fees_conditions_values'] ) ) {
						if ( $cart_rowtotal['product_fees_conditions_values'] >= $product_counts ) {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'no';
						}
					}
				}
				if ( 'less_then' === $cart_rowtotal['product_fees_conditions_is'] ) {
					if ( $cart_rowtotal['product_fees_conditions_values'] >= 0 || ! empty( $cart_rowtotal['product_fees_conditions_values'] ) ) {
						if ( $cart_rowtotal['product_fees_conditions_values'] > $product_counts ) {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'no';
						}
					}
				}
				if ( 'greater_equal_to' === $cart_rowtotal['product_fees_conditions_is'] ) {
					if ( $cart_rowtotal['product_fees_conditions_values'] >= 0 || ! empty( $cart_rowtotal['product_fees_conditions_values'] ) ) {
						if ( $cart_rowtotal['product_fees_conditions_values'] <= $product_counts ) {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'no';
						}
					}
				}
				if ( 'greater_then' === $cart_rowtotal['product_fees_conditions_is'] ) {
					if ( $cart_rowtotal['product_fees_conditions_values'] >= 0 || ! empty( $cart_rowtotal['product_fees_conditions_values'] ) ) {
						if ( $cart_rowtotal['product_fees_conditions_values'] < $product_counts ) {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'yes';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'no';
						}
					}
				}
				if ( 'not_in' === $cart_rowtotal['product_fees_conditions_is'] ) {
					if ( $cart_rowtotal['product_fees_conditions_values'] >= 0 || ! empty( $cart_rowtotal['product_fees_conditions_values'] ) ) {
						if ( $product_counts === $cart_rowtotal['product_fees_conditions_values'] ) {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'no';
						} else {
							$is_passed[ $key ]['has_fee_based_on_cart_row_total'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_cart_row_total', $general_rule_match );

		return $main_is_passed;

	}

	/**
	 * Remove WooCommerce currency symbol
	 *
	 * @param float $price
	 *
	 * @return float $new_price2
	 * @since  1.0.0
	 *
	 * @uses   get_woocommerce_currency_symbol()
	 *
	 */
	public function wcpfc_pro_remove_currency_symbol( $price ) {
        $args  = array(
            'decimal_separator'  => wc_get_price_decimal_separator(),
            'thousand_separator' => wc_get_price_thousand_separator(),
        );

        $wc_currency_symbol = get_woocommerce_currency_symbol();
        $cleanText          = wp_strip_all_tags($price);
		$new_price          = str_replace( $wc_currency_symbol, '', $cleanText );

        $tnew_price         = str_replace( $args['thousand_separator'], '', $new_price);
        $dnew_price         = str_replace( $args['decimal_separator'], '.', $tnew_price);
        $new_price2         = preg_replace( '/[^.\d]/', '', $dnew_price );
        
		return $new_price2;
	}

	/**
	 * Match rule based on total cart weight
	 *
	 * @param array $weight_array
	 *
	 * @return array $is_passed
	 * @since    1.3.3
	 *
	 * @uses     WC_Cart::get_cart()
	 *
	 */
	public function wcpfc_pro_match_cart_total_weight_rule__premium_only( $cart_array, $weight_array, $general_rule_match ) {
		$weight_total = 0;
		foreach ( $cart_array as $woo_cart_item ) {
			if ( ! empty( $woo_cart_item['variation_id'] ) || 0 !== $woo_cart_item['variation_id'] ) {
				$product_id_lan = $woo_cart_item['variation_id'];
			} else {
				$product_id_lan = $woo_cart_item['product_id'];
			}
			$_product     = wc_get_product( $product_id_lan );
			$weight_total += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
		}
		$is_passed = array();
		foreach ( $weight_array as $key => $weight ) {
			settype( $weight_total, 'float' );
			settype( $weight['product_fees_conditions_values'], 'float' );
			if ( 'is_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total === $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'less_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] >= $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'less_then' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] > $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'greater_equal_to' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight['product_fees_conditions_values'] <= $weight_total ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'greater_then' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total > $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					}
				}
			}
			if ( 'not_in' === $weight['product_fees_conditions_is'] ) {
				if ( ! empty( $weight['product_fees_conditions_values'] ) ) {
					if ( $weight_total === $weight['product_fees_conditions_values'] ) {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_weight'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_weight', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on shipping class
	 *
	 * @param array $cart_array
	 * @param array $shipping_class_array
	 *
	 * @return array $is_passed
	 * @since    1.3.3
	 *
	 * @uses     get_the_terms()
	 * @uses     wcpfc_pro_array_flatten()
	 *
	 */
	public function wcpfc_pro_match_shipping_class_rule__premium_only( $cart_product_ids_array, $shipping_class_array, $general_rule_match ) {
		$shippingclass = array();
		foreach ( $cart_product_ids_array as $product ) {
			$get_shipping_class = wp_get_post_terms( $product, 'product_shipping_class', array( 'fields' => 'ids' ) );
            //if on variation choose "Same as parent" option then check for parent(variable) product shipping class
            if( empty($get_shipping_class) ) {
                $product_obj = wc_get_product( $product );
                //Check that product is variation or not
                if( $product_obj->is_type('variation') ) {
                    $parent_product_id = $product_obj->get_parent_id();
                    $get_shipping_class = wp_get_post_terms( $parent_product_id, 'product_shipping_class', array( 'fields' => 'ids' ) );
                }
            }
			if ( isset( $get_shipping_class ) && ! empty( $get_shipping_class ) && is_array( $get_shipping_class ) ) {
				$shippingclass[] = $get_shipping_class;
			}
		}
		$get_shipping_class_all = array_unique( $this->wcpfc_pro_array_flatten( $shippingclass ) );
		$is_passed              = array();
		foreach ( $shipping_class_array as $key => $shipping_class ) {
			if ( 'is_equal_to' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
					foreach ( $shipping_class['product_fees_conditions_values'] as $shipping_class_slug ) {
						$shipping_class_id = $shipping_class_slug;
						settype( $shipping_class_id, 'integer' );
						if ( in_array( $shipping_class_id, $get_shipping_class_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
						}
					}
				}
			}
			if ( 'not_in' === $shipping_class['product_fees_conditions_is'] ) {
				if ( ! empty( $shipping_class['product_fees_conditions_values'] ) ) {
					foreach ( $shipping_class['product_fees_conditions_values'] as $shipping_class_slug ) {
						$shipping_class_id = $shipping_class_slug;
						settype( $shipping_class_id, 'integer' );
						if ( in_array( $shipping_class_id, $get_shipping_class_all, true ) ) {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'no';
							break;
						} else {
							$is_passed[ $key ]['has_fee_based_on_shipping_class'] = 'yes';
						}
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_shipping_class', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on payment gateway
	 *
	 * @param int   $wc_curr_version
	 * @param array $payment_gateway
	 *
	 * @return array $is_passed
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_payment_gateway_rule__premium_only( $payment_methods_array, $general_rule_match ) {
		$is_passed             = array();
		$chosen_payment_method = WC()->session->get( 'chosen_payment_method' );
		if ( ! empty( $payment_methods_array ) ) {
			foreach ( $payment_methods_array as $key => $payment ) {
				if ( $payment['product_fees_conditions_is'] === 'is_equal_to' ) {
					if ( in_array( $chosen_payment_method, $payment['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_payment'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_payment'] = 'no';
					}
				}
				if ( $payment['product_fees_conditions_is'] === 'not_in' ) {
					if ( in_array( $chosen_payment_method, $payment['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_payment'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_payment'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_payment', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match rule based on shipping method
	 *
	 * @param int   $wc_curr_version
	 * @param array $shipping_methods
	 *
	 * @return array $is_passed
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_shipping_method_rule__premium_only( $wc_curr_version, $shipping_methods, $general_rule_match ) {
		global $woocommerce;
		$is_passed = array();
		if ( $wc_curr_version >= 3.0 ) {
			$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		} else {
			$chosen_shipping_methods = $woocommerce->session->chosen_shipping_methods;
		}
		
		if ( ! empty( $chosen_shipping_methods ) ) {
			// Make plugin compatibility with "UPS Live Rates and Access Points" plugin.
			if ( false !== strpos( $chosen_shipping_methods[0], 'flexible_shipping_ups' ) ) {
				// Split the string based on ":"
				$chosen_shipping_method = explode(":", $chosen_shipping_methods[0]);

				// Take the first and second part of the array
				$chosen_shipping_methods = array( $chosen_shipping_method[0] . ":" . $chosen_shipping_method[1] );
			}
			
			// Check shipping methods to add fee
			foreach ( $shipping_methods as $key => $method ) {
				if ( 'is_equal_to' === $method['product_fees_conditions_is'] ) {
					if ( in_array( $chosen_shipping_methods[0], $method['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_shipping_method'] = 'yes';
					} else {
						$is_passed[ $key ]['has_fee_based_on_shipping_method'] = 'no';
					}
				}
				if ( 'not_in' === $method['product_fees_conditions_is'] ) {
					if ( in_array( $chosen_shipping_methods[0], $method['product_fees_conditions_values'], true ) ) {
						$is_passed[ $key ]['has_fee_based_on_shipping_method'] = 'no';
					} else {
						$is_passed[ $key ]['has_fee_based_on_shipping_method'] = 'yes';
					}
				}
			}
		}
		$main_is_passed = $this->wcpfc_pro_check_all_passed_general_rule( $is_passed, 'has_fee_based_on_shipping_method', $general_rule_match );

		return $main_is_passed;
	}

	/**
	 * Match product per qty rules
	 *
	 * @param array  $get_condition_array_ap_product
	 * @param array  $cart_products_array
	 * @param string $default_lang
	 *
	 * @return array $is_passed_advance_rule
	 * @since    1.3.3
	 *
	 * @uses     wcpfc_count_qty_for_product()
	 *
	 */
	public function wcpfc_pro_match_product_per_qty__premium_only( $get_condition_array_ap_product, $woo_cart_array, $sitepress, $default_lang, $cost_on_product_rule_match ) {
		$per_product_cost = 0;
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_prd = array();
			if ( ! empty( $get_condition_array_ap_product ) || '' !== $get_condition_array_ap_product ) {
				foreach ( $get_condition_array_ap_product as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_products'] ) || '' !== $get_condition['ap_fees_products'] ) {
						$total_qws                 = $this->wcpfc_get_count_qty__premium_only(
							$get_condition['ap_fees_products'], $woo_cart_array, $sitepress, $default_lang, 'product', 'qty'
						);
						$total_sub                 = $this->wcpfc_get_count_qty__premium_only(
							$get_condition['ap_fees_products'], $woo_cart_array, $sitepress, $default_lang, 'product', 'subtotal'
						);
						$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_prd_min_qty'], $get_condition['ap_fees_ap_prd_max_qty'], $get_condition['ap_fees_ap_price_product'], 'qty'
						);
						$is_passed_from_here_prd[] = $this->wcpfc_check_passed_rule__premium_only(
							$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_cost_per_prd_qty', 'has_fee_based_on_cost_per_prd_price', $get_condition['ap_fees_ap_price_product'], $total_qws, 'qty'
						);
						if( isset($get_condition['ap_fees_ap_per_product']) && !empty($get_condition['ap_fees_ap_per_product']) && "yes" === $get_condition['ap_fees_ap_per_product'] && "yes" === $is_passed_from_here_prd[$key]['has_fee_based_on_cost_per_prd_qty'][$key] ){
							$per_product_cost += $this->wcpfc_check_percantage_price__premium_only( $get_condition['ap_fees_ap_price_product'], $total_sub );
							$is_passed_from_here_prd[$key]['skip_as_apply_on_product'][$key] = 'yes';
						}
					}
				}
			}
            
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_prd, 'has_fee_based_on_cost_per_prd_qty', 'has_fee_based_on_cost_per_prd_price', $cost_on_product_rule_match
			);

			if( $per_product_cost > 0 && "yes" === $main_is_passed['flag'] ){
				$main_is_passed['total_amount'] += $per_product_cost;
			}
		}
        return $main_is_passed;
	}

	/**
	 * Count qty for Product, Category and Total Cart
	 *
	 * @param array  $ap_selected_id
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $type
	 * @param string $qws
	 *
	 * @return int $total
	 *
	 * @since 3.6
	 *
	 * @uses  wc_get_product()
	 * @uses  WC_Product::is_type()
	 * @uses  wp_get_post_terms()
	 * @uses  wcpfc_get_prd_category_from_cart__premium_only()
	 *
	 */
	public function wcpfc_get_count_qty__premium_only( $ap_selected_id, $woo_cart_array, $sitepress, $default_lang, $type, $qws ) {
		$total_qws = 0;
		if ( 'shipping_class' !== $type ) {
			$ap_selected_id = array_map( 'intval', $ap_selected_id );
		}
		foreach ( $woo_cart_array as $woo_cart_item ) {
			$main_product_id_lan = $woo_cart_item['product_id'];
			if ( ! empty( $woo_cart_item['variation_id'] ) || 0 !== $woo_cart_item['variation_id'] ) {
				$product_id_lan = $woo_cart_item['variation_id'];
			} else {
				$product_id_lan = $woo_cart_item['product_id'];
			}
			$_product = wc_get_product( $product_id_lan );
			if ( ! empty( $sitepress ) ) {
				$product_id_lan = intval( apply_filters( 'wpml_object_id', $product_id_lan, 'product', true, $default_lang ) );
			} else {
				$product_id_lan = intval( $product_id_lan );
			}
			if ( 'product' === $type ) {
				if ( in_array( $product_id_lan, $ap_selected_id, true ) ) {
					if ( 'qty' === $qws ) {
						$total_qws += intval( $woo_cart_item['quantity'] );
					}
					if ( 'weight' === $qws ) {
						$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
					}
					if ( 'subtotal' === $qws ) {
						if ( ! empty( $woo_cart_item['line_tax'] ) ) {
							$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
						}
						$total_qws += $this->wcpfc_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
					}
				}
			}
			if ( 'category' === $type ) {
				$cat_id_list        = wp_get_post_terms( $main_product_id_lan, 'product_cat', array( 'fields' => 'ids' ) );
				$cat_id_list_origin = $this->wcpfc_get_prd_category_from_cart__premium_only( $cat_id_list, $sitepress, $default_lang );
				if ( ! empty( $cat_id_list_origin ) && is_array( $cat_id_list_origin ) ) {
					foreach ( $ap_selected_id as $ap_fees_categories_key_val ) {
						if ( in_array( $ap_fees_categories_key_val, $cat_id_list_origin, true ) ) {
							if ( 'qty' === $qws ) {
								$total_qws += intval( $woo_cart_item['quantity'] );
							}
							if ( 'weight' === $qws ) {
								$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
							}
							if ( 'subtotal' === $qws ) {
								if ( ! empty( $woo_cart_item['line_tax'] ) ) {
									$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
								}
								$total_qws += $this->wcpfc_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
							}
							break;
						}
					}
				}
			}
			if ( 'shipping_class' === $type ) {
				$prd_shipping_class = $_product->get_shipping_class();
				if ( in_array( $prd_shipping_class, $ap_selected_id, true ) ) {
					if ( 'qty' === $qws ) {
						$total_qws += intval( $woo_cart_item['quantity'] );
					}
					if ( 'weight' === $qws ) {
						$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
					}
					if ( 'subtotal' === $qws ) {
						if ( ! empty( $woo_cart_item['line_tax'] ) ) {
							$woo_cart_item['line_tax'] = $woo_cart_item['line_tax'];
						}
						$total_qws += $this->wcpfc_pro_get_specific_subtotal__premium_only( $woo_cart_item['line_subtotal'], $woo_cart_item['line_tax'] );
					}
				}
			}
		}

		return $total_qws;
	}

	/**
	 * Get specific subtotal for product and category
	 *
	 * @return float $subtotal
	 *
	 * @since    3.6
	 */
	public function wcpfc_pro_get_specific_subtotal__premium_only( $line_total, $line_tax ) {
		$get_customer            = WC()->cart->get_customer();
		$get_customer_vat_exempt = WC()->customer->get_is_vat_exempt();
		$tax_display_cart        = WC()->cart->get_tax_price_display_mode();
		$wc_prices_include_tax   = wc_prices_include_tax();
		$tax_enable              = wc_tax_enabled();
		$cart_subtotal           = 0;
		if ( true === $tax_enable ) {
			if ( true === $wc_prices_include_tax ) {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += $line_total + $line_tax;
				} else {
					$cart_subtotal += $line_total;
				}
			} else {
				if ( 'incl' === $tax_display_cart && ! ( $get_customer && $get_customer_vat_exempt ) ) {
					$cart_subtotal += $line_total + $line_tax;
				} else {
					$cart_subtotal += $line_total;
				}
			}
		} else {
			$cart_subtotal += $line_total;
		}

		return $cart_subtotal;
	}

	/**
	 * Get Product category from cart
	 *
	 * @param array  $cat_id_list
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $cat_id_list_origin
	 *
	 * @since 3.6
	 *
	 */
	public function wcpfc_get_prd_category_from_cart__premium_only( $cat_id_list, $sitepress, $default_lang ) {
		$cat_id_list_origin = array();
		if ( isset( $cat_id_list ) && ! empty( $cat_id_list ) ) {
			foreach ( $cat_id_list as $cat_id ) {
				if ( ! empty( $sitepress ) ) {
					$cat_id_list_origin[] = (int) apply_filters( 'wpml_object_id', $cat_id, 'product_cat', true, $default_lang );
				} else {
					$cat_id_list_origin[] = (int) $cat_id;
				}
			}
		}

		return $cat_id_list_origin;
	}

	/**
	 * Check Min and max qty, weight and subtotal
	 *
	 * @param int|float $min
	 * @param int|float $max
	 * @param float     $price
	 * @param string    $qws
	 *
	 * @return array
	 *
	 * @since 3.4
	 *
	 */
	public function wcpfc_check_min_max_qws__premium_only( $min, $max, $price, $qws ) {
		$min_val = $min;
		if ( '' === $max || '0' === $max ) {
			$max_val = 2000000000;
		} else {
			$max_val = $max;
		}
		
		if ( 'qty' === $qws ) {
			settype( $min_val, 'integer' );
			settype( $max_val, 'integer' );
		} else {
			settype( $min_val, 'float' );
			settype( $max_val, 'float' );
		}

        // Currency conversion by CURCY plugin
        $price = $this->wcpfc_pro_convert_currency($price);
        if( 'subtotal' === $qws ) {
            $min_val = $this->wcpfc_pro_convert_currency($min_val);
            $max_val = $this->wcpfc_pro_convert_currency($max_val);
        }

		return array(
			'min'   => $min_val,
			'max'   => $max_val,
			'price' => $price,
		);
	}

	/**
	 * Check rule passed or not
	 *
	 * @param string    $key
	 * @param string    $min
	 * @param string    $max
	 * @param string    $hbc
	 * @param string    $hbp
	 * @param float     $price
	 * @param int|float $total_qws
	 * @param string    $qws
	 *
	 * @return array
	 * @since    3.6
	 *
	 */
	public function wcpfc_check_passed_rule__premium_only( $key, $min, $max, $hbc, $hbp, $price, $total_qws, $qws ) { // phpcs:ignore
		$is_passed_from_here_prd = array();
		if ( ( $min <= $total_qws ) && ( $total_qws <= $max ) ) {
			$is_passed_from_here_prd[ $hbc ][ $key ] = 'yes';
			$is_passed_from_here_prd[ $hbp ][ $key ] = $price;
		} else {
			$is_passed_from_here_prd[ $hbc ][ $key ] = 'no';
			$is_passed_from_here_prd[ $hbp ][ $key ] = $price;
		}

		return $is_passed_from_here_prd;
	}

	/**
	 * Find unique id based on given array
	 *
	 * @param array  $is_passed
	 * @param string $has_fee_checked
	 * @param string $has_fee_based
	 * @param string $advance_inside_rule_match
	 *
	 * @return array
	 * @since    3.6
	 *
	 */
	public function wcpfc_pro_check_all_passed_advance_rule__premium_only( $is_passed, $has_fee_checked, $has_fee_based, $advance_inside_rule_match ) {
		$get_cart_total = WC()->cart->get_cart_contents_total();
		$main_is_passed = 'no';
		$flag           = array();
		$sum_ammount    = 0;
		$cart_qty_n_combination_passed = array();
		if ( ! empty( $is_passed ) ) {
			foreach ( $is_passed as $main_is_passed ) {
				foreach ( $main_is_passed[ $has_fee_checked ] as $key => $is_passed_value ) {
					if ( 'yes' === $is_passed_value ) {

						if( isset($main_is_passed['skip_as_apply_on_product']) && !empty($main_is_passed['skip_as_apply_on_product']) && 'yes' === $main_is_passed['skip_as_apply_on_product'][$key] ){
							$flag[ $key ] = true;
							continue;
						}
                        
                        if( isset($main_is_passed['skip_as_apply_on_category']) && !empty($main_is_passed['skip_as_apply_on_category']) && 'yes' === $main_is_passed['skip_as_apply_on_category'][$key] ){
							$flag[ $key ] = true;
							continue;
						}

						foreach ( $main_is_passed[ $has_fee_based ] as $hfb_key => $hfb_is_passed_value ) {
							if ( $hfb_key === $key ) {
								$final_price = $this->wcpfc_check_percantage_price__premium_only( $hfb_is_passed_value, $get_cart_total );
								$sum_ammount += $final_price;
							}
						}
						$flag[ $key ] = true;
					} else {
						$flag[ $key ] = false;
					}
				}

				// Calculate N combination fees on Total Cart Quantity
				$cart_qty_n_combination_passed[] = $main_is_passed;
			}
			if ( 'any' === $advance_inside_rule_match ) {
				if ( in_array( true, $flag, true ) ) {
					$main_is_passed = 'yes';
				} else {
					$main_is_passed = 'no';
				}
			} else {
				if ( in_array( false, $flag, true ) ) {
					$main_is_passed = 'no';
				} else {
					$main_is_passed = 'yes';
				}
			}
		}

		// Calculate N combination fees on Total Cart Quantity
		if ( 'has_fee_based_on_tcq_price' === $has_fee_based ) {
			return array(
				'flag'         => $main_is_passed,
				'total_amount' => $sum_ammount,
				'n_combination' => $cart_qty_n_combination_passed,
			);
		} else {
			return array(
				'flag'         => $main_is_passed,
				'total_amount' => $sum_ammount,
			);
		}
	}

	/**
	 * Add shipping rate
	 *
	 * @param int|float $min
	 * @param int|float $max
	 * @param float     $price
	 * @param int|float $count_total
	 * @param float     $get_cart_total
	 * @param float     $shipping_rate_cost
	 *
	 * @return float $shipping_rate_cost
	 *
	 * @since 3.4
	 *
	 */
	public function wcpfc_check_percantage_price__premium_only( $price, $get_cart_total ) {
		if ( ! empty( $price ) ) {
			$is_percent = substr( $price, - 1 );
			if ( '%' === $is_percent ) {
				$percent = substr( $price, 0, - 1 );
				$percent = number_format( $percent, 2, '.', '' );
				if ( ! empty( $percent ) ) {
					$percent_total = ( $percent / 100 ) * $get_cart_total;
					$price         = $percent_total;
				}
			} else {
				$price = $this->wcpfc_pro_price_format( $price );
			}
		}

		return $price;
	}

	/**
	 * Price format
	 *
	 * @param string $price
	 *
	 * @return string $price
	 * @since  1.3.3
	 *
	 */
	public function wcpfc_pro_price_format( $price ) {
        $price = floatval( $price );
        
        // We must to round off the price to selected decimal places to avoid floating point issues
        $price = round( $price, wc_get_price_decimals() );

		return $price;
	}

	/**
	 * Cost for Product subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_product_subtotal
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_product_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 */
	public function wcpfc_pro_match_product_subtotal__premium_only( $get_condition_array_ap_product_subtotal, $woo_cart_array, $cost_on_product_subtotal_rule_match, $sitepress, $default_lang ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_ps = array();
			if ( ! empty( $get_condition_array_ap_product_subtotal ) || '' !== $get_condition_array_ap_product_subtotal ) {
				foreach ( $get_condition_array_ap_product_subtotal as $key => $get_condition ) {
					$total_qws                = $this->wcpfc_get_count_qty__premium_only(
						$get_condition['ap_fees_product_subtotal'], $woo_cart_array, $sitepress, $default_lang, 'product', 'subtotal'
					);
					$get_min_max              = $this->wcpfc_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_product_subtotal_min_subtotal'], $get_condition['ap_fees_ap_product_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_product_subtotal'], 'subtotal'
					);
					$is_passed_from_here_ps[] = $this->wcpfc_check_passed_rule__premium_only(
						$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_ps', 'has_fee_based_on_ps_price', $get_condition['ap_fees_ap_price_product_subtotal'], $total_qws, 'subtotal'
					);
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_ps, 'has_fee_based_on_ps', 'has_fee_based_on_ps_price', $cost_on_product_subtotal_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Match product per weight rules
	 *
	 * @param array  $get_condition_array_ap_product_weight
	 * @param array  $cart_products_array
	 * @param string $default_lang
	 *
	 * @return array $is_passed_advance_rule
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_product_per_weight__premium_only( $get_condition_array_ap_product_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_product_weight_rule_match ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_prd = array();
			if ( ! empty( $get_condition_array_ap_product_weight ) || '' !== $get_condition_array_ap_product_weight ) {
				foreach ( $get_condition_array_ap_product_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_product_weight'] ) || '' !== $get_condition['ap_fees_product_weight'] ) {
						$total_qws                 = $this->wcpfc_get_count_qty__premium_only(
							$get_condition['ap_fees_product_weight'], $woo_cart_array, $sitepress, $default_lang, 'product', 'weight'
						);
						$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_product_weight_min_qty'], $get_condition['ap_fees_ap_product_weight_max_qty'], $get_condition['ap_fees_ap_price_product_weight'], 'weight'
						);
						$is_passed_from_here_prd[] = $this->wcpfc_check_passed_rule__premium_only(
							$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_cost_ppw', 'has_fee_based_on_cost_ppw_price', $get_condition['ap_fees_ap_price_product_weight'], $total_qws, 'weight'
						);
					}
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_prd, 'has_fee_based_on_cost_ppw', 'has_fee_based_on_cost_ppw_price', $cost_on_product_weight_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Match category per qty rules
	 *
	 * @param array  $get_condition_array_ap_category
	 * @param array  $cart_products_array
	 * @param string $default_lang
	 *
	 * @since    1.3.3
	 * 
	 */
	public function wcpfc_pro_match_category_per_qty__premium_only( $get_condition_array_ap_category, $woo_cart_array, $sitepress, $default_lang, $cost_on_category_rule_match ) {
        $per_category_cost = 0;
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_category ) || '' !== $get_condition_array_ap_category ) {
				foreach ( $get_condition_array_ap_category as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_categories'] ) || '' !== $get_condition['ap_fees_categories'] ) {
						$total_qws                 = $this->wcpfc_get_count_qty__premium_only(
							$get_condition['ap_fees_categories'], $woo_cart_array, $sitepress, $default_lang, 'category', 'qty'
						);
                        $total_sub                 = $this->wcpfc_get_count_qty__premium_only(
							$get_condition['ap_fees_categories'], $woo_cart_array, $sitepress, $default_lang, 'category', 'subtotal'
						);
						$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_cat_min_qty'], $get_condition['ap_fees_ap_cat_max_qty'], $get_condition['ap_fees_ap_price_category'], 'qty'
						);
						$is_passed_from_here_cat[] = $this->wcpfc_check_passed_rule__premium_only(
							$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_cost_per_cat_qty', 'has_fee_based_on_cost_per_cat_price', $get_condition['ap_fees_ap_price_category'], $total_qws, 'qty'
						);
                        if( isset($get_condition['ap_fees_ap_per_category']) && !empty($get_condition['ap_fees_ap_per_category']) && "yes" === $get_condition['ap_fees_ap_per_category'] && "yes" === $is_passed_from_here_cat[$key]['has_fee_based_on_cost_per_cat_qty'][$key] ){
							$per_category_cost += $this->wcpfc_check_percantage_price__premium_only( $get_condition['ap_fees_ap_price_category'], $total_sub );
							$is_passed_from_here_cat[$key]['skip_as_apply_on_category'][$key] = 'yes';
						}
					}
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_cat, 'has_fee_based_on_cost_per_cat_qty', 'has_fee_based_on_cost_per_cat_price', $cost_on_category_rule_match
			);

            if( $per_category_cost > 0 && "yes" === $main_is_passed['flag'] ){
				$main_is_passed['total_amount'] += $per_category_cost;
			}
            
			return $main_is_passed;
		}
	}

	/**
	 * Cost for Category subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_category_subtotal
	 * @param array  $woo_cart_array
	 * @param string $sitepress
	 * @param string $default_lang
	 * @param string $cost_on_category_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 */
	public function wcpfc_pro_match_category_subtotal__premium_only( $get_condition_array_ap_category_subtotal, $woo_cart_array, $cost_on_category_subtotal_rule_match, $sitepress, $default_lang ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cs = array();
			if ( ! empty( $get_condition_array_ap_category_subtotal ) || '' !== $get_condition_array_ap_category_subtotal ) {
				foreach ( $get_condition_array_ap_category_subtotal as $key => $get_condition ) {
					$total_qws                = $this->wcpfc_get_count_qty__premium_only(
						$get_condition['ap_fees_category_subtotal'], $woo_cart_array, $sitepress, $default_lang, 'category', 'subtotal'
					);
					$get_min_max              = $this->wcpfc_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_category_subtotal_min_subtotal'], $get_condition['ap_fees_ap_category_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_category_subtotal'], 'subtotal'
					);
					$is_passed_from_here_cs[] = $this->wcpfc_check_passed_rule__premium_only(
						$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_cs', 'has_fee_based_on_cs_price', $get_condition['ap_fees_ap_price_category_subtotal'], $total_qws, 'subtotal'
					);
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_cs, 'has_fee_based_on_cs', 'has_fee_based_on_cs_price', $cost_on_category_subtotal_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Match category per weight rules
	 *
	 * @param array  $get_condition_array_ap_category_weight
	 * @param array  $cart_products_array
	 * @param string $default_lang
	 *
	 * @return array $is_passed_advance_rule
	 *
	 * @since    1.3.3
	 * 
	 */
	public function wcpfc_pro_match_category_per_weight__premium_only( $get_condition_array_ap_category_weight, $woo_cart_array, $sitepress, $default_lang, $cost_on_category_weight_rule_match ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_cat = array();
			if ( ! empty( $get_condition_array_ap_category_weight ) || '' !== $get_condition_array_ap_category_weight ) {
				foreach ( $get_condition_array_ap_category_weight as $key => $get_condition ) {
					if ( ! empty( $get_condition['ap_fees_categories_weight'] ) || '' !== $get_condition['ap_fees_categories_weight'] ) {
						$total_qws                 = $this->wcpfc_get_count_qty__premium_only(
							$get_condition['ap_fees_categories_weight'], $woo_cart_array, $sitepress, $default_lang, 'category', 'weight'
						);
						$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
							$get_condition['ap_fees_ap_category_weight_min_qty'], $get_condition['ap_fees_ap_category_weight_max_qty'], $get_condition['ap_fees_ap_price_category_weight'], 'weight'
						);
						$is_passed_from_here_cat[] = $this->wcpfc_check_passed_rule__premium_only(
							$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_per_cw', 'has_fee_based_on_cost_per_cw', $get_condition['ap_fees_ap_price_category_weight'], $total_qws, 'weight'
						);
					}
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_cat, 'has_fee_based_on_per_cw', 'has_fee_based_on_cost_per_cw', $cost_on_category_weight_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Match total cart per qty rules
	 *
	 * @param array $get_condition_array_ap_total_cart_qty
	 * @param array $cart_products_array
	 *
	 * @return array $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_total_cart_qty__premium_only( $get_condition_array_ap_total_cart_qty, $woo_cart_array, $cost_on_total_cart_qty_rule_match ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcq = array();
			if ( ! empty( $get_condition_array_ap_total_cart_qty ) || '' !== $get_condition_array_ap_total_cart_qty ) {
				foreach ( $get_condition_array_ap_total_cart_qty as $key => $get_condition ) {
					$total_qws = 0;
					foreach ( $woo_cart_array as $woo_cart_item ) {
						$total_qws += $woo_cart_item['quantity'];
					}
					$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_total_cart_qty_min_qty'], $get_condition['ap_fees_ap_total_cart_qty_max_qty'], $get_condition['ap_fees_ap_price_total_cart_qty'], 'qty'
					);
					$is_passed_from_here_tcq[] = $this->wcpfc_check_passed_rule__premium_only(
						$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_tcq', 'has_fee_based_on_tcq_price', $get_condition['ap_fees_ap_price_total_cart_qty'], $total_qws, 'qty'
					);
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only( $is_passed_from_here_tcq, 'has_fee_based_on_tcq', 'has_fee_based_on_tcq_price', $cost_on_total_cart_qty_rule_match );
		}
        return $main_is_passed;
	}

	/**
	 * Match total cart weight rules
	 *
	 * @param array $get_condition_array_ap_total_cart_weight
	 * @param array $cart_products_array
	 *
	 * @return array $main_is_passed
	 *
	 * @since    1.3.3
	 *
	 */
	public function wcpfc_pro_match_total_cart_weight__premium_only( $get_condition_array_ap_total_cart_weight, $woo_cart_array, $cost_on_total_cart_weight_rule_match ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcw = array();
			if ( ! empty( $get_condition_array_ap_total_cart_weight ) || '' !== $get_condition_array_ap_total_cart_weight ) {
				foreach ( $get_condition_array_ap_total_cart_weight as $key => $get_condition ) {
					$total_qws = 0;
					foreach ( $woo_cart_array as $woo_cart_item ) {
						if ( ! empty( $woo_cart_item['variation_id'] ) || 0 !== $woo_cart_item['variation_id'] ) {
							$product_id_lan = $woo_cart_item['variation_id'];
						} else {
							$product_id_lan = $woo_cart_item['product_id'];
						}
						$_product = wc_get_product( $product_id_lan );
						if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
							$total_qws += intval( $woo_cart_item['quantity'] ) * floatval( $_product->get_weight() );
						}
					}
					$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_total_cart_weight_min_weight'], $get_condition['ap_fees_ap_total_cart_weight_max_weight'], $get_condition['ap_fees_ap_price_total_cart_weight'], 'weight'
					);
					$is_passed_from_here_tcw[] = $this->wcpfc_check_passed_rule__premium_only(
						$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_tcw', 'has_fee_based_on_tcw_price', $get_condition['ap_fees_ap_price_total_cart_weight'], $total_qws, 'weight'
					);
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_tcw, 'has_fee_based_on_tcw', 'has_fee_based_on_tcw_price', $cost_on_total_cart_weight_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Cost for total cart subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_total_cart_subtotal
	 * @param array  $woo_cart_array
	 * @param string $cost_on_total_cart_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.4
	 *
	 */
	public function wcpfc_pro_match_total_cart_subtotal__premium_only( $get_condition_array_ap_total_cart_subtotal, $woo_cart_array, $cost_on_total_cart_subtotal_rule_match ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_tcw = array();
			if ( ! empty( $get_condition_array_ap_total_cart_subtotal ) || '' !== $get_condition_array_ap_total_cart_subtotal ) {
				foreach ( $get_condition_array_ap_total_cart_subtotal as $key => $get_condition ) {
					$total_qws                 = $this->wcpfc_pro_get_cart_subtotal();
					$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_total_cart_subtotal_min_subtotal'], $get_condition['ap_fees_ap_total_cart_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_total_cart_subtotal'], 'weight'
					);
					$is_passed_from_here_tcw[] = $this->wcpfc_check_passed_rule__premium_only(
						$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_tcs', 'has_fee_based_on_tcs_price', $get_condition['ap_fees_ap_price_total_cart_subtotal'], $total_qws, 'weight'
					);
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_tcw, 'has_fee_based_on_tcs', 'has_fee_based_on_tcs_price', $cost_on_total_cart_subtotal_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Cost for Category subtotal in advance pricing rules
	 *
	 * @param array  $get_condition_array_ap_shipping_class_subtotal
	 * @param array  $woo_cart_array
	 * @param string $cost_on_shipping_class_subtotal_rule_match
	 *
	 * @return array $main_is_passed
	 * @since 3.6
	 *
	 */
	public function wcpfc_pro_match_shipping_class_subtotal__premium_only( $get_condition_array_ap_shipping_class_subtotal, $woo_cart_array, $cost_on_shipping_class_subtotal_rule_match, $sitepress, $default_lang ) {
        $main_is_passed = array();
		if ( ! empty( $woo_cart_array ) ) {
			$is_passed_from_here_scs = array();
			if ( ! empty( $get_condition_array_ap_shipping_class_subtotal ) || '' !== $get_condition_array_ap_shipping_class_subtotal ) {
				foreach ( $get_condition_array_ap_shipping_class_subtotal as $key => $get_condition ) {
					$total_qws                 = $this->wcpfc_get_count_qty__premium_only(
						$get_condition['ap_fees_shipping_class_subtotals'], $woo_cart_array, $sitepress, $default_lang, 'shipping_class', apply_filters('ad_fee_shipping_class_default_behave', 'subtotal')
					);
					$get_min_max               = $this->wcpfc_check_min_max_qws__premium_only(
						$get_condition['ap_fees_ap_shipping_class_subtotal_min_subtotal'], $get_condition['ap_fees_ap_shipping_class_subtotal_max_subtotal'], $get_condition['ap_fees_ap_price_shipping_class_subtotal'], 'subtotal'
					);
					$is_passed_from_here_scs[] = $this->wcpfc_check_passed_rule__premium_only(
						$key, $get_min_max['min'], $get_min_max['max'], 'has_fee_based_on_scs', 'has_fee_based_on_scs_price', $get_condition['ap_fees_ap_price_shipping_class_subtotal'], $total_qws, 'subtotal'
					);
				}
			}
			$main_is_passed = $this->wcpfc_pro_check_all_passed_advance_rule__premium_only(
				$is_passed_from_here_scs, 'has_fee_based_on_scs', 'has_fee_based_on_scs_price', $cost_on_shipping_class_subtotal_rule_match
			);
		}
        return $main_is_passed;
	}

	/**
	 * Get applied fees on frontside
	 *
	 * @return array|object $fees
	 * @since  1.3.3
	 *
	 */
	public function wcpfc_pro_get_applied_fees() {

		// Check if WooCommerce cart is initialized
		if ( ! WC()->cart ) {
			return array(); // Return an empty array if the cart is not initialized
		}
		
		$fees = WC()->cart->get_fees();
		
		$fee_names = wp_list_pluck($fees, 'name'); // Use wp_list_pluck to extract names from fees.
	
		$args = array(
			'post_type'      => 'wc_conditional_fee',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post_title__in' => $fee_names,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);
	
		$query = new WP_Query($args);
	
		$fee_posts = array();
		if ($query->have_posts()) {
			$fee_posts = $query->posts;
		}

		foreach ($fees as &$fee) {
			foreach ($fee_posts as $fee_post) {
				if ($fee->name === $fee_post->post_title) {
					$fee->menu_order = $fee_post->menu_order;
					break; // Break the inner loop when a match is found
				}
			}
		}
	
		uasort($fees, array($this, 'wcpfc_pro_sorting_fees'));
	
		return $fees;
	}

	/**
	 * Sorting fees on front side
	 *
	 * @param object $a
	 * @param object $b
	 *
	 * @return int
	 * @since  1.3.3
	 *
	 */
	public function wcpfc_pro_sorting_fees( $a, $b ) {
		if(isset($a->menu_order) && isset($b->menu_order)){
			return ( $a->menu_order < $b->menu_order ) ? - 1 : 1;
		}
        return 0;
	}

	/**
	 * Get variation name from cart
	 *
	 * @param string $sitepress
	 * @param string $default_lang
	 *
	 * @return array $variation_cart_products_array
	 * @uses  wcpfc_pro_get_cart();
	 *
	 * @since 1.0.0
	 *
	 */
	public function wcpfc_pro_get_var_name__premium_only( $sitepress, $default_lang ) {

		$cart_array             = $this->wcpfc_pro_get_cart();

        $product_attributes = array();

		foreach ( $cart_array as $woo_cart_item ) {

            $product_id = !empty( $woo_cart_item['variation_id'] ) && $woo_cart_item['variation_id'] > 0 ? $woo_cart_item['variation_id'] : $woo_cart_item['product_id'];
            $product_id = !empty( $sitepress ) ? apply_filters( 'wpml_object_id', $product_id, 'product', true, $default_lang ) : $product_id;
            $product_obj = wc_get_product( $product_id );

            if ( ! ( $product_obj->is_virtual( 'yes' ) ) ) {
                foreach( $product_obj->get_attributes() as $pa_key => $pa_value ) {

                    if( $product_obj->is_type( 'variation' ) ) {

                        // For Variation product
                        if( empty( $pa_value ) ) {

                            // For 'Any' attribute value
                            $variation_data = $woo_cart_item['variation'];
                            $selected_value = $variation_data["attribute_$pa_key"] ?? '';

                            $terms = wc_get_product_terms($product_obj->get_parent_id(), $pa_key, ['fields' => 'slugs']);
                            if ( !empty($terms) ) {
                                // If the selected value is one of the valid terms, it's from "Any" options
                                if ( in_array( $selected_value, $terms, true ) ) {
                                    $product_attributes[$pa_key][] = $selected_value;
                                }
                            }
                        } else {

                            // For 'Specific' attribute value
                            $product_attributes[$pa_key][] = $pa_value;
                        }

                        // We need to remove repeated attributes
                        $product_attributes[$pa_key] = array_unique($product_attributes[$pa_key]);
                    } else {

                        // For Simple product
                        foreach( $pa_value->get_slugs() as $sj_slugs ){
                            $product_attributes[$pa_value->get_name()][] = $sj_slugs;
                        }

                        // We need to remove repeated attributes
                        $product_attributes[$pa_value->get_name()] = array_unique($product_attributes[$pa_value->get_name()]);
                    }
                }
            }
		}
       
		return $product_attributes;
	}

	/**
	 * Cart total with tax and shipping cost
	 *
	 * @return number $cart_final_total.
	 *
	 * @since  3.8
	 *
	 * @author sj
	 */
	public function wcpfc_cart_total(){

		if ( ! WC()->cart ) {
			return 0;
		}

		$cart_final_total = 0;
		$total_tax = 0;
		$total_shipping = 0;

		$cart_subtotal = WC()->cart->get_cart_contents_total();

		foreach(WC()->cart->get_tax_totals() as $taxy){
			$total_tax += $taxy->amount;
		}
		
		// Loop through shipping packages from WC_Session (They can be multiple in some cases)
		foreach ( WC()->cart->get_shipping_packages() as $package_id => $package ) {

			// Check if a shipping for the current package exist
			if ( WC()->session->__isset( 'shipping_for_package_'.$package_id ) ) {
				// Loop through shipping rates for the current package
				foreach ( WC()->session->get( 'shipping_for_package_'.$package_id )['rates'] as $shipping_rate_id => $shipping_rate ) {
					if( in_array( $shipping_rate_id, WC()->session->get( 'chosen_shipping_methods' ), true ) ){
						$shipping_rate = WC()->session->get( 'shipping_for_package_'.$package_id )['rates'][$shipping_rate_id];
						$total_shipping += $shipping_rate->get_cost(); // The cost without tax
					}
				}
			}
		}
		$cart_final_total = $cart_subtotal + $total_tax + $total_shipping;
		return $cart_final_total;
	}

	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param string $fee_cost_sum
	 * @param array  $args
	 *
	 * @return string $fee_cost_sum if fee cost is empty then it will return 0
	 * @since 1.0.0
	 *
	 * @uses  wc_get_price_decimal_separator()
	 * @uses  WC_Eval_Math::evaluate()
	 * 
	 * @author sj
	 *
	 */
	public function wcpfc_evaluate_cost__premium_only( $fee_cost_sum, $args = array() ){
        
        include_once WP_PLUGIN_DIR . '/woocommerce/includes/libraries/class-wc-eval-math.php';
		$locale         = localeconv();
		$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
		
		if ( isset( $args) && ! empty( $args ) ) {
			$this->fee_cost = $args[1];
			// Expand shortcodes.
			add_shortcode( 'fee', array( $this, 'wcpfc_fee__premium_only' ) );
			
			$fee_cost_sum = do_shortcode( str_replace( array( '[qty]', '[cost]', '[weight]' ), array(
				$args[0],
				$args[1],
				$args[2] 
			), $fee_cost_sum ) );
			
			remove_shortcode( 'fee', array( $this, 'wcpfc_fee__premium_only' ) );	
		}
		
		// Remove whitespace from string
		$fee_cost_sum = preg_replace( '/\s+/', '', $fee_cost_sum );
		
		// Remove locale from string
		$fee_cost_sum = str_replace( $decimals, '.', $fee_cost_sum );
		
		// Trim invalid start/end characters
		$fee_cost_sum = rtrim( ltrim( $fee_cost_sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
		
		// Do the math
		return $fee_cost_sum ? WC_Eval_Math::evaluate( $fee_cost_sum ) : 0;
	}

	/**
	 * Work out fee ( shortcode ).
	 *
	 * @param array $atts
	 *
	 * @return string $calculated_fee
	 * @since 1.0.0
	 *
	 * @uses  wcpfc_fee_string_sanitize
	 *
	 * @author sj
	 * 
	 */
	public function wcpfc_fee__premium_only( $atts ) {
		$atts            = shortcode_atts( array( 'min_fee' => '', 'max_fee' => '' ), $atts );
		$atts['min_fee'] = $this->wcpfc_fee_string_sanitize( $atts['min_fee'] );
		$atts['max_fee'] = $this->wcpfc_fee_string_sanitize( $atts['max_fee'] );
		$calculated_fee  = $this->fee_cost ? $this->fee_cost : 0;
		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}
		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}
		
		return $calculated_fee;
	}
	/**
	 * Sanitize string
	 *
	 * @param mixed $string
	 *
	 * @return string $result
	 * @since 1.0.0
	 *
	 */
	public function wcpfc_fee_string_sanitize( $string ) {
		$result = preg_replace( "/[^ A-Za-z0-9_=.*()+\-\[\]\/]+/", '', html_entity_decode( $string, ENT_QUOTES ) );
		return $result;
	}

    /**
	 * Check fee has recursive apply on subscription product
	 *
	 * @param mixed $return
     * @param object $fee
	 *
	 * @return string $result
	 * @since 3.9.0
	 *
	 */
    public function wcpfc_pro_recurring_fees__premium_only( $return, $fee ){
        
        // Query to fetch fees ids by name
        $args = array(
		    'post_type' => 'wc_conditional_fee',
		    'post_status' => 'publish',
		    'posts_per_page' => 1,
		    'fields' => 'ids',
		    'title' => $fee->name
		);

		$query = new WP_Query( $args );

		$fee_post = '';
		if ( $query->have_posts() ) {
		    $fee_post = $query->posts[0];
		}
		wp_reset_postdata();

        $fees_id = !empty($fee_post) ? $fee_post : 0;
        if( $fees_id > 0 ) {
            $fee_is_recurring = get_post_meta( $fees_id, 'fee_settings_recurring', true ) ? get_post_meta( $fees_id, 'fee_settings_recurring', true ) : 'off';
            if( 'on' === $fee_is_recurring ) {
                $return = true;
            } else {
                $return = false;
            }
        }
        return $return;
    }

    /**
     * Convert currency based on multi currency - CURCY Plugin
     * 
     * @param float $amount
     * 
     * @return float $amount
     * 
     * @since 4.2.0
     */
	public function wcpfc_pro_convert_currency( $amount ) {

		$multiCurrencySettings = null;
		if( class_exists('WOOMULTI_CURRENCY_Data') ) {
            $multiCurrencySettings = WOOMULTI_CURRENCY_Data::get_ins();
        } elseif( class_exists('WOOMULTI_CURRENCY_F_Data') ) {
            $multiCurrencySettings = WOOMULTI_CURRENCY_F_Data::get_ins();
        }
		
		if ($multiCurrencySettings) {
			$currentCurrency = $multiCurrencySettings->get_current_currency() ? $multiCurrencySettings->get_current_currency() : $multiCurrencySettings->get_default_currency();
	
			if ( $currentCurrency ) {
				$all_currencies = $multiCurrencySettings->get_list_currencies();
				$currentCurrencyRate = floatval($all_currencies[$currentCurrency]['rate']);
                $currentCurrencyRate = !empty($all_currencies) && is_array($all_currencies) && isset($all_currencies[$currentCurrency]) && isset($all_currencies[$currentCurrency]['rate']) ? floatval( $all_currencies[$currentCurrency]['rate'] ) : 1;
				$amount *= $currentCurrencyRate;
			}
		}
        
        // Convert any type to float
        $amount = floatval( $amount );

        // Put 3 as round off to get more accurate value
        $amount = round( $amount, 3 );

		return $amount;
	}

	public function wcpfc_pro_get_all_fees( $args = array() ) {

        // Get all fees
        $wcpfc_get_all_fees = get_transient( 'get_all_fees' );

        if( false === $wcpfc_get_all_fees ) {
            
            $fees_args    = wp_parse_args( $args, array(
                'post_type'        	=> 'wc_conditional_fee',
                'post_status'      	=> 'publish',
                'posts_per_page'   	=> -1,
                'suppress_filters' 	=> false,
                'fields'        	=> 'ids',
                'order'          	=> 'DESC',
                'orderby'        	=> 'ID',
            ) );

            $wcpfc_get_all_fees_query = new WP_Query( $fees_args );
            $wcpfc_get_all_fees       = $wcpfc_get_all_fees_query->get_posts();

            // Set transient for fees
			set_transient( 'get_all_fees', $wcpfc_get_all_fees );
        }

        return $wcpfc_get_all_fees;
    }

	/**
     * Apply fee for first order
     * 
     * @param int $fees_id
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_apply_fee_for_first_order( $fees_id ) {
        
		if ( empty( $fees_id ) ) {
			return false;
		}

		$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

        if( empty( $conditional_fee ) ){
            return false;
        }
	
		$getFirstOrderForUser   	= $conditional_fee->get_first_order_for_user();
        $firstOrderForUser   		= ( ! empty( $getFirstOrderForUser ) && 'on' === $getFirstOrderForUser ) ? true : false;
		
		if( $firstOrderForUser && is_user_logged_in() ) {

            $current_user_id = get_current_user_id();
            $check_for_user = $this->wcpfc_check_first_order_for_user__premium_only( $current_user_id );

            if( !$check_for_user ){
                return false;
            } else {
                return true;
            }
        }

        return true;
    }

	/**
     * Whether fee apply based on date, time and days or not
     * 
     * @param int $fees_id
     * 
     * @return boolean
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_apply_fee_based_on_date_and_time__premium_only( $fees_id ) {
        
        if( empty( $fees_id ) ){
            return false;
        }

		$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

        if( empty( $conditional_fee ) ){
            return false;
        }

        $date_check = $time_check = $day_check = false;

        // Date validation check
        $currentDate    = strtotime( gmdate( 'd-m-Y' ) );
		$feeStartDate = $conditional_fee->get_fee_settings_start_date() ? strtotime($conditional_fee->get_fee_settings_start_date()) : null;
		$feeEndDate = $conditional_fee->get_fee_settings_end_date() ? strtotime($conditional_fee->get_fee_settings_end_date()) : null;

        if( ( $currentDate >= $feeStartDate || null === $feeStartDate ) && ( $currentDate <= $feeEndDate || null === $feeEndDate ) ) {
            $date_check = true;
        }

        // Time validation check
		$currentTime    = current_time( 'timestamp' );
        $feeStartTime 	= $conditional_fee->get_ds_time_from() ? strtotime($conditional_fee->get_ds_time_from()) : null;
		$feeEndTime   	= $conditional_fee->get_ds_time_to() ? strtotime($conditional_fee->get_ds_time_to()) : null;


        if( ( $currentTime >= $feeStartTime || null === $feeStartTime ) && ( $currentTime <= $feeEndTime || null === $feeEndTime ) ) {
            $time_check = true;
        }

        // Days validation check
        $today =  strtolower( gmdate( "D" ) );
		$ds_select_day_of_week = $conditional_fee->get_ds_select_day_of_week();

		// Ensure $ds_select_day_of_week is an array
		if ( ! is_array( $ds_select_day_of_week ) ) {
			$ds_select_day_of_week = ! empty( $ds_select_day_of_week ) ? explode( ',', $ds_select_day_of_week ) : array();
		}

        if( in_array( $today, $ds_select_day_of_week, true ) || empty( $ds_select_day_of_week ) ) {
            $day_check = true;
        }

        if( $date_check && $time_check && $day_check ) {
            return true;
        }

        return false;
    }

	/**
     * Check provided fee is optional or not on checkout page
     * 
     * @param int $fees_id
     * @param array $optional_fee_array
     * 
     * @return boolean
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_is_fee_optional__premium_only( $fees_id, $optional_fee_array = array() ) {

        if( empty( $fees_id ) ){
            return false;
        }

		$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

        if( empty( $conditional_fee ) ){
            return false;
        }

        $getFeeOptional  = $conditional_fee->get_fee_settings_select_optional();
		$getFeesOptional = apply_filters('is_fee_optional_default', $getFeeOptional);

        $apply_rule_for_optional = false;
        if( in_array( $fees_id, $optional_fee_array, true ) ) {
            $apply_rule_for_optional = true;
        }

        if( !( 'yes' !== $getFeesOptional || $apply_rule_for_optional ) ) {
            return true;
        }
        return false;
    }

	/**
     * Get cart line specific data
     * 
     * @param string $element_count
     * 
     * @return float $return_count
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_cart_line_specific_data__premium_only( $element_count = 'quantity' ) {

        $return_count   = 0;

        if( empty( WC()->cart ) ){
            return $return_count;
        }

        $cart_array     = WC()->cart->get_cart();

        if( 'count' === $element_count ) {
            return count( $cart_array );
        }

        if( 'subtotal' === $element_count ) {
            return floatval( wc_prices_include_tax() ? WC()->cart->subtotal : WC()->cart->subtotal_ex_tax );
        }

        if( 'subtotal_with_discount' === $element_count ) {
            
            $cart_subtotal = floatval( wc_prices_include_tax() ? WC()->cart->subtotal : WC()->cart->subtotal_ex_tax );
            $discount_amount =  wc_prices_include_tax() ? round(WC()->cart->get_discount_total(), 2) + round(WC()->cart->get_discount_tax(), 2) : round(WC()->cart->get_discount_total(), 2);

            return floatval( $cart_subtotal - $discount_amount );
        }

        if( 'weight' === $element_count ){
            return WC()->cart->get_cart_contents_weight();
        }

        if ( !empty( $cart_array ) ) {
            foreach ( $cart_array as $cart_item ) {

                $product_obj = $cart_item['data'];
                $product_type = $product_obj->get_type();

                // If bundle product then skip from count
                if( "bundle" === $product_type ){
                    continue;
                }

                if( 'quantity' === $element_count ) {
                    $return_count += $cart_item['quantity'];
                }
            }	
        }

        return $return_count;
    }

	/**
	 * Calculate amount based on fee type
	 *
     * @param    string $fee_type.
     * @param    number $fee_cost.
     * @param    number $cart_amount.
     * 
     * @return   float $return_cost.
     * 
	 * @since    1.0.0
     */
    public function wcpfc_pro_calculate_amount__premium_only( $fee_type, $fee_cost, $cart_amount ){

        $return_cost = 0;

        switch ( $fee_type ) {

            case 'percentage':
                $return_cost = $cart_amount * ( $fee_cost / 100);
                break;

            case 'both':
                // Split the input into parts using '+'

				if(strpos($fee_cost, '+') !== false) {

					$newamount = explode('+', $fee_cost);
					if (is_numeric($newamount[0]) && is_numeric($newamount[1])) {
						$peramount = ( $cart_amount * $newamount[0] ) / 100;

						$getFeesCost = $peramount + $newamount[1];
					}
				} else { 
					$getFeesCost = $fee_cost;
				}

              
                $return_cost = $getFeesCost;
                break;
                
            default:
                // For fixed amount we are evaluate cost
                $return_cost = $this->wcpfc_evaluate_cost__premium_only( 
                    $fee_cost, 
                    array( 
                        $this->wcpfc_pro_cart_line_specific_data__premium_only(), 
                        $this->wcpfc_pro_cart_line_specific_data__premium_only('subtotal'), 
                        $this->wcpfc_pro_cart_line_specific_data__premium_only('weight') 
                    ) 
                );
                break;
        }

        return (float) $return_cost;
    }

	/**
     * Check for applying full discount on cart/checkout page
     * 
     * @return boolean
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_remove_fee_on_full_discount__premium_only() {

        if( empty( WC()->cart ) ){
            return false;
        }

        $cart_subtotal = floatval( wc_prices_include_tax() ? WC()->cart->subtotal : WC()->cart->subtotal_ex_tax );

        $remove_fee_on_full_discount = get_option( 'chk_enable_coupon_fee', 'no' );

        if( 'on' === $remove_fee_on_full_discount ) {

            $discount_excl_tax_total = WC()->cart->get_cart_discount_total();
            $discount_tax_total = WC()->cart->get_cart_discount_tax_total();

            $discount_total = round( $discount_excl_tax_total + $discount_tax_total, 2);

            if( $discount_total >= $cart_subtotal ) {
                return true;
            }
        }

        return false;
    }

	/**
     * Prepare data for product specific cart data
     * 
     * @param array $return_type
     * 
     * @return array
     * 
     * @since 1.0.0
     * 
     * @internal
     * 
     * @uses     Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public::wcpfc_pro_cart_line_specific_data__premium_only()
     * @uses     Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public::wcpfc_pro_remove_currency_symbol()
     * @uses     wc_prices_include_tax()
     */
    public function wcpfc_pro_get_product_specific_cart_data__premium_only( $fees_id, $return_type = '' ) {
        $return_data = array(
            'quantity' => wcpfc_pro_public()->wcpfc_pro_cart_line_specific_data__premium_only(),
            'subtotal' => floatval( wc_prices_include_tax() ? WC()->cart->subtotal : WC()->cart->subtotal_ex_tax )
        );

        if( empty( $fees_id ) ){
            if ( array_key_exists( $return_type, $return_data ) ) {
                return $return_data[$return_type];
            }
            return $return_data;
        }

        $conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

        $product_fees_metabox = $conditional_fee->get_product_fees_metabox();

        // Check if product fees metabox is empty then return total cart data
        if( empty( $product_fees_metabox ) ) {

            if ( array_key_exists( $return_type, $return_data ) ) {
                return $return_data[$return_type];
            }

            return $return_data;
        }

        $prepare_array = array();
        $cart_item_data = array();

        foreach ( $product_fees_metabox as $condition ) {
            
            $condition_value = !empty( $condition['product_fees_conditions_values'] ) && is_array( $condition['product_fees_conditions_values'] ) ? array_map( 'intval', $condition['product_fees_conditions_values'] ) : array();
            
            // For Product condition
            if( array_search( 'product', $condition, true ) ) {
                    
                foreach ( WC()->cart->get_cart() as $cart_item ) {

                    $product = $cart_item['data'];
                    
                    // Check if product is not a product object then skip the loop
                    if ( ! is_a( $product, 'WC_Product' ) ) {
                        continue;
                    }   
                    
                    $product_id = $product->get_id();
                    
                    $flag = false;

                    if ( 
                        ( 'is_equal_to' === $condition['product_fees_conditions_is'] && in_array( $product_id, $condition_value, true ) )
                        || ( 'not_in' === $condition['product_fees_conditions_is'] && ! in_array( $product_id, $condition_value, true ) ) 
                    ) {
                        $flag = true;
                    }

                    if( $flag ) {
                        $prepare_array[$product_id]['quantity'] = absint( $cart_item['quantity'] );
                        $prepare_array[$product_id]['subtotal'] = floatval( wcpfc_pro_public()->wcpfc_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ) ) );
                    }
                }
            }
            // For Brand, Category and Tag condition
            if( array_search( 'brand', $condition, true ) 
            || array_search( 'wlf_location', $condition, true )
            || array_search( 'category', $condition, true )
            || array_search( 'tag', $condition, true ) ) {
        
                foreach ( WC()->cart->get_cart() as $cart_item ) {
                    
                    //This is main object we will use it for prepare array
                    $cart_product = $cart_item['data'];

                    // Check if product is not a product object then skip the loop
                    if ( ! is_a( $cart_product, 'WC_Product' ) ) {
                        continue;
                    }

                    //Get product id from product type from cart
                    $product_id = 'variation' === $cart_product->get_type() ? $cart_product->get_parent_id() : $cart_product->get_id();

                    // This object for checking purpose
                    $product = wc_get_product( $product_id );

                    // Brand check
                    if( array_search( 'brand', $condition, true ) ) {
                        $cart_item_data = wp_get_post_terms( $product_id, 'product_brand', array('fields' => 'ids') );
                    }

                    // wlf_location check
                    if( array_search( 'wlf_location', $condition, true ) ) {
                    	$cart_item_data = wp_get_post_terms( $product_id, 'location', array('fields' => 'ids') );
                    }

                    // Category check
                    if( array_search( 'category', $condition, true ) ) {
                        $cart_item_data = $product->get_category_ids();
                    }

                    // Tag check
                    if( array_search( 'tag', $condition, true ) ) {
                        $cart_item_data = $product->get_tag_ids();
                    }
                    
                    $flag = false;

                    if ( 
                        ( 'is_equal_to' === $condition['product_fees_conditions_is'] && array_intersect( $cart_item_data, $condition_value ) )
                        || ( 'not_in' === $condition['product_fees_conditions_is'] && ! array_intersect( $cart_item_data, $condition_value ) ) 
                    ) {
                        $flag = true;
                    }

                    if( $flag ) {
                        $prepare_array[$cart_product->get_id()]['quantity'] = absint( $cart_item['quantity'] );
                        $prepare_array[$cart_product->get_id()]['subtotal'] = floatval( wcpfc_pro_public()->wcpfc_pro_remove_currency_symbol(WC()->cart->get_product_subtotal( wc_get_product( $cart_product->get_id() ), $cart_item['quantity'] ) ) );
                    }
                }
            }
        }
        
        $return_data = array(
            'quantity' => array_sum(array_column($prepare_array, 'quantity')),
            'subtotal' => array_sum(array_column($prepare_array, 'subtotal'))
        );

        if (array_key_exists( $return_type, $return_data ) ) {
            return $return_data[$return_type];
        }

        return $return_data;
    }

	/**
     * Calculate quantity based fee
     * 
     * @param int $fees_id
     * 
     * @return float $fee_cost
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_calculate_quantity_based_fee__premium_only( $fees_id ) {

        $fee_cost = 0;

        if( empty( $fees_id ) ){
            return $fee_cost;
        }

		$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

        if( empty( $conditional_fee ) ){
            return $fee_cost;
        }

		$getFeesPerQtyFlag          = $conditional_fee->get_fee_chk_qty_price();
        $getFeesPerQty              = $conditional_fee->get_fee_per_qty();
        $extraProductCostOriginal 	= $conditional_fee->get_extra_product_cost();

        $extraProductCostOriginal   = $this->wcpfc_evaluate_cost__premium_only( $extraProductCostOriginal );

        if( 'on' === $getFeesPerQtyFlag ){
            if ( 'qty_cart_based' === $getFeesPerQty ) {
                
                $cart_based_qty = $this->wcpfc_pro_cart_line_specific_data__premium_only();
                $fee_cost += ( ( $cart_based_qty - 1 ) * $extraProductCostOriginal );
            } else if ( 'qty_product_based' === $getFeesPerQty ) {
                
                $products_based_qty = $this->wcpfc_pro_get_product_specific_cart_data__premium_only( $fees_id, 'quantity');
                $fee_cost += ( ( $products_based_qty - 1 ) * $extraProductCostOriginal );
            } else if( 'count_cart_based' === $getFeesPerQty ) {
                
                $cart_based_qty = $this->wcpfc_pro_cart_line_specific_data__premium_only('count');
                $fee_cost += ( ( $cart_based_qty - 1 ) * $extraProductCostOriginal );
            }
        }
        
        return $fee_cost;
    }

	/**
     * Calculate weight based fee
     * 
     * @param int $fees_id
     * 
     * @return float $fee_cost
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_calculate_weight_based_fee__premium_only( $fees_id ) {

        $fee_cost = 0;

        if( empty( $fees_id ) || empty( WC()->cart ) ){
            return $fee_cost;
        }

		$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

        if( empty( $conditional_fee ) ){
            return $fee_cost;
        }

        $is_allow_custom_weight_base = $conditional_fee->get_is_allow_custom_weight_base();

        if( "yes" === $is_allow_custom_weight_base ) {

            $total_cart_weights = WC()->cart->get_cart_contents_weight();

			$sm_custom_weight_base_cost = $conditional_fee->get_sm_custom_weight_base_cost();
            $sm_custom_weight_base_per_each = $conditional_fee->get_sm_custom_weight_base_per_each();
            $sm_custom_weight_base_over = $conditional_fee->get_sm_custom_weight_base_over();
            $sm_custom_weight_base_cost_shipping = 0;

            if( $total_cart_weights > 0 && $sm_custom_weight_base_per_each > 0 && $sm_custom_weight_base_cost > 0 && $total_cart_weights >= $sm_custom_weight_base_per_each ){
                if( $sm_custom_weight_base_over > 0 ){
                    if( $total_cart_weights >= $sm_custom_weight_base_over ){
                        $total_cart_weights = ($total_cart_weights - $sm_custom_weight_base_over);
                        $sm_custom_weight_base_cost_part = (float)( $total_cart_weights / $sm_custom_weight_base_per_each );
                        $sm_custom_weight_base_cost_shipping = (float)( $sm_custom_weight_base_cost * $sm_custom_weight_base_cost_part );
                    }
                } else {
                    $sm_custom_weight_base_cost_part = (float)( $total_cart_weights / $sm_custom_weight_base_per_each );
                    $sm_custom_weight_base_cost_shipping = (float)( $sm_custom_weight_base_cost * $sm_custom_weight_base_cost_part );
                }
                $fee_cost += $sm_custom_weight_base_cost_shipping;
            }
        }

        return $fee_cost;
    }


	/**
     * Get optional fee data which will use to preapre HTML on classic and block both checkout
     * 
     * @return array $optional_fee_array
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_get_optional_fee_data__premium_only() {

        if( is_admin() ){
            return;
        }

        $optional_fee_array = array();

        // Get all fees
        $wcpfc_pro_get_all_fees = $this->wcpfc_pro_get_all_fees();

		if ( ! empty( $wcpfc_pro_get_all_fees ) && count( $wcpfc_pro_get_all_fees ) > 0 ) { 

            foreach( $wcpfc_pro_get_all_fees as $fees_id ) {

				
                // Check for user first order or not
                $check_for_user = $this->wcpfc_pro_apply_fee_for_first_order( $fees_id );

                if( ! $check_for_user ) {
                    continue;
                }
				

                if( ! $this->wcpfc_pro_apply_fee_based_on_date_and_time__premium_only( $fees_id ) ){
                    continue;
                }

				// Retrieve the meta value for 'optional_fees_in_cart'
				$getOptionalFeesCartPage = get_post_meta($fees_id, 'optional_fees_in_cart', true);

				// Skip this iteration if 'optional_fees_in_cart' is not set or is empty
				if (empty($getOptionalFeesCartPage) && is_cart()) {
					continue;
				}

				$getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );

				if( 'yes' !== $getFeesOptional ) {
					continue;
				}

				$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );
				$fee_cost = 0; 

                // Get optional fee configuration
                if( $this->wcpfc_pro_is_fee_optional__premium_only( $fees_id ) ) {

					$fee_title           = get_the_title( $fees_id );
					$title               = ! empty( $fee_title ) ? esc_html( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ) : esc_html( 'Fee', 'woocommerce-conditional-product-fees-for-checkout' );

					// Get Fee based on cart total or cart subtotal
					$fees_on_cart_total         = $conditional_fee->get_fees_on_cart_total();
                    $getFeeType                 = $conditional_fee->get_fee_type();
                    $getFeesCost                = $conditional_fee->get_fee_settings_product_cost();

					if( 'on' === $fees_on_cart_total ) {

                        $cart_total = $this->wcpfc_cart_total();

                        // Apply basic configuration fee on cart total
                        $fee_cost = $this->wcpfc_pro_calculate_amount__premium_only( $getFeeType, $getFeesCost, $cart_total );
                    } else {
                        
						if (WC()->cart) {
							$cart_subtotal = floatval( wc_prices_include_tax() ? WC()->cart->subtotal : WC()->cart->subtotal_ex_tax );

							// Apply basic configuration fee on cart subtotal
							$fee_cost = $this->wcpfc_pro_calculate_amount__premium_only( $getFeeType, $getFeesCost, $cart_subtotal );
						}
                    }
                    
					// Per Quantity based calculation
                    $fee_cost += $this->wcpfc_pro_calculate_quantity_based_fee__premium_only( $fees_id );

					// Weight based calculation
                    $fee_cost += $this->wcpfc_pro_calculate_weight_based_fee__premium_only( $fees_id );

					// Check for conditional rule validation
                    $conditional_rule = new \Woocommerce_Conditional_Product_Fees_Conditional_Rules( $fees_id );

                    if( ! $conditional_rule->is_fee_passed_conditional_rule_validation( $fees_id ) ){
                        continue;
                    }

					$fee_cost += $conditional_rule->is_fee_passed_advanced_pricing_rule_validation($fees_id);
					

					/** 
					 * Check with Global setting things
					 */

					//Remove fee on 100% discount applied
					if( $this->wcpfc_pro_remove_fee_on_full_discount__premium_only() ){
						continue;
					}

					
					$getOtionalChecked      = $conditional_fee->get_default_optional_checked();
                    $getOtionalCheckedType  = $conditional_fee->get_fee_settings_optional_type();
                    $getOtionalDescription  = $conditional_fee->get_fee_settings_optional_description();

					$optional_fee_array[ $fees_id ] = array(
						'fee_id'            => $fees_id,
						'fee_title'         => $title,
						'fee_cost'          => $fee_cost,
						'fee_checked'       => $getOtionalChecked,
						'fee_checked_type'  => $getOtionalCheckedType,
						'fee_description'   => $getOtionalDescription,
					);

				}

            } 

        }
        return $optional_fee_array;

	}

	/**
	 * Apply optional fees in block based checkout page.
	 *
	 * @since    1.0.0
     */
    public function wcpfc_pro_apply_conditional_fee_to_checkout__premium_only() {

		// We are checking block on checkout here as this optional fee will only show on checkout page
        $is_checkout_has_block = wcpfc_pro()->is_wc_has_block( 'checkout' );

        // check if block enable on page then check for optional fee data in session
        $optional_fee_array = ( $is_checkout_has_block && WC()->session->__isset('wcpfc_pro_optional_fee') ) ? WC()->session->get('wcpfc_pro_optional_fee') : array();

		// Get all fees
		$wcpfc_pro_get_all_fees = $this->wcpfc_pro_get_all_fees();

		// If merge all fee into one fee enabled then this @var will use
		$total_fee = 0;

		if ( isset( $wcpfc_pro_get_all_fees ) && ! empty( $wcpfc_pro_get_all_fees ) ) {

			foreach ( $wcpfc_pro_get_all_fees as $fees_id ) {

				// Check for user first order or not
                $check_for_user = $this->wcpfc_pro_apply_fee_for_first_order( $fees_id );

                if( ! $check_for_user ) {
                    continue;
                }

				if( ! $this->wcpfc_pro_apply_fee_based_on_date_and_time__premium_only( $fees_id ) ){
                    continue;
                }

				// Check for conditional rule validation
				$conditional_rule = new \Woocommerce_Conditional_Product_Fees_Conditional_Rules( $fees_id );

				if( ! $conditional_rule->is_fee_passed_conditional_rule_validation( $fees_id ) ){
					continue;
				}

				// Retrieve the meta value for 'optional_fees_in_cart'
				$getOptionalFeesCartPage = get_post_meta($fees_id, 'optional_fees_in_cart', true);

				// Skip this iteration if 'optional_fees_in_cart' is not set or is empty
				if (empty($getOptionalFeesCartPage) && is_cart()) {
					continue;
				}

				$getFeesOptional = get_post_meta( $fees_id, 'fee_settings_select_optional', true );

				if( 'yes' !== $getFeesOptional ) {
					continue;
				}

				$conditional_fee = new \Woocommerce_Conditional_Product_Fees( $fees_id );

				$final_item_tax_class       = '';
				$fee_title           		= get_the_title( $fees_id );
				$title               		= ! empty( $fee_title ) ? esc_html( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ) : esc_html( 'Fee', 'woocommerce-conditional-product-fees-for-checkout' );
				$fee_cost                   = 0;
				$getFeetaxable   		    = $conditional_fee->get_fee_settings_select_taxable();
                $texable      			    = ( !empty( $getFeetaxable ) && 'yes' === $getFeetaxable ) ? true : false;
				// Get Fee based on cart total or cart subtotal
				$fees_on_cart_total         = $conditional_fee->get_fees_on_cart_total();
				$getFeeType                 = $conditional_fee->get_fee_type();
				$getFeesCost                = $conditional_fee->get_fee_settings_product_cost();

				$chk_enable_custom_fun  = get_option( 'chk_enable_custom_fun' );

				if( 'on' === $fees_on_cart_total ) {

					$cart_total = $this->wcpfc_cart_total();

					// Apply basic configuration fee on cart total
					$fee_cost = $this->wcpfc_pro_calculate_amount__premium_only( $getFeeType, $getFeesCost, $cart_total );
				} else {
					
					$cart_subtotal = floatval( wc_prices_include_tax() ? WC()->cart->subtotal : WC()->cart->subtotal_ex_tax );

					// Apply basic configuration fee on cart subtotal
					$fee_cost = $this->wcpfc_pro_calculate_amount__premium_only( $getFeeType, $getFeesCost, $cart_subtotal );
				}

				// Check for conditional rule validation
				$conditional_rule = new \Woocommerce_Conditional_Product_Fees_Conditional_Rules( $fees_id );
				$fee_cost += $conditional_rule->is_fee_passed_advanced_pricing_rule_validation($fees_id);
				
				// Skip this fee if it is optional and not selected
                if( $this->wcpfc_pro_is_fee_optional__premium_only( $fees_id, $optional_fee_array ) ) {
                    continue;
                }

				// Per Quantity based calculation
				$fee_cost += $this->wcpfc_pro_calculate_quantity_based_fee__premium_only( $fees_id );

				// Weight based calculation
				$fee_cost += $this->wcpfc_pro_calculate_weight_based_fee__premium_only( $fees_id );

				// Remove all fees if merge fee global option is enable, we will combine them and add as one fee
                if( 'on' === $chk_enable_custom_fun ) {
                    $total_fee += $fee_cost;
                    continue;
                }

				// This will go last as our all calculation on cart will above this
                WC()->cart->add_fee( 
                    $title,
                    $fee_cost, 
                    $texable, 
                    apply_filters('wcpfc_pro_tax_class', $final_item_tax_class, $fees_id ) 
                );
			}

			// Apply combined fee
			if ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) ) {
				if ( isset( $total_fee ) && 0 < $total_fee ) {
					$chk_enable_all_fee_tax   = ( 'on' === get_option( 'chk_enable_all_fee_tax', 'no' ) ) ? true : false;
					$fee_title              = apply_filters( 'wcpfc_all_fee_title', __( ' Fees', 'woocommerce-conditional-product-fees-for-checkout' ) );

					WC()->cart->add_fee( wp_kses_post( $fee_title, 'woocommerce-conditional-product-fees-for-checkout' ), $total_fee, $chk_enable_all_fee_tax, apply_filters('wcpfc_tax_class', $final_item_tax_class, -1)); //-1 for combined fees id
				}
			}
		}

	}

	/**
     * Include optional fee template on block cart
     * 
     * @param string $block_content
     * 
     * @return string $block_content with appended optional fee HTML
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_block_cart_include_optional_fee_template__premium_only( $block_content ){

        if( ! wcpfc_pro()->is_wc_has_block( 'cart' ) ){
            return $block_content;
        }

        $get_optional_fee_data = $this->wcpfc_pro_get_optional_fee_data__premium_only();

		$valid_fees = [];

		// Ensure $get_optional_fee_data is an array or an object
		if (is_array($get_optional_fee_data) || is_object($get_optional_fee_data)) {
			// Iterate over the optional fee data
			foreach($get_optional_fee_data as $key => $value){
				// Retrieve the meta value for 'optional_fees_in_cart'
				$getOptionalFeesCartPage = get_post_meta($key, 'optional_fees_in_cart', true);

				// Skip this iteration if 'optional_fees_in_cart' is not set or is empty
				if (empty($getOptionalFeesCartPage)) {
					continue;
				}

				// Add valid fees to the $valid_fees array
				$valid_fees[$key] = $value;
			}
		}

		// Only proceed if there are valid fees to display
		if( !empty($valid_fees) ) {
        
			ob_start();

			// Optional Fee at Checkout HTML
			wcpfc_pro()->include_template( 'public/partials/woocommerce-conditional-optional-fee.php', array( 
					'optional_fee_data' => $get_optional_fee_data,
					'page' => 'cart'
				) 
			);

			$checkout_bump_html = ob_get_clean();
        	return $block_content . $checkout_bump_html;

		}

		return $block_content;
    }

	/**
     * Include optional fee template on block checkout
     * 
     * @param string $block_content
     * 
     * @return string $block_content with appended optional fee HTML
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_block_checkout_include_optional_fee_template__premium_only( $block_content ){

        if( ! wcpfc_pro()->is_wc_has_block( 'checkout' ) ){
            return $block_content;
        }

        $get_optional_fee_data = $this->wcpfc_pro_get_optional_fee_data__premium_only();
        
        ob_start();

        // Optional Fee at Checkout HTML
        wcpfc_pro()->include_template( 'public/partials/woocommerce-conditional-optional-fee.php', array( 
                'optional_fee_data' => $get_optional_fee_data,
				'page' => 'checkout'
            ) 
        );

        $checkout_bump_html = ob_get_clean();

        return $block_content . $checkout_bump_html;
    }

	// Function to return custom allowed HTML
	public function wcpfc_get_allowed_html() {
		// Get default allowed HTML
		$allowed_html = wp_kses_allowed_html( 'post' );

		// Add select, option, input (checkbox/radio) to the allowed HTML
		$allowed_html['select'] = array(
			'name'  => array(),
			'id'    => array(),
			'class' => array(),
			'data-value' => array(),
		);

		$allowed_html['option'] = array(
			'value'    => array(),
			'selected' => array(),
			'data-value' => array(),
		);

		$allowed_html['input'] = array(
			'type'    => array(),
			'name'    => array(),
			'id'      => array(),
			'value'   => array(),
			'checked' => array(),
			'class'   => array(),
			'data-value' => array(),
		);

		return $allowed_html;
	}

	/* Ajax request check */
	public function is_ajax_request() {
        return ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ); //phpcs:ignore
    }


	/**
     * Add/Remove optional fee at Block Checkout, here we are use session to transfer data to hook
     * it will use in `woocommerce_cart_calculate_fees` hook
     * 
     * @since 1.0.0
     */
    public function wcpfc_pro_block_add_remove_optional_fee_at_checkout__premium_only() {
        woocommerce_store_api_register_update_callback(
			array(
				'namespace' => 'woocommerce-conditional-product-optional-fees',
                'callback'  => function( $data ) {
                    
                    // Check if optional fees are selected/checked on block checkout then store them in session to interect with hook
                    if( isset( $data['fees_ids'] ) && !empty($data['fees_ids'] ) ) {
                        WC()->session->set( 'wcpfc_pro_optional_fee', array_map( 'intval', $data ) );
                    }

                    // Check for payment method
                    if( isset( $data['payment_method'] ) && !empty( $data['payment_method'] ) ) {
                        WC()->session->set( 'chosen_payment_method', $data['payment_method'] );
                    } else {
                        WC()->session->set( 'chosen_payment_method', '' );
                    }
				},
			)
		);
    }

    /**
     * Retrive fee ID from fee name
     * 
     * @param string $fee_name
     * 
     * @return int $fee_id
     * 
     * @since 4.3.0
     */
    public function wcpfc_fee_id_from_name( $fee_name ) {

        if( empty( $fee_name ) ) {
            return 0;
        }

        // This will return latest fee if fond same fee name found
        $fee_args = new WP_Query(
            array(
                'post_type'              => 'wc_conditional_fee',
                'title'                  => $fee_name,
                'post_status'            => 'publish',
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'ignore_sticky_posts'    => true,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,          
                'orderby'                => 'post_date',
                'order'                  => 'DESC',
            )
        );
        
        $fee_object = null;

        if ( ! empty( $fee_args->post ) ) {
            $fee_object = $fee_args->post;
        }

        $fee_id = (int) isset($fee_object->ID) && !empty($fee_object->ID) ? $fee_object->ID : 0;

        return $fee_id;
    }

     /**
     * Display fee tooltip on cart and checkout page
     * 
     * @param string $fee_html
     * @param object $fee
     * 
     * @return string $fee_html
     * 
     * @since 4.3.0
     */
    public function wcpfc_fee_tooltip( $fee_html, $fee ) {

        $fee_id = $this->wcpfc_fee_id_from_name( $fee->name );
        $fee_tooltip = '';

        if( !empty( $fee_id ) ) {

            $wcpfc_fee = new \Woocommerce_Conditional_Product_Fees( $fee_id );
            $fee_tooltip = $wcpfc_fee->get_wcpfc_tooltip_description();
        } else {
            
            $combine_fees_status = get_option( 'chk_enable_custom_fun', 'no' );
            $combine_fees_tooltip = get_option( 'chk_enable_all_fee_tooltip', 'no' );
            if( 'on' === $combine_fees_tooltip && 'on' === $combine_fees_status ) {
                $fee_tooltip = get_option( 'chk_enable_all_fee_tooltip_text', '' );
            }
        }
        
        if( !empty( $fee_tooltip ) ) {
            $fee_html .= sprintf( ' <a class="wc-wcpfc-help-tip" data-tooltip="%s"><i class="fa fa-question-circle fa-lg"></i></a>', esc_attr( $fee_tooltip ) );
        }

        return $fee_html;
    }

     /**
     * List all fees with tooltip data (For Block Cart/Checkout Use)
     * 
     * @return array $fee_tooltip_data
     * 
     * @since 4.3.0
     */
    public function wcpfc_all_fee_tooltip_data() {

        $all_fees = $this->wcpfc_pro_get_all_fees();

        $fee_tooltip_data = array();

        if( !empty( $all_fees ) ) {
            
            $combine_fees_status = get_option( 'chk_enable_custom_fun', 'off' );
            if( 'on' === $combine_fees_status ) {
                
                $combine_fees_tooltip = get_option( 'chk_enable_all_fee_tooltip', 'off' );
                if( 'on' === $combine_fees_tooltip ) {
                    $fee_tooltip = get_option( 'chk_enable_all_fee_tooltip_text', '' );
                    if( !empty( $fee_tooltip ) ) {
                        $combine_fee_title = apply_filters('wcpfc_all_fee_title', __( 'Fees', 'woocommerce-conditional-product-fees-for-checkout' ) );
                        $fee_tooltip_data[ sanitize_title( $combine_fee_title ) ] = esc_html( $fee_tooltip );
                    }
                }
            } else {
                
                foreach( $all_fees as $fee_id ) {
                    $advance_fee = new \Woocommerce_Conditional_Product_Fees( $fee_id );
                    if( $advance_fee->has_wcpfc_tooltip_description() ) {
                        $fee_tooltip_data[ sanitize_title( $advance_fee->get_name() ) ] = $advance_fee->get_wcpfc_tooltip_description();
                    }
                }
            }

        }

        return $fee_tooltip_data;
    }

	/**
	 * Gets the main Woocommerce Conditional Product Fees For Checkout Pro instance.
	 *
	 * Ensures only one instance loaded at one time.
	 *
	 * @see \wcpfc_pro()
	 *
	 * @since 1.0.0
	 *
	 * @return \Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public
	 */
	public static function instance($plugin_name, $version) {

		if ( null === self::$instance ) {
			self::$instance = new self($plugin_name, $version);
		}

		return self::$instance;
	}		

}

/**
 * Returns the One True Instance of Woocommerce Conditional Product Fees For Checkout Pro Public class object.
 *
 * @since 1.0.0
 *
 * @return \Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public
 */
function wcpfc_pro_public(){
	return \Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Public::instance( '', '' );
}

/** Show the fees once trial coupon code apply on subscription product */
add_filter('wcs_remove_fees_from_initial_cart','wcs_remove_fees_from_initial_cart_custom');
function wcs_remove_fees_from_initial_cart_custom(){
	return false;
}
