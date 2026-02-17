<?php 
    get_header(); 
    $queried_object = get_queried_object(); 
	$term_id = $queried_object->term_id;
	if($queried_object->parent) {
        $term_parent = $queried_object->parent;
    } else {
        $term_parent = $term_id;
    }
	$tf = 'category_'.$term_id;
    $tfp = 'category_'.$term_parent;
	$pageNum = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

        <div class="top_page bg_grey">
            <div class="container">
                <div class="top_page_inner">
                    <?php lsx_breadcrumbs(); ?>
                    <div class="top_page_row">
                        <h1 class="title_page fade_in"><?php if(get_field('title',$tf)) { echo get_field('title',$tf); } else { echo get_cat_name($term_id); } ?></h1>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(get_field('section_text',$tf) || get_field('title_text',$tf) || get_field('text_text',$tf)) { ?>
		<div class="content_section pt_180 pb_100">
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
        
        <div class="content_section pt_no pb_130" id="posts">
            <div class="container">
                
                <?php if(get_field('filter',$tfp) || get_field('search',$tfp)) { ?>
				<div class="filter_panel fade_in">
					<?php if(get_field('title_filter',$tfp)) { ?>
					<p class="filter_panel_name"><?php echo get_field('title_filter',$tfp); ?></p>
					<?php } ?>
					<form action="<?php echo get_category_link($term_id); ?>#posts" method="get" class="filter_panel_right">
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
										<?php } if(have_rows('tags')): while(have_rows('tags')): the_row(); $tag = get_sub_field('tag'); if(in_tags_filter_posts($term_id,$tag->term_id)) { ?>
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
                    $arg = array('cat'=>$term_id, 'post_type'=>'post', 'posts_per_page'=>16, 'paged'=>$pageNum);
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
                <div class="posts_wrap">
                    <?php while($Nquery->have_posts()): $Nquery->the_post(); $category = get_the_category($post->ID); ?>
                    <a class="post_item fade_in" href="<?php the_permalink(); ?>">
						<div class="post_item_top">
							<div class="post_item_details">
								<p class="cats"><?php 
									$cat_short = get_field('short_title','category_'.$category[0]->cat_ID);
									echo esc_html( $cat_short ? $cat_short : get_cat_name($term_id) );
								?></p>
								<!-- <p class="tags"><?php 
									$tags = get_the_tags();
									if($tags && !is_wp_error($tags)) {
										echo esc_html( join(', ', wp_list_pluck($tags, 'name')) );
									}
								?></p> -->
								<p><?php the_time("F Y"); ?></p>
							</div>
							<?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
							<div class="post_item_img">
								<img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
							</div>
							<?php } ?>
						</div>
						<div class="post_item_info">
							<p class="post_item_name"><?php the_title(); ?></p>
							<div class="post_item_more">
								<span class="post_item_link">Learn More</span>
								<span class="post_item_arrow">
									<svg class="svg_arrow_post">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_post"></use>
									</svg>
								</span>
							</div>
						</div>
					</a>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <h2 class="title_block nothing_found">Nothing found</h2>
                <?php endif; ?>
                <?php kama_pagenavi($arg,$Nquery,'#posts'); ?>
                <?php wp_reset_query(); ?>

                <?php if(get_field('section_form',$tfp) || get_field('title_form',$tfp)) { ?>
                <div class="newsletter_block fade_in">
                    <div class="newsletter_wrap">
                        <div class="newsletter_info">
                            <div class="newsletter_description">
                            	<?php if(get_field('section_form',$tfp)) { ?>
                                <h3 class="title_category grey"><?php echo get_field('section_form',$tfp); ?></h3>
                                <?php } if(get_field('title_form',$tfp)) { ?>
                                <h2 class="title_block grey"><?php echo get_field('title_form',$tfp); ?></h2>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="newsletter_form">
                        	<?php echo get_field('form_form',$tfp); ?>                        
                        </div>
                    </div>
                </div>
                <?php } ?>

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