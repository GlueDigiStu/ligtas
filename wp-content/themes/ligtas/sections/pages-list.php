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
                <?php if(have_rows('blocks')): ?>
                <div class="job_container">
                    <?php while(have_rows('blocks')): the_row(); ?>
                    <div class="job_info">
                        <?php if(get_sub_field('title')) { ?>
                        <h2 class="title_section<?php echo $colortext; ?> fade_in"><?php echo get_sub_field('title'); ?></h2>
                        <?php } if(get_sub_field('text')) { ?>
                        <div class="typical_text<?php echo $colortext; ?> large fade_in">
                            <?php echo get_sub_field('text'); ?>
                        </div>
                        <?php } if(have_rows('list')): ?>
                        <ul class="checklist<?php echo $colortext; ?> fade_in">
                            <?php while(have_rows('list')): the_row(); ?>
                            <li><?php echo get_sub_field('text'); ?> </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php endif; if(get_sub_field('link') && get_sub_field('button')) { ?>
                        <a <?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn fade_in" href="<?php echo get_sub_field('link'); ?>">
                            <?php echo get_sub_field('button'); ?> 
                            <svg class="svg_arrow_btn">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                            </svg>
                        </a>
                        <?php } ?>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

<?php } ?>