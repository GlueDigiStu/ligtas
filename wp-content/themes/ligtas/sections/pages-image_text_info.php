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
                <div class="about_content<?php if($alignment==2) { echo ' reverse'; } ?>">
                    <?php if(get_field('image')) { $img = get_field('image'); ?>
                    <div class="about_content_img fade_<?php if($alignment==2) { echo 'right'; } else { echo 'left'; } ?>">
                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                    </div>
                    <?php } ?>
                    <div class="about_content_info fade_<?php if($alignment==2) { echo 'left'; } else { echo 'right'; } ?>">
                        <div class="about_content_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?>"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('intro')) { ?>
                            <div class="typical_text large<?php echo $colortext; ?>">
                                <?php echo get_field('intro'); ?>
                            </div>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text small<?php echo $colortext; ?>">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } if(get_field('button') && get_field('link')) { ?>
                            <a <?php if(get_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link'); ?>">
                                <?php echo get_field('button'); ?>
                                <svg class="svg_arrow_btn">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                </svg>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>