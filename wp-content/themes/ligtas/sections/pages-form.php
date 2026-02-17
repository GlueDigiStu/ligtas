<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
?>
        <div class="contact_section" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <div class="container">
                <div class="contact_wrap">
                    <div class="contact_info fade_left">
                        <div class="contact_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category purple"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_block"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('description')) { ?>
                            <div class="typical_text">
                                <?php echo get_field('description'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="contact_form fade_right">
                        <?php echo get_field('form'); ?>                        
                    </div>
                </div>
            </div>
        </div>
<?php } ?>