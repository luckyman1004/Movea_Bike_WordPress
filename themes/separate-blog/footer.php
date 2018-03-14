<?php
/**
* The template for displaying the footer
*
* Contains the closing of the #content div and all content after.
*
* @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* @package Separate_Blog
*/
?>
<footer class="main-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="logo">
					<h6 class="text-white"><?php esc_html_e( 'Address', 'separate-blog' ); ?></h6>
				</div>
				<div class="contact-details">
					<?php echo esc_textarea( wpautop( get_theme_mod( 'separate_blog_address' ), true ) ); ?>
					<p><?php echo sprintf( 'Email: %s', separate_blog_admin_email() ); ?></p>
					<ul class="social-menu">
						<?php if ( get_theme_mod( 'separate_blog_facebook' ) ): ?>
							<li class="list-inline-item">
								<a href="<?php echo esc_url( get_theme_mod( 'separate_blog_facebook' ) ); ?>"><i class="fa fa-facebook"></i></a>
							</li>
						<?php endif;?>

						<?php if ( get_theme_mod( 'separate_blog_twitter' ) ): ?>
							<li class="list-inline-item">
								<a href="<?php echo esc_url( get_theme_mod( 'separate_blog_twitter' ) ); ?>"><i class="fa fa-twitter"></i></a>
							</li>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'separate_blog_google_plus' ) ): ?>
							<li class="list-inline-item">
								<a href="<?php echo esc_url( get_theme_mod( 'separate_blog_google_plus' ) ); ?>"><i class="fa fa-google-plus"></i></a>
							</li>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'separate_blog_instagram' ) ): ?>
							<li class="list-inline-item">
								<a href="<?php echo esc_url( get_theme_mod( 'separate_blog_instagram' ) ); ?>"><i class="fa fa-instagram"></i></a>
							</li>
						<?php endif;?>
						<?php if ( get_theme_mod( 'separate_blog_youtube' ) ): ?>
						<li class="list-inline-item">
							<a href="<?php echo esc_url( get_theme_mod( 'separate_blog_youtube' ) ); ?>"><i class="fa fa-youtube"></i></a>
						</li>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'separate_blog_pinterest' ) ): ?>
							<li class="list-inline-item">
								<a href="<?php echo esc_url( get_theme_mod( 'separate_blog_pinterest' ) ); ?>"><i class="fa fa-pinterest"></i></a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="col-md-4">
				<div class="menus d-flex">
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'ul', 'menu_class' => 'list-unstyled' ) ); ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="latest-posts">
				<?php dynamic_sidebar( 'newsletter' );?>
				</div>
			</div>
		</div>
	</div>
		<div class="copyrights">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
					
					</div>
				</div>
			</div>
		</div>
	</footer>
</div><!-- #content -->
<?php wp_footer(); ?>
</body>
</html>
