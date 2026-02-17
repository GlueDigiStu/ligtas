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
    <?php if(get_field('type') == 2) { ?>
        <div class="why_section content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(have_rows('intro')): ?>
                <div class="content_row">
                    <div class="intro_left">
                        <?php while(have_rows('intro')): the_row(); ?>
                        <div class="intro_description">
                            <?php if(get_sub_field('section')) { ?>
                            <h2 class="title_category fade_in"><?php echo get_sub_field('section'); ?></h2>
                            <?php } if(get_sub_field('title')) { ?>
                            <h3 class="title_section fade_in<?php echo $colortext; ?>"><?php echo get_sub_field('title'); ?></h3>
                            <?php } if(get_sub_field('text')) { ?>
                            <div class="typical_text small fade_in<?php echo $colortext; ?>">
                                <?php echo get_sub_field('text'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(get_field('section') || get_field('title') || get_field('text') || get_field('accordion') || get_field('description')) { ?>
                <div class="content_row">
                    <div class="content_row_left fade_left">
                        <?php if(get_field('section')) { ?>
                        <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                        <?php } ?>
                    </div>
                    <div class="content_row_right">
                        <?php if(get_field('title') || get_field('text') || get_field('image')) { ?>
                        <div class="why_description">
                            <?php if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text<?php echo $colortext; ?> fade_in">
                                <?php echo get_field('text'); ?>
                            </div>
                           <?php } if(get_field('image')) { $img = get_field('image'); ?>
                            <div class="why_img fade_in">
                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                            </div>
                            <?php } ?>
                        </div>
                        <?php } if(have_rows('accordion')): ?>
                        <div class="faq_accordion accordion">
                            <?php $i = 0; while(have_rows('accordion')): the_row(); $i++; ?>
                            <div class="faq_box accordion_box<?php if($i==1) { echo ' active'; } ?> fade_in">
                                <div class="faq_box_trigger accordion_trigger<?php if($i==1) { echo ' active'; } ?>">
                                    <p class="faq_box_name<?php echo $colortext; ?>"><?php echo get_sub_field('title'); ?></p>
                                    <div class="faq_box_plus"></div>
                                </div>
                                <div class="faq_box_container toggle_container">
                                    <div class="faq_box_desc">
                                        <?php if(get_sub_field('text')) { ?>
                                        <div class="typical_text small<?php echo $colortext; ?>">
                                            <?php echo get_sub_field('text'); ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>     
                        </div>
                        <?php endif; if(get_field('description')) { ?>
                        <div class="intro_description">
                            <div class="typical_text large fade_in<?php echo $colortext; ?>">
                                <?php echo get_field('description'); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="why_curve">
                <picture>
                    <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/why_purple_mobile.png">
                    <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/why_purple.png">
                    <img src="<?php bloginfo('template_url'); ?>/images/why_purple.png" alt="">
                </picture>
            </div>
        </div>
    <?php } else { ?>
        <div class="content_section <?php echo $color.' '.get_field('indents'); ?>"id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(have_rows('intro')): ?>
                <div class="content_row">
                    <div class="intro_left">
                        <?php while(have_rows('intro')): the_row(); ?>
                        <div class="intro_description">
                            <?php if(get_sub_field('section')) { ?>
                            <h2 class="title_category fade_in" id="<?php echo str_replace(' ', '-', strtolower(get_sub_field('section'))); ?>"><?php echo get_sub_field('section'); ?></h2>
                            <?php } if(get_sub_field('title')) { ?>
                            <h3 class="title_section fade_in<?php echo $colortext; ?>"><?php echo get_sub_field('title'); ?></h3>
                            <?php } if(get_sub_field('text')) { ?>
                            <div class="typical_text small fade_in<?php echo $colortext; ?>">
                                <?php echo get_sub_field('text'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(get_field('section') || get_field('title') || get_field('text') || get_field('accordion') || get_field('description') || get_field('image')) { ?>
                <div class="cs_solutions content_row">
                    <div class="content_row_right">
                        <?php if(get_field('section') || get_field('title') || get_field('text') || get_field('image')) { ?>
                        <div class="intro_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category fade_in" id="<?php echo str_replace(' ', '-', strtolower(get_field('section'))); ?>"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section fade_in<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text fade_in<?php echo $colortext; ?>">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } if(get_field('image')) { $img = get_field('image'); ?>
                            <div class="why_img fade_in">
                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                            </div>
                            <?php } ?>
                        </div>
                        <?php } if(have_rows('accordion')): ?>
                        <div class="faq_accordion accordion">
                            <?php $i = 0; while(have_rows('accordion')): the_row(); $i++; ?>
                            <div class="faq_box accordion_box<?php if($i==1) { echo ' active'; } ?> fade_in">
                                <div class="faq_box_trigger accordion_trigger<?php if($i==1) { echo ' active'; } ?>">
                                    <p class="faq_box_name<?php echo $colortext; ?>"><?php echo get_sub_field('title'); ?></p>
                                    <div class="faq_box_plus"></div>
                                </div>
                                <div class="faq_box_container toggle_container">
                                    <div class="faq_box_desc">
                                        <?php if(get_sub_field('text')) { ?>
                                        <div class="typical_text small<?php echo $colortext; ?>">
                                            <?php echo get_sub_field('text'); ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php endif; if(get_field('description')) { ?>
                        <div class="intro_description">
                            <div class="typical_text large fade_in<?php echo $colortext; ?>">
                                <?php echo get_field('description'); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <div class="intro_curve v_8">
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_mobile_1.png">
                        <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/intro_curve_8.png">
                        <img src="<?php bloginfo('template_url'); ?>/images/intro_curve_8.png" alt="">
                    </picture>
                </div>
            </div>
        </div>
    <?php } ?>

<?php } ?>