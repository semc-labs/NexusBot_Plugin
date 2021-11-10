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
		wp_register_script( 'babylon', 'https://cdn.babylonjs.com/viewer/babylon.viewer.js', array(), $this->version, false );
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

		if ( $post->post_type == 'project' ) {

			//$return = Nexus_Aurora_Globals::get_template( 'single-project' );

		}

	    // if ( $post->post_type == 'file' ) {

			// 	$return = Nexus_Aurora_Globals::get_template( 'single-file' );

			// }

		return $return;

	} // single_cpt_template()

	/**
	 * Shortcode for viewing a list of Google drive files
	 *
	 * @param array $atts An array of attributes used to build the viewer
	 * @atts[id] The folder ID of the folder to view
	 * @return void
	 */
	public function na_drive_folder($atts) {
		if(empty($atts['id'])) return;

		return '<iframe src="https://drive.google.com/embeddedfolderview?id='.$atts['id'].'#list" style="width:100%; height:300px; border:0;"></iframe>';

	}

	/**
	 * Display a discord widget for the server
	 */
	public function na_discord($atts){
		if(empty($atts['channelid'])) return;

		$nexusbot_url = get_option('nexusbot_url');
		$request = wp_remote_get($nexusbot_url.'/online?channelId='.$atts['channelid']);
		$channelInfo = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);

		$widgetHeader = '<div class="discord-widget__header">
											<a class="dicord-widget__discord" href="https://discord.com" target="_blank"></a>
											<div>
												<span><strong>'.$channelInfo['onlineMembers'].'</strong> Members Online</span>
												'.(! empty($atts['invite']) ? '<a class="dicord-widget__join" href="'.$atts['invite'].'">Join Project Channel</a>':'').'
											</div>
										</div>';

		$widgetBody = '<div class="discord-widget__body">
								<div class="discord-widget__channels">
									<div class="dicord-widget__channel">
										<div class="dicord-widget__channel__name">'.$channelInfo['messages']['channel'].'</div>
									</div>
								</div>';

		if(! empty($channelInfo['messages']['messages'])){
			//$channelInfo['messages']['messages'] = array_reverse($channelInfo['messages']['messages']);

			$widgetBody .= '<div class="discord-widget__channel-messages">';

			foreach($channelInfo['messages']['messages'] as $message){
				$widgetBody .= '<div class="discord-widget__channel-messages__message">';

				$widgetBody .= '<div class="discord-widget__channel-messages__avatar"><img src="'.$message['avatar'].'" alt=""/></div>';
				$widgetBody .= '<div class="discord-widget__channel-messages__content">
													<strong>'.$message['username'].' <span>'.date('F j, Y @ g:ia', strtotime($message['date'])).'</span></strong>
													<div>'.$message['message'].'</div>
												</div>';

				$widgetBody .= '</div>';
			}

			$widgetBody .= '</div>';
		}

		$widgetBody .= '</div>';

		$widgetHTML = '<div id="na-discord-widget">'.$widgetHeader.$widgetBody.'</div>';
		
		$data = array(
			'hideChannels' => ['🔊meeting-room-voice-1'],
			'theme' => 'dark',
			'id' => '731855215816343592'
		);
		$query_vars = http_build_query($data);

		$widgetIFrame = '<iframe src="https://discord.com/widget?'.$query_vars.'" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>';
		
		return $widgetHTML.$widgetIFrame;
	}


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
		wp_enqueue_script( 'babylon');
		
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

	/**
	 * Shortcode for viewing 3D models attached to a project
	 *
	 * @param array $atts An array of attributes used to build the viewer
	 * @return void
	 */
	public function na_viewer_list($atts) {
		global $post;

		if(function_exists('get_fields')){
			$fields = get_fields( $post->ID );
		}

		if(empty($fields['3d_files'])) return;

		wp_enqueue_script( 'babylon');

		$list_html = '<div id="na-viewer-list">
			<div id="viewer-wrapper">
				<babylon id="babylon-viewer" 
				model="'.$fields['3d_files'][0]['file']['url'].'" 
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
			</div>';

		$list_html .=  '<h3>Available Files</h3>';

		$list_html .=  '<div class="download-list">';

		foreach($fields['3d_files'] as $file){	
			$list_html .=  '<div class="download">
					<h6>'.$file['file']['title'].'</h6>
					<div class="download-info">
						<span>'.Nexus_Aurora_Globals::convert_bytes( $file['file']['filesize'] ).'</span>
						<a href="'.$file['file']['url'].'" class="view-model" >View</a>
						<a href="'.$file['file']['url'].'" download>Download</a>
					</div>
				</div>';
		}

		$list_html .=  '</div></div>';

		$list_html .= <<<EOT
<script>
	(function($) {
		$(window).on('load', function() {
			BabylonViewer.viewerManager.getViewerPromiseById('babylon-viewer').then(function (viewer) {
				$('.view-model').click(function(e){
					e.preventDefault();
					
					viewer.loadModel({
						url: $(this).attr('href')
					});
				});
			});
		});
	})( jQuery );
</script>
EOT;

		return $list_html;
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
