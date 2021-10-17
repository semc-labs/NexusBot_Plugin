<?php
/**
 * Globally-accessible functions
 *
 * @link 		https://github.com/slushman/now-hiring/
 * @since 		1.0.0
 *
 * @package		Nexus_Aurora
 * @subpackage 	Nexus_Aurora/includes
 */
function na_get_template( $name ) {

	return Nexus_Aurora_Globals::get_template( $name );

}

class Nexus_Aurora_Globals {

 	/**
	 * Returns the path to a template file
	 *
	 * Looks for the file in these directories, in this order:
	 * 		Current theme
	 * 		Parent theme
	 * 		Current theme templates folder
	 * 		Parent theme templates folder
	 * 		This plugin
	 *
	 * To use a custom list template in a theme, copy the
	 * file from public/templates into a templates folder in your
	 * theme. Customize as needed, but keep the file name as-is. The
	 * plugin will automatically use your custom template file instead
	 * of the ones included in the plugin.
	 *
	 * @param 	string 		$name 			The name of a template file
	 * @return 	string 						The path to the template
	 */
 	public static function get_template( $name ) {

 		$template = '';

		$locations[] = "{$name}.php";
		$locations[] = "/templates/{$name}.php";

		/**
		 * Filter the locations to search for a template file
		 *
		 * @param 	array 		$locations 			File names and/or paths to check
		 */
		//apply_filters( 'nexus-aurora-template-paths', $locations );

		$template = locate_template( $locations, TRUE );

		if ( empty( $template ) ) {

			$template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/' . $name . '.php';

		}

		return $template;

 	} // get_template()

	public static function convert_bytes($bytes, $decimal_places = 1) {
		$kb = 1024;
		$mb = 1048576;
		$gb = 1073741824;
		if($bytes < $mb) return number_format($bytes / $kb, $decimal_places).'KB';
		if($bytes < $gb) return number_format($bytes / $mb, $decimal_places).'MB';
		return number_format($bytes / $gb, $decimal_places).'GB';
	}

	public static function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && Nexus_Aurora_Globals::in_array_r($needle, $item, $strict))) {
				return $item;
			}
		}
	
		return false;
	}

} // class