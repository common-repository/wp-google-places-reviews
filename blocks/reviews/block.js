/**
 * WP Google Places Reviews
 * Version 1.0.0
 */

// Block dependencies
var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	Fragment = wp.element.Fragment,
	InspectorControls = wp.editor.InspectorControls,
	PanelBody = wp.components.PanelBody,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	ServerSideRender = wp.components.ServerSideRender;

// Call block when DOM is fully load and parsed
document.addEventListener('DOMContentLoaded', function() {
	hmpGoogleReviewBlock();
});

/**
 * Google Review Block.
 */
function hmpGoogleReviewBlock() {
	var blockSlug = 'hmp/google-review-block';

	registerBlockType(blockSlug, {
		title: 'Google Reviews',
		description: 'Display top 5 Google places listing reviews.',
		icon: 'star-filled',
		category: 'common',
		supports: {
			customClassName: false,
		},
		attributes: {
			place_id: {
				type: 'string',
			},
			format: {
				type: 'string',
				default: '',
			},
			quote_color: {
				type: 'string',
				default: '#ccc',
			},
			star_color: {
				type: 'string',
				default: '#e3661a',
			},
		},

		edit: function(props) {
			var place_id = props.attributes.place_id;
			var format = props.attributes.format;
			var quote_color = props.attributes.quote_color;
			var star_color = props.attributes.star_color;

			return(
				el(
				Fragment,
				null,
					el(
					InspectorControls,
					null,
						el(PanelBody, { // Settings section
							title: 'Google Settings',
							description: '',
							icon: 'admin-settings',
							initialOpen: true,
						},
							el(TextControl, {
								type: 'text',
								label: 'Google PlaceID',
								value: place_id,
								onChange: function(newPlaceId) { props.setAttributes({ place_id: newPlaceId }) },
							}),
						),
						el(PanelBody, { // Design section
							title: 'Design',
							icon: 'art',
							initialOpen: true,
						},
							el(SelectControl, {
								type: 'select',
								label: 'Format',
								value: format,
								options: [
									{ value: '', label: 'Column' },
									{ value: 'row', label: 'Row' },
								],
								onChange: function(newFormat) { props.setAttributes({ format: newFormat }) },
							}),
							el(TextControl, {
								type: 'text',
								label: 'Quote Icon Color',
								value: quote_color,
								onChange: function(newQuoteColor) { props.setAttributes({ quote_color: newQuoteColor }) },
							}),
							el(TextControl, {
								type: 'text',
								label: 'Star Icon Color',
								value: star_color,
								onChange: function(newStarColor) { props.setAttributes({ star_color: newStarColor }) },
							}),
						)
					),
					el(ServerSideRender, { // Render the result in admin
						block: blockSlug,
						attributes: props.attributes
					})
				)
			);
		},

		save: function() {
			return null;
		},
	});
}