<?php
/**
 * Image Editor.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.2.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Image_Editor class.
 *
 * @since 1.2.0
 */
class Boldermail_Image_Editor {

	/**
	 * Add play button watermark.
	 *
	 * @since  1.2.0
	 * @param  string $url Destination image URL.
	 * @return string
	 */
	public static function add_video_watermark( $url ) {
		return self::add_watermark( $url, BOLDERMAIL_PLUGIN_DIR . 'assets/images/youtube-play-button.png' );
	}

	/**
	 * Use PHP GD library to add the watermark.
	 *
	 * @since  1.2.0
	 * @param  string $dst_path Destination image.
	 * @param  string $src_path Source (watermark) image.
	 * @return string
	 */
	public static function add_watermark( $dst_path, $src_path ) {

		if ( extension_loaded( 'gd' ) && function_exists( 'imagecreatefromjpeg' ) ) {

			try {
				$dst_image = @imagecreatefromjpeg( $dst_path ); /* phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged */
				$src_image = @imagecreatefrompng( $src_path ); /* phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged */

				if ( $dst_image && $src_image ) {

					ob_start();

					$src_image = imagescale( $src_image, imagesx( $dst_image ) / 6 ); // <!-- Controls size of button.

					// Dimensions.
					$dst_image_w = imagesx( $dst_image );
					$dst_image_h = imagesy( $dst_image );
					$src_image_w = imagesx( $src_image );
					$src_image_h = imagesy( $src_image );

					// Merge images.
					imagealphablending( $src_image, true );
					imagesavealpha( $src_image, true );

					imagecopy(
						$dst_image,
						$src_image,
						( $dst_image_w - $src_image_w ) / 2,
						( $dst_image_h - $src_image_h ) / 2,
						0,
						0,
						$src_image_w,
						$src_image_h
					);

					// Output the image.
					imagejpeg( $dst_image );

					// @see https://stackoverflow.com/a/19486527
					// using the data directly does not work with apply_filters( 'the_content' )
					$dst_path = 'data:image/jpeg;base64,' . base64_encode( ob_get_clean() ); /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode */

					// Free up memory.
					imagedestroy( $dst_image );
					imagedestroy( $src_image );

					return $dst_path;

				}

			} catch ( Exception $e ) {
				return '';
			}

		}

		return '';

	}

	/**
	 * Insert base64 encoded image into the database.
	 *
	 * @see    https://codex.wordpress.org/Function_Reference/wp_handle_sideload
	 * @since  1.2.0
	 * @param  string $image_base64 Base64 image.
	 * @param  string $filename     Filename.
	 * @return int|bool             Attachment ID on success, false on failure.
	 */
	public static function insert_base64_attachment( $image_base64, $filename ) {

		$upload_dir  = wp_upload_dir();
		$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

		$image_decoded = base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $image_base64 ) ); /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode */

		$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

		// Create file.
		$image_upload = file_put_contents( $upload_path . $hashed_filename, $image_decoded ); /* phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents */

		// If failed to create file, return.
		if ( ! $image_upload ) {
			return false;
		}

		if ( ! function_exists( 'wp_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! function_exists( 'wp_crop_image' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		// Array based on $_FILE as seen in PHP file uploads.
		$file = array(
			'name'     => $hashed_filename,
			'type'     => wp_check_filetype( basename( $filename ), null ),
			'tmp_name' => $upload_path . $hashed_filename,
			'error'    => 0,
			'size'     => filesize( $upload_path . $hashed_filename ),
		);

		$overrides = array(
			// Tells WordPress to not look for the POST form fields that would normally
			// be present as we downloaded the file from a remote server, so there
			// will be no form fields.
			// Default is true.
			'test_form' => false,

			// Setting this to false lets WordPress allow empty files, not recommended.
			// Default is true.
			'test_size' => true,
		);

		// Upload file to server.
		$file_return = wp_handle_sideload( $file, $overrides );

		if ( ! empty( $file_return['error'] ) ) {
			return false;
		}

		$filename = $file_return['file'];

		$parent_post_id = get_the_ID();

		$attachment = array(
			'post_mime_type' => $file_return['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_dir['url'] . '/' . basename( $filename ),
		);

		$attachment_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		wp_update_attachment_metadata( $attachment_id, $attachment_data );

		return $attachment_id;

	}

}
