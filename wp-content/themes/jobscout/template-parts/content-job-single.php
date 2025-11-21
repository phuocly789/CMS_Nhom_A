<?php

/**
 * The template for displaying single job listings.
 *
 * Please note, this is a simplified template. You might need to adjust
 * the get_post_meta keys ('company_logo', 'job_type', etc.) to match
 * the actual keys used by your theme or plugin (like ACF, MetaBox).
 */
?>

<div class="job-single-container">

        <!-- Breadcrumb -->
        <nav class="job-breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a> /
                <a href="<?php echo get_post_type_archive_link('job_listing'); ?>">All Jobs</a> /
                <span class="current"><?php the_title(); ?></span>
        </nav>

        <div class="job-detail-card">

                <!-- Job Header -->
                <header class="job-header">
                        <div class="logo-wrapper">
                                <?php
                                // Giả sử 'company_logo' là URL của logo lưu trong custom field.
                                $company_logo_url = get_post_meta(get_the_ID(), 'company_logo', true);
                                if ($company_logo_url) {
                                        echo '<img src="' . esc_url($company_logo_url) . '" alt="' . get_the_title() . ' Logo">';
                                } else {
                                        // Fallback nếu không có logo
                                        echo '<img src="https://i.ibb.co/6yB6B46/sodoh-logo.png" alt="The Sodoh Logo">';
                                }
                                ?>
                        </div>

                        <div class="job-title-section">
                                <h1 class="job-title"><?php the_title(); ?></h1>
                                <div class="job-meta-main">
                                        <span class="created-date">Created: <?php echo get_the_date('M d, Y'); ?></span>
                                        <span class="job-type"><?php echo esc_html(get_post_meta(get_the_ID(), 'job_type', true) ?: 'Fulltime'); ?></span>
                                        <span class="job-category">
                                                <?php
                                                $categories = get_the_terms(get_the_ID(), 'job_listing_category'); // Thay 'job_listing_category' bằng taxonomy của bạn
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
                                <a href="#apply" class="apply-btn">APPLY JOB</a>
                        </div>
                </header>

                <!-- Main Content Wrapper -->
                <div class="job-content-wrapper">

                        <!-- Left column -->
                        <main class="job-content-main">
                                <section class="content-section">
                                        <h2>Overview about Company</h2>
                                        <div class="section-content">
                                                <?php the_content(); // Nội dung chính của bài đăng 
                                                ?>
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

                                <section class="content-section">
                                        <h2>Location</h2>
                                        <div class="section-content">
                                                <?php
                                                $location_details = get_post_meta(get_the_ID(), 'location_details', true);
                                                echo $location_details ? wpautop(esc_html($location_details)) : '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fauibus lectus tristique massa gravida vel elementum, mi. Sit scelerisque at amet leo. In volutpat turpis dolor, at. Vivamus volutpat in nunc, porttitor dui.</p>';
                                                ?>
                                        </div>
                                </section>
                        </main>

                        <!-- Right sidebar -->
                        <aside class="job-sidebar">
                                <div class="sidebar-card">
                                        <h3>Staff Rating</h3>
                                        <div class="rating-section">
                                                <div class="rating-stars" title="4.0 out of 5 stars">
                                                        <span class="star filled">★</span>
                                                        <span class="star filled">★</span>
                                                        <span class="star filled">★</span>
                                                        <span class="star filled">★</span>
                                                        <span class="star">★</span>
                                                </div>
                                                <div class="rating-score">4.0</div>
                                        </div>
                                </div>

                                <div class="sidebar-card">
                                        <h3>Company Photos</h3>
                                        <div class="company-photos">
                                                <div class="photo-item" style="background-image: url('https://i.ibb.co/L5TzPjQ/sodoh-photo.jpg');">
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
                        $related_jobs_query = new WP_Query([
                                'post_type'      => 'job_listing',
                                'posts_per_page' => 4,
                                'post__not_in'   => [get_the_ID()],
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                        ]);

                        if ($related_jobs_query->have_posts()) :
                                // Dòng lặp ĐÚNG phải có a`$related_jobs_query->the_post();`
                                while ($related_jobs_query->have_posts()) : $related_jobs_query->the_post();

                                        // --- Lấy dữ liệu ---
                                        $logo_url     = get_post_meta(get_the_ID(), 'company_logo', true);
                                        $location     = get_post_meta(get_the_ID(), 'job_location', true) ?: 'Chưa cập nhật';
                                        $categories   = get_the_terms(get_the_ID(), 'job_listing_category');

                                        // Bạn cần thay key đúng cho 2 trường này
                                        $company_name = get_post_meta(get_the_ID(), 'company_name', true) ?: 'Tên công ty';
                                        $salary       = get_post_meta(get_the_ID(), 'job_salary', true) ?: 'Thương lượng';
                        ?>

                                        <!-- Bắt đầu cấu trúc HTML mới cho mỗi card -->
                                        <div class="other-job-card">
                                                <div class="job-card-logo">
                                                        <?php if ($logo_url) : ?>
                                                                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($company_name); ?> Logo">
                                                        <?php else: ?>
                                                                <div class="logo-placeholder"></div>
                                                        <?php endif; ?>
                                                </div>
                                                <div class="job-card-details">
                                                        <h3 class="job-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                        <p class="job-card-company">@ <?php echo esc_html($company_name); ?></p>

                                                        <hr class="job-card-separator">

                                                        <div class="job-card-meta">
                                                                <span class="meta-item salary">
                                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgb(108, 117, 125);">
                                                                                <path d="M3 6h18M3 10h18M3 14h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                        </svg>
                                                                        <?php echo esc_html($salary); ?>
                                                                </span>
                                                                <span class="meta-item location">
                                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: rgb(108, 117, 125);">
                                                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                                <path d="M12 10a3 3 0 100-6 3 3 0 000 6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                        </svg>
                                                                        <?php echo esc_html($location); ?>
                                                                </span>
                                                        </div>

                                                        <div class="job-card-category">
                                                                <?php if ($categories && !is_wp_error($categories)) : ?>
                                                                        <span class="category-tag"><?php echo esc_html($categories[0]->name); ?></span>
                                                                <?php else : ?>
                                                                        <span class="category-tag">General</span>
                                                                <?php endif; ?>
                                                        </div>
                                                </div>
                                        </div>
                                        <!-- Kết thúc cấu trúc HTML mới -->

                        <?php
                                endwhile;
                                wp_reset_postdata();
                        endif;
                        ?>
                </div>
        </aside>

</div>