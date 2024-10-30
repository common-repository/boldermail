<?php
/**
 * Post Type Factory.
 *
 * The Boldermail Post Type Factory for creating the right object.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-newsletter-autoresponder.php';
require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-newsletter-regular.php';
require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-newsletter-rss-feed.php';

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-template.php';
require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-block-template.php';

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-list.php';

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-subscriber.php';

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-autoresponder.php';

/**
 * Boldermail_Factory class.
 *
 * @since   1.0.0
 */
class Boldermail_Factory {

	/**
	 * Get an object.
	 *
	 * @since   1.0.0
	 * @param   Boldermail_Newsletter|Boldermail_Template|Boldermail_Block_Template|Boldermail_List|Boldermail_Subscriber|Boldermail_Autoresponder|WP_Post|int|bool $type   Newsletter, Template, List, Subscriber, or Autoresponder instance, Post instance, numeric or false to use global $post.
	 * @param   mixed                                                                                                                                               $post   Boldermail or WordPress bject or post ID.
	 * @return  Boldermail_Newsletter|Boldermail_Template|Boldermail_Block_Template|Boldermail_List|Boldermail_Subscriber|Boldermail_Autoresponder|bool                     Object or false if the template cannot be loaded.
	 */
	public static function get_object( $type, $post = false ) {

		if ( ! did_action( 'boldermail_init' ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( __( '%1$s should not be called before the %2$s action.', 'boldermail' ), 'boldermail_get_{post_type}', 'boldermail_init' ), '1.0' );
			return false;
		}

		$object_id = self::get_id( $post );

		if ( ! $object_id ) {
			return false;
		}

		try {
			switch ( $type ) {

				case 'newsletter':
					$newsletter_type = self::get_newsletter_type( $object_id );

					if ( ! $newsletter_type ) {
						return false;
					}

					$classname = self::get_newsletter_classname( $object_id, $newsletter_type );

					return ( $classname && class_exists( $classname ) ) ? new $classname( $object_id ) : false;

				case 'template':
					return ( get_post_type( $object_id ) === 'bm_template' ) ? new Boldermail_Template( $object_id ) : false;

				case 'block_template':
					return ( get_post_type( $object_id ) === 'bm_block_template' ) ? new Boldermail_Block_Template( $object_id ) : false;

				case 'list':
					return ( get_post_type( $object_id ) === 'bm_list' ) ? new Boldermail_List( $object_id ) : false;

				case 'subscriber':
					return ( get_post_type( $object_id ) === 'bm_subscriber' ) ? new Boldermail_Subscriber( $object_id ) : false;

				case 'autoresponder':
					return ( get_post_type( $object_id ) === 'bm_autoresponder' ) ? new Boldermail_Autoresponder( $object_id ) : false;

				default:
					return false;

			}
		} catch ( Exception $e ) {
			return false;
		}

	}

	/**
	 * Gets a newsletter classname and allows filtering.
	 * Returns Boldermail_Newsletter_Regular if the class does not exist.
	 *
	 * @since   1.0.0
	 * @param   int    $newsletter_id     Newsletter ID.
	 * @param   string $newsletter_type   Newsletter type.
	 * @return  string
	 */
	private static function get_newsletter_classname( $newsletter_id, $newsletter_type ) {

		$classname = apply_filters( 'bm_newsletter_class', self::get_classname_from_newsletter_type( $newsletter_type ), $newsletter_type, $newsletter_id );

		return $classname;

	}

	/**
	 * Get the newsletter type for a newsletter.
	 *
	 * @since   1.0.0
	 * @param   int $newsletter_id  Newsletter ID.
	 * @return  string|false
	 */
	private static function get_newsletter_type( $newsletter_id ) {

		$post_type = get_post_type( $newsletter_id );

		$newsletter_type_map = apply_filters(
			'bm_newsletter_type_map',
			array(
				'bm_newsletter'      => 'regular',
				'bm_newsletter_rss'  => 'rss-feed',
				'bm_newsletter_ares' => 'autoresponder',
			)
		);

		if ( $post_type && array_key_exists( $post_type, $newsletter_type_map ) ) {
			return $newsletter_type_map[ $post_type ];
		} else {
			return false;
		}

	}

	/**
	 * Create a class name for the newsletter.
	 *
	 * @since   1.0.0
	 * @param   string $newsletter_type   Product type.
	 * @return  string|false
	 */
	private static function get_classname_from_newsletter_type( $newsletter_type ) {

		return $newsletter_type ? 'Boldermail_Newsletter_' . implode( '_', array_map( 'ucfirst', explode( '-', $newsletter_type ) ) ) : false;

	}

	/**
	 * Get the ID depending on what was passed.
	 *
	 * @since   1.0.0
	 * @param   Boldermail_Newsletter|Boldermail_Template|Boldermail_Block_Template|Boldermail_List|Boldermail_Subscriber|Boldermail_Autoresponder|WP_Post|int|bool $object   Newsletter, Template, List, Subscriber, or Autoresponder instance, Post instance, numeric or false to use global $post.
	 * @return  int|bool  false on failure
	 */
	private static function get_id( $object ) {

		global $post;

		if ( false === $object && isset( $post, $post->ID ) && in_array( get_post_type( $post->ID ), array( 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares', 'bm_template', 'bm_block_template', 'bm_list', 'bm_subscriber', 'bm_autoresponder' ), true ) ) {
			return absint( $post->ID );
		} elseif ( is_numeric( $object ) ) {
			return $object;
		} elseif ( $object instanceof Boldermail_Newsletter || $object instanceof Boldermail_Template || $object instanceof Boldermail_Block_Template || $object instanceof Boldermail_List || $object instanceof Boldermail_Subscriber || $object instanceof Boldermail_Autoresponder ) {
			return $object->get_post_id();
		} elseif ( ! empty( $object->ID ) ) {
			return $object->ID;
		} else {
			return false;
		}

	}

}
