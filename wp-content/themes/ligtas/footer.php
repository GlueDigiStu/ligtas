<footer class="footer">
    <div class="container">
        <div class="footer_top">
            <div class="footer_top_wrap">
                <div class="footer_left">
                    <a class="footer_logo" href="<?php bloginfo('url'); ?>/">
                        <img src="<?php bloginfo('template_url'); ?>/images/icon-purple.png"
                             alt="<?php bloginfo('name'); ?>">
                    </a>
                    <button id="back_to_top">
                        <img src="<?php bloginfo('template_url'); ?>/images/arrow_up.svg" alt="Back to top">
                        <p>Back to top</p>
                    </button>
                </div>
                <div class="footer_navbar">
                    <?php
                    $menu_name = wp_get_nav_menu_name('footer1');
                    if ($menu_name) {
                        ?>
                        <div class="footer_column">
                            <a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/"
                               style="text-decoration: none;">
                                <p class="footer_name"><?php echo $menu_name; ?></p>
                            </a>
                            <div class="footer_nav">
                                <?php wp_nav_menu(array('theme_location' => 'footer1', 'container' => false, 'depth' => 1, 'walker' => new True_Walker_Nav_Menu_Footer())); ?>
                            </div>
                        </div>
                        <?php
                    }
                    $menu_name = wp_get_nav_menu_name('footer2');
                    if ($menu_name) {
                        ?>
                        <div class="footer_column">
                            <a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/"
                               style="text-decoration: none;">
                                <p class="footer_name"><?php echo $menu_name; ?></p>
                            </a>
                            <div class="footer_nav">
                                <?php wp_nav_menu(array('theme_location' => 'footer2', 'container' => false, 'depth' => 1, 'walker' => new True_Walker_Nav_Menu_Footer())); ?>
                            </div>
                        </div>
                        <?php
                    }
                    $menu_name = wp_get_nav_menu_name('footer3');
                    if ($menu_name) {
                        ?>
                        <div class="footer_column">
                            <a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/"
                               style="text-decoration: none;">
                                <p class="footer_name"><?php echo $menu_name; ?></p>
                            </a>
                            <div class="footer_nav">
                                <?php wp_nav_menu(array('theme_location' => 'footer3', 'container' => false, 'depth' => 1, 'walker' => new True_Walker_Nav_Menu_Footer())); ?>
                            </div>
                        </div>

                        <?php
                    }
                    $menu_name = wp_get_nav_menu_name('footer4');
                    if ($menu_name) {
                        ?>
                        <div class="footer_column">
                            <a href="<?php echo home_url('/' . str_replace([' ', '?', '.'], '-', strtolower($menu_name))); ?>/"
                               style="text-decoration: none;">
                                <p class="footer_name"><?php echo $menu_name; ?></p>
                            </a>
                            <div class="footer_nav">
                                <?php wp_nav_menu(array(
                                                'theme_location' => 'footer4',
                                                'container' => false, 'depth' => 2,
                                                'walker' => new True_Walker_Nav_Menu_Footer()
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>

                <div class="footer_contact">
                    <?php if (get_field('title_contact', 'ts')) { ?>
                        <p class="footer_name"><?php echo get_field('title_contact', 'ts'); ?></p>
                    <?php } ?>
                    <div class="footer_contact_list">
                        <?php if (have_rows('contact', 'ts')): while (have_rows('contact', 'ts')): the_row(); ?>
                            <?php if (get_sub_field('type') == 1) { ?>
                                <?php if (get_sub_field('link')) { ?>
                                    <a class="footer_contact_item" href="<?php echo get_sub_field('link'); ?>"
                                       target="_blank">
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
                            <?php } elseif (get_sub_field('type') == 2) { ?>
                                <a class="footer_contact_item"
                                   href="tel:<?php echo str_replace(array(' ', '(', ')', '-'), '', get_sub_field('phone')); ?>">
                                    <div class="footer_contact_icon">
                                        <svg class="svg_tel_icon">
                                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#tel_icon"></use>
                                        </svg>
                                    </div>
                                    <?php echo get_sub_field('phone'); ?>
                                </a>
                            <?php } elseif (get_sub_field('type') == 3) { ?>
                                <a class="footer_contact_item" href="mailto:<?php echo get_sub_field('email'); ?>">
                                    <div class="footer_contact_icon">
                                        <svg class="svg_email_icon">
                                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#email_icon"></use>
                                        </svg>
                                    </div>
                                    <?php echo get_sub_field('email'); ?>
                                </a>
                            <?php } elseif (get_sub_field('type') == 4) { ?>
                                <span class="footer_contact_item">
									<div class="footer_contact_icon">
<svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
     xmlns:xlink="http://www.w3.org/1999/xlink"
     width="17px" height="17px" viewBox="0 0 45.999 45.999"
     xml:space="preserve">
<g>
	<g>
		<path d="M39.264,6.736c-8.982-8.981-23.545-8.982-32.528,0c-8.982,8.982-8.981,23.545,0,32.528c8.982,8.98,23.545,8.981,32.528,0
			C48.245,30.281,48.244,15.719,39.264,6.736z M25.999,33c0,1.657-1.343,3-3,3s-3-1.343-3-3V21c0-1.657,1.343-3,3-3s3,1.343,3,3V33z
			 M22.946,15.872c-1.728,0-2.88-1.224-2.844-2.735c-0.036-1.584,1.116-2.771,2.879-2.771c1.764,0,2.88,1.188,2.917,2.771
			C25.897,14.648,24.746,15.872,22.946,15.872z"/>
	</g>
</g>
									</div>
									<?php echo get_sub_field('info_text'); ?>
								</span>
                            <?php } ?>
                        <?php endwhile; endif; ?>
                    </div>
                    <div class="footer_soc">
                        <?php if (get_field('linkedin', 'ts')) { ?>
                            <a href="<?php echo get_field('linkedin', 'ts'); ?>" target="_blank">
                                <svg class="svg_soc_in">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#soc_in"></use>
                                </svg>
                            </a>
                        <?php }
                        if (get_field('youtube', 'ts')) { ?>
                            <a href="<?php echo get_field('youtube', 'ts'); ?>" target="_blank">
                                <svg class="svg_soc_y">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#soc_y"></use>
                                </svg>
                            </a>
                        <?php }
                        if (get_field('facebook', 'ts')) { ?>
                            <a href="<?php echo get_field('facebook', 'ts'); ?>" target="_blank">
                                <svg class="svg_soc_fb">
                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg?v=5#soc_fb"></use>
                                </svg>
                            </a>
                        <?php }
                        if (get_field('instagram', 'ts')) { ?>
                            <a href="<?php echo get_field('instagram', 'ts'); ?>" target="_blank">
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
                <?php if (get_field('form', 'ts')) { ?>
                    <!-- Readd when they're ready -->
                     <div class="footer_newsletter">
							<?php if (get_field('section_form', 'ts')) { ?>
							<h3 class="title_category"><?php echo get_field('section_form', 'ts'); ?></h3>
							<?php }
                    if (get_field('title_form', 'ts')) { ?>
							<h2 class="footer_newsletter_title"><?php echo get_field('title_form', 'ts'); ?></h2>
					    	<?php }
                    echo get_field('form', 'ts'); ?>
						</div>
                <?php } ?>
                <ul class="footer_links">
                    <?php wp_nav_menu(array('theme_location' => 'links', 'items_wrap' => '%3$s', 'container' => false, 'depth' => 1)); ?>
                </ul>
            </div>
            <button id="back_to_top">
                <img src="<?php bloginfo('template_url'); ?>/images/arrow_up.svg" alt="Back to top">
                <p>Back to top</p>
            </button>
            <a class="footer_logo" href="<?php bloginfo('url'); ?>/">
                <img src="<?php bloginfo('template_url'); ?>/images/icon-purple.png" alt="<?php bloginfo('name'); ?>">
            </a>
            <?php if (have_rows('logos', 'ts')): ?>
                <div class="logos_wrap">
                    <?php while (have_rows('logos', 'ts')): the_row(); ?>
                        <?php if (get_sub_field('logo')) { ?>
                            <?php
                            $image = get_sub_field('logo');
                            if (!empty($image)): ?>
                                <img src="<?php echo esc_url($image['url']); ?>"
                                     alt="<?php echo esc_attr($image['alt']); ?>"/>
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
<!-- Mini Cart Panel -->
<div id="mini-cart-panel" style="display:none;position:fixed;top:20px;right:20px;z-index:99999;width:320px;background:#fff;border-radius:10px;box-shadow:0 8px 32px rgba(41,18,97,0.2);padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="margin:0;font-size:18px;font-weight:bold;color:#291261;">Added to Cart &#10003;</h3>
        <button id="mini-cart-close" style="background:none;border:none;font-size:28px;cursor:pointer;color:#291261;line-height:1;padding:0;">&times;</button>
    </div>
    <div id="mini-cart-items" style="margin-bottom:12px;"></div>
    <div id="mini-cart-total" style="border-top:2px solid #a27fff;padding-top:12px;font-weight:bold;color:#291261;font-size:16px;"></div>
    <div style="display:flex;gap:12px;margin-top:16px;">
        <a id="mini-cart-link" href="#" class="btn" style="flex:1;text-align:center;justify-content:center;min-width:0;">View Cart</a>
        <a id="mini-cart-checkout-link" href="#" class="btn btn_purple" style="flex:1;text-align:center;justify-content:center;min-width:0;">Checkout</a>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var miniCartNonce = '<?php echo wp_create_nonce('mini_cart_nonce'); ?>';
    var miniCartTimer;

    // Handle .cart form submissions (custom_buy_now shortcode, single product pages)
    $(document).on('submit', '.cart', function(e) {
        e.preventDefault();
        var form      = $(this);
        var btn       = form.find('[type="submit"]');
        var origText  = btn.text();
        var productId = form.find('[name="add-to-cart"]').val();
        var quantity  = form.find('[name="quantity"]').val() || 1;

        btn.text('Adding\u2026').prop('disabled', true).css('opacity', '0.4');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action:     'mini_cart_add',
                product_id: productId,
                quantity:   quantity,
                nonce:      miniCartNonce
            },
            success: function(response) {
                if (response.success) {
                    renderMiniCart(response.data);
                }
            },
            complete: function() {
                btn.text(origText).prop('disabled', false).css('opacity', '');
            }
        });
    });

    // Handle WooCommerce archive/loop add-to-cart buttons (WooCommerce already added the item)
    $(document.body).on('added_to_cart', function() {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'mini_cart_fetch',
                nonce:  miniCartNonce
            },
            success: function(response) {
                if (response.success) {
                    renderMiniCart(response.data);
                }
            }
        });
    });

    function renderMiniCart(data) {
        var html = '';
        $.each(data.items, function(i, item) {
            html += '<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">';
            html += '<span style="color:#291261;font-size:14px;">' + item.name + ' \u00d7 ' + item.quantity + '</span>';
            html += '<span style="font-weight:bold;font-size:14px;">' + item.subtotal + '</span>';
            html += '</div>';
        });

        $('#mini-cart-items').html(html);
        $('#mini-cart-total').html('Total: ' + data.cart_total);
        $('.cart_counter').text(data.cart_count);
        $('#mini-cart-link').attr('href', data.cart_url);
        $('#mini-cart-checkout-link').attr('href', data.checkout_url);

        $('#mini-cart-panel').fadeIn(300);

        clearTimeout(miniCartTimer);
        miniCartTimer = setTimeout(function() {
            $('#mini-cart-panel').fadeOut(300);
        }, 6000);
    }

    $('#mini-cart-close').on('click', function() {
        clearTimeout(miniCartTimer);
        $('#mini-cart-panel').fadeOut(300);
    });

    $(document).on('click', function(e) {
        if ($('#mini-cart-panel').is(':visible') &&
            !$(e.target).closest('#mini-cart-panel').length &&
            !$(e.target).closest('.cart').length) {
            clearTimeout(miniCartTimer);
            $('#mini-cart-panel').fadeOut(300);
        }
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html>
