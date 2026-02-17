<?php
/**
 * Dotstore Tracking and Analytics for our plugins
 *
 * @version 4.3.0
 */

namespace DotStore\ConditionalExtraFees;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.thedotstore.com/
 * @package    Dotstore_Tracking_And_Analytics
 * @subpackage Dotstore_Tracking_And_Analytics/admin
 * @since      4.3.0
 * @author     support <support@thedotstore.com/>
 */
if( ! class_exists( 'Dotstore_Tracking_And_Analytics' ) ) {

    class Dotstore_Tracking_And_Analytics {

        /** 
         * The current instance of the plugin
         * 
         * @since   1.0.0
         * @access  protected
         * @var     \Dotstore_Tracking_And_Analytics single instance of this plugin 
         */
        protected static $instance;

        /**
         * The plugin prefix
         * 
         * @since 4.3.0
         * @access private
         * @var string
         */
        private $dsmrkt_prefix;

        /**
         * The plugin data
         * 
         * @since 4.3.0
         * @access private
         * @var array
         */
        private $dsmrkt_data;

        /**
         * Freemius API scope
         * 
         * @since 4.3.0
         * @access protected
         * @var string
         */
        protected static $fs__api_scope = 'developer';

        /**
         * Freemius API dev ID
         * 
         * @since 4.3.0
         * @access protected
         * @var int
         */
        protected static $fs__api_dev_id = 3811;

        /**
         * Freemius API public key
         * 
         * @since 4.3.0
         * @access protected
         * @var string
         */
        protected static $fs__api_public_key = 'pk_9edf804dccd14eabfd00ff503acaf';

        /**
         * Freemius API secret key
         * 
         * @since 4.3.0
         * @access protected
         * @var string
         */
        protected static $fs__api_secret_key = 'sk_utp+ZQJRJ#t[zcaFwYkHWDVvTaXsa';

        /**
         * Constructor for the module class.
         *
         * @since 4.3.0
         */
        public function __construct() {
            
            add_action( 'admin_enqueue_scripts', [ $this, 'dsmrkt_admin_enqueue_styles' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'dsmrkt_admin_enqueue_scripts' ] );

        }

        /**
         * Register AJAX hooks for the module
         * 
         * @since  4.3.0
         */
        public function register_ajax_hooks() {
            add_action( 'wp_ajax_dsmrkt_'.$this->dsmrkt_prefix.'_dotstore_event_tracking', [ $this, 'dsmrkt_dotstore_event_tracking__premium_only' ] );
        }

        /**
         * Set the module prefix
         * 
         * @since  4.3.0
         * 
         * @param  string $module_prefix
         * 
         * @return void
         */
        public function set_module_prefix( $module_prefix ) {
            $this->dsmrkt_prefix = $module_prefix;
        }

        /**
         * Set the module data
         * 
         * @since  4.3.0
         * 
         * @param  string $module_data
         * 
         * @return void
         */
        public function set_module_data( $module_data ) {
            $this->dsmrkt_data = defined($module_data) ? constant($module_data) : array();
        }

        /**
         * Enqueue admin styles
         * 
         * @since  4.3.0
         */
        public function dsmrkt_admin_enqueue_styles( $hook ) {

            if ( ! empty( $hook ) && ( false !== strpos( $hook, $this->dsmrkt_prefix ) ) && ( substr( $hook, -8 ) !== '-account' ) ) {

                wp_enqueue_style( 'dsmrkt-dotstore-analytics', plugin_dir_url( __FILE__ ) . 'assets/css/dotstore-analytics.css', array(), '' );
            }
        }

        /**
         * Enqueue admin scripts
         * 
         * @since  4.3.0
         */
        public function dsmrkt_admin_enqueue_scripts( $hook ) {
            
            if ( ! empty( $hook ) && ( false !== strpos( $hook, $this->dsmrkt_prefix ) ) && ( substr( $hook, -8 ) !== '-account' ) ) {
                // Load the Freemius 
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'dotstore-analytics/freemius-sdk/Freemius.php';
            
                wp_enqueue_script( 'dsmrkt-'.$this->dsmrkt_prefix.'-dotstore-analytics', plugin_dir_url( __FILE__ ) . 'assets/js/dotstore-analytics.js', array( 'jquery' ), '', true );

                if( class_exists( 'Freemius_Api' ) ) {

                    // Marketing Data
                    $dsmrkt_data = get_transient( $this->dsmrkt_prefix . '_dsmrkt_data' );

                    if( false === $dsmrkt_data ) {

                        // Marketing Data
                        $dsmrkt_data = array();

                        // Init SDK.
                        $api = new \Freemius_Api( self::$fs__api_scope, self::$fs__api_dev_id, self::$fs__api_public_key, self::$fs__api_secret_key );
                        
                        // For plugin's public_key
                        $plugin_data = $api->Api( "/dashboard.json" );

                        // if the response is not valid JSON, return the original string
                        $plugin_data = $this->dsmrkt_is_valid_json( $plugin_data ) ? json_decode( $plugin_data ) : $plugin_data;

                        if( !empty( $this->dsmrkt_data ) && !empty($plugin_data) && isset( $plugin_data->plugins ) && !empty( $plugin_data->plugins ) ) {

                            $plugins = array_values( array_filter($plugin_data->plugins, function ($plugin) {
                                return in_array( intval($plugin->id), array_keys( $this->dsmrkt_data ), true );
                            }) );
                            foreach( $plugins as $plugin ) {
                                $plugin_id = intval($plugin->id);
                                $dsmrkt_data[$plugin_id]['bearer_token'] = $plugin->bearer_token ? $plugin->bearer_token : '';
                                $dsmrkt_data[$plugin_id]['title'] = $plugin->title ? $plugin->title : '';
                            };
                        }
                        set_transient( $this->dsmrkt_prefix . '_dsmrkt_data' , $dsmrkt_data, 7 * 24 * HOUR_IN_SECONDS );
                    }

                    // Freemius localisation data
                    wp_localize_script( 'dsmrkt-'.$this->dsmrkt_prefix.'-dotstore-analytics', 'dsmrkt_data', array(
                            'dsmrkt_prefix' => $this->dsmrkt_prefix,
                            'ajaxurl' => admin_url( 'admin-ajax.php' ),
                            'dsmrkt_nonce' => wp_create_nonce( 'dsmrkt_nonce_string' ),
                            'dsmrkt_plugins' => $dsmrkt_data,
                            'dsmrkt_offer_text' => esc_html__( 'Get {{offer}} Off – Go Premium and Boost Your Experience', 'dotstore-tracking-and-analytics' ),
                        )
                    );
                }
            }
        }

        /**
         * Check if string is valid JSON
         * 
         * @since  4.3.0
         * 
         * @param  string $string
         * 
         * @return bool
         */
        public function dsmrkt_is_valid_json( $string ) {

            // Check if the string is empty
            if (!is_string($string)) {
                return false;
            }
        
            json_decode($string);

            return (json_last_error() === JSON_ERROR_NONE);
        }

        /**
         * Check if plugin is active
         * 
         * @since  4.3.0
         * 
         * @param  string $plugin_slug
         * 
         * @return bool
         */
        public function dsmrkt_is_marketing_plugin_activated__premium_only( $plugin_slug ) {

            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
                if ( is_plugin_active($plugin_slug) ) {
                    return true;
                } 
            }

            return false;
        }

        /**
         * Check if plugin is exist
         * 
         * @since  4.3.0
         * 
         * @param  string $plugin_slug
         * 
         * @return bool
         */
        public function dsmrkt_is_marketing_plugin_exist__premium_only( $plugin_slug ) {

            if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
                return true;
            }

            return false;
        }

        /**
         * Add marketing checkbox in plugin settings
         * 
         * @since  4.3.0
         * 
         * @param  int $plugin_id
         * 
         * @return void
         */
        public function dsmrkt_marketing_html__premium_only( $plugin_id ) {

            $marketing_data = !empty($this->dsmrkt_data) && isset( $this->dsmrkt_data[$plugin_id] ) && !empty( $this->dsmrkt_data[$plugin_id] ) ? $this->dsmrkt_data[$plugin_id] : array();
            $marketing_title = isset( $marketing_data['marketing_title'] ) ? sanitize_text_field( $marketing_data['marketing_title'] ) : '';
            $marketing_tooltip = isset( $marketing_data['marketing_tooltip'] ) ? sanitize_text_field ( $marketing_data['marketing_tooltip'] ) : '';
            $marketing_help_url = isset( $marketing_data['marketing_help_url'] ) ? esc_url( $marketing_data['marketing_help_url'] ) : '';
            $marketing_plugin_path = isset( $marketing_data['marketing_plugin_path'] ) ? $marketing_data['marketing_plugin_path'] : '';
            $marketing_coupon_code = isset( $marketing_data['marketing_coupon_code'] ) ? $marketing_data['marketing_coupon_code'] : 0;

            $is_plugin_activeted = $this->dsmrkt_is_marketing_plugin_activated__premium_only( $marketing_plugin_path );
            $is_plugin_exist = $this->dsmrkt_is_marketing_plugin_exist__premium_only( $marketing_plugin_path );

            if( !empty($marketing_data) ) {
                $allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
                $template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'dotstore-analytics/dots-marketing-popup.php';
                if (file_exists($template_path)) {
                    include $template_path;
                }
                ?>
                <tr valign="top" class="marketing_section">
                    <th class="titledesc" scope="row">
                        <label for="dsmrkt_checkbox">
                            <?php echo esc_html($marketing_title); ?>
                            <?php echo wp_kses( wc_help_tip( esc_html( $marketing_tooltip ) ), array( 'span' => $allowed_tooltip_html ) ); ?>
                            <img src="<?php echo esc_url( plugin_dir_url( __DIR__ ) . 'admin/images/premium-upgrade-img/pro-feature-icon.svg' ); ?>" class="premium_title_icon" alt="<?php esc_attr_e( 'Premium title icon', 'dotstore-tracking-and-analytics' ); ?>" />
                        </label>
                    </th>
                    <td class="forminp">
                        <label class="switch <?php echo $is_plugin_exist || $is_plugin_activeted ? 'disabled' : ''; ?>">
                            <input type="checkbox" name="dsmrkt_checkbox" value="on" class="<?php echo !$is_plugin_activeted ? 'dsmrkt_checkbox' : ''; ?>" data-plugin_id="<?php echo intval($plugin_id); ?>" data-coupon_id="<?php echo intval($marketing_coupon_code); ?>" <?php checked( true, $is_plugin_activeted, true ); ?> <?php disabled( true, $is_plugin_activeted, true ); ?> />
                            <div class="slider round"></div>
                        </label>

                        <?php if( $is_plugin_activeted ) { ?>
                            <a href="<?php echo esc_url( $marketing_help_url ); ?>" class="dsmrkt_help_docs" target="_blank"><?php esc_html_e( 'Configure plugin', 'dotstore-tracking-and-analytics' ); ?></a>
                        <?php } ?>

                        <?php if( $is_plugin_exist && ! $is_plugin_activeted ) { ?>
                            <a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="dsmrkt_activate_plugin" target="_blank"><?php esc_html_e( 'Activate premium plugin', 'dotstore-tracking-and-analytics' ); ?></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
        }

        /**
         * Dotstore event tracking
         * 
         * @since  4.3.0
         */
        public function dsmrkt_dotstore_event_tracking__premium_only() {

            if ( ! check_ajax_referer( 'dsmrkt_nonce_string', 'security' ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'dotstore-tracking-and-analytics' ) ) );
            }

            // Get the current user's email address
            // If the user is logged in, use their email; otherwise, use the admin email
            $email = wp_get_current_user() && isset( wp_get_current_user()->user_email ) && !empty( wp_get_current_user()->user_email ) ? wp_get_current_user()->user_email : get_option( 'admin_email' );

            // You can set this to whatever event you want to track
            $event_type = filter_input( INPUT_POST, 'event_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( empty( $event_type ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Event type is missing', 'dotstore-tracking-and-analytics' ) ) );
            }

            // Get the plugin ID from the request
            $plugin_id = filter_input( INPUT_POST, 'plugin_id', FILTER_SANITIZE_NUMBER_INT );

            if ( empty( $plugin_id ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Plugin ID is missing', 'dotstore-tracking-and-analytics' ) ) );
            }
            
            if ( ! isset( $this->dsmrkt_data[$plugin_id] ) || empty( $this->dsmrkt_data[$plugin_id] ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Invalid plugin ID', 'dotstore-tracking-and-analytics' ) ) );
            }
            // Get the marketing title from the plugin ID
            $marketing_title = !empty( $this->dsmrkt_data ) && isset( $this->dsmrkt_data[$plugin_id] ) && !empty( $this->dsmrkt_data[$plugin_id] ) ? $this->dsmrkt_data[$plugin_id] : array();
            $marketing_title = isset( $marketing_title['marketing_title'] ) ? sanitize_text_field( $marketing_title['marketing_title'] ) : '';

            $marketing_website = get_site_url();
            if ( empty( $marketing_website ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Marketing website is missing', 'dotstore-tracking-and-analytics' ) ) );
            }

            $marketing_plugin_title = filter_input( INPUT_POST, 'marketing_plugin_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( empty( $marketing_plugin_title ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Marketing plugin title is missing', 'dotstore-tracking-and-analytics' ) ) );
            }

            // Prepare the data to be sent
            $data = array(
                'email' => $email,
                'event_type' => $event_type,
                'marketing_title' => $marketing_title,
                'marketing_website' => $marketing_website,
                'marketing_plugin_title' => $marketing_plugin_title,
            );
            
            $response = wp_remote_post('https://pluginsdemo.thedotstore.com/wp-json/dotstore/v1/marketing-data?r=' . wp_rand(), array(
                'headers' => array(
                    'Dotstore-API-Key' => md5('sagar_jariwala'),
                    'Content-Type' => 'application/json'
                ),
                'body' => wp_json_encode( $data ),
            ));
            
            if ( is_wp_error( $response ) ) {
                wp_send_json_error( array( 'message' => esc_html__( 'Error occurred while sending data', 'dotstore-tracking-and-analytics' ) ) );
            } else {

                $response_body = wp_remote_retrieve_body( $response );
                $data = json_decode( $response_body, true );
                
                if ( isset( $data['status'] ) && $data['status'] === 'success' ) {
                    wp_send_json_success( array( 'message' => esc_html__( 'Event tracked successfully', 'dotstore-tracking-and-analytics' ) ) );
                } else {
                    $error_message = isset( $data['error_message'] ) ? $data['error_message'] : $data['message'];
                    wp_send_json_error( array( 'message' => esc_html( $error_message ), 'request_parameter'=> $data['data'] ) );
                }
            }
        }
    }
}