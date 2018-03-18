<?php
/**
 * The template for displaying all single product
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Separate_Blog
 */

get_header(); ?>
	<div class="main-container detail-template post-<?php the_ID(); ?>">
            <div class="buyModule">
			<?php
				the_post();
				$post_id = the_ID();
				$current_post_id = $post->ID;
				$post_type = get_field('model_type', $post_id);
			?>
                <section class="ecom-buy-module js-ecom standard-view">
                    <div class="content-container">
                        <div class="ecom-buy-module-header mobile-only">
                            <h1><?php the_title();?></h1>
                            <p><?php the_excerpt();?></p>
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
                                    <div class="ecom-buy-module-sidebar-row with-border ecom-buy-module-sidebar-prices space-large clearfix js-total-prices">
                                        <div class="inner">
                                            <div class="cols">
                                                <div class="col">
                                                    <div class="ecom-buy-module-price js-normal-price">
                                                        <span class="currency">USD</span>
                                                        <span class="price"><?php the_field('price', $post_id);?></span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <!-- button-purchase (green version) -->
                                                    <a href="<?php the_field('indiegogo_url', $post_id);?>" class="button button-primary js-buy-button">Add to cart</a>
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
                <div class="block" data-theme="0" data-bg="#8daebd"  data-bgimg="<?php echo get_option_tree($i.'_slider_image_e_model');?>" data-mbgimg="<?php echo get_option_tree($i.'_slider_image_mobile_e_model');?>">
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
                <div class="block" data-theme="0" data-bg="#8daebd"  data-bgimg="<?php echo get_option_tree($i.'_slider_image_8_speed');?>" data-mbgimg="<?php echo get_option_tree($i.'_slider_image_mobile_8_speed');?>">
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
            <!-- Tech Section -->
            <div id="TextSpot2Column">
                <div class="moveaSplitText" >
                    <h2><?php echo get_option_tree('title_geometry');?></h2>
                    <h3><a href="#headphones" style="color: inherit;"><?php echo get_option_tree('sub_title_geometry');?></a></h3>
                </div>
            </div>
            <div class="moveaTechSpec">
                <div class="techImgWrapper">
                    <div class="img_wrapper">
                        <img class="" src="<?php echo get_option_tree('background_image_geometry');?>" >
                    </div>
                    <div class="txt_wrapper">
                        <div class="txt_block">
                            <div class="title">Geometry Men 20" 24"</div>
                            <ul>
                                <li>A) Seat tube angle</li>
                                <li>B) Top-tube length</li>
                                <li>C) Seat-tube length</li>
                                <li>D) Wheel base</li>
                                <li>E) Head-tube length</li>
                                <li>F) Stand over high</li>
                            </ul>
                        </div>
                        <div class="txt_block">
                            <div class="title">Geometry Lady 20" 24"</div>
                            <ul>
                                <li>A) Seat tube angle</li>
                                <li>B) Top-tube length</li>
                                <li>C) Seat-tube length</li>
                                <li>D) Wheel base</li>
                                <li>E) Head-tube length</li>
                                <li>F) Stand over high</li>
                            </ul>
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
            <div class="moveaSupportVideo" id="supportVideo1" data-videoId="<?php echo get_option_tree('youtube_video_id_support_video');?>">
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
                                                <li class="carousel_item" id="item_0"><img class="lazy" src="<?php the_field('image1', $like_post_id);?>" /></li>
                                                <li class="carousel_item" id="item_1"><img class="lazy" src="<?php the_field('image2', $like_post_id);?>" /></li>
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
                                                <span class="currency">USD</span><span class="amount"><?php the_field('price', $like_post_id);?></span>
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
        <div class="ind-gallery" id="ind_gallery" style="display: none;">
            <div class="ind-close">&times;</div>
            <div class="gallery-container">
                <div class="carousel_container" id="product">       
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
<?php get_footer();
