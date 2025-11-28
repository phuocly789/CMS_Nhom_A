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
                            <?php
                            $paged = 1;
                            $filter = 'latest';

                            // SETUP QUERY WITH SEARCH FILTERS
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

                            // APPLY FILTERS
                            if ($filter == 'latest') {
                                $args['orderby'] = 'date';
                                $args['order'] = 'DESC';
                            } elseif ($filter == 'popular') {
                                $args['orderby'] = 'meta_value_num';
                                $args['meta_key'] = '_views_count';
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
                                    $company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : (has_post_thumbnail() ? get_the_post_thumbnail_url() : '');
                                    $job_title = get_the_title();
                                    $job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'No location';
                                    $job_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
                                    $job_category = (is_array($job_terms)) ? (!empty($job_terms) ? implode(', ', $job_terms) : 'Uncategorized') : 'Uncategorized';
                                    $job_excerpt = get_the_excerpt() ?: 'No description';
                                    ?>
                                    <div class="job-card"
                                        style="background:#fff; padding:25px; border:1px solid #eee; box-shadow:0 2px 10px rgba(0,0,0,0.05); display:flex; gap:20px; align-items:start;">
                                        <div class="job-logo"
                                            style="width:80px; height:80px; background:#f0f0f0; display:flex; align-items:center; justify-content:center;">
                                            <?php if ($company_logo): ?>
                                                <img src="<?php echo $company_logo; ?>" alt="Logo"
                                                    style="max-width:100%; max-height:100%;">
                                            <?php else: ?>
                                                <i class="fa fa-briefcase" style="font-size:40px; color:#ccc;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="job-content" style="flex:1;">
                                            <h3 style="font-size:22px; color:#333; margin:0 0 10px 0;">
                                                <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                                                    <?php echo $job_title; ?>
                                                </a>
                                            </h3>
                                            <p style="font-size:16px; color:#666; margin:0 0 10px 0;">
                                                <?php echo $job_category; ?> / <?php echo $job_location; ?>
                                            </p>
                                            <p style="font-size:15px; color:#555; line-height:1.6; margin:0;">
                                                <?php echo wp_trim_words($job_excerpt, 30); ?>
                                            </p>
                                        </div>
                                        <ul style="height: fit-content; list-style-type:disc; padding-left:20px; margin:0; font-size:14px; color:#555; line-height:1.6;">
                                            <?php if (!empty($excerpt_lines)): ?>
                                                <?php foreach ($excerpt_lines as $line): ?>
                                                    <li><?php echo esc_html($line); ?></li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
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