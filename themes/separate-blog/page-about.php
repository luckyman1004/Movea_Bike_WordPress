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
                <h1>Anders Hermansen</h1>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-6 col-sm-12 col-md-6 col-xs-12">
                <img class="about-thumbnail" src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"/>
            </div>
			<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12">
				
                <div class="abount-content">
                    <p>Industrial Designer Anders Hermansen graduated as a young student from the Royal Danish Academy of Fine Arts, Copenhagen in 1982. Mastering industrial design and furniture architecture.</p>
                    <p>As a recent graduate, he began working with leadign Danish design companies such Paustian and Louis Poulsen.</p>
                    <p>Since the early 1980’s Anders Hermansen has also worked closely with the audio-visual company Bang & Olofsen (B&O) for decades.Other close design partnerships includes amonngst others LG Electronics and Engelbrechts Furniture.</p>
                </div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer();

