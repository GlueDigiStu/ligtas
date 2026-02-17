<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $color = get_field('color'); $colortext = '';
        if($color=='bg_purple') { 
            $colortext = ' grey'; 
        }
        $alignment = get_field('alignment');
?>
        <div class="content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(get_field('section') || get_field('title') || get_field('text') || get_field('button') && get_field('link')) { ?>
                <?php if(get_field('button') && get_field('link')) { ?>
                <div class="panel_content">
                    <div class="panel_content_left fade_left">
                        <div class="panel_content_description">
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
                    </div>
                    <div class="panel_content_right">
                        <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_right" href="<?php echo get_field('link'); ?>">
                            <?php echo get_field('button'); ?>
                            <svg class="svg_arrow_btn">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                            </svg>
                        </a>
                    </div>           
                </div>
                <?php } else { ?>
                <div class="info_description">
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
                <?php } ?>   
                <?php } if(get_field('image') || get_field('list') || get_field('description') || get_field('accordion') || get_field('button0') && get_field('link0')) { ?>
                <div class="info_content<?php if($alignment==2) { echo ' reverse'; } ?>">
                    <?php if(get_field('image')) { $img = get_field('image'); ?>
                    <div class="info_content_img fade_<?php if($alignment==2) { echo 'right'; } else { echo 'left'; } ?>">
                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                    </div>
                    <?php } ?>
                    <div class="info_content_about fade_<?php if($alignment==2) { echo 'left'; } else { echo 'right'; } ?>">
                        <div class="info_content_description">
                            <?php if(have_rows('accordion')): ?>
                            <div class="faq_info accordion">
                                <?php $i = 0; while(have_rows('accordion')): the_row(); $i++; ?>
                                <div class="faq_info_box accordion_box<?php if($i==1) { echo ' active'; } ?>">
                                    <div class="faq_info_trigger accordion_trigger<?php if($i==1) { echo ' active'; } ?>">
                                        <p class="faq_info_name<?php echo $colortext; ?>"><?php echo get_sub_field('title'); ?></p>
                                        <div class="faq_info_plus"></div>
                                    </div>
                                    <div class="faq_info_container toggle_container">
                                        <div class="faq_info_desc">
                                            <?php if(get_sub_field('text')) { ?>
                                            <div class="typical_text<?php echo $colortext; ?> small">
                                                <?php echo get_sub_field('text'); ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php endif; ?>
                            <?php if(have_rows('list')): ?>
                            <ul class="info_content_list">
                                <?php while(have_rows('list')): the_row(); ?>
                                <li><?php echo get_sub_field('text'); ?></li>
                                <?php endwhile; ?>
                            </ul>
                            <?php endif; ?>
                            <?php if(get_field('description')) { ?>
                            <div class="info_content_bottom">
                                <div class="typical_text<?php echo $colortext; ?>">
                                    <?php echo get_field('description'); ?>
                                </div>
                                <?php if(get_field('button0') && get_field('link0')) { ?>
                                <a<?php if(get_field('new0')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link0'); ?>">
                                    <?php echo get_field('button0'); ?>
                                    <svg class="svg_arrow_btn">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                    </svg>
                                </a>
                                <?php } ?>
                            </div>
                            <?php } elseif(get_field('button0') && get_field('link0')) { ?>
                            <a<?php if(get_field('new0')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link0'); ?>">
                                <?php echo get_field('button0'); ?>
                                <svg class="svg_arrow_btn">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                </svg>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
<?php } ?>