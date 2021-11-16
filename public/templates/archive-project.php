<?php
/**
 * The template for displaying projects archive.
 *
 * @package Nexus_Aurora
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

get_header('nexus');

$project_page = get_post(253);
$featured_image_url = get_the_post_thumbnail_url( $project_page->ID, 'full' );

?>
<div id="nexus-featured" class="cover-background" style="background-image: url(<?php echo $featured_image_url; ?>)" >
	<div class="nexus-container">
		<h1><?php echo $project_page->post_title; ?></h1>
	</div>
</div>
<div id="project-archive" class="nexus-container row">
	<div id="project-archive__menu">
		<p><strong>Project Categories</strong></p>
		<?php 
			$project_categories = get_terms( 'project_category' );

			if(! empty($project_categories)){
				echo '<ul>';
				foreach($project_categories as $project_category){
					echo '<li>
								<label for="product-categories-'.$project_category->term_id.'">
								'.$project_category->name.' <input type="checkbox" id="product-categories-'.$project_category->term_id.'" name="product_categories[]" value="'.$project_category->term_id.'" />
								</label>
							</li>';
				}
				echo '</ul>';
			}
		?>
	</div>
	<div id="project-archive__listing">
		<?php 
			if ( have_posts() ) {
				echo '<div class="row">';
				while ( have_posts() ) {
					the_post();

					if($post->post_parent) continue;
					
					$fields = get_fields($post->ID);
					
					$project_cats = get_the_terms( $post->ID, 'project_category' );

					$project_image_url = get_the_post_thumbnail_url( $post->ID, 'medium' );
					
					$cat_string = '';
					if(! empty($project_cats)){
						foreach($project_cats as $project_cat){
							$cat_string .= ' cat-'.$project_cat->term_id;
						}
					}

					echo '<div class="projects col col-sm-6 col-lg-4 mb-4 '.$cat_string.'">
									<a href="'.get_the_permalink($post->ID).'" class="card text-center">
										<img src="'.$project_image_url.'" class="card-img-top" alt="">
										<div class="card-body">
											<h5 class="card-title">'.$post->post_title.'</h5>
											<p class="card-text">'.($fields['description']??'').'</p>
										</div>
									</a>
								</div>';
					
					
				}
				echo '</div>';
			}
		?>
	</div>
</div>
<script>
	jQuery(function($) {
		$filterLabels = $('#project-archive__menu label');
		$filters = $('#project-archive__menu input');
		$projects = $('.projects');
		$projectListing = $('#project-archive__listing');

		$filters.change(function(e){
			var $selectedFilters = $filterLabels.find('input:checked');
			
			var selectedFilters = [];
			$selectedFilters.each(function(){ selectedFilters.push($(this).val()); });
			console.log('selectedFilters  ', selectedFilters )

			if(selectedFilters.length){
				$projects.hide();
				selectedFilters.forEach(cat => $projectListing.find('.cat-'+cat).show() )
				
			}else{
				$projects.show();
			}
		});
	});
</script>
<?php 
get_footer('nexus');