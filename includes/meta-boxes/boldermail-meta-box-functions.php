<?php
/**
 * Boldermail Meta Box Functions.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.1.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output a text input box with Emoji button.
 *
 * @since 2.1.0
 * @param array $field Input field parameters.
 */
function boldermail_wp_emoji_text_input( $field ) {

	$field['id']                 = isset( $field['id'] ) ? $field['id'] : '';
	$field['label']              = isset( $field['label'] ) ? $field['label'] : '';
	$field['name']               = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['type']               = isset( $field['type'] ) ? $field['type'] : 'text';
	$field['class']              = isset( $field['class'] ) ? $field['class'] : 'regular-text';
	$field['style']              = isset( $field['style'] ) ? $field['style'] : '';
	$field['placeholder']        = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['wrapper_class']      = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['wrapper_attributes'] = isset( $field['wrapper_attributes'] ) ? (array) $field['wrapper_attributes'] : array();
	$field['value']              = isset( $field['value'] ) ? $field['value'] : '';
	$field['description']        = isset( $field['description'] ) ? $field['description'] : '';
	$field['desc_tip']           = isset( $field['desc_tip'] ) ? $field['desc_tip'] : true;
	$field['editable']           = isset( $field['editable'] ) ? $field['editable'] : true;
	$field['custom_attributes']  = isset( $field['custom_attributes'] ) ? (array) $field['custom_attributes'] : array();
	$field['custom_attributes']  = $field['editable'] ? $field['custom_attributes'] : array_merge( [ 'readonly' => 'readonly' ], $field['custom_attributes'] );

	$rows = 2;
	$cols = 20;

	if ( isset( $field['custom_attributes']['readonly'] ) && 'readonly' === $field['custom_attributes']['readonly'] ) {

		echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">
			<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	} else {

		echo '<fieldset class="form-field emoji-picker-field '
				. esc_attr( $field['id'] ) . '_field '
				. esc_attr( $field['wrapper_class'] ) . '">
			<legend>' . wp_kses_post( $field['label'] ) . '</legend>';

		echo '<div class="emoji-picker">
			<div contenteditable class="emoji-contenteditable emoji-text">'
				. wp_kses_post( $field['value'] ) . '</div>
				<button class="emoji-button">
					<img class="bm-emoji" draggable="false" alt="😎" src="https://twemoji.maxcdn.com/v/13.0.0/svg/1f60e.svg">
				</button>
			</div>';

	}

	echo '<input type="text" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '"  name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="' . esc_attr( $rows ) . '" cols="' . esc_attr( $cols ) . '" value="' . esc_attr( $field['value'] ) . '" ' . boldermail_implode_html_attributes( $field['custom_attributes'] ) . ' />';

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo boldermail_help_tip( $field['description'] );
	}

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	if ( isset( $field['custom_attributes']['readonly'] ) && 'readonly' === $field['custom_attributes']['readonly'] ) {
		echo '</p>';
	} else {
		echo '</fieldset>';
	}

}

/**
 * Output a text input box.
 *
 * @since 2.1.0
 * @param array $field Input field parameters.
 */
function boldermail_wp_text_input( $field ) {

	$field['id']                 = isset( $field['id'] ) ? $field['id'] : '';
	$field['label']              = isset( $field['label'] ) ? $field['label'] : '';
	$field['name']               = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['type']               = isset( $field['type'] ) ? $field['type'] : 'text';
	$field['class']              = isset( $field['class'] ) ? $field['class'] : 'regular-text';
	$field['style']              = isset( $field['style'] ) ? $field['style'] : '';
	$field['placeholder']        = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['wrapper_class']      = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['wrapper_attributes'] = isset( $field['wrapper_attributes'] ) ? (array) $field['wrapper_attributes'] : array();
	$field['value']              = isset( $field['value'] ) ? $field['value'] : '';
	$field['editable']           = isset( $field['editable'] ) ? $field['editable'] : true;
	$field['description']        = isset( $field['description'] ) ? $field['description'] : '';
	$field['desc_tip']           = isset( $field['desc_tip'] ) ? $field['desc_tip'] : true;
	$field['custom_attributes']  = isset( $field['custom_attributes'] ) ? (array) $field['custom_attributes'] : array();
	$field['custom_attributes']  = $field['editable'] ? $field['custom_attributes'] : array_merge( [ 'readonly' => 'readonly' ], $field['custom_attributes'] );

	echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" ' . boldermail_implode_html_attributes( $field['wrapper_attributes'] ) . '>
		<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo boldermail_help_tip( $field['description'] );
	}

	echo '<input type="' . esc_attr( $field['type'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . boldermail_implode_html_attributes( $field['custom_attributes'] ) . ' />';

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	echo '</p>';

}

/**
 * Output a hidden input box.
 *
 * @since 2.1.0
 * @param array $field Input field parameters.
 */
function boldermail_wp_hidden_input( $field ) {

	$field['id']    = isset( $field['id'] ) ? $field['id'] : '';
	$field['name']  = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['class'] = isset( $field['class'] ) ? $field['class'] : '';
	$field['value'] = isset( $field['value'] ) ? $field['value'] : '';

	echo '<input type="hidden" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" /> ';

}

/**
 * Output a textarea input box.
 *
 * @since 2.3.0
 * @param array $field Input field parameters.
 */
function boldermail_wp_textarea_input( $field ) {

	$field['id']                 = isset( $field['id'] ) ? $field['id'] : '';
	$field['label']              = isset( $field['label'] ) ? $field['label'] : '';
	$field['name']               = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['type']               = isset( $field['type'] ) ? $field['type'] : 'text';
	$field['class']              = isset( $field['class'] ) ? $field['class'] : 'large-text';
	$field['style']              = isset( $field['style'] ) ? $field['style'] : '';
	$field['placeholder']        = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['wrapper_class']      = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['wrapper_attributes'] = isset( $field['wrapper_attributes'] ) ? (array) $field['wrapper_attributes'] : array();
	$field['value']              = isset( $field['value'] ) ? $field['value'] : '';
	$field['editable']           = isset( $field['editable'] ) ? $field['editable'] : true;
	$field['description']        = isset( $field['description'] ) ? $field['description'] : '';
	$field['desc_tip']           = isset( $field['desc_tip'] ) ? $field['desc_tip'] : true;
	$field['custom_attributes']  = isset( $field['custom_attributes'] ) ? (array) $field['custom_attributes'] : array();
	$field['custom_attributes']  = $field['editable'] ? $field['custom_attributes'] : array_merge( [ 'readonly' => 'readonly' ], $field['custom_attributes'] );
	$field['rows']               = isset( $field['rows'] ) ? $field['rows'] : 2;
	$field['cols']               = isset( $field['cols'] ) ? $field['cols'] : 20;

	echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" ' . boldermail_implode_html_attributes( $field['wrapper_attributes'] ) . '>
		<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo boldermail_help_tip( $field['description'] );
	}

	echo '<textarea class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '"  name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="' . esc_attr( $field['rows'] ) . '" cols="' . esc_attr( $field['cols'] ) . '" ' . boldermail_implode_html_attributes( $field['custom_attributes'] ) . '>' . esc_textarea( $field['value'] ) . '</textarea> ';

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	echo '</p>';
}

/**
 * Output a checkbox input box.
 *
 * @since 2.3.0
 * @param array $field Data about the field to render.
 */
function boldermail_wp_checkbox( $field ) {

	$field['id']                 = isset( $field['id'] ) ? $field['id'] : '';
	$field['label']              = isset( $field['label'] ) ? $field['label'] : '';
	$field['name']               = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['type']               = isset( $field['type'] ) ? $field['type'] : 'text';
	$field['class']              = isset( $field['class'] ) ? $field['class'] : 'checkbox';
	$field['style']              = isset( $field['style'] ) ? $field['style'] : '';
	$field['placeholder']        = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['wrapper_class']      = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['wrapper_attributes'] = isset( $field['wrapper_attributes'] ) ? (array) $field['wrapper_attributes'] : array();
	$field['value']              = isset( $field['value'] ) ? $field['value'] : '';
	$field['cbvalue']            = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
	$field['editable']           = isset( $field['editable'] ) ? $field['editable'] : true;
	$field['description']        = isset( $field['description'] ) ? $field['description'] : '';
	$field['desc_tip']           = isset( $field['desc_tip'] ) ? $field['desc_tip'] : true;
	$field['custom_attributes']  = isset( $field['custom_attributes'] ) ? (array) $field['custom_attributes'] : array();
	$field['custom_attributes']  = $field['editable'] ? $field['custom_attributes'] : array_merge( [ 'readonly' => 'readonly' ], $field['custom_attributes'] );

	echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" ' . boldermail_implode_html_attributes( $field['wrapper_attributes'] ) . '>
		<legend for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</legend>';

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo boldermail_help_tip( $field['description'] );
	}

	if ( is_array( $field['value'] ) && is_array( $field['cbvalue'] ) ) {
		echo '<ul id="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['class'] ) . '">';

		$counter = 0;

		foreach ( $field['cbvalue'] as $cbvalue => $cblabel ) {
			echo '<li><label>
				<input type="checkbox" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $cbvalue ) . '" ' . boldermail_implode_html_attributes( $field['custom_attributes'] ) . ( in_array( $cbvalue, $field['value'], true ) ? 'checked="checked"' : '' ) . '/>
				' . wp_kses_post( $cblabel ) . '</label></li>';
			$counter++;
		}

		echo '</ul>';
	} else {
		echo '<input type="checkbox" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . '  ' . boldermail_implode_html_attributes( $field['custom_attributes'] ) . '/> ';
	}

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	echo '</fieldset>';

}

/**
 * Output a select input box.
 *
 * @since 2.3.0
 * @param array $field Data about the field to render.
 */
function boldermail_wp_select( $field ) {

	$field['id']                 = isset( $field['id'] ) ? $field['id'] : '';
	$field['label']              = isset( $field['label'] ) ? $field['label'] : '';
	$field['name']               = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['class']              = isset( $field['class'] ) ? $field['class'] : 'select short';
	$field['style']              = isset( $field['style'] ) ? $field['style'] : '';
	$field['placeholder']        = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
	$field['wrapper_class']      = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['wrapper_attributes'] = isset( $field['wrapper_attributes'] ) ? (array) $field['wrapper_attributes'] : array();
	$field['value']              = isset( $field['value'] ) ? $field['value'] : '';
	$field['options']            = isset( $field['options'] ) ? (array) $field['options'] : '';
	$field['editable']           = isset( $field['editable'] ) ? $field['editable'] : true;
	$field['description']        = isset( $field['description'] ) ? $field['description'] : '';
	$field['desc_tip']           = isset( $field['desc_tip'] ) ? $field['desc_tip'] : true;
	$field['custom_attributes']  = isset( $field['custom_attributes'] ) ? (array) $field['custom_attributes'] : array();

	echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '" ' . boldermail_implode_html_attributes( $field['wrapper_attributes'] ) . '>
		<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo boldermail_help_tip( $field['description'] );
	}

	echo '<select class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" data-placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . boldermail_implode_html_attributes( $field['custom_attributes'] ) . ( $field['editable'] ? '' : ' disabled' ) . '>';

	foreach ( $field['options'] as $key => $value ) {
		echo '<option value="' . esc_attr( $key ) . '" ' . boldermail_selected( $key, $field['value'] ) . '>' . esc_html( $value ) . '</option>';
	}

	echo '</select>';

	if ( ! $field['editable'] ) {
		foreach ( (array) $field['value'] as $value ) {
			boldermail_wp_hidden_input(
				[
					'name'  => $field['name'],
					'value' => $value,
				]
			);
		}
	}

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	echo '</p>';

}

/**
 * Output a radio input box.
 *
 * @since 2.3.0
 * @param array $field Data about the field to render.
 */
function boldermail_wp_radio( $field ) {

	$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
	$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
	$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
	$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

	echo '<fieldset class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><legend>' . wp_kses_post( $field['label'] ) . '</legend>';

	if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
		echo boldermail_help_tip( $field['description'] );
	}

	echo '<ul class="boldermail-radios">';

	foreach ( $field['options'] as $key => $value ) {

		echo '<li><label><input
				name="' . esc_attr( $field['name'] ) . '"
				value="' . esc_attr( $key ) . '"
				type="radio"
				class="' . esc_attr( $field['class'] ) . '"
				style="' . esc_attr( $field['style'] ) . '"
				' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
				/> ' . esc_html( $value ) . '</label>
		</li>';
	}
	echo '</ul>';

	if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
		echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
	}

	echo '</fieldset>';
}
