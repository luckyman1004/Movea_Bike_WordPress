<?php
/**
 * The template for displaying all single product
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Separate_Blog
 */

get_header(); ?>
    <div class="ind-gallery" id="ind_gallery" style="display: none;">
        <?php $post_id = get_the_ID();?>
        <div class="ind-close">&times;</div>
        <div class="gallery-container">
            <div class="carousel_container fullscreen" id="product">       
                <div class="carousel_items">        
                    <ul>
                        <?php if(get_field('image1', $post_id) != '') { ?>
                        <li class="carousel_item" id="item_0"><img src="<?php the_field('image1', $post_id);?>" /></li>
                        <?php }?>
                        <?php if(get_field('image2', $post_id) != '') { ?>
                        <li class="carousel_item" id="item_1"><img src="<?php the_field('image2', $post_id);?>" /></li>
                        <?php }?>
                        <?php if(get_field('image3', $post_id) != '') { ?>
                        <li class="carousel_item" id="item_2"><img src="<?php the_field('image3', $post_id);?>" /></li>
                        <?php }?>
                        <?php if(get_field('image4', $post_id) != '') { ?>
                        <li class="carousel_item" id="item_3"><img src="<?php the_field('image4', $post_id);?>" /></li>
                        <?php }?>
                    </ul>
                </div>
                <div class="nav_dots"></div>
            </div>
        </div>
    </div>
    <div class="lifestyle-gallery" id="lifestyle_gallery" style="display: none;"><div class="lifestyle-close">&times;</div></div>
	<div class="main-container detail-template post-<?php the_ID(); ?>">
            <div class="buyModule">
            <div style="display: none;">
			<?php
				the_post();
				$post_id = the_ID();
				$current_post_id = $post->ID;
				$post_type = get_field('model_type', $post_id);
            ?>
            </div>
                <section class="ecom-buy-module js-ecom standard-view">
                    <div class="content-container">
                        <div class="ecom-buy-module-header mobile-only">
                            <h1><?php the_title();?></h1>
                            <p><?php the_content();?></p>
                        </div>
                        <div class="ecom-buy-module-container clearfix">
                            <div class="ecom-buy-module-image js-product-image">
                                <div class="ind-image">
                                    <img src="<?php the_field('image1', $post_id);?>" />
                                </div>
                                <div class="view-gallery" id="view_gallery">View gallery</div>
                            </div>
                            <aside class="ecom-buy-module-sidebar animation-fade-in">
                                <span class="desktop-only" role="heading" aria-level="1"><?php the_title();?></span>
                                <div class="ecom-buy-module-standard">
                                    <div class="ecom-buy-module-short-description desktop-only v-standard">
										<?php the_content(); ?>										
                                    </div>
                                    <div style="padding: 15px 0;     font-family: 'ProximaThin',sans-serif;    color: #666; font-weight: 400;">
                                        Fenders and bongee code are included<br>
                                        Size Guide: Rider's height (cm) <?php the_field('rider_height', $post_id);?>
                                    </div>
                                    <div class="ecom-buy-module-sidebar-row with-border ecom-buy-module-sidebar-prices space-large clearfix js-total-prices">
                                        <div class="inner">
                                            <div class="cols">
                                                <!-- <div class="col">
                                                    <div class="ecom-buy-module-price js-normal-price">
                                                        <span class="currency">USD</span>
                                                        <span class="price"><?php the_field('price', $post_id);?></span>
                                                    </div>
                                                </div> -->
                                                <div class="col">
                                                    <!-- button-purchase (green version) -->
                                                    <!-- <a href="<?php the_field('indiegogo_url', $post_id);?>" class="button button-primary js-buy-button">Buy at Indiegogo</a> -->
                                                    <span class="size-guide" style="float: left;">Buy at Indiegogo May 1st. Save up to 40%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </section>
			</div>
			<?php if (get_field('model_type', $post_id) == 'E-move') { ?>
            <!-- Accordion Slider For E-Model -->
            <div id="TextSpot2Column">
                <div class="moveaSplitText" >
                    <h3><a href="#headphones" style="color: inherit;">Movea E-move Model</a></h3>
                </div>
            </div>
            <div class="moveausp marginBelow" id="moveausp1" data-anchor="usp"  data-bgimg="<?php echo get_option_tree('background_image_e_model');?>" data-mbgimg="<?php echo get_option_tree('background_image_mobile_e_model');?>">
				<div class="bg mainbg"></div>
				<?php for( $i = 1; $i < 5; $i ++) { ?>
                    <?php
                        $src = get_option_tree($i.'_slider_image_e_model');
                        if(get_option_tree($i.'_slider_top_text_e_model') == 'Size') {
                            if(get_field('size', $post_id) == '20') {
                                $src = 'http://movea.bike/wp-content/uploads/2018/03/size-bike-custom.jpg';
                            } else {
                                $src = 'http://movea.bike/wp-content/uploads/2018/03/size-bike-custom-1.jpg';
                            }
                        }
                    ?>
                <div class="block" data-theme="0" data-bg="#8daebd"  data-bgimg="<?php echo $src;?>" data-mbgimg="<?php echo $src;?>">
                    <div class="overlay"></div>
                    <!-- Front content -->
                    <div class="label"><?php echo get_option_tree($i.'_slider_top_text_e_model');?></div>
                    <!-- same text as "toptext" when open!!! -->
                    <div class="front">
                        <h1><?php echo get_option_tree($i.'_slider_title_e_model');?></h1>
                        <p><?php echo get_option_tree($i.'_slider_sub_title_e_model');?></p>
                        <!-- same text as "subtitletext" when open!!! -->
                    </div>
                    <!--  Open block content  -->
                    <div class="text ">
                        <!-- Small title -->
                        <div class="toptext"><?php echo get_option_tree($i.'_slider_top_text_e_model');?></div>
                        <!-- Large title -->
                        <div class="titletext"><?php echo get_option_tree($i.'_slider_title_e_model');?></div>
                        <!-- Medium title -->
                        <div class="subtitletext"><?php echo get_option_tree($i.'_slider_sub_title_e_model');?></div>
                        <!-- Body -->
                        <div class="bodytext">
							<?php echo get_option_tree($i.'_slider_body_text_e_model');?>
                        </div>
                     </div>
                    <div class="roundBtn">
                        <div class="bg"></div>
                        <div class="cross">
                            <div class="a"></div>
                            <div class="b"></div>
                        </div>
                        <div class="readmore">Read More</div>
                    </div>
				</div>
				<?php } ?>
                <div class="roundBtn rotated" style="opacity: 0;"><div class="bg" style="transform-origin: 21px 21px 0px; transform: matrix(0, 0, 0, 0, 0, 0);"></div><div class="cross" style="transform: matrix(0.7071, 0.7071, -0.7071, 0.7071, 0, 0);"><div class="a"></div><div class="b"></div></div></div>
            </div>
			<!-- End Accordion Slider For E-Model -->
			<?php } else if (get_field('model_type', $post_id) == '8-speed') {?>
            <!-- Accordion Slider For 8 Speed-Model -->
            <div id="TextSpot2Column">
                <div class="moveaSplitText" >
                    <h3><a href="#headphones" style="color: inherit;">Movea 8 Speed Model</a></h3>
                </div>
            </div>
            <div class="moveausp marginBelow" id="moveausp2" data-anchor="usp"  data-bgimg="<?php echo get_option_tree('background_image_8_speed');?>" data-mbgimg="<?php echo get_option_tree('background_image_mobile_8_speed');?>">
				<div class="bg mainbg"></div>
				<?php for( $i = 1; $i < 4; $i ++) { ?>
                    <?php
                        $src = get_option_tree($i.'_slider_image_8_speed');
                        if(get_option_tree($i.'_slider_top_text_8_speed') == 'Size') {
                            if(get_field('size', $post_id) == '20') {
                                $src = 'http://movea.bike/wp-content/uploads/2018/03/size-bike-custom.jpg';
                            } else {
                                $src = 'http://movea.bike/wp-content/uploads/2018/03/size-bike-custom-1.jpg';
                            }
                        }
                    ?>
                <div class="block" data-theme="0" data-bg="#8daebd"  data-bgimg="<?php echo $src;?>" data-mbgimg="<?php echo $src;?>">
                    <div class="overlay"></div>
                    <!-- Front content -->
                    <div class="label"><?php echo get_option_tree($i.'_slider_top_text_8_speed');?></div>
                    <!-- same text as "toptext" when open!!! -->
                    <div class="front">
                        <h1><?php echo get_option_tree($i.'_slider_title_8_speed');?></h1>
                        <p><?php echo get_option_tree($i.'_slider_sub_title_8_speed');?></p>
                        <!-- same text as "subtitletext" when open!!! -->
                    </div>
                    <!--  Open block content  -->
                    <div class="text ">
                        <!-- Small title -->
                        <div class="toptext"><?php echo get_option_tree($i.'_slider_top_text_8_speed');?></div>
                        <!-- Large title -->
                        <div class="titletext"><?php echo get_option_tree($i.'_slider_title_8_speed');?></div>
                        <!-- Medium title -->
                        <div class="subtitletext"><?php echo get_option_tree($i.'_slider_sub_title_8_speed');?></div>
                        <!-- Body -->
                        <div class="bodytext">
							<?php echo get_option_tree($i.'_slider_body_text_8_speed');?>
                        </div>
                        </div>
                    <div class="roundBtn">
                        <div class="bg"></div>
                        <div class="cross">
                            <div class="a"></div>
                            <div class="b"></div>
                        </div>
                        <div class="readmore">Read More</div>
                    </div>
				</div>
				<?php } ?>
                <div class="roundBtn rotated" style="opacity: 0;"><div class="bg" style="transform-origin: 21px 21px 0px; transform: matrix(0, 0, 0, 0, 0, 0);"></div><div class="cross" style="transform: matrix(0.7071, 0.7071, -0.7071, 0.7071, 0, 0);"><div class="a"></div><div class="b"></div></div></div>
            </div>
            <!-- End Accordion Slider For 8Speed-Model -->
            <?php } ?>
            
            <!-- Lifestyle picture grid -->
            <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                <div class="moveaGallery " data-anchor="gallery" data-bg="transparent" data-animin="fade" data-animfullscreen="fade" data-interval="no" data-load="instant" data-pan="yes" >
                    <div class="FiveGrid">
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle1.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle1.jpg" data-labelcolor="0" class="spot spot1" >                    
                            <a class="overlayText" href="">
                                <h4></h4>
                                <p></p>
                            </a>
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle1.jpg"></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle2.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle2.jpg" data-labelcolor="0" class="spot spot2" >
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle2.jpg" ></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle3.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle3.jpg" data-labelcolor="0" class="spot spot3" >
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle3.jpg" ></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle4.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle4.jpg" data-labelcolor="0" class="spot spot4" >
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle4.jpg" ></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#000000" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle5.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle5.jpg" data-labelcolor="0" class="spot spot5" >
                            <div class="galImg" style="background-color: rgb(0, 0, 0);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle5.jpg" ></div>
                        </div>
                    </div>
                    <div class="MobileGrid">
                        <div class="carousel_container" id="lifestyle_images_mobile_slider">       
                            <div class="carousel_items">        
                                <ul>
                                    <li class="carousel_item" id="lifestyle_item_0"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-1.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_1"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-2.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_2"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-3.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_3"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-4.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_4"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-5.jpg" /></li>
                                </ul>
                            </div>
                            <div class="nav_dots"></div>
                        </div>
                    </div>
                </div>
            <?php } else {?>
                <div class="moveaGallery " data-anchor="gallery" data-bg="transparent" data-animin="fade" data-animfullscreen="fade" data-interval="no" data-load="instant" data-pan="yes" >
                    <div class="FiveGrid">
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle1.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle1.jpg" data-labelcolor="0" class="spot spot1" >                    
                            <a class="overlayText" href="">
                                <h4></h4>
                                <p></p>
                            </a>
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle1.jpg"></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle2.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle2.jpg" data-labelcolor="0" class="spot spot2" >
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle2.jpg" ></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle3.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle3.jpg" data-labelcolor="0" class="spot spot3" >
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle3.jpg" ></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#FFFFFF" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle4.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle4.jpg" data-labelcolor="0" class="spot spot4" >
                            <div class="galImg" style="background-color: rgb(255, 255, 255);"><img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle4.jpg"></div>
                        </div>
                        <div data-w="1500" data-h="1010" data-color="#000000" data-low="http://movea.bike/wp-content/uploads/2018/03/lifestyle5.jpg" data-img="http://movea.bike/wp-content/uploads/2018/03/lifestyle5.jpg" data-labelcolor="0" class="spot spot5" >
                            <div class="galImg" style="background-color: rgb(0, 0, 0);">
                                <img class="galImg" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle5.jpg" style="transform: matrix(1, 0, 0, 1, 0, 0);">
                            </div>
                        </div>
                    </div>
                    <div class="MobileGrid">
                        <div class="carousel_container" id="lifestyle_images_mobile_slider">       
                            <div class="carousel_items">        
                                <ul>
                                    <li class="carousel_item" id="lifestyle_item_0"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-1.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_1"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-2.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_2"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-3.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_3"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-4.jpg" /></li>
                                    <li class="carousel_item" id="lifestyle_item_4"><img class="lazy" src="http://movea.bike/wp-content/uploads/2018/03/lifestyle-mobile-5.jpg" /></li>
                                </ul>
                            </div>
                            <div class="nav_dots"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <!-- end Lifestyle picture grid -->
            <!-- Tech Section -->
            
            <div class="moveaTechSpec">
                
                <!-- Tech Specification -->
                <div class="specContent">
                    <div class="container">
                        <div class="tech-header">
                            <div class="top">Technical</div>
                            <div class="title">Specifications</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="spec-item">
                                    <div class="title">Frame</div>
                                    <div class="content"><?php the_field('frame', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Stem</div>
                                    <div class="content"><?php the_field('stem', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Rear brake</div>
                                    <div class="content"><?php the_field('rear_brake', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Rear Derailleur</div>
                                    <div class="content"><?php the_field('rear_derailleur', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Crankset</div>
                                    <div class="content"><?php the_field('crankset', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Rims</div>
                                    <div class="content"><?php the_field('rims', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Tires</div>
                                    <div class="content"><?php the_field('tires', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Colors</div>
                                    <div class="content"><?php the_field('colors', $post_id);?></div>
                                </div>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Motor</div>
                                    <div class="content"><?php the_field('motor', $post_id);?></div>
                                </div>
                                <?php } ?>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Max speed</div>
                                    <div class="content"><?php the_field('max_speed', $post_id);?></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="spec-item">
                                    <div class="title">Front rack</div>
                                    <div class="content"><?php the_field('front_rack', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Handlebar</div>
                                    <div class="content"><?php the_field('handlebar', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Brake Levels</div>
                                    <div class="content"><?php the_field('brake_levels', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Shift Levers</div>
                                    <div class="content"><?php the_field('shift_levers', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Chainring</div>
                                    <div class="content"><?php the_field('chainring', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Front hub</div>
                                    <div class="content"><?php the_field('front_hub', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Saddle</div>
                                    <div class="content"><?php the_field('saddle', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Size</div>
                                    <div class="content"><?php the_field('size', $post_id);?></div>
                                </div>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Batteri</div>
                                    <div class="content"><?php the_field('batteri', $post_id);?></div>
                                </div>
                                <?php } ?>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">BATTERY LIFE CYCLE</div>
                                    <div class="content"><?php the_field('battery_life_cycle', $post_id);?></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="spec-item">
                                    <div class="title">Fork</div>
                                    <div class="content"><?php the_field('fork', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Grips</div>
                                    <div class="content"><?php the_field('grips', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Belt drive spoket</div>
                                    <div class="content"><?php the_field('belt_drive_spoket', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Cassette</div>
                                    <div class="content"><?php the_field('cassette', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Bottom bracket</div>
                                    <div class="content"><?php the_field('bottom_bracket', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Rear hub</div>
                                    <div class="content"><?php the_field('rear_hub', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Seatpost</div>
                                    <div class="content"><?php the_field('seatpost', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Weight</div>
                                    <div class="content"><?php the_field('weight', $post_id);?></div>
                                </div>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Hub</div>
                                    <div class="content"><?php the_field('hub', $post_id);?></div>
                                </div>
                                <?php } ?>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Range</div>
                                    <div class="content"><?php the_field('range', $post_id);?></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="spec-item">
                                    <div class="title">Headset</div>
                                    <div class="content"><?php the_field('headset', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Front brake</div>
                                    <div class="content"><?php the_field('front_brake', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Beltdrive</div>
                                    <div class="content"><?php the_field('beltdrive', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Chain</div>
                                    <div class="content"><?php the_field('chain', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Pedals</div>
                                    <div class="content"><?php the_field('pedals', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Spokes</div>
                                    <div class="content"><?php the_field('spokes', $post_id);?></div>
                                </div>
                                <div class="spec-item">
                                    <div class="title">Fender</div>
                                    <div class="content"><?php the_field('fender', $post_id);?></div>
                                </div>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Hub</div>
                                    <div class="content"><?php the_field('hub', $post_id);?></div>
                                </div>
                                <?php } ?>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Charging time</div>
                                    <div class="content"><?php the_field('charging_time', $post_id);?></div>
                                </div>
                                <?php } ?>
                                <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                <div class="spec-item">
                                    <div class="title">Rider’s height</div>
                                    <div class="content"><?php the_field('rider_height', $post_id);?></div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="specContentMobile">
                    <div class="tech-header">
                        <div class="top">Technical</div>
                        <div class="title">Specifications</div>
                    </div>
                    <div class="carousel_container" id="spec_table">       
                        <div class="carousel_items">        
                            <ul>
                                <li class="carousel_item" id="tb_item_0">
                                    <div class="specMobile">
                                        <div class="spec-item">
                                            <div class="title">Frame</div>
                                            <div class="content"><?php the_field('frame', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Stem</div>
                                            <div class="content"><?php the_field('stem', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Rear brake</div>
                                            <div class="content"><?php the_field('rear_brake', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Rear Derailleur</div>
                                            <div class="content"><?php the_field('rear_derailleur', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Crankset</div>
                                            <div class="content"><?php the_field('crankset', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Rims</div>
                                            <div class="content"><?php the_field('rims', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Tires</div>
                                            <div class="content"><?php the_field('tires', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Colors</div>
                                            <div class="content"><?php the_field('colors', $post_id);?></div>
                                        </div>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Motor</div>
                                            <div class="content"><?php the_field('motor', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Max speed</div>
                                            <div class="content"><?php the_field('max_speed', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </li>
                                <li class="carousel_item" id="tb_item_1">
                                    <div class="specMobile">
                                        <div class="spec-item">
                                            <div class="title">Front rack</div>
                                            <div class="content"><?php the_field('front_rack', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Handlebar</div>
                                            <div class="content"><?php the_field('handlebar', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Brake Levels</div>
                                            <div class="content"><?php the_field('brake_levels', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Shift Levers</div>
                                            <div class="content"><?php the_field('shift_levers', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Chainring</div>
                                            <div class="content"><?php the_field('chainring', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Front hub</div>
                                            <div class="content"><?php the_field('front_hub', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Saddle</div>
                                            <div class="content"><?php the_field('saddle', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Size</div>
                                            <div class="content"><?php the_field('size', $post_id);?></div>
                                        </div>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Batteri</div>
                                            <div class="content"><?php the_field('batteri', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">BATTERY LIFE CYCLE</div>
                                            <div class="content"><?php the_field('battery_life_cycle', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </li>
                                <li class="carousel_item" id="tb_item_2">
                                    <div class="specMobile">
                                        <div class="spec-item">
                                            <div class="title">Fork</div>
                                            <div class="content"><?php the_field('fork', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Grips</div>
                                            <div class="content"><?php the_field('grips', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Belt drive spoket</div>
                                            <div class="content"><?php the_field('belt_drive_spoket', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Cassette</div>
                                            <div class="content"><?php the_field('cassette', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Bottom bracket</div>
                                            <div class="content"><?php the_field('bottom_bracket', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Rear hub</div>
                                            <div class="content"><?php the_field('rear_hub', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Seatpost</div>
                                            <div class="content"><?php the_field('seatpost', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Weight</div>
                                            <div class="content"><?php the_field('weight', $post_id);?></div>
                                        </div>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Hub</div>
                                            <div class="content"><?php the_field('hub', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Range</div>
                                            <div class="content"><?php the_field('range', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </li>
                                <li class="carousel_item" id="tb_item_3">
                                    <div class="specMobile">
                                        <div class="spec-item">
                                            <div class="title">Headset</div>
                                            <div class="content"><?php the_field('headset', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Front brake</div>
                                            <div class="content"><?php the_field('front_brake', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Beltdrive</div>
                                            <div class="content"><?php the_field('beltdrive', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Chain</div>
                                            <div class="content"><?php the_field('chain', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Pedals</div>
                                            <div class="content"><?php the_field('pedals', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Spokes</div>
                                            <div class="content"><?php the_field('spokes', $post_id);?></div>
                                        </div>
                                        <div class="spec-item">
                                            <div class="title">Fender</div>
                                            <div class="content"><?php the_field('fender', $post_id);?></div>
                                        </div>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Hub</div>
                                            <div class="content"><?php the_field('hub', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Charging time</div>
                                            <div class="content"><?php the_field('charging_time', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                        <?php if (get_field('model_type', $post_id) == 'E-move') { ?>
                                        <div class="spec-item">
                                            <div class="title">Rider’s height</div>
                                            <div class="content"><?php the_field('rider_height', $post_id);?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="nav_dots"></div>
                    </div>
                </div>
                <!-- end Tech Specification -->
                <div id="TextSpot2Column">
                    <div class="moveaSplitText" >
                        <!-- <h2><?php echo get_option_tree('title_geometry');?></h2> -->
                        <h3><a href="#geo" style="color: inherit;"><?php echo get_option_tree('title_geometry');?></a></h3> 
                    </div>
                </div>
                <div class="techImgWrapper">
                    <div class="img_wrapper" style="display: none;">
                        <img class="" src="<?php echo get_option_tree('background_image_geometry');?>" >
                    </div>
                    <div class="txt_wrapper">
                        <div class="txt_block">
                            <table>
                                <thead>
                                    <th>Men</th>
                                    <th>20"</th>
                                    <th>24"</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>A) Seat tube angle</td>
                                        <td>73 degree</td>
                                        <td>73 degree</td>
                                    </tr>
                                    <tr>
                                        <td>B) Top-tube length</td>
                                        <td>56cm</td>
                                        <td>58cm</td>
                                    </tr>
                                    <tr>
                                        <td>C) Seat-tube length</td>
                                        <td>37cm</td>
                                        <td>46cm</td>
                                    </tr>
                                    <tr>
                                        <td>D) Wheel base</td>
                                        <td>102cm</td>
                                        <td>104cm</td>
                                    </tr>
                                    <tr>
                                        <td>E) Head-tube length</td>
                                        <td>28cm</td>
                                        <td>26cm</td>
                                    </tr>
                                    <tr>
                                        <td>F) Stand over high</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="txt_block">
                            <table>
                                <thead>
                                    <th>Women</th>
                                    <th>20"</th>
                                    <th>24"</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>A) Seat tube angle</td>
                                        <td>73 degree</td>
                                        <td>73 degree</td>
                                    </tr>
                                    <tr>
                                        <td>B) Top-tube length</td>
                                        <td>56cm</td>
                                        <td>58cm</td>
                                    </tr>
                                    <tr>
                                        <td>C) Seat-tube length</td>
                                        <td>37cm</td>
                                        <td>46cm</td>
                                    </tr>
                                    <tr>
                                        <td>D) Wheel base</td>
                                        <td>102cm</td>
                                        <td>104cm</td>
                                    </tr>
                                    <tr>
                                        <td>E) Head-tube length</td>
                                        <td>30cm</td>
                                        <td>26cm</td>
                                    </tr>
                                    <tr>
                                        <td>F) Stand over high</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- <div class="specContent">
                    <table>
                        <thead>
                            <th></th>
                            <th>S</th>
                            <th>M</th>
                            <th>L</th>
                            <th>XL</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Rider Height(feet)</td>
                                <td>5'1" - 5'5"</td>
                                <td>5'6" - 5'9"</td>
                                <td>5'10" - 6'1"</td>
                                <td>6'2" - 6'6"</td>
                            </tr>
                            <tr>
                                <td>Rider Height(cm)</td>
                                <td>154 - 164cm</td>
                                <td>165 - 175cm</td>
                                <td>176 - 185cm</td>
                                <td>187 - 198cm</td>
                            </tr>
                            <tr>
                                <td>A) Head Tube Angle</td>
                                <td>71.5°</td>
                                <td>72.0°</td>
                                <td>72.5°</td>
                                <td>72.5°</td>
                            </tr>
                            <tr>
                                <td>B) Seat Tube Angle</td>
                                <td>69.5°</td>
                                <td>69.5°</td>
                                <td>69.5°</td>
                                <td>69.5°</td>
                            </tr>
                            <tr>
                                <td>C) Top-Tube Length</td>
                                <td>560mm</td>
                                <td>580mm</td>
                                <td>605mm</td>
                                <td>630mm</td>
                            </tr>
                            <tr>
                                <td>D) Seat-Tube Length</td>
                                <td>430mm</td>
                                <td>455mm</td>
                                <td>485mm</td>
                                <td>505mm</td>
                            </tr>
                            <tr>
                                <td>E) Chain-Stay Length</td>
                                <td>458mm</td>
                                <td>458mm</td>
                                <td>458mm</td>
                                <td>458mm</td>
                            </tr>
                            <tr>
                                <td>F) Bottom Bracket Drop</td>
                                <td>70mm</td>
                                <td>70mm</td>
                                <td>75mm</td>
                                <td>75mm</td>
                            </tr>
                            <tr>
                                <td>G) Fork Rake</td>
                                <td>50mm</td>
                                <td>50mm</td>
                                <td>50mm</td>
                                <td>50mm</td>
                            </tr>
                            <tr>
                                <td>H) Fork Length</td>
                                <td>430mm</td>
                                <td>430mm</td>
                                <td>430mm</td>
                                <td>430mm</td>
                            </tr>
                            <tr>
                                <td>I) Head-Tube Length</td>
                                <td>122mm</td>
                                <td>127mm</td>
                                <td>152mm</td>
                                <td>172mm</td>
                            </tr>
                            <tr>
                                <td>J) Stand Over Height</td>
                                <td>765mm</td>
                                <td>785mm</td>
                                <td>800mm</td>
                                <td>810mm</td>
                            </tr>
                            <tr>
                                <td>K) Wheel Base</td>
                                <td>1072mm</td>
                                <td>1087mm</td>
                                <td>1103mm</td>
                                <td>1126mm</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="specContentMobile">
                    <div class="carousel_container" id="spec_table">       
                        <div class="carousel_items">        
                            <ul>
                                <li class="carousel_item" id="tb_item_0">
                                    <table>
                                        <thead>
                                            <th></th>
                                            <th>S</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Rider Height(feet)</td>
                                                <td>5'1" - 5'5"</td>
                                            </tr>
                                            <tr>
                                                <td>Rider Height(cm)</td>
                                                <td>154 - 164cm</td>
                                            </tr>
                                            <tr>
                                                <td>A) Head Tube Angle</td>
                                                <td>71.5°</td>
                                            </tr>
                                            <tr>
                                                <td>B) Seat Tube Angle</td>
                                                <td>69.5°</td>
                                            </tr>
                                            <tr>
                                                <td>C) Top-Tube Length</td>
                                                <td>560mm</td>
                                            </tr>
                                            <tr>
                                                <td>D) Seat-Tube Length</td>
                                                <td>430mm</td>
                                            </tr>
                                            <tr>
                                                <td>E) Chain-Stay Length</td>
                                                <td>458mm</td>
                                            </tr>
                                            <tr>
                                                <td>F) Bottom Bracket Drop</td>
                                                <td>70mm</td>
                                            </tr>
                                            <tr>
                                                <td>G) Fork Rake</td>
                                                <td>50mm</td>
                                            </tr>
                                            <tr>
                                                <td>H) Fork Length</td>
                                                <td>430mm</td>
                                            </tr>
                                            <tr>
                                                <td>I) Head-Tube Length</td>
                                                <td>122mm</td>
                                            </tr>
                                            <tr>
                                                <td>J) Stand Over Height</td>
                                                <td>765mm</td>
                                            </tr>
                                            <tr>
                                                <td>K) Wheel Base</td>
                                                <td>1072mm</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li class="carousel_item" id="tb_item_1">
                                    <table>
                                        <thead>
                                            <th></th>
                                            <th>M</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Rider Height(feet)</td>
                                                <td>5'6" - 5'9"</td>
                                            </tr>
                                            <tr>
                                                <td>Rider Height(cm)</td>
                                                <td>165 - 175cm</td>
                                            </tr>
                                            <tr>
                                                <td>A) Head Tube Angle</td>
                                                <td>72.0°</td>
                                            </tr>
                                            <tr>
                                                <td>B) Seat Tube Angle</td>
                                                <td>69.5°</td>
                                            </tr>
                                            <tr>
                                                <td>C) Top-Tube Length</td>
                                                <td>580mm</td>
                                            </tr>
                                            <tr>
                                                <td>D) Seat-Tube Length</td>
                                                <td>455mm</td>
                                            </tr>
                                            <tr>
                                                <td>E) Chain-Stay Length</td>
                                                <td>458mm</td>
                                            </tr>
                                            <tr>
                                                <td>F) Bottom Bracket Drop</td>
                                                <td>70mm</td>
                                            </tr>
                                            <tr>
                                                <td>G) Fork Rake</td>
                                                <td>50mm</td>
                                            </tr>
                                            <tr>
                                                <td>H) Fork Length</td>
                                                <td>430mm</td>
                                            </tr>
                                            <tr>
                                                <td>I) Head-Tube Length</td>
                                                <td>127mm</td>
                                            </tr>
                                            <tr>
                                                <td>J) Stand Over Height</td>
                                                <td>785mm</td>
                                            </tr>
                                            <tr>
                                                <td>K) Wheel Base</td>
                                                <td>1087mm</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li class="carousel_item" id="tb_item_2">
                                    <table>
                                        <thead>
                                            <th></th>
                                            <th>L</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Rider Height(feet)</td>
                                                <td>5'10" - 6'1"</td>
                                            </tr>
                                            <tr>
                                                <td>Rider Height(cm)</td>
                                                <td>176 - 185cm</td>
                                            </tr>
                                            <tr>
                                                <td>A) Head Tube Angle</td>
                                                <td>72.5°</td>
                                            </tr>
                                            <tr>
                                                <td>B) Seat Tube Angle</td>
                                                <td>69.5°</td>
                                            </tr>
                                            <tr>
                                                <td>C) Top-Tube Length</td>
                                                <td>605mm</td>
                                            </tr>
                                            <tr>
                                                <td>D) Seat-Tube Length</td>
                                                <td>485mm</td>
                                            </tr>
                                            <tr>
                                                <td>E) Chain-Stay Length</td>
                                                <td>458mm</td>
                                            </tr>
                                            <tr>
                                                <td>F) Bottom Bracket Drop</td>
                                                <td>75mm</td>
                                            </tr>
                                            <tr>
                                                <td>G) Fork Rake</td>
                                                <td>50mm</td>
                                            </tr>
                                            <tr>
                                                <td>H) Fork Length</td>
                                                <td>430mm</td>
                                            </tr>
                                            <tr>
                                                <td>I) Head-Tube Length</td>
                                                <td>152mm</td>
                                            </tr>
                                            <tr>
                                                <td>J) Stand Over Height</td>
                                                <td>800mm</td>
                                            </tr>
                                            <tr>
                                                <td>K) Wheel Base</td>
                                                <td>1103mm</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                                <li class="carousel_item" id="tb_item_3">
                                    <table>
                                        <thead>
                                            <th></th>
                                            <th>XL</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Rider Height(feet)</td>
                                                <td>6'2" - 6'6"</td>
                                            </tr>
                                            <tr>
                                                <td>Rider Height(cm)</td>
                                                <td>187 - 198cm</td>
                                            </tr>
                                            <tr>
                                                <td>A) Head Tube Angle</td>
                                                <td>72.5°</td>
                                            </tr>
                                            <tr>
                                                <td>B) Seat Tube Angle</td>
                                                <td>69.5°</td>
                                            </tr>
                                            <tr>
                                                <td>C) Top-Tube Length</td>
                                                <td>630mm</td>
                                            </tr>
                                            <tr>
                                                <td>D) Seat-Tube Length</td>
                                                <td>505mm</td>
                                            </tr>
                                            <tr>
                                                <td>E) Chain-Stay Length</td>
                                                <td>458mm</td>
                                            </tr>
                                            <tr>
                                                <td>F) Bottom Bracket Drop</td>
                                                <td>75mm</td>
                                            </tr>
                                            <tr>
                                                <td>G) Fork Rake</td>
                                                <td>50mm</td>
                                            </tr>
                                            <tr>
                                                <td>H) Fork Length</td>
                                                <td>430mm</td>
                                            </tr>
                                            <tr>
                                                <td>I) Head-Tube Length</td>
                                                <td>172mm</td>
                                            </tr>
                                            <tr>
                                                <td>J) Stand Over Height</td>
                                                <td>810mm</td>
                                            </tr>
                                            <tr>
                                                <td>K) Wheel Base</td>
                                                <td>1126mm</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </li>
                            </ul>
                        </div>
                        <div class="nav_dots"></div>
                    </div>
                </div> -->
            </div>
            <!-- End Tech Section -->
            <!-- Designer Slider -->
            <!-- End Designer Slider -->
            <!-- Support Video Section -->
            <div class="moveaSupportVideo" id="supportVideo1" data-videoId="<?php echo get_option_tree('youtube_video_id_support_video');?>" style="display: none;">
                <div class="text textCenter">
                    <div class="toptext"><?php echo get_option_tree('top_text_support_video');?></div>
                    <div class="titletext "><?php echo get_option_tree('title_support_video');?></div>
                    <br>
                </div>
                <div class="moveaVideoPlayer center fadeIn" >
                    <div class="playBtn">
                        <div class="playIcon"></div>
                    </div>
                    <img src="<?php echo get_option_tree('background_image_support_video');?>">
                </div>
            </div>
            <!-- End Support Video Section -->
            <!-- Also Like Section -->
            <div id="TextSpot2Column">
                <div class="moveaSplitText" >
                    <h3><a href="#headphones" style="color: inherit;">YOU MAY ALSO LIKE...</a></h3>
                </div>
            </div>
            <div class="productindex-introcontainer">
            <div class="product-category">
                <div class="relationspotlist">
                    <div class="spots packed">
						<?php 
                            $product_args = array(
								'post_type' => 'product'
                              );
							  
							$product_query = new WP_Query( $product_args );
                            if ( $product_query->have_posts() ) :
                                while ( $product_query->have_posts() ) : $product_query->the_post();
                                    $like_post = get_post();
									$like_post_id = $like_post->ID;
						?>
							<?php if (get_field('model_type', $like_post_id) == $post_type && $like_post_id != $current_post_id) {?>
                            <div class="moveaSpot multiple " data-link="<?php echo esc_url(get_page_link($like_post_id));?>" >
                                <div class="linkImg">
                                    <div class="carousel_container" id="spot_<?php echo $like_post_id;?>">       
                                        <div class="carousel_items">        
                                            <ul>
                                                <?php if(get_field('image1', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_0"><img src="<?php the_field('image1', $post_id);?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image2', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_1"><img src="<?php the_field('image2', $post_id);?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image3', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_2"><img src="<?php the_field('image3', $post_id);?>" /></li>
                                                <?php }?>
                                                <?php if(get_field('image4', $post_id) != '') { ?>
                                                <li class="carousel_item" id="item_3"><img src="<?php the_field('image4', $post_id);?>" /></li>
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
                                        <a href="<?php echo esc_url(get_page_link($post_id));?>" class="btn" style="background-color:#FFFFFF;">
                                            <div class="left"></div>
                                            <div class="right" style="color: #666;">
                                                <!-- <span class="amount">Buy at Indiegogo</span> -->
                                                May 1st. Save up to 40%<br> Buy at Indiegogo
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <h2><?php the_field('model_type', $like_post_id);?></h2>
							</div>
							<?php } ?>
                        <?php
                                endwhile;
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
            <!-- End Also Like Section -->
        </div>
        
<?php get_footer();
