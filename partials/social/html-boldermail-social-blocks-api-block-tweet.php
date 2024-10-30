<?php
/**
 * Tweet display.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 *
 * @var        array $args Template arguments.
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- These aren't global variables.
$tweet_data = $args['tweet_data'];

if ( ! $tweet_data ) {
	return;
}

$tweet_id                = $tweet_data['data']['id'];
$tweet_author_name       = $tweet_data['includes']['users'][0]['name'];
$tweet_author_username   = $tweet_data['includes']['users'][0]['username'];
$tweet_author_url        = "https://www.twitter.com/{$tweet_author_username}";
$tweet_author_more       = "See {$tweet_author_username}'s other Tweets";
$tweet_profile_image_url = $tweet_data['includes']['users'][0]['profile_image_url'];
$tweet_author_verified   = $tweet_data['includes']['users'][0]['verified'];
$tweet_url               = "https://twitter.com/i/web/status/{{$tweet_id}";
$tweet_created_at        = $tweet_data['data']['created_at'];
$tweet_like_url          = "https://twitter.com/intent/like?tweet_id={$tweet_id}";
$tweet_like_count        = $tweet_data['data']['public_metrics']['like_count'];
$tweet_text              = $tweet_data['data']['text'];

try {
	$date = new DateTime( $tweet_created_at );

	$tweet_created_at_formatted = $date->format( 'H:i a - M j, Y' );
} catch ( Exception $e ) {
	$tweet_created_at_formatted = '';
}

?>
<table border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" class="bmEmbedContentTwitter" style="background: #ffffff; border: 1px solid #e1e8ed; border-radius: 4px; border-collapse: separate" width="100%">
	<tbody>
		<tr>
			<td style="padding: 18px">
				<!-- Tweet Header -->
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td width="36">
								<a href="<?php echo esc_url( $tweet_author_url ); ?>" target="_blank" rel="noreferrer noopener">
									<img width="36" border="0" alt="<?php echo esc_attr( $tweet_author_name ); ?>" src="<?php echo esc_url( $tweet_profile_image_url ); ?>" style="display: block;">
								</a>
							</td>
							<td width="8"></td>
							<td align="left" class="bmEmbedContentTwitter-profileDetails">
								<a href="<?php echo esc_attr( $tweet_author_url ); ?>" target="_blank" rel="noreferrer noopener" class="profile-name" style="color: #292F33; font-family: Helvetica, Arial, sans-serif; font-size: 15px; font-weight: bold; line-height: 20px; text-decoration: none;">
									<?php echo esc_html( $tweet_author_name ); ?>
								</a>
								<?php if ( $tweet_author_verified ) : ?>
									<span style="line-height: 20px;">
										<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/twitter/twitter-verified.png" width="16" style="display: inline-block;" alt="" border="0">
									</span>
								<?php endif; ?>
								<br>
								<a href="<?php echo esc_url( $tweet_author_url ); ?>" target="_blank" rel="noreferrer noopener" class="profile-screenname" style="color: #86999D; font-family: Helvetica, Arial, sans-serif; font-size: 15px; line-height: 20px; text-decoration: none;">
									<?php echo esc_html( "@{$tweet_author_name}" ); ?>
								</a>
							</td>
							<td align="right" valign="middle">
								<a href="<?php echo esc_url( $tweet_url ); ?>" target="_blank" rel="noreferrer noopener" class="bmEmbedContentTwitter-followButton">
									<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/twitter/twitter-icon.png" width="18" alt="<?php esc_attr__( 'View on Twitter', 'boldermail' ); ?>" style="display: block;" border="0">
								</a>
							</td>
						</tr>
						<tr>
							<td height="11" colspan="4"></td>
						</tr>
					</tbody>
				</table>
				<!-- Tweet Body -->
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td class="bmEmbedContentTwitter-userContent" style="color: #1d2129; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px">
								<p style="color: #292F33; font-family: Helvetica, Arial, sans-serif; font-size: 19px; font-weight: normal; line-height: 25px;">
									<?php echo wp_kses_post( make_clickable( $tweet_text ) ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<td height="11"></td>
						</tr>
						<tr>
							<td>
								<div style="color: #697882; font-size: 14px; font-family: Helvetica, Arial, sans-serif; line-height: 20px; padding-top: 4px;">
									<span style="color: #697882; text-decoration: none;"><?php echo esc_html( $tweet_created_at_formatted ); ?></span>
								</div>
							</td>
						</tr>
						<tr>
							<td align="left" style="padding-top: 20px;">
								<table border="0" cellpadding="0" cellspacing="0">
									<tbody>
									<tr>
										<td>
											<a href="<?php echo esc_url( $tweet_like_url ); ?>" target="_blank" rel="noreferrer noopener">
												<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/twitter/icon-heartEdge.png" width="20" height="20" style="display: block;" border="0">
											</a>
										</td>
										<td width="4"></td>
										<td style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; line-height: 16px;">
											<a href="<?php echo esc_url( $tweet_like_url ); ?>" target="_blank" rel="noreferrer noopener" style="color: #697882; text-decoration: none;">
												<?php echo esc_html( boldermail_round_to_nearest_thousandth( $tweet_like_count ) ); ?>
											</a>
										</td>
										<td width="20"></td>
										<td>
											<a href="<?php echo esc_url( $tweet_author_url ); ?>" target="_blank" rel="noreferrer noopener">
												<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/twitter/twitter-feed.png" width="13" height="16" style="display: block;" border="0">
											</a>
										</td>
										<td width="4"></td>
										<td style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; line-height: 16px;">
											<a href="<?php echo esc_url( $tweet_author_url ); ?>" target="_blank" rel="noreferrer noopener" style="color: #697882; text-decoration: none;">
												<?php echo esc_html( $tweet_author_more ); ?>
											</a>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php
