<?php
/**
 * Countdown timer GIF generator.
 *
 * @link       https://www.boldermail.com/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

use GifCreator\AnimGif;

/**
 * Boldermail_Countdown_GIF class.
 *
 * @since 2.3.0
 */
class Boldermail_Countdown_GIF {

	/**
	 * The maximum number of frames in the GIF image.
	 *
	 * @since 2.3.0
	 * @var int
	 */
	const MAX_FRAMES = 60;

	/**
	 * The duration (in 1/100s) of each individual frames.
	 *
	 * @since 2.3.0
	 * @var int DELAY
	 */
	const DELAY = 100;

	/**
	 * The UNIX timestamp that indicates when the counter ends.
	 *
	 * @since 2.3.0
	 * @var DateTime $timestamp_dt
	 */
	public $timestamp_dt;

	/**
	 * Constructor.
	 *
	 * @since 2.3.0
	 * @param int $timestamp UNIX timestamp of when the countdown ends.
	 */
	public function __construct( $timestamp ) {

		try {
			$this->timestamp_dt = new DateTime( '@' . $timestamp, wp_timezone() );
		} catch ( Exception $e ) {
			exit;
		}

	}

	/**
	 * Get animated GIF image of the countdown timer.
	 *
	 * @see https://github.com/jephchristoff/EmailGIFCountdown/blob/master/gif.php
	 * @see https://litmus.com/community/learning/27-how-to-add-a-countdown-timer-to-your-email
	 * @see https://github.com/MarBie77/EmailCountdown/blob/master/src/DefaultCountdown.php
	 *
	 * @since 2.3.0
	 *
	 * @param array $params Customization options.
	 *
	 * @return mixed The resulting GIF binary data.
	 */
	public function get_animated_gif( $params = [] ) {

		if ( extension_loaded( 'gd' ) && function_exists( 'imagecreatefromjpeg' ) ) {

			try {
				$now_dt   = new DateTime( 'now', wp_timezone() );
				$timediff = max( $this->timestamp_dt->getTimestamp() - $now_dt->getTimestamp(), 0 );

				$countdown_gif = get_transient( "boldermail_countdown_gif_timediff_{$timediff}" );

				if ( $countdown_gif ) {
					return base64_decode( $countdown_gif ); /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode */
				}

				class_exists( '\GifCreator\AnimGif' ) || require_once BOLDERMAIL_PLUGIN_DIR . 'includes/plugins/AnimGif/autoload.php';

				$params = wp_parse_args(
					$params,
					[
						'image'            => BOLDERMAIL_PLUGIN_DIR . 'assets/images/countdown.png',
						'size'             => 83,
						'font'             => 'Futura.ttc',
						'x-offset'         => 20,
						'y-offset'         => 108.5,
						'color'            => [ 84, 86, 84 ],
						'background-color' => [ 252, 254, 252 ],
					]
				);

				$image = imagecreatefrompng( $params['image'] );

				imagealphablending( $image, true );
				imagesavealpha( $image, true );

				$font = [
					'file'             => BOLDERMAIL_PLUGIN_DIR . 'assets/fonts/' . $params['font'],
					'size'             => $params['size'],
					'angle'            => 0,
					'x-offset'         => $params['x-offset'],
					'y-offset'         => $params['y-offset'],
					'color'            => imagecolorallocate( $image, $params['color'][0], $params['color'][1], $params['color'][2] ),
					'background-color' => imagecolorallocate( $image, $params['background-color'][0], $params['background-color'][1], $params['background-color'][2] ),
				];

				$frames = [];
				$loops  = 0;

				for ( $i = 0; $i <= self::MAX_FRAMES; $i++ ) {

					if ( $this->timestamp_dt->getTimestamp() < $now_dt->getTimestamp() ) {
						// If this GIF image we are generating will reach zero, do not loop, and stop adding more frames.
						$frames[] = $this->get_frame( $params['image'], false, $font );
						$loops    = 1;
						break;
					} else {
						$interval = date_diff( $this->timestamp_dt, $now_dt );
						$frames[] = $this->get_frame( $params['image'], $interval, $font );
					}

					$now_dt->modify( '+1 second' );

				}

				$num_frames = count( $frames );

				if ( $num_frames > 1 ) {
					$countdown_gif = new AnimGif();
					$countdown_gif = $countdown_gif->create( $frames, self::DELAY, $loops )->get();
				} else {
					ob_start();
					imagegif( $frames[0] );
					$countdown_gif = ob_get_clean();
				}

				set_transient( "boldermail_countdown_gif_timediff_{$timediff}", base64_encode( $countdown_gif ), $num_frames - 1 ); /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode */
				return $countdown_gif;

			} catch ( Exception $e ) {
				return null;
			}

		}

		return null;

	}

	/**
	 * Get a GIF frame.
	 *
	 * @since 2.3.0
	 *
	 * @param string             $bg_img   Background image PNG path.
	 * @param DateInterval|false $interval Time difference.
	 * @param array              $font     Font data.
	 *
	 * @return resource Image resource.
	 */
	public function get_frame( $bg_img, $interval, $font ) {

		// Each frame needs to start by creating a new image because otherwise
		// the new numbers would draw on top of old ones.
		// Here, it doesn't really matter what the PNG is (other than for size)
		// because it's about to get filled with a new color.
		$image = imagecreatefrompng( $bg_img );

		$seconds = $interval ? sprintf( '%02d', $interval->s ) : '00';
		$minutes = $interval ? sprintf( '%02d', $interval->i ) : '00';
		$hours   = $interval ? sprintf( '%02d', $interval->h ) : '00';
		$days    = $interval ? sprintf( '%02d', $interval->days ) : '00';

		imagettftext( $image, $font['size'], $font['angle'], $font['x-offset'], $font['y-offset'], $font['color'], $font['file'], $days );
		imagettftext( $image, $font['size'], $font['angle'], $font['x-offset'] + 172, $font['y-offset'], $font['color'], $font['file'], $hours );
		imagettftext( $image, $font['size'], $font['angle'], $font['x-offset'] + 344, $font['y-offset'], $font['color'], $font['file'], $minutes );
		imagettftext( $image, $font['size'], $font['angle'], $font['x-offset'] + 516, $font['y-offset'], $font['color'], $font['file'], $seconds );

		return $image;

	}

}
