<?php

/**
 * Custom Job Listing Card Template (Modern Design)
 *
 * File: content-job_listing-custom.php
 */
$company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : '';
$job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'Ho Chi Minh City';

$job_type_terms = wp_get_post_terms(get_the_ID(), 'job_listing_type', array('fields' => 'names'));
$job_type = (!is_wp_error($job_type_terms) && !empty($job_type_terms)) ? $job_type_terms[0] : 'Fulltime';

$job_category_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
$job_category = (!is_wp_error($job_category_terms) && !empty($job_category_terms)) ? $job_category_terms[0] : 'Category Name';

$job_excerpt = get_the_excerpt();
$post_date = get_the_date('M d, Y');

// Normalize by inserting period before capital letters if stuck (camelcase fix)
$job_excerpt = preg_replace('/([a-z0-9])([A-Z])/', '$1. $2', trim($job_excerpt));

// Tách excerpt bằng dấu chấm (.) để lấy câu
$excerpt_parts = explode('.', $job_excerpt);
$bullets = [];
foreach ($excerpt_parts as $part) {
    $part = trim($part);
    if ($part) {
        $bullets[] = $part;
    }
}
$bullets = array_slice($bullets, 0, 3); // Chỉ lấy 3 câu đầu tiên
?>

<div class="top-job-card">
    <div class="top-job-card-header">
        <div class="top-job-logo">
            <?php if ($company_logo) : ?>
                <img src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
            <?php else : ?>
                <div class="logo-placeholder">
                    <i class="fas fa-briefcase"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="top-job-info">
            <h3 class="top-job-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p class="top-job-date">Created: <?php echo esc_html($post_date); ?></p>

            <div class="top-job-meta">
                <span class="meta-badge"><?php echo esc_html($job_type); ?></span>
                <span class="meta-badge"><?php echo esc_html($job_category); ?></span>
                <span class="meta-badge"><?php echo esc_html($job_location); ?></span>
            </div>
        </div>
    </div>

    <ul class="top-job-bullets">
        <?php if (!empty($bullets)) : ?>
            <?php foreach ($bullets as $bullet) : ?>
                <li><?php echo esc_html($bullet); ?></li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>No description available</li>
        <?php endif; ?>
    </ul>
</div>