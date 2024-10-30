<?php
/**
 * Template browser.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="template-browser-<?php echo esc_attr( $this->editor_id ); ?>" class="template-browser boldermail-editor-meta-box">

	<button type="button" class="boldermail-handlediv">
		<span class="screen-reader-text"><?php esc_html_e( 'Close panel: Templates', 'boldermail' ); ?></span>
		<span class="boldermail-close-indicator"></span>
	</button>
	<h3 class="boldermail-hndle"><span><?php esc_html_e( 'Select a Template', 'boldermail' ); ?></span></h3>

	<div class="boldermail-editor-meta-box-inside">

<?php if ( $templates && count( $templates ) > 0 ) : ?>

	<div class="templates wp-clearfix">

	<?php foreach ( $templates as $template ) : ?>

		<?php if ( ! $template ) return; ?>

		<div class="template">
			<div class="template-screenshot">
				<img src="<?php echo has_post_thumbnail( $template->ID ) ? get_the_post_thumbnail_url( $template->ID, 'boldermail_template_thumbnail' ) : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAALElEQVQYGWO8d+/efwYkoKioiMRjYGBC4WHhUK6A8T8QIJt8//59ZC493AAAQssKpBK4F5AAAAAASUVORK5CYII='; ?>" alt="<?php echo esc_attr( $template->post_title ); ?>">
			</div>

			<div class="template-actions">
				<a class="button activate-template" href="javascript:;" data-id="<?php echo esc_attr( $template->ID ); ?>" aria-label="Activate <?php echo esc_attr( $template->post_title ); ?>"><?php esc_html_e( 'Activate', 'boldermail' ); ?></a>
				<a class="button button-primary load-template" href="javascript:;" data-id="<?php echo esc_attr( $template->ID ); ?>"><?php esc_html_e( 'Preview', 'boldermail' ); ?></a>
			</div>

			<div class="template-id-container">
				<h3 class="template-name" id="<?php echo esc_attr( $template->post_name ); ?>-name"><?php echo esc_html( $template->post_title ); ?></h3>
			</div>
		</div>

	<?php endforeach; ?>

		<div class="template add-new-template">
			<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bm_template' ) ); ?>" target="_blank">
				<div class="template-screenshot">
					<span></span>
				</div>
				<h3 class="template-name"><?php esc_html_e( 'Add New Template', 'boldermail' ); ?></h3>
			</a>
		</div>
	</div>

<?php else : ?>
	<div class="notice notice-alt notice-warning inline"><p><?php echo sprintf( __( "There are no templates available. <a href=&quot;%s&quot; target=&quot;_blank&quot;>Go create one!</a>", 'boldermail' ), esc_url( admin_url( 'post-new.php?post_type=bm_template' ) ) ); ?></p></div>
<?php endif; ?>

	</div>
</div>
<?php
