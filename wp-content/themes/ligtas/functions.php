<?php
if (isset($_GET['URL'])) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include(get_404_template());
        exit;
    }
function my_custom_enqueue_scripts() {
    // Enqueue jQuery (built-in WordPress version)
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'my_custom_enqueue_scripts');
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
            'ligtas-tailwind',
            get_template_directory_uri() . '/dist/output.css',
            array(),
            filemtime(get_template_directory() . '/dist/output.css') // Auto-bust cache
    );
});
add_action('wp_head', 'myplugin_ajaxurl');
function myplugin_ajaxurl() {
    echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}

add_action( 'pre_get_posts', 'hwl_pagesize', 1 );
function hwl_pagesize( $query ) {
    if( is_admin() || ! $query->is_main_query() )
        return;
    if( is_category() ){
        $query->set( 'posts_per_page', 16 );
    } elseif(is_tax('cases_cat')) {
        $query->set( 'posts_per_page', 8 );
    } elseif(is_tax('product_cat')) {
        $query->set( 'posts_per_page', 12 );
    }
}

register_nav_menus(array(
    'top'=>__('Top'), 
    'primary'=>__('Main menu'), 
    'footer1'=>__('Footer 1'), 
    'footer2'=>__('Footer 2'), 
    'footer3'=>__('Footer 3'),
    'footer4'=>__('Footer 4'),
    'links'=>__('Links')
));

add_theme_support( 'title-tag' );
add_filter( 'run_wptexturize', '__return_false' );
add_theme_support( 'woocommerce' );

add_action( 'after_setup_theme', 'remove_plugin_image_sizes' );
function remove_plugin_image_sizes(){
	remove_image_size( 'thumbnail' );
	remove_image_size( 'medium' );
	remove_image_size( 'medium_large' );
	remove_image_size( 'large' );
}

if ( function_exists( 'add_theme_support' ) )
add_theme_support( 'post-thumbnails' );

add_action( 'init', 'smartwp_disable_emojis' );
function smartwp_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}

function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

remove_action('wp_head','feed_links_extra', 3);
remove_action('wp_head','feed_links', 2);
remove_action('wp_head','rsd_link');
remove_action('wp_head','wlwmanifest_link');
remove_action('wp_head','wp_generator'); 
remove_action('wp_head','start_post_rel_link',10,0);
remove_action('wp_head','index_rel_link');
remove_action('wp_head','rel_canonical');
remove_action( 'wp_head','adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head','wp_shortlink_wp_head', 10, 0 );

class True_Walker_Nav_Menu_Footer extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        global $wp_query;           
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';
        if(get_field('new_column',$item)) { $output .=  '</ul><ul>'; }
        $output .= $indent . '<li' . $value . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class True_Walker_Nav_Menu_Submenu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        global $wp_query;           
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="submenu_item ' . esc_attr( $class_names ) . '"';
        $output .= $indent . '';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
        $item_output = $args->before;
        $item_output .= '<a'. $class_names . $attributes .'>';
            if(get_field('image',$item)) { $img = get_field('image',$item);
                $item_output .= '<div class="submenu_item_img"><img src="'.$img['url'].'" alt="'.$img['alt'].'"></div>';
            }
            $item_output .= '<p class="submenu_item_name">'.$args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after.'<svg class="svg_arrow_nav"><use xlink:href="'.get_bloginfo('template_url').'/images/sprite/sprite.svg#arrow_nav"></use></svg></p>';
            if(get_field('description',$item)) {
                $item_output .= '<p class="submenu_item_text">'.get_field('description',$item).'<svg class="svg_arrow_small"><use xlink:href="'.get_bloginfo('template_url').'/images/sprite/sprite.svg#arrow_small"></use></svg></p>';
            }
        $item_output .= '</a>';                           
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    function end_el(&$output, $object, $depth = 0, $args = array()) {
        $output .="";
    }
}

class True_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        global $wp_query;           
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $submenu = get_field('submenu',$item);
        if($submenu) {
           $class_names = ' class="has_submenu ' . esc_attr( $class_names ) . '"';
        } else {
           $class_names = ' class="' . esc_attr( $class_names ) . '"';
        }
        $output .= $indent . '<li' . $value . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '<svg class="svg_arrow_nav"><use xlink:href="'.get_bloginfo('template_url').'/images/sprite/sprite.svg#arrow_nav"></use></svg>';
        $item_output .= '</a>';
        $item_output .= $args->after;
        if($submenu) {
            if(have_rows('submenu','ts')): $i = 0; while(have_rows('submenu','ts')): the_row(); $i++;
                if($submenu == $i) {
                    $item_output .= '<div class="header_submenu"><div class="container"><div class="header_submenu_inner"><div class="header_submenu_wrap"><div class="header_back"><a class="header_back_link" href="#"><svg class="svg_arrow_back"><use xlink:href="'.get_bloginfo('template_url').'/images/sprite/sprite.svg#arrow_back"></use></svg><span>Back</span></a></div><div class="header_submenu_left"><div class="header_submenu_description"><p class="header_submenu_name">'.apply_filters( 'the_title', $item->title, $item->ID ).'</p><div class="header_submenu_text">'.get_sub_field('text').'</div></div></div><div class="header_submenu_main"><div class="submenu_wrap">';
                    $menu = wp_get_nav_menu_object(get_sub_field('menu')); 
                    $item_output .= wp_nav_menu(array('menu'=>$menu->term_id, 'items_wrap'=>'%3$s', 'container'=>false, 'depth'=>1, 'echo'=>false, 'walker'=>new True_Walker_Nav_Menu_Submenu()));                            
                    $item_output .= '</div></div></div>';
                    if(get_field('button','ts') && get_field('link','ts')) {
                        if(get_field('new','ts')) { $target = ' target="_blank"'; } else { $target = ''; }
                        $item_output .= '<a'.$target.' class="header_submenu_btn btn" href="'.get_field('link','ts').'">'.get_field('button','ts').'<svg class="svg_arrow_btn"><use xlink:href="'.get_bloginfo('template_url').'/images/sprite/sprite.svg#arrow_btn"></use></svg></a>';
                    }
                    $item_output .= '</div></div></div>';
                }
            endwhile; endif;
        }
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

function wph_inline_css_admin() {
    echo '<style> #addtag .term-description-wrap, #edittag .term-description-wrap, .term-display-type-wrap, .term-thumbnail-wrap {  display:none !important; } </style>'; 
}
add_action('admin_head', 'wph_inline_css_admin');


add_action( 'admin_menu', 'admin_menu_add_external_link' );
function admin_menu_add_external_link() {
    global $menu;
    $menu_slug = "external_slug";
    add_menu_page( 'Patterns', 'Patterns', 'read', "edit.php?post_type=wp_block", '', 'dashicons-database-add',61);
}

function lh_filter_acf_get_post_types( $post_types ) {
  if(!in_array('wp_block', $post_types)){
    $post_types[] = 'wp_block';
  }
  return $post_types;
};
add_filter( 'acf/get_post_types', 'lh_filter_acf_get_post_types', 10, 1 );

add_filter('acf/location/rule_values/post_type', 'acf_location_rule_values_Post');
function acf_location_rule_values_Post( $choices ) {
    $choices['product_variation'] = 'Product Variation';
    return $choices;
}

add_action( 'woocommerce_product_after_variable_attributes', function( $loop, $variation_data, $variation ) {
    global $abcdefgh_i;
    $abcdefgh_i = $loop;
    add_filter( 'acf/prepare_field', 'acf_prepare_field_update_field_name' );
    $acf_field_groups = acf_get_field_groups();
    foreach( $acf_field_groups as $acf_field_group ) {
        foreach( $acf_field_group['location'] as $group_locations ) {
            foreach( $group_locations as $rule ) {
                if( $rule['param'] == 'post_type' && $rule['operator'] == '==' && $rule['value'] == 'product_variation' ) {
                    acf_render_fields( $variation->ID, acf_get_fields( $acf_field_group ) );
                    break 2;
                }
            }
        }
    }
    remove_filter( 'acf/prepare_field', 'acf_prepare_field_update_field_name' );
}, 10, 3 );

function  acf_prepare_field_update_field_name( $field ) {
    global $abcdefgh_i;
    $field['name'] = preg_replace( '/^acf\[/', "acf[$abcdefgh_i][", $field['name'] );
    return $field;
}
    
// Save variation data
add_action( 'woocommerce_save_product_variation', function( $variation_id, $i = -1 ) {
    // Update all fields for the current variation
    if ( ! empty( $_POST['acf'] ) && is_array( $_POST['acf'] ) && array_key_exists( $i, $_POST['acf'] ) && is_array( ( $fields = $_POST['acf'][ $i ] ) ) ) {
        foreach ( $fields as $key => $val ) {
            update_field( $key, $val, $variation_id );
        }
    }
}, 10, 2 );

function kama_pagenavi( $args = [], $wp_query = null, $anchor = '' ){
    $default = [
        'before'          => '',           // Text before the navigation.
        'after'           => '',           // Text after the navigation.
        'echo'            => true,         // Return or output the result.
        'text_num_page'   => '',           // Text before the pagination.
                                           // {current} - current.
                                           // {last} - last (eg: 'Page {current} of {last}' will result in: "Page 4 of 60").
        'num_pages'       => 10,           // How many links to show.
        'step_link'       => 10,           // Links with step (if 10, then: 1,2,3...10,20,30. Use 0 if such links are not needed.
        'dotright_text'   => '…',          // Intermediate text "before".
        'dotright_text2'  => '…',          // Intermediate text "after".
        'back_text'       => '« back',     // Text "go to the previous page". Use 0 if this link is not needed.
        'next_text'       => 'forward »',  // Text "go to the next page". Use 0 if this link is not needed.
        'first_page_text' => '« to start', // Text "to the first page". Use 0 if the page number should be shown instead of the text.
        'last_page_text'  => 'to end »',   // Text "to the last page". Use 0 if the page number should be shown instead of the text.
    ];
    $fargs = func_get_args();
    if( $fargs && is_string( $fargs[0] ) ){
        $default['before'] = $fargs[0] ?? '';
        $default['after']  = $fargs[1] ?? '';
        $default['echo']   = $fargs[2] ?? true;
        $args              = $fargs[3] ?? [];
        $wp_query = $GLOBALS['wp_query']; // !!! after $default
    }
    if( ! $wp_query ){
        wp_reset_query();
        global $wp_query;
    }
    if( ! $args ){
        $args = [];
    }
    $default = apply_filters( 'kama_pagenavi_args', $default );
    $rg = (object) array_merge( $default, $args );
    $paged = (int) ( $wp_query->get( 'paged' ) ?: 1 );
    $max_page = (int) $wp_query->max_num_pages;
    if( $max_page < 2 ){
        return '';
    }
    $pages_to_show = (int) $rg->num_pages;
    $pages_to_show_minus_1 = $pages_to_show - 1;
    $half_page_start = (int) floor( $pages_to_show_minus_1 / 2 ); // how many links before the current page
    $half_page_end   = (int) ceil(  $pages_to_show_minus_1 / 2 ); // how many links after the current page
    $start_page = $paged - $half_page_start; // first page
    $end_page   = $paged + $half_page_end;   // last page (conventionally)
    if( $start_page <= 0 ){
        $start_page = 1;
    }
    if( (int) ( $end_page - $start_page ) !== (int) $pages_to_show_minus_1 ){
        $end_page = $start_page + $pages_to_show_minus_1;
    }
    if( $end_page > $max_page ){
        $start_page = $max_page - $pages_to_show_minus_1;
        $end_page =  $max_page;
    }
    if( $start_page <= 0 ){
        $start_page = 1;
    }
    $link_base = str_replace( PHP_INT_MAX, '___', get_pagenum_link( PHP_INT_MAX ) );
    $first_url = get_pagenum_link( 1 );
    if( ! str_contains( $first_url, '?' ) ){
        $first_url = user_trailingslashit( $first_url );
    }
    $els = [];
    for( $i = $start_page; $i <= $end_page; $i++ ){
        if( $i === $paged ){
            if($i<10) { $ch = '0'.$i; } else { $ch = $i; }
            $els['current'] = '<li class="active"><a>' . $ch .'</a></li>';
        }
        elseif( $i === 1 ){
            $els[] = sprintf( '<li><a href="%s'.$anchor.'">01</a></li>', $first_url );
        }
        else{
            if($i<10) { $ch = '0'.$i; } else { $ch = $i; }
            $els[] = sprintf( '<li><a href="%s'.$anchor.'">%s</a></li>', str_replace( '___', (string) $i, $link_base ), $ch );
        }
    }
    $dd = 0;
    if( $rg->step_link && $end_page < $max_page ){
        for( $i = $end_page + 1; $i <= $max_page; $i++ ){
            if( 0 === ( $i % $rg->step_link) && $i !== $rg->num_pages ){
                if( ++$dd === 1 ){
                    $els[] = '<li>' . $rg->dotright_text2 . '</li>';
                }
                if($i<10) { $ch = '0'.$i; } else { $ch = $i; }
                $els[] = sprintf( '<li><a href="%s'.$anchor.'">%s</a></li>', str_replace( '___', (string) $i, $link_base ), $ch );
            }
        }
    }
    $els = apply_filters( 'kama_pagenavi_elements', $els );
    $html = $rg->before . '<div class="pagination fade_in"><ul class="pagination_list">' . implode( '', $els ) . '</ul></div>' . $rg->after;
    $html = apply_filters( 'kama_pagenavi', $html );
    if( $rg->echo ){
        echo $html;
    }
    return $html;
}

function in_tags_filter_posts($cat,$tag) {
    $return = 0;
    $arg = array('post_type'=>'post', 'posts_per_page'=>1);
    $arg['tax_query'][] = array('taxonomy'=>'category', 'field'=>'id', 'terms'=>$cat);
    $arg['tax_query'][] = array('taxonomy'=>'post_tag', 'field'=>'id', 'terms'=>$tag);
    $Nquery = new WP_Query($arg); if($Nquery->have_posts()): while($Nquery->have_posts()): $Nquery->the_post();
        $return = 1;
    endwhile; endif; wp_reset_query();
    return $return;
}

function in_tags_filter_cases($cat,$tag) {
    $return = 0;
    $arg = array('post_type'=>'cases', 'posts_per_page'=>1);
    $arg['tax_query'][] = array('taxonomy'=>'cases_cat', 'field'=>'id', 'terms'=>$cat);
    $arg['tax_query'][] = array('taxonomy'=>'post_tag', 'field'=>'id', 'terms'=>$tag);
    $Nquery = new WP_Query($arg); if($Nquery->have_posts()): while($Nquery->have_posts()): $Nquery->the_post();
        $return = 1;
    endwhile; endif; wp_reset_query();
    return $return;
}

function in_tags_filter_courses($cat,$tag) {
    $return = 0;
    $arg = array('post_type'=>'product', 'posts_per_page'=>1);
    $arg['tax_query'][] = array('taxonomy'=>'product_cat', 'field'=>'id', 'terms'=>$cat);
    $arg['tax_query'][] = array('taxonomy'=>'product_tag', 'field'=>'id', 'terms'=>$tag);
    $Nquery = new WP_Query($arg); if($Nquery->have_posts()): while($Nquery->have_posts()): $Nquery->the_post();
        $return = 1;
    endwhile; endif; wp_reset_query();
    return $return;
}

add_action( 'wp', 'force_404' );
function force_404() {
    global $wp_query;
    if(!is_admin()){
        if(is_tag() || is_tax('product_tag')){
            status_header( 404 );
            nocache_headers();
            include( get_query_template( '404' ) );
            die();
        }
    }
}

function lsx_breadcrumbs() {
    if (!function_exists('yoast_breadcrumb')) { return null; }
    $breadcrumb = ''; $i = 0; 
    $crumbs = yoast_breadcrumb(null, null, false);
    $breadcrumbs = explode("||", $crumbs);
    $count = count($breadcrumbs);
    foreach ($breadcrumbs as &$value) { $i++; 
        if($count == $i) {
            $breadcrumb .= '<span>'.strip_tags($value).'</span>'; 
        } elseif(is_product() && $i==2) {
        } else {
            $breadcrumb .= strip_tags($value,'<a>').' > '; 
        }
    }
    $output = '<div class="breadcrumbs fade_in">' . $breadcrumb . '</div>';
    echo $output;
}

function add_cart() {
    $nonce = htmlspecialchars($_POST['nonce']);
    $product = htmlspecialchars($_POST['product']);
    $variation = htmlspecialchars($_POST['variation']);
    if(!wp_verify_nonce($nonce,'add_varition') || !$product || !$variation) { die(); }
    global $woocommerce;
    $qyt = htmlspecialchars($_POST['qyt']);
    if($qyt>0) { $qyt_set = (int)$qyt; } else { $qyt_set = 1; }
    $add = $woocommerce->cart->add_to_cart($product,$qyt_set,$variation,'', array('wccpf_0DcPN3RHDVbP'=>'-','wccpf_CQiaw4uXpkFR'=>'-','wccpf_4bM5GzahTORI'=>'-'));
    die();
}
add_filter( 'wp_ajax_nopriv_add_cart', 'add_cart' );
add_filter( 'wp_ajax_add_cart', 'add_cart' );

function remove_cart() {
    $nonce = htmlspecialchars($_POST['nonce']);
    $variation = htmlspecialchars($_POST['variation']);
    if(!wp_verify_nonce( $nonce, 'add_varition') || !$variation) { die(); }
    global $woocommerce;
    $cart = $woocommerce->cart;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item){
        if($cart_item_key == $variation){
            $cart->remove_cart_item($cart_item_key); 
        }
    }
    die();
}
add_filter( 'wp_ajax_nopriv_remove_cart', 'remove_cart' );
add_filter( 'wp_ajax_remove_cart', 'remove_cart' );

function update_cart() {
    $nonce = htmlspecialchars($_POST['nonce']);
    $product = htmlspecialchars($_POST['product']);
    if(!wp_verify_nonce( $nonce, 'add_varition' ) || !$product) { die(); }
        $buy_empty = 1;
        if(WC()->cart->get_cart()) {
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
                if($product == $cart_item['product_id']) {
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $buy_empty = 0;                
?>
<div class="course_buy_item">
    <div class="course_buy_left">
        <div class="course_buy_remove" data-product="<?php echo $product; ?>"
            data-variation="<?php echo $cart_item_key; ?>" data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">x
        </div>
        <div class="course_buy_text">
            <p class="course_buy_name">
                <?php 
                                        $variation = new WC_Product_Variation($cart_item['variation_id']);
                                        $variationName = implode(" / ", $variation->get_variation_attributes());
                                        echo get_field('type',$cart_item['variation_id']).' - '.$variationName;
                                    ?>
            </p>
            <p class="course_buy_quantity">x<?php echo $cart_item['quantity']; ?></p>
        </div>
    </div>
    <p class="course_buy_price">
        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
    </p>
</div>
<?php } } ?>
<?php } if($buy_empty) { echo '<div class="course_buy_empty course_buy_price">Add courses<br><br></div>'; } ?>
<?php                                        
    die();
}
add_filter( 'wp_ajax_nopriv_update_cart', 'update_cart' );
add_filter( 'wp_ajax_update_cart', 'update_cart' );

function mini_cart_add_and_fetch() {
    $nonce      = isset( $_POST['nonce'] )      ? $_POST['nonce']            : '';
    $product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
    $quantity   = isset( $_POST['quantity'] )   ? intval( $_POST['quantity'] )   : 1;

    if ( ! wp_verify_nonce( $nonce, 'mini_cart_nonce' ) || ! $product_id ) {
        wp_send_json_error( 'Invalid request' );
    }

    if ( $quantity < 1 ) $quantity = 1;

    $added = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( ! $added ) {
        wp_send_json_error( 'Could not add to cart' );
    }

    WC()->cart->calculate_totals();

    $product = wc_get_product( $product_id );
    $items   = array();

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $item_product = $cart_item['data'];
        $items[] = array(
            'name'     => $item_product->get_name(),
            'quantity' => $cart_item['quantity'],
            'subtotal' => WC()->cart->get_product_subtotal( $item_product, $cart_item['quantity'] ),
        );
    }

    wp_send_json_success( array(
        'product_name' => $product->get_name(),
        'cart_count'   => WC()->cart->get_cart_contents_count(),
        'cart_total'   => WC()->cart->get_cart_total(),
        'cart_url'     => wc_get_cart_url(),
        'checkout_url' => wc_get_checkout_url(),
        'items'        => $items,
    ) );
}
add_action( 'wp_ajax_mini_cart_add', 'mini_cart_add_and_fetch' );
add_action( 'wp_ajax_nopriv_mini_cart_add', 'mini_cart_add_and_fetch' );




add_action( 'init', 'create_custom_taxonomies' );
function create_custom_taxonomies(){

  register_taxonomy('cases_cat', 'cases',array(
    'hierarchical'  => true,
    'labels'        => array(
      'name'        => 'Categories cases',
      'singular_name' => 'Categories cases'
    ),
    'show_admin_column'       => true,
    'show_ui'       => true,
    'show_in_rest'      => true,
    'query_var'     => true,
    'show_tagcloud' => true,
    'rewrite'       => array( 'slug' => 'cases_cat' )
  ));
  register_post_type( 'cases', array(
  'labels' => array(
    'name' => 'Cases',
    'singular_name' => 'Cases',
   ),
  'description' => 'cases',
  'public' => true, 
  'menu_position' => 21,
  'menu_icon' => 'dashicons-images-alt2',
  'show_in_rest' => true,
  'supports' => array( 'title', 'editor', 'thumbnail' ),
  'taxonomies' => array( "post_tag" ),
  'rewrite'             => array( "slug" => 'cases' )
  ));

}

function register_layout_category( $categories ) {
	$categorie = array('slug' =>'themes','title'=>'Theme sections'); 
	array_unshift($categories, $categorie);
	return $categories;
}
add_filter( 'block_categories_all', 'register_layout_category' );
function mihdan_register_blocks() {
    if( function_exists( 'acf_register_block_type' ) ) {

        acf_register_block_type(array('name'=>'TS0', 'title'=>'Heander', 'render_template'=>'sections/pages-heander.php', 'post_types'=>array('page','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS1', 'title'=>'Rich text', 'render_template'=>'sections/pages-rich_text.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS2', 'title'=>'Form', 'render_template'=>'sections/pages-form.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS3', 'title'=>'Hero text & Anchor Links', 'render_template'=>'sections/pages-hero_text_anchor_links.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS4', 'title'=>'3 Column Accordion', 'render_template'=>'sections/pages-3_column_accordion.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS5', 'title'=>'Testimonial', 'render_template'=>'sections/pages-testimonial.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS6', 'title'=>'Image & text info', 'render_template'=>'sections/pages-image_text_info.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS7', 'title'=>'Gallery', 'render_template'=>'sections/pages-gallery.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS8', 'title'=>'Wayfinding Card', 'render_template'=>'sections/pages-wayfinding_card.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS9', 'title'=>'Hero Text & CTA', 'render_template'=>'sections/pages-hero_text_cta.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS10', 'title'=>'Hero Text & Texture', 'render_template'=>'sections/pages-hero_text_texture.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS11', 'title'=>'Text & Cards', 'render_template'=>'sections/pages-text_cards.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS12', 'title'=>'Information', 'render_template'=>'sections/pages-information.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS13', 'title'=>'Text & Image & Accordion', 'render_template'=>'sections/pages-text_image_accordion.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS14', 'title'=>'After Scroll', 'render_template'=>'sections/pages-after_scroll.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS15', 'title'=>'Parallax', 'render_template'=>'sections/pages-parallax.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS16', 'title'=>'Image', 'render_template'=>'sections/pages-image.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS17', 'title'=>'Full accordion', 'render_template'=>'sections/pages-full_accordion.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS18', 'title'=>'Cases', 'render_template'=>'sections/pages-cases.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS19', 'title'=>'Info', 'render_template'=>'sections/pages-info.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS20', 'title'=>'Posts', 'render_template'=>'sections/pages-posts.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS21', 'title'=>'Intro text', 'render_template'=>'sections/pages-intro_text.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS22', 'title'=>'Team', 'render_template'=>'sections/pages-team.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS23', 'title'=>'List', 'render_template'=>'sections/pages-list.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS24', 'title'=>'CTA', 'render_template'=>'sections/pages-cta.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS25', 'title'=>'Intro slider', 'render_template'=>'sections/pages-intro_slider.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS26', 'title'=>'Hero Image & Text', 'render_template'=>'sections/pages-hero_image_text.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));
        acf_register_block_type(array('name'=>'TS27', 'title'=>'Learning Table', 'render_template'=>'sections/pages-learning_table.php', 'post_types'=>array('page','post','cases','wp_block'), 'supports'=>array('anchor'=>true,'align'=>false,'customClassName'=>false), 'mode'=>'edit', 'category'=>'themes'));

    }
}
add_action( 'acf/init', 'mihdan_register_blocks' );

/**
 * Initialize ACF date picker for WooCommerce product variations
 */
function acf_init_date_picker_for_variations() {
    // Only load on the product edit screen
    global $pagenow, $post_type;
    if (!($pagenow == 'post.php' && $post_type == 'product')) {
        return;
    }
    ?>
    <script type="text/javascript">
    (function($) {
        // When variations are loaded or added
        $(document).on('woocommerce_variations_loaded woocommerce_variations_added', function() {
            // Initialize any date pickers that exist in variations
            if (typeof acf !== 'undefined' && typeof acf.do_action !== 'undefined') {
                acf.do_action('append', $('#woocommerce-product-data'));
            } 
        });
    })(jQuery);
    </script>
    <?php
}
add_action('admin_footer', 'acf_init_date_picker_for_variations');

// ### DOCEBO FUNCTIONS ### //

require_once get_template_directory() . '/docebo.php';

$DOCEBO = new DoceboClass();

function docebo_add_user_and_enrol($order_id) {
    global $DOCEBO;
    $duration = 365; // Used for add_user_to_LMS_course variable
    
    if (!$order_id) return;

    $order = wc_get_order($order_id);
    if (!$order) return;
    
    $order_items = $order->get_items();

    if (!empty($order_items)) {
        foreach ($order_items as $item) {
            // For each order item get each person added to cart fields
            $recipients = $item->get_meta('_course_recipients');
            // error_log(print_r($recipients, true));
            
            if (!empty($recipients)) {
                // Check for LMS course ID first to see if we need to create a user
                $variation_id = $item->get_product()->get_id();
                $course_id = get_post_meta($variation_id, 'course_code', true);
                
                if ($course_id) {
                    // Cool, now get what we need to create a user
                    foreach($recipients as $person) {
                        $first_name = $person['first_name'];
                        $last_name = $person['last_name'];
                        $email = $person['email'];

                        if (!empty($first_name) && !empty($last_name) && !empty($email)) {
                            // error_log("Creating and enrolling");
                            // error_log(print_r($person), true);
                            // Create user on LMS
                            $user_id = $DOCEBO->add_user_to_LMS($first_name, $last_name, $email);
                            // error_log("Response from user creation => $user_id");
                        } else {
                            // Break out of all loops, some data is empty
                            // error_log("Cannot create user because some variable is empty $first_name $last_name $email");
                            continue;
                        }
                        
                        // Enrol user on LMS course
                        // Course_id might be a comma separated list of ids
                        if (strpos($course_id, ',') !== false) {
                            // It is a comma separated string of ids
                            $course_ids = explode(',', $course_id);
                            // Explode to array to feed into API call
                            $response = $DOCEBO->add_user_to_LMS_course_multiple($course_ids, $user_id, $duration);
                            // error_log("Response from user enrolment => $response");
                        } else {
                            // It is one course id
                            $response = $DOCEBO->add_user_to_LMS_course($course_id, $user_id, $duration);
                            // error_log("Response from user enrolment => $response");
                        }
                    }
                } else {
                    error_log("No course_id found for variation ID $variation_id on order $order_id");
                }
            } else {
                error_log("No person data found for item in order $order_id");
            }
        }
    }
}
add_action('woocommerce_thankyou', 'docebo_add_user_and_enrol', 10, 1); // Triggers on thankyou page after payment processed

// ### END DOCEBO FUNCTIONS ### //

/**
* Redirect dead news inners to news outer
*/
function redirect_news_404s() {
	if (!is_404()) {
		return;
	}
	
	$current_url = $_SERVER['REQUEST_URI'];
	
	if (preg_match('#^/news/.+#', $current_url)) {
		wp_redirect(home_url('/news/'), 301);
		exit;
	}
}
add_action('template_redirect', 'redirect_news_404s');

/**
* Redirect dead case studies inners to case study outer
*/

function redirect_case_study_404s() {
	if (!is_404()) {
		return;
	}
	
	$current_url = $_SERVER['REQUEST_URI'];
	
	if (preg_match('#^/case-studies/.+#', $current_url)) {
		wp_redirect(home_url('/case-studies/'), 301);
		exit;
	}
}
add_action('template_redirect', 'redirect_case_study_404s');

/**
* Redirect old site shop inners to training outers
*/

function redirect_shop_404s() {

	$current_url = $_SERVER['REQUEST_URI'];
	
	if (preg_match('#^/shop/.+#', $current_url)) {
		wp_redirect(home_url('/training/'), 301);
		exit;
	}
}
add_action('template_redirect', 'redirect_shop_404s');


//
// add these fields into the quick edit view for case studies
//
function add_quick_edit_fields($column_name, $post_type) {
    if ($post_type != 'cases') return;

    switch ($column_name) {
        case 'service':
        case 'sector':
            ?>
            <fieldset class="inline-edit-col-left">
                <div class="inline-edit-col">
                    <label>
                        <span class="title"><?php echo ucfirst($column_name); ?></span>
                        <span class="input-text-wrap">
                            <input type="text" name="<?php echo $column_name; ?>" class="acf-<?php echo $column_name; ?>" />
                        </span>
                    </label>
                </div>
            </fieldset>
            <?php
            break;
    }
}
add_action('quick_edit_custom_box', 'add_quick_edit_fields', 10, 2);

add_action( 'init', 'enable_product_page_attributes' );
function enable_product_page_attributes() {
    add_post_type_support( 'product', 'page-attributes' );
}


add_shortcode('custom_buy_now', 'render_variation_with_quantity');

function render_variation_with_quantity($atts) {
    $atts = shortcode_atts(array(
            'id' => 0,
    ), $atts);

    if (empty($atts['id'])) return '';

    $product = wc_get_product($atts['id']);
    if (!$product) return 'Product not found';

    // Start Buffering
    ob_start();
    ?>
    <form class="cart" action="<?php echo esc_url($product->add_to_cart_url()); ?>" method="POST" enctype='multipart/form-data'>
        <div class="custom-buy-wrapper x:flex x:items-center x:justify-center x:flex-col x:lg:flex-row x:gap-4">
            <?php
            // Renders the standard WC quantity input
            woocommerce_quantity_input(array(
                    'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                    'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : $product->get_min_purchase_quantity(),
            ));
            ?>

            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn">
                <?php echo esc_html($product->single_add_to_cart_text()); ?>
            </button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}
