<?php
/**
 * Template Name: Homepage
 * Template part for displaying Homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Separate_Blog
 */
get_header(); ?>
    <div class="lifestyle-gallery" id="lifestyle_gallery" style="display: none;"><div class="lifestyle-close">&times;</div></div>
    <div class="main-container">
        <div id="videoSection">
            <div class="container-movea moveaVideo" data-astop="true">
                <div class="text textCenter">
                    <!-- <div class="toptext"><?php echo get_option_tree('top_text');?></div> -->
                    <div class="titletext">
                        <p>
                        
                        </p>
                    </div>
                </div>
                <div class="mobiletextWrapper">
                    <div class="mobiletext">
                        <!-- <div class="toptext">
                            <?php echo get_option_tree('top_text');?>
                        </div> -->
                        <div class="titletext">
                            <p>
                                
                            </p>
                        </div>
                    </div>
                </div>
                <div class="moveaVideoPlayer center fadeIn" id='moveaVideoPlayer'>
                    <div class="playBtn" data-youtube_id="<?php echo get_option_tree('banner_youtube_video_id');?>">
                        <div class="playIcon"></div>
                    </div>
                    <div class="moveaVideoThumbnail">
                        <img class="lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/33-1024x684.png" style="width: 100%; "  />
                    </div>
                    <!-- <video class="moveaVideoThumbnail" id="moveaVideoThumbnail" preload="auto" loop autoplay muted>
                        <source type="video/mp4" src="<?php //echo get_option_tree('banner_video');?>">
                    </video> -->
                </div>
            </div>
        </div>
        <div class="countdowntimer" id="clock"></div>
        <div id="TextSpot2Column">
            <div class="moveaSplitText" >
                <h2><?php echo get_option_tree('title_movea_design');?></h2>
                <div class="paragraphWrap">
                    <?php echo get_option_tree('left_block_movea_design');?>
                    <?php echo get_option_tree('right_block_movea_design');?>
                </div>
                <h3><a href="#products" style="color: inherit;"><?php echo get_option_tree('bottom_link_movea_design');?></a></h3>
            </div>
        </div>
        <div class="nextgenspots">
            <?php for( $i = 1; $i < 4; $i ++) { 
                $post_id = get_option_tree($i.'_product_featured');
                $product = get_post($post_id);
                $product_image1 = get_field('image1', $post_id);
                $product_image2 = get_field('image2', $post_id);
                $product_image3 = get_field('image3', $post_id);
                $product_image4 = get_field('image4', $post_id);
                $product_image5 = get_field('image5', $post_id);
                $product_image6 = get_field('image6', $post_id);
                $product_image7 = get_field('image7', $post_id);
            ?>
            <div class="moveaSpot multiple" data-link="<?php echo esc_url(get_page_link($post_id));?>">
                <div class="linkImg">
                    <div class="carousel_container" id="featured_product_<?php echo $post_id;?>">       
                        <div class="carousel_items">        
                            <ul>
                                <?php if(get_field('image1', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_0"><img class="lazyload" data-src="<?php echo $product_image1['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                                <?php if(get_field('image2', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_1"><img class="lazyload" data-src="<?php echo $product_image2['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                                <?php if(get_field('image3', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_2"><img class="lazyload" data-src="<?php echo $product_image3['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                                <?php if(get_field('image4', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_3"><img class="lazyload" data-src="<?php echo $product_image4['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                                <?php if(get_field('image5', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_4"><img class="lazyload" data-src="<?php echo $product_image5['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                                <?php if(get_field('image6', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_5"><img class="lazyload" data-src="<?php echo $product_image6['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                                <?php if(get_field('image7', $post_id) != '') { ?>
                                <li class="carousel_item" id="item_6"><img class="lazyload" data-src="<?php echo $product_image7['sizes']['separate-blog-post-thumbnail'];?>" /></li>
                                <?php }?>
                            </ul>
                        </div>
                        <div class="nav_dots"></div>
                    </div>
                </div>
                <div class="info">
                    <h4><?php echo $product->post_title;?></h4>
                    <div class="priceAndInfo">
                        <p><?php echo $product->post_excerpt;?></p>
                        <ul>
                            <li>Weight: <?php the_field('weight', $post_id);?></li>
                            <li>Motor: <?php the_field('motor', $post_id);?></li>
                            <li>Batteri: <?php the_field('batteri', $post_id);?></li>
                            <li>Max speed: <?php the_field('max_speed', $post_id);?></li>
                        </ul>
                        <!-- <div class="price" style="color:#000;">
                            <span class="currency">USD</span><span class="amount"><?php the_field('price', $post_id);?></span>
                        </div> -->
                        <div>
                            <a href="<?php echo esc_url(get_page_link($post_id));?>" style="color:#fff;text-decoration: underline;font-weight:100;">More info</a>
                        </div>
                        <!-- <a class="soon-indiegog btn" style="padding: 10px 25px!important;" href="javascript:;">Soon on Indiegogo</a> -->
                        <!-- <a href="https://www.indiegogo.com/projects/movea-the-self-charging-design-e-bike/coming_soon/pica" class="btn" data-toggle="tooltip" data-placement="top" title=" Buy on Indiegogo   Safe up to 53%" >
                            <div class="left"></div>
                            <div class="right" style="color: #000;">
                                Buy at Indiegogo
                            </div>
                        </a> -->
                    </div>
                </div>
            </div>
            <?php }?>
            <!-- <div class="moveaSpot multiple" style="background-color: #A6A9AA;">
                <div class="linkImg">
                    <div class="carousel_container"  id="second">       
                        <div class="carousel_items">        
                            <ul>
                                <li class="carousel_item" id="item_0"><img src="./assets/images/featured_products/movea-model-e-20-lady-1.png" /></li>
                                <li class="carousel_item" id="item_1"><img src="./assets/images/featured_products/movea-model-e-20-lady-2.png" /></li>
                            </ul>
                        </div>
                        <div class="nav_dots"></div>
                    </div>
                </div>
                <div class="info" >
                    <h4 style="color:#fff;">Movea Model E 20" Lady</h4>
                    <div class="priceAndInfo" style="color:#fff;">
                        <p>Enjoy an incredible range of up to 80 KM, and recharge on the go. </p>
                        <ul>
                            <li>Weight: 12 kg</li>
                            <li>Motor: 250 W </li>
                            <li>Batteri: 30 V L-ion 160Wh </li>
                            <li>Max speed: 25 km/h max</li>
                        </ul>
                        <div class="price" style="color:#fff;">
                            <span class="currency">USD</span><span class="amount">299</span>
                        </div>
                        <a href="/en/products/beoplayh4" class="btn">
                            <div class="left" style="background-image: url('./assets/images/beobasket_black.png');"></div>
                            <div class="right" style="color: #000;">
                                Buy Bike
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="moveaSpot multiple" style="background-color: #313234;">
                <div class="linkImg">
                    <div class="carousel_container"  id="third">       
                        <div class="carousel_items">        
                            <ul>
                                <li class="carousel_item" id="item_0"><img src="./assets/images/featured_products/movea-model-e-24-men-1.png" /></li>
                                <li class="carousel_item" id="item_1"><img src="./assets/images/featured_products/movea-model-e-24-men-2.png" /></li>
                            </ul>
                        </div>
                        <div class="nav_dots"></div>
                    </div>
                </div>
                <div class="info">
                    <h4 style="color:#fff;">Movea Model E 24" Men</h4>
                    <div class="priceAndInfo" style="color:#fff;">
                        <p>Model E includes a Continental Carbon Belt drive. 250W Zehus All in One electric hub motor totally wireless. </p>
                        <ul>
                            <li>Weight: 12 kg</li>
                            <li>Motor: 250 W </li>
                            <li>Batteri: 30 V L-ion 160Wh </li>
                            <li>Max speed: 25 km/h max</li>
                        </ul>
                        <div class="price" style="color:#fff;">
                            <span class="currency">USD</span><span class="amount">299</span>
                        </div>
                        <a href="/en/products/beoplayh4" class="btn">
                            <div class="left" style="background-image: url('./assets/images/beobasket_black.png');"></div>
                            <div class="right" style="color: #000;">
                                Buy Bike
                            </div>
                        </a>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- Lifestyle picture grid -->
        <div class="moveaGallery " data-anchor="gallery" data-bg="transparent" data-animin="fade" data-animfullscreen="fade" data-interval="no" data-load="instant" data-pan="yes" >
            <div class="FiveGrid">
                <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="https://movea.bike/wp-content/uploads/2018/04/lifestyle11.jpg" data-img="https://movea.bike/wp-content/uploads/2018/04/lifestyle11.jpg" data-labelcolor="0" class="spot spot1" >                    
                    <!-- <a class="overlayText" href="">
                        <h4></h4>
                        <p></p>
                    </a> -->
                    <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle11.jpg"></div>
                </div>
                <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="https://movea.bike/wp-content/uploads/2018/04/lifestyle21.jpg" data-img="https://movea.bike/wp-content/uploads/2018/04/lifestyle21.jpg" data-labelcolor="0" class="spot spot2" >
                    <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle21.jpg" style="transform: matrix(1, 0, 0, 1, 0, 0);"></div>
                </div>
                <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="https://movea.bike/wp-content/uploads/2018/04/lifestyle31.jpg" data-img="https://movea.bike/wp-content/uploads/2018/04/lifestyle31.jpg" data-labelcolor="0" class="spot spot3" >
                    <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle31.jpg" style="transform: matrix(1, 0, 0, 1, 0, 0);"></div>
                </div>
                <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="https://movea.bike/wp-content/uploads/2018/04/lifestyle41.jpg" data-img="https://movea.bike/wp-content/uploads/2018/04/lifestyle41.jpg" data-labelcolor="0" class="spot spot4" >
                    <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle41.jpg" style="transform: matrix(1, 0, 0, 1, 0, 0);"></div>
                </div>
                <div data-w="1500" data-h="1010" data-color="#000000" data-low="https://movea.bike/wp-content/uploads/2018/04/lifestyle51.jpg" data-img="https://movea.bike/wp-content/uploads/2018/04/lifestyle51.jpg" data-labelcolor="0" class="spot spot5" >
                    <div class="galImg" style="background-color: rgb(0, 0, 0);"><img class="galImg lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle51.jpg" style="transform: matrix(1, 0, 0, 1, 0, 0);"></div>
                </div>
            </div>
            <div class="MobileGrid">
                <div class="carousel_container" id="lifestyle_images_mobile_slider">       
                    <div class="carousel_items">        
                        <ul>
                            <li class="carousel_item" id="lifestyle_item_0"><img class="lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle-mobile-11.jpg" /></li>
                            <li class="carousel_item" id="lifestyle_item_1"><img class="lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle-mobile-21.jpg" /></li>
                            <li class="carousel_item" id="lifestyle_item_2"><img class="lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle-mobile-31.jpg" /></li>
                            <li class="carousel_item" id="lifestyle_item_3"><img class="lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle-mobile-41.jpg" /></li>
                            <li class="carousel_item" id="lifestyle_item_4"><img class="lazyload" data-src="https://movea.bike/wp-content/uploads/2018/04/lifestyle-mobile-51.jpg" /></li>
                        </ul>
                    </div>
                    <div class="nav_dots"></div>
                </div>
            </div>
        </div>
        
        <!-- end Lifestyle picture grid -->


        <div id="TextSpot2Column">
            <div class="moveaSplitText" >
                <h2><?php echo get_option_tree('title_like');?></h2>
                <div class="paragraphWrap">
                    <?php echo get_option_tree('left_block_like');?>
                    <?php echo get_option_tree('right_block_like');?>
                </div>
                <!-- <h3><a href="#headphones" style="color: inherit;">Compare headphones</a></h3> -->
            </div>
        </div>
        <!-- <div class="moveaSplitProduct moveaSplit inview" id="split1" data-valign="top">
            <div class="block">
                <img class="bgImg desktopOnly lazy" src="./assets/images/split/h9i_split_1.jpg">
            </div>
            <div class="block">
                <img class="bgImg desktopOnly lazy" src="./assets/images/split/h9i_split_2.jpg">
            </div>
            <div class="text titles right" style="color:#fff;">
                <h2>Beoplay</h2>
                <h1>H9i</h1>
                <div class="film" data-yt="UQTfZUo0uQs">Watch video</div>
                <div class="price">USD 499</div>
        
                <a href="#" class="btn">
                    <div class="left" style="background-image: url('./assets/images/experience_black.png');"></div>
                    <div class="right">Learn more</div>
                </a>
            </div>
            <div class="text description left" style="color:#fff;">
                <p>Beoplay H9i</p>
                <p>Premium, Active Noise Cancelling over-ear headphones offering uncompromising wireless sound. Turn on the sound and feel the silence. With Beoplay H9i, the focus is on the music. So you can enjoy all the power and precision of authentic Bang &amp; Olufsen Signature Sound and move freely. Exclusive, carefully selected materials guarantee absolute comfort and style.</p>
                <p></p>
            </div>
        </div>
        <div class="moveaSplitProduct moveaSplit marginAbove marginBelow inview" id="split2" data-valign="top">
            <div class="block">
                <img class="bgImg desktopOnly" src="./assets/images/split/h8i_split_1.jpg">
            </div>
            <div class="block">
                <img class="bgImg desktopOnly" src="./assets/images/split/h8i_split_2.jpg">
            </div>
            <div class="text titles right" style="color:#fff;">
                <h2>Beoplay</h2>
                <h1>H9i</h1>
                <div class="film" data-yt="UQTfZUo0uQs">Watch video</div>
                <div class="price">USD 499</div>
        
                <a href="#" class="btn">
                    <div class="left" style="background-image: url('./assets/images/experience_black.png');"></div>
                    <div class="right">Learn more</div>
                </a>
            </div>
            <div class="text description left" style="color:#fff;">
                <p>Beoplay H9i</p>
                <p>Premium, Active Noise Cancelling over-ear headphones offering uncompromising wireless sound. Turn on the sound and feel the silence. With Beoplay H9i, the focus is on the music. So you can enjoy all the power and precision of authentic Bang &amp; Olufsen Signature Sound and move freely. Exclusive, carefully selected materials guarantee absolute comfort and style.</p>
                <p></p>
            </div>
        </div>
        <div class="moveaSplitProduct moveaSplit marginAbove marginBelow inview" id="split3" data-valign="top">
            <div class="block">
                <img class="bgImg desktopOnly" src="./assets/images/split/h4_split_3.jpg">
            </div>
            <div class="block">
                <img class="bgImg desktopOnly" src="./assets/images/split/h4_split_3_1.jpg">
            </div>
            <div class="text titles right" style="color:#fff;">
                <h2>Beoplay</h2>
                <h1>H9i</h1>
                <div class="film" data-yt="UQTfZUo0uQs">Watch video</div>
                <div class="price">USD 499</div>
        
                <a href="#" class="btn">
                    <div class="left" style="background-image: url('./assets/images/experience_black.png');"></div>
                    <div class="right">Learn more</div>
                </a>
            </div>
            <div class="text description left" style="color:#fff;">
                <p>Beoplay H9i</p>
                <p>Premium, Active Noise Cancelling over-ear headphones offering uncompromising wireless sound. Turn on the sound and feel the silence. With Beoplay H9i, the focus is on the music. So you can enjoy all the power and precision of authentic Bang &amp; Olufsen Signature Sound and move freely. Exclusive, carefully selected materials guarantee absolute comfort and style.</p>
                <p></p>
            </div>
        </div> -->
        <div class="productindex-introcontainer" id="products">
            <div class="product-category">
                <div class="relationspotlist">
                    <div class="spots packed">
                        <?php 
                            $product_args = array(
                                'post_type' => 'product',
                                'order' => 'ASC',
                              );
                
                            $product_query = new WP_Query( $product_args );
                            if ( $product_query->have_posts() ) :
                                while ( $product_query->have_posts() ) : $product_query->the_post();
                                    $post = get_post();
                                    $post_id = $post->ID;
                                    $product_image1 = get_field('image1', $post_id);
                                    $product_image2 = get_field('image2', $post_id);
                                    $product_image3 = get_field('image3', $post_id);
                                    $product_image4 = get_field('image4', $post_id);
                                    $product_image5 = get_field('image5', $post_id);
                                    $product_image6 = get_field('image6', $post_id);
                                    $product_image7 = get_field('image7', $post_id);
                        ?>
                            <div class="moveaSpot multiple " data-link="<?php echo esc_url(get_page_link($post_id));?>">
                                <div class="linkImg">
                                    <div class="carousel_container" id="spot_<?php echo $post_id;?>">       
                                        <div class="carousel_items">        
                                            <ul>
                                                <?php if(get_field('image1', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_0"><img class="lazyload" data-src="<?php echo $product_image1['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image2', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_1"><img class="lazyload" data-src="<?php echo $product_image2['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image3', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_2"><img class="lazyload" data-src="<?php echo $product_image3['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image4', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_3"><img class="lazyload" data-src="<?php echo $product_image4['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image5', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_4"><img class="lazyload" data-src="<?php echo $product_image5['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image6', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_5"><img class="lazyload" data-src="<?php echo $product_image6['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image7', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_6"><img class="lazyload" data-src="<?php echo $product_image7['sizes']['medium'];?>" /></li>
                                                <?php }?>
                                            </ul>
                                        </div>
                                        <div class="nav_dots"></div>
                                    </div>
                                </div>
                                <div class="info">
                                    <h4><?php the_title();?></h4>
                                    <div class="priceAndInfo">
                                        <p><?php echo $post->post_excerpt;?></p>
                                        <a class="more-btn" style="background-color:#FFFFFF;" class="more-info" href="<?php echo esc_url(get_page_link($post_id));?>">More info</a>
                                        <!-- <a class="soon-indiegog btn" style="padding: 10px 25px!important;" href="javascript:;">Soon on Indiegogo</a> -->
                                        <!-- <a href="https://www.indiegogo.com/projects/movea-the-self-charging-design-e-bike/coming_soon/pica" class="btn" style="background-color:#FFFFFF;">
                                            <div class="left"></div>
                                            <div class="right" style="color: #666;">
                                                <span class="amount">Buy at Indiegogo</span>
                                                Buy at Indiegogo
                                            </div>
                                        </a> -->
                                    </div>
                                </div>
                                <h2><?php echo the_field('model_type', $post_id);?></h2>
                            </div>
                        <?php
                                endwhile;
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="moveaApp">
            <div class="imgWrapper">
                <img class="lazyload" data-src="<?php echo get_option_tree('app_image_app');?>">
            </div>
            <div class="txtWrapper">
                <div class="txtBlock">
                    <h2 class="title"><?php echo get_option_tree('title_app');?></h2>
                    <h5 class="content"><?php echo get_option_tree('sub_title_app');?></h5>
                    <a target="_blank" href="https://itunes.apple.com/us/app/bitride/id1021902781?mt=8" class="btn">
                        <div class="left"></div>
                        <div class="right">Learn more</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="newsletterPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="left-img" style="background: url(https://movea.bike/wp-content/uploads/2018/05/scriber-image.jpg); background-size: cover; background-position: center center; background-repeat:no-repeat;"></div>
                <div class="right-newletter">
                    <h1>Win a Hövding airbag helmet, value € 299,- <br/>Sign up to our newsletter.</h1>
                    <h3>Competition ends 15. July 2018.<br/>We´ll draw 2 winners at random.</h3>
                    <div class="sign-up-btn"><a href="/newsletter">Sign up now</a></div>
                </div>
                <!-- <h1>New danish designed E bikes</h1>
                <h3>Save up to 53%</h3>
                <h4>The New Movea bike will soon launch on Indiegogo</h4> -->
                <?php //echo do_shortcode("[newsletter]"); ?>
            </div>
        </div>
    </div>
    </div>
<?php get_footer();