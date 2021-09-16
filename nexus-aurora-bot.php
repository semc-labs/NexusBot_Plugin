<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://jboullion.com
 * @since             0.1.0
 * @package           Nexus_Aurora_Bot
 *
 * @wordpress-plugin
 * Plugin Name:       Nexus Aurora Bot
 * Plugin URI:        http://jboullion.com/nexus-aurora-bot
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.1.0
 * Author:            James Boullion
 * Author URI:        http://jboullion.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nexus-aurora-bot
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'NEXUS_AURORA_BOT_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nexus-aurora-bot-activator.php
 */
function activate_nexus_aurora_bot() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nexus-aurora-bot-activator.php';
	Nexus_Aurora_Bot_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nexus-aurora-bot-deactivator.php
 */
function deactivate_nexus_aurora_bot() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nexus-aurora-bot-deactivator.php';
	Nexus_Aurora_Bot_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nexus_aurora_bot' );
register_deactivation_hook( __FILE__, 'deactivate_nexus_aurora_bot' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nexus-aurora-bot.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nexus_aurora_bot() {

	$plugin = new Nexus_Aurora_Bot();
	$plugin->run();

}
run_nexus_aurora_bot();

function pre_print($data){
	echo '<pre>'.print_r($data, true).'</pre>';
}