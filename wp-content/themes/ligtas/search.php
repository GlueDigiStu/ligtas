<?php get_header(); ?>

<div class="container" style="padding-top: 150px;">
  <h1 class="search_main_heading" style="margin-bottom: 30px;">Search results for: <?php echo esc_html(get_search_query()); ?></h1>

  <?php
  $post_types = array(
    'post'    => 'News Articles',
    'page'    => 'Pages',
    'cases'   => 'Case Studies',
    'product' => 'Available Training'
  );

  $found_any = false;

  foreach ($post_types as $type => $label) :
    $args = array(
      'post_type'      => $type,
      's'              => get_search_query(),
      'posts_per_page' => ($type == 'page') ? 4 :-1
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) :
      $found_any = true;
      ?>
      <h2 class="search_heading search_heading_<?php echo esc_attr($type); ?>"><?php echo esc_html($label); ?></h2>
      <div class="search_results search_results_<?php echo esc_attr($type); ?>">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
          <?php
            // Category/term logic for posts
            $category = get_the_category();
            $term_id = !empty($category) ? $category[0]->cat_ID : null;
            $short_title = $term_id ? get_field('short_title', 'category_' . $term_id) : '';
            $post_time = get_the_time("F Y");
            $has_thumb = has_post_thumbnail();
            $thumb = $has_thumb ? wp_get_attachment_image_src(get_post_thumbnail_id(), 'full', true) : null;
          ?>
          <a class="post_item fade_in search_item" href="<?php the_permalink(); ?>">
            <?php if ($type != 'page') : ?>
            <div class="post_item_top">
              <div class="post_item_details search_item_details">
                <?php if($type == 'post' || $type == 'cases') { ?>
                  <p><?php echo esc_html($post_time); ?></p>
                <?php } ?>
              </div>
              <?php if ($has_thumb && $thumb) : ?>
                <div class="search_item_img">
                  <img src="<?php echo esc_url($thumb[0]); ?>" alt="<?php the_title_attribute(); ?>">
                </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="post_item_info search_item_info">
              <p class="post_item_name"><?php the_title(); ?></p>
              <div class="post_item_more search_item_more">
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
      <?php
    endif;
    wp_reset_postdata();
  endforeach;

  if (!$found_any) : ?>
    <h2>Nothing found</h2>
  <?php endif; ?>
</div>

<?php get_footer(); ?>

<style>
  .search_heading {
    margin-top: 30px;
    margin-bottom: 30px;
  }
  .search_results {
    display: flex;
    flex-wrap: wrap;
  }
  .search_item {
    margin-left: 0px;
  }
  @media screen and (max-width: 767px) {
    .post_item:last-child {
      margin-bottom: 30px;
    }
  }
  .search_item_details {
    color: #FFF;
    font-size: 16px;
  }
  .search_item_img {
    position: absolute;
    overflow: hidden;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
  }
  .search_item_img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .search_item_info {
    padding-top: 10px;
    padding-bottom: 10px;
  }
  .search_item_more {
    margin-top: 25px;
  }
</style>