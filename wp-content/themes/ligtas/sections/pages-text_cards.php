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
                <?php if(get_field('button') && get_field('link')) { ?>
                <div class="panel_content">
                    <div class="panel_content_left fade_left">
                        <div class="panel_content_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="<?php if(get_field('big')) { echo 'title_section'; } else { echo 'title_block'; } echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel_content_right">
                        <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn btn_purple fade_right" href="<?php echo get_field('link'); ?>">
                            <?php echo get_field('button'); ?>
                            <svg class="svg_arrow_btn">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                            </svg>
                        </a>
                    </div>              
                </div>
                <?php } elseif(get_field('section') || get_field('title')) { ?>
                <div class="content_description mb_64">
                    <?php if(get_field('section')) { ?>
                    <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                    <?php } if(get_field('title')) { ?>
                    <h3 class="<?php if(get_field('big')) { echo 'title_section'; } else { echo 'title_block'; } echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                    <?php } ?>
                </div>
                <?php } if(have_rows('cards')): ?>
                <div class="cards_flex">
                    <?php while(have_rows('cards')): the_row(); ?>
                    <?php if(get_sub_field('link')) { ?>
                    <a <?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="card_flex_item <?php echo get_sub_field('color').' '.get_sub_field('type'); ?> fade_in" href="<?php echo get_sub_field('link'); ?>">
                    <?php } else { ?>
                    <span class="card_flex_item <?php echo get_sub_field('color').' '.get_sub_field('type'); ?> fade_in">
                    <?php } ?>
                        <?php if(get_sub_field('image')) { $img = get_sub_field('image'); ?>
                        <div class="card_flex_img">
                            <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                        </div>
                        <?php } ?>
                        <div class="card_flex_body">
                            <?php if(get_sub_field('section')) { ?>
                            <div class="card_flex_top">
                                <p class="title_category <?php echo get_sub_field('section_color'); ?>"><?php echo get_sub_field('section'); ?></p>
                            </div>
							<?php } else { ?>
    						<div class="card_flex_top"></div>
                            <?php } ?>
                            <div class="card_flex_bottom">
                                <div class="card_flex_description">
                                    <?php if(get_sub_field('title')) { ?>
                                    <p class="card_flex_name"><?php echo get_sub_field('title'); ?></p>
                                    <?php } if(get_sub_field('text')) { ?>
                                    <div class="card_flex_text">
                                        <?php echo get_sub_field('text'); ?>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php if(get_sub_field('link') && get_sub_field('button')) { ?>
                                <div class="card_flex_link">
                                    <span><?php echo get_sub_field('button'); ?></span>
                                    <div class="btn_arrow<?php if(get_sub_field('section_color')=='blue_light') { echo ' blue'; } ?>">
                                        <svg class="svg_arrow_small">
                                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                        </svg>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php if(get_sub_field('link')) { echo '</a>'; } else { echo '</span>'; }  ?>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
<?php } ?>