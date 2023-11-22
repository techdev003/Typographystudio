<?php
/**
 * Plugin Name: Parallax Section - Block
 * Description: Makes background element scrolls slower than foreground content.
 * Version: 1.0.7
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: parallax-section
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'PSB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.7' );
define( 'PSB_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

// Parallax Section
class PSBParallaxSection{
	function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit() {
		wp_register_script( 'psb-parallax-script', plugins_url( 'dist/script.js', __FILE__ ), [ 'react', 'react-dom' ], PSB_PLUGIN_VERSION, true ); // Frontend Script

		wp_register_style( 'psb-parallax-style', plugins_url( 'dist/style.css', __FILE__ ), [], PSB_PLUGIN_VERSION ); // Style

		wp_register_style( 'psb-parallax-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'psb-parallax-style' ], PSB_PLUGIN_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'psb-parallax-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'psb-parallax-editor-script', 'parallax-section', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	}

	function render( $attributes, $content ){
		extract( $attributes );

		// Enqueue assets where has block
		wp_enqueue_script( 'psb-parallax-script' );
		wp_enqueue_style( 'psb-parallax-style' );

		$className = $className ?? '';
		$blockClassName = 'wp-block-psb-parallax ' . $className . ' align' . $align;

		ob_start(); ?>
		<style>
			<?php echo esc_html( "#psbParallaxSection-$cId{ min-height: $minHeight; }" ) ?>
		</style>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='psbParallaxSection-<?php echo esc_attr( $cId ) ?>' data-props='<?php echo esc_attr( wp_json_encode( [ 'attributes' => $attributes, 'content' => $content ] ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new PSBParallaxSection;