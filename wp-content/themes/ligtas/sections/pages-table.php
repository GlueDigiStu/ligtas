<?php 
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
        <?php if(get_field('section')) { ?>
        <h2 class="title_category fade_in"><?php echo get_field('section'); ?></h2>
        <?php } if(get_field('big') && get_field('title')) { ?>
        <h3 class="title_section fade_in"><?php echo get_field('title'); ?></h3>
        <?php } elseif(get_field('title')) { ?>
        <div class="typical_text large<?php echo $colortext; ?> fade_in">
            <h3><?php echo get_field('title'); ?></h3>
        </div>
        <?php } ?>

        <?php 
        $repeater = get_field_object('table_row');
        $sub_fields = $repeater['sub_fields'];
        ?>
        <table class="learning_options_table">
            <thead class="learning_options_table_head">
                <?php foreach ($sub_fields as $sub_field) { ?>
                    <th><?php echo esc_html($sub_field['label']); ?></th>
                <?php } ?>
            </thead>
            <tbody class="learning_options_table_body">
                <?php while (have_rows('table_row')): the_row(); ?>
                <tr class="learning_options_table_row">
                    <?php 
                        foreach ($sub_fields as $sub_field) {
                            $value = get_sub_field($sub_field['name']);
                            if ($sub_field['type'] === 'true_false') {
                                echo '<td>' . ($value
                                    ? '<img class="checklist" src="' . get_template_directory_uri() . '/images/checklist.svg" alt="Yes"
                                        style="background-color: #A27FFF; width: 20px; height: 20px; border-radius: 3px; padding: 5px;
                                        display: block; margin: auto;">'
                                    : '') . '</td>';
                            } else {
                                echo '<td>' . esc_html($value) . '</td>';
                            }
                        }
                    ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php } ?>