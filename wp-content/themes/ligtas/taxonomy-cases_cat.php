<?php 
   get_header(); 
   $queried_object = get_queried_object(); 
   $term_id = $queried_object->term_id;
   if($queried_object->parent) {
        $term_parent = $queried_object->parent;
    } else {
        $term_parent = $term_id;
    }
   $tf = 'cases_cat_'.$term_id;
   $tfp = 'cases_cat_'.$term_parent;
   $pageNum = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

         <div class="top_page bg_grey">
            <div class="container">
                <div class="top_page_inner">
                    <?php lsx_breadcrumbs(); ?>
                    <div class="top_page_row">
                        <h1 class="title_page fade_in"><?php if(get_field('title',$tf)) { echo get_field('title',$tf); } else { echo get_cat_name($term_id); } ?></h1>
                        <?php if(get_field('description',$tf)) { ?>
                        <div class="typical_text large fade_in">
                            <?php echo get_field('description',$tf); ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
         </div>
         
         <?php if(get_field('section_text',$tf) || get_field('title_text',$tf) || get_field('text_text',$tf)) { ?>
         <div class="content_section pt_130 pb_180">
            <div class="container">
                <div class="intro_wrap">
                    <div class="intro_left">
                        <div class="intro_description">
                            <?php if(get_field('section_text',$tf)) { ?>
                            <h3 class="title_category fade_in"><?php echo get_field('section_text',$tf); ?></h3>
                            <?php } if(get_field('title_text',$tf)) { ?>
                            <h2 class="title_section fade_in"><?php echo get_field('title_text',$tf); ?></h2>
                            <?php } if(get_field('text_text',$tf)) { ?>
                            <div class="typical_text fade_in">
                              <?php echo get_field('text_text',$tf); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="content_section pt_no pb_180" id="cases">
            <div class="container">

            <?php if(get_field('filter',$tfp) || get_field('search',$tfp)) { ?>
            <div class="filter_panel fade_in">
               <?php if(get_field('title_filter',$tfp)) { ?>
               <p class="filter_panel_name"><?php echo get_field('title_filter',$tfp); ?></p>
               <?php } ?>
               <form action="<?php echo get_term_link($term_id); ?>#cases" method="get" class="filter_panel_right">
                  <?php if(have_rows('filter',$tfp)): ?>
                  <div class="filter_sort">
                            <?php $i = 0; while(have_rows('filter',$tfp)): the_row(); $i++; $namef = get_sub_field('name'); ?>
                     <div class="filter_item dropdown_wrap">
                        <span class="filter_item_toggle dropdown_toggle">
                           <?php 
                               if(isset($_GET['filter'.$i]) && $_GET['filter'.$i]) { 
                                 $term = get_term_by('slug', htmlspecialchars($_GET['filter'.$i]), 'post_tag');
                                 echo $term->name;
                               } else {
                                   echo $namef; 
                               }
                           ?>
                           <div class="filter_item_arrow">
                              <svg class="svg_arrow_down">
                                 <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_down"></use>
                              </svg>
                           </div>
                        </span>
                        <div class="filter_item_dropdown dropdown_box">
                           <ul>
                              <?php 
                                   if(isset($_GET['filter'.$i]) && $_GET['filter'.$i]) { 
                                 $term = get_term_by('slug', htmlspecialchars($_GET['filter'.$i]), 'post_tag');
                               ?>
                               <li><a href="#">All</a><input type="checkbox" name="filter<?php echo $i; ?>" value=""></li>
                              <?php } if(have_rows('tags')): while(have_rows('tags')): the_row(); $tag = get_sub_field('tag'); if(in_tags_filter_cases($term_id,$tag->term_id)) { ?>
                              <li><a href="#"><?php echo $tag->name; ?></a><input type="checkbox"<?php if(isset($_GET['filter'.$i]) && $_GET['filter'.$i]==$tag->slug) { echo ' checked'; } ?> name="filter<?php echo $i; ?>" value="<?php echo $tag->slug; ?>"></li>
                              <?php } endwhile; endif; ?>
                           </ul>
                        </div>
                     </div>
                     <?php endwhile; ?>
                  </div>
                  <script>
                     jQuery(document).ready(function(){
                        jQuery('body').on('click','.filter_panel li a', function(e) { 
                           e.preventDefault();
                           jQuery(this).closest('.filter_item_dropdown').find('input').prop('checked', false);
                                    jQuery(this).find('+ input').prop('checked', true);
                                    jQuery(this).closest('form').submit();
                                });
                     });
                  </script>
                  <?php endif; ?>
                  <?php if(get_field('search',$tfp)) { ?>
                  <span class="filter_search">
                     <input class="filter_search_field" type="text" name="search"<?php if(isset($_GET['search']) && $_GET['search']) { echo ' value="'.htmlspecialchars($_GET['search']).'"'; } ?> placeholder="Search">
                     <button class="filter_search_send" type="submit">
                        <svg class="svg_search_icon">
                           <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#search_icon"></use>
                        </svg>
                     </button>
                  </span>
                  <?php } ?>
               </form>
            </div>
            <?php } ?>
               
               <?php 
                    $arg = array('post_type'=>'cases', 'posts_per_page'=>8, 'paged'=>$pageNum);
                    $arg['tax_query'][] = array('taxonomy'=>'cases_cat', 'field'=>'id', 'terms'=>$term_id);
                    if(have_rows('filter',$tfp)): $i = 0; while(have_rows('filter',$tfp)): the_row(); $i++;
                        if(isset($_GET['filter'.$i]) && $_GET['filter'.$i]) {
                         $arg['tax_query'][] = array('taxonomy'=>'post_tag', 'field'=>'slug', 'terms'=>htmlspecialchars($_GET['filter'.$i]));
                     }
                    endwhile; endif;
                    if(isset($_GET['search']) && $_GET['search']) {
                     $arg['s'] = htmlspecialchars($_GET['search']);
                     $arg['relevanssi']  = true;
                    }
                    $Nquery = new WP_Query($arg); if($Nquery->have_posts()):
               ?>
               <div class="cs_wrap">
                  <?php while($Nquery->have_posts()): $Nquery->the_post(); $type = get_field('type'); ?>
                  <div class="cs_item <?php echo get_field('background'); ?> <?php echo $type; ?> fade_in">
                     <?php if(get_field('decor')) { $img = get_field('decor'); ?>
                     <div class="cs_item_curve">
                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
                     </div>
                     <?php } if($type=='variant_1' && has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                     <div class="cs_item_img">
                        <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                     </div>
                     <?php } ?>
                     <div class="cs_item_body">
                        <?php if($type == 'variant_1') { ?>
                           <div class="cs_item_details">
                              <?php if(get_field('service')) { ?>
                              <p>Service: <strong><?php echo get_field('service'); ?></strong></p>
                              <?php } if(get_field('sector')) { ?>
                              <p>Sector: <strong><?php echo get_field('sector'); ?></strong></p>
                              <?php } ?>
                           </div>
                           <div class="cs_item_row">
                               <div class="cs_item_info <?php echo get_field('color'); ?>">
                                 <div class="cs_item_description">
                                    <?php if(get_field('location')) { ?>
                                    <p class="cs_item_location"><?php echo get_field('location'); ?></p>
                                    <?php } ?>
                                    <p class="cs_item_name"><?php the_title(); ?></p>
                                 </div>
                                 <div class="cs_item_more">
                                    <a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
                                 </div>
                               </div>
                               <div class="btn_arrow <?php echo str_replace('_light','',get_field('color')); ?>">
                                  <svg class="svg_arrow_small">
                                     <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                  </svg>
                               </div>
                            </div>
                        <?php } elseif($type == 'variant_2') { ?>
                           <div class="cs_item_main">
                              <div class="cs_item_details">
                                 <?php if(get_field('service')) { ?>
                                 <p>Service: <strong><?php echo get_field('service'); ?></strong></p>
                                 <?php } if(get_field('sector')) { ?>
                                 <p>Sector: <strong><?php echo get_field('sector'); ?></strong></p>
                                 <?php } ?>
                              </div>
                              <div class="cs_item_description">
                                 <?php if(get_field('location')) { ?>
                                 <p class="cs_item_location"><?php echo get_field('location'); ?></p>
                                 <?php } ?>
                                 <p class="cs_item_name"><?php the_title(); ?></p>
                              </div>
                              <?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                              <div class="cs_item_img">
                                 <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                              </div>
                              <?php } ?>
                           </div>
                           <div class="cs_item_more">
                              <a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
                              <div class="btn_arrow">
                                 <svg class="svg_arrow_small">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                 </svg>
                              </div>
                           </div>
                        <?php } elseif($type == 'variant_3') { ?>
                           <div class="cs_item_details">
                              <?php if(get_field('service')) { ?>
                              <p>Service: <strong><?php echo get_field('service'); ?></strong></p>
                              <?php } if(get_field('sector')) { ?>
                              <p>Sector: <strong><?php echo get_field('sector'); ?></strong></p>
                              <?php } ?>
                           </div>
                           <?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                           <div class="cs_item_img">
                              <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                           </div>
                           <?php } ?>
                           <div class="cs_item_main">
                              <div class="cs_item_description">
                                 <?php if(get_field('location')) { ?>
                                 <p class="cs_item_location"><?php echo get_field('location'); ?></p>
                                 <?php } ?>
                                 <p class="cs_item_name"><?php the_title(); ?></p>
                              </div>
                              <div class="cs_item_more">
                                 <a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
                                 <div class="btn_arrow">
                                    <svg class="svg_arrow_small">
                                       <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                    </svg>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </div>
                  <?php endwhile; ?>
                </div>
                <?php else: ?>
                <h2 class="title_block nothing_found">Nothing found</h2>
                <?php endif; ?>
                <?php kama_pagenavi($arg,$Nquery,'#cases'); ?>
                <?php wp_reset_query(); ?>
            </div>
         </div>

         <?php 
            if(have_rows('sections_cats',$tfp)): while(have_rows('sections_cats',$tfp)): the_row(); 
               $sections = get_post(get_sub_field('section'));
               $blocks = parse_blocks( $sections->post_content );
               echo render_block($blocks[0]);  
            endwhile; endif; 
         ?>
   
<?php get_footer(); ?>