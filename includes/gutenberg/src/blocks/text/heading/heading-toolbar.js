// Internal dependencies.
import HeadingLevelIcon from './heading-level-icon';

// WordPress dependencies.
const { __, sprintf } = wp.i18n;
const { ToolbarGroup } = wp.components;
const { Component } = wp.element;

// Toolbar function.
class HeadingToolbar extends Component {
	createLevelControl( targetLevel, selectedLevel, onChange ) {
		const isActive = targetLevel === selectedLevel;
		return {
			icon: (
				<HeadingLevelIcon
					level={ targetLevel }
					isPressed={ isActive }
				/>
			),
			// translators: %s: heading level e.g: "1", "2", "3"
			title: sprintf( __( 'Heading %d', 'boldermail' ), targetLevel ),
			isActive,
			onClick: () => onChange( targetLevel ),
		};
	}

	render() {
		const { isCollapsed = true, minLevel, maxLevel, selectedLevel, onChange } = this.props;

		const range = ( start, end ) => Array.from( { length: end - start }, ( v, k ) => k + start );

		return (
			<ToolbarGroup
				isCollapsed={ isCollapsed }
				icon={ <HeadingLevelIcon level={ selectedLevel } /> }
				controls={ range( minLevel, maxLevel ).map( ( index ) =>
					this.createLevelControl( index, selectedLevel, onChange )
				) }
				label={ __( 'Change heading level', 'boldermail' ) }
			/>
		);
	}
}

export default HeadingToolbar;
