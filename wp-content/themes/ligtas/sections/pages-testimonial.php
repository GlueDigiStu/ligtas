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
        <div class="testimonials_section <?php echo $color.' '.get_field('indents'); ?>" 
            id=<?php 
            if(get_field('section')) { 
                echo str_replace(' ', '-', strtolower(get_field('section')));
            } elseif (get_field('title')) {
                echo str_replace(' ', '-', strtolower(get_field('title')));
            }
            ?>>
            <div class="testimonials_curve">
                <picture>
                    <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/testimonials_mobile.png">
                    <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/testimonials_left.png">
                    <img src="<?php bloginfo('template_url'); ?>/images/testimonials_left.png" alt="">
                </picture>
            </div>
            <div class="container">
                <?php if(get_field('title')) { ?>
                <h3 class="title_category fade_in"><?php echo get_field('title'); ?></h3>
                <?php } if(have_rows('testimonial')): ?>
                <div class="testimonials_slider swiper fade_in">
                    <div class="testimonials_nav">
                        <div class="testimonials_arrow testimonials_prev">
                            <svg class="svg_arrow_back">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_back"></use>
                            </svg>
                        </div>
                        <div class="testimonials_arrow testimonials_next">
                            <svg class="svg_arrow_small">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                            </svg>
                        </div>
                    </div>
                    <div class="swiper-wrapper">
                        <?php while(have_rows('testimonial')): the_row(); ?>
                        <div class="testimonial_item swiper-slide">
                            <div class="testimonial_item_wrap">
                                <div class="testimonial_item_left">
                                    <?php if(get_sub_field('photo')) { $img = get_sub_field('photo'); ?>
                                    <div class="testimonial_item_img">
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="testimonial_item_info">
                                    <?php if(get_sub_field('text')) { ?>
                                    <div class="testimonial_item_text">
                                        <?php echo get_sub_field('text'); ?>
                                    </div>
                                    <?php } if(get_sub_field('name') || get_sub_field('position')) { ?>
                                    <div class="testimonial_item_bottom">
                                        <?php if(get_sub_field('name')) { ?>
                                        <p class="testimonial_item_name"><?php echo get_sub_field('name'); ?></p>
                                        <?php } if(get_sub_field('position')) { ?>
                                        <p class="testimonial_item_position"><?php echo get_sub_field('position'); ?></p>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
<?php } ?>