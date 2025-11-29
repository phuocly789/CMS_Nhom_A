<?php

/**
 * Template Name: Contact Us Custom Layout
 * Description: Chỉ áp dụng cho trang Contact Us (ID = 6). Các trang khác dùng layout mặc định.
 *
 * @package JobScout
 */

get_header(); ?>

<?php
// === CHỈ HIỂN THỊ NỘI DUNG CUSTOM NÀY KHI TRANG HIỆN TẠI LÀ ID = 6 ===
if (get_the_ID() == 6): ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class('contact-us-page'); ?>>

                <!-- Header with background image -->
                <header class="contact-header">
                    <?php dynamic_sidebar('contact-header'); ?>
                </header>

                <!-- Headquarters Address -->
                <section class="headquarters" style="text-align: center; padding: 20px 0; background-color: #f9f9f9;">
                    <?php dynamic_sidebar('contact-address'); ?>
                </section>

                <!-- For Employers and Jobseekers -->
                <section class="contact-sections"
                    style="display: flex; justify-content: space-around; padding: 40px 0; background-color: #f0f0f0;">
                    <div class="for-employers" style="width: 45%; text-align: center;">
                        <?php dynamic_sidebar('contact-one'); ?>
                    </div>
                    <div class="for-jobseekers" style="width: 45%; text-align: center;">
                        <?php dynamic_sidebar('contact-two'); ?>
                    </div>
                </section>

            </article>
        </main>
    </div>

<?php
// === NẾU KHÔNG PHẢI TRANG ID = 6 → DÙNG GIAO DIỆN MẶC ĐỊNH CỦA THEME ===

elseif (get_the_ID() == 10): ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class('jobs-all'); ?>>
                <header class="contact-header">
                    <?php dynamic_sidebar('jobs-header'); ?>
                </header>

                <!-- SEARCH FORM ON JOBS PAGE - CHỈ HIỆN KHI ĐANG SEARCH -->
                <?php
                $is_searching = isset($_GET['search_keywords']) && !empty($_GET['search_keywords']) ||
                    isset($_GET['search_location']) && !empty($_GET['search_location']);

                if ($is_searching): ?>
                    <section class="job-search-section"
                        style="background: #fff; padding: 40px 0; border-bottom: 1px solid #eee;">
                        <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
                            <form method="GET" action="<?php echo esc_url(get_permalink(10)); ?>"
                                style="display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap;">

                                <!-- Keywords Search -->
                                <input type="text" name="search_keywords" placeholder="Job title, keywords..."
                                    style="padding: 12px; width: 300px; border: 1px solid #ddd; border-radius: 4px;"
                                    value="<?php echo isset($_GET['search_keywords']) ? esc_attr($_GET['search_keywords']) : ''; ?>">

                                <!-- Location Search -->
                                <input type="text" name="search_location" placeholder="Location..."
                                    style="padding: 12px; width: 200px; border: 1px solid #ddd; border-radius: 4px;"
                                    value="<?php echo isset($_GET['search_location']) ? esc_attr($_GET['search_location']) : ''; ?>">

                                <!-- Submit Button -->
                                <input type="submit" value="Search Jobs"
                                    style="padding: 12px 24px; background: #ff6600; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">

                                <!-- Reset Button -->
                                <a href="<?php echo esc_url(get_permalink(10)); ?>"
                                    style="padding: 12px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                    Reset
                                </a>
                            </form>

                            <!-- Show search info -->
                            <?php
                            $current_search = '';
                            if (isset($_GET['search_keywords']) && !empty($_GET['search_keywords'])) {
                                $current_search .= 'Keywords: "' . esc_html($_GET['search_keywords']) . '" ';
                            }
                            if (isset($_GET['search_location']) && !empty($_GET['search_location'])) {
                                $current_search .= 'Location: "' . esc_html($_GET['search_location']) . '"';
                            }

                            if ($current_search): ?>
                                <div style="text-align: center; margin-top: 15px; color: #666;">
                                    <strong>Searching for:</strong> <?php echo $current_search; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- JOBS LISTING SECTION -->
                <section class="all-jobs-section" style="background:#f8f8f8; padding:60px 0;">
                    <div class="container" style="max-width:1200px; margin:0 auto;">

                        <!-- Header -->
                        <div class="jobs-header"
                            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                            <h2 style="font-size:32px; color:#333; margin:0;">
                                <?php
                                if ($is_searching) {
                                    echo 'SEARCH RESULTS';
                                } else {
                                    echo 'ALL JOBS';
                                }
                                ?>
                            </h2>
                            <div class="custom-select-wrapper" style="position:relative; width:auto;">
                                <select id="jobs-filter"
                                    style="padding:10px 40px 10px 20px; border:1px solid #ddd; background:#fff; font-size:16px; cursor:pointer; width:auto !important; appearance:none; -webkit-appearance:none; -moz-appearance:none;">
                                    <option value="latest">Latest Jobs</option>
                                    <option value="popular">Popular Jobs</option>
                                    <option value="featured">Featured Jobs</option>
                                </select>
                                <i class="fa fa-chevron-down"
                                    style="position:absolute; right:15px; top:50%; transform:translateY(-50%); color:#666; pointer-events:none; font-size:14px;"></i>
                            </div>
                        </div>

                        <!-- Jobs Grid -->
                        <div id="jobs-grid" class="jobs-grid"
                            style="display:grid; grid-template-columns:repeat(2, 1fr); gap:30px;">
                            <!-- Jobs ban đầu sẽ load ở đây (PHP query đầu tiên) -->
                            <?php
                            $paged = 1;
                            $filter = 'latest';  // Default filter
                            $args = array(
                                'post_type' => 'job_listing',
                                'posts_per_page' => 8,
                                'post_status' => 'publish',
                                'paged' => $paged,
                            );

                            // ADD SEARCH FILTERS
                            if (isset($_GET['search_keywords']) && !empty($_GET['search_keywords'])) {
                                $args['s'] = sanitize_text_field($_GET['search_keywords']);
                            }

                            if (isset($_GET['search_location']) && !empty($_GET['search_location'])) {
                                $args['meta_query'] = array(
                                    array(
                                        'key' => '_job_location',
                                        'value' => sanitize_text_field($_GET['search_location']),
                                        'compare' => 'LIKE'
                                    )
                                );
                            }

                            // Áp dụng filter default
                            if ($filter == 'latest') {
                                $args['orderby'] = 'date';
                                $args['order'] = 'DESC';
                            } elseif ($filter == 'popular') {
                                $args['orderby'] = 'meta_value_num';
                                $args['meta_key'] = '_views_count';  // Giả sử dùng Post Views Counter
                                $args['order'] = 'DESC';
                            } elseif ($filter == 'featured') {
                                $args['meta_query'] = array(
                                    array(
                                        'key' => '_featured',
                                        'value' => 1,
                                        'compare' => '=',
                                    ),
                                );
                                $args['orderby'] = 'date';
                                $args['order'] = 'DESC';
                            }

                            $jobs_query = new WP_Query($args);

                            if ($jobs_query->have_posts()):
                                while ($jobs_query->have_posts()):
                                    $jobs_query->the_post();

                                    // Lấy dữ liệu job
                                    $company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : (has_post_thumbnail() ? get_the_post_thumbnail_url() : '');
                                    $company_name = function_exists('get_the_company_name') ? get_the_company_name() : 'Company Name';
                                    $job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'Ho Chi Minh City';

                                    $job_type_terms = wp_get_post_terms(get_the_ID(), 'job_listing_type', array('fields' => 'names'));
                                    $job_type = (!is_wp_error($job_type_terms) && !empty($job_type_terms)) ? $job_type_terms[0] : 'Fulltime';

                                    $job_category_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
                                    $job_category = (!is_wp_error($job_category_terms) && !empty($job_category_terms)) ? $job_category_terms[0] : 'Category Name';

                                    $job_excerpt = get_the_excerpt() ?: 'No description available.';
                                    $post_date = get_the_date('M d, Y');

                                    // Xử lý excerpt thành bullet points
                                    $job_excerpt = preg_replace('/([a-z0-9])([A-Z])/', '$1. $2', trim($job_excerpt));
                                    $excerpt_parts = explode('.', $job_excerpt);
                                    $bullets = [];
                                    foreach ($excerpt_parts as $part) {
                                        $part = trim($part);
                                        if ($part && strlen($part) > 10) {
                                            $bullets[] = $part;
                                        }
                                    }
                                    $bullets = array_slice($bullets, 0, 3);
                            ?>
                                    <!-- JOB CARD - GIỐNG VỚI MẪU YÊU CẦU -->
                                    <div class="job-card" style="flex-wrap: wrap; height: fit-content; background:#fff; padding:20px; border:1px solid #eee; box-shadow:0 2px 10px rgba(0,0,0,0.05); display:flex; gap:20px; align-items:flex-start;">
                                        <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
                                            <div class="job-logo" style="width:100px; height:auto; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                <?php if ($company_logo) : ?>
                                                    <img src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="max-width:100%;">
                                                <?php else : ?>
                                                    <i class="fa fa-briefcase" style="font-size:40px; color:#ccc;"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="job-content" style="flex:1; margin-left: 20px;">
                                                <h3 style="font-size:24px; color:#333; margin:0 0 5px 0; text-transform:uppercase;">
                                                    <a href="<?php the_permalink(); ?>" class="job-title-link" style="color:#333 !important; text-decoration:none;">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h3>
                                                <p style="font-size:14px; color:#666; margin:0 0 10px 0;">
                                                    Created: <?php echo esc_html($post_date); ?>
                                                </p>
                                                <div class="top-job-meta" style="border-radius: 0 !important;">
                                                    <span class="meta-badge"><?php echo esc_html($job_type); ?></span>
                                                    <span class="meta-badge"><?php echo esc_html($job_category); ?></span>
                                                    <span class="meta-badge"><?php echo esc_html($job_location); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <ul style="list-style-type:disc; padding-left:20px; margin:0; font-size:14px; color:#555; line-height:1.6;">
                                            <?php if (!empty($bullets)) : ?>
                                                <?php foreach ($bullets as $bullet) : ?>
                                                    <li><?php echo esc_html($bullet); ?></li>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <li>No description available</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<div style="grid-column: 1 / -1; text-align:center; padding:40px; color:#999;">';
                                echo '<h3>No jobs found</h3>';
                                if ($is_searching) {
                                    echo '<p>Try adjusting your search criteria or <a href="' . esc_url(get_permalink(10)) . '">browse all jobs</a>.</p>';
                                } else {
                                    echo '<p>No job listings available at the moment.</p>';
                                }
                                echo '</div>';
                            endif;
                            ?>
                        </div>

                        <!-- Load More Button -->
                        <div class="load-more" style="text-align:center; margin-top:50px;">
                            <?php if ($jobs_query->max_num_pages > 1): ?>
                                <button id="load-more-jobs" data-page="1"
                                    data-max-pages="<?php echo $jobs_query->max_num_pages; ?>"
                                    data-filter="<?php echo $filter; ?>"
                                    style="padding: 10px 20px; background-color: transparent; color: orange; border: 1px solid orange; cursor: pointer;">
                                    LOAD MORE JOBS
                                </button>
                            <?php endif; ?>
                        </div>

                    </div>
                </section>
            </article>
        </main>
    </div>
<?php
// === THÊM PHẦN ABOUT (ID = 1076) ===
elseif (get_the_ID() == 1076): ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class('about-page'); ?>>

                <!-- Header giống Jobs: dùng dynamic_sidebar -->
                <header class="about-header">
                    <?php dynamic_sidebar('about-header'); ?>
                </header>

                <!-- About Vision/Mission Section -->
                <section class="about-vision"
                    style="display: flex; flex-direction: column; align-items: center; margin: 0 auto; padding: 60px 20px; background: #f2f2f2;">
                    <h2 style="text-align: center; font-size: 36px; color: #333; margin-bottom: 40px; font-weight: bold;">
                        ABOUT US</h2>
                    <div class="about-vision-content"
                        style="display: flex; max-width: 1200px; gap: 40px; align-items: center; width: 100%;">
                        <div class="about-image" style="flex: 1; text-align: center;">
                            <?php dynamic_sidebar('about-image'); ?>
                        </div>
                        <div class="about-text" style="flex: 1;">
                            <?php dynamic_sidebar('about-vision-mission'); ?>
                        </div>
                    </div>
                </section>

                <!-- Business Section (Hotels, etc.) -->
                <section class="about-business" style="background: #fff; padding: 60px 20px; text-align: center;">
                    <div class="container" style="margin: 0 auto;">
                        <?php dynamic_sidebar('about-business'); ?>
                    </div>
                </section>

                <!-- Team/Office Section -->
                <section class="about-team"
                    style="display: flex; margin: 0 auto; padding: 60px 190px; gap: 40px; align-items: center; background: #f2f2f2;">
                    <div class="team-info" style="flex: 1; text-align: center;">
                        <?php dynamic_sidebar('about-team'); ?>
                    </div>
                    <div class="team-image" style="flex: 1; text-align: center;">
                        <?php dynamic_sidebar('about-team-image'); ?>
                    </div>
                </section>

            </article>
        </main>
    </div>
<?php
else:
    // Load nội dung trang bình thường như theme gốc
?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            </article>
        </main>
    </div>
<?php
endif;
?>

<?php
get_sidebar();
get_footer();
