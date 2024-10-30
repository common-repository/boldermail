<?php
/**
 * Setup menus in WP admin.
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
 * Boldermail_Menus class.
 *
 * @since 1.7.0
 */
class Boldermail_Menus {

	/**
	 * Hook in tabs.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		/**
		 * Modify menu display.
		 *
		 * @since 1.7.0
		 */
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		add_action( 'admin_head', array( $this, 'menu_staging_site' ) );

		/**
		 * Add settings submenu.
		 *
		 * @since 1.7.0
		 */
		add_action( 'admin_menu', array( $this, 'settings_menu' ), 50 );

		/**
		 * Handle saving settings earlier than `load-{$page}` hook to avoid
		 * race conditions in conditional menus.
		 *
		 * @since 1.7.0
		 */
		add_action( 'wp_loaded', array( $this, 'save_settings' ) );

	}

	/**
	 * Add menu item.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function settings_menu() {

		$settings_page = add_submenu_page(
			'edit.php?post_type=bm_newsletter',
			__( 'Boldermail Settings', 'boldermail' ),
			__( 'Settings', 'boldermail' ),
			'manage_options',
			'boldermail-settings',
			array( $this, 'settings_page' )
		);

		// @see https://developer.wordpress.org/reference/hooks/load-page_hook/
		add_action( 'load-' . $settings_page, array( $this, 'settings_page_init' ) );

	}

	/**
	 * Loads options into memory for use within settings.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function settings_page_init() {

		// Include settings pages.
		Boldermail_Settings::get_settings_pages();

		// Add any posted messages.
		if ( ! empty( $_GET['bm_error'] ) ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			Boldermail_Settings::add_error( wp_kses_post( wp_unslash( $_GET['bm_error'] ) ) ); /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		}
		if ( ! empty( $_GET['bm_message'] ) ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			Boldermail_Settings::add_message( wp_kses_post( wp_unslash( $_GET['bm_message'] ) ) ); /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		}

		do_action( 'boldermail_settings_page_init' );

	}

	/**
	 * Handle saving of settings.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function save_settings() {

		global $current_tab, $current_section;

		// We should only save on the settings page.
		if ( ! is_admin() || ! isset( $_GET['page'] ) || 'boldermail-settings' !== $_GET['page'] ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			return;
		}

		// Include settings pages.
		Boldermail_Settings::get_settings_pages();

		// Initialize current tab and current section for use later in other functions.
		$current_tab     = empty( $_GET['tab'] ) ? 'account' : sanitize_title( wp_unslash( $_GET['tab'] ) ); /* phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound */
		$current_section = empty( $_REQUEST['section'] ) ? '' : sanitize_title( wp_unslash( $_REQUEST['section'] ) ); /* phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound */

		// Save settings if data has been posted.
		if ( '' !== $current_section && apply_filters( "boldermail_save_settings_{$current_tab}_{$current_section}", ! empty( $_POST['save'] ) ) ) { /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
			Boldermail_Settings::save();
		} elseif ( '' === $current_section && apply_filters( "boldermail_save_settings_{$current_tab}", ! empty( $_POST['save'] ) ) ) { /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
			Boldermail_Settings::save();
		}

	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function menu_highlight() {

		global $parent_file, $submenu_file, $post_type;

		switch ( $post_type ) {

			case 'bm_newsletter':
				$submenu_file = ( 'post-new.php?post_type=bm_newsletter' === $submenu_file ) ? 'edit.php?post_type=bm_newsletter' : $submenu_file; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */
				break;

			case 'bm_block_template':
				if ( 'edit.php?post_type=bm_block_template' === $parent_file ) {
					$parent_file = 'edit.php?post_type=bm_newsletter'; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */

					if ( 'post-new.php?post_type=bm_block_template' === $submenu_file || 'edit.php?post_type=bm_block_template' === $submenu_file ) {
						$submenu_file = 'edit.php?post_type=bm_newsletter'; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */
					}
				}
				break;

			case 'bm_newsletter_ares':
				if ( 'edit.php?post_type=bm_newsletter_ares' === $parent_file ) {
					$parent_file = 'edit.php?post_type=bm_newsletter'; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */

					if ( 'post-new.php?post_type=bm_newsletter_ares' === $submenu_file || 'edit.php?post_type=bm_newsletter_ares' === $submenu_file ) {
						$submenu_file = 'edit.php?post_type=bm_autoresponder'; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */
					}
				}
				break;

		}

		remove_submenu_page( 'edit.php?post_type=bm_newsletter', 'post-new.php?post_type=bm_newsletter' );

	}

	/**
	 * Adds the staging site indicator to the menu.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function menu_staging_site() {

		global $submenu;

		$is_duplicate_site = Boldermail_Site::is_duplicate_site();

		if ( isset( $submenu['edit.php?post_type=bm_newsletter'] ) && current_user_can( 'manage_options' ) && $is_duplicate_site ) {

			foreach ( $submenu['edit.php?post_type=bm_newsletter'] as $key => $menu_item ) {

				if ( 0 === strpos( $menu_item[0], __( 'Settings', 'boldermail' ) ) ) {
					$submenu['edit.php?post_type=bm_newsletter'][ $key ][0] .= ' <span class="update-plugins staging">staging</span>'; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */
					break;
				}

			}

		}

	}

	/**
	 * Initialize the settings page.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public function settings_page() {

		Boldermail_Settings::output();

	}

}

return new Boldermail_Menus();
