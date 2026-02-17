<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $color = get_field('color'); $colortext = '';
        if($color=='bg_purple') { 
            $colortext = ' grey'; 
        }
        $breadcrumbs = get_field('no_breadcrumbs');
?>
    <?php if(get_field('type') == 1) { ?>
        <div class="top_section<?php if($breadcrumbs) { echo ' top_home'; } ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="top_bg">
                <?php if(get_field('video')) { ?>
                <video autoplay muted playsinline loop>
                    <source src="<?php echo get_field('video'); ?>" type="video/mp4">
                </video>
                <?php } ?>
            </div>
            <div class="container">
                <div class="top_inner">
                    <div class="top_description">
                        <?php if(!$breadcrumbs) { lsx_breadcrumbs(); } ?>
                        <?php if($breadcrumbs && get_field('section')) { ?>
                        <p class="title_category grey fade_in"><?php echo get_field('section'); ?></p>
                        <?php } if($breadcrumbs && get_field('title')) { ?>
                        <h1 class="title_page grey fade_in"><?php echo get_field('title'); ?></h1>
                        <?php } ?>
                    </div>
                    <div class="top_row">
                        <div class="top_row_left">
                            <div class="top_row_description">
                                <?php if(!$breadcrumbs && get_field('section')) { ?>
                                <p class="title_category grey fade_in"><?php echo get_field('section'); ?></p>
                                <?php } if(!$breadcrumbs && get_field('title')) { ?>
                                <h1 class="title_page grey fade_in"><?php echo get_field('title'); ?></h1>
                                <?php } if(get_field('text')) { ?>
                                <div class="typical_text large grey fade_in">
                                    <?php echo get_field('text'); ?>
                                </div>
                                <?php } if(get_field('button') && get_field('link')) { ?>
                                <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="link fade_in" href="<?php echo get_field('link'); ?>"><?php echo get_field('button'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(have_rows('links')): ?>
                        <div class="top_row_right">
                            <div class="top_boxes">
                                <?php while(have_rows('links')): the_row(); ?>
                                <a <?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="top_box_item <?php echo get_sub_field('color'); ?> fade_in" href="<?php echo get_sub_field('link'); ?>">
                                    <p class="top_box_name"><?php echo get_sub_field('text'); ?></p>
                                    <div class="top_box_plus"></div>
                                    <?php if(get_sub_field('icon')) { $img = get_sub_field('icon'); ?>
                                    <img class="top_box_curve" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    <?php } ?>
                                </a>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } elseif(get_field('type') == 2) { ?>
        <div class="top_page <?php echo $color ?>">
            <div class="container">
                <div class="top_page_inner">
                    <?php if(!get_field('no_breadcrumbs')) { lsx_breadcrumbs(); } ?>
                    <div class="top_page_row">
                        <?php if(get_field('title')) { ?>
                        <h1 class="title_page<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h1>
                        <?php } if(get_field('text')) { ?>
                        <div class="typical_text large<?php echo $colortext; ?> fade_in">
                            <?php echo get_field('text'); ?>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if(get_field('button') && get_field('link')) { ?>
                    <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="link fade_in" href="<?php echo get_field('link'); ?>"><?php echo get_field('button'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } elseif(get_field('type') == 3) { ?>
        <div class="top_article top_page <?php echo $color ?>">
            <div class="container">
                <div class="top_page_inner">
                    <?php if(!get_field('no_breadcrumbs')) { lsx_breadcrumbs(); } ?>
                    <div class="top_page_row">
                        <?php if(get_field('title')) { ?>
                        <h1 class="title_section<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h1>
                        <?php } if(have_rows('list')): ?>
                        <div class="top_details">
                            <?php while(have_rows('list')): the_row(); ?>
                            <div class="top_detail_item<?php echo $colortext; ?> fade_in">
                                <p><?php echo get_sub_field('text_1'); ?></p>
                                <p><?php echo get_sub_field('text_2'); ?></p>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if(get_field('button') && get_field('link')) { ?>
                    <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="link fade_in" href="<?php echo get_field('link'); ?>"><?php echo get_field('button'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>