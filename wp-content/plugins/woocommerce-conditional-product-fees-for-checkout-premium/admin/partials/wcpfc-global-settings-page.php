<?php
/**
 * Handles plugin global settings
 * 
 * @package Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @since   3.9.3
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );

// Function for free plugin content
function wcpfc_free_global_settings_content() {
    $allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
    ?>
    <div class="wcpfc-mastersettings wcpfc-table-tooltip wcpfc-upgrade-pro-to-unlock">
        <div class="mastersettings-title">
            <h2><?php esc_html_e( 'Global Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?><div class="wcpfc-pro-label"></div></h2>
        </div>
        <table class="table-mastersettings form-table table-outer" cellpadding="0" cellspacing="0">
            <tbody>
                <tr valign="top" id="enable_coupon_fee">
                    <th class="table-whattodo fr-1">
                        <label for="chk_move_menu_under_wc">
                            <?php esc_html_e( 'Move main menu under WooCommerce > Extra Fees', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                            <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the main menu of the plugin will be moved to the "WooCommerce > Extra Fees" section.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="chk_move_menu_under_wc" id="chk_move_menu_under_wc" value="">
                    </td>
                </tr>
                <tr valign="top" id="enable_coupon_fee">
                    <th class="table-whattodo fr-1">
                        <label for="chk_enable_coupon_fee">
                            <?php 
                            /* translators: %d: 100 as number of percentage discount */
                            echo sprintf( esc_html__('Remove fees once a %d%% discount applies.', 'woocommerce-conditional-product-fees-for-checkout'), 100 ); 
                            ?>
                            <?php 
                            /* translators: %d: 100 as number of percentage discount */
                            echo wp_kses( wc_help_tip( sprintf( esc_html__( 'When this option is enabled, the fee will be removed once a %d%% discount applies to the cart.', 'woocommerce-conditional-product-fees-for-checkout' ), 100 ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="chk_enable_coupon_fee" id="chk_enable_coupon_fee" value="">
                    </td>
                </tr>
                <tr valign="top" id="enable_custom_fun">
                    <th class="table-whattodo fr-1">
                        <label for="chk_enable_custom_fun">
                            <?php esc_html_e( 'Display all fees in one label', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                            <?php echo wp_kses( wc_help_tip( esc_html__( 'When this option is enabled, all fees will be combined into a single fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="chk_enable_custom_fun" id="chk_enable_custom_fun" value="">
                    </td>
                </tr>
                <tr valign="top" id="enable_all_fee_tax" class="wcpffc_merged_fee_settings">
                    <th class="table-whattodo fr-1">
                        <label for="chk_enable_all_fee_tax">
                            <?php esc_html_e( 'Merge all fee with taxable', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                            <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, it will make this one merged fee calculated as taxable.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="chk_enable_all_fee_tax" id="chk_enable_all_fee_tax" value="">
                    </td>
                </tr>
                <tr valign="top" id="enable_all_fee_tooltip" class="wcpffc_merged_fee_settings">
                    <th class="table-whattodo fr-1">
                        <label for="chk_enable_all_fee_tooltip">
                            <?php esc_html_e( 'Merge all fee tooltip', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                            <?php echo wp_kses( wc_help_tip( esc_html__( 'Enable this if you want to add a tooltip to the merged fee label.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="chk_enable_all_fee_tooltip" id="chk_enable_all_fee_tooltip" value="">
                    </td>
                </tr>
                <tr valign="top" id="enable_all_fee_tooltip_text" class="wcpffc_merged_fee_settings">
                    <th class="table-whattodo fr-1">
                        <label for="chk_enable_all_fee_tooltip_text">
                            <?php esc_html_e( 'Merge all fee tooltip text', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                            <?php echo wp_kses( wc_help_tip( esc_html__( 'Add your own tooltip text that will apply to the merged fee label.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>        
                        </label>
                    </th>
                    <td>
                        <input type="text" name="chk_enable_all_fee_tooltip_text" id="chk_enable_all_fee_tooltip_text" value="" />
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <span class="button-primary" id="save_master_settings" name="save_master_settings">
                            <?php esc_html_e( 'Save Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                        </span>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
?>
<div class="wcpfc-section-left">
    <?php
    if ( wcpffc_fs()->is__premium_only() ) {
        if ( wcpffc_fs()->can_use_premium_code() ) {
            $allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
            ?>
            <div class="wcpfc-mastersettings wcpfc-table-tooltip">
                <div class="mastersettings-title">
                    <h2><?php esc_html_e( 'Global Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
                </div>
                <?php
                $chk_enable_coupon_fee           = get_option( 'chk_enable_coupon_fee' );
                $chk_enable_custom_fun           = get_option( 'chk_enable_custom_fun' );
                $chk_enable_all_fee_tax          = get_option( 'chk_enable_all_fee_tax' );
                $merge_fee_settings_taxable_type = get_option( 'merge_fee_settings_taxable_type' );
                $chk_enable_all_fee_tooltip      = get_option( 'chk_enable_all_fee_tooltip' );
                $chk_enable_all_fee_tooltip_text = get_option( 'chk_enable_all_fee_tooltip_text' );
                $chk_move_menu_under_wc          = get_option( 'chk_move_menu_under_wc' );

                $chk_enable_coupon_fee_checked   = ( ( ! empty( $chk_enable_coupon_fee ) && 'on' === $chk_enable_coupon_fee ) ) ? 'checked' : '';
                $chk_enable_custom_fun_checked   = ( ( ! empty( $chk_enable_custom_fun ) && 'on' === $chk_enable_custom_fun ) ) ? 'checked' : '';
                $chk_enable_all_fee_tax          = ( ( ! empty( $chk_enable_all_fee_tax ) && 'on' === $chk_enable_all_fee_tax ) ) ? 'checked' : '';
                $merge_fee_settings_taxable_type = ( ! empty( $merge_fee_settings_taxable_type ) ) ? $merge_fee_settings_taxable_type : 'standard';
                $chk_enable_all_fee_tooltip      = ( ( ! empty( $chk_enable_all_fee_tooltip ) && 'on' === $chk_enable_all_fee_tooltip ) ) ? 'checked' : '';
                $chk_enable_all_fee_tooltip_text = ( ! empty( $chk_enable_all_fee_tooltip_text ) ) ? $chk_enable_all_fee_tooltip_text : '';
                $chk_move_menu_under_wc_checked  = ( ( ! empty( $chk_move_menu_under_wc ) && 'on' === $chk_move_menu_under_wc ) ) ? 'checked' : '';
                ?>
                <table class="table-mastersettings form-table table-outer" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr valign="top" id="enable_coupon_fee">
                            <th class="table-whattodo fr-1">
                                <label for="chk_move_menu_under_wc">
                                    <?php esc_html_e( 'Move main menu under WooCommerce > Extra Fees', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, the main menu of the plugin will be moved to the "WooCommerce > Extra Fees" section.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="chk_move_menu_under_wc" id="chk_move_menu_under_wc" value="on" <?php echo esc_attr( $chk_move_menu_under_wc_checked ); ?>>
                            </td>
                        </tr>
                        <tr valign="top" id="enable_coupon_fee">
                            <th class="table-whattodo fr-1">
                                <label for="chk_enable_coupon_fee">
                                    <?php /* translators: %d: 100 as number of percentage discount */
                                    echo sprintf( esc_html__('Remove fees once a %d%% discount applies.', 'woocommerce-conditional-product-fees-for-checkout'), 100 ); ?>
                                    <?php /* translators: %d: 100 as number of percentage discount */
                                    echo wp_kses( wc_help_tip( sprintf( esc_html__( 'When this option is enabled, the fee will be removed once a %d%% discount applies to the cart.', 'woocommerce-conditional-product-fees-for-checkout' ), 100 ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="chk_enable_coupon_fee" id="chk_enable_coupon_fee" value="on" <?php echo esc_attr( $chk_enable_coupon_fee_checked ); ?>>
                            </td>
                        </tr>
                        <tr valign="top" id="enable_custom_fun">
                            <th class="table-whattodo fr-1">
                                <label for="chk_enable_custom_fun">
                                    <?php esc_html_e( 'Display all fees in one label', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'When this option is enabled, all fees will be combined into a single fee.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="chk_enable_custom_fun" id="chk_enable_custom_fun" value="on" <?php echo esc_attr( $chk_enable_custom_fun_checked ); ?>>
                            </td>
                        </tr>
                        <tr valign="top" id="enable_all_fee_tax" class="wcpffc_merged_fee_settings">
                            <th class="table-whattodo fr-1">
                                <label for="chk_enable_all_fee_tax">
                                    <?php esc_html_e( 'Merge all fee with taxable', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'If enabled, it will make this one merged fee calculated as taxable.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="chk_enable_all_fee_tax" id="chk_enable_all_fee_tax" value="on" <?php echo esc_attr( $chk_enable_all_fee_tax ); ?>>
                            </td>
                        </tr>
                        <tr valign="top" id="enable_merge_fee_tax" class="wcpffc_merged_fee_settings">
                            <th class="titledesc" scope="row">
                                <label for="merge_fee_settings_taxable_type">
                                    <?php esc_html_e( 'Tax Class', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Select the Tax Class.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
                            </th>
                            <td class="forminp">
                                <select name="merge_fee_settings_taxable_type" id="merge_fee_settings_taxable_type"><?php
                                    // Get all tax classes including the default 'Standard' tax class
                                    $tax_classes = WC_Tax::get_tax_classes();
                                    // Prepend 'Standard' as the default tax class
                                    array_unshift( $tax_classes, 'Standard' );
                                    // Loop through each tax class and create an option
                                    foreach ( $tax_classes as $tax_class ) {
                                        // If it's the default tax class, set the label to 'Standard'
                                        $tax_class_label = ( 'Standard' === $tax_class ) ? __( 'Standard', 'woocommerce-conditional-product-fees-for-checkout' ) : $tax_class;?>
                                        <option value="<?php echo esc_attr( sanitize_title( $tax_class ) ) ?>" <?php echo selected( $merge_fee_settings_taxable_type, sanitize_title( $tax_class ), false ) ?>><?php echo esc_html( $tax_class_label ) ?></option><?php 
                                    } ?>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top" id="enable_all_fee_tooltip" class="wcpffc_merged_fee_settings">
                            <th class="table-whattodo fr-1">
                                <label for="chk_enable_all_fee_tooltip">
                                    <?php esc_html_e( 'Merge all fee tooltip', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Enable this if you want to add a tooltip to the merged fee label.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="chk_enable_all_fee_tooltip" id="chk_enable_all_fee_tooltip" value="on" <?php echo esc_attr( $chk_enable_all_fee_tooltip ); ?>>
                            </td>
                        </tr>
                        <tr valign="top" id="enable_all_fee_tooltip_text" class="wcpffc_merged_fee_settings">
                            <th class="table-whattodo fr-1">
                                <label for="chk_enable_all_fee_tooltip_text">
                                    <?php esc_html_e( 'Merge all fee tooltip text', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                    <?php echo wp_kses( wc_help_tip( esc_html__( 'Add your own tooltip text that will apply to the merged fee label.', 'woocommerce-conditional-product-fees-for-checkout' ) ), array( 'span' => $allowed_tooltip_html ) ); ?>        
                                </label>
                            </th>
                            <td>
                                <input type="text" name="chk_enable_all_fee_tooltip_text" id="chk_enable_all_fee_tooltip_text" value="<?php echo esc_attr( $chk_enable_all_fee_tooltip_text ); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">
                                <span class="button-primary" id="save_master_settings" name="save_master_settings">
                                    <?php esc_html_e( 'Save Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?>
                                </span>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            wcpfc_free_global_settings_content();
        }
    } else {
        wcpfc_free_global_settings_content();
    }
    ?>
</div>
</div>
</div>
</div>
</div>
