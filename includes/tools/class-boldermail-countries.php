<?php
/**
 * Country and codes tools.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Countries class.
 *
 * @since   1.7.0
 */
class Boldermail_Countries {

	/**
	 * Auto-load in-accessible properties on demand.
	 *
	 * @since   1.7.0
	 * @param   string  $key    Key.
	 * @return  string
	 */
	public function __get( $key ) {

		if ( 'countries' === $key ) {
			return $this->get_countries();
		}

	}

	/**
	 * Get all countries.
	 *
	 * @since   1.7.0
	 * @return  array
	 */
	public function get_countries() {

		if ( empty( $this->countries ) ) {
			$this->countries = apply_filters( 'boldermail_countries', BOLDERMAIL_PLUGIN_DIR . 'includes/i18n/countries.php' );
		}

		return $this->countries;

	}

	/**
	 * Convert country to 2-letter country code.
	 *
	 * @since   1.6.0
	 * @param   string  $country
	 * @return  string
	 */
	public function to_country_code( $country ) {

		return array_search( $country, $this->countries );

	}

	/**
	 * Convert 2-letter country code to country.
	 *
	 * @since   1.6.0
	 * @param   string  $country
	 * @return  string
	 */
	public function to_country( $country_code ) {

		return isset( $this->countries[ $country_code ] ) ? $this->countries[ $country_code ] : '';

	}

	/**
	 * Converts string of (one) country code to emoji flag (string).
	 * Makes correction for codes that have no corresponding flag.
	 * Most flags have 2-letter code, but some have more (eg England=gbeng,
	 * Scotland=gbsct, Wales=gbwls, etc.).
	 *
	 * @since   1.6.0
	 * @var     string      $code   One or more 2-letter codes.
	 * @return  string
	 */
	public function to_emoji( $code ) {

		if ( ! is_string( $code ) || strlen( $code ) < 2 ) {
			return '';
		}

		$code = strtolower( $code );

		$replacement = array(
			'uk' => 'gb',
			'an' => 'nl',
			'ap' => 'un',
		);

		if ( array_key_exists( $code, $replacement ) ) {
			$code = $replacement[$code];
		}

		return $this->to_unicode( $code );

	}

	/**
	 * Converts country (or region) code to emoji flag. One flag only!
	 *
	 * @since   1.6.0
	 * @param   string      $code   2 or more letter code.
	 * @return  string
	 */
	private function to_unicode( $code ) {

		$arr = str_split( $code );
		$str = '';

		foreach ( $arr as $char ) {
			$str .= $this->enclosed_unicode( $char );
		}

		return $str;

	}

	/**
	 * Converts a character into enclosed unicode.
	 *
	 * @since   1.6.0
	 * @param   string    (one character)
	 * @return  string
	 */
	private function enclosed_unicode( $char ) {

		$arr = array(
			'a' => '1F1E6',
			'b' => '1F1E7',
			'c' => '1F1E8',
			'd' => '1F1E9',
			'e' => '1F1EA',
			'f' => '1F1EB',
			'g' => '1F1EC',
			'h' => '1F1ED',
			'i' => '1F1EE',
			'j' => '1F1EF',
			'k' => '1F1F0',
			'l' => '1F1F1',
			'm' => '1F1F2',
			'n' => '1F1F3',
			'o' => '1F1F4',
			'p' => '1F1F5',
			'q' => '1F1F6',
			'r' => '1F1F7',
			's' => '1F1F8',
			't' => '1F1F9',
			'u' => '1F1FA',
			'v' => '1F1FB',
			'w' => '1F1FC',
			'x' => '1F1FD',
			'y' => '1F1FE',
			'z' => '1F1FF',
		);

		$char = strtolower( $char );

		if ( array_key_exists( $char, $arr ) ) {
			return mb_convert_encoding( '&#x'.$arr[$char].';', 'UTF-8', 'HTML-ENTITIES' );
		}

		return '';

	}

}
