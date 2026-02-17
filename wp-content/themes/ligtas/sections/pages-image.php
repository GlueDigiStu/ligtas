<?php 
    // if( !empty($block['anchor']) ) { $id = ' id="'.$block['anchor'].'"'; } else { $id = ''; }	
    if(is_admin()) {
    	echo '<hr><h2>'.$block['title'].'</h2><hr>';
    } else {
        $color = get_field('color'); $colortext = '';
        if($color=='bg_purple') { 
            $colortext = ' grey'; 
        }
        $img = get_field('image');
?>
        <div class="top_img <?php echo $color.' '.get_field('indents'); ?>" id=<?php if(get_field('section')) { echo str_replace(' ', '-', strtolower(get_field('section')));} ?>>
            <?php if(get_field('container')) { echo '<div class="container">'; } ?>
            <div class="top_img_inner fade_in">
                <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
            </div>
            <?php if(get_field('container')) { echo '</div>'; } ?>
        </div>
<?php } ?>