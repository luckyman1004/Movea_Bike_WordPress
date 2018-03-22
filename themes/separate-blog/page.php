<?php
/**
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
                <h1><?php the_title();?></h1>
            </div>
        </div>
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
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
