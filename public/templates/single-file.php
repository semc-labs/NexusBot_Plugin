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
<div class="container">

<?php
	if(! empty($fields['3d_files'])){
		//pre_print($fields['3d_files']);
		echo '<h3>'.$post->post_title.'</h3>';

		echo '<div id="viewer-wrapper">
				<babylon id="babylon-viewer"  model="'.$fields['3d_files'][0]['file']['url'].'" templates.main.params.fill-screen="true">
					<scene debug="false" render-in-background="true" disable-camera-control="false">
						<main-color r="0.3" g="0.3" b="0.3"></main-color>
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

		echo '<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Size</th>
						<th>View</th>
						<th>Download</th>
					</tr>
				</thead>
				<tbody>';
		
		foreach($fields['3d_files'] as $file){
			echo '<tr>
					<td>'.$file['file']['title'].'</td>
					<td class="text-center">'.Nexus_Aurora_Globals::convert_bytes( $file['file']['filesize'] ).'</td>
					<td class="text-center"><button type="button" class="view-model" value="'.$file['file']['url'].'">View</button></td>
					<td class="text-center"><a href="'.$file['file']['url'].'" download>Download</a></td>
				</tr>';
		}

		echo '</tbody></table>';

	}
?>
</div>
<script src="https://cdn.babylonjs.com/viewer/babylon.viewer.js"></script>
<script>
	(function($) {
		
		console.log('Testing');
		

		BabylonViewer.viewerManager.getViewerPromiseById('babylon-viewer').then(function (viewer) {
			$('.view-model').click(function(e){
				console.log('Testing Click');
				//$('#viewer-wrapper').html('<babylon model="'+$(this).val()+'"></babylon>');
				viewer.loadModel({
					url: $(this).val()
				});
			});
		});
	})( jQuery );
</script>
<?php 
get_footer();