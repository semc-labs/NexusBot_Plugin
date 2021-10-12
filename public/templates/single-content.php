<?php
/**
 * The template for displaying all single job posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Now_Hiring
 */

$meta = get_post_custom( $post->ID );
$fields = get_fields( $post->ID );

pre_print($fields);

/**
 * nexus-aurora-before-single hook
 */
do_action( 'nexus-aurora-before-single', $meta );

?><div class="wrap-file"><?php

	/**
	 * nexus-aurora-before-single-content hook
	 */
	do_action( 'nexus-aurora-before-single-content', $meta );

	/**
	 * nexus-aurora-single-content hook
	 */
	do_action( 'nexus-aurora-single-content', $meta );

	/**
	 * nexus-aurora-after-single-content hook
	 */
	do_action( 'nexus-aurora-after-single-content', $meta );

?></div><!-- .wrap-employee --><?php

/**
 * nexus-aurora-after-single hook
 */
do_action( 'nexus-aurora-after-single', $meta );