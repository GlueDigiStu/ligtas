<?php
/**
 * Plugin Name: Ligtas Course Data (one-shot)
 * Description: Applies the course setup Ligtas confirmed in September 2026 - LMS course IDs, and how long each course's access lasts. Preview first, then apply. DELETE THIS PLUGIN once the data is in.
 * Version:     2.0.0
 * Author:      Glue Studio
 *
 * ONE-SHOT TOOL. Adds Tools > Ligtas Course Data. Nothing runs on its own -
 * it only does anything when you press Apply on that screen.
 *
 * Every value below was checked against Docebo itself on 2 September 2026 by
 * listing the courses and matching on their Ligtas admin code (L0004.1 etc).
 * Those codes are what Ligtas actually use; the numbers are what the API needs.
 *
 * TODO: deactivate and delete this plugin once the values have been applied
 * and checked. It has no other purpose.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ACF field keys, so ACF sees the values as its own. */
const LIGTAS_KEY_DAYS = 'field_68b6a1c4e3f10';
const LIGTAS_KEY_CODE = 'field_681e127d54f95';

/**
 * The LMS course each booking option should use.
 *
 * Keyed by variation ID. An empty 'code' means the link is removed, so the
 * booking no longer creates an LMS account at all.
 *
 * 'expect' is a safety check - if the variation with that ID is not the one
 * named here, the row is skipped rather than written to the wrong course.
 */
function ligtas_course_codes() {
	return array(
		// Ligtas confirmed the correct Working Safely is L0004.1, which Docebo
		// lists as course 409. The sheet's 232 is L0004, the older version.
		1418 => array( 'code' => '409', 'expect' => 'IOSH Working Safely', 'type' => 'Online',
			'why' => 'Already correct. L0004.1 is course 409 in Docebo.' ),

		// The site has been sending learners to 392, which Docebo lists under
		// the code "Resellers" - a different organisation's copy of the course.
		// Ligtas confirmed theirs is L0046, which is course 389.
		1342 => array( 'code' => '389', 'expect' => 'NEBOSH Certificate in Fire Safety', 'type' => 'Online',
			'why' => 'Was 392, which is the resellers copy of this course. L0046 is course 389.' ),

		// Never had a code. L0003.6 is course 408, which the virtual dates were
		// already using. Five learners were missed because of this.
		2192 => array( 'code' => '408', 'expect' => 'IOSH Managing Safely', 'type' => 'Online',
			'why' => 'Was blank. L0003.6 is course 408. Five learners were never enrolled.' ),

		// Never had a code. Sheet gives 310, and Docebo confirms L0019 is 310.
		2145 => array( 'code' => '310', 'expect' => 'NEBOSH International General Certificate', 'type' => 'Online',
			'why' => 'Was blank. L0019 is course 310.' ),

		// Ligtas do not have an e-learning NEBOSH construction course. The 123
		// on here is Working at Height Awareness (L0033), so it is wrong either way.
		1260 => array( 'code' => '', 'expect' => 'NEBOSH Health and Safety Management for Construction (UK)', 'type' => 'Online',
			'why' => 'There is no e-learning version of this course. 123 is Working at Height Awareness.' ),

		// Ligtas confirmed virtual bookings are enrolled by hand, so the site
		// should not do it.
		2172 => array( 'code' => '', 'expect' => 'IOSH Managing Safely', 'type' => 'Virtual',
			'why' => 'Virtual bookings are enrolled manually.' ),
		2173 => array( 'code' => '', 'expect' => 'IOSH Managing Safely', 'type' => 'Virtual',
			'why' => 'Virtual bookings are enrolled manually.' ),
		2178 => array( 'code' => '', 'expect' => 'IOSH Working Safely', 'type' => 'Virtual',
			'why' => 'Virtual bookings are enrolled manually.' ),
		2179 => array( 'code' => '', 'expect' => 'IOSH Working Safely', 'type' => 'Virtual',
			'why' => 'Virtual bookings are enrolled manually.' ),
	);
}

/**
 * How long a learner keeps LMS access, for the booking options that actually
 * enrol anyone. Keyed by variation ID.
 *
 * Conversions: 30 Days = 30, 3 Months = 90, 6 Months = 180, 12 Months = 365,
 * 5 Years = 1825.
 */
function ligtas_course_durations() {
	$d = array();

	$add = function ( $ids, $days, $expect, $sheet ) use ( &$d ) {
		foreach ( (array) $ids as $id ) {
			$d[ $id ] = array( 'days' => $days, 'expect' => $expect, 'sheet' => $sheet );
		}
	};

	$add( 1418, 90,   'IOSH Working Safely',                      '3 Months' );
	$add( 2192, 180,  'IOSH Managing Safely',                     '6 Months' );
	$add( 1342, 365,  'NEBOSH Certificate in Fire Safety',        '12 Months' );
	$add( 2145, 365,  'NEBOSH International General Certificate', '12 Months' );
	$add( 1318, 1825, 'NEBOSH Level 6 National Diploma',          '5 Years' );
	$add( 1293, 1825, 'NEBOSH Level 6 International Diploma',     '5 Years' );

	// Accident and Incident Investigation (L0061) is course 425, which is what
	// this product already uses. Confirmed against Docebo.
	$add( 1330, 30,   'NEBOSH HSE Introduction to Incident Investigation', '30 Days' );

	// Bronze, Silver and Gold, all 12 months.
	$add( array( 3665, 3664, 3662 ), 365, 'NEBOSH National General Certificate', '12 Months' );

	// The older draft copy of the same product, so it is not left half set up.
	$add( array( 1316, 1315, 1314 ), 365, 'NEBOSH National General Certificate', '12 Months (draft duplicate)' );

	return $d;
}

/** Things deliberately left alone, shown on screen so nothing looks forgotten. */
function ligtas_course_notes() {
	return array(
		array( 'Classroom bookings', 'Ligtas enrol these by hand. None of the five classroom dates has an LMS course and none is being added.' ),
		array( 'Nine online courses with no LMS course', 'Environmental Management Certificate, Construction International, HSE Award in Managing Risks, Leadership Excellence, Managing Stress, Process Safety, both Environmental Diplomas, and Working With Wellbeing. Ligtas confirmed these are delivered by a third party and never go on the LMS. Left blank on purpose.' ),
		array( 'Eleven awareness courses with no booking options', 'Asbestos, COSHH, Manual Handling, DSE, Fire Marshall, Electrical, Confined Space, Working at Heights, General H&S, CDM and Legionella have pages but cannot be bought here. Nothing to set until that changes.' ),
		array( 'NEBOSH Level 6 Diplomas', 'Both already carry three course IDs each. Checked against Docebo and all six are correct.' ),
		array( 'NEBOSH National General Certificate tiers', 'Bronze 307, Silver 352, Gold 351. All three confirmed correct against Docebo.' ),
		array( 'Two learners on the resellers Fire Safety course', 'Orders from 3 December 2025 and 23 January 2026 were enrolled on course 392. Changing the setting here does not move them - they need moving in Docebo.' ),
	);
}

add_action( 'admin_menu', function () {
	add_management_page(
		'Ligtas Course Data',
		'Ligtas Course Data',
		'manage_options',
		'ligtas-course-data',
		'ligtas_course_data_screen'
	);
} );

function ligtas_course_data_row( $id, $label, $field, $current, $new, $action, $note ) {
	return compact( 'id', 'label', 'field', 'current', 'new', 'action', 'note' );
}

/** Describe a variation the way a person would recognise it. */
function ligtas_course_data_label( $variation ) {
	$parent = get_post( $variation->post_parent );
	$label  = ( $parent ? $parent->post_title : 'Unknown product' );
	$type   = (string) get_post_meta( $variation->ID, 'attribute_type', true );
	$tier   = (string) get_post_meta( $variation->ID, 'tier', true );
	$start  = (string) get_post_meta( $variation->ID, 'attribute_start-date', true );

	if ( $type ) {
		$label .= ' — ' . $type;
	}

	if ( $tier && 'None' !== $tier ) {
		$label .= ', ' . $tier;
	}

	if ( $start && 'Any' !== $start ) {
		$label .= ', ' . $start;
	}

	if ( $parent && 'publish' !== $parent->post_status ) {
		$label .= ' (' . $parent->post_status . ')';
	}

	return $label;
}

/**
 * Check a variation is the one we think it is before writing to it.
 *
 * @return string Empty when it checks out, otherwise why it does not.
 */
function ligtas_course_data_check( $variation_id, $expect, $type = null ) {
	$variation = get_post( $variation_id );

	if ( ! $variation || 'product_variation' !== $variation->post_type ) {
		return 'No booking option with this ID on this site.';
	}

	$parent = get_post( $variation->post_parent );

	if ( ! $parent || false === stripos( $parent->post_title, $expect ) ) {
		return 'Expected "' . $expect . '". Not writing to the wrong course.';
	}

	if ( null !== $type && (string) get_post_meta( $variation_id, 'attribute_type', true ) !== $type ) {
		return 'Expected the ' . $type . ' option. Not writing to the wrong one.';
	}

	return '';
}

/** Work out every change, without making any of them. */
function ligtas_course_data_plan() {
	$rows = array();

	foreach ( ligtas_course_codes() as $variation_id => $spec ) {
		$problem = ligtas_course_data_check( $variation_id, $spec['expect'], $spec['type'] );
		$label   = ( $v = get_post( $variation_id ) ) && 'product_variation' === $v->post_type
			? ligtas_course_data_label( $v )
			: '—';

		if ( $problem ) {
			$rows[] = ligtas_course_data_row( $variation_id, $label, 'LMS course', '', $spec['code'], 'skip', $problem );
			continue;
		}

		$current = (string) get_post_meta( $variation_id, 'course_code', true );
		$action  = ( $current === $spec['code'] ) ? 'same' : ( '' === $spec['code'] ? 'clear' : 'set' );

		$rows[] = ligtas_course_data_row(
			$variation_id,
			$label,
			'LMS course',
			$current,
			$spec['code'],
			$action,
			'same' === $action ? 'Already correct. ' . $spec['why'] : $spec['why']
		);
	}

	foreach ( ligtas_course_durations() as $variation_id => $spec ) {
		$problem = ligtas_course_data_check( $variation_id, $spec['expect'] );
		$label   = ( $v = get_post( $variation_id ) ) && 'product_variation' === $v->post_type
			? ligtas_course_data_label( $v )
			: '—';

		if ( $problem ) {
			$rows[] = ligtas_course_data_row( $variation_id, $label, 'Access period', '', $spec['days'], 'skip', $problem );
			continue;
		}

		$current = (string) get_post_meta( $variation_id, 'enrolment_days', true );
		$action  = ( $current === (string) $spec['days'] ) ? 'same' : 'set';

		$rows[] = ligtas_course_data_row(
			$variation_id,
			$label,
			'Access period',
			$current,
			$spec['days'],
			$action,
			'same' === $action ? 'Already set to this.' : 'Sheet says ' . $spec['sheet'] . '.'
		);
	}

	return $rows;
}

/** Write the planned changes. */
function ligtas_course_data_apply() {
	$rows = ligtas_course_data_plan();

	foreach ( $rows as &$row ) {
		if ( 'set' !== $row['action'] && 'clear' !== $row['action'] ) {
			continue;
		}

		if ( 'Access period' === $row['field'] ) {
			update_post_meta( $row['id'], 'enrolment_days', (string) $row['new'] );
			update_post_meta( $row['id'], '_enrolment_days', LIGTAS_KEY_DAYS );
		} else {
			update_post_meta( $row['id'], 'course_code', (string) $row['new'] );
			update_post_meta( $row['id'], '_course_code', LIGTAS_KEY_CODE );
		}

		error_log( sprintf(
			'LIGTAS COURSE DATA: %s on #%d %s (%s)',
			$row['field'],
			$row['id'],
			'' === (string) $row['new'] ? 'cleared' : 'set to ' . $row['new'],
			$row['label']
		) );

		$row['action'] = ( 'clear' === $row['action'] ) ? 'cleared' : 'done';
	}

	unset( $row );

	foreach ( array_unique( wp_list_pluck( $rows, 'id' ) ) as $id ) {
		$parent = wp_get_post_parent_id( $id );

		if ( $parent ) {
			wc_delete_product_transients( $parent );
		}
	}

	return $rows;
}

function ligtas_course_data_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have permission to use this screen.' );
	}

	$applied = false;

	if ( isset( $_POST['ligtas_apply'] ) ) {
		check_admin_referer( 'ligtas_course_data' );
		$rows    = ligtas_course_data_apply();
		$applied = true;
	} else {
		$rows = ligtas_course_data_plan();
	}

	$counts  = array_count_values( wp_list_pluck( $rows, 'action' ) );
	$pending = ( $counts['set'] ?? 0 ) + ( $counts['clear'] ?? 0 );

	echo '<div class="wrap"><h1>Ligtas Course Data</h1>';

	echo '<p style="max-width:60em">Applies the course setup Ligtas confirmed in September 2026. Every course number here was checked against Docebo itself, by matching on the Ligtas admin codes (L0004.1 and so on).</p>';

	if ( $applied ) {
		printf(
			'<div class="notice notice-success"><p><strong>Done.</strong> %d value%s written.</p></div>',
			(int) $pending,
			1 === $pending ? '' : 's'
		);
	} else {
		echo '<div class="notice notice-info"><p>Nothing has been changed yet. This is what pressing Apply would do.</p></div>';
	}

	echo '<form method="post">';
	wp_nonce_field( 'ligtas_course_data' );

	echo '<table class="widefat striped"><thead><tr>';
	echo '<th>ID</th><th>Booking option</th><th>Setting</th><th>Now</th><th>New</th><th>What happens</th><th>Why</th>';
	echo '</tr></thead><tbody>';

	$words = array(
		'set'     => 'Will be set',
		'done'    => 'Set',
		'clear'   => 'Will be removed',
		'cleared' => 'Removed',
		'same'    => 'No change needed',
		'skip'    => 'Skipped',
	);

	$colours = array(
		'set'     => '#0a7c2f',
		'done'    => '#0a7c2f',
		'clear'   => '#b26200',
		'cleared' => '#b26200',
		'same'    => '#646970',
		'skip'    => '#b32d2e',
	);

	foreach ( $rows as $row ) {
		printf(
			'<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td><strong>%s</strong></td><td style="color:%s">%s</td><td>%s</td></tr>',
			(int) $row['id'],
			esc_html( $row['label'] ),
			esc_html( $row['field'] ),
			esc_html( '' === (string) $row['current'] ? '—' : $row['current'] ),
			esc_html( '' === (string) $row['new'] ? 'none' : $row['new'] ),
			esc_attr( $colours[ $row['action'] ] ?? '#646970' ),
			esc_html( $words[ $row['action'] ] ?? $row['action'] ),
			esc_html( $row['note'] )
		);
	}

	echo '</tbody></table>';

	if ( ! $applied ) {
		printf(
			'<p><button type="submit" name="ligtas_apply" value="1" class="button button-primary">Apply %d change%s</button></p>',
			(int) $pending,
			1 === $pending ? '' : 's'
		);
	} else {
		echo '<p><a href="' . esc_url( admin_url( 'plugins.php' ) ) . '" class="button">Go and delete this plugin</a></p>';
	}

	echo '</form>';

	echo '<h2>Left alone on purpose</h2>';
	echo '<table class="widefat striped"><thead><tr><th style="width:28%">What</th><th>Why</th></tr></thead><tbody>';

	foreach ( ligtas_course_notes() as $note ) {
		printf( '<tr><td><strong>%s</strong></td><td>%s</td></tr>', esc_html( $note[0] ), esc_html( $note[1] ) );
	}

	echo '</tbody></table></div>';
}
