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
                    <div class="intro_left">
                        <div class="intro_description">
                            <?php if(get_field('section')) { ?>
                            <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
                            <?php } if(get_field('title')) { ?>
                            <h3 class="title_section<?php echo $colortext; ?> fade_in"><?php echo get_field('title'); ?></h3>
                            <?php } if(get_field('text')) { ?>
                            <div class="typical_text<?php echo $colortext; ?> fade_in">
                                <?php echo get_field('text'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>