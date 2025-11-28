<?php

/**
 * JobScout functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JobScout
 */

$jobscout_theme_data = wp_get_theme();
if (!defined('JOBSCOUT_THEME_VERSION'))
	define('JOBSCOUT_THEME_VERSION', $jobscout_theme_data->get('Version'));
if (!defined('JOBSCOUT_THEME_NAME'))
	define('JOBSCOUT_THEME_NAME', $jobscout_theme_data->get('Name'));

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

if (jobscout_is_rara_theme_companion_activated()):
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
if (jobscout_is_woocommerce_activated()) {
	require get_template_directory() . '/inc/woocommerce-functions.php';
}

/**
 * Modify filter hooks of WP Job Manager plugin.
 */
if (jobscout_is_wp_job_manager_activated()):
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

// Enqueue JS cho filter + load more
function jobscout_enqueue_jobs_scripts()
{
	if (is_page(10)) {  // Chỉ ở trang ID=10
		wp_enqueue_script('jquery');
		wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                // Filter change: Reload toàn bộ grid với filter mới
                $("#jobs-filter").on("change", function() {
                    var filter = $(this).val();
                    $("#jobs-grid").empty();  // Xóa grid cũ
                    loadJobs(1, filter, true);  // Load page 1 với filter mới
                });

                // Load more click
                $(document).on("click", "#load-more-jobs", function() {
                    var button = $(this);
                    var page = parseInt(button.data("page")) + 1;
                    var filter = button.data("filter");
                    loadJobs(page, filter, false);
                });

                // Function chung để load jobs (AJAX)
                function loadJobs(page, filter, isFilterChange) {
                    var button = $("#load-more-jobs");
                    button.text("Loading...").prop("disabled", true);

                    $.ajax({
                        url: "' . admin_url('admin-ajax.php') . '",
                        type: "POST",
                        data: {
                            action: "load_more_jobs",
                            page: page,
                            filter: filter,
                        },
                        success: function(response) {
                            if (response.html) {
                                if (isFilterChange) {
                                    $("#jobs-grid").html(response.html);  // Thay toàn bộ nếu filter
                                    button.data("max-pages", response.max_pages);
                                    button.data("filter", filter);
                                    button.data("page", 1);
                                    if (response.max_pages > 1) button.show();
                                    else button.hide();
                                } else {
                                    $("#jobs-grid").append(response.html);  // Append nếu load more
                                    button.data("page", page);
                                }
                                button.text("LOAD MORE JOBS").prop("disabled", false);
                                if (page >= response.max_pages) button.hide();
                            } else {
                                button.hide();
                            }
                        },
                        error: function() {
                            button.text("Error").prop("disabled", true);
                        }
                    });
                }
            });
        ');
	}
}
add_action('wp_enqueue_scripts', 'jobscout_enqueue_jobs_scripts');

// PHP handler AJAX (cập nhật với filter)
function load_more_jobs()
{
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'latest';

	$args = array(
		'post_type' => 'job_listing',
		'posts_per_page' => 6,
		'post_status' => 'publish',
		'paged' => $page,
	);

	// Áp dụng filter
	if ($filter == 'latest') {
		$args['orderby'] = 'date';
		$args['order'] = 'DESC';
	} elseif ($filter == 'popular') {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = '_views_count';  // Cần plugin Post Views Counter
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

	$html = '';
	if ($jobs_query->have_posts()):
		while ($jobs_query->have_posts()):
			$jobs_query->the_post();
			$company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : (has_post_thumbnail() ? get_the_post_thumbnail_url() : '');
			$job_title = get_the_title();
			$job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'No location';
			$job_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
			$job_category = (is_array($job_terms)) ? (!empty($job_terms) ? implode(', ', $job_terms) : 'Uncategorized') : 'Uncategorized';
			$job_excerpt = get_the_excerpt() ?: 'No description';

			$html .= '<div class="job-card" style="background:#fff; padding:25px; border:1px solid #eee; box-shadow:0 2px 10px rgba(0,0,0,0.05); display:flex; gap:20px; align-items:start;">';
			$html .= '    <div class="job-logo" style="width:80px; height:80px; background:#f0f0f0; display:flex; align-items:center; justify-content:center;">';
			if ($company_logo)
				$html .= '<img src="' . $company_logo . '" alt="Logo" style="max-width:100%; max-height:100%;">';
			else
				$html .= '<i class="fa fa-briefcase" style="font-size:40px; color:#ccc;"></i>';
			$html .= '    </div>';
			$html .= '    <div class="job-content" style="flex:1;">';
			$html .= '        <h3 style="font-size:22px; color:#333; margin:0 0 10px 0;">' . $job_title . '</h3>';
			$html .= '        <p style="font-size:16px; color:#666; margin:0 0 10px 0;">' . $job_category . ' / ' . $job_location . '</p>';
			$html .= '        <p style="font-size:15px; color:#555; line-height:1.6; margin:0;">' . wp_trim_words($job_excerpt, 30) . '</p>';
			$html .= '    </div>';
			$html .= '</div>';
		endwhile;
		wp_reset_postdata();
	endif;

	wp_send_json(array(
		'html' => $html,
		'max_pages' => $jobs_query->max_num_pages,
	));

	wp_die();
}
add_action('wp_ajax_load_more_jobs', 'load_more_jobs');
add_action('wp_ajax_nopriv_load_more_jobs', 'load_more_jobs');


// Enqueue JS cho nút "View All Jobs" ở homepage
function jobscout_enqueue_view_all_jobs_script()
{
	if (is_front_page() || is_home()) {
		wp_enqueue_script('jquery');
		wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                $("#view-all-jobs-btn").on("click", function() {
                    var button = $(this);
                    var total = button.data("total");
                    
                    button.text("Loading...").prop("disabled", true);
                    
                    $.ajax({
                        url: "' . admin_url('admin-ajax.php') . '",
                        type: "POST",
                        data: {
                            action: "load_all_jobs",
                        },
                        success: function(response) {
                            if (response.html) {
                                $(".top-jobs-grid").html(response.html);
                                button.hide();
                            } else {
                                button.text("No more jobs").prop("disabled", true);
                            }
                        },
                        error: function() {
                            button.text("Error loading jobs").prop("disabled", true);
                        }
                    });
                });
            });
        ');
	}
}
add_action('wp_enqueue_scripts', 'jobscout_enqueue_view_all_jobs_script');

// AJAX handler để load tất cả jobs
function load_all_jobs()
{
	$args = array(
		'post_type' => 'job_listing',
		'posts_per_page' => -1, // Load tất cả
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$jobs_query = new WP_Query($args);

	$html = '';
	if ($jobs_query->have_posts()):
		while ($jobs_query->have_posts()):
			$jobs_query->the_post();
			$company_logo = function_exists('get_the_company_logo') ? get_the_company_logo() : '';
			$job_location = get_post_meta(get_the_ID(), '_job_location', true) ?: 'Ho Chi Minh City';
			$job_type_terms = wp_get_post_terms(get_the_ID(), 'job_listing_type', array('fields' => 'names'));
			$job_type = (!is_wp_error($job_type_terms) && !empty($job_type_terms)) ? $job_type_terms[0] : 'Fulltime';
			$job_category_terms = wp_get_post_terms(get_the_ID(), 'job_listing_category', array('fields' => 'names'));
			$job_category = (!is_wp_error($job_category_terms) && !empty($job_category_terms)) ? $job_category_terms[0] : 'Category Name';
			$job_excerpt = get_the_excerpt();
			$post_date = get_the_date('M d, Y');

			// Parse excerpt into bullet points
			$bullets = preg_split('/[\r\n]+/', strip_tags($job_excerpt), -1, PREG_SPLIT_NO_EMPTY);
			if (count($bullets) < 3) {
				$bullets = array(
					'Be responsible for the effective operational management of the hotel',
					'Excellent salary bonuses & recognition activities',
					'Foreign language allowance (up to 500USD/month)'
				);
			} else {
				$bullets = array_slice($bullets, 0, 3);
			}

			ob_start();
			?>
			<div class="top-job-card">
				<div class="top-job-card-header">
					<div class="top-job-logo">
						<?php if ($company_logo): ?>
							<img src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
						<?php else: ?>
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
					<?php foreach ($bullets as $bullet): ?>
						<li><?php echo esc_html($bullet); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
			$html .= ob_get_clean();
		endwhile;
		wp_reset_postdata();
	endif;

	wp_send_json(array(
		'html' => $html,
		'total' => $jobs_query->found_posts,
	));

	wp_die();
}
add_action('wp_ajax_load_all_jobs', 'load_all_jobs');
add_action('wp_ajax_nopriv_load_all_jobs', 'load_all_jobs');

// Register custom sidebars cho About (thêm vào functions.php, sau phần Widgets)
function jobscout_register_about_sidebars()
{
	register_sidebar(array(
		'name' => 'About Header',
		'id' => 'about-header',
		'description' => 'Header banner cho trang About (text/hình nền).',
		'before_widget' => '<div class="about-header-widget" style="line-height: 1.0 !important; text-align: center; margin: 0 !important;">',  // Line-height chặt toàn bộ, margin 0
		'after_widget' => '</div>',
		'before_title' => '<h1 style="color: white; text-align: center; margin: 0 !important; line-height: 1.0 !important; font-size: 52px; font-weight: 900; text-shadow: 2px 2px 4px rgba(0,0,0,0.6);">',  // h1 sát, shadow
		'after_title' => '</h1><p class="about-header-p" style="color: white; margin: 0 !important; line-height: 1.0 !important; font-size: 28px; font-weight: 400; text-shadow: 1px 1px 2px rgba(0,0,0,0.6);">',  // p sát, shadow, class để CSS nếu cần
	));
	register_sidebar(array(
		'name' => 'About Image',
		'id' => 'about-image',
		'description' => 'Hình ảnh cho phần Vision/Mission.',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
	));
	register_sidebar(array(
		'name' => 'About Vision Mission',
		'id' => 'about-vision-mission',
		'description' => 'Text Vision và Mission.',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h3 style="color: #333; margin-bottom: 20px;">',
		'after_title' => '</h3><p style="color: #666; line-height: 1.6;">',
	));
	register_sidebar(array(
		'name' => 'About Business',
		'id' => 'about-business',
		'description' => 'Mô tả business (Hotels, etc.).',
		'before_widget' => '<div class="about-business-widget" style="text-align: center; margin-bottom: 20px;">',  // Thêm class
		'after_widget' => '</div>',
		'before_title' => '<h3 class="about-business-title">',  // Class cho h3
		'after_title' => '</h3><p class="about-business-desc">',  // Class cho p
	));
	register_sidebar(array(
		'name' => 'About Team',
		'id' => 'about-team',
		'description' => 'Thông tin team/office (địa chỉ, CEO).',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
	));
	register_sidebar(array(
		'name' => 'About Team Image',
		'id' => 'about-team-image',
		'description' => 'Hình ảnh team (thuyền biển).',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
	));
}
add_action('widgets_init', 'jobscout_register_about_sidebars');

// Thêm chữ "SEARCH JOB" vào nút tìm kiếm banner home
function jobscout_add_search_button_text()
{
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const submitBtn = document.querySelector('.banner-caption .jobscout_job_filters .search_jobs input[type="submit"]');
			if (submitBtn) {
				submitBtn.value = 'SEARCH JOB'; // gán chữ bạn muốn
			}
		});
	</script>
	<?php
}
add_action('wp_footer', 'jobscout_add_search_button_text');

add_filter('job_manager_get_listings_args', function ($args) {
	$args['posts_per_page'] = 6; // Số job hiển thị
	return $args;
});
// Trang detail post: Chỉ hiện breadcrumb và ẩn post navigation
// Override breadcrumb để chỉ hiện trên single post
function jobscout_conditional_breadcrumbs_bar()
{
	if (is_single() && 'post' === get_post_type()) {  // Chỉ hiện trên single post, không phải page hay custom post type
		jobscout_breadcrumbs_bar();  // Gọi function breadcrumb gốc
	}
}

// Chỉ thực hiện khi theme đã được setup
function jobscout_modify_breadcrumbs()
{
	remove_action('jobscout_after_header', 'jobscout_breadcrumbs_bar', 30);  // Xóa hook cũ
	add_action('jobscout_after_header', 'jobscout_conditional_breadcrumbs_bar', 30);  // Add hook mới
}
add_action('after_setup_theme', 'jobscout_modify_breadcrumbs');
// Ẩn post navigation trên single post
function hide_post_navigation_on_single()
{
	if (is_single()) {
		remove_action('jobscout_after_post_content', 'jobscout_navigation', 10);  // Xóa hook navigation (dựa trên template-functions.php)
	}
}
add_action('wp', 'hide_post_navigation_on_single');

function remove_video_from_content($content)
{
	// Xoá các iframe (video YouTube / Vimeo embed)
	$content = preg_replace('/<iframe.*?<\/iframe>/is', '', $content);

	// Xoá thẻ <video> nếu có
	$content = preg_replace('/<video.*?<\/video>/is', '', $content);

	// Xoá URL YouTube dạng plain text tự embed
	$content = preg_replace('/https?:\/\/(www\.)?(youtube\.com|youtu\.be)\/[^\s]+/i', '', $content);

	return $content;
}
