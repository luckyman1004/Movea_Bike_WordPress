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
<div class="logo-section" >
    <img src="http://movea.bike/wp-content/uploads/2018/03/logo_white_small.png"  />
    <div class="social-links" style="text-align: right; width: 100%;">
        <ul>
            <li><a href="https://www.facebook.com/MoveaBikes/" target="_blank">Facebook</a></li>
            <li><a href="https://www.instagram.com/moveabikes/" target="_blank">Instagram</a></li>
        </ul>
    </div>
</div>

<?php if( have_posts() && $post->post_content ) : ?>
<section class="intro newsletter-landing coming-soon" style="background: url( <?php echo esc_url( get_the_post_thumbnail_url() ); ?> ); background-size: cover; background-position: center center">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-wrapper">
                <h4>Stay turned</h4>
                <h1>We are working on the site. Will be back later today <br></h1>
                <!-- <h3>The most stylish E bikes</h3> -->
                <?php 
					// while ( have_posts() ) : the_post();
					// 	the_content(); 
					// endwhile;
                ?>
                <div class="copyright">
                    Copyright &copy; 2018 &nbsp;&nbsp;&middot;&nbsp;&nbsp;movea bikes
                </div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer();
