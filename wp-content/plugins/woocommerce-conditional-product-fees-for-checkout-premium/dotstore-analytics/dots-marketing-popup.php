<?php
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    die;
}

$marketing_data = !empty($this->dsmrkt_data) && isset( $this->dsmrkt_data[$plugin_id] ) && !empty( $this->dsmrkt_data[$plugin_id] ) ? $this->dsmrkt_data[$plugin_id] : array();
$marketing_coupon_code = isset( $marketing_data['marketing_coupon_code'] ) ? $marketing_data['marketing_coupon_code'] : 0;
$marketing_feature_list = isset( $marketing_data['marketing_feature_list'] ) ? $marketing_data['marketing_feature_list'] : array();
$marketing_button_text = isset( $marketing_data['marketing_button_text'] ) ? $marketing_data['marketing_button_text'] : esc_html__( 'Upgrade Now', 'woocommerce-conditional-product-fees-for-checkout' );
?>
<!-- Upgrade to pro plugin popup -->
<div class="marketing-modal-main" data-plugin_id="<?php echo esc_attr($plugin_id); ?>">
    <div class="marketing-modal-outer">
        <div class="pro-modal-inner">
            <div class="pro-modal-wrapper">
                <div class="pro-modal-header">
                    <img src="<?php // This SRC will replace from JS with dynamic URL from API ?>" alt="<?php echo esc_attr( $marketing_button_text ); ?>" class="pro-feature-img" />
                    <span class="dashicons dashicons-no-alt modal-close-btn"></span>
                </div>
                <div class="pro-modal-body">
                    <h3 class="pro-feature-title"><?php echo esc_html__( 'Unlock Premium Features Today!', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h3>
                    <p><!-- This will replce from JS with dynamic content from API --></p>
                    <?php if( !empty($marketing_feature_list) ) { ?>
                        <ul class="pro-feature-list">
                            <?php foreach( $marketing_feature_list as $feature ) { ?>
                                <li><?php echo esc_html( $feature ); ?></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="pro-modal-footer">
                    <a class="pro-feature-trial-btn get-now" target="_blank" data-plugin_id="<?php echo esc_attr($plugin_id); ?>" data-coupon_id="<?php echo esc_attr($marketing_coupon_code); ?>" href="javascript:void(0);"><?php echo esc_html( $marketing_button_text ); ?></a>
                    <div class="marketing-deal-slogan"><?php esc_html_e( 'Limited Time Only – Grab It Before It’s Gone!', 'woocommerce-conditional-product-fees-for-checkout' ); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
