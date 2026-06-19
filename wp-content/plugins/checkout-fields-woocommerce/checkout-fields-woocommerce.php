<?php
/**
 * Plugin Name: WooCommerce Simple Course Recipients
 * Description: Basic recipient fields implementation with quantity support
 * Version: 1.0.0
 * Author: Gabe @ DD
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    
    /**
     * Prevent items from being grouped in cart
     */
    function course_prevent_cart_item_merging($cart_item_data, $product_id) {
        // Add a unique ID to prevent items from being merged/grouped
        $cart_item_data['unique_key'] = md5(microtime() . rand());
        return $cart_item_data;
    }
    add_filter('woocommerce_add_cart_item_data', 'course_prevent_cart_item_merging', 10, 2);

    /**
     * Whether a product is configured to collect each recipient's date of birth.
     *
     * Driven by the "Collect date of birth" checkbox on the product edit screen
     * (stored as the _collect_date_of_birth meta). Used everywhere the DOB field
     * is rendered, validated and saved so the behaviour stays per-product.
     */
    function course_product_requires_dob($product_id) {
        return 'yes' === get_post_meta($product_id, '_collect_date_of_birth', true);
    }

    /**
     * Add the "Collect date of birth" checkbox to the product data panel.
     */
    function course_add_dob_product_option() {
        woocommerce_wp_checkbox(array(
            'id'          => '_collect_date_of_birth',
            'label'       => __('Collect date of birth', 'woocommerce'),
            'description' => __('Show a date of birth field for each recipient in the cart.', 'woocommerce'),
        ));
    }
    add_action('woocommerce_product_options_general_product_data', 'course_add_dob_product_option');

    /**
     * Save the "Collect date of birth" checkbox.
     *
     * WooCommerce verifies the meta-box nonce and edit capability before firing
     * woocommerce_process_product_meta, so we only normalise the checkbox value.
     */
    function course_save_dob_product_option($post_id) {
        $value = isset($_POST['_collect_date_of_birth']) ? 'yes' : 'no';
        update_post_meta($post_id, '_collect_date_of_birth', $value);
    }
    add_action('woocommerce_process_product_meta', 'course_save_dob_product_option');

    /**
     * Add recipient fields after cart item name
     */
    function course_add_recipient_fields_after_cart_item_name($cart_item, $cart_item_key) {
        // Get quantity
        $quantity = $cart_item['quantity'];
        
        // Get saved recipient data (if any)
        $recipients = isset($cart_item['course_recipients']) ? $cart_item['course_recipients'] : array();

        // Whether this product collects a date of birth per recipient.
        $collect_dob = course_product_requires_dob($cart_item['product_id']);

        // Container for all recipient forms
        echo '<div class="course-recipients-container">';

        // Add a form for each quantity
        for ($i = 0; $i < $quantity; $i++) {
            // Get saved values for this recipient (if any)
            $first_name = isset($recipients[$i]['first_name']) ? $recipients[$i]['first_name'] : '';
            $last_name = isset($recipients[$i]['last_name']) ? $recipients[$i]['last_name'] : '';
            $email = isset($recipients[$i]['email']) ? $recipients[$i]['email'] : '';
            $dob = isset($recipients[$i]['dob']) ? $recipients[$i]['dob'] : '';
            
            // Only show recipient number if there's more than one
            $recipient_label = $quantity > 1 ? 'Recipient ' . ($i + 1) . ' of ' . $quantity : 'Recipient Information';
            
            ?>
            <div class="course-recipient-form">
                <p class="course-recipient-form-label"><?php echo 	esc_html($recipient_label); ?></p>
                
                <div class="course-recipient-field">
                    <label for="firstname">First Name:</label>
                    <input type="text" 
						   id="firstname"
                           name="recipient_first_name[<?php echo esc_attr($cart_item_key); ?>][<?php echo $i; ?>]"
                           value="<?php echo esc_attr($first_name); ?>"
                           class="input-text"
                           />
                </div>
                
                <div class="course-recipient-field">
                    <label for="lastname">Last Name:</label>
                    <input type="text"
						   id="lastname"
                           name="recipient_last_name[<?php echo esc_attr($cart_item_key); ?>][<?php echo $i; ?>]"
                           value="<?php echo esc_attr($last_name); ?>"
                           class="input-text"
                           />
                </div>
                
                <div class="course-recipient-field">
                    <label for="email">Email:</label>
                    <input type="email"
						   id="email"
                           name="recipient_email[<?php echo esc_attr($cart_item_key); ?>][<?php echo $i; ?>]"
                           value="<?php echo esc_attr($email); ?>"
                           class="input-text"
                           />
                </div>

                <?php if ($collect_dob) : ?>
                <div class="course-recipient-field">
                    <label for="dob">Date of Birth:</label>
                    <input type="date"
						   id="dob"
                           name="recipient_dob[<?php echo esc_attr($cart_item_key); ?>][<?php echo $i; ?>]"
                           value="<?php echo esc_attr($dob); ?>"
                           class="input-text"
                           />
                </div>
                <?php endif; ?>
            </div>
            <?php
        }
        
        echo '</div>';
    }
    add_action('woocommerce_after_cart_item_name', 'course_add_recipient_fields_after_cart_item_name', 10, 2);
    
    /**
     * Persist the posted recipient fields into the cart contents.
     *
     * Shared by the standard "Update cart" submit and the AJAX save that runs
     * when a customer clicks "Proceed to checkout". Reads recipient_first_name,
     * recipient_last_name and recipient_email out of $_POST (each keyed by cart
     * item key then recipient index) and writes them onto the matching cart line.
     *
     * Does not call set_session() — callers persist when appropriate.
     */
    function course_persist_recipients_from_post() {
        $cart = WC()->cart;

        if (!$cart) {
            return;
        }

        // Map each POST array to the recipient sub-key it populates.
        $field_map = array(
            'recipient_first_name' => 'first_name',
            'recipient_last_name'  => 'last_name',
            'recipient_email'      => 'email',
            'recipient_dob'        => 'dob',
        );

        foreach ($field_map as $post_key => $recipient_key) {
            if (!isset($_POST[$post_key]) || !is_array($_POST[$post_key])) {
                continue;
            }

            foreach (wp_unslash($_POST[$post_key]) as $cart_item_key => $values) {
                $cart_item_key = sanitize_text_field($cart_item_key);

                // Ignore anything that doesn't map to a real cart line.
                if (!isset($cart->cart_contents[$cart_item_key]) || !is_array($values)) {
                    continue;
                }

                if (!isset($cart->cart_contents[$cart_item_key]['course_recipients'])) {
                    $cart->cart_contents[$cart_item_key]['course_recipients'] = array();
                }

                foreach ($values as $index => $value) {
                    $index = absint($index);

                    if (!isset($cart->cart_contents[$cart_item_key]['course_recipients'][$index])) {
                        $cart->cart_contents[$cart_item_key]['course_recipients'][$index] = array();
                    }

                    $clean = ('email' === $recipient_key) ? sanitize_email($value) : sanitize_text_field($value);
                    $cart->cart_contents[$cart_item_key]['course_recipients'][$index][$recipient_key] = $clean;
                }
            }
        }
    }

    /**
     * Update recipient fields when the cart is updated via the "Update cart" button.
     */
    function course_update_recipient_fields() {
        // Only proceed if the update_cart button was clicked
        if (!isset($_POST['update_cart'])) {
            return;
        }

        course_persist_recipients_from_post();
    }
    add_action('woocommerce_update_cart_action_cart_updated', 'course_update_recipient_fields', 20);

    /**
     * AJAX: save recipient details, then report whether the cart is ready for checkout.
     *
     * Fired by the cart-page script when the customer clicks "Proceed to
     * checkout". This removes the need to press "Update cart" first — the
     * details are persisted to the session here, and the existing
     * woocommerce_checkout_process validation remains the server-side backstop.
     */
    function course_ajax_save_recipients() {
        check_ajax_referer('course_save_recipients', 'nonce');

        if (!WC()->cart) {
            wp_send_json_error(array('message' => __('Your cart is unavailable. Please refresh and try again.', 'woocommerce')));
        }

        course_persist_recipients_from_post();
        WC()->cart->set_session();

        // Mirror the checkout validation so the redirect can't dead-end.
        $errors = array();

        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_name = $cart_item['data']->get_name();
            $collect_dob  = course_product_requires_dob($cart_item['product_id']);

            if (!isset($cart_item['course_recipients']) || !is_array($cart_item['course_recipients'])) {
                $errors[] = sprintf(__('Please enter recipient information for "%s".', 'woocommerce'), $product_name);
                continue;
            }

            foreach ($cart_item['course_recipients'] as $index => $recipient) {
                if (empty($recipient['first_name']) || empty($recipient['last_name']) || empty($recipient['email']) || !is_email($recipient['email'])) {
                    $errors[] = sprintf(__('Please complete valid recipient details for recipient %1$d of "%2$s".', 'woocommerce'), $index + 1, $product_name);
                }

                if ($collect_dob && empty($recipient['dob'])) {
                    $errors[] = sprintf(__('Please enter the date of birth for recipient %1$d of "%2$s".', 'woocommerce'), $index + 1, $product_name);
                }
            }
        }

        if (!empty($errors)) {
            wp_send_json_error(array('errors' => $errors));
        }

        wp_send_json_success();
    }
    add_action('wp_ajax_course_save_recipients', 'course_ajax_save_recipients');
    add_action('wp_ajax_nopriv_course_save_recipients', 'course_ajax_save_recipients');

    /**
     * Enqueue the cart-page script that gates "Proceed to checkout".
     */
    function course_enqueue_cart_assets() {
        if (!function_exists('is_cart') || !is_cart()) {
            return;
        }

        wp_enqueue_style(
            'course-cart-recipients',
            plugins_url('assets/css/cart-recipients.css', __FILE__),
            array(),
            '1.2.0'
        );

        wp_enqueue_script(
            'course-cart-recipients',
            plugins_url('assets/js/cart-recipients.js', __FILE__),
            array('jquery'),
            '1.2.0',
            true
        );

        wp_localize_script('course-cart-recipients', 'CourseRecipients', array(
            'ajaxUrl'  => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('course_save_recipients'),
            'messages' => array(
                'incomplete'   => __('Please complete the recipient details for every course before proceeding to checkout.', 'woocommerce'),
                'invalidEmail' => __('Please enter a valid email address for every recipient before proceeding to checkout.', 'woocommerce'),
                'saveFailed'   => __('Sorry, we couldn\'t save the recipient details. Please try again.', 'woocommerce'),
            ),
        ));
    }
    add_action('wp_enqueue_scripts', 'course_enqueue_cart_assets');

    /**
     * Show recipient details as line-item meta in the checkout order table.
     *
     * Feeds the rows WooCommerce renders beneath each product in the checkout
     * "Your order" table (and the mini-cart) so customers can confirm what they
     * submitted. Skipped on the main cart page, where the editable recipient
     * boxes already display this information.
     */
    function course_display_recipients_item_data($item_data, $cart_item) {
        if (function_exists('is_cart') && is_cart()) {
            return $item_data;
        }

        if (empty($cart_item['course_recipients']) || !is_array($cart_item['course_recipients'])) {
            return $item_data;
        }

        $recipients = $cart_item['course_recipients'];
        $multiple   = count($recipients) > 1;

        foreach ($recipients as $index => $recipient) {
            $first = isset($recipient['first_name']) ? $recipient['first_name'] : '';
            $last  = isset($recipient['last_name']) ? $recipient['last_name'] : '';
            $email = isset($recipient['email']) ? $recipient['email'] : '';
            $dob   = isset($recipient['dob']) ? $recipient['dob'] : '';

            $name  = trim($first . ' ' . $last);

            if ('' === $name && '' === $email && '' === $dob) {
                continue;
            }

            $value = $name;
            if ('' !== $email) {
                $value .= ('' !== $name) ? ' (' . $email . ')' : $email;
            }
            if ('' !== $dob) {
                $value .= ('' !== $value) ? ' — ' . sprintf(__('DOB: %s', 'woocommerce'), $dob) : sprintf(__('DOB: %s', 'woocommerce'), $dob);
            }

            $item_data[] = array(
                'key'     => $multiple ? sprintf(__('Recipient %d', 'woocommerce'), $index + 1) : __('Recipient', 'woocommerce'),
                'value'   => $value,
                'display' => esc_html($value),
            );
        }

        return $item_data;
    }
    add_filter('woocommerce_get_item_data', 'course_display_recipients_item_data', 10, 2);
    
    /**
     * Handle quantity changes
     */
    function course_handle_quantity_changes($cart) {
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (isset($cart_item['course_recipients'])) {
                $quantity = $cart_item['quantity'];
                $recipients = $cart_item['course_recipients'];
                
                // If quantity is less than number of recipients, keep only what we need
                if (count($recipients) > $quantity) {
                    $cart->cart_contents[$cart_item_key]['course_recipients'] = array_slice($recipients, 0, $quantity);
                }
            }
        }
    }
    add_action('woocommerce_before_calculate_totals', 'course_handle_quantity_changes', 20, 1);
    
    /**
     * Validate recipient fields at checkout
     */
    function course_validate_recipient_fields_at_checkout() {
        $cart_items = WC()->cart->get_cart();
        
        foreach ($cart_items as $cart_item_key => $cart_item) {
            $product_name = $cart_item['data']->get_name();
            $quantity = $cart_item['quantity'];
            $collect_dob = course_product_requires_dob($cart_item['product_id']);

            // Check if we have recipients data
            if (!isset($cart_item['course_recipients']) || !is_array($cart_item['course_recipients'])) {
                wc_add_notice(sprintf(__('Please enter recipient information for "%s"', 'woocommerce'), $product_name), 'error');
                continue;
            }
            
            // Validate each recipient
            foreach ($cart_item['course_recipients'] as $index => $recipient) {
                if (empty($recipient['first_name'])) {
                    wc_add_notice(sprintf(__('Please enter first name for recipient %d of "%s"', 'woocommerce'), $index + 1, $product_name), 'error');
                }
                
                if (empty($recipient['last_name'])) {
                    wc_add_notice(sprintf(__('Please enter last name for recipient %d of "%s"', 'woocommerce'), $index + 1, $product_name), 'error');
                }
                
                if (empty($recipient['email'])) {
                    wc_add_notice(sprintf(__('Please enter email for recipient %d of "%s"', 'woocommerce'), $index + 1, $product_name), 'error');
                } elseif (!is_email($recipient['email'])) {
                    wc_add_notice(sprintf(__('Please enter a valid email for recipient %d of "%s"', 'woocommerce'), $index + 1, $product_name), 'error');
                }

                if ($collect_dob && empty($recipient['dob'])) {
                    wc_add_notice(sprintf(__('Please enter date of birth for recipient %d of "%s"', 'woocommerce'), $index + 1, $product_name), 'error');
                }
            }
        }
    }
    add_action('woocommerce_checkout_process', 'course_validate_recipient_fields_at_checkout');
    
    /**
     * Save recipient information as order item meta
     */
    function course_save_recipient_info_to_order_items($item, $cart_item_key, $values) {
        if (isset($values['course_recipients']) && is_array($values['course_recipients'])) {
            // Save the entire recipients array
            $item->add_meta_data('_course_recipients', $values['course_recipients']);
            
            // Also save individual recipients
			
            foreach ($values['course_recipients'] as $index => $recipient) {
                $prefix = count($values['course_recipients']) > 1 ? 'Recipient ' . ($index + 1) . ' ' : '';
                
                if (!empty($recipient['first_name'])) {
                    $item->add_meta_data($prefix . 'First Name', $recipient['first_name'], true);
                }
                
                if (!empty($recipient['last_name'])) {
                    $item->add_meta_data($prefix . 'Last Name', $recipient['last_name'], true);
                }
                
                if (!empty($recipient['email'])) {
                    $item->add_meta_data($prefix . 'Email', $recipient['email'], true);
                }

                if (!empty($recipient['dob'])) {
                    $item->add_meta_data($prefix . 'Date of Birth', $recipient['dob'], true);
                }
            }
        }
    }
    add_action('woocommerce_checkout_create_order_line_item', 'course_save_recipient_info_to_order_items', 10, 3);
    
    /**
     * Hook for API integration with LMS (to be implemented later)
     */
    function course_process_lms_enrollment($order_id) {
        $order = wc_get_order($order_id);
        
        foreach ($order->get_items() as $item_id => $item) {
            $recipients = $item->get_meta('_course_recipients');
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            
            if (is_array($recipients)) {
                foreach ($recipients as $recipient) {
                    $first_name = isset($recipient['first_name']) ? $recipient['first_name'] : '';
                    $last_name = isset($recipient['last_name']) ? $recipient['last_name'] : '';
                    $email = isset($recipient['email']) ? $recipient['email'] : '';
                    $dob = isset($recipient['dob']) ? $recipient['dob'] : '';

                    if ($first_name && $last_name && $email) {
                        // This is where you would make your API call to the LMS
                        // Example placeholder for future implementation:
                        /*
                        $lms_api_data = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $email,
                            'dob' => $dob,
                            'course_id' => $product_id,
                            'variation_id' => $variation_id,
                            'order_id' => $order_id
                        );
                        
                        // Make API call here
                        */
                    }
                }
            }
        }
    }
    add_action('woocommerce_order_status_completed', 'course_process_lms_enrollment');
}
?>