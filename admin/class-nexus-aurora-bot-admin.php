<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/admin
 * @author     James Boullion <jboullion83@gmail.com>
 */
class Nexus_Aurora_Bot_Admin {

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
	 * The url to query our discord bot.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $bot_url    url to query our discord bot.
	 */
	private $bot_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $bot_url ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->bot_url = $bot_url;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nexus_Aurora_Bot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nexus_Aurora_Bot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nexus-aurora-bot-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nexus_Aurora_Bot_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nexus_Aurora_Bot_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nexus-aurora-bot-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_menu()
	{
		// Table helper
		require plugin_dir_path( __FILE__ ) . 'includes/class-nexus-table.php';

		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page( "Nexus Bot", "Nexus Bot", 'edit_posts', $this->plugin_name, array( $this, 'page_dashboard' ), 'dashicons-buddicons-buddypress-logo');
		add_submenu_page(
			'nexus-aurora-bot',
			'Dashboard',
			'Dashboard',
			'edit_posts',
			$this->plugin_name,
			array( $this, 'page_dashboard' )
		);
		add_submenu_page(
			'nexus-aurora-bot',
			'Announcements',
			'Announcements',
			'edit_posts',
			$this->plugin_name . '-announcements',
			array( $this, 'page_announcements' )
		);
		add_submenu_page(
			'nexus-aurora-bot',
			'Channels',
			'Channels',
			'edit_posts',
			$this->plugin_name . '-channels',
			array( $this, 'page_channels' )
		);
		add_submenu_page(
			'nexus-aurora-bot',
			'Members',
			'Members',
			'edit_posts',
			$this->plugin_name . '-members',
			array( $this, 'page_members' )
		);
		add_submenu_page(
			'nexus-aurora-bot',
			'Subscribers',
			'Subscribers',
			'manage_options',
			$this->plugin_name . '-subscribers',
			array( $this, 'page_subscribers' )
		);
		add_submenu_page(
			'nexus-aurora-bot',
			'Settings',
			'Settings',
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_settings' )
		);
	}

	public function page_dashboard() {
        include( plugin_dir_path( __FILE__ ) . 'partials/dashboard.php' );
    }

	public function page_announcements() {
        include( plugin_dir_path( __FILE__ ) . 'partials/announcements.php' );
    }

	public function page_channels() {
        include( plugin_dir_path( __FILE__ ) . 'partials/channels.php' );
    }

	public function page_members() {
        include( plugin_dir_path( __FILE__ ) . 'partials/members.php' );
    }

	public function page_subscribers() {
        include( plugin_dir_path( __FILE__ ) . 'partials/subscribers.php' );
    }

	public function page_settings() {
        include( plugin_dir_path( __FILE__ ) . 'partials/settings.php' );
    }

	public function add_mime_types($mime_types){
		$mime_types['stl'] = 'application/sla';
		$mime_types['fbx'] = 'application/octet-stream';
		$mime_types['obj'] = 'text/plain';
		
		return $mime_types;
	}
	

	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function cpt_file() {

		$cap_type 	= 'post';
		$plural 	= 'Files';
		$single 	= 'File';
		$cpt_name 	= 'file';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= FALSE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-format-gallery';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'editor', 'thumbnail' );
		$opts['taxonomies']								= array();

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'nexus-aurora' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'nexus-aurora' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'nexus-aurora' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'nexus-aurora' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'nexus-aurora' );
		$opts['labels']['name']							= esc_html__( $plural, 'nexus-aurora' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'nexus-aurora' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'nexus-aurora' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'nexus-aurora' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'nexus-aurora' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent {$plural} :", 'nexus-aurora' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'nexus-aurora' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'nexus-aurora' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'nexus-aurora' );

		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'nexus-aurora' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'nexus-aurora-cpt-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	} // new_cpt_job()

	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function file_type() {

		$plural 	= 'File Types';
		$single 	= 'File Type';
		$tax_name 	= 'file_type';

		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= FALSE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';

		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';

		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'nexus-aurora' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'nexus-aurora' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'nexus-aurora' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'nexus-aurora' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'nexus-aurora');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'nexus-aurora' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'nexus-aurora' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'nexus-aurora' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'nexus-aurora' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'nexus-aurora' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'nexus-aurora' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'nexus-aurora' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'nexus-aurora' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'nexus-aurora' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'nexus-aurora' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'nexus-aurora' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'nexus-aurora' );

		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $tax_name ), 'nexus-aurora' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'nexus-aurora-taxonomy-options', $opts );

		register_taxonomy( $tax_name, 'file', $opts );

	} // new_taxonomy_type()

	/*
	function add_file_upload_meta_boxes() {
 
		// Define the custom attachment for posts
		add_meta_box(
			'file_attachments',
			'File',
			array( &$this, 'file_attachments' ),
			'file',
			'normal'
		);
	 
	} // end add_custom_meta_boxes

	function file_attachments() {

		if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['file_attachment_id'] ) ){
			update_option( 'file_attachment_id', absint( $_POST['material_attachment_id'] ) );
		}

		if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['material_attachment_id'] ) ){
			update_option( 'material_attachment_id', absint( $_POST['material_attachment_id'] ) );
		}

		wp_enqueue_media();

		$file_attachment_id = get_post_meta( 'file_attachment_id', 0 );
		$material_attachment_id = get_post_meta( 'material_attachment_id', 0 );
		
	?>
		<form method='post'>
			<div class='file-name-wrapper'></div>
			<input id="upload_file_button" type="button" class="button" value="<?php _e( 'Upload File' ); ?>" />

			<div class='image-preview-wrapper'>
				<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'material_attachment_id' ) ); ?>' style="max-width: 100%;">
			</div>
			<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload Material' ); ?>" />

			<input type='hidden' name='file_attachment_id' id='file_attachment_id' value='<?php echo get_option( 'file_attachment_id' ); ?>'>
			<input type='hidden' name='material_attachment_id' id='material_attachment_id' value='<?php echo get_option( 'material_attachment_id' ); ?>'>
			<input type="submit" name="submit_image_selector" value="Save" class="button-primary">
		</form>
		
		<script type='text/javascript'>
			jQuery( document ).ready( function( $ ) {
				// Uploading files
				var file_frame;
				
				var file_attachment_id = <?php echo $file_attachment_id; ?>; // Set this
				
				var material_frame;
				var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				var material_attachment_id = <?php echo $material_attachment_id; ?>; // Set this

				jQuery('#upload_file_button').on('click', function( event ){
					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						// Set the post ID to what we want
						file_frame.uploader.uploader.param( 'post_id', file_attachment_id );
						// Open frame
						file_frame.open();
						return;
					} else {
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = file_attachment_id;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Select a 3D file to upload',
						button: {
							text: 'Use this file',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();
						// Do something with attachment.id and/or attachment.url here
						$( '#file-name-wrapper' ).html( JSON.stringify(attachment) );
						$( '#file_attachment_id' ).val( attachment.id );
						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});

					// Finally, open the modal
					file_frame.open();
				});

				jQuery('#upload_image_button').on('click', function( event ){
					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( material_frame ) {
						// Set the post ID to what we want
						material_frame.uploader.uploader.param( 'post_id', material_attachment_id );
						// Open frame
						material_frame.open();
						return;
					} else {
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = material_attachment_id;
					}

					// Create the media frame.
					material_frame = wp.media.frames.material_frame = wp.media({
						title: 'Select a image to upload',
						button: {
							text: 'Use this image',
						},
						multiple: false // Set to true to allow multiple files to be selected
					});

					// When an image is selected, run a callback.
					material_frame.on( 'select', function() {
						// We set multiple to false so only get one image from the uploader
						attachment = material_frame.state().get('selection').first().toJSON();
						// Do something with attachment.id and/or attachment.url here
						$( '#image-preview' ).attr( 'src', attachment.url );
						$( '#material_attachment_id' ).val( attachment.id );
						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});

					// Finally, open the modal
					material_frame.open();
				});

				// // Restore the main ID when the add media button is pressed
				// jQuery( 'a.add_media' ).on( 'click', function() {
				// 	wp.media.model.settings.post.id = wp_media_post_id;
				// });
			});
		</script>
	<?php
	 
	} // end wp_custom_attachment
	*/
}
