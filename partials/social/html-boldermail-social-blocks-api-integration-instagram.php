<?php
/**
 * Instagram integration.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

define( 'BOLDERMAIL_REST_CONTROLLER_INSTAGRAM_CLIENT_ID', '1227867470941537' );
define( 'BOLDERMAIL_REST_CONTROLLER_INSTAGRAM_REDIRECT_URI', 'https://api.boldermail.com/wp-json/boldermail/v1/instagram/oauth/authorize' );

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- These aren't global variables.
$instagram_settings = boldermail_get_option( 'boldermail_instagram_integration' );

?>
<tr data-integration_id="instagram>">
	<td class="icon" width="48">
		<a href="https://www.instagram.com" target="_blank" rel="noreferrer noopener">
			<svg width="48" height="48" viewBox="0 0 24 24" version="1.1" role="img" aria-hidden="true" focusable="false" fill="rgb(226, 67, 97)">
				<g>
					<path d="M12 4.622c2.403 0 2.688.01 3.637.052.877.04 1.354.187 1.67.31.42.163.72.358 1.036.673.315.315.51.615.673 1.035.123.317.27.794.31 1.67.043.95.052 1.235.052 3.638s-.01 2.688-.052 3.637c-.04.877-.187 1.354-.31 1.67-.163.42-.358.72-.673 1.036-.315.315-.615.51-1.035.673-.317.123-.794.27-1.67.31-.95.043-1.234.052-3.638.052s-2.688-.01-3.637-.052c-.877-.04-1.354-.187-1.67-.31-.42-.163-.72-.358-1.036-.673-.315-.315-.51-.615-.673-1.035-.123-.317-.27-.794-.31-1.67-.043-.95-.052-1.235-.052-3.638s.01-2.688.052-3.637c.04-.877.187-1.354.31-1.67.163-.42.358-.72.673-1.036.315-.315.615-.51 1.035-.673.317-.123.794-.27 1.67-.31.95-.043 1.235-.052 3.638-.052M12 3c-2.444 0-2.75.01-3.71.054s-1.613.196-2.185.418c-.592.23-1.094.538-1.594 1.04-.5.5-.807 1-1.037 1.593-.223.572-.375 1.226-.42 2.184C3.01 9.25 3 9.555 3 12s.01 2.75.054 3.71.196 1.613.418 2.186c.23.592.538 1.094 1.038 1.594s1.002.808 1.594 1.038c.572.222 1.227.375 2.185.418.96.044 1.266.054 3.71.054s2.75-.01 3.71-.054 1.613-.196 2.186-.418c.592-.23 1.094-.538 1.594-1.038s.808-1.002 1.038-1.594c.222-.572.375-1.227.418-2.185.044-.96.054-1.266.054-3.71s-.01-2.75-.054-3.71-.196-1.613-.418-2.186c-.23-.592-.538-1.094-1.038-1.594s-1.002-.808-1.594-1.038c-.572-.222-1.227-.375-2.185-.418C14.75 3.01 14.445 3 12 3zm0 4.378c-2.552 0-4.622 2.07-4.622 4.622s2.07 4.622 4.622 4.622 4.622-2.07 4.622-4.622S14.552 7.378 12 7.378zM12 15c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm4.804-8.884c-.596 0-1.08.484-1.08 1.08s.484 1.08 1.08 1.08c.596 0 1.08-.484 1.08-1.08s-.483-1.08-1.08-1.08z" />
				</g>
			</svg>
		</a>
	</td>
	<td class="name">
		<span class="boldermail-integration-title">Instagram</span>
	</td>
	<td class="description">
		<?php echo boldermail_kses_post( __( 'Connect to your Instagram account and embed your posts in your Boldermail newsletter.<br><strong>This connection is read-only.</strong> Boldermail will not be able to post media or comments on your account.', 'boldermail' ) ); ?>
	</td>
	<td class="action" width="18%">
		<?php if ( ! empty( $instagram_settings['user_id'] ) ) : ?>
			<?php foreach ( $instagram_settings['user_id'] as $user_id ) : ?>
				<?php
				$instagram_api = new Boldermail_Instagram_API( $user_id, $instagram_settings['connected_accounts'][ $user_id ]['access_token'] );
				$user_profile  = $instagram_api->get_user();
				?>

				<?php if ( ! is_wp_error( $user_profile ) ) : ?>
					<a class="boldermail-integration-username" target="_blank" rel="noreferrer noopener" aria-label="<?php esc_attr__( 'The &quot;Instagram&quot; username', 'boldermail' ); ?>" href="<?php echo esc_url( $user_profile['profile_url'] ); ?>">
						<?php echo esc_html( $user_profile['username'] ); ?>
					</a>
				<?php else : ?>
					<span class="boldermail-integration-error"><?php esc_html_e( 'Error', 'boldermail' ); ?></span>
				<?php endif; ?>

				<?php
				$disconnect_url = add_query_arg(
					[
						'integration'           => 'instagram',
						'bm_integrations_nonce' => wp_create_nonce( 'instagram' ),
						'action'                => 'disconnect',
						'user_id'               => $user_id,
					],
					admin_url( 'edit.php?post_type=bm_newsletter&page=boldermail-settings&tab=integrations' )
				);
				?>
				<a class="button" aria-label="<?php esc_attr__( 'Manage the &quot;Instagram&quot; integration', 'boldermail' ); ?>" href="<?php echo esc_url( $disconnect_url ); ?>">
					<?php esc_html_e( 'Disconnect', 'boldermail' ); ?>
				</a>
			<?php endforeach; ?>
		<?php else : ?>
			<?php
			$connect_url = add_query_arg(
				[
					'client_id'     => BOLDERMAIL_REST_CONTROLLER_INSTAGRAM_CLIENT_ID,
					'redirect_uri'  => rawurlencode( BOLDERMAIL_REST_CONTROLLER_INSTAGRAM_REDIRECT_URI ),
					'scope'         => 'user_profile,user_media',
					'response_type' => 'code',
					'state'         => base64_encode( /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode */
						add_query_arg(
							[
								'integration'           => 'instagram',
								'bm_integrations_nonce' => wp_create_nonce( 'instagram' ),
								'action'                => 'connect',
							],
							admin_url( 'edit.php?post_type=bm_newsletter&page=boldermail-settings&tab=integrations' )
						)
					),
				],
				'https://api.instagram.com/oauth/authorize'
			);
			?>
		<a class="button" aria-label="<?php esc_attr__( 'Manage the &quot;Instagram&quot; integration', 'boldermail' ); ?>" href="<?php echo esc_url( $connect_url ); ?>">
			<?php esc_html_e( 'Connect', 'boldermail' ); ?>
		</a>
		<?php endif; ?>
	</td>
</tr>
