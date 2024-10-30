<?php
/**
 * "Feed" meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 *
 * @var        Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="feed_panel" class="panel boldermail-options-panel">
	<div class="options_group">
		<h3><?php esc_html_e( 'Define the sending schedule, and confirm the start of the campaign.', 'boldermail' ); ?></h3>

		<?php
		boldermail_wp_select(
			[
				'id'          => 'when_to_send',
				'label'       => __( 'Send', 'boldermail' ),
				'name'        => '_when_to_send',
				'value'       => $newsletter->get_rss_when_to_send(),
				'options'     => [
					'every-day'   => __( 'Every day', 'boldermail' ),
					'every-week'  => __( 'Every week', 'boldermail' ),
					'every-month' => __( 'Every month', 'boldermail' ),
				],
				'editable'    => $newsletter->is_editable(),
				'description' => __( 'We\'ll only send if there\'s new content.', 'boldermail' ),
			]
		);

		boldermail_wp_checkbox(
			[
				'id'                 => 'which_days',
				'label'              => __( 'Send only on these days', 'boldermail' ),
				'name'               => '_which_days[]',
				'class'              => 'boldermail-inline-checkboxes',
				'wrapper_attributes' => [
					'data-boldermail-show-if' => 'select#when_to_send option[value="every-day"]:checked',
				],
				'value'              => $newsletter->get_rss_which_days(),
				'cbvalue'            => [
					'MON' => __( 'Monday', 'boldermail' ),
					'TUE' => __( 'Tuesday', 'boldermail' ),
					'WED' => __( 'Wednesday', 'boldermail' ),
					'THU' => __( 'Thursday', 'boldermail' ),
					'FRI' => __( 'Friday', 'boldermail' ),
					'SAT' => __( 'Saturday', 'boldermail' ),
					'SUN' => __( 'Sunday', 'boldermail' ),
				],
				'editable'           => $newsletter->is_editable(),
			]
		);

		boldermail_wp_select(
			[
				'id'                 => 'what_day',
				'label'              => __( 'On what day?', 'boldermail' ),
				'name'               => '_what_day',
				'wrapper_attributes' => [
					'data-boldermail-show-if' => 'select#when_to_send option[value="every-week"]:checked',
				],
				'value'              => $newsletter->get_rss_what_day(),
				'options'            => [
					'MON' => __( 'Monday', 'boldermail' ),
					'TUE' => __( 'Tuesday', 'boldermail' ),
					'WED' => __( 'Wednesday', 'boldermail' ),
					'THU' => __( 'Thursday', 'boldermail' ),
					'FRI' => __( 'Friday', 'boldermail' ),
					'SAT' => __( 'Saturday', 'boldermail' ),
					'SUN' => __( 'Sunday', 'boldermail' ),
				],
				'editable'           => $newsletter->is_editable(),
			]
		);

		boldermail_wp_select(
			[
				'id'                 => 'which_date',
				'label'              => __( 'On which date?', 'boldermail' ),
				'name'               => '_which_date',
				'wrapper_attributes' => [
					'data-boldermail-show-if' => 'select#when_to_send option[value="every-month"]:checked',
				],
				'value'              => $newsletter->get_rss_which_date(),
				'options'            => [
					'1'  => __( '1st', 'boldermail' ),
					'2'  => __( '2nd', 'boldermail' ),
					'3'  => __( '3rd', 'boldermail' ),
					'4'  => __( '4th', 'boldermail' ),
					'5'  => __( '5th', 'boldermail' ),
					'6'  => __( '6th', 'boldermail' ),
					'7'  => __( '7th', 'boldermail' ),
					'8'  => __( '8th', 'boldermail' ),
					'9'  => __( '9th', 'boldermail' ),
					'10' => __( '10th', 'boldermail' ),
					'11' => __( '11th', 'boldermail' ),
					'12' => __( '12th', 'boldermail' ),
					'13' => __( '13th', 'boldermail' ),
					'14' => __( '14th', 'boldermail' ),
					'15' => __( '15th', 'boldermail' ),
					'16' => __( '16th', 'boldermail' ),
					'17' => __( '17th', 'boldermail' ),
					'18' => __( '18th', 'boldermail' ),
					'19' => __( '19th', 'boldermail' ),
					'20' => __( '20th', 'boldermail' ),
					'21' => __( '21st', 'boldermail' ),
					'22' => __( '22nd', 'boldermail' ),
					'23' => __( '23rd', 'boldermail' ),
					'24' => __( '24th', 'boldermail' ),
					'25' => __( '25th', 'boldermail' ),
					'26' => __( '26th', 'boldermail' ),
					'27' => __( '27th', 'boldermail' ),
					'28' => __( '28th', 'boldermail' ),
					'L'  => __( 'Last day of the month', 'boldermail' ),
					'29' => __( '29th (not available in all months)', 'boldermail' ),
					'30' => __( '30th (not available in all months)', 'boldermail' ),
					'31' => __( '31st (not available in all months)', 'boldermail' ),
				],
				'editable'           => $newsletter->is_editable(),
			]
		);

		boldermail_wp_select(
			[
				'id'          => 'what_time',
				'label'       => __( 'At what time?', 'boldermail' ),
				'name'        => '_what_time',
				'value'       => $newsletter->get_rss_what_time(),
				'options'     => [
					'0'  => __( '12:00 AM', 'boldermail' ),
					'1'  => __( '01:00 AM', 'boldermail' ),
					'2'  => __( '02:00 AM', 'boldermail' ),
					'3'  => __( '03:00 AM', 'boldermail' ),
					'4'  => __( '04:00 AM', 'boldermail' ),
					'5'  => __( '05:00 AM', 'boldermail' ),
					'6'  => __( '06:00 AM', 'boldermail' ),
					'7'  => __( '07:00 AM', 'boldermail' ),
					'8'  => __( '08:00 AM', 'boldermail' ),
					'9'  => __( '09:00 AM', 'boldermail' ),
					'10' => __( '10:00 AM', 'boldermail' ),
					'11' => __( '11:00 AM', 'boldermail' ),
					'12' => __( '12:00 PM', 'boldermail' ),
					'13' => __( '01:00 PM', 'boldermail' ),
					'14' => __( '02:00 PM', 'boldermail' ),
					'15' => __( '03:00 PM', 'boldermail' ),
					'16' => __( '04:00 PM', 'boldermail' ),
					'17' => __( '05:00 PM', 'boldermail' ),
					'18' => __( '06:00 PM', 'boldermail' ),
					'19' => __( '07:00 PM', 'boldermail' ),
					'20' => __( '08:00 PM', 'boldermail' ),
					'21' => __( '09:00 PM', 'boldermail' ),
					'22' => __( '10:00 PM', 'boldermail' ),
					'23' => __( '11:00 PM', 'boldermail' ),
				],
				'editable'    => $newsletter->is_editable(),
				/* translators: %s: Timezone location (e.g. America/Denver). */
				'description' => '' === get_option( 'timezone_string' ) ? __( 'The time is on the universal timezone.', 'boldermail' ) : sprintf( __( 'The time is on the %s timezone.', 'boldermail' ), get_option( 'timezone_string' ) ),
			]
		);
		?>
	</div>

	<div id="query_args" class="options_group">
		<h3><?php esc_html_e( 'Customize your RSS feed (optional).', 'boldermail' ); ?></h3>
		<?php
		boldermail_wp_select(
			[
				'id'          => 'post_type',
				'label'       => __( 'Post Type', 'boldermail' ),
				'name'        => '_post_type',
				'class'       => 'boldermail-select2 short',
				'value'       => $newsletter->get_rss_post_type(),
				'options'     => boldermail_get_post_types_labels( [ 'public' => true ] ),
				'editable'    => $newsletter->is_editable(),
				'description' => __( 'Select the post type for your feed.', 'boldermail' ),
			]
		);
		?>
	</div>
</div>
<?php
