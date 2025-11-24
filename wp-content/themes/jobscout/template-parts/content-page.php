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
if (get_the_ID() == 6) : ?>

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
                <section class="contact-sections" style="display: flex; justify-content: space-around; padding: 40px 0; background-color: #f0f0f0;">
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

elseif (get_the_ID() == 10) : ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class('jobs-all'); ?>>
                <header class="contact-header">
                    <?php dynamic_sidebar('jobs-header'); ?>
                </header>

                <!-- Phần từ ALL JOBS trở xuống -->
                <section class="all-jobs-section" style="background:#f8f8f8; padding:60px 0;">
                    <div class="container" style="max-width:1200px; margin:0 auto;">

                        <!-- Tiêu đề + Dropdown Filter -->
                        <div class="jobs-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                            <h2 style="font-size:32px; color:#333; margin:0;">ALL JOBS</h2>
                            <select id="jobs-filter" style="padding:10px 20px; border:1px solid #ddd; background:#fff; font-size:16px; cursor:pointer;width: auto !important;">
                                <option value="latest">Latest Jobs</option>
                                <option value="popular">Popular Jobs</option>
                                <option value="featured">Featured Jobs</option>
                            </select>
                        </div>

                        <!-- Grid danh sách jobs (sẽ reload bằng AJAX) -->
                        <div id="jobs-grid" class="jobs-grid" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:30px;">
                            <!-- Jobs ban đầu sẽ load ở đây (PHP query đầu tiên) -->
                            <?php
                            $paged = 1;
                            $filter = 'latest';  // Default filter
                            $args = array(
                                'post_type'      => 'job_listing',
                                'posts_per_page' => 8,
                                'post_status'    => 'publish',
                                'paged'          => $paged,
                            );

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
                                        'key'     => '_featured',
                                        'value'   => 1,
                                        'compare' => '=',
                                    ),
                                );
                                $args['orderby'] = 'date';
                                $args['order'] = 'DESC';
                            }

                            $jobs_query = new WP_Query($args);

                            if ($jobs_query->have_posts()) :
                                while ($jobs_query->have_posts()) : $jobs_query->the_post();
                                    // Code job-card giống trước (lặp lại để đầy đủ)
                                    $company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : (has_post_thumbnail() ? get_the_post_thumbnail_url() : '');
                                    $job_title    = get_the_title();
                                    $job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'No location';
                                    $job_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
                                    $job_category = (is_array($job_terms)) ? (!empty($job_terms) ? implode(', ', $job_terms) : 'Uncategorized') : 'Uncategorized';
                                    $job_excerpt  = get_the_excerpt() ?: 'No description';
                            ?>
                                    <div class="job-card" style="background:#fff; padding:25px; border:1px solid #eee; box-shadow:0 2px 10px rgba(0,0,0,0.05); display:flex; gap:20px; align-items:start;">
                                        <div class="job-logo" style="width:80px; height:80px; background:#f0f0f0; display:flex; align-items:center; justify-content:center;">
                                            <?php if ($company_logo) : ?><img src="<?php echo $company_logo; ?>" alt="Logo" style="max-width:100%; max-height:100%;"><?php else : ?><i class="fa fa-briefcase" style="font-size:40px; color:#ccc;"></i><?php endif; ?>
                                        </div>
                                        <div class="job-content" style="flex:1;">
                                            <h3 style="font-size:22px; color:#333; margin:0 0 10px 0;"><?php echo $job_title; ?></h3>
                                            <p style="font-size:16px; color:#666; margin:0 0 10px 0;"><?php echo $job_category; ?> / <?php echo $job_location; ?></p>
                                            <p style="font-size:15px; color:#555; line-height:1.6; margin:0;"><?php echo wp_trim_words($job_excerpt, 30); ?></p>
                                        </div>
                                    </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<p style="text-align:center; color:#999;">No jobs found.</p>';
                            endif;
                            ?>
                        </div>

                        <!-- Load More Button -->
                        <div class="load-more" style="text-align:center; margin-top:50px;">
                            <?php if ($jobs_query->max_num_pages > 1) : ?>
                                <button id="load-more-jobs" data-page="1" data-max-pages="<?php echo $jobs_query->max_num_pages; ?>" data-filter="<?php echo $filter; ?>"
                                    style="padding:15px 40px; background:#ff6600; color:#fff; border:none; font-weight:bold; cursor:pointer; text-transform:uppercase; border-radius:5px;">
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
else :
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
