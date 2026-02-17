<footer class="footer">
			<div class="container">
				<div class="footer_top">
					<div class="footer_top_wrap">
						<div class="footer_left">
							<a class="footer_logo" href="<?php bloginfo('url'); ?>/">
								<img src="<?php bloginfo('template_url'); ?>/images/icon-purple.png" alt="<?php bloginfo('name'); ?>">
							</a>
								<button id="back_to_top">
									<img src="<?php bloginfo('template_url'); ?>/images/arrow_up.svg" alt="Back to top">
									<p>Back to top</p>
								</button>
						</div>
						<div class="footer_navbar">
							<?php 
					    		$menu_name = wp_get_nav_menu_name('footer1');
					    		if($menu_name) {
							?>
							<div class="footer_column">
								<a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/" style="text-decoration: none;">
									<p class="footer_name"><?php echo $menu_name; ?></p>
								</a>
								<div class="footer_nav">
									<?php wp_nav_menu(array('theme_location'=>'footer1', 'container'=>false, 'depth'=>1, 'walker'=>new True_Walker_Nav_Menu_Footer())); ?>
								</div>
							</div>
							<?php 
							    }
					    		$menu_name = wp_get_nav_menu_name('footer2');
					    		if($menu_name) {
							?>
							<div class="footer_column">
								<a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/" style="text-decoration: none;">
									<p class="footer_name"><?php echo $menu_name; ?></p>
								</a>
								<div class="footer_nav">
									<?php wp_nav_menu(array('theme_location'=>'footer2', 'container'=>false, 'depth'=>1, 'walker'=>new True_Walker_Nav_Menu_Footer())); ?>
								</div>
							</div>
							<?php 
							    }
					    		$menu_name = wp_get_nav_menu_name('footer3');
					    		if($menu_name) {
							?>
							<div class="footer_column">
								<a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/" style="text-decoration: none;">
									<p class="footer_name"><?php echo $menu_name; ?></p>
								</a>
								<div class="footer_nav">
									<?php wp_nav_menu(array('theme_location'=>'footer3', 'container'=>false, 'depth'=>1, 'walker'=>new True_Walker_Nav_Menu_Footer())); ?>
								</div>
							</div>
							
						    <?php 
								}
					    		$menu_name = wp_get_nav_menu_name('footer4');
					    		if($menu_name) {
							?>
							<div class="footer_column">
								<a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/" style="text-decoration: none;">
									<p class="footer_name"><?php echo $menu_name; ?></p>
								</a>
								<div class="footer_nav">
									<?php wp_nav_menu(array(
										'theme_location'=>'footer4',
										'container'=>false, 'depth'=>2,
										'walker'=>new True_Walker_Nav_Menu_Footer()
										)
										); 
									?>
								</div>
							</div>
						    <?php } ?>
							
						</div>
						
						<div class="footer_contact">
							<?php if(get_field('title_contact','ts')) { ?>
							<p class="footer_name"><?php echo get_field('title_contact','ts'); ?></p>
						    <?php } ?>
							<div class="footer_contact_list">
								<?php if(have_rows('contact','ts')): while(have_rows('contact','ts')): the_row(); ?>
								<?php if(get_sub_field('type') == 1) { ?>
								<?php if(get_sub_field('link')) { ?>
								<a class="footer_contact_item" href="<?php echo get_sub_field('link'); ?>" target="_blank">
									<div class="footer_contact_icon">
										<svg class="svg_location_icon">
											<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#location_icon"></use>
										</svg>
									</div>
									<?php echo get_sub_field('address'); ?>
								</a>
							    <?php } else { ?>
							    <span class="footer_contact_item">
									<div class="footer_contact_icon">
										<svg class="svg_location_icon">
											<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#location_icon"></use>
										</svg>
									</div>
									<?php echo get_sub_field('address'); ?>
								</span>
								<?php } ?>
								<?php } elseif(get_sub_field('type') == 2) { ?>
								<a class="footer_contact_item" href="tel:<?php echo str_replace(array(' ','(',')','-'),'',get_sub_field('phone')); ?>">
									<div class="footer_contact_icon">
										<svg class="svg_tel_icon">
											<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#tel_icon"></use>
										</svg>
									</div>
									<?php echo get_sub_field('phone'); ?>
								</a>
							    <?php } elseif(get_sub_field('type') == 3) { ?>
								<a class="footer_contact_item" href="mailto:<?php echo get_sub_field('email'); ?>">
									<div class="footer_contact_icon">
										<svg class="svg_email_icon">
											<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#email_icon"></use>
										</svg>
									</div>
									<?php echo get_sub_field('email'); ?>
								</a>
								<?php } ?>
								<?php endwhile; endif; ?>
							</div>
							<div class="footer_soc">
								<?php if(get_field('linkedin','ts')) { ?>
								<a href="<?php echo get_field('linkedin','ts'); ?>" target="_blank">
									<svg class="svg_soc_in">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#soc_in"></use>
									</svg>
								</a>
							    <?php } if(get_field('youtube','ts')) { ?>
								<a href="<?php echo get_field('youtube','ts'); ?>" target="_blank">
									<svg class="svg_soc_y">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#soc_y"></use>
									</svg>
								</a>
								<?php } if(get_field('facebook','ts')) { ?>
								<a href="<?php echo get_field('facebook','ts'); ?>" target="_blank">
									<svg class="svg_soc_fb">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg?v=5#soc_fb"></use>
									</svg>
								</a>
								<?php } if(get_field('instagram','ts')) { ?>
								<a href="<?php echo get_field('instagram','ts'); ?>" target="_blank">
									<svg class="svg_soc_ig">
										<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg?v=6#soc_ig"></use>
									</svg>
								</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="footer_bottom">
					<div class="footer_bottom_wrap">
						<?php if(get_field('form','ts')) { ?>
						<!-- Readd when they're ready -->
						<!-- <div class="footer_newsletter">
							<?php if(get_field('section_form','ts')) { ?>
							<h3 class="title_category"><?php echo get_field('section_form','ts'); ?></h3>
							<?php } if(get_field('title_form','ts')) { ?>
							<h2 class="footer_newsletter_title"><?php echo get_field('title_form','ts'); ?></h2>
					    	<?php } echo get_field('form','ts'); ?>
						</div> -->
						<?php } ?>
						<ul class="footer_links">
							<?php wp_nav_menu(array('theme_location'=>'links', 'items_wrap'=>'%3$s', 'container'=>false, 'depth'=>1)); ?>
						</ul>
					</div>
					<button id="back_to_top">
						<img src="<?php bloginfo('template_url'); ?>/images/arrow_up.svg" alt="Back to top">
						<p>Back to top</p>
					</button>
					<a class="footer_logo" href="<?php bloginfo('url'); ?>/">
						<img src="<?php bloginfo('template_url'); ?>/images/icon-purple.png" alt="<?php bloginfo('name'); ?>">
					</a>
					<?php if(have_rows('logos','ts')): ?>
						<div class="logos_wrap">
						<?php while(have_rows('logos','ts')): the_row(); ?>
							<?php if(get_sub_field('logo')) { ?>
								<?php 
								$image = get_sub_field('logo');
								if( !empty( $image ) ): ?>
									<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
								<?php endif; ?>
							<?php } ?>
						<?php endwhile; ?>
						</div> 
					<?php endif; ?>
				</div>
			</div>
		</footer>
	</div>
	<script>
	
	</script>
	<script src="<?php bloginfo('template_url'); ?>/js/main.min.js"></script>
<script>
    // Ensure Swiper is loaded before running this
    const classroomSwiper = new Swiper('.classroom-course-swiper', {
        slidesPerView: 1, // Mobile default
        spaceBetween: 20,
        loop: true,

        // Navigation arrows
        navigation: {
            nextEl: '.classroom_next',
            prevEl: '.classroom_prev',
        },

        // Responsive breakpoints
        breakpoints: {
            // When window width is >= 1024px (Desktop)
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            // When window width is >= 768px (Tablet)
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            }
        },
    });
</script>
	<?php wp_footer(); ?>
    
</body>
</html>
