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
        <div class="training_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="switch_wrap">
                    <div class="switch_left fade_left">
                        <div class="switch_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
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
                        <?php if(have_rows('slider')): ?>
                        <div class="switch_nav">
                            <?php $i = 0; while(have_rows('slider')): the_row(); $i++; ?>
                            <?php if(get_sub_field('name')) { ?>
                            <div class="switch_nav_item<?php if($i==1) { echo ' current'; } ?>" data-num="<?php echo $i; ?>">
                                <p class="switch_nav_name"><?php echo get_sub_field('name'); ?></p>
                                <div class="switch_nav_arrow">
                                    <svg class="svg_arrow_small">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                    </svg>
                                </div>
                            </div>
                            <?php } ?>
                            <?php endwhile; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if(have_rows('slider')): ?>
                    <div class="switch_right fade_right">
                        <div class="switch_slider swiper">
                            <div class="swiper-wrapper">
                                <?php while(have_rows('slider')): the_row(); ?>
                                <div class="switch_item swiper-slide">
                                    <div class="switch_item_info <?php echo get_sub_field('color'); ?>_light">
                                        <div class="switch_item_curve">
                                            <img src="<?php bloginfo('template_url'); ?>/images/training_curve_<?php echo get_sub_field('color'); ?>.png" alt="">
                                        </div>
                                        <?php if(get_sub_field('note')) { ?>
                                        <div class="switch_item_top">
                                            <span class="switch_item_num"><?php echo get_sub_field('note'); ?></span>
                                        </div>
                                        <?php } ?>
                                        <div class="switch_item_bottom">
                                            <?php if(get_sub_field('title')) { ?>
                                            <p class="switch_item_name"><?php echo get_sub_field('title'); ?></p>
                                            <?php } if(get_sub_field('text')) { ?>
                                            <div class="typical_text small">
                                                <?php echo get_sub_field('text'); ?>
                                            </div>
                                            <?php } if(get_sub_field('link')) { ?>
                                            <div class="switch_item_row">
                                                <?php if(get_sub_field('button')) { ?>
                                                <a<?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="switch_item_link" href="<?php echo get_sub_field('link'); ?>"><?php echo get_sub_field('button'); ?></a>
                                                <?php } ?>
                                                <a<?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn_arrow" href="<?php echo get_sub_field('link'); ?>">
                                                    <svg class="svg_arrow_small">
                                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php if(get_sub_field('image')) { $img = get_sub_field('image'); ?>
                                    <div class="switch_item_img">
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php } ?>