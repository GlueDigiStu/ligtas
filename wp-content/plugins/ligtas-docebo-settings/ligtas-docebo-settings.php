<?php
/**
 * Plugin Name:       Ligtas Docebo Settings
 * Plugin URI:        https://ligtas.co.uk
 * Description:       Stores the Docebo LMS API credentials in the database instead of hard-coding them in the theme. Adds a settings screen under Settings &rarr; Docebo API.
 * Version:           1.0.0
 * Author:            Station Rd
 * License:           GPL-2.0-or-later
 * Requires at least: 6.0
 * Requires PHP:      7.4
 *
 * Credentials can also be defined in wp-config.php, which takes precedence over
 * anything saved here and keeps the values out of the database entirely:
 *
 *     define( 'LIGTAS_DOCEBO_BASE_URL',      'https://ligtas.yourlms.net' );
 *     define( 'LIGTAS_DOCEBO_CLIENT_ID',     '...' );
 *     define( 'LIGTAS_DOCEBO_CLIENT_SECRET', '...' );
 *     define( 'LIGTAS_DOCEBO_USERNAME',      '...' );
 *     define( 'LIGTAS_DOCEBO_PASSWORD',      '...' );
 *
 * TODO: while none of the above are set, the theme's DoceboClass falls back to
 * the old hard-coded credentials in _legacy_setting(). Remove that method once
 * the details below have been entered and the connection tested.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LIGTAS_DOCEBO_OPTION', 'ligtas_docebo_settings' );

/**
 * The settings this plugin manages.
 *
 * 'constant' is the wp-config.php constant that overrides the stored value.
 * 'secret'   fields are never rendered back into the page.
 *
 * @return array
 */
function ligtas_docebo_fields() {
	return array(
		'base_url'      => array(
			'label'    => 'LMS base URL',
			'constant' => 'LIGTAS_DOCEBO_BASE_URL',
			'secret'   => false,
			'default'  => 'https://ligtas.yourlms.net',
			'help'     => 'The root address of the Docebo platform, with no trailing slash. For example <code>https://ligtas.yourlms.net</code>.',
		),
		'client_id'     => array(
			'label'    => 'Client ID',
			'constant' => 'LIGTAS_DOCEBO_CLIENT_ID',
			'secret'   => false,
			'default'  => '',
			'help'     => 'From the API and SSO app registered in Docebo.',
		),
		'client_secret' => array(
			'label'    => 'Client secret',
			'constant' => 'LIGTAS_DOCEBO_CLIENT_SECRET',
			'secret'   => true,
			'default'  => '',
			'help'     => 'Shown in Docebo only when the app is created. Leave blank to keep the saved value.',
		),
		'username'      => array(
			'label'    => 'API username',
			'constant' => 'LIGTAS_DOCEBO_USERNAME',
			'secret'   => false,
			'default'  => '',
			'help'     => 'The Docebo account the website signs in as. This should be a dedicated integration account, not a person&rsquo;s login.',
		),
		'password'      => array(
			'label'    => 'API password',
			'constant' => 'LIGTAS_DOCEBO_PASSWORD',
			'secret'   => true,
			'default'  => '',
			'help'     => 'The password for the account above. Leave blank to keep the saved value.',
		),
	);
}

/**
 * Read a single Docebo setting.
 *
 * A matching wp-config.php constant always wins over the stored value.
 *
 * @param  string $key One of the keys in ligtas_docebo_fields().
 * @return string
 */
function ligtas_docebo_get_setting( $key ) {
	$fields = ligtas_docebo_fields();

	if ( ! isset( $fields[ $key ] ) ) {
		return '';
	}

	if ( defined( $fields[ $key ]['constant'] ) ) {
		return (string) constant( $fields[ $key ]['constant'] );
	}

	$stored = get_option( LIGTAS_DOCEBO_OPTION, array() );
	$value  = isset( $stored[ $key ] ) ? (string) $stored[ $key ] : '';

	if ( '' === $value ) {
		$value = (string) $fields[ $key ]['default'];
	}

	return $value;
}

/**
 * Are all four credentials plus a base URL present?
 *
 * @return bool
 */
function ligtas_docebo_is_configured() {
	foreach ( array_keys( ligtas_docebo_fields() ) as $key ) {
		if ( '' === ligtas_docebo_get_setting( $key ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Is this value locked by a wp-config.php constant?
 *
 * @param  string $key
 * @return bool
 */
function ligtas_docebo_is_locked( $key ) {
	$fields = ligtas_docebo_fields();

	return isset( $fields[ $key ] ) && defined( $fields[ $key ]['constant'] );
}

/* -------------------------------------------------------------------------
 * Settings screen
 * ---------------------------------------------------------------------- */

add_action( 'admin_menu', 'ligtas_docebo_add_settings_page' );
function ligtas_docebo_add_settings_page() {
	add_options_page(
		'Docebo API',
		'Docebo API',
		'manage_options',
		'ligtas-docebo',
		'ligtas_docebo_render_settings_page'
	);
}

add_action( 'admin_init', 'ligtas_docebo_register_settings' );
function ligtas_docebo_register_settings() {
	register_setting(
		'ligtas_docebo',
		LIGTAS_DOCEBO_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ligtas_docebo_sanitise_settings',
			'default'           => array(),
			'show_in_rest'      => false,
		)
	);
}

/**
 * Validate the submitted settings.
 *
 * Blank secret fields mean "leave the saved value alone" rather than "clear it",
 * because the form never renders the existing secret back to the browser.
 *
 * @param  mixed $input
 * @return array
 */
function ligtas_docebo_sanitise_settings( $input ) {
	$existing = get_option( LIGTAS_DOCEBO_OPTION, array() );
	$existing = is_array( $existing ) ? $existing : array();
	$fields   = ligtas_docebo_fields();
	$clean    = array();

	foreach ( $fields as $key => $field ) {
		$submitted = isset( $input[ $key ] ) ? trim( (string) $input[ $key ] ) : '';
		$previous  = isset( $existing[ $key ] ) ? (string) $existing[ $key ] : '';

		if ( $field['secret'] && '' === $submitted ) {
			$clean[ $key ] = $previous;
			continue;
		}

		if ( 'base_url' === $key ) {
			$submitted = untrailingslashit( esc_url_raw( $submitted ) );
		} else {
			$submitted = sanitize_text_field( $submitted );
		}

		$clean[ $key ] = $submitted;
	}

	return $clean;
}

function ligtas_docebo_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$fields = ligtas_docebo_fields();
	?>
	<div class="wrap">
		<h1>Docebo API</h1>

		<p>
			The website uses these details to sign in to Docebo when it creates learner
			accounts and enrols them on courses after a purchase.
		</p>

		<?php if ( ligtas_docebo_is_configured() ) : ?>
			<div class="notice notice-success inline"><p><strong>All required details are set.</strong> Use Test connection below to confirm Docebo accepts them.</p></div>
		<?php else : ?>
			<div class="notice notice-warning inline"><p><strong>Some details are missing.</strong> Until all five are filled in, the website falls back to the old credentials built into the theme. Those still work, but they are due to be removed and the account behind them is going to be closed.</p></div>
		<?php endif; ?>

		<form action="options.php" method="post" autocomplete="off">
			<?php settings_fields( 'ligtas_docebo' ); ?>

			<table class="form-table" role="presentation">
				<tbody>
				<?php foreach ( $fields as $key => $field ) : ?>
					<?php
					$locked  = ligtas_docebo_is_locked( $key );
					$value   = ligtas_docebo_get_setting( $key );
					$is_set  = ( '' !== $value );
					$id      = 'ligtas_docebo_' . $key;
					$name    = LIGTAS_DOCEBO_OPTION . '[' . $key . ']';
					?>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
						</th>
						<td>
							<?php if ( $locked ) : ?>
								<input type="text" class="regular-text code" id="<?php echo esc_attr( $id ); ?>"
									value="<?php echo $field['secret'] ? '&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;' : esc_attr( $value ); ?>" disabled>
								<p class="description">
									Set in <code>wp-config.php</code> as <code><?php echo esc_html( $field['constant'] ); ?></code>,
									so it cannot be changed here.
								</p>
							<?php else : ?>
								<input
									type="<?php echo $field['secret'] ? 'password' : 'text'; ?>"
									class="regular-text<?php echo $field['secret'] ? '' : ' code'; ?>"
									id="<?php echo esc_attr( $id ); ?>"
									name="<?php echo esc_attr( $name ); ?>"
									value="<?php echo $field['secret'] ? '' : esc_attr( $value ); ?>"
									autocomplete="new-password"
									<?php echo $field['secret'] && $is_set ? 'placeholder="Saved &mdash; leave blank to keep"' : ''; ?>>
								<p class="description">
									<?php echo wp_kses_post( $field['help'] ); ?>
									<?php if ( $field['secret'] ) : ?>
										<br><strong><?php echo $is_set ? 'A value is currently saved.' : 'No value saved yet.'; ?></strong>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<?php submit_button( 'Save settings' ); ?>
		</form>

		<hr>

		<h2>Test connection</h2>
		<p>Asks Docebo for an access token using the details above. Nothing is created or changed on the LMS.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="ligtas_docebo_test">
			<?php wp_nonce_field( 'ligtas_docebo_test' ); ?>
			<?php submit_button( 'Test connection', 'secondary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Connection test
 * ---------------------------------------------------------------------- */

add_action( 'admin_post_ligtas_docebo_test', 'ligtas_docebo_handle_test' );
function ligtas_docebo_handle_test() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to do that.' );
	}

	check_admin_referer( 'ligtas_docebo_test' );

	$result = ligtas_docebo_test_connection();

	set_transient( 'ligtas_docebo_test_result', $result, MINUTE_IN_SECONDS );

	wp_safe_redirect( admin_url( 'options-general.php?page=ligtas-docebo' ) );
	exit;
}

/**
 * Request an OAuth2 token using the saved details.
 *
 * Deliberately self-contained so the screen can be used to check the
 * credentials even if the theme's Docebo class is unavailable.
 *
 * @return array{ok:bool,message:string}
 */
function ligtas_docebo_test_connection() {
	if ( ! ligtas_docebo_is_configured() ) {
		return array(
			'ok'      => false,
			'message' => 'Fill in all five fields before testing.',
		);
	}

	$response = wp_remote_post(
		ligtas_docebo_get_setting( 'base_url' ) . '/oauth2/token',
		array(
			'timeout' => 20,
			'body'    => array(
				'client_id'     => ligtas_docebo_get_setting( 'client_id' ),
				'client_secret' => ligtas_docebo_get_setting( 'client_secret' ),
				'grant_type'    => 'password',
				'scope'         => 'api',
				'username'      => ligtas_docebo_get_setting( 'username' ),
				'password'      => ligtas_docebo_get_setting( 'password' ),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return array(
			'ok'      => false,
			'message' => 'Could not reach Docebo: ' . $response->get_error_message(),
		);
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = json_decode( wp_remote_retrieve_body( $response ) );

	if ( 200 === $code && isset( $body->access_token ) ) {
		$expires = isset( $body->expires_in ) ? ' The token is valid for ' . human_time_diff( time(), time() + (int) $body->expires_in ) . '.' : '';

		return array(
			'ok'      => true,
			'message' => 'Connected to Docebo successfully.' . $expires,
		);
	}

	$detail = '';
	if ( isset( $body->error_description ) ) {
		$detail = ' Docebo said: ' . $body->error_description;
	} elseif ( isset( $body->message ) ) {
		$detail = ' Docebo said: ' . $body->message;
	}

	return array(
		'ok'      => false,
		'message' => 'Docebo rejected the request (HTTP ' . $code . ').' . $detail,
	);
}

add_action( 'admin_notices', 'ligtas_docebo_test_notice' );
function ligtas_docebo_test_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'settings_page_ligtas-docebo' !== $screen->id ) {
		return;
	}

	$result = get_transient( 'ligtas_docebo_test_result' );

	if ( ! $result ) {
		return;
	}

	delete_transient( 'ligtas_docebo_test_result' );

	printf(
		'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
		$result['ok'] ? 'success' : 'error',
		esc_html( $result['message'] )
	);
}

/* -------------------------------------------------------------------------
 * Housekeeping
 * ---------------------------------------------------------------------- */

/**
 * Warn on the plugins screen if the integration has no credentials to use.
 */
add_action( 'admin_notices', 'ligtas_docebo_unconfigured_notice' );
function ligtas_docebo_unconfigured_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'plugins' !== $screen->id || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ligtas_docebo_is_configured() ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>The Docebo integration has no credentials saved and is running on the old ones built into the theme. <a href="%s">Add the new details under Settings &rarr; Docebo API</a> before that fallback is removed.</p></div>',
		esc_url( admin_url( 'options-general.php?page=ligtas-docebo' ) )
	);
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ligtas_docebo_action_links' );
function ligtas_docebo_action_links( $links ) {
	array_unshift(
		$links,
		sprintf( '<a href="%s">Settings</a>', esc_url( admin_url( 'options-general.php?page=ligtas-docebo' ) ) )
	);

	return $links;
}

/**
 * Keep the credentials out of the autoloaded option cache.
 */
register_activation_hook( __FILE__, 'ligtas_docebo_on_activate' );
function ligtas_docebo_on_activate() {
	if ( false === get_option( LIGTAS_DOCEBO_OPTION ) ) {
		add_option( LIGTAS_DOCEBO_OPTION, array(), '', 'no' );
	}
}
