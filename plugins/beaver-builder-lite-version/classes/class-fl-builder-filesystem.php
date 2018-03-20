<?php
/**
 * Filesystem Class.
 * @since 2.0.6
 */
class FL_Filesystem {

	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * file_get_contents using wp_filesystem.
	 * @since 2.0.6
	 */
	function file_get_contents( $path ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->get_contents( $path );
	}

	/**
	 * file_put_contents using wp_filesystem.
	 * @since 2.0.6
	 */
	function file_put_contents( $path, $contents ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->put_contents( $path, $contents, FS_CHMOD_FILE );
	}

	/**
	 * mkdir using wp_filesystem.
	 * @since 2.0.6
	 */
	function mkdir( $path ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->mkdir( $path );
	}

	/**
	 * is_dir using wp_filesystem.
	 * @since 2.0.6
	 */
	function is_dir( $path ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->is_dir( $path );
	}

	/**
	 * dirlist using wp_filesystem.
	 * @since 2.0.6
	 */
	function dirlist( $path ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->dirlist( $path );
	}

	/**
	 * dirlist using wp_filesystem.
	 * @since 2.0.6
	 */
	function move( $old, $new ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->move( $old, $new );
	}

	/**
	 * rmdir using wp_filesystem.
	 * @since 2.0.6
	 */
	function rmdir( $path, $recursive = false ) {

		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->rmdir( $path, $recursive );
	}

	/**
	 * unlink using wp_filesystem.
	 * @since 2.0.6
	 */
	function unlink( $path ) {
		$wp_filesystem = $this->get_filesystem();
		return $wp_filesystem->delete( $path );
	}

	/**
	 * unlink using wp_filesystem.
	 * @since 2.0.6
	 */
	function file_exists( $path ) {
		return file_exists( $path );
	}

	/**
	 * filesize using wp_filesystem.
	 * @since 2.0.6
	 */
	function filesize( $path ) {
		return filesize( $path );
	}

	/**
	 * Return an instance of WP_Filesystem.
	 * @since 2.0.6
	 */
	function get_filesystem() {

		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			add_filter( 'filesystem_method',              array( $this, 'filesystem_method' ) );
			add_filter( 'request_filesystem_credentials', array( $this, 'request_filesystem_credentials' ) );

			$creds = request_filesystem_credentials( site_url(), '', true, false, null );

			WP_Filesystem( $creds );

			remove_filter( 'filesystem_method',              array( $this, 'filesystem_method' ) );
			remove_filter( 'request_filesystem_credentials', array( $this, 'FLBuilderUtils::request_filesystem_credentials' ) );
		}

		return $wp_filesystem;
	}

	/**
	 * Sets method to direct.
	 * @since 2.0.6
	 */
	function filesystem_method() {
		return 'direct';
	}

	/**
	 * Sets credentials to true.
	 * @since 2.0.6
	 */
	function request_filesystem_credentials() {
		return true;
	}

}

/**
 * Setup singleton.
 * @since 2.0.6
 */
function fl_builder_filesystem() {
	return FL_Filesystem::instance();
}
