<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $color = get_field('color'); $colortext = '';
        if($color=='bg_purple') { 
            $colortext = ' grey'; 
        }
        $type = get_field('type');
?>
    <?php if($type == 3) { ?>
        <div class="content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="benefits_wrap">
                    <div class="benefits_left fade_left">
                        <?php if(get_field('section')) { ?>
                        <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                        <?php } ?>
                    </div>
                    <div class="benefits_right">
                        <?php if(get_field('title') || get_field('text')) { ?>
                        <div class="benefits_description">
                            <?php if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text<?php echo $colortext; ?> fade_in">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } if(have_rows('blocks')): ?>
                        <div class="compliance_wrap">
                            <?php while(have_rows('blocks')): the_row(); ?>
                            <div class="compliance_item fade_in">
                                <?php if(get_sub_field('icon')) { $img = get_sub_field('icon'); ?>
                                <div class="compliance_item_check">
                                    <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                </div>
                                <?php } if(get_sub_field('title')) { ?>
                                <p class="compliance_item_text"><?php echo get_sub_field('title'); ?></p>                
                                <?php } if(get_sub_field('text')) { ?>
                                <div class="typical_text small <?php echo $colortext; ?>">
                                    <?php echo get_sub_field('text'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php endif; ?>
                        <?php if(get_field('note') || get_field('button') && get_field('link')) { ?>
                        <div class="compliance_bottom">
                            <?php if(get_field('note')) { ?>
                            <div class="typical_text large<?php echo $colortext; ?> fade_in">
                                <?php echo get_field('note'); ?>
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
            </div>
        </div>
    <?php } else { ?>  
        <div class="compliance_section content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(get_field('section') || get_field('title') || get_field('text')) { ?>
                <div class="compliance_description">
                    <?php if(get_field('section')) { ?>
                    <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                    <?php } if(get_field('title')) { ?>
                    <h3 class="title_section<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                    <?php } if(get_field('text')) { ?>
                    <div class="typical_text<?php echo $colortext; ?> fade_in">
                        <?php echo get_field('text'); ?>
                    </div>
                    <?php } ?>
                </div>  
                <?php } if(get_field('decor') == 2) { ?>
                <div class="compliance_curve">
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/compliance_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/compliance_curve_1.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/compliance_curve_1.png" alt="">
                    </picture>
                </div>
                <?php } elseif(get_field('decor') == 3) { ?>
                <div class="compliance_curve v_2">
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/compliance_mobile_2.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/compliance_curve_2.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/compliance_curve_2.png" alt="">
                    </picture>
                </div>
                <?php } else { ?>
                <div class="compliance_curve v_3">
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/compliance_mobile_2.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/compliance_curve_3.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/compliance_curve_3.png" alt="">
                    </picture>
                </div>
                <?php } if(get_field('blocks') || get_field('note') || get_field('button') && get_field('link')) { ?> 
                <div class="compliance_container<?php if($type!=2) { echo ' small'; } ?>">
                    <?php if(have_rows('blocks')): ?>
                    <div class="compliance_wrap<?php if($type!=2) { echo ' row_two'; } ?>">
                        <?php while(have_rows('blocks')): the_row(); ?>
                        <div class="compliance_item fade_in">
                          <?php if($type == 2) { ?>
                            <?php if(get_sub_field('icon')) { $img = get_sub_field('icon'); ?>
                            <div class="compliance_item_check">
                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                            </div>
                            <?php } if(get_sub_field('title')) { ?>
                            <p class="compliance_item_text"><?php echo get_sub_field('title'); ?></p>
                            <?php } ?>
                          <?php } else { ?>
                            <?php if(get_sub_field('icon')) { $img = get_sub_field('icon'); ?>
                            <div class="compliance_item_icon">
                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                            </div>
                            <?php } if(get_sub_field('title')) { ?>
                            <p class="compliance_item_name"><?php echo get_sub_field('title'); ?></p>
                            <?php } ?>
                          <?php } ?>
                            <?php if(get_sub_field('text')) { ?>
                            <div class="typical_text small <?php echo $colortext; ?>">
                                <?php echo get_sub_field('text'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>
                    <?php if(get_field('note') || get_field('button') && get_field('link')) { ?>
                    <div class="compliance_bottom">
                        <?php if(get_field('note')) { ?>
                        <div class="typical_text large<?php echo $colortext; ?> fade_in">
                            <?php echo get_field('note'); ?>
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
                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>