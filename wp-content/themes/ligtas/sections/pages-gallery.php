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
            <div class="gallery_block">
                <?php if(have_rows('gallery')): ?>
                <div class="gallery_slider swiper fade_in">
                    <div class="swiper-wrapper">
                        <?php while(have_rows('gallery')): the_row(); $img = get_sub_field('image'); ?>
                        <div class="gallery_item swiper-slide">
                            <div class="gallery_item_img">
                                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="gallery_arrow gallery_prev">
                        <svg class="svg_arrow_prev">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_prev"></use>
                        </svg>
                    </div>
                    <div class="gallery_arrow gallery_next">
                        <svg class="svg_arrow_next">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next"></use>
                        </svg>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
<?php } ?>