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
                <div class="intro_wrap">
                    <div class="intro_left fade_left">
                        <div class="intro_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text<?php echo $colortext; ?>">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if(get_field('section_cta') || get_field('title_cta') || get_field('text_cta') || get_field('button_cta') && get_field('link_cta')) { ?>
                    <div class="intro_aside fade_right">
                        <div class="intro_cta">
                            <div class="intro_cta_info">
                                <?php if(get_field('section_cta')) { ?>
                                <h2 class="title_category"><?php echo get_field('section_cta'); ?></h2>
                                <?php } if(get_field('title_cta')) { ?>
                                <h3 class="intro_cta_title"><?php echo get_field('title_cta'); ?></h3>
                                <?php } if(get_field('text_cta')) { ?>
                                <div class="typical_text grey small">
                                    <?php echo get_field('text_cta'); ?>
                                </div>
                                <?php } if(get_field('button_cta') && get_field('link_cta')) { ?>
                                <a <?php if(get_field('new_cta')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link_cta'); ?>">
                                    <?php echo get_field('button_cta'); ?>
                                    <svg class="svg_arrow_btn">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                    </svg>
                                </a>
                                <?php } ?>
                            </div>
                            <div class="intro_cta_curve">
                                <img src="<?php bloginfo('template_url'); ?>/images/intro_cta_purple.png" alt="">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php } ?>