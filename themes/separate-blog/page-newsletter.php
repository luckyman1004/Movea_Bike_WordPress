<?php
/**
 * Template Name: Newsletter page template
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
<?php if( have_posts() && $post->post_content ) : ?>
<section class="intro newsletter-landing" style="background: url( <?php echo esc_url( get_the_post_thumbnail_url() ); ?> ); background-size: cover; background-position: center center">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-wrapper">
                <h1>New danish designed E bikes</h1>
                <h3>Get up to 40%</h3>
                <h4>The New Movea bike will soon launch on Indiegogo</h4>
				<?php 
					while ( have_posts() ) : the_post();
						the_content(); 
					endwhile;
				?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer();
