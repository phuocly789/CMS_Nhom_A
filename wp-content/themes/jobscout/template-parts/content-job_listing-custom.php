<?php

/**
 * Custom Job Listing Card Template (Modern Design)
 *
 * File: content-job_listing-custom.php
 */

global $post;
$salary   = get_post_meta(get_the_ID(), '_job_salary', true);
?>
<a href="<?php the_job_permalink(); ?>" class="oj-card">
    <article class="oj-card-inner">

        <!-- Logo -->
        <div class="oj-card-logo">
            <?php the_company_logo('thumbnail'); ?>
        </div>

        <!-- Main Content -->
        <div class="oj-card-body">

            <div> <!-- Wrapper for top content -->
                <h3 class="oj-title"><?php the_title(); ?></h3>
                <div class="oj-company"><?php the_company_name('@ '); ?></div>
            </div>

            <hr class="oj-separator">

            <div class="oj-meta-row"> <!-- Wrapper for meta info -->
                <?php if ($salary): ?>
                    <span class="oj-meta">
                        <i class="fas fa-dollar-sign"></i> <!-- FontAwesome Icon -->
                        <?php echo esc_html($salary); ?>
                    </span>
                <?php endif; ?>

                <span class="oj-meta">
                    <i class="fas fa-map-marker-alt"></i> <!-- FontAwesome Icon -->
                    <?php the_job_location(false); ?>
                </span>
            </div>

            <div class="oj-badge-wrapper">
                <?php
                // Ưu tiên hiển thị Job Category, nếu không có thì fallback ra Job Type
                $cats = get_the_terms(get_the_ID(), 'job_listing_category');
                if ($cats && ! is_wp_error($cats)) {
                    echo '<span class="oj-badge">' . esc_html($cats[0]->name) . '</span>';
                } else {
                    $types = wpjm_get_the_job_types();
                    if (!empty($types)) {
                        echo '<span class="oj-badge">' . esc_html($types[0]->name) . '</span>';
                    }
                }
                ?>
            </div>

        </div>

    </article>
</a>