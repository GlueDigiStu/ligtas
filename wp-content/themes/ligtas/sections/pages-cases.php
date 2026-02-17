<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $color = get_field('color'); $colortext = '';
        if($color=='bg_purple') { 
            $colortext = ' grey'; 
        }  
?>
        <div class="content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container_left">
                <div class="cs_row">
                    <div class="cs_row_info fade_left">
                        <div class="cs_row_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_block<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('description')) { ?>
                            <div class="typical_text<?php echo $colortext; ?>">
                                <?php echo get_field('description'); ?>
                            </div>
                            <?php } if(get_field('button') && get_field('link')) { ?>
                            <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link'); ?>">
                                <?php echo get_field('button'); ?>
                                <svg class="svg_arrow_btn">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                </svg>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php 
                        if(get_field('cases')) { 
                            $arg = array('post__in'=>get_field('cases'), 'post_type'=>'cases', 'orderby'=>'post__in', 'posts_per_page'=>-1);
                        } else {
                            $arg = array('post_type'=>'cases', 'posts_per_page'=>get_field('quantity'));
                            if(get_field('categories')) { 
                                $arg['tax_query'][] = array('taxonomy'=>'cases_cat', 'field'=>'id', 'terms'=>get_field('categories'));
                            } if(get_field('tags')) { 
                                $arg['tax_query'][] = array('taxonomy'=>'post_tag', 'field'=>'slug', 'terms'=>get_field('tags'));
                            }
                        }
                        $Nquery = new WP_Query($arg); if($Nquery->have_posts()): 
                    ?>
                    <div class="cs_carousel swiper fade_right">
                        <div class="swiper-wrapper">
                            <?php while($Nquery->have_posts()): $Nquery->the_post(); $id = get_the_ID(); $type = get_field('type',$id); ?>
                            <div class="cs_carousel_item swiper-slide">
                                <div class="cs_item <?php echo get_field('background',$id); ?> <?php echo $type; ?>">
                                    <?php if(get_field('decor',$id)) { $img = get_field('decor',$id); ?>
                                    <div class="cs_item_curve">
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    </div>
                                    <?php } if($type=='variant_1' && has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                                    <div class="cs_item_img">
                                        <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                                    </div>
                                    <?php } ?>
                                    <div class="cs_item_body">
                                    <?php if($type == 'variant_1') { ?>
                                        <div class="cs_item_details">
                                            <?php if(get_field('service',$id)) { ?>
                                            <p>Service: <strong><?php echo get_field('service',$id); ?></strong></p>
                                            <?php } if(get_field('sector',$id)) { ?>
                                            <p>Sector: <strong><?php echo get_field('sector',$id); ?></strong></p>
                                            <?php } ?>
                                        </div>
                                        <div class="cs_item_info <?php echo get_field('color',$id); ?>">
                                            <div class="cs_item_options">
                                                <!-- <?php if(get_field('property_type',$id)) { ?>
                                                <p>Property Type: <strong><?php echo get_field('property_type',$id); ?></strong></p>
                                                <?php } if(get_field('sector',$id)) { ?>
                                                <p>Sector: <strong><?php echo get_field('sector',$id); ?></strong></p>
                                                <?php } ?> -->
                                            </div>
                                            <div class="cs_item_description">
                                                <?php if(get_field('location',$id)) { ?>
                                                <p class="cs_item_location"><?php echo get_field('location',$id); ?></p>
                                                <?php } ?>
                                                <p class="cs_item_name"><?php the_title(); ?></p>
                                            </div>
                                            <div class="cs_item_more">
                                                <a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
                                                <div class="btn_arrow <?php echo str_replace('_light','',get_field('color',$id)); ?>">
                                                    <svg class="svg_arrow_small">
                                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } elseif($type == 'variant_2') { ?>
                                        <div class="cs_item_main">
                                            <div class="cs_item_details">
                                                <?php if(get_field('service',$id)) { ?>
                                                <p>Service: <strong><?php echo get_field('service',$id); ?></strong></p>
                                                <?php } if(get_field('sector',$id)) { ?>
                                                <p>Sector: <strong><?php echo get_field('sector',$id); ?></strong></p>
                                                <?php } ?>
                                            </div>
                                            <div class="cs_item_description">
                                                <?php if(get_field('location',$id)) { ?>
                                                <p class="cs_item_location"><?php echo get_field('location',$id); ?></p>
                                                <?php } ?>
                                                <p class="cs_item_name"><?php the_title(); ?></p>
                                            </div>
                                            <?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                                            <div class="cs_item_img">
                                                <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="cs_item_more">
                                            <a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
                                            <div class="btn_arrow">
                                                <svg class="svg_arrow_small">
                                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    <?php } elseif($type == 'variant_3') { ?>
                                        <div class="cs_item_details">
                                            <?php if(get_field('service',$id)) { ?>
                                            <p>Service: <strong><?php echo get_field('service',$id); ?></strong></p>
                                            <?php } if(get_field('sector',$id)) { ?>
                                            <p>Sector: <strong><?php echo get_field('sector',$id); ?></strong></p>
                                            <?php } ?>
                                        </div>
                                        <?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                                        <div class="cs_item_img">
                                            <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                                        </div>
                                        <?php } ?>
                                        <div class="cs_item_main">
                                            <div class="cs_item_description">
                                                <?php if(get_field('location',$id)) { ?>
                                                <p class="cs_item_location"><?php echo get_field('location',$id); ?></p>
                                                <?php } ?>
                                                <p class="cs_item_name"><?php the_title(); ?></p>
                                            </div>
                                            <div class="cs_item_more">
                                                <a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
                                                <div class="btn_arrow">
                                                    <svg class="svg_arrow_small">
                                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="cs_carousel_nav">
                            <div class="cs_carousel_arrow cs_carousel_prev">
                                <svg class="svg_arrow_prev">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_prev"></use>
                                </svg>
                            </div>
                            <div class="cs_carousel_arrow cs_carousel_next">
                                <svg class="svg_arrow_next">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <?php endif; wp_reset_query(); ?>
                </div>
            </div>
        </div>
<?php } ?>