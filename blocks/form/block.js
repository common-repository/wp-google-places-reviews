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
	hmpGoogleReviewFormBlock();
});

/**
 * Google Review Form Block.
 */
function hmpGoogleReviewFormBlock() {
	var blockSlug = 'hmp/google-review-form-block';

	registerBlockType(blockSlug, {
		title: 'Google Review Form',
		description: 'Display top 5 Google places listing reviews.',
		icon: 'feedback',
		category: 'common',
		supports: {
			customClassName: false,
		},
		attributes: {
			place_id: {
				type: 'string',
			},
			btn_class: {
				type: 'string',
			},
		},

		edit: function(props) {
			var place_id = props.attributes.place_id;
			var btn_class = props.attributes.btn_class;

			return(
				el(
				Fragment,
				null,
					el(
					InspectorControls,
					null,
						el(PanelBody, { // Google settings
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
						el(PanelBody, { // Form settings
							title: 'Form Settings',
							icon: 'art',
							initialOpen: true,
						},
							el(TextControl, {
								type: 'text',
								label: 'Button Class',
								value: btn_class,
								onChange: function(newBtnClass) { props.setAttributes({ btn_class: newBtnClass }) },
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