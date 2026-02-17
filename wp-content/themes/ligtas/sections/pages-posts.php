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
        <div class="news_section content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(get_field('section') || get_field('title') || get_field('button') && get_field('link')) { ?>
                <div class="panel_content">
                    <div class="panel_content_left fade_left">
                        <div class="panel_content_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if(get_field('button') && get_field('link')) { ?>
                    <div class="panel_content_right fade_right">
                        <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link'); ?>">
                            <?php echo get_field('button'); ?>
                            <svg class="svg_arrow_btn">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                            </svg>
                        </a>
                    </div> 
                    <?php } ?>             
                </div>
                <?php } ?>
                <?php 
                    if(get_field('cases')) { 
                        $arg = array('post__in'=>get_field('cases'), 'post_type'=>'post', 'orderby'=>'post__in', 'posts_per_page'=>-1);
                    } else {
                        $arg = array('post_type'=>'post', 'posts_per_page'=>get_field('quantity'));
                        if(get_field('categories')) { 
                            $arg['tax_query'][] = array('taxonomy'=>'category', 'field'=>'id', 'terms'=>get_field('categories'));
                        } if(get_field('tags')) { 
                            $arg['tax_query'][] = array('taxonomy'=>'post_tag', 'field'=>'slug', 'terms'=>get_field('tags'));
                        }
                    }
                    $Nquery = new WP_Query($arg); if($Nquery->have_posts()): 
                ?>
                <div class="posts_slider swiper fade_in">
                    <div class="swiper-wrapper">
                        <?php $i = 0; while($Nquery->have_posts()): $Nquery->the_post(); $i++; $category = get_the_category(get_the_ID()); ?>
                        <div class="post_slide <?php if($i%2===0) { echo 'even'; } else { echo 'odd'; } ?> swiper-slide">
                            <a class="post_item" href="<?php the_permalink(); ?>">
                                <div class="post_item_top">
                                    <div class="post_item_details">
                                        <p class="cats"><?php 
                                            $cat_short = get_field('short_title','category_'.$category[0]->cat_ID);
                                            echo esc_html( $cat_short ? $cat_short : get_cat_name($term_id) );
                                        ?></p>
                                        <!-- <p class="tags"><?php 
                                            $tags = get_the_tags();
                                            if($tags && !is_wp_error($tags)) {
                                                echo esc_html( join(', ', wp_list_pluck($tags, 'name')) );
                                            }
                                        ?></p> -->
                                        <p><?php the_time("F Y"); ?></p>
                                    </div>
                                    <?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                                    <div class="post_item_img">
                                        <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="post_item_info">
                                    <p class="post_item_name"><?php the_title(); ?></p>
                                    <div class="post_item_more">
                                        <span class="post_item_link">Learn More</span>
                                        <span class="post_item_arrow">
                                            <svg class="svg_arrow_post">
                                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_post"></use>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="posts_nav">
                        <div class="posts_arrow posts_prev">
                            <svg class="svg_arrow_prev">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_prev"></use>
                            </svg>
                        </div>
                        <div class="posts_arrow posts_next">
                            <svg class="svg_arrow_next">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <?php endif; wp_reset_query(); ?>
            </div>
        </div>
<?php } ?>