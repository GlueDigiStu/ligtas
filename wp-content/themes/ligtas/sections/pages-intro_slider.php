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
        <div class="we_are <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="we_are_info">
                    <?php if(get_field('section') || get_field('title') || get_field('text')) { ?>
                    <div class="we_are_description">
                        <?php if(get_field('section')) { ?>
                        <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                        <?php } if(get_field('title')) { ?>
                        <h3 class="title_block<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                        <?php } if(get_field('text')) { ?>
                        <div class="typical_text<?php echo $colortext; ?> small fade_in">
                            <?php echo get_field('text'); ?>                      
                        </div>
                        <?php } ?>
                    </div>
                    <?php } if(have_rows('slider')): ?>
                    <div class="info_slider swiper fade_in">
                        <div class="info_dots"></div>
                        <div class="swiper-wrapper">
                            <?php while(have_rows('slider')): the_row(); ?>
                            <div class="info_slide_item swiper-slide">
                                <p class="info_slide_name"><?php echo get_sub_field('title'); ?></p>
                                <div class="typical_text small purple_light">
                                    <?php echo get_sub_field('text'); ?>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endif; if(get_field('button') && get_field('link')) { ?>
                    <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_in" href="<?php echo get_field('link'); ?>">
                        <?php echo get_field('button'); ?>
                        <svg class="svg_arrow_btn">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                        </svg>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <div class="we_are_curve">
                <picture>
                    <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/we_are_mobile.png">
                    <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/we_are_curve.png">
                    <img src="<?php bloginfo('template_url'); ?>/images/we_are_curve.png" alt="">
                </picture>
            </div>
        </div>
<?php } ?>