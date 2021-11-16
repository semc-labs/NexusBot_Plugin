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
	<div class="nexus-container row">
		<div id="project-single__menu" class="col-lg-4">

		<div class="social">
			<a href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $URL;?>&picture=<?php echo $featured_image_url; ?>" target="_blank"><i class="fa fa-facebook-square"></i></a>
			<a href="https://twitter.com/share?text=<?php echo $post->post_title;?>&url=<?php echo $URL;?>" target="_blank"><i class="fa fa-twitter-square"></i></a>
			<a href="https://www.linkedin.com/shareArticle?url=<?php echo $URL;?>&title=<?php echo $post->post_title;?>" target="_blank"><i class="fab fa-linkedin"></i></a>
			<a href="https://www.reddit.com/submit?url=<?php echo $URL;?>&title=<?php echo $post->post_title;?>" target="_blank"><i class="fab fa-reddit"></i></a>
			<a href="mailto:?subject=<?php echo $post->post_title;?>&body=<?php echo $URL;?>" target="_blank"><i class="fas fa-envelope"></i></a>
		</div>

			<?php 
				
				echo do_shortcode( '[na-discord channelid="866066557331570710" invite="https://discord.gg/wP2N4WYANQ"][/na-discord]' );

				$arr = array(
					'post_parent'    => $post->ID,
					'post_type'      => 'project',
				);
			
				$child_projects = get_children($arr);

				if(! empty($child_projects)){
					echo '<ul>';
					foreach($child_projects as $child_project){
						echo '<li>
										<a href="'.get_the_permalink($child_project->ID).'">
										'.$child_project->post_title.'
										</a>
									</li>';
					}
					echo '</ul>';
				}

				echo do_shortcode( '[na-calendar calendarid="jboullion85@gmail.com" apikey="AIzaSyCqKZfmB9zsw74xh1ScF0P8EN980_aJzFQ" search="event"][/na-calendar]' );
				echo do_shortcode( '[na-drive-folder id="1XeCYXUdkiad1QFmwTbKbUJsTu3xiVPKn"][/na-drive-folder]' );

			?>
		</div>
		<div id="project-single__content">
			<?php 
				the_content();
			?>
		</div>
	</div>
</div>
<script>
	jQuery(function($) {
		
	});
</script>
<?php 
get_footer('nexus');