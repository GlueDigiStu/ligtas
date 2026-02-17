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
            <div class="container">
                <div class="intro_left">
                    <div class="intro_description">
                        <?php if(get_field('section')) { ?>
                        <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                        <?php } if(get_field('title')) { ?>
                        <h3 class="title_block fade_in<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                        <?php } if(get_field('text')) { ?>
                        <div class="typical_text small fade_in<?php echo $colortext; ?>">
                            <?php echo get_field('text'); ?>
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
                </div>
                <div class="intro_curve<?php if(get_field('decor')==3) { echo ' v_5'; } ?>">
                    <picture>
                      <?php if(get_field('decor') == 2) { ?>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_4.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_4.png" alt="">
                      <?php } elseif(get_field('decor') == 3) { ?>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_5.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_5.png" alt="">
                      <?php } elseif(get_field('decor') == 4) { ?>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_6.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_6.png" alt="">
                      <?php } elseif(get_field('decor') == 5) { ?>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_1.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_1.png" alt="">
                      <?php } elseif(get_field('decor') == 6) { ?>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_7.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_7.png" alt="">
                      <?php } else { ?>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_2.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_2.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_2.png" alt="">
                      <?php } ?>
                    </picture>
                </div>
            </div>
        </div>
<?php } ?>