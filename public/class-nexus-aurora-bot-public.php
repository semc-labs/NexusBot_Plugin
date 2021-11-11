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
	 * Display a Google Calendar Widger
	 * 
	 * @param array $atts An array of attributes used to build the viewer
	 * @atts[calendarid] The calendar id of the calendar get events from. aka the email address
	 * @atts[apkikey] ??? The apikey connected to the email address to authorize querying events. 
	 * @atts[q] Query string to search events by
	 * TODO: Implement the apikey differently. If all events are from one calendar, set that calednars API globally
	 * ? NOTE: Possibly pass in a "calendar" name which can match an array of API Keys if we have multiple calendars 
	 * @link https://developers.google.com/calendar/api/v3/reference/events/list
	 */
 	public function na_calendar($atts) {

		if(empty($atts['calendarid'])
		|| empty($atts['apikey'])) return;

		// Move these to a safe location OR have them set in the $atts? Not very safe that way, but much more configurable.
		// Could potentially also set an API Key in an ACF field...not much safer
		$calendar_id = $atts['calendarid'];
		$api_key = $atts['apikey'];
		$today = date("Y-m-d\TH:i:s\Z"); // NOTE: In testing I could only get this to work by passing "Z" as the timezone.

		$calendar_link = 'https://www.googleapis.com/calendar/v3/calendars/'.$calendar_id.'/events?maxResults=10&orderBy=startTime&singleEvents=true&timeMin='.$today.'&key='.$api_key;
		
		if($atts['search']) {
			$calendar_link .= "&q=".$atts['search'];
		}
		
		$ics_link = 'https://calendar.google.com/calendar/ical/'.$calendar_id.'/public/basic.ics';
		$add_apple_calendar = 'webcal://calendar.google.com/calendar/ical/'.$calendar_id.'/public/basic.ics';
		$add_google_calendar = 'https://calendar.google.com/calendar/render?cid=https://calendar.google.com/calendar/ical/'.$calendar_id.'/public/basic.ics';
		
		$plugin_url = plugin_dir_path( __FILE__ );
		$apple_svg = include $plugin_url . 'images/fontawesome/apple-brands.php';
		$google_svg = include $plugin_url . 'images/fontawesome/google-brands.php';
		$download_svg = include $plugin_url . 'images/fontawesome/download-solid.php';
		$clock_svg = include $plugin_url . 'images/fontawesome/clock-regular.php';
		$marker_svg = include $plugin_url . 'images/fontawesome/map-marker-regular.php';

		

		$calender_image = 'https://ssl.gstatic.com/calendar/images/dynamiclogo_2020q4/calendar_10_2x.png';

		$request = wp_remote_get($calendar_link);
		$event_info = json_decode( wp_remote_retrieve_body( $request ), ARRAY_A);

		$user_location = $this->get_user_location();

		$widgetHeader = '<div class="calendar-widget__header">
											<a class="calendar-widget__google-link" href="https://calendar.google.com/" target="_blank">
												<img src="'.$calender_image.'" alt="Google Calendar"/> Calendar
											</a>
											<div class="calendar-widget__integrate">
												<a class="calendar-widget__google" href="'.$add_google_calendar.'" target="_blank">'.$google_svg.' Add to Google</a>
												<a class="calendar-widget__apple" href="'.$add_apple_calendar.'" target="_blank">'.$apple_svg.' Add to Apple</a>
												<a class="calendar-widget__ical" href="'.$ics_link.'" download>'.$download_svg.' Download</a>
											</div>
										</div>';

		$widgetBody = '<div class="calendar-widget__body">
										<div class="calendar-widget__events">';

		if(! empty($event_info['items'])){

			foreach($event_info['items'] as $event){
				$widgetBody .= '<a href="'.$event['htmlLink'].'" target="_blank" class="calendar-widget__event">';
				
				// Attempt to convert event to users local time
				if ($user_location['timezone']) {
					$timezone = new DateTimeZone( $user_location['timezone']);
					if(! empty($event['start']['date'])){
						$start_time = new DateTime( $event['start']['date']); 
						$start_time = $start_time->setTimezone($timezone)->format('F j, Y');
						$end_time = 'All Day';
					}else{
						$start_time = new DateTime( $event['start']['dateTime']); 
						$start_time = $start_time->setTimezone($timezone)->format('F j, Y g:ia');
						$end_time = new DateTime( $event['end']['dateTime']); 
						$end_time = $end_time->setTimezone($timezone)->format('F j, Y g:ia');
					}
					
				}else{
					if(! empty($event['start']['date'])){
						$start_time = date('F j, Y', strtotime($event['start']['date']));
						$end_time = 'All Day';
					}else{
						$start_time = date('F j, Y g:ia', strtotime($event['start']['dateTime']));
						$end_time = date('F j, Y g:ia', strtotime($event['end']['dateTime']));
					}
				}

				if(date('F j, Y', strtotime($start_time)) === date('F j, Y', strtotime($end_time))) {
					// If NOT an all day event, but the event ends the same day it begins, ONLY show the end time.
					$end_time = date('g:ia', strtotime($end_time));
				}

				//$widgetBody .= '<div class="calendar-widget__event__avatar"><img src="'.$message['avatar'].'" alt=""/></div>';
				$widgetBody .= '<strong class="event-title">'.$event['summary'].'</strong>
												<div class="event-date">'.$clock_svg.' '.$start_time.' - '.$end_time.'</div>
												'.($event['location']?'<div class="event-location">'.$marker_svg.' '.$event['location'].'</div>':'').'
												';
				// '.($event['description']?'<div class="event-description">'.$event['description'].'</div>':'');

				$widgetBody .= '</a>';
			}

		}

		$widgetBody .= '</div></div>';

		$widgetHTML = '<div id="na-calendar-widget">'.$widgetHeader.$widgetBody.'</div>';

		return $widgetHTML;

		// Iframes, not great, but kind of work
		//return THIS IS A TRIAL / PAID SERVICE '<iframe src="https://feed.mikle.com/widget/v2/150927/?preloader-text=Loading" height="230px" width="100%" class="fw-iframe" scrolling="no" frameborder="0"></iframe>';
		//return '<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=America%2FChicago&showTitle=0&showPrint=0&showCalendars=0&src=amJvdWxsaW9uODVAZ21haWwuY29t&src=YWRkcmVzc2Jvb2sjY29udGFjdHNAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&src=ZW4udXNhI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%237986CB&color=%2333B679&color=%230B8043" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>';
 	}

	/**
	 * Attempt to get the users location info, including timezone 
	 * 
	 * ! NOTE: I don't love this. But other solutions will involve JS. Which we could do if needed. 
	 */
	public function get_user_location(){
		$ip     = $_SERVER['REMOTE_ADDR']; 
		if($ip === '127.0.0.1'){
			// for local testing let's just assume a default IP
			$ip = '69.131.85.114';
		}
		$json   = file_get_contents( 'http://ip-api.com/json/' . $ip);
		return json_decode( $json, true);
	 }


	/**
	 * Display a discord widget for the server
	 * 
	 * @param array $atts An array of attributes used to build the viewer
	 * @atts[channelid] The channelid of the discord channel to get messages from
	 * @atts[invite] The url invite link to the channel / server
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
