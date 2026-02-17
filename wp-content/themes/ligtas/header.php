<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/main.min.css">
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/style.css">
	<link rel="icon" type="image/x-icon" href="<?php bloginfo('template_url'); ?>/images/favicon.png">
    <?php wp_head(); ?>
</head>
<body>
	
	<div class="all">
		<header class="header<?php if(!is_singular('cases') && !get_field('white_menu')) { echo ' dark'; } ?>">
		<div class="top_panel" style="overflow:visible;position:relative;z-index:999;">
		<div class="container" style="overflow:visible;">
			<div class="top_panel_wrap" style="overflow:visible;">
				<?php if(get_field('top_text','ts')) { ?>
					<div class="top_panel_text" style="font-size: 14px;">
						<?php echo get_field('top_text','ts'); ?>
					</div>
				<?php } ?>
				<nav class="top_panel_nav">
					<a class="cart_anchor" href="<?php bloginfo('url'); ?>/cart/" style="display: flex;">
						<img class="cart_icon" src="<?php echo get_template_directory_uri();?>/images/cart.svg" />
						<div class="cart_counter"><?php echo  WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></div>
					</a>
					<ul style="display:none;">
						<?php wp_nav_menu(array('theme_location'=>'top', 'items_wrap'=>'%3$s', 'container'=>false, 'depth'=>1)); ?>
					</ul>
					<ul>
					<?php
						wp_nav_menu(array(
							'theme_location' => 'top',
							'container' => false,
							'depth' => 2,  // Changed from 1 to 2 to allow for one level of dropdown
							'menu_class' => 'top-menu',
							'walker' => new Walker_Nav_Menu()
						));
						?>
					</ul>
					<div class="expanding_search">
						<form class="search_form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input
								type="search"
								id="search_input"
								class="search_input"
								placeholder="Search"
								name="s"
								autocomplete="off"
							/>
							<label for="search_input" class="search_icon_label">
								<div class="filter_search_send">
									<svg class="svg_search_icon">
											<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#search_icon"></use>
									</svg>
								</div>
							</label>
						</form>
					</div>
				</nav>
			</div>
		</div>
	</div>
			<div class="container">
				<div class="header_wrap">
					<a class="header_logo" href="<?php bloginfo('url'); ?>/">
						<img class="header_logo_white" src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>">
						<img class="header_logo_dark" src="<?php bloginfo('template_url'); ?>/images/logo_dark.png" alt="<?php bloginfo('name'); ?>">
					</a>
					<button class="header_toggle">
						<!-- <span class="header_toggle_text">Menu</span> -->
						<div class="header_toggle_inner">
							<span></span>
						</div>
					</button>
					<div class="header_navbar">
						<nav class="header_nav">
							<?php wp_nav_menu(array('theme_location'=>'primary', 'container'=>false, 'depth'=>1, 'walker'=>new True_Walker_Nav_Menu())); ?>							
						</nav>
						<div class="header_right">
							<div class="header_links">
								<ul>
									<?php wp_nav_menu(array('theme_location'=>'top', 'items_wrap'=>'%3$s', 'container'=>false, 'depth'=>1)); ?>
								</ul>
							</div>
						<?php if(get_field('button','ts') && get_field('link','ts')) { ?>
							<a <?php if(get_field('new','ts')) { echo ' target="_blank"'; } ?> class="btn" href="<?php echo get_field('link','ts'); ?>"><?php echo get_field('button','ts'); ?>
								<svg class="svg_arrow_btn">
									<use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
								</svg>
							</a>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</header>

<style>
	.top_panel_nav {
		display: flex;
	}
	.cart_anchor {
		margin-right: 5px;
		height: fit-content;
	}
	.cart_counter {
		/* text-decoration: underline; */
		background-color: #FAFAFA;
		color: #A27FFF;
		border-radius: calc(infinity * 1px);
		width: 25px;
		height: 25px;
		text-align: center;
		font-weight: 600;
	}
	/* Following rules match other nav spacing */
	.top_panel_nav .cart_counter { 
		margin-right: 52px;
	}
	@media screen and (max-width: 1440px) {
		.top_panel_nav .cart_counter {
			margin-right: 39px
		}
	}
	@media screen and (max-width: 1200px) {
		.top_panel_nav .cart_counter {
			margin-right: 24px
		}
	}

	/* Basic styling for the top menu */
	.top-menu {
		list-style: none;
		margin: 0;
		padding: 0;
		display: flex;
		gap:10px;
	}
	.top-menu > li:first-child {
		cursor: default;
	}
	.top-menu > li {
		position: relative;
	}
	.top-menu > li > a {
		text-decoration: none;
	}
	.top-menu > li > a:hover {
		text-decoration: underline;
	}

	/* Dropdown styling */
	.top-menu ul.sub-menu {
		position: absolute;
		background-color: #a27fff;
		min-width: 200px;
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
		opacity: 0;
		visibility: hidden;
		transition: all 0.3s ease;
		z-index: 100;
		list-style: none;
		padding: 0;
		margin: 0;
		display: block;
	}
	.top_panel_nav {
		z-index: 9;
	}
	.top-menu li:hover>ul.sub-menu {
		opacity: 1;
		visibility: visible;
	}
	.top-menu ul.sub-menu li {
		width: 100%;
		
	}
	.top-menu ul.sub-menu li a {
		padding: 12px 15px;
		display: block;
		text-decoration: none;
	}
	.top-menu ul.sub-menu li a:hover {
		text-decoration: underline;
	}

	/* Search bar */
	.search_form {
		display: flex;
		align-items: center;
		justify-content: center;
		max-width: 300px;
	}
	.search_input {
		position: absolute;
		z-index: 99;
		width: 0px;
		opacity: 0;
		transition: width 0.5s ease;
		background-color: #A27FFF;
		border: none;
		border-bottom: 2px solid #FFF;
		font-size: 16px;
		line-height: 24px;
		font-family: Borna, sans-serif;
		color: #FFF;
		height: 30px;
	}
	.search_input:focus,
	.expanding_search.active .search_input {
		width: 300px;
		opacity: 1;
		margin-right: 320px;
	}
</style>
