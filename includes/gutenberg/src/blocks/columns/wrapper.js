// Wrapper function.
export default function ColumnsWrapper( { children, ...props } ) {
	const { save } = props;

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmColumnsBlock" style={ { minWidth: '100%' } }>
			<tbody className="bmColumnsBlockOuter">
				<tr>
					<td valign="top" className="bmColumnsBlockInner">
						{ save ? (
							// @todo
							// Can't place strings around InnerBlocks.Content -- see https://github.com/WordPress/gutenberg/issues/10308
							// For now we add this `div` tag and will do a string search and replace before displaying the newsletter.
							<div className="boldermail-html-comment">
								{ '[boldermail_html_comment]' }
								{ '[if mso]>' }
								{ '<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">' }
								{ '<tr>' }
								{ '<![endif]' }
								{ '[/boldermail_html_comment]' }
							</div>
						) : null }
						{ children }
						{ save ? (
							<div className="boldermail-html-comment">
								{ '[boldermail_html_comment]' }
								{ '[if mso]>' }
								{ '</tr>' }
								{ '</table>' }
								{ '<![endif]' }
								{ '[/boldermail_html_comment]' }
							</div>
						) : null }
					</td>
				</tr>
			</tbody>
		</table>
	);
}
