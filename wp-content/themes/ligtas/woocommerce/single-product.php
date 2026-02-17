<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); 

    $category = get_the_terms($post->ID,'product_cat');
    $term_id = $category[0]->term_id;
    if($category[0]->parent) {
        $term_parent = $category[0]->parent;
    } else {
        $term_parent = $term_id;
    }
    $tf = 'product_cat_'.$term_id;
    $tfp = 'product_cat_'.$term_parent;


?>

        <div class="course_intro bg_grey">
            <div class="container">
                <div class="course_intro_wrap">
                    <div class="course_intro_left">
						<div class="course_intro_info">
							<?php lsx_breadcrumbs(); ?>
							<h1 class="course_intro_title fade_in"><?php if(get_field('title')) { echo get_field('title'); } else { the_title(); } ?></h1>
							<div class="typical_text small fade_in">
								<?php while (have_posts()) : the_post(); ?>
								<?php the_content(); ?>
								<?php endwhile; ?>
							</div>
							<div class="course_intro_tags fade_in">
								<?php if(get_field('online')) { ?>
								<span>
									<img src="<?php bloginfo('template_url'); ?>/images/course_icon_1.svg" alt="">
									Online
								</span>
								<?php } if(get_field('virtual')) { ?>
								<span>
									<img src="<?php bloginfo('template_url'); ?>/images/course_icon_2.svg" alt="">
									Virtual
								</span>
								<?php } if(get_field('classroom')) { ?>
								<span>
									<img src="<?php bloginfo('template_url'); ?>/images/course_icon_3.svg" alt="">
									Classroom
								</span>
								<?php } if(get_field('workplace')) { ?>
								<span>
									<img src="<?php bloginfo('template_url'); ?>/images/course_icon_4.svg" alt="">
									Workplace
								</span>
								<?php } ?>
							</div>
							<div class="course_intro_bottom fade_in">
								<?php if(get_field('prices_from')) { ?>
								<div class="course_intro_price">
									<span>Prices From</span>
									<p><?php echo get_field('prices_from'); ?></p>
								</div>
								<?php } if(get_field('button_scroll')) { ?>
								<a class="btn" href="#book_now"><?php echo get_field('button_scroll'); ?>
									<svg class="svg_arrow_btn">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
									</svg>
								</a>
								<?php } ?>
							</div>
						</div>
                    </div>
                    <div class="course_intro_right fade_right">
                        <div class="course_intro_img">
                        	<?php if(get_field('image')) { $img = get_field('image'); ?>
                        	<img src="<?php echo $img['url']; ?>" alt="<?php the_title(); ?>">
                        	<?php } elseif(has_post_thumbnail()){ $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(),'full',true); ?>
                            <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                            <?php } ?>
                        </div>              
                        <?php if(get_field('section_cta_product',$tfp) || get_field('title_cta_product',$tfp) || get_field('phone_cta_product',$tfp) || get_field('button_cta_product',$tfp) && get_field('link_cta_product',$tfp)) { ?>
						<div class="course_contact">
							<div class="course_contact_body">
								<div class="course_contact_info">
									<?php if(get_field('section_cta_product',$tfp)) { ?>
									<h3 class="title_category"><?php echo get_field('section_cta_product',$tfp); ?></h3>
									<?php } if(get_field('title_cta_product',$tfp)) { ?>
									<h2 class="course_contact_title"><?php echo get_field('title_cta_product',$tfp); ?></h2>
									<?php } ?>
									<div class="course_contact_buttons">
										<?php if(get_field('button_cta_product',$tfp) && get_field('link_cta_product',$tfp)) { ?>
										<a <?php if(get_field('new_cta_product',$tfp)) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link_cta_product',$tfp); ?>">
											<?php echo get_field('button_cta_product',$tfp); ?>
											<svg class="svg_arrow_btn">
												<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
											</svg>
										</a>
										<?php } if(get_field('phone_cta_product',$tfp)) { ?>
										<a class="course_contact_link" href="tel:<?php echo str_replace(array(' ','(',')','-'),'',get_field('phone_cta_product',$tfp)); ?>">
											<svg class="svg_tel_icon">
												<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#tel_icon"></use>
											</svg>
											<span><?php echo get_field('phone_cta_product',$tfp); ?></span>
										</a>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="course_contact_curve">
								<picture>
									<source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/course_contact_mobile.png">
									<source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/course_contact_curve.png">
									<img src="<?php bloginfo('template_url'); ?>/images/course_contact_curve.png" alt="">
								</picture>
							</div>
						</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if(get_field('button_cta_product',$tfp) && get_field('link_cta_product',$tfp)) { ?>
			<div class="course_intro_vertical">
				<a <?php if(get_field('new_cta_product',$tfp)) { echo ' target="_blank"'; } ?> class="btn course_intro_btn" href="<?php echo get_field('link_cta_product',$tfp); ?>"><?php echo get_field('button_cta_product',$tfp); ?></a>
			</div>
            <?php } ?>
        </div>
        
        <?php if(get_field('course_overview') || get_field('learning_outcomes') || get_field('VALUE','ts')) { ?>
		<div class="course_about" id="book_now">
            <div class="container">
				<h3 class="title_category">The Course</h3>
				<div class="course_tabs tabs_section">
					<?php $i = 0; ?>
					<div class="course_tabs_buttons tabs_buttons">
						<?php if($product->get_available_variations()) { $i++; ?>
						<div class="course_btn tab_btn<?php if($i==1) { echo ' current'; } ?>" data-tab="<?php echo $i; ?>">Book Your Course</div>
						<?php } if(get_field('course_overview')) { $i++; ?>
						<div class="course_btn tab_btn<?php if($i==1) { echo ' current'; } ?>" data-tab="<?php echo $i; ?>">Course Overview</div>
						<?php } if(get_field('learning_outcomes')) { $i++; ?>
						<div class="course_btn tab_btn<?php if($i==1) { echo ' current'; } ?>" data-tab="<?php echo $i; ?>">Learning Outcomes</div>
						<?php } if(get_field('important_information')) { $i++; ?>
						<div class="course_btn tab_btn<?php if($i==1) { echo ' current'; } ?>" data-tab="<?php echo $i; ?>">Important Information</div>
						<?php } ?>
					</div>
					<?php $i = 0; ?>
					<p class="course_lower_title"><?php echo the_title() ?></p>
					<div class="course_content">
						<div class="course_content_main">
							<?php if($product->get_available_variations()) { $i++; ?>
							<div class="course_tab_container tab_container tab_container_<?php echo $i; if($i==1) { echo ' active'; } ?>">
								<div class="course_accordion accordion">
                                    <?php 
                                        $variations_type = 0;             
                                        foreach( $product->get_available_variations() as $variations ) {
                                        	if(get_field('type',$variations['variation_id']) == 'Online') {
                                                $variations_type = 1;  
                                        	}                                 
                                        }
                                        if($variations_type) {
									?>
									<div class="course_accordion_box accordion_box active">
										<div class="course_accordion_trigger accordion_trigger active">
											<p class="course_accordion_name">
												<img src="<?php bloginfo('template_url'); ?>/images/course_icon_1.svg" alt="">
												Book Online Course
											</p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content variations_accordion">
												<div class="course_list">
													<?php 
													    foreach( $product->get_available_variations() as $variations ) { 
													    if(get_field('type',$variations['variation_id']) == 'Online') {
													?>
													<div class="course_list_item" data-product="<?php echo $product->get_id(); ?>" data-variation="<?php echo $variations['variation_id']; ?>" data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">
														<div class="course_list_info">
															<div class="course_list_details">
																<?php echo $variations['variation_description']; ?>
															</div>
														</div>
														<div class="course_list_row">
													
															<p class=“course_list_price”>
																<?php
																if (!empty($variations['price_html'])) {
																	echo $variations['price_html'];
																} else {
																	$variation_obj = wc_get_product($variations['variation_id']);
																	echo wc_price($variation_obj->get_price());
																}
																?>
															</p>
															<div class="course_list_right">
																<div class="quantity_counter">
																	<button class="counter_decrement decrement"></button>
																	<input class="counter_value value" type="number" name="qyt" value="1">
																	<button class="counter_increment increment"></button>
																</div>
																<a class="btn add_varition" href="#">Add to cart
																	<svg class="svg_arrow_btn">
																		<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
																	</svg>
																</a>
															</div>
														</div>
													</div>
										            <?php } } ?>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
                                    
                                    <?php 
                                        $variations_type = 0;             
                                        foreach( $product->get_available_variations() as $variations ) {
                                        	if(get_field('type',$variations['variation_id']) == 'Virtual') {
                                                $variations_type = 1;  
                                        	}                                 
                                        }
                                        if($variations_type) {
									?>
									<div class="course_accordion_box accordion_box">
										<div class="course_accordion_trigger accordion_trigger">
											<p class="course_accordion_name">
												<img src="<?php bloginfo('template_url'); ?>/images/course_icon_2.svg" alt="">
												Book Virtual Course
											</p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content variations_accordion">
												<div class="course_list">
													<?php 
													    foreach( $product->get_available_variations() as $variations ) { 
													    if(get_field('type',$variations['variation_id']) == 'Virtual') {
													?>
													<div class="course_list_item" data-product="<?php echo $product->get_id(); ?>" data-variation="<?php echo $variations['variation_id']; ?>" data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">
														<div class="course_list_info">
															<div class="course_list_details">
																<?php echo $variations['variation_description']; ?>
															</div>
														</div>
														<div class="course_list_row">
															<p class=“course_list_price”>
																<?php
																if (!empty($variations['price_html'])) {
																	echo $variations['price_html'];
																} else {
																	$variation_obj = wc_get_product($variations['variation_id']);
																	echo wc_price($variation_obj->get_price());
																}
																?>
															</p>
															<div class="course_list_right">
																<div class="quantity_counter">
																	<button class="counter_decrement decrement"></button>
																	<input class="counter_value value" type="number" name="qyt" value="1">
																	<button class="counter_increment increment"></button>
																</div>
																<a class="btn add_varition" href="#">Add to cart
																	<svg class="svg_arrow_btn">
																		<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
																	</svg>
																</a>
															</div>
														</div>
													</div>
										            <?php } } ?>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
                                    
                                    <?php 
                                        $variations_type = 0;             
                                        foreach( $product->get_available_variations() as $variations ) {
                                        	if(get_field('type',$variations['variation_id']) == 'Classroom') {
                                                $variations_type = 1;  
                                        	}                                 
                                        }
                                        if($variations_type) {
									?>
									<div class="course_accordion_box accordion_box">
										<div class="course_accordion_trigger accordion_trigger">
											<p class="course_accordion_name">
												<img src="<?php bloginfo('template_url'); ?>/images/course_icon_3.svg" alt="">
												Book Classroom Course
											</p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content variations_accordion">
												<div class="course_list">
													<?php 
													    foreach( $product->get_available_variations() as $variations ) { 
													    if(get_field('type',$variations['variation_id']) == 'Classroom') {
													?>
													<div class="course_list_item" data-product="<?php echo $product->get_id(); ?>" data-variation="<?php echo $variations['variation_id']; ?>" data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">
														<div class="course_list_info">
															<div class="course_list_details">
																<?php echo $variations['variation_description']; ?>
															</div>
														</div>
														<div class="course_list_row">
															<p class=“course_list_price”>
																<?php
																if (!empty($variations['price_html'])) {
																	echo $variations['price_html'];
																} else {
																	$variation_obj = wc_get_product($variations['variation_id']);
																	echo wc_price($variation_obj->get_price());
																}
																?>
															</p>
															<div class="course_list_right">
																<div class="quantity_counter">
																	<button class="counter_decrement decrement"></button>
																	<input class="counter_value value" type="number" name="qyt" value="1">
																	<button class="counter_increment increment"></button>
																</div>
																<a class="btn add_varition" href="#">Add to cart
																	<svg class="svg_arrow_btn">
																		<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
																	</svg>
																</a>
															</div>
														</div>
													</div>
										            <?php } } ?>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
                                    
                                    <?php 
                                        $variations_type = 0;             
                                        foreach( $product->get_available_variations() as $variations ) {
                                        	if(get_field('type',$variations['variation_id']) == 'Workplace') {
                                                $variations_type = 1;  
                                        	}                                 
                                        }
                                        if($variations_type) {
									?>
									<div class="course_accordion_box accordion_box">
										<div class="course_accordion_trigger accordion_trigger">
											<p class="course_accordion_name">
												<img src="<?php bloginfo('template_url'); ?>/images/course_icon_4.svg" alt="">
												Book Workplace Course
											</p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content variations_accordion">
												<div class="course_list">
													<?php 
													    foreach( $product->get_available_variations() as $variations ) { 
													    if(get_field('type',$variations['variation_id']) == 'Workplace') {
													?>
													<div class="course_list_item" data-product="<?php echo $product->get_id(); ?>" data-variation="<?php echo $variations['variation_id']; ?>" data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">
														<div class="course_list_info">
															<div class="course_list_details">
																<?php echo $variations['variation_description']; ?>
															</div>
														</div>
														<div class="course_list_row">
														<p class=“course_list_price”>
																<?php
																if (!empty($variations['price_html'])) {
																	echo $variations['price_html'];
																} else {
																	$variation_obj = wc_get_product($variations['variation_id']);
																	echo wc_price($variation_obj->get_price());
																}
																?>
															</p>
															<div class="course_list_right">
																<div class="quantity_counter">
																	<button class="counter_decrement decrement"></button>
																	<input class="counter_value value" type="number" name="qyt" value="1">
																	<button class="counter_increment increment"></button>
																</div>
																<a class="btn add_varition" href="#">Add to cart
																	<svg class="svg_arrow_btn">
																		<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
																	</svg>
																</a>
															</div>
														</div>
													</div>
										            <?php } } ?>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
									
								</div>
							</div>
							<?php } ?>
                            <?php if(have_rows('course_overview')): $i++; ?>
							<div class="course_tab_container tab_container tab_container_<?php echo $i; if($i==1) { echo ' active'; } ?>">
								<div class="course_accordion accordion">
                                    <?php $a = 0; while(have_rows('course_overview')): the_row(); $a++; ?>
									<div class="course_accordion_box accordion_box<?php if($a==1) { echo ' active'; } ?>">
										<div class="course_accordion_trigger accordion_trigger<?php if($a==1) { echo ' active'; } ?>">
                                            <?php if(get_sub_field('description')) { ?>
											<div class="course_accordion_left">
												<p class="course_accordion_name"><?php echo get_sub_field('name'); ?></p>
												<div class="typical_text">
													<?php echo get_sub_field('description'); ?>
												</div>
											</div>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
                                            <?php } else { ?>
											<p class="course_accordion_name"><?php echo get_sub_field('name'); ?></p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
                                            <?php } ?>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content">
												<?php if(have_rows('sections')): while(have_rows('sections')): the_row(); ?>
												<?php if( get_row_layout() == 'b1' ): ?>
												<div class="typical_text<?php if(get_sub_field('small')) { echo ' small'; } ?>">
													<?php echo get_sub_field('text'); ?>
												</div>
												<?php elseif( get_row_layout() == 'b2' ): ?> 
												<ul class="checklist">
													<?php if(have_rows('list')): while(have_rows('list')): the_row(); ?>
													<li><?php echo get_sub_field('text'); ?></li>	
													<?php endwhile; endif; ?>					
												</ul>
                                                <?php elseif( get_row_layout() == 'b3' ): ?>
												    <a<?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_sub_field('link'); ?>"><?php echo get_sub_field('button'); ?>
														<svg class="svg_arrow_btn">
															<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
														</svg>
													</a>
                                                <?php elseif( get_row_layout() == 'b4' ): ?>
												<div class="downloads_list">
                                                    <?php if(have_rows('downloads')): while(have_rows('downloads')): the_row(); ?>
													<a href="<?php echo get_sub_field('file'); ?>" download>
														<?php echo get_sub_field('name'); ?>
														<div class="downloads_list_arrow">
															<svg class="svg_arrow_small">
																<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
															</svg>
														</div>
													</a>
													<?php endwhile; endif; ?>
												</div>
												<?php endif; ?>
												<?php endwhile; endif; ?>
											</div>
										</div>
									</div>
									<?php endwhile; ?>
								</div>
							</div>
							<?php endif; ?>
                            <?php if(have_rows('learning_outcomes')): $i++; ?>
							<div class="course_tab_container tab_container tab_container_<?php echo $i; if($i==1) { echo ' active'; } ?>">
								<div class="course_accordion accordion">
									<?php $a = 0; while(have_rows('learning_outcomes')): the_row(); $a++; ?>
									<div class="course_accordion_box accordion_box<?php if($a==1) { echo ' active'; } ?>">
										<div class="course_accordion_trigger accordion_trigger<?php if($a==1) { echo ' active'; } ?>">
                                            <?php if(get_sub_field('description')) { ?>
											<div class="course_accordion_left">
												<p class="course_accordion_name"><?php echo get_sub_field('name'); ?></p>
												<div class="typical_text">
													<?php echo get_sub_field('description'); ?>
												</div>
											</div>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
                                            <?php } else { ?>
											<p class="course_accordion_name"><?php echo get_sub_field('name'); ?></p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
                                            <?php } ?>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content">
												<?php if(have_rows('sections')): while(have_rows('sections')): the_row(); ?>
												<?php if( get_row_layout() == 'b1' ): ?>
												<div class="typical_text<?php if(get_sub_field('small')) { echo ' small'; } ?>">
													<?php echo get_sub_field('text'); ?>
												</div>
												<?php elseif( get_row_layout() == 'b2' ): ?> 
												<ul class="checklist">
													<?php if(have_rows('list')): while(have_rows('list')): the_row(); ?>
													<li><?php echo get_sub_field('text'); ?></li>	
													<?php endwhile; endif; ?>					
												</ul>
                                                <?php elseif( get_row_layout() == 'b3' ): ?>
												    <a<?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_sub_field('link'); ?>"><?php echo get_sub_field('button'); ?>
														<svg class="svg_arrow_btn">
															<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
														</svg>
													</a>
                                                <?php elseif( get_row_layout() == 'b4' ): ?>
												<div class="downloads_list">
                                                    <?php if(have_rows('downloads')): while(have_rows('downloads')): the_row(); ?>
													<a href="<?php echo get_sub_field('file'); ?>" download>
														<?php echo get_sub_field('name'); ?>
														<div class="downloads_list_arrow">
															<svg class="svg_arrow_small">
																<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
															</svg>
														</div>
													</a>
													<?php endwhile; endif; ?>
												</div>
												<?php endif; ?>
												<?php endwhile; endif; ?>
											</div>
										</div>
									</div>
									<?php endwhile; ?>
								</div>
							</div>
							<?php endif; ?>
                            <?php if(have_rows('important_information')): $i++; ?>
							<div class="course_tab_container tab_container tab_container_<?php echo $i; if($i==1) { echo ' active'; } ?>">
								<div class="course_accordion accordion">
									<?php $a = 0; while(have_rows('important_information')): the_row(); $a++; ?>
									<div class="course_accordion_box accordion_box<?php if($a==1) { echo ' active'; } ?>">
										<div class="course_accordion_trigger accordion_trigger<?php if($a==1) { echo ' active'; } ?>">
                                            <?php if(get_sub_field('description')) { ?>
											<div class="course_accordion_left">
												<p class="course_accordion_name"><?php echo get_sub_field('name'); ?></p>
												<div class="typical_text">
													<?php echo get_sub_field('description'); ?>
												</div>
											</div>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
                                            <?php } else { ?>
											<p class="course_accordion_name"><?php echo get_sub_field('name'); ?></p>
											<div class="course_accordion_arrow">
												<svg class="svg_arrow_accordion">
													<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_accordion"></use>
												</svg>
											</div>
                                            <?php } ?>
										</div>
										<div class="course_accordion_container toggle_container">
											<div class="course_accordion_content">
												<?php if(have_rows('sections')): while(have_rows('sections')): the_row(); ?>
												<?php if( get_row_layout() == 'b1' ): ?>
												<div class="typical_text<?php if(get_sub_field('small')) { echo ' small'; } ?>">
													<?php echo get_sub_field('text'); ?>
												</div>
												<?php elseif( get_row_layout() == 'b2' ): ?> 
												<ul class="checklist">
													<?php if(have_rows('list')): while(have_rows('list')): the_row(); ?>
													<li><?php echo get_sub_field('text'); ?></li>	
													<?php endwhile; endif; ?>					
												</ul>
                                                <?php elseif( get_row_layout() == 'b3' ): ?>
												    <a<?php if(get_sub_field('new')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_sub_field('link'); ?>"><?php echo get_sub_field('button'); ?>
														<svg class="svg_arrow_btn">
															<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
														</svg>
													</a>
                                                <?php elseif( get_row_layout() == 'b4' ): ?>
												<div class="downloads_list">
                                                    <?php if(have_rows('downloads')): while(have_rows('downloads')): the_row(); ?>
													<a href="<?php echo get_sub_field('file'); ?>" download>
														<?php echo get_sub_field('name'); ?>
														<div class="downloads_list_arrow">
															<svg class="svg_arrow_small">
																<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
															</svg>
														</div>
													</a>
													<?php endwhile; endif; ?>
												</div>
												<?php endif; ?>
												<?php endwhile; endif; ?>
											</div>
										</div>
									</div>
									<?php endwhile; ?>
								</div>
							</div>
                            <?php endif; ?>
						</div>
                        
                        <?php if($product->get_available_variations()) { $i++; ?>
						<div class="course_content_sidebar">
							<div class="course_side">
								<div class="course_side_main">
									<h4 class="course_side_title"><?php if(get_field('title')) { echo get_field('title'); } else { the_title(); } ?></h4>
									<div class="course_buy">
										<div class="course_buy_block">
                                        <?php 
                                            $buy_empty = 1;
                                            if(WC()->cart->get_cart()) {
                                                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
                                            	    if($product->get_id() == $cart_item['product_id']) {
                                            	    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );  
                                            	    $buy_empty = 0;         	 
                                        ?>   	                                     
                                        <div class="course_buy_item">
											<div class="course_buy_left">
												<div class="course_buy_remove" data-product="<?php echo $product->get_id(); ?>" data-variation="<?php echo $cart_item_key; ?>" data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">x</div>
												<div class="course_buy_text">
												<p class="course_buy_name">
												<?php 
													$variation = new WC_Product_Variation($cart_item['variation_id']);
															$variationType = get_field('type', $cart_item['variation_id']); // get the type (e.g online)
															echo $variationType;
															$variationDescription = $variation->get_description(); // get the variation description                                                        
															if (!empty($variationDescription)) {
															echo '<br><span class="course_buy_description">' . $variationDescription . '</span>';
															}
													?>
												</p>
													<p class="course_buy_quantity">x<?php echo $cart_item['quantity']; ?></p>
												</div>
											</div>
										    <p class="course_buy_price"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></p>
									    </div>
                                        <?php } } ?> 
                                        <?php } if($buy_empty) { echo '<div class="course_buy_empty course_buy_price">Add courses<br><br></div>'; } ?> 
                                        </div>
										<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn">
											Checkout
											<svg class="svg_arrow_btn">
												<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
											</svg>
										</a>
									</div>
								</div>
                                <?php if(get_field('title_cta_product',$tfp) || get_field('button_cta_product',$tfp) && get_field('link_cta_product',$tfp)) { ?>
								<div class="course_side_bottom">
									<div class="course_side_cta">
										<?php if(get_field('title_cta_product',$tfp)) { ?>
										<p><?php echo get_field('title_cta_product',$tfp); ?></p>
										<?php } if(get_field('button_cta_product',$tfp) && get_field('link_cta_product',$tfp)) { ?>
										<a<?php if(get_field('new_cta_product',$tfp)) { echo ' target="_blank"'; } ?> class="link_arrow grey" href="<?php echo get_field('link_cta_product',$tfp); ?>">
											<?php echo get_field('button_cta_product',$tfp); ?>
											<svg class="svg_arrow_post">
												<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_post"></use>
											</svg>
										</a>
										<?php } ?>
									</div>
								</div>
                                <?php } ?>
							</div>
						</div>

						<script>
                     		jQuery(document).ready(function(){

                        		jQuery('body').on('click', '.add_varition', function(e){
		                    		e.preventDefault();
                            		var product = jQuery(this).closest('.course_list_item').data('product');
                            		var variation = jQuery(this).closest('.course_list_item').data('variation');
                            	    var qyt = jQuery(this).closest('.course_list_item').find('[name=qyt]').val();
                           			var nonce = jQuery(this).closest('.course_list_item').data('nonce');
                            		jQuery.ajax({
                               			url : ajaxurl,
                               			type : 'post',
                                		data : { 'action':'add_cart', 'product':product, 'variation':variation, 'qyt':qyt, 'nonce':nonce },
                                		success:function(datas){
                                    		jQuery.ajax({ 
                                    			url:ajaxurl, type:'post', data : { 'action':'update_cart', 'product':product, 'nonce':nonce },
                                					success:function(datas){ jQuery('.course_buy_block').html(datas); }
                            					});
                                		},
                                		error: function(xhr, str){ alert('Error: ' + xhr.responseCode); }
                            		});
                        		});

                        		jQuery('body').on('click', '.course_buy_remove', function(e){
                            		e.preventDefault();
                            		var product = jQuery(this).data('product');
                            		var variation  = jQuery(this).data('variation');
                           			var nonce = jQuery(this).data('nonce');
                            		jQuery.ajax({
                               			url : ajaxurl,
                               			type : 'post',
                               			data : { 'action':'remove_cart', 'variation':variation, 'nonce':nonce },
                               			success:function(datas){
                                            jQuery.ajax({ 
                                    		    url:ajaxurl, type:'post', data : { 'action':'update_cart', 'product':product, 'nonce':nonce },
                                				success:function(datas){ jQuery('.course_buy_block').html(datas); }
                            				});
                                		},
                                		error: function(xhr, str){ alert('Error: ' + xhr.responseCode); }
                            		});
                       			});

                     		});
                  		</script>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	    <?php } ?>

		<?php 
            if(have_rows('sections_product',$tfp)): while(have_rows('sections_product',$tfp)): the_row(); 
               $sections = get_post(get_sub_field('section'));
               $blocks = parse_blocks( $sections->post_content );
               echo render_block($blocks[0]);  
            endwhile; endif; 
        ?>
        
        <?php if(have_rows('testimonials')): ?>
        <div class="testimonials_section reverse">
            <div class="testimonials_curve">
                <picture>
                    <source media="(max-width: 767px)" srcset="<?php bloginfo('template_url'); ?>/images/testimonials_mobile.png">
                    <source media="(min-width: 768px)" srcset="<?php bloginfo('template_url'); ?>/images/testimonials_right.png">
                    <img src="<?php bloginfo('template_url'); ?>/images/testimonials_right.png" alt="">
                </picture>
            </div>
            <div class="container">
                <h3 class="title_category fade_in">Testimonial</h3>
                <div class="testimonials_slider swiper fade_in">
                    <div class="swiper-wrapper">
                        <?php while(have_rows('testimonials')): the_row(); ?>
                        <div class="testimonial_item swiper-slide">
                            <div class="testimonial_item_wrap">
                                <div class="testimonial_item_left"></div>
                                <div class="testimonial_item_info">
                                	<?php if(get_sub_field('text')) { ?>
                                    <div class="testimonial_item_text">
                                    	<?php echo get_sub_field('text'); ?>
                                    </div>
                                    <?php } if(get_sub_field('name') || get_sub_field('position')) { ?>
                                    <div class="testimonial_item_bottom">
                                    	<?php if(get_sub_field('name')) { ?>
                                        <p class="testimonial_item_name"><?php echo get_sub_field('name'); ?></p>
                                        <?php } if(get_sub_field('position')) { ?>
                                        <p class="testimonial_item_position"><?php echo get_sub_field('position'); ?></p>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?> 
                    </div>
                    <div class="testimonials_nav">
                        <div class="testimonials_arrow testimonials_prev">
                            <svg class="svg_arrow_back">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_back"></use>
                            </svg>
                        </div>
                        <div class="testimonials_arrow testimonials_next">
                            <svg class="svg_arrow_small">
                                <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php 
            $arg = array('post__not_in'=>array($post->ID), 'post_type'=>'product', 'posts_per_page'=>3);
            $arg['tax_query'][] = array('taxonomy'=>'product_cat', 'field'=>'id', 'terms'=>$term_id);
            $Nquery = new WP_Query($arg); if($Nquery->have_posts()): 
        ?>
        <div class="content_section pt_no">
            <div class="container">
            	<?php if(get_field('section_more_product',$tfp) || get_field('title_more_posts',$tfp)) { ?>
				<div class="content_description mb_64">
					<?php if(get_field('section_more_product',$tfp)) { ?>
					<h3 class="title_category fade_in"><?php echo get_field('section_more_product',$tfp); ?></h3>
					<?php } if(get_field('title_more_product',$tfp)) { ?>
					<h2 class="title_section fade_in"><?php echo get_field('title_more_product',$tfp); ?></h2>
				    <?php } ?>
				</div>
			    <?php } ?>
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
            </div>
        </div>
        <?php endif; wp_reset_query(); ?>
	
<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
