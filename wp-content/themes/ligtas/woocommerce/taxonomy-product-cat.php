<?php 
    get_header(); 
    $queried_object = get_queried_object(); 
	$term_id = $queried_object->term_id;
	if($queried_object->parent) {
        $term_parent = $queried_object->parent;
    } else {
        $term_parent = $term_id;
    }
	$tf = 'product_cat_'.$term_id;
    $tfp = 'product_cat_'.$term_parent;
	$pageNum = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

        <div class="top_page bg_grey">
            <div class="container">
                <div class="top_page_inner">
                    <?php lsx_breadcrumbs(); ?>
                    <div class="top_page_row">
                        <h1 class="title_page fade_in"><?php if(get_field('title',$tf)) { echo get_field('title',$tf); } else { echo get_term($term_id)->name; } ?></h1>
                        <?php if(get_field('description',$tf)) { ?>
                        <div class="typical_text large fade_in">
                        	<?php echo get_field('description',$tf); ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(get_field('section_text',$tf) || get_field('title_text',$tf) || get_field('text_text',$tf) || get_field('section_cta_text',$tf) || get_field('title_cta_text',$tf) || get_field('description_cta_text',$tf) || get_field('button_cta_text',$tf) && get_field('link_cta_text',$tf)) { ?>
		<div class="content_section pt_160">
            <div class="container">
                <div class="intro_wrap">
                    <div class="intro_left fade_left">
                        <div class="intro_description">
                        	<?php if(get_field('section_text',$tf)) { ?>
                            <h3 class="title_category"><?php echo get_field('section_text',$tf); ?></h3>
                            <?php } if(get_field('title_text',$tf)) { ?>
                            <h2 class="title_section"><?php echo get_field('title_text',$tf); ?></h2>
                            <?php } if(get_field('text_text',$tf)) { ?>
                            <div class="typical_text">
                            	<?php echo get_field('text_text',$tf); ?>
                            </div>
                            <?php } ?>
                            <?php if ($link = get_field('body_link', $tf)) { ?>
								<a class="btn" href="<?php echo esc_url($link['url']); ?>">
									<?php echo esc_html($link['title']); ?>
									<svg class="svg_arrow_btn">
                                        <use xlink:href="https://lig001.dd-staging.co.uk/wp-content/themes/ligtas/images/sprite/sprite.svg#arrow_btn"></use>
                                    </svg>
								</a>
							<?php } ?>
                        </div>
                    </div>
                    <?php if(get_field('section_cta_text',$tf) || get_field('title_cta_text',$tf) || get_field('description_cta_text',$tf) || get_field('button_cta_text',$tf) && get_field('link_cta_text',$tf)) { ?>
                    <div class="intro_aside fade_right">
                        <div class="intro_cta">
                            <div class="intro_cta_info">
                            	<?php if(get_field('section_cta_text',$tf)) { ?>
                                <h3 class="title_category"><?php echo get_field('section_cta_text',$tf); ?></h3>
                                <?php } if(get_field('title_cta_text',$tf)) { ?>
                                <h2 class="intro_cta_title"><?php echo get_field('title_cta_text',$tf); ?></h2>
                                <?php } if(get_field('description_cta_text',$tf)) { ?>
                                <div class="typical_text grey small">
                                	<?php echo get_field('description_cta_text',$tf); ?>                           
                                </div>
                                <?php } if(get_field('button_cta_text',$tf) && get_field('link_cta_text',$tf)) { ?>
                                <a <?php if(get_field('new_cta_text',$tf)) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link_cta_text',$tf); ?>">
                                    <?php echo get_field('button_cta_text',$tf); ?>
                                    <svg class="svg_arrow_btn">
                                        <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                    </svg>
                                </a>
                                <?php } ?>
                            </div>
                            <div class="intro_cta_curve">
                                <img src="<?php bloginfo('template_url'); ?>/images/intro_cta_purple.png" alt="">
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="content_section pt_no" id="courses">
            <div class="container">

				<?php if(get_field('filter',$tfp) || get_field('search',$tfp)) { ?>
				<div class="filter_panel fade_in">
					<?php if(get_field('title_filter',$tfp)) { ?>
					<p class="filter_panel_name"><?php echo get_field('title_filter',$tfp); ?></p>
					<?php } ?>
					<form action="<?php echo get_category_link($term_id); ?>#courses" method="get" class="filter_panel_right">
						<?php if(have_rows('filter',$tfp)): ?>
						<div class="filter_sort">
                            <?php $i = 0; while(have_rows('filter',$tfp)): the_row(); $i++; $namef = get_sub_field('name'); ?>
							<div class="filter_item dropdown_wrap">
								<span class="filter_item_toggle dropdown_toggle">
									<?php 
									    if(isset($_GET['filter'.$i]) && $_GET['filter'.$i]) { 
									    	$term = get_term_by('slug', htmlspecialchars($_GET['filter'.$i]), 'product_tag');
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
									    	$term = get_term_by('slug', htmlspecialchars($_GET['filter'.$i]), 'product_tag');
									    ?>
									    <li><a href="#">All</a><input type="checkbox" name="filter<?php echo $i; ?>" value=""></li>
										<?php } if(have_rows('tags')): while(have_rows('tags')): the_row(); $tag = get_sub_field('tag'); if(in_tags_filter_courses($term_id,$tag->term_id)) { ?>
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
                    $arg = array('post_type'=>'product', 'posts_per_page'=>18, 'paged'=>$pageNum);
                    $arg['tax_query'][] = array('taxonomy'=>'product_cat', 'field'=>'id', 'terms'=>$term_id);
                    if(have_rows('filter',$tfp)): $i = 0; while(have_rows('filter',$tfp)): the_row(); $i++;
                        if(isset($_GET['filter'.$i]) && $_GET['filter'.$i]) {
                    	    $arg['tax_query'][] = array('taxonomy'=>'product_tag', 'field'=>'slug', 'terms'=>htmlspecialchars($_GET['filter'.$i]));
                    	}
                    endwhile; endif;
                    if(isset($_GET['search']) && $_GET['search']) {
                    	$arg['s'] = htmlspecialchars($_GET['search']);
                    	$arg['relevanssi']  = true;
                    }
                    $Nquery = new WP_Query($arg); if($Nquery->have_posts()):
                ?>
                <div class="courses_wrap">
                    <?php while($Nquery->have_posts()): $Nquery->the_post(); $category = get_the_terms($post->ID,'product_cat'); ?>
                    <div class="course_item fade_in">
                        <div class="course_item_main">
                            <div class="course_item_top">
                                <span class="course_item_category"><?php echo get_term($category[0]->term_id)->name; ?></span>
                                <?php if(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                                <a class="course_item_img" href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                                </a>
                                <?php } ?>
                            </div>
                            <div class="course_item_info">
                                <div class="course_item_icons">
                                	<?php if(get_field('online')) { ?>
                                    <img src="<?php bloginfo('template_url'); ?>/images/course_icon_1.svg" alt="">
                                    <?php } if(get_field('virtual')) { ?>
                                    <img src="<?php bloginfo('template_url'); ?>/images/course_icon_2.svg" alt="">
                                    <?php } if(get_field('classroom')) { ?>
                                    <img src="<?php bloginfo('template_url'); ?>/images/course_icon_3.svg" alt="">
                                    <?php } if(get_field('workplace')) { ?>
                                    <img src="<?php bloginfo('template_url'); ?>/images/course_icon_4.svg" alt="">
                                    <?php } ?>
                                </div>
                                <a class="course_item_name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <div class="typical_text small">
                                    <p><?php echo get_the_excerpt(); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="course_item_more">
                            <a class="link" href="<?php the_permalink(); ?>">Learn More</a>
                            <a class="course_item_arrow" href="<?php the_permalink(); ?>">
                                <svg class="svg_arrow_post">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_post"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <h2 class="title_block nothing_found">Nothing found</h2>
                <?php endif; ?>
                <?php kama_pagenavi($arg,$Nquery,'#courses'); ?>
                <?php wp_reset_query(); ?>
                
                <?php if(get_field('section_contact',$tfp) || get_field('title_contact',$tfp) || get_field('phone_contact',$tfp) || get_field('button_contact',$tfp) && get_field('link_contact',$tfp)) { ?>
                <div class="contact_cta fade_in">
                    <div class="contact_cta_body">
                        <div class="contact_cta_info">
                        	<?php if(get_field('section_contact',$tfp)) { ?>
                            <h3 class="title_category"><?php echo get_field('section_contact',$tfp); ?></h3>
                            <?php } if(get_field('title_contact',$tfp)) { ?>
                            <h2 class="contact_cta_title"><?php echo get_field('title_contact',$tfp); ?></h2>
                            <?php } ?>
                            <div class="contact_cta_buttons">
                            	<?php if(get_field('button_contact',$tfp) && get_field('link_contact',$tfp)) { ?>
								<a <?php if(get_field('new_contact',$tfp)) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link_contact',$tfp); ?>">
									<?php echo get_field('button_contact',$tfp); ?>
									<svg class="svg_arrow_btn">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
									</svg>
								</a>
								<?php } if(get_field('phone_contact',$tfp)) { ?>
								<a class="contact_cta_link" href="tel:<?php echo str_replace(array(' ','(',')','-'),'',get_field('phone_contact',$tfp)); ?>">
									<svg class="svg_tel_icon">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#tel_icon"></use>
									</svg>
									<span><?php echo get_field('phone_contact',$tfp); ?></span>
								</a>
								<?php } ?>
							</div>
                        </div>
                    </div>
                    <div class="contact_cta_curve">
                        <picture>
                            <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/contact_cta_mobile.png">
                            <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/contact_cta_curve.png">
                            <img src="<?php bloginfo('template_url'); ?>/images/contact_cta_curve.png" alt="">
                        </picture>
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