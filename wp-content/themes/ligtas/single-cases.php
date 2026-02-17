<?php 
    get_header(); 
    $category = get_the_terms($post->ID,'cases_cat');
    $term_id = $category[0]->term_id;
    if($category[0]->parent) {
        $term_parent = $category[0]->parent;
    } else {
        $term_parent = $term_id;
    }
    $tf = 'cases_cat_'.$term_id;
    $tfp = 'cases_cat_'.$term_parent;
?>

        <div class="top_cs top_section">
			<div class="top_bg">
				<?php if(get_field('top_image')) { $img = get_field('top_image'); ?>
				<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
				<?php } ?>
			</div>
			<div class="container">
				<div class="top_inner">
					<div class="top_description"></div>
					<h2 class="title_section grey fade_in"><?php if(get_field('title')) { echo get_field('title'); } else { the_title(); } ?></h2>
				</div>
			</div>
		</div>

        <?php if(have_rows('details')): ?>
        <div class="cs_details bg_grey">
            <div class="container">
                <div class="cs_details_wrap">
                	<?php while(have_rows('details')): the_row(); ?>
                    <p class="cs_details_item fade_in"><?php echo get_sub_field('name'); if(get_sub_field('value')) { ?> <strong>| <?php echo get_sub_field('value'); ?></strong><?php } ?></p>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; ?>

        <?php if(get_field('cases')) { ?>
        <?php 
            $arg = array('post__in'=>get_field('cases'), 'post_type'=>'cases', 'orderby'=>'post__in', 'posts_per_page'=>-1);
            $Nquery = new WP_Query($arg); if($Nquery->have_posts()): 
        ?>
        <div class="content_section">
			<div class="container_left">
				<div class="cs_row">
					<div class="cs_row_info fade_left">
						<div class="cs_row_description">
							<h3 class="title_category"><?php echo get_term($term_parent)->name; ?></h3>
							<?php if(get_field('title_more_posts',$tfp)) { ?>
							<h2 class="title_block"><?php echo get_field('title_more_posts',$tfp); ?></h2>
							<?php } ?>
							<a class="btn" href="<?php echo get_term_link($term_parent); ?>">
								View All
								<svg class="svg_arrow_btn">
									<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
								</svg>
							</a>
						</div>
					</div>
					<div class="cs_carousel swiper fade_right">
						<div class="swiper-wrapper">
                            <?php while($Nquery->have_posts()): $Nquery->the_post(); $type = get_field('type'); ?>
							<div class="cs_carousel_item swiper-slide">
								<div class="cs_item <?php echo get_field('background'); ?> <?php echo $type; ?>">
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
										<div class="cs_item_info <?php echo get_field('color'); ?>">
											<div class="cs_item_options">
												<?php if(get_field('property_type')) { ?>
												<p>Property Type: <strong><?php echo get_field('property_type'); ?></strong></p>
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
											<div class="cs_item_more">
												<a class="link" href="<?php the_permalink(); ?>">View Case Study</a>
												<div class="btn_arrow <?php echo str_replace('_light','',get_field('color')); ?>">
													<svg class="svg_arrow_small">
														<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
													</svg>
												</div>
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
							</div>
						    <?php endwhile; ?>
						</div>
						<div class="cs_carousel_nav">
							<div class="cs_carousel_arrow cs_carousel_prev">
								<svg class="svg_arrow_prev">
									<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_prev"></use>
								</svg>
							</div>
							<div class="cs_carousel_arrow cs_carousel_next">
								<svg class="svg_arrow_next">
									<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next"></use>
								</svg>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	    <?php endif; wp_reset_query(); ?>
		<?php } ?>

		<?php 
            if(have_rows('sections',$tfp)): while(have_rows('sections',$tfp)): the_row(); 
               $sections = get_post(get_sub_field('section'));
               $blocks = parse_blocks( $sections->post_content );
               echo render_block($blocks[0]);  
            endwhile; endif; 
        ?>

<?php get_footer(); ?>