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
                <?php if(have_rows('team')): ?>
                <div class="team_wrap">
                    <?php while(have_rows('team')): the_row(); ?>
                    <div class="team_item fade_in">
                        <div class="team_item_top">
                            <div class="team_item_info">
                                <p class="team_item_name">
                                    <?php echo get_sub_field('name'); ?>
                                    <?php if(get_sub_field('msc')) { ?>
                                    <span class="team_item_msc">&nbsp;<?php echo get_sub_field('msc'); ?></span>
                                </p>
                                <!-- <p class="team_item_msc"><?php echo get_sub_field('msc'); ?></p> -->
                                <?php } if(get_sub_field('position')) { ?>
                                <p class="team_sub_item_position"><?php echo get_sub_field('position'); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="bio_container">
                                <div class="bio_text_wrapper">
                                    <p class="bio_text"><?php echo get_sub_field('bio') ?></p>
                                </div>
                            </div>
                        <div class="team_item_bottom" id="team_item_bottom">
                            <?php if(get_sub_field('bio') && get_sub_field('button')) { ?>
                            <a class="link_arrow bio_reveal" style="cursor: pointer">
                                <?php echo get_sub_field('button'); ?>
                                <svg class="svg_arrow_btn">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                </svg>
                            </a>
                            <div class="team_item_img_wrap">
                                <?php } if(get_sub_field('photo')) { 
                                    $img = get_sub_field('photo'); ?>
                                    <div class="team_item_img" style="margin-left: auto;">
                                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                                    </div>
                                <?php } else { ?>
                                    <div class="team_item_img" style="margin-left: auto;">
                                        <img src="<?php bloginfo('template_url'); ?>/images/bio_placeholder.png" alt="placeholder image">
                                    </div>
                                <?php } ?>
                                <?php if(get_sub_field('linkedin')) { ?>
                                <a class="team_item_soc" href="<?php echo get_sub_field('linkedin'); ?>" target="_blank">
                                    <svg class="svg_soc_in">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#soc_in"></use>
                                    </svg>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
<?php } ?>

<style>
    .bio_container {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease-out;
    }
    .bio_container.active {
        transition: max-height 0.3s ease-in;
    }
    .bio_text_wrapper {
        transition: padding 0.3s ease;
        border-left: 2px solid #A27FFF;
        border-right: 2px solid #A27FFF;
        margin-top: 15px;
    }
    .bio_text {
        padding-left: 10px;
        padding-right: 10px;
    }
    .bio_reveal.active .svg_arrow_btn {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }
    .svg_arrow_btn {
        transition: transform 0.3s ease;
    }
    .team_item_img_wrap {
        position: relative; 
        display: inline-block; 
        margin-left: auto;
    }
    .team_item_img_wrap img {
        display: block;
        width: 100%;
        height: auto;
    }
    .team_item_soc {
        position: absolute;
        top: 8px;
        left: 8px;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>