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