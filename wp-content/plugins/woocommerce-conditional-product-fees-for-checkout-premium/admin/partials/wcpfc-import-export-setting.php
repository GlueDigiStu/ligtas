<?php
/**
 * Handles plugin import/export settings
 * 
 * @package Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @since   3.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );

// Function for free plugin content
function wcpfc_free_import_export_settings_content() {
	?>
	<div class="wcpfc-section-left">
		<div class="wcpfc-main-table res-cl wcpfc-import-export-section wcpfc-upgrade-pro-to-unlock">
			<h2><?php echo esc_html__( 'Import &amp; Export Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?><div class="wcpfc-pro-label"></div></h2>
			<table class="table-outer import-export-table">
				<tbody>
				<tr>
					<td><label for="blogname"><?php echo esc_html__( 'Export Settings Data', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
					</td>
					<td>
						<form method="post">
							<div class="wcpfc_main_container">
								<p class="wcpfc_button_container"><?php submit_button( __( 'Export', 'woocommerce-conditional-product-fees-for-checkout' ), 'primary', 'submit', false ); ?></p>
								<p class="wcpfc_content_container">
									<span><?php esc_html_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
								</p>
							</div>
						</form>

					</td>
				</tr>
				<tr>
					<td><label for="blogname"><?php echo esc_html__( 'Import Settings Data', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
					</td>
					<td>
						<form method="post" enctype="multipart/form-data">
							<div class="wcpfc_main_container">
								<p>
									<input type="file" name="import_file"/>
								</p>
								<p class="wcpfc_button_container">
									<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Import', 'woocommerce-conditional-product-fees-for-checkout' ); ?>">
								</p>
								<p class="wcpfc_content_container">
									<span><?php esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
								</p>
							</div>
						</form>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}

if ( wcpffc_fs()->is__premium_only() ) {
    if ( wcpffc_fs()->can_use_premium_code() ) {
    	$get_status = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$msg        = '';
		$style      = "display:none;";
		if ( 'success' === $get_status ) {
			$style = "display:block;";
			$msg   = esc_html__( 'Data has been imported successfully.', 'woocommerce-conditional-product-fees-for-checkout' );
		}
		?>
		<div id="message" class="notice notice-success is-dismissible" style="<?php echo esc_attr( $style ); ?>">
			<p><?php echo esc_html( $msg ); ?></p>
		</div>
		<div class="wcpfc-section-left">
			<div class="wcpfc-main-table res-cl wcpfc-import-export-section">
				<h2><?php echo esc_html__( 'Import &amp; Export Settings', 'woocommerce-conditional-product-fees-for-checkout' ); ?></h2>
				<table class="table-outer import-export-table">
					<tbody>
					<tr>
						<td><label for="blogname"><?php echo esc_html__( 'Export Settings Data', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
						</td>
						<td>
							<form method="post">
								<div class="wcpfc_main_container">
									<p class="wcpfc_button_container"><?php submit_button( __( 'Export', 'woocommerce-conditional-product-fees-for-checkout' ), 'primary', 'submit', false ); ?></p>
									<p class="wcpfc_content_container">
										<?php wp_nonce_field( 'wcpfc_export_save_action_nonce', 'wcpfc_export_action_nonce' ); ?>
										<input type="hidden" name="wcpfc_export_action" value="export_settings"/>
										<span><?php esc_html_e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
									</p>
								</div>
							</form>

						</td>
					</tr>
					<tr>
						<td><label for="blogname"><?php echo esc_html__( 'Import Settings Data', 'woocommerce-conditional-product-fees-for-checkout' ); ?></label>
						</td>
						<td>
							<form method="post" enctype="multipart/form-data">
								<div class="wcpfc_main_container">
									<p>
										<input type="file" name="import_file"/>
									</p>
									<p class="wcpfc_button_container">
										<input type="hidden" name="wcpfc_import_action" value="import_settings"/>
										<?php wp_nonce_field( 'wcpfc_import_action_nonce', 'wcpfc_import_action_nonce' ); ?>
										<?php
										$other_attributes = array( 'id' => 'wcpfc_import_setting' );
										?>
										<?php submit_button( __( 'Import', 'woocommerce-conditional-product-fees-for-checkout' ), 'primary', 'submit', false, $other_attributes ); ?>
									</p>
									<p class="wcpfc_content_container">
										<span><?php esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'woocommerce-conditional-product-fees-for-checkout' ); ?></span>
									</p>
								</div>
							</form>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
    } else {
    	wcpfc_free_import_export_settings_content();
    }
} else {
	wcpfc_free_import_export_settings_content();
}
?>
</div>
</div>
</div>
</div>
<?php
