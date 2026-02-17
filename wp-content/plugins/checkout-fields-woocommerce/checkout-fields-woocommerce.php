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
     * Add recipient fields after cart item name
     */
    function course_add_recipient_fields_after_cart_item_name($cart_item, $cart_item_key) {
        // Get quantity
        $quantity = $cart_item['quantity'];
        
        // Get saved recipient data (if any)
        $recipients = isset($cart_item['course_recipients']) ? $cart_item['course_recipients'] : array();
        
        // Container for all recipient forms
        echo '<div class="course-recipients-container">';
        
        // Add a form for each quantity
        for ($i = 0; $i < $quantity; $i++) {
            // Get saved values for this recipient (if any)
            $first_name = isset($recipients[$i]['first_name']) ? $recipients[$i]['first_name'] : '';
            $last_name = isset($recipients[$i]['last_name']) ? $recipients[$i]['last_name'] : '';
            $email = isset($recipients[$i]['email']) ? $recipients[$i]['email'] : '';
            
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
            </div>
            <?php
        }
        
        echo '</div>';
    }
    add_action('woocommerce_after_cart_item_name', 'course_add_recipient_fields_after_cart_item_name', 10, 2);
    
    /**
     * Update recipient fields when cart is updated
     */
    function course_update_recipient_fields() {
        // Only proceed if the update_cart button was clicked
        if (!isset($_POST['update_cart'])) {
            return;
        }
        
        // Process first names
        if (isset($_POST['recipient_first_name']) && is_array($_POST['recipient_first_name'])) {
            foreach ($_POST['recipient_first_name'] as $cart_item_key => $values) {
                if (isset(WC()->cart->cart_contents[$cart_item_key])) {
                    // Initialize recipients array if it doesn't exist
                    if (!isset(WC()->cart->cart_contents[$cart_item_key]['course_recipients'])) {
                        WC()->cart->cart_contents[$cart_item_key]['course_recipients'] = array();
                    }
                    
                    // Store each recipient's first name
                    foreach ($values as $index => $value) {
                        // Initialize this recipient if needed
                        if (!isset(WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index])) {
                            WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index] = array();
                        }
                        
                        WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index]['first_name'] = sanitize_text_field($value);
                    }
                }
            }
        }
        
        // Process last names
        if (isset($_POST['recipient_last_name']) && is_array($_POST['recipient_last_name'])) {
            foreach ($_POST['recipient_last_name'] as $cart_item_key => $values) {
                if (isset(WC()->cart->cart_contents[$cart_item_key])) {
                    // Initialize recipients array if it doesn't exist
                    if (!isset(WC()->cart->cart_contents[$cart_item_key]['course_recipients'])) {
                        WC()->cart->cart_contents[$cart_item_key]['course_recipients'] = array();
                    }
                    
                    // Store each recipient's last name
                    foreach ($values as $index => $value) {
                        // Initialize this recipient if needed
                        if (!isset(WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index])) {
                            WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index] = array();
                        }
                        
                        WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index]['last_name'] = sanitize_text_field($value);
                    }
                }
            }
        }
        
        // Process emails
        if (isset($_POST['recipient_email']) && is_array($_POST['recipient_email'])) {
            foreach ($_POST['recipient_email'] as $cart_item_key => $values) {
                if (isset(WC()->cart->cart_contents[$cart_item_key])) {
                    // Initialize recipients array if it doesn't exist
                    if (!isset(WC()->cart->cart_contents[$cart_item_key]['course_recipients'])) {
                        WC()->cart->cart_contents[$cart_item_key]['course_recipients'] = array();
                    }
                    
                    // Store each recipient's email
                    foreach ($values as $index => $value) {
                        // Initialize this recipient if needed
                        if (!isset(WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index])) {
                            WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index] = array();
                        }
                        
                        WC()->cart->cart_contents[$cart_item_key]['course_recipients'][$index]['email'] = sanitize_email($value);
                    }
                }
            }
        }
    }
    add_action('woocommerce_update_cart_action_cart_updated', 'course_update_recipient_fields', 20);
    
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
			error_log(print_r($values, true));
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
                    
                    if ($first_name && $last_name && $email) {
                        // This is where you would make your API call to the LMS
                        // Example placeholder for future implementation:
                        /*
                        $lms_api_data = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $email,
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