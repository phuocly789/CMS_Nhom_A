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
else :
    // Load nội dung trang bình thường như theme gốc
    while (have_posts()) : the_post(); ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main">
                <?php get_template_part('template-parts/content', 'page'); ?>
            </main>
        </div>
<?php endwhile;
endif;
?>

<?php
get_sidebar();
get_footer();
