<?php
/**
 * Job Posting Section
 * 
 * @package JobScout
 */

$job_title         = get_theme_mod( 'job_posting_section_title', __( 'TOP JOBS', 'jobscout' ) );
$ed_jobposting     = get_theme_mod( 'ed_jobposting', true );
$count_posts       = wp_count_posts('job_listing'); 
if ( $ed_jobposting && jobscout_is_wp_job_manager_activated() && $job_title  ) {
    ?>
    <section id="job-posting-section" class="top-job-section">
        <div class="container">
            <?php 
                if( $job_title ) echo '<h2 class="section-title">'. esc_html( $job_title ) .'</h2>'; 
                if( jobscout_is_wp_job_manager_activated() && $count_posts->publish != 0 ){ 
                    // Get top 6 jobs
                    $args = array(
                        'post_type' => 'job_listing',
                        'posts_per_page' => 6,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    );
                    $jobs_query = new WP_Query($args);
                    
                    if ($jobs_query->have_posts()) :
                    ?>
                        <div class="top-jobs-grid">
                            <?php
                            while ($jobs_query->have_posts()) : $jobs_query->the_post();
                                $company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : '';
                                
                                // Get real data from database
                                $job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'Ho Chi Minh City';
                                
                                // Get job type with error checking
                                $job_type_terms = wp_get_post_terms(get_the_ID(), 'job_listing_type', array('fields' => 'names'));
                                $job_type = (!is_wp_error($job_type_terms) && !empty($job_type_terms)) ? $job_type_terms[0] : 'Fulltime';
                                
                                // Get job category with error checking
                                $job_category_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
                                $job_category = (!is_wp_error($job_category_terms) && !empty($job_category_terms)) ? $job_category_terms[0] : 'Category Name';
                                
                                $job_excerpt = get_the_excerpt();
                                $post_date = get_the_date('M d, Y');
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
                                    <?php if ($job_excerpt) : 
                                        // Parse excerpt into bullet points (split by line breaks or create default bullets)
                                        $bullets = preg_split('/[\r\n]+/', strip_tags($job_excerpt), -1, PREG_SPLIT_NO_EMPTY);
                                        if (count($bullets) < 3) {
                                            // If not enough bullets, create default ones
                                            $bullets = array(
                                                'Be responsible for the effective operational management of the hotel',
                                                'Excellent salary bonuses & recognition activities',
                                                'Foreign language allowance (up to 500USD/month)'
                                            );
                                        } else {
                                            // Limit to 3 bullets
                                            $bullets = array_slice($bullets, 0, 3);
                                        }
                                    ?>
                                    <ul class="top-job-bullets">
                                        <?php foreach ($bullets as $bullet) : ?>
                                            <li><?php echo esc_html($bullet); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <?php endif; ?>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                        
                        <?php
                        // Check if there are more jobs to show
                        if ($jobs_query->found_posts > 6) : ?>
                            <div class="view-more-jobs-wrapper">
                                <button id="view-all-jobs-btn" class="view-more-jobs-btn" data-total="<?php echo $jobs_query->found_posts; ?>">
                                    VIEW MORE JOBS
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php
                    endif;
                } 
            ?>
        </div>
    </section>
    <?php
}