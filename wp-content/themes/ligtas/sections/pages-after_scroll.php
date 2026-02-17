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
        <div class="sticky_section content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="sticky_content">
                    <div class="sticky_content_left">
                        <div class="sticky_content_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text<?php echo $colortext; ?>">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } if(get_field('blocks')) { ?>
                            <!-- <div class="sticky_content_count">
                                <span class="sticky_content_num">0</span> /
                                <span class="sticky_content_total">0</span>
                            </div> -->
                            <?php } ?>
                        </div>
                    </div>
                    <?php if(have_rows('blocks')): ?>
                    <div class="sticky_content_list">
                        <?php while(have_rows('blocks')): the_row(); $color = get_sub_field('color'); ?>
                        <div class="sticky_elem">
                            <div class="sticky_card <?php echo get_sub_field('type').' '.$color; ?>">
                                <div class="sticky_card_body">
                                    <div class="sticky_card_main">
                                        <div class="sticky_card_top">
                                            <?php if(get_sub_field('title')) { ?>
                                            <p class="sticky_card_name"><?php echo get_sub_field('title'); ?></p>
                                            <?php } if(get_sub_field('link')) { ?>
                                            <a <?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn_arrow" href="<?php echo get_sub_field('link'); ?>">
                                                <svg class="svg_arrow_small">
                                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                                </svg>
                                            </a>
                                            <?php } ?>
                                        </div>
                                        <?php if(get_sub_field('text')) { ?>
                                        <div class="typical_text<?php if(get_sub_field('grey')) { echo ' grey'; } ?>">
                                            <?php echo get_sub_field('text'); ?>
                                        </div>
                                        <?php } if(have_rows('list')): ?>
                                        <ul class="sticky_card_list">
                                            <?php while(have_rows('list')): the_row(); ?>
                                            <li><?php echo get_sub_field('text'); ?></li>
                                            <?php endwhile; ?>
                                        </ul>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(get_sub_field('link') && get_sub_field('button')) { ?>
                                    <a <?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="link_arrow" href="<?php echo get_sub_field('link'); ?>" text="Find Out More">
                                        <span><?php echo get_sub_field('button'); ?></span>
                                        <svg class="svg_arrow_btn">
                                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                        </svg>
                                    </a>
                                    <?php } ?>
                                </div>
                                <?php if(get_sub_field('image')) { $img = get_sub_field('image'); ?>
                                <div class="sticky_card_img">
                                    <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                </div>
                                <?php } else { ?>
                                <div class="sticky_card_curve">
                                    <picture>
                                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/sticky_<?php echo str_replace('_light','',$color); ?>_mobile.png">
                                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/sticky_curve_<?php echo str_replace('_light','',$color); ?>.png">
                                        <img src="<?php bloginfo('template_url'); ?>/images/sticky_curve_<?php echo str_replace('_light','',$color); ?>.png" alt="">
                                    </picture>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php endwhile; ?>                    
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php } ?>