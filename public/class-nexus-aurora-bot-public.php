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


	private $google_client;

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
		wp_register_script( 'babylon', 'https://cdn.babylonjs.com/viewer/babylon.viewer.js', array(), $this->version, false );
	}

	

	// /**
	//  * Generate the subscriber form
	//  *
	//  * @param array $atts An array of attributes used to build the subscribe form
	//  * @atts[show_button] Show the form button
	//  * @return void
	//  */
	// public function na_subscribe($atts = []) {
	// 	global $post; 

	// 	if(!empty($_POST['subscribe']['email'])) $subscribe_message = $this->subscribe_submission();

		
	// 	return '<div id="na-subscribe-form">
		
	// 			'.( ! empty($subscribe_message['success']) ? '<div class="na-alert na-success">'.$subscribe_message['success'].'</div>':'').'
	// 			'.( ! empty($subscribe_message['error']) ? '<div class="na-alert na-error">'.$subscribe_message['error'].'</div>':'').'
					
		
	// 			'.( empty($subscribe_message['success']) ? '
	// 			<form 
	// 				class="na-form" 
	// 				method="post" 
	// 				name="Subscribe Form"
	// 				action="#na-subscribe-form">
	// 				<input type="hidden" name="action" value="subscribe_form">
	// 				<input type="hidden" name="post_id" value="'.$post->ID.'">

	// 			<div class="na-input-wrapper">
	// 				<input size="1" type="email" name="subscribe[email]" id="subscriber-email" class="" placeholder="Enter Email" value="'.$_POST['subscribe']['email'].'">
	// 				'.( empty($atts['show_button']) ? '' : '<button type="submit" class="">Subscribe</button>').'
	// 			</div>
	// 		</form>
	// 	</div>':'');

	// 	/*
	// 	<div class="elementor-widget-container">
	// 		<form class="elementor-form" method="post" name="New Form">
	// 			<input type="hidden" name="post_id" value="1376">
				
	// 			<div class="elementor-form-fields-wrapper elementor-labels-above">
	// 				<div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-100">
	// 					<input size="1" type="text" name="form_fields[name]" id="form-field-name" class="elementor-field elementor-size-sm  elementor-field-textual" placeholder="Name">
	// 				</div>
	// 			</div>
	// 		</form>
	// 	</div>
	// 	*/
	// }

	// /**
	//  * Subscribe a visitor to our newsletter
	//  *
	//  * @return void
	//  */
	// public function subscribe_submission() {
	// 	global $wpdb;
		
	// 	if(empty($_POST['subscribe']['email'])) return;

	// 	if(! filter_var($_POST['subscribe']['email'], FILTER_VALIDATE_EMAIL)) return ['error' => 'Invalid email'];

	// 	$subscriber_email = $_POST['subscribe']['email'];

	// 	$subscriber = $wpdb->get_row($wpdb->prepare("SELECT * FROM na_subscribers WHERE email = %s", $subscriber_email));

	// 	if( !$subscriber ){
	// 		$result = $wpdb->insert( 
	// 			'na_subscribers', 
	// 			array( 
	// 				'email' => $subscriber_email,
	// 				'active' => 1,
	// 				'createdAt' => current_time( 'mysql' ),
	// 				'updatedAt' => current_time( 'mysql' ),
	// 			) 
	// 		);

	// 		if($result){
	// 			return ['success' => 'You are now subscribed!'];
	// 		}
	// 	}else{
	// 		$result = $wpdb->update( 
	// 			'na_subscribers', 
	// 			array( 
	// 				'active' => 1,
	// 				'updatedAt' => current_time( 'mysql' ),
	// 			),
	// 			array( 
	// 				'email' => $subscriber_email,
	// 			)
	// 		);

	// 		return ['success' => 'You are now subscribed!']; // 'Already subscribed!'

	// 	}
		
	// 	// return ['error' => 'Error: '.$wpdb->last_error];

	// 	return ['error' => 'Unable to subscribe'];
	// }

}
