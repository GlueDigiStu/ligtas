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
                <div class="article_container">
                    <?php if(get_field('text')) { ?>
                    <div class="typical_text<?php echo (get_field('large')) ? " large" : " small"; echo $colortext; ?> fade_in">
                        <?php echo get_field('text'); ?>
                    </div>
                    <?php } if(have_rows('list')): ?>
                    <ul class="checklist<?php echo $colortext; ?> fade_in">
                        <?php while(have_rows('list')): the_row(); ?>
                        <li><?php echo get_sub_field('text'); ?></li>
                        <?php endwhile; ?>
                    </ul>
                    <?php endif; if(get_field('image')) { $img = get_field('image');  ?>
                    <div class="typical_text fade_in">
                        <p><img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>"></p>
                    </div>
                    <?php } ?>
                    <?php if(get_field('button') && get_field('email')) { ?>
                        <a class="btn" href="mailto:<?php echo get_field('email'); ?>"><?php echo get_field('button'); ?>
                            <svg class="svg_arrow_btn">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                            </svg>
                        </a>
					<?php } elseif (get_field('button') && get_field('link')) { ?>
						<a class="btn" href="<?php echo esc_url(get_field('link')); ?>">
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