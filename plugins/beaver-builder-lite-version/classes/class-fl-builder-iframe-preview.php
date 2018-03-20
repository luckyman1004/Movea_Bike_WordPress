<?php

/**
 * Handles rendering layouts in an iframe preview.
 *
 * @since 2.0.6
 */
final class FLBuilderIframePreview {

	/**
	 * Initialize hooks.
	 *
	 * @since 2.0.6
	 * @return void
	 */
	static public function init() {
		if ( ! isset( $_GET['fl_builder_preview'] ) ) {
			return;
		}

		add_filter( 'show_admin_bar', '__return_false' );
		add_filter( 'fl_builder_node_status', __CLASS__ . '::filter_node_status' );
	}

	/**
	 * Forces draft node status for layout previews.
	 *
	 * @since 2.0.6
	 * @param string $status
	 * @return string
	 */
	static public function filter_node_status( $status ) {
		return 'draft';
	}
}

FLBuilderIframePreview::init();
