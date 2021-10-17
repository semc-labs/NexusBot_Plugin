<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://jboullion.com
 * @since      1.0.0
 *
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/includes
 * @author     James Boullion <jboullion83@gmail.com>
 */
class Nexus_Aurora_Bot {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nexus_Aurora_Bot_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The url to query our discord bot.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $bot_url    url to query our discord bot.
	 */
	protected $bot_url;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'NEXUS_AURORA_PLUGIN_VERSION' ) ) {
			$this->version = NEXUS_AURORA_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'nexus-aurora-bot';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Nexus_Aurora_Bot_Loader. Orchestrates the hooks of the plugin.
	 * - Nexus_Aurora_Bot_i18n. Defines internationalization functionality.
	 * - Nexus_Aurora_Bot_Admin. Defines all hooks for the admin area.
	 * - Nexus_Aurora_Bot_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nexus-aurora-bot-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nexus-aurora-bot-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nexus-aurora-bot-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nexus-aurora-bot-public.php';

		/**
		 * The class responsible for all global functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/nexus-aurora-global-functions.php';


		$this->loader = new Nexus_Aurora_Bot_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nexus_Aurora_Bot_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Nexus_Aurora_Bot_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Nexus_Aurora_Bot_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_bot_url() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );

		$this->loader->add_action( 'init', $plugin_admin, 'cpt_file' );
		$this->loader->add_action( 'init', $plugin_admin, 'file_type' );

		//$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_file_upload_meta_boxes' );
		$this->loader->add_action( 'upload_mimes', $plugin_admin, 'add_mime_types' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Nexus_Aurora_Bot_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'the_content', $plugin_public, 'alter_the_content' );

		$this->loader->add_filter( 'single_template', $plugin_public, 'single_cpt_template' );

		$this->loader->add_shortcode( 'na-viewer',  $plugin_public, 'na_viewer' );
		$this->loader->add_shortcode( 'na-subscribe',  $plugin_public, 'na_subscribe' );

		$this->loader->add_action( 'admin_post_subscribe_form', $plugin_public, 'subscribe_submission' );
		$this->loader->add_action( 'admin_post_nopriv_subscribe_form', $plugin_public, 'subscribe_submission' );


	}


	/**
	 * Register all of the hooks related to the templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {

		// $plugin_templates = new Nexus_Aurora_Template_Functions( $this->get_plugin_name(), $this->get_version() );

		// // Loop
		// $this->loader->add_action( 'now-hiring-before-loop', $plugin_templates, 'list_wrap_start', 10 );
		// $this->loader->add_action( 'now-hiring-before-loop-content', $plugin_templates, 'content_wrap_start', 10, 2 );
		// $this->loader->add_action( 'now-hiring-before-loop-content', $plugin_templates, 'content_link_start', 15, 2 );
		// $this->loader->add_action( 'now-hiring-loop-content', $plugin_templates, 'content_job_title', 10, 2 );
		// $this->loader->add_action( 'now-hiring-after-loop-content', $plugin_templates, 'content_link_end', 10, 2 );
		// $this->loader->add_action( 'now-hiring-after-loop-content', $plugin_templates, 'content_wrap_end', 90, 2 );
		// $this->loader->add_action( 'now-hiring-after-loop', $plugin_templates, 'list_wrap_end', 10 );

		// // Single
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_title', 10 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_content', 15 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_responsibilities', 20 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_location', 25 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_education', 30 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_skills', 35 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_experience', 40 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_info', 45 );
		// $this->loader->add_action( 'now-hiring-single-content', $plugin_templates, 'single_post_file', 50 );
		// $this->loader->add_action( 'now-hiring-after-single', $plugin_templates, 'single_post_how_to_apply', 10 );

	} // define_template_hooks()

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nexus_Aurora_Bot_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_bot_url() {
		return $this->bot_url;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
