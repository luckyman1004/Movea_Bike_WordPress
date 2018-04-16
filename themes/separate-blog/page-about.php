<?php
/**
 * Template Name: About us page template
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
<section class="intro">
	<div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <h1>About US</h1>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-6 col-sm-12 col-md-6 col-xs-12 img-wrapper">
                <img class="about-thumbnail" src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"/>
            </div>
			<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12">
				
                <div class="abount-content">
                    <?php the_content();?>
                </div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer();

