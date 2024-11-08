<?php
/**
 * Admin View: Settings
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

$tab_exists = isset( $tabs[ $current_tab ] ) || has_action( 'boldermail_sections_' . $current_tab ) || has_action( 'boldermail_settings_' . $current_tab ) || has_action( 'boldermail_settings_tabs_' . $current_tab );
$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';

if ( ! $tab_exists ) {
	wp_safe_redirect( admin_url( 'edit.php?post_type=bm_newsletter&page=boldermail-settings' ) );
	exit;
}

?>
<div class="wrap boldermail">
	<?php do_action( 'boldermail_before_settings_' . $current_tab ); ?>
	<form method="<?php echo esc_attr( apply_filters( 'boldermail_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper boldermail-nav-tab-wrapper">
			<?php

			foreach ( $tabs as $slug => $label ) {
				echo '<a href="' . esc_html( admin_url( 'edit.php?post_type=bm_newsletter&page=boldermail-settings&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
			}

			do_action( 'boldermail_settings_tabs' );

			?>
		</nav>
		<h1 class="screen-reader-text"><?php echo esc_html( $current_tab_label ); ?></h1>
		<?php
			do_action( 'boldermail_sections_' . $current_tab );

			self::show_messages();

			do_action( 'boldermail_settings_' . $current_tab );
		?>
		<p class="submit">
			<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
				<button name="save" class="button-primary boldermail-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'boldermail' ); ?>"><?php esc_html_e( 'Save changes', 'boldermail' ); ?></button>
			<?php endif; ?>
			<?php wp_nonce_field( 'boldermail-settings' ); ?>
		</p>
	</form>
	<?php do_action( 'boldermail_after_settings_' . $current_tab ); ?>
</div>
