<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $color = get_field('color'); $colortext = '';
        if($color=='bg_purple' || $color=='bg_blue' || $color=='bg_green') { 
            $colortext = ' grey'; 
        }
?>
    <?php if(get_field('type') == 2) { ?>
        <div class="journey_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(get_field('section') || get_field('title')) { ?>
                <div class="journey_description">
                    <?php if(get_field('section')) { ?>
                    <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                    <?php } if(get_field('title')) { ?>
                    <h3 class="title_block<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                    <?php } ?>
                </div>
                <?php }if(have_rows('blocks0')): ?>
                <div class="journey_wrap">
                    <div class="journey_left">
                        <?php $i = 0; while(have_rows('blocks0')): the_row(); $i++; ?>
                        <div class="journey_item" data-num="<?php echo $i; ?>">
                            <span class="journey_item_num"><?php if($i<10) { echo '0'.$i; } else { echo $i; } ?></span>
                            <div class="journey_item_info">
                                <div class="journey_item_description">
                                    <?php if(get_sub_field('title')) { ?>
                                    <p class="journey_item_name"><?php echo get_sub_field('title'); ?></p>
                                    <?php } if(get_sub_field('text')) { ?>
                                    <div class="typical_text">
                                        <?php echo get_sub_field('text'); ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="journey_item_img">
                                <?php if(get_sub_field('image')) { $img = get_sub_field('image'); ?>
                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                <?php } ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="journey_right">
                        <div class="journey_sticky">
                            <div class="journey_slider swiper swiper-container">
                                <div class="swiper-wrapper">
                                    <?php while(have_rows('blocks0')): the_row(); ?>
                                    <div class="journey_slider_item swiper-slide">
                                        <?php if(get_sub_field('image')) { $img = get_sub_field('image'); ?>
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                        <?php } ?>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(get_field('description') || get_field('button') && get_field('link')) { ?>
                <div class="process_bottom">
                    <?php if(get_field('description')) { ?>
                    <div class="process_bottom_text fade_in">
                        <?php echo get_field('description'); ?>
                    </div>
                    <?php } if(get_field('button') && get_field('link')) { ?>
                    <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_in" href="<?php echo get_field('link'); ?>">
                        <?php echo get_field('button'); ?>
                        <svg class="svg_arrow_btn">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                        </svg>
                    </a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="process_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));}?>>
            <div class="container">
                <?php if(get_field('section') || get_field('title')) { ?>
                <div class="process_description">
                    <?php if(get_field('section')) { ?>
                    <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                    <?php } if(get_field('title')) { ?>
                    <h3 class="title_block<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                    <?php } ?>
                </div>
                <?php } if(get_field('blocks') || get_field('video')) { ?>
                <div class="process_wrap">
                    <?php if(have_rows('blocks')): ?>
                    <div class="process_steps">
                        <div class="process_steps_list">
                            <div class="process_steps_line"></div>
                            <?php $i = 0; while(have_rows('blocks')): the_row(); $i++; ?>
                            <div class="process_step_item">
                                <span class="process_step_num"><?php if($i<10) { echo '0'.$i; } else { echo $i; } ?></span>
                                <div class="process_step_description">
                                    <?php if(get_sub_field('title')) { ?>
                                    <h3 class="process_step_name"><?php echo get_sub_field('title'); ?></h3>
                                    <?php } if(get_sub_field('text')) { ?>
                                    <div class="process_step_text">
                                        <?php echo get_sub_field('text'); ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endif; if(get_field('video')) { ?>
                    <div class="process_steps_video">
                        <video muted playsinline>
                            <source src="<?php echo get_field('video'); ?>" type="video/mp4">
                        </video>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if(get_field('description') || get_field('button') && get_field('link')) { ?>
                <div class="process_bottom">
                    <?php if(get_field('description')) { ?>
                    <div class="process_bottom_text fade_in">
                        <?php echo get_field('description'); ?>
                    </div>
                    <?php } if(get_field('button') && get_field('link')) { ?>
                    <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_in" href="<?php echo get_field('link'); ?>">
                        <?php echo get_field('button'); ?>
                        <svg class="svg_arrow_btn">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                        </svg>
                    </a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>