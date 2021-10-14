<?php
/**
 * The template for displaying all file posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Nexus_Aurora
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$meta = get_post_custom( $post->ID );
if(function_exists('get_fields')){
	$fields = get_fields( $post->ID );
}

get_header();
the_post();
?>
<div class="nexus-container">

<?php
	echo '<h2 class="text-center">'.$post->post_title.'</h2>';

	the_content();

	if(! empty($fields['3d_files'])){

		echo '<div id="viewer-wrapper">
				<babylon id="babylon-viewer"  model="'.$fields['3d_files'][0]['file']['url'].'" templates.main.params.fill-screen="true">
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

		// TODO: Get bootstrap or something and turn these into cards? Or some other more responsive layout
		// echo '<table>
		// 		<thead>
		// 			<tr>
		// 				<th>Name</th>
		// 				<th>Size</th>
		// 				<th>View</th>
		// 				<th>Download</th>
		// 			</tr>
		// 		</thead>
		// 		<tbody>';

		echo '<h3>Available Files</h3>';

		echo '<div class="download-list">';

		foreach($fields['3d_files'] as $file){
			// echo '<tr>
			// 		<td>'.$file['file']['title'].'</td>
			// 		<td class="text-center">'.Nexus_Aurora_Globals::convert_bytes( $file['file']['filesize'] ).'</td>
			// 		<td class="text-center"><a href="'.$file['file']['url'].'" class="view-model" >View</a></td>
			// 		<td class="text-center"><a href="'.$file['file']['url'].'" download>Download</a></td>
			// 	</tr>';

			echo '<div class="download">
					<h6>'.$file['file']['title'].'</h6>
					<div class="download-info">
						<span>'.Nexus_Aurora_Globals::convert_bytes( $file['file']['filesize'] ).'</span>
						<a href="'.$file['file']['url'].'" class="view-model" >View</a>
						<a href="'.$file['file']['url'].'" download>Download</a>
					</div>
				</div>';
		}

		echo '</div>';

		//echo '</tbody></table>';

	}
?>
</div>
<script>
	(function($) {
		BabylonViewer.viewerManager.getViewerPromiseById('babylon-viewer').then(function (viewer) {
			$('.view-model').click(function(e){
				e.preventDefault();
				
				viewer.loadModel({
					url: $(this).attr('href')
				});
			});
		});
	})( jQuery );
</script>
<?php 
get_footer();