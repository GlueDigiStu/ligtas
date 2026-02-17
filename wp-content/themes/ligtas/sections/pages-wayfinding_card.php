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
        <div class="cta_section <?php echo get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="cta_wrap<?php if(get_field('wider')) { echo ' wider'; } ?>">
                <?php if(get_field('image')) { $img = get_field('image'); ?>
                <div class="cta_img fade_left">
                    <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                </div>
                <?php } ?>
                <div class="cta_box <?php echo $color ?> fade_right">
                    <div class="cta_description">
                        <?php if(get_field('section')) { ?>
                        <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                        <?php } if(get_field('title')) { ?>
                        <h3 class="title_block"><?php echo get_field('title'); ?></h3>
                        <?php } if(get_field('text')) { ?>
                        <div class="typical_text grey small">
                            <?php echo get_field('text'); ?>
                        </div>
                        <?php } if(have_rows('list')): ?>
                        <ul class="checklist">
                            <?php while(have_rows('list')): the_row(); ?>
                            <li><?php echo get_sub_field('text'); ?></li>
                            <?php endwhile; ?>
                        </ul>
                        <?php endif; if(get_field('button') && get_field('link')) { ?>
                        <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="<?php if(get_field('type_link')) { echo 'link_arrow'; } else { echo 'btn'; } ?>" href="<?php echo get_field('link'); ?>">
                            <?php echo get_field('button'); ?>
                            <svg class="svg_arrow_btn">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                            </svg>
                        </a>
                        <?php } ?>
                    </div>
                    <div class="cta_box_curve">
                        <picture>
                            <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/cta_<?php echo $color ?>_mobile.png">
                            <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/cta_curve_<?php echo $color ?>.png">
                            <img src="<?php bloginfo('template_url'); ?>/images/cta_curve_<?php echo $color ?>.png" alt="">
                        </picture>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>