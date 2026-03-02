<?php
/**
 * Template Name: Single Product Custom
 * Template Post Type: product
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop');
global $product;

$category = get_the_terms($post->ID, 'product_cat');
$term_id = $category[0]->term_id;
if ($category[0]->parent) {
    $term_parent = $category[0]->parent;
} else {
    $term_parent = $term_id;
}
$tf = 'product_cat_' . $term_id;
$tfp = 'product_cat_' . $term_parent;


?>

    <div class="course_intro bg_grey">
        <div class="container">
            <div class="course_intro_wrap">
                <div class="course_intro_left">
                    <div class="course_intro_info">
                        <?php lsx_breadcrumbs(); ?>

                        <h1 class="course_intro_title fade_in"><?php the_title() ?></h1>
                        <div class="typical_text small fade_in">
                            <?php while (have_posts()) : the_post(); ?>
                                <?php the_content(); ?>
                            <?php endwhile; ?>
                        </div>
                        <a class="btn fade_in" href="#">Course Overview</a>
                    </div>
                </div>

                <div class="course_intro_right fade_right">
                    <div class="course_intro_img">
                        <?php if (get_field('image')) {
                            $img = get_field('image'); ?>
                            <img src="<?php echo $img['url']; ?>" alt="<?php the_title(); ?>">
                        <?php } elseif (has_post_thumbnail()) {
                            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true); ?>
                            <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                        <?php } ?>
                    </div>
                    <?php $course_overview = get_field('course_overview_panels'); ?>

                    <?php $count = 0;
                    if($course_overview['online_courses']['enable'])
                        $count++;
                    if($course_overview['virtual_courses']['enable'])
                        $count++;
                    if($course_overview['classroom_courses']['enable'])
                        $count++;
?>
                    <div class="x:md:absolute x:bottom-2 x:left-2 x:right-2 x:grid x:md:grid-cols-<?= $count ?> x:gap-4">
                        <?php if ($course_overview['online_courses']['enable']): ?>
                            <div class="x:bg-[#291261] x:rounded-[10px] x:p-4">
                                <p class="x:text-white x:text-[22px] x:mb-4 x:text-center">Online Courses</p>

                                <p class="x:text-center x:text-sm x:text-white">Prices from</p>
                                <p class="x:text-white x:text-[22px] x:text-center x:mb-4"><?= $course_overview['online_courses']['price'] ?></p>
                                <a class="btn fade_in x:w-full x:text-center x:justify-center x:min-w-0" href="#online-courses">Find
                                    out more</a>
                            </div>
                        <?php endif; ?>
                        <?php if ($course_overview['virtual_courses']['enable']): ?>
                            <div class="x:bg-[#291261] x:rounded-[10px] x:p-4">
                                <p class="x:text-white x:text-[22px] x:mb-4 x:text-center">Virtual Courses</p>

                                <p class="x:text-center x:text-sm x:text-white">Prices from</p>
                                <p class="x:text-white x:text-[22px] x:text-center x:mb-4"><?= $course_overview['virtual_courses']['price'] ?></p>
                                <a class="btn fade_in x:w-full x:text-center x:justify-center x:min-w-0" href="#virtual-courses">Find
                                    out more</a>
                            </div>
                        <?php endif; ?>

                        <?php if ($course_overview['classroom_courses']['enable']): ?>
                            <div class="x:bg-[#291261] x:rounded-[10px] x:p-4">
                                <p class="x:text-white x:text-[22px] x:mb-4 x:text-center">Classroom Courses</p>

                                <p class="x:text-center x:text-sm x:text-white">Prices from</p>
                                <p class="x:text-white x:text-[22px] x:text-center x:mb-4"><?= $course_overview['classroom_courses']['price'] ?></p>
                                <a class="btn fade_in x:w-full x:text-center x:justify-center x:min-w-0"
                                   href="#classroom-courses">Find
                                    out more</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $icon_row = get_field('icon_row'); ?>

            <?php if ($icon_row && $icon_row['enable']): ?>
                <div class="x:mt-20">

                    <h2 class="title_section fade_in x:text-center"><?= $icon_row['title'] ?></h2>
                    <p class="x:text-center"><?= $icon_row['subtitle'] ?></p>
                    <div class="x:flex x:items-center x:justify-center">
                        <?php if (is_array($icon_row['items'])): ?>
                            <?php foreach ($icon_row['items'] as $item): ?>
                                <div>
                                    <?= wp_get_attachment_image($item['icon'], 'full'); ?>
                                    <?= $item['text'] ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>


        </div>
    </div>


<?php
$variations_type = 0;
foreach ($product->get_available_variations() as $variations) {
    if (get_field('type', $variations['variation_id']) == 'Online') {
        $variations_type = 1;
    }
}
if ($variations_type) {
    ?>
<style>
    @media(max-width: 500px){
        #online-courses td{
            width: 150px;
        }
    }
</style>
    <?php $most_popular = get_field('most_popular'); ?>
    <div id="online-courses" class="content_section  pbm_no">
        <div class="container">
            <div class="">
                <div class="">
                    <div class="typical_text x:mt-12">
                        <h3 class="title_block x:flex x:items-center x:gap-2 x:mb-12 x:text-[#291261]">

                            <img src="<?php bloginfo('template_url'); ?>/images/course_icon_1.svg" alt="">

                            Our Online Course Options

                        </h3>
                        <table class=" x:w-full x:mx-auto x:mt-20 details_table">
                            <tbody>
                            <tr>
                                <td></td>
                                <td style="text-align: center;" class="x:text-center x:text-2xl x:font-bold x:relative">
                                    <?php if ($most_popular == 'gold'): ?>
                                        <div class="x:rounded-t-full x:bg-[#a27fff] x:text-sm x:font-normal x:text-white x:p-3 x:absolute x:top-0 x:-translate-y-full x:left-0 x:right-0">
                                            Most Popular
                                        </div>
                                    <?php endif; ?>
                                    Gold
                                </td>
                                <td style="text-align: center;" class="x:text-center x:text-2xl x:font-bold x:relative">
                                    <?php if ($most_popular == 'silver'): ?>
                                        <div class="x:rounded-t-full x:bg-[#a27fff] x:text-sm x:font-normal x:text-white x:p-3 x:absolute x:top-0 x:-translate-y-full x:left-0 x:right-0">
                                            Most Popular
                                        </div>
                                    <?php endif; ?>
                                    Silver
                                </td>
                                <td style="text-align: center;" class="x:text-center x:text-2xl x:font-bold x:relative">
                                    <?php if ($most_popular == 'bronze'): ?>
                                        <div class="x:rounded-t-full x:bg-[#a27fff] x:text-sm x:font-normal x:text-white x:p-3 x:absolute x:top-0 x:-translate-y-full x:left-0 x:right-0">
                                            Most Popular
                                        </div>
                                    <?php endif; ?>
                                    Bronze
                                </td>
                            </tr>

                            <?php $comparison_table = get_field('comparison_table'); ?>

                            <?php if (is_array($comparison_table)): ?>


                                <?php foreach ($comparison_table as $item): ?>

                                    <tr>
                                        <td><?= $item['feature'] ?></td>
                                        <td style="text-align: center;"><?php if ($item['gold']) { ?>✓<?php } ?></td>
                                        <td style="text-align: center;"><?php if ($item['silver']) { ?>✓<?php } ?></td>
                                        <td style="text-align: center;"><?php if ($item['bronze']) { ?>✓<?php } ?></td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php endif; ?>

                            <tr>
                                <td></td>
                                <td class="x:text-center x:md:text-2xl x:text-lg x:font-bold">
                                    <?= get_field('gold_price') ?>

                                    <?php echo do_shortcode("[custom_buy_now id=" . get_field('gold_code') . "]") ?>

                                </td>
                                <td class="x:text-center x:md:text-2xl x:text-lg x:font-bold">
                                    <?= get_field('silver_price') ?>

                                    <?php echo do_shortcode("[custom_buy_now id=" . get_field('silver_code') . "]") ?>

                                </td>
                                <td class="x:text-center x:md:text-2xl x:text-lg x:font-bold">
                                    <?= get_field('bronze_price') ?>

                                    <?php echo do_shortcode("[custom_buy_now id=" . get_field('bronze_code') . "]") ?>

                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>


<?php
$variations_type = 0;
foreach ($product->get_available_variations() as $variations) {
    if (get_field('type', $variations['variation_id']) == 'Virtual') {
        $variations_type = 1;
    }
}
if ($variations_type) {
    ?>

    <div id="online-courses" class="container x:pt-8">
        <h3 class="title_block x:flex x:items-center x:gap-2 x:mb-12 x:text-[#291261]">


            <img src="<?php bloginfo('template_url'); ?>/images/course_icon_1.svg" alt="">

            Our Virtual Course Options
        </h3>
<div class="x:py-20">

        <div class="swiper classroom-course-swiper ">
            <div class="swiper-wrapper">


                <?php
                foreach ($product->get_available_variations() as $variations) {
                    if (get_field('type', $variations['variation_id']) == 'Virtual') {
                        ?>
                        <div class="swiper-slide course_list_item x:mt-0  x:border-4 x:border-[#a27fff] x:bg-[#fff] x:p-4 x:rounded-[10px]"
                             data-product="<?php echo $product->get_id(); ?>"
                             data-variation="<?php echo $variations['variation_id']; ?>"
                             data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">
                            <div class="course_list_info">
                                <div class="course_list_details">
                                    <?php echo $variations['variation_description']; ?>
                                </div>
                            </div>
                            <div class="">
                                <p class="course_list_price x:text-center x:mb-4">
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
                                    <div class="quantity_counter x:hidden">
                                        <button class="counter_decrement decrement"></button>
                                        <input class="counter_value value" type="number" name="qyt" value="1">
                                        <button class="counter_increment increment"></button>
                                    </div>
                                    <a class="btn btn_purple add_varition x:mx-auto" href="#">Add to cart
                                        <svg class="svg_arrow_btn">
                                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>

        <div class="classroom_nav">
            <div class="classroom_arrow classroom_prev" tabindex="0" role="button" aria-label="Previous slide"
                 aria-controls="swiper-wrapper-fbb8af61117135d7">
                <svg class="svg_arrow_prev">
                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_prev"></use>
                </svg>
            </div>
            <div class="classroom_arrow classroom_next" tabindex="0" role="button" aria-label="Next slide"
                 aria-controls="swiper-wrapper-fbb8af61117135d7">
                <svg class="svg_arrow_next">
                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next"></use>
                </svg>
            </div>
        </div>
</div>

    </div>
<?php } ?>


<?php
$variations_type = 0;
foreach ($product->get_available_variations() as $variations) {
    if (get_field('type', $variations['variation_id']) == 'Classroom') {
        $variations_type = 1;
    }
}
if ($variations_type) {
    ?>
    <div id="classroom-courses" class="x:bg-[#a27fff]">
        <div class="container">
            <div class="x:py-20">
                <h3 class="title_block x:flex x:items-center x:gap-2 x:mb-12 x:text-[#291261]">

                    <svg width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.7895 18.4893C18.943 18.5742 19.0734 18.6975 19.1687 18.8477C19.2787 19.0214 19.337 19.2238 19.337 19.4305C19.337 19.6371 19.2787 19.8396 19.1687 20.0132C19.0587 20.1869 18.9016 20.3244 18.7163 20.4085L18.7895 18.4893ZM18.7895 18.4893V18.4671L18.7165 18.4337L10.4555 14.6555L10.4554 14.6554C10.3221 14.5947 10.1778 14.5632 10.0318 14.5632C9.88578 14.5632 9.74148 14.5947 9.60821 14.6554L9.60807 14.6555L1.34747 18.4335C1.34743 18.4335 1.34739 18.4335 1.34735 18.4336C1.16199 18.5177 1.00496 18.6552 0.894951 18.8288C0.784942 19.0025 0.726562 19.2049 0.726562 19.4116C0.726562 19.6183 0.784942 19.8207 0.894951 19.9944C1.00498 20.168 1.16206 20.3056 1.34747 20.3897L1.34805 20.3899L2.90088 21.0851L2.6418 21.1784L2.55916 21.2081V21.296V27.4355C2.55916 27.5928 2.61983 27.7443 2.72893 27.8566C2.83815 27.969 2.98705 28.0328 3.1431 28.0328C3.29914 28.0328 3.44805 27.969 3.55726 27.8566C3.66636 27.7443 3.72704 27.5928 3.72704 27.4355V21.9674L10.0721 19.5235L10.35 19.4165L10.079 19.2931L8.95921 18.7831L8.9114 18.7613L8.8624 18.7802L4.43227 20.4898L2.06671 19.4069L10.0272 15.7662L17.9876 19.4069L10.0271 23.0477L6.71958 21.5411L6.67188 21.5194L6.62296 21.5382L5.55362 21.9488V21.9458L5.38415 22.0103L4.46626 22.3598L4.38574 22.3905V22.4766V25.4709V25.5228L4.42259 25.5595L4.69795 25.8334L4.69791 25.8334L4.70057 25.8359C6.15901 27.2045 8.04965 27.985 10.0242 28.0327L10.0242 28.0329L10.0299 28.0327C11.9976 27.9907 13.8841 27.2207 15.3442 25.8647L15.3443 25.8648L15.3473 25.8617L15.6227 25.5878L15.6595 25.5512V25.4992V21.8014L18.7161 20.4086L18.7895 18.4893ZM5.55362 22.3161L9.59904 24.13C9.59915 24.13 9.59926 24.1301 9.59937 24.1301C9.73254 24.1908 9.87673 24.2222 10.0226 24.2222C10.1685 24.2222 10.3127 24.1908 10.4459 24.1301C10.446 24.1301 10.4461 24.13 10.4462 24.13L14.4824 22.3202V25.0193C13.2481 26.1465 11.6676 26.7905 10.018 26.8382C8.36852 26.7905 6.78801 26.1465 5.55362 25.0193V22.3161Z"
                              fill="#291261" stroke="#291261" stroke-width="0.25"/>
                        <path d="M18.7367 26.439H26.2282C28.4374 26.439 30.2282 24.6481 30.2282 22.439V5.46875C30.2282 3.25961 28.4374 1.46875 26.2282 1.46875H6.32031C4.11117 1.46875 2.32031 3.25961 2.32031 5.46875V14.1348"
                              stroke="#291261" stroke-width="2"/>
                        <line x1="5.25781" y1="5.60974" x2="28.0248" y2="5.60974" stroke="#291261" stroke-width="2"/>
                        <line x1="5.25781" y1="10.7507" x2="28.0248" y2="10.7507" stroke="#291261" stroke-width="2"/>
                        <line x1="23.6172" y1="15.1572" x2="28.0237" y2="15.1572" stroke="#291261" stroke-width="2"/>
                        <line x1="23.6172" y1="20.2981" x2="28.0237" y2="20.2981" stroke="#291261" stroke-width="2"/>
                    </svg>
                    Our Classroom Based Course Options
                </h3>

                <div class="swiper classroom-course-swiper">
                    <div class="swiper-wrapper">


                        <?php
                        foreach ($product->get_available_variations() as $variations) {
                            if (get_field('type', $variations['variation_id']) == 'Classroom') {
                                ?>
                                <div class="swiper-slide course_list_item x:mt-0  x:border-0 x:bg-white x:p-4 x:rounded-[10px]"
                                     data-product="<?php echo $product->get_id(); ?>"
                                     data-variation="<?php echo $variations['variation_id']; ?>"
                                     data-nonce="<?php echo wp_create_nonce('add_varition'); ?>">
                                    <div class="course_list_info">
                                        <div class="course_list_details">
                                            <?php echo $variations['variation_description']; ?>
                                        </div>
                                    </div>
                                    <div class="">
                                        <p class="course_list_price x:text-center x:mb-4">
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
                                            <div class="quantity_counter x:hidden">
                                                <button class="counter_decrement decrement"></button>
                                                <input class="counter_value value" type="number" name="qyt" value="1">
                                                <button class="counter_increment increment"></button>
                                            </div>
                                            <a class="btn add_varition x:mx-auto" href="#">Add to cart
                                                <svg class="svg_arrow_btn">
                                                    <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>

                <div class="classroom_nav">
                    <div class="classroom_arrow classroom_prev" tabindex="0" role="button" aria-label="Previous slide"
                         aria-controls="swiper-wrapper-fbb8af61117135d7">
                        <svg class="svg_arrow_prev">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_prev"></use>
                        </svg>
                    </div>
                    <div class="classroom_arrow classroom_next" tabindex="0" role="button" aria-label="Next slide"
                         aria-controls="swiper-wrapper-fbb8af61117135d7">
                        <svg class="svg_arrow_next">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_next"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


    <style>
        details[open] .switch_nav_arrow {
            transform: rotate(90deg);
        }
    </style>
    <div class="bg_grey x:py-20">
    <div class="container">
        <h3 class="title_block x:flex x:items-center x:gap-2 x:mb-12 x:text-[#291261]">
            Course Overview
        </h3>

        <?php $coa = get_field('course_overview_accordion'); ?>

        <?php if (!empty($coa)) : ?>
            <div class="x:grid x:grid-cols-1 <?= count($coa) > 1 ? 'x:md:grid-cols-4' : '' ?> x:gap-8 x:mb-16">
                <?php if (count($coa) > 1) : ?>
                    <ul class="x:md:col-span-1 x:sticky x:top-0 sidebar-links x:pt-[36px]">
                        <?php foreach ($coa as $key => $item) : ?>
                            <li class="x:mb-8"><a class="x:text-xl x:font-bold <?= $key == 0 ? 'x:border-b x:border-[#a27fff]' : '' ?>"
                                                  href="#<?= $key ?>"><?= $item['section'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php foreach ($coa as $key => $item) : ?>
                    <div id="<?= $key ?>" class="<?= $key > 0 ? 'x:hidden' : '' ?> x:md:col-span-3 accordion-panel">
                        <?php foreach ($item['faqs'] as $faq) : ?>
                            <details name="accordion-items">
                                <summary class="switch_nav_item current">
                                    <p class="switch_nav_name"><?= $faq['question'] ?></p>
                                    <div class="switch_nav_arrow">
                                        <svg class="svg_arrow_small">
                                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_small"></use>
                                        </svg>
                                    </div>
                                </summary>
                                <div class="content">
                                    <?= $faq['answer'] ?>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>


    <div class="cta_section " id="careers">
        <div class="cta_wrap">
            <div class="cta_img fade_left"
                 style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1;">
                <img decoding="async"
                     src="https://ligtas.co.uk/wp-content/uploads/2025/05/LIGTAS-76_websize-e1746715814764.jpg" alt="">
            </div>
            <div class="cta_box green fade_right"
                 style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1;">
                <div class="cta_description">
                    <h3 class="title_block">Booking for more than one candidate?
                        <br><span>Speak to one of our team today.</span></h3>
                    <a class="link_arrow" href="#">
                        Find out more
                        <svg class="svg_arrow_btn">
                            <use xlink:href="<?php bloginfo('template_url'); ?>/images/sprite/sprite.svg#arrow_btn"></use>
                        </svg>
                    </a>
                </div>
                <div class="cta_box_curve">
                    <picture>
                        <source media="(max-width: 767px)"
                                srcset="<?php bloginfo('template_url'); ?>/images/cta_green_mobile.png">
                        <source media="(min-width: 768px)"
                                srcset="<?php bloginfo('template_url'); ?>/images/cta_curve_green.png">
                        <img decoding="async" src="<?php bloginfo('template_url'); ?>/images/cta_curve_green.png"
                             alt="">
                    </picture>
                </div>
            </div>
        </div>
    </div>

    <?php
    $arg = array('post__not_in' => array($post->ID), 'post_type' => 'product', 'posts_per_page' => 3);
    $arg['tax_query'][] = array('taxonomy' => 'product_cat', 'field' => 'id', 'terms' => $term_id);
    $Nquery = new WP_Query($arg);
    if ($Nquery->have_posts()):
        ?>
        <div class="content_section">
            <div class="container">
                <?php if (get_field('section_more_product', $tfp) || get_field('title_more_posts', $tfp)) { ?>
                    <div class="content_description mb_64">
                        <?php if (get_field('section_more_product', $tfp)) { ?>
                            <h3 class="title_category fade_in"><?php echo get_field('section_more_product', $tfp); ?></h3>
                        <?php }
                        if (get_field('title_more_product', $tfp)) { ?>
                            <h2 class="title_section fade_in"><?php echo get_field('title_more_product', $tfp); ?></h2>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="courses_wrap">
                    <?php while ($Nquery->have_posts()): $Nquery->the_post();
                        $category = get_the_terms($post->ID, 'product_cat'); ?>
                        <div class="course_item fade_in">
                            <div class="course_item_main">
                                <div class="course_item_top">
                                    <span class="course_item_category"><?php echo get_term($category[0]->term_id)->name; ?></span>
                                    <?php if (has_post_thumbnail()) {
                                        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true); ?>
                                        <a class="course_item_img" href="<?php the_permalink(); ?>">
                                            <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title(); ?>">
                                        </a>
                                    <?php } ?>
                                </div>
                                <div class="course_item_info">
                                    <div class="course_item_icons">
                                        <?php if (get_field('online')) { ?>
                                            <img src="<?php bloginfo('template_url'); ?>/images/course_icon_1.svg"
                                                 alt="">
                                        <?php }
                                        if (get_field('virtual')) { ?>
                                            <img src="<?php bloginfo('template_url'); ?>/images/course_icon_2.svg"
                                                 alt="">
                                        <?php }
                                        if (get_field('classroom')) { ?>
                                            <img src="<?php bloginfo('template_url'); ?>/images/course_icon_3.svg"
                                                 alt="">
                                        <?php }
                                        if (get_field('workplace')) { ?>
                                            <img src="<?php bloginfo('template_url'); ?>/images/course_icon_4.svg"
                                                 alt="">
                                        <?php } ?>
                                    </div>
                                    <a class="course_item_name"
                                       href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
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
    <?php endif;
    wp_reset_query(); ?>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const sidebarLinks = document.querySelectorAll('ul.sidebar-links a');
            const sections = document.querySelectorAll('div[id].accordion-panel');

            sidebarLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();

                    // 1. Get the ID from the href (remove the #)
                    const targetId = link.getAttribute('href').substring(1);

                    // 2. Hide all sections
                    sections.forEach(section => {
                        section.classList.add('x:hidden');
                    });

                    // 3. Show the targeted section
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.remove('x:hidden');
                    }

                    // 4. Optional: Active link styling
                    sidebarLinks.forEach(l => l.classList.remove('x:border-b', 'x:border-[#a27fff]')); // Example class
                    link.classList.add('x:border-b', 'x:border-[#a27fff]');
                });
            });
        });

        jQuery(document).ready(function () {

            jQuery('body').on('click', '.add_varition', function (e) {
                e.preventDefault();
                var product = jQuery(this).closest('.course_list_item').data('product');
                var variation = jQuery(this).closest('.course_list_item').data('variation');
                var qyt = jQuery(this).closest('.course_list_item').find('[name=qyt]').val();
                var nonce = jQuery(this).closest('.course_list_item').data('nonce');
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {
                        'action': 'add_cart',
                        'product': product,
                        'variation': variation,
                        'qyt': qyt,
                        'nonce': nonce
                    },
                    success: function (datas) {
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: {'action': 'update_cart', 'product': product, 'nonce': nonce},
                            success: function (datas) {
                                jQuery('.course_buy_block').html(datas);
                            }
                        });
                    },
                    error: function (xhr, str) {
                        alert('Error: ' + xhr.responseCode);
                    }
                });
            });

            jQuery('body').on('click', '.course_buy_remove', function (e) {
                e.preventDefault();
                var product = jQuery(this).data('product');
                var variation = jQuery(this).data('variation');
                var nonce = jQuery(this).data('nonce');
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    data: {'action': 'remove_cart', 'variation': variation, 'nonce': nonce},
                    success: function (datas) {
                        jQuery.ajax({
                            url: ajaxurl,
                            type: 'post',
                            data: {'action': 'update_cart', 'product': product, 'nonce': nonce},
                            success: function (datas) {
                                jQuery('.course_buy_block').html(datas);
                            }
                        });
                    },
                    error: function (xhr, str) {
                        alert('Error: ' + xhr.responseCode);
                    }
                });
            });

        });
    </script>

    <!-- Mini Cart Panel -->
    <div id="mini-cart-panel" style="display:none;position:fixed;top:20px;right:20px;z-index:99999;width:320px;background:#fff;border-radius:10px;box-shadow:0 8px 32px rgba(41,18,97,0.2);padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="margin:0;font-size:18px;font-weight:bold;color:#291261;">Added to Cart ✓</h3>
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

        $(document).on('submit', '.cart', function(e) {
            e.preventDefault();
            var form     = $(this);
            var productId = form.find('[name="add-to-cart"]').val();
            var quantity  = form.find('[name="quantity"]').val() || 1;

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
                }
            });
        });

        function renderMiniCart(data) {
            var html = '';
            $.each(data.items, function(i, item) {
                html += '<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0;">';
                html += '<span style="color:#291261;font-size:14px;">' + item.name + ' &times; ' + item.quantity + '</span>';
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


<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

