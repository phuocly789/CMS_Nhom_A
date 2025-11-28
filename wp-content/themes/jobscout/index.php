<?php
/**
 * The main template file
 */
get_header(); ?>

<div id="primary" class="content-area" style="width: 100%; padding: 0;">
	<main id="main" class="site-main">

		<?php if (have_posts()) : ?>
			<article style="display: block; margin-bottom: 0;">
				<!-- Header with background image -->
				<header class="contact-header">
					<?php dynamic_sidebar('news-header'); ?>
				</header>

				<!-- JOB SEARCH FORM - FIXED VERSION -->
				<section class="home-job-search" style="background: #fff; padding: 60px 0; text-align: center;">
					<div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
						<h2 style="font-size: 36px; color: #333; margin-bottom: 20px;">Find Your Dream Job</h2>
						<p style="font-size: 18px; color: #666; margin-bottom: 40px;">Search thousands of job opportunities</p>
						
						<!-- FIXED FORM - USING CORRECT JOBS PAGE URL -->
						<div class="job-search-form" style="background: #f8f9fa; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
							<?php
							// FORCE THE CORRECT JOBS PAGE URL
							$jobs_page_url = home_url('/index.php/jobs-2/');
							?>
							
							<form class="jobscout_job_filters" method="GET" action="<?php echo esc_url($jobs_page_url); ?>">
								<div class="search_jobs" style="display: flex; gap: 10px; justify-content: center; align-items: center; flex-wrap: wrap;">

									<div class="search_keywords">
										<input type="text" id="search_keywords" name="search_keywords" 
											   placeholder="Search for jobs, companies, skills" 
											   style="padding: 12px; width: 250px; border: 1px solid #ddd; border-radius: 4px;">
									</div>

									<div class="search_location">
										<?php											
										global $wpdb;											
										$sql = "SELECT DISTINCT meta_value as location 
												FROM {$wpdb->postmeta} 
												WHERE meta_key = '_job_location' 
												AND meta_value != '' 
												ORDER BY meta_value";
										$locations = $wpdb->get_results($sql);
										?>
										<select id="search_location" name="search_location" style="padding: 12px; width: 180px; border: 1px solid #ddd; border-radius: 4px;">
											<option value="">All Areas</option>											
											<?php foreach ($locations as $location) : ?>											
												<option value="<?php echo esc_attr($location->location); ?>">
													<?php echo esc_html($location->location); ?>
												</option>											
											<?php endforeach ?>											
										</select>
									</div>
									
									<div class="search_submit">
										<input type="submit" value="Search Jobs" 
											   style="padding: 12px 24px; background: #ff6600; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
									</div>

								</div>
							</form>
							
							<!-- Debug info -->
							<div style="margin-top: 15px; font-size: 12px; color: #999;">
								<strong>Form action:</strong> <?php echo esc_url($jobs_page_url); ?>
							</div>
							
							<!-- Direct link -->
							<div style="margin-top: 20px;">
								<a href="<?php echo esc_url($jobs_page_url); ?>" 
								   style="color: #666; text-decoration: none;">
								   Or browse all jobs →
								</a>
							</div>
						</div>
					</div>
				</section>

				<!-- Blog Section -->
				<section class="blog-section" style="background:#f8f8f8; padding:40px 0; text-align:center;">
					<div class="container" style="max-width:1000px; margin:0 auto;">
						<h2 style="font-size:28px; color:#333; margin:0 0 30px 0; text-transform:uppercase;">NEWEST BLOG ENTRIES</h2>

						<div class="blog-grid" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:20px;">
							<?php while (have_posts()) : the_post(); ?>
								<div class="blog-card" style="padding: 15px;background:#fff; border:1px solid #eee; box-shadow:0 2px 8px rgba(0,0,0,0.05); display:flex; align-items:center; gap:15px; overflow:hidden; text-align:left;">
									<?php if (has_post_thumbnail()) : ?>
										<div>
											<img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" style="width:200px; height:200px; object-fit:cover; flex-shrink:0;">
										</div>
									<?php else : ?>
										<div style="background:#f0f0f0; width:150px; height:150px; flex-shrink:0;"></div>
									<?php endif; ?>

									<div style="padding:15px 15px 15px 15px; flex:1;">
										<h3 style="font-size:18px; color:#333; margin:0 0 8px 0;"><?php the_title(); ?></h3>
										<p style="font-size:14px; color:#555; line-height:1.5; margin:0 0 12px 0;"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
										<a href="<?php the_permalink(); ?>" style="color:#ff6600; text-decoration:none; font-weight:bold; font-size:14px;">Read More</a>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				</section>

			</article>
		<?php else :
			get_template_part('template-parts/content', 'none');
		endif; ?>

	</main>
</div>

<?php get_footer();