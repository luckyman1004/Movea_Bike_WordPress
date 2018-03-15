<?php
/**
 * Template Name:Coming Soon page template
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Separate_Blog
 */


get_header(); ?>

<section class="intro newsletter-landing coming-soon" style="background: url( <?php echo esc_url( get_the_post_thumbnail_url() ); ?> ); background-size: cover; background-position: center center">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-wrapper">
                <h1>Under Construction <br> The most cool E bikes <br> Stay turned</h1>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
