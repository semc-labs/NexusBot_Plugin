<?php

/**
 * Fired during plugin activation
 *
 * @link       http://jboullion.com
 * @since      1.0.0
 *
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nexus_Aurora_Bot
 * @subpackage Nexus_Aurora_Bot/includes
 * @author     James Boullion <jboullion83@gmail.com>
 */
class Nexus_Aurora_Bot_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//Nexus_Aurora_Bot_Activator::subscribe_table_install();
	}

	// ! NOTE: Depricated for now. Moved table creation into discord bot. Saved for quick reference
	// protected static function subscribe_table_install() {
	// 	global $wpdb;
	// 	$na_db_version = 1.1;
		
	// 	$charset_collate = $wpdb->get_charset_collate();
	
	// 	$sql = "CREATE TABLE na_subscribers (
	// 		subscriber_id INT(11) NOT NULL AUTO_INCREMENT,
	// 		email VARCHAR(255) NOT NULL,
	// 		active TINYINT(1) NOT NULL DEFAULT 1,
	// 		from_discord TINYINT(1) NOT NULL DEFAULT 0,
	// 		subscribed_at DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	// 		PRIMARY KEY  (subscriber_id),
	// 		UNIQUE KEY `email` (`email`)
	// 	) $charset_collate;";
	
	// 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	// 	dbDelta( $sql );
	
	// 	add_option( 'na_db_version', $na_db_version );
	// }


}
