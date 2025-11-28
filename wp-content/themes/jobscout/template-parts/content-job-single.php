<?php

/**
 * The template for displaying single job listings.
 * UPDATED VERSION - Please replace the old file content with this.
 */
$company_logo_url = get_the_company_logo();
?>

<div class="job-single-container">

        <!-- Breadcrumb -->
        <nav class="job-breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a> /
                <a href="<?php echo get_permalink(10); ?>">All Jobs</a> /
                <span class="current"><?php the_title(); ?></span>
        </nav>

        <div class="job-detail-card">

                <!-- Job Header -->
                <header class="job-header">
                        <figure class="company-logo">
                                <?php the_company_logo('thumbnail'); ?>
                        </figure>

                        <div class="job-title-section">
                                <h1 class="job-title"><?php the_title(); ?></h1>

                                <!-- Date is now separate for correct styling -->
                                <span class="created-date">Created: <?php echo get_the_date('M d, Y'); ?></span>

                                <!-- Meta tags are in their own container -->
                                <div class="job-meta-main">
                                        <span class="job-type"><?php echo esc_html(get_post_meta(get_the_ID(), 'job_type', true) ?: 'Fulltime'); ?></span>
                                        <span class="job-category">
                                                <?php
                                                $categories = get_the_terms(get_the_ID(), 'job_listing_category');
                                                if ($categories && !is_wp_error($categories)) {
                                                        echo esc_html($categories[0]->name);
                                                } else {
                                                        echo 'Category Name';
                                                }
                                                ?>
                                        </span>
                                        <span class="job-location"><?php echo esc_html(get_post_meta(get_the_ID(), 'job_location', true) ?: 'Ho Chi Minh City'); ?></span>
                                </div>
                        </div>

                        <div class="job-actions">
                                <button class="share-btn">SHARE</button>

                                <?php get_job_manager_template_part('job-application'); ?>

                        </div>
                </header>

                <!-- Main Content Wrapper (Grid Layout) -->
                <div class="job-content-wrapper">

                        <!-- Left column -->
                        <main class="job-content-main">
                                <section class="content-section">
                                        <h2>Overview about Company</h2>
                                        <div class="section-content">
                                                <?php echo remove_video_from_content(get_the_content()); ?>

                                        </div>
                                </section>

                                <section class="content-section">
                                        <h2>Our Key Skills</h2>
                                        <div class="section-content">
                                                <?php
                                                $skills = get_post_meta(get_the_ID(), 'key_skills', true);
                                                echo $skills ? wpautop(esc_html($skills)) : '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fauibus lectus tristique massa gravida vel elementum, mi. Sit scelerisque at amet leo. In volutpat turpis dolor, at. Vivamus volutpat in nunc, porttitor dui. Ut placerat aenean accumsan a, aenean lacus eu. Aliquet urna, habitasse elit lorem id enim quam. Eu varius nulla nullam dignissim massa tempor, massa tortor.</p>';
                                                ?>
                                        </div>
                                </section>
                        </main>

                        <!-- Right sidebar -->
                        <aside class="job-sidebar">
                                <div class="sidebar-widget">
                                        <h3>Staff Rating</h3>
                                        <div class="rating-wrapper">

                                                <div class="rating-stars">
                                                        <i class="star fas fa-star"></i>
                                                        <i class="star fas fa-star"></i>
                                                        <i class="star fas fa-star"></i>
                                                        <i class="star fas fa-star"></i>
                                                        <i class="star far fa-star"></i>
                                                </div>

                                                <span class="rating-number">4.0</span>

                                        </div>
                                </div>

                                <div class="sidebar-widget">
                                        <h3>Company Photos</h3>
                                        <div class="company-photos">
                                                <div class="photo-item" style="background-image: url('http://goldviet24k.vn/pic/News/images/tin-khac/loi-chuc-thanh-cong-trong-cong-viec(3).jpg');">
                                                        <div class="photo-overlay">+5</div>
                                                </div>
                                        </div>
                                </div>
                        </aside>

                </div>
        </div>

        <!-- Other Jobs Section -->
        <aside class="other-jobs-section">
                <h2>OTHER JOBS</h2>

                <div class="other-jobs-grid">
                        <?php
                        global $post;
                        $original_post = $post; // LƯU LẠI POST GỐC

                        $related_jobs_query = new WP_Query([
                                'post_type'      => 'job_listing',
                                'posts_per_page' => 6,
                                'post__not_in'   => [get_the_ID()],
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                        ]);

                        if ($related_jobs_query->have_posts()) :

                                while ($related_jobs_query->have_posts()) :
                                        $related_jobs_query->the_post();

                                        // GÁN LẠI GLOBAL POST CHO TEMPLATE
                                        $post = $related_jobs_query->post;

                                        // GỌI TEMPLATE CHUẨN
                                        include locate_template('template-parts/content-job_listing-custom.php');


                                endwhile;

                        endif;

                        // KHÔI PHỤC GLOBAL POST GỐC
                        $post = $original_post;
                        wp_reset_postdata();
                        ?>
                </div>
        </aside>

</div>