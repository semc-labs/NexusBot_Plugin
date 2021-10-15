<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://jboullion.com
 * @since      1.0.0
 *
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/public
 * @author     James Boullion <jboullion83@gmail.com>
 */
class Nexus_Aurora_Bot_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nexus-aurora-bot-public.css', array(), $this->version, 'all' );
		

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nexus-aurora-bot-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'babylon', 'https://cdn.babylonjs.com/viewer/babylon.viewer.js', array(), $this->version, false );
	}

	
	function alter_the_content($content){
		$icon_url = plugin_dir_url( __FILE__ ).'images/icons';
		$icon_dir = plugin_dir_path( __FILE__ ).'images/icons';
		$icons = scandir($icon_dir);
		
		if($icons && is_array($icons)){
			
			// clean our content by spacing out our icons
			$content = preg_replace("/:(\D*)::/", ':$1: :', $content);

			foreach($icons as $icon_file){
				$icon_name = pathinfo($icon_file, PATHINFO_FILENAME);				
				$content = str_replace(":$icon_name:", '<img src="'.$icon_url.'/'.$icon_file.'"  draggable="false" role="img" width="24" alt="" />', $content);
			}
		}
		return $content;
	}

	/**
	 * Adds a default single view template for a job opening
	 *
	 * @param 	string 		$template 		The name of the template
	 * @return 	mixed 						The single template
	 */
	public function single_cpt_template( $template ) {

		global $post;

		$return = $template;

	    if ( $post->post_type == 'file' ) {

			$return = Nexus_Aurora_Globals::get_template( 'single-file' );

		}

		return $return;

	} // single_cpt_template()

	/**
	 * Shortcode for viewing 3D models on the site
	 *
	 * @param array $atts An array of attributes used to build the viewer
	 * @atts[id] The attachment_id of the file to view
	 * @atts[url] The url of the file to view
	 * @atts[mtl] mtl file required when viewing an OBJ file
	 * @return void
	 */
	public function na_viewer($atts) {
		if(! empty($atts['id'])){
			$atts['url'] = wp_get_attachment_url($atts['id']);
			$filesize = filesize(get_attached_file($atts['id']));
		}

		if(empty($atts['url']) || ! filter_var($atts['url'], FILTER_VALIDATE_URL)) return '';

		//$headers = get_headers($atts['url'], true);

		return '<div class="babylon-wrapper">
					<babylon class="babylon-viewers" 
						model="'.$atts['url'].'" 
						templates.main.params.fill-screen="true"
						templates.nav-bar.params.hide-vr="true"
						templates.nav-bar.params.hide-logo="true"
						templates.loading-screen.params.static-loading-image="https://nexusaurora.org/wp-content/uploads/2021/10/na_spin_logo.gif"
						templates.loading-screen.params.loading-image="">
						<scene debug="false" render-in-background="true" disable-camera-control="false">
							<main-color r="0.5" g="0.3" b="0.3"></main-color>
							<image-processing-configuration color-curves-enabled="true" exposure="1" contrast="1">
								<color-curves global-hue="5">
								</color-curves>
							</image-processing-configuration>
						</scene>
						<lab>
							<default-rendering-pipeline grain-enabled="false" sharpen-enabled="true" glow-layer-enabled="false" bloom-enabled="false" bloom-threshold="2.0">
							</default-rendering-pipeline>
						</lab>
					</babylon>
					<div class="download text-center">
						'.($filesize?'<span>'.Nexus_Aurora_Globals::convert_bytes( $filesize ).'</span>':'').'
						<a href="'.$atts['url'].'" download>Download</a>
					</div>
				</div>';
	}

}
