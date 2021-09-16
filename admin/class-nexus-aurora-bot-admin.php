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

	public function page_settings() {
        include( plugin_dir_path( __FILE__ ) . 'partials/settings.php' );
    }
	
}
