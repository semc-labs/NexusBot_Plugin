<?php
/**
 * The template for displaying projects archive.
 *
 * @package Nexus_Aurora
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

get_header('nexus');
the_post();

$project_page = get_post(253);
$featured_image_url = get_the_post_thumbnail_url( $project_page->ID, 'full' );

?>
<div id="nexus-featured" class="cover-background" style="background-image: url(<?php echo $featured_image_url; ?>)" >
	<div class="nexus-container">
		<h1><?php echo $project_page->post_title; ?></h1>
	</div>
</div>
<?php 
get_footer('nexus');