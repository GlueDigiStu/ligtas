<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $background = get_field('background');
?>
        <div class="content_section pt_no <?php echo $background.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="contact_cta fade_in">
                    <div class="contact_cta_body">
                        <div class="contact_cta_info">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="contact_cta_title"><?php echo get_field('title'); ?></h3>
                            <?php } ?>
                            <div class="contact_cta_buttons">
                                <?php if(get_field('button') && get_field('link')) { ?>
                                <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link'); ?>">
                                    <?php echo get_field('button'); ?>
                                    <svg class="svg_arrow_btn">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                    </svg>
                                </a>
                                <?php } if(get_field('phone')) { ?>
                                <a class="contact_cta_link" href="tel:<?php echo str_replace(array(' ','(',')','-'),'',get_field('phone')); ?>">
                                    <svg class="svg_tel_icon">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#tel_icon"></use>
                                    </svg>
                                    <span><?php echo get_field('phone'); ?></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="contact_cta_curve">
                        <picture>
                            <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/contact_cta_mobile.png">
                            <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/contact_cta_curve.png">
                            <img src="<?php bloginfo('template_url'); ?>/images/contact_cta_curve.png" alt="">
                        </picture>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>