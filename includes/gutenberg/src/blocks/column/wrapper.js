// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function ColumnWrapper( { children, ...props } ) {
	const { attributes: { width }, save } = props;

	return (
		<Fragment>
			{ save ? (
				// @todo
				// Can't place strings around InnerBlocks.Content -- see https://github.com/WordPress/gutenberg/issues/10308
				// For now we add this `div` tag and will do a string search and replace before displaying the newsletter.
				<div className="boldermail-html-comment">
					{ '[boldermail_html_comment]' }
					{ '[if gte mso 9]>' }
					{ `<td valign="top" width="${ width }" style="width:${ width }px;">` }
					{ '<![endif]' }
					{ '[/boldermail_html_comment]' }
				</div>
			) : null }
			<table align="left" border="0" cellPadding="0" cellSpacing="0" style={ { maxWidth: `${ width }px` } } width="100%" className="bmColumnContentContainer">
				<tbody>
					<tr>
						<td valign="top" className="bmColumnContent">
							{ children }
						</td>
					</tr>
				</tbody>
			</table>
			{ save ? (
				<div className="boldermail-html-comment">
					{ '[boldermail_html_comment]' }
					{ '[if gte mso 9]>' }
					{ '</td>' }
					{ '<![endif]' }
					{ '[/boldermail_html_comment]' }
				</div>
			) : null }
		</Fragment>
	);
}
