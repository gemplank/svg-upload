<?php
/**
 * SVG Upload
 *
 * @package           svg_upload
 *
 * Plugin Name:       SVG Upload
 * Description:       A plugin to allow SVG Uploads into the media library.
 * Version:           1.0.0
 * Author:            Gemma Plank <gemma@makedo.net>
 * Author URI:        https://makedo.net
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       svg-upload
 * Domain Path:       /languages
 */

/**
 * Abort on Direct Call
 *
 * Abort if this file is called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Constants.
 */
define( 'SVG_UPLOAD_ROOT', __FILE__ );
define( 'SVG_UPLOAD_NAME', 'SVG Upload' );
define( 'SVG_UPLOAD_SLUG', 'svg-upload' );
define( 'SVG_UPLOAD_PREFIX', 'svg_upload' );



class Svg {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}
	/**
	 * Run all of the plugin functions.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		/**
		 * Load Text Domain
		 */
		load_plugin_textdomain( 'svg-upload', false, SVG_UPLOAD_ROOT . '\languages' );

		add_filter( 'upload_mimes', array( $this, 'cc_mime_types' ) );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'allow_svg' ), 10, 4 );
		add_action( 'wp_ajax_svg_get_attachment_url', array( $this, 'get_attachment_url_media_library' ), 10 );
	}
	/**
	 * Allow SVG MIME type.
	 */
	public function cc_mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	/**
	 * Allow SVG's.
	 */
	public function allow_svg( $data, $file, $filename, $mimes ) {
		global $wp_version;
		// Satisfy WPCS.
		$f = $file;
		if ( '4.7' === $wp_version || ( (float) $wp_version < 4.7 ) ) {
			return $data;
		}
		$filetype = wp_check_filetype( $filename, $mimes );
		return [
			'ext'             => $filetype['ext'],
			'type'            => $filetype['type'],
			'proper_filename' => $data['proper_filename'],
		];
	}
	/**
	 * Ajax get_attachment_url_media_library
	 */
	public function get_attachment_url_media_library() {
		$url           = '';
		$attachment_id = isset( $_REQUEST['attachment_id'] ) ? $_REQUEST['attachment_id'] : '';
		if ( $attachment_id ) {
			$url = wp_get_attachment_url( $attachment_id );
		}
		echo esc_url( $url );
		die();
	}


}

$svg = new Svg();
$svg->run();
