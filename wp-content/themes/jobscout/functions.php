<?php
/**
 * JobScout functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JobScout
 */

$jobscout_theme_data = wp_get_theme();
if( ! defined( 'JOBSCOUT_THEME_VERSION' ) ) define ( 'JOBSCOUT_THEME_VERSION', $jobscout_theme_data->get( 'Version' ) );
if( ! defined( 'JOBSCOUT_THEME_NAME' ) ) define( 'JOBSCOUT_THEME_NAME', $jobscout_theme_data->get( 'Name' ) );

/**
 * Implement Local Font Method functions.
 */
require get_template_directory() . '/inc/class-webfont-loader.php';

/**
 * Custom Functions.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Standalone Functions.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Template Functions.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom functions for selective refresh.
 */
require get_template_directory() . '/inc/partials.php';

if( jobscout_is_rara_theme_companion_activated() ) :
	/**
	 * Modify filter hooks of RTC plugin.
	 */
	require get_template_directory() . '/inc/rtc-filters.php';
endif;

/**
 * Custom Controls
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Widgets
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Metabox
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Getting Started
*/
require get_template_directory() . '/inc/dashboard/dashboard.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Add theme compatibility function for woocommerce if active
*/
if( jobscout_is_woocommerce_activated() ){
    require get_template_directory() . '/inc/woocommerce-functions.php';    
}

/**
 * Modify filter hooks of WP Job Manager plugin.
 */
if( jobscout_is_wp_job_manager_activated() ) :
	require get_template_directory() . '/inc/wp-job-manager-filters.php';
endif;

function jobscout_custom_single_job_layout()
{
	// Remove default hooks
	remove_action('jobscout_before_single_job_content', 'jobscout_get_single_job_title', 10);
	remove_action('jobscout_before_single_job_content', 'jobscout_entry_content', 15);

	// Add custom layout hooks
	add_action('jobscout_before_single_job_content', 'jobscout_custom_job_header', 10);
	add_action('jobscout_after_single_job_content', 'jobscout_custom_job_sections', 15);
	add_action('jobscout_after_single_job_content', 'jobscout_other_jobs_section', 20);
}

function jobscout_custom_job_header()
{
	// Include custom header template
	get_template_part('template-parts/job/custom-header');
}