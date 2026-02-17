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
        <div class="<?php if(get_field('type')==2) { echo 'we_do'; } else { echo 'content_section'; } ?> <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="content_row">
                    <div class="content_row_left fade_left">
                        <?php if(get_field('section')) { ?>
                        <h2 class="title_category"><?php echo get_field('section'); ?></h2>
                        <?php } ?>
                    </div>
                    <div class="content_row_right<?php if(get_field('type')==2) { echo ' fade_right'; } ?>">
                        <div class="content_row_description">
                            <?php if(get_field('title')) { ?>
                            <h3 class="<?php if(get_field('big')) { echo 'title_section'; } else { echo 'title_block'; } echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } if(get_field('button') && get_field('link')) { ?>
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
            </div>
        </div>
<?php } ?>