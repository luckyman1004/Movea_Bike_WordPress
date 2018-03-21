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
            <div class="col-lg-6 col-sm-12 col-md-6 col-xs-12">
                <img class="about-thumbnail" src="<?php echo esc_url( get_the_post_thumbnail_url() ); ?>"/>
            </div>
			<div class="col-lg-6 col-sm-12 col-md-6 col-xs-12">
				
                <div class="abount-content">
                    <p>Hi! I’m Lars Andersen, founder of Movea.  It’s my mission to inspire people to incorporate cycling into their everyday lives by creating a more exciting and efficient way to explore your town.  It’s not about how far and fast you ride or how may calories you burn, it’s about the fun you have along the way.  My passion and love for ergonomics, style and, ultimately, your smile is what keeps me going.   </p>
                    <p>Born and raised in Copenhagen, Denmark, been in the bicycle business for more than 25 years, first owning shops and now designing and manufacturing bicycles.  Although I’ve spent much of my life around bikes. </p>
                    <p>With the new Movea line, we started with a clean slate and distilled all of our design ideas, experience and knowledge from the last two decades into a completely new line of bikes.  Danish designer Anders Hermansen, formerly a designer for Bang and Olufsen, worked with us to create a bicycle with the perfect balance of comfort, style, performance, quality and affordability.  </p>
                    <p>Our aim is to keep Movea bikes clean and simple but highly functional.  To do this, we’ve incorporated practical rack and cargo solutions and more gears, for a sporty yet smooth and comfortable ride.  Some of our bikes also have electric systems to give you an easy (sweat free!) ride when traveling to work or school.  </p>
                    <p>Movea is designed to go anywhere with you.  Compact and extremely light, it’s an extraordinarily slim and elegant bicycle that will stand out in a crowd.  But we kept much more than easy storage and looking good in mind.  </p>
                    <p>Movea’s small wheels are specially designed for riding in traffic.  Because smaller wheels weigh less, they allow for faster acceleration and climb better than larger wheels – a major plus when making frequent stops.  Small wheels are also more responsive to steering, creating a more maneuverable bike. </p>
                    <p>I’m excited to share my new collection with you.  I hope the new Movea bikes inspire you to get out there and ride.  </p>
                </div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php get_footer();

