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
                            <h2 class="title_category" id="<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="<?php if(get_field('big')) { echo 'testimonial_item_text'; } else { echo 'typical_text'; } echo $colortext; ?>">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } if(get_field('name') || get_field('position')) { ?>
                            <div class="testimonial_item_bottom">
                                <?php if(get_field('name')) { ?>
                                <p class="testimonial_item_name<?php echo $colortext; ?>"><?php echo get_field('name'); ?></p>
                                <?php } if(get_field('position')) { ?>
                                <p class="testimonial_item_position<?php echo $colortext; ?>"><?php echo get_field('position'); ?></p>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if(have_rows('links')): ?>
                    <div class="intro_aside fade_right">
                        <nav class="page_nav">
                            <ul>
                                <?php while(have_rows('links')): the_row(); ?>
                                <li>
								<?php 
									$anchor_id = str_replace([' ', '.'], '-', strtolower(get_sub_field('link')));
									$full_url = get_permalink() . '#' . $anchor_id;
								?>
                                <a <?php if(get_sub_field('new')) { echo ' target=“_blank”'; } ?>
                                    class="scroll_anchor<?php echo $colortext; ?>"
                                    href="<?php echo esc_url($full_url); ?>">
                                    <?php echo get_sub_field('anchor'); ?>
                                    <svg class="svg_arrow_next">
                                        <use
                                            xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next">
                                        </use>
                                    </svg>
                                </a>
                                </li>
                                <?php endwhile; ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
<?php } ?>