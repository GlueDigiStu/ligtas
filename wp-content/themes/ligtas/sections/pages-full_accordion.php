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
        <div class="content_section <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace([' ', '?', '.'], '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <?php if(get_field('section') || get_field('title')) { ?>
                <div class="<?php if(get_field('big')) { echo 'content_description'; } else { echo 'services_description'; }?>">
                    <?php if(get_field('section')) { ?>
                    <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                    <?php } if(get_field('big') && get_field('title')) { ?>
                    <h3 class="title_section fade_in"><?php echo get_field('title'); ?></h3>
                    <?php } elseif(get_field('title')) { ?>
                    <div class="typical_text large<?php echo $colortext; ?> fade_in">
                        <p><?php echo get_field('title'); ?></p>
                    </div>
                    <?php } if(get_field('text')) { ?>
                    <div class="typical_text<?php echo $colortext; ?> fade_in">
                        <?php echo get_field('text'); ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } if(have_rows('accordion')): ?>
                <div class="faq_accordion full accordion">
                    <?php $i = 0; while(have_rows('accordion')): the_row(); $i++; ?>
                    <div class="faq_box accordion_box<?php if($i==1) { echo ' active'; } ?> fade_in">
                        <div class="faq_box_trigger accordion_trigger<?php if($i==1) { echo ' active'; } ?>">
                            <p class="faq_box_name"><?php echo get_sub_field('title'); ?></p>
                            <div class="faq_box_arrow">
                                <svg class="svg_arrow_accordion">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="faq_box_container toggle_container">
                            <div class="faq_box_desc">
                                <?php if(get_sub_field('text') || get_sub_field('image') || get_sub_field('list_small') || get_sub_field('list0') || get_sub_field('button') && get_sub_field('link')) { ?>
                                <div class="service_about_wrap">
                                    <?php if(get_sub_field('text') || get_sub_field('list0') || get_sub_field('list_small') || get_sub_field('button') && get_sub_field('link')) { ?> 
                                    <div class="service_about_left">
                                        <?php if(get_sub_field('text') || get_sub_field('list0')) { ?> 
                                        <div class="service_about_description"> 
                                            <?php if(get_sub_field('text')) { ?>                                           
                                            <div class="typical_text<?php echo $colortext; ?>">
                                                <?php echo get_sub_field('text'); ?>
                                            </div>
                                            <?php } if(have_rows('list_small')): ?>
                                            <div class="typical_text<?php echo $colortext; ?> small">
                                                <ul>
                                                    <?php while(have_rows('list_small')): the_row(); ?>
                                                    <li><?php echo get_sub_field('text'); ?></li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            </div>
                                            <?php endif; if(have_rows('list0')): ?>
                                            <ul class="checklist<?php echo $colortext; ?> small">
                                                <?php while(have_rows('list0')): the_row(); ?>
                                                <li><?php echo get_sub_field('text'); ?></li>
                                                <?php endwhile; ?>
                                            </ul>
                                            <?php endif; ?>
                                        </div>
                                        <?php } if(get_sub_field('button') && get_sub_field('link')) { ?> 
                                        <a <?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_sub_field('link'); ?>">
                                            <?php echo get_sub_field('button'); ?>
                                            <svg class="svg_arrow_btn">
                                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                            </svg>
                                        </a>
                                        <?php } ?>
                                    </div>
                                    <?php } if(get_sub_field('image')) { $img = get_sub_field('image'); ?>
                                    <div class="service_about_img">
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } if(have_rows('list')): ?>
                                <div class="certification_list">
                                    <?php while(have_rows('list')): the_row(); ?>
                                    <div class="certification_item">
                                        <p class="certification_item_name"><?php echo get_sub_field('text'); ?></p>
                                        <?php if(get_sub_field('icon')) { $img = get_sub_field('icon'); ?>
                                        <div class="certification_item_icon">
                                            <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                        </div>
                                        <?php } ?>
                                    </div>
									<?php if(get_sub_field('link_out_button_url')) { ?>
									<a class="btn certification_item_button" href="<?php echo esc_url(get_sub_field('link_out_button_url')); ?>">
										<?php echo get_sub_field('link_out_button_text'); ?>
										<svg class="svg_arrow_btn">
											<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
										</svg>
									</a>
									<?php } ?>
                                    <?php endwhile; ?> 
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
                <?php if(get_field('section_info') || get_field('title_info') || get_field('description') || get_field('button') && get_field('link')) { ?>
                <div class="services_contact">
                    <div class="services_contact_left">
                        <?php if(get_field('section_info')) { ?>
                        <h2 class="title_category"><?php echo get_field('section_info'); ?></h2>
                        <?php } ?>
                    </div>
                    <div class="services_contact_info">
                        <div class="services_contact_description">
                            <?php if(get_field('title_info')) { ?>
                            <h3 class="title_block<?php echo $colortext; ?> fade_in"><?php echo get_field('title_info'); ?></h3>
                            <?php } if(get_field('description')) { ?>
                            <div class="typical_text large<?php echo $colortext; ?> fade_in">
                                <?php echo get_field('description'); ?>
                            </div>
                            <?php } if(get_field('phone')) { ?>
                            <div class="services_contact_buttons">
                                <?php if(get_field('button') && get_field('link')) { ?>
                                <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_in" href="<?php echo get_field('link'); ?>">
                                    <?php echo get_field('button'); ?>
                                    <svg class="svg_arrow_btn">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                    </svg>
                                </a>
                                <?php } if(get_field('phone')) { ?>
                                <a class="services_contact_link fade_in" href="tel:<?php echo str_replace(array(' ','(',')','-'),'',get_field('phone')); ?>">
                                    <svg class="svg_tel_icon">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#tel_icon"></use>
                                    </svg>
                                    <span><?php echo get_field('phone'); ?></span>
                                </a>
                                <?php } ?>
                            </div>
                            <?php } elseif(get_field('button') && get_field('link')) { ?>
                            <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_in" href="<?php echo get_field('link'); ?>">
                                <?php echo get_field('button'); ?>
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