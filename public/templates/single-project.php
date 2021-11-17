<?php
/**
 * The template for displaying projects archive.
 *
 * @package Nexus_Aurora
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

get_header('nexus');
the_post();

$featured_image_url = get_the_post_thumbnail_url( $post->ID, 'full' );

$fields = get_fields( $post->ID );

$URL = get_the_permalink();
?>
<div id="project-single" />
	<div id="nexus-featured">
		<div class="nexus-container">
			<div class="row align-center">
				<div class="col"><img src="<?php echo $featured_image_url; ?>" width="200" alt="" /></div>
				<div class="col "><h1><?php echo $post->post_title; ?></h1></div>
			</div>
		</div>
	</div>
	<div class="nexus-container">
		<div class="row">
			<div id="project-single__content" class="col">
				<?php 
					the_content();

					if(! empty($fields['3d_files'])){
						echo do_shortcode( '[na-viewer-list][/na-viewer-list]' );
					}
				?>
			</div>
			<div id="project-single__menu" class="col col-lg-4">

				<div class="social">
					<a class="facebook" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $URL;?>&picture=<?php echo $featured_image_url; ?>" target="_blank"><i class="fa fa-facebook-square"></i></a>
					<a class="twitter" href="https://twitter.com/share?text=<?php echo $post->post_title;?>&url=<?php echo $URL;?>" target="_blank"><i class="fa fa-twitter-square"></i></a>
					<a class="linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $URL;?>" target="_blank"><i class="fab fa-linkedin"></i></a>
					<a class="reddit" href="https://www.reddit.com/submit?url=<?php echo $URL;?>&title=<?php echo $post->post_title;?>" target="_blank"><i class="fab fa-reddit"></i></a>
					<a class="email" href="mailto:?subject=<?php echo $post->post_title;?>&body=<?php echo $URL;?>" target="_blank"><i class="fas fa-envelope"></i></a>
				</div>

				<?php 
					$arr = array(
						'post_parent'    => $post->ID,
						'post_type'      => 'project',
						'orderby'				=> 'title',
						'order'					=> 'ASC',
					);
				
					$child_projects = get_children($arr);

					if(! empty($child_projects)){
						echo '<div class="child-projects">
									<p>Related Projects</p>
									<ul>';

						foreach($child_projects as $child_project){
							echo '<li>
											<a href="'.get_the_permalink($child_project->ID).'">
											'.$child_project->post_title.'
											</a>
										</li>';
						}
						echo '</ul>
								</div>';
					}

					if(! empty($fields['channel_invite'])){
						echo do_shortcode( '[na-discord channelid="'.$fields['channel_id'].'" invite="'.$fields['channel_invite'].'"][/na-discord]' );
					}

					if(! empty($fields['calendar_filter'])){
						echo do_shortcode( '[na-calendar filter="'.$fields['calendar_filter'].'"][/na-calendar]' );
					}

					if(! empty($fields['folder_id'])){
						echo do_shortcode( '[na-drive-folder id="'.$fields['folder_id'].'"][/na-drive-folder]' );
					}

				?>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery(function($) {
		
	});
</script>
<?php 
get_footer('nexus');