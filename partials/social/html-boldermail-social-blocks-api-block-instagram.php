<?php
/**
 * Instagram media display.
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
$media_data = $args['media_data'];

if ( ! $media_data ) {
	return;
}

$caption       = $media_data['caption'];
$media_type    = $media_data['media_type'];
$media_url     = $media_data['media_url'];
$permalink     = $media_data['permalink'];
$permalink     = $media_data['permalink'];
$thumbnail_url = isset( $media_data['thumbnail_url'] ) ? $media_data['thumbnail_url'] : '';
$timestamp     = $media_data['timestamp'];
$username      = $media_data['username'];

global $post;
$profile_image_url = $post ? get_avatar_url( get_post_field( 'post_author', $post->ID ) ) : '';

$profile_url = "https://www.instagram.com/{$username}";

?>
<table border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" class="bmEmbedContentInstagram" style="background: #ffffff; border: 1px solid #e1e8ed; border-radius: 4px; border-collapse: separate" width="100%">
	<tbody>
		<tr>
			<td>
				<!-- Media Header -->
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; padding: 10px;">
					<tbody>
						<tr>
							<td width="36">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; border-collapse: separate; border-radius: 100%">
									<tbody>
										<tr>
											<td align="center" valign="middle">
												<a href="<?php echo esc_url( $profile_url ); ?>" target="_blank" rel="noreferrer noopener">
													<img id="logoBlock-6" width="30" border="0" alt="<?php echo esc_attr( $username ); ?>" src="<?php echo esc_url( $profile_image_url ); ?>" style="display: block; border-radius: 100%">
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td width="8"></td>
							<td align="left" class="bmEmbedContentInstagram-profileDetails">
								<a href="<?php echo esc_attr( $profile_url ); ?>" target="_blank" rel="noreferrer noopener" class="profile-name" style="color: #292F33; font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; font-weight: bold; line-height: 18px; text-decoration: none;">
									<?php echo esc_html( $username ); ?>
								</a>
								<br>
							</td>
							<td align="right" valign="middle">
								<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; border-radius: 3px; background-color: #0095f6">
									<tbody>
										<tr>
											<td align="center" valign="middle" style="padding: 6px 12px">
												<a href="<?php echo esc_url( $profile_url ); ?>" target="_blank" rel="noreferrer noopener" class="bmEmbedContentInstagram-followButton" style="color: #fff; font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; font-weight: 600; line-height: 18px; text-decoration: none">
													<?php esc_html_e( 'View Profile', 'boldermail' ); ?>
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- Media Image -->
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<td class="bmEmbedContentInstagram-userContent" style="color: #1d2129; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px">
								<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener">
									<img id="logoBlock-6" width="100%" border="0" alt="<?php echo esc_attr( $caption ); ?>" src="<?php echo 'VIDEO' === $media_type ? esc_url( $thumbnail_url ) : esc_url( $media_url ); ?>" style="display: block">
								</a>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- Media Caption -->
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; padding: 0 12px;">
					<tbody>
						<tr>
							<td align="left" valign="middle" style="border-bottom: 1px solid #dbdbdb; padding: 12px 0">
								<a href="<?php echo esc_url( $profile_url ); ?>" target="_blank" rel="noreferrer noopener" style="text-decoration: none">
									<span style="color: #0095f6; font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; line-height: 18px; font-weight: 600;">
										<?php echo esc_html__( 'View More on Instagram', 'boldermail' ); ?>
									</span>
								</a>
							</td>
						</tr>
						<tr>
							<td align="left" valign="middle" style="padding-top: 8px">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; width: 100%">
									<tbody>
										<tr>
											<td align="left">
												<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse">
													<tbody>
														<tr>
															<td align="left" valign="middle" width="28">
																<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener" class="bmEmbedContentInstagram-likeButton">
																	<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/instagram/instagram-like.png" width="28" alt="<?php esc_attr__( 'View on Instagram', 'boldermail' ); ?>" style="display: block" border="0">
																</a>
															</td>
															<td width="8"></td>
															<td align="left" valign="middle" width="24">
																<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener" class="bmEmbedContentInstagram-commentButton">
																	<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/instagram/instagram-comment.png" width="24" alt="<?php esc_attr__( 'View on Instagram', 'boldermail' ); ?>" style="display: block" border="0">
																</a>
															</td>
															<td width="8"></td>
														</tr>
													</tbody>
												</table>
											</td>
											<td align="right" valign="middle" width="20">
												<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse">
													<tbody>
														<tr>
															<td align="right" valign="middle" width="28">
																<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener" class="bmEmbedContentInstagram-bookmarkButton">
																	<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/instagram/instagram-save.png" width="20" alt="<?php esc_attr__( 'View on Instagram', 'boldermail' ); ?>" style="display: block" border="0">
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
						<tr>
							<td align="left" valign="middle" style="padding: 12px 0 0; font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; line-height: 18px">
								<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener" style="color: #292F33; text-decoration: none">
									<span style="font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; font-weight: 600; line-height: 18px">
										<?php echo esc_html( $username ); ?>
									</span>
								</a>
							</td>
						</tr>
						<tr>
							<td align="left" valign="middle" style="color: #292F33; padding: 12px 0; font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; line-height: 18px">
								<?php echo boldermail_kses_post( make_clickable( nl2br( $caption ) ) ); ?>
							</td>
						</tr>
					</tbody>
				</table>
				<!-- Add a comment -->
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: separate; border-top: 1px solid #dbdbdb; padding: 0 12px;">
					<tbody>
						<tr>
							<td align="left" valign="middle" style="padding: 8px 0">
								<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener" style="text-decoration: none">
									<span style="color: rgb(142, 142, 142); font-size: 14px; font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif; line-height: 17px">
										<?php echo esc_html__( 'Add a Comment...', 'boldermail' ); ?>
									</span>
								</a>
							</td>
							<td width="8"></td>
							<td align="right" valign="middle">
								<a href="<?php echo esc_url( $permalink ); ?>" target="_blank" rel="noreferrer noopener" class="bmEmbedContentInstagram-followButton">
									<img src="https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/instagram/instagram-icon.png" width="22" alt="<?php esc_attr__( 'View on Instagram', 'boldermail' ); ?>" style="display: block" border="0">
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?php
