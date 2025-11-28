<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JobScout
 */


get_header(); ?>

<div id="primary" class="content-area" style="width: 100%; padding: 0;">

	<?php
	/**
	 * Before Posts hook
	 */
	do_action('jobscout_before_posts_content');
	?>

	<main id="main" class="site-main">

		<?php
		if (have_posts()) :
		?>
			<article style="display: block; margin-bottom: 0;">
				<!-- Header with background image -->
				<header class="contact-header">
					<?php dynamic_sidebar('news-header'); ?>
				</header>

				<!-- Phần NEWEST BLOG ENTRIES giống 100% ảnh (grid 2 cột, cards nhỏ hơn, ảnh left - content right, bỏ pagination) -->
				<section class="blog-section" style="background:#f8f8f8; padding:40px 0; text-align:center;"> <!-- Giảm padding để nhỏ hơn -->
					<div class="container" style="max-width:1000px; margin:0 auto;"> <!-- Giảm max-width để nhỏ hơn -->
						<h2 style="font-size:28px; color:#333; margin:0 0 30px 0; text-transform:uppercase;">NEWEST BLOG ENTRIES</h2> <!-- Giảm font-size -->

						<div class="blog-grid" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:20px;"> <!-- Giảm gap -->
							<?php
							// Loop qua posts (latest posts từ blog, không cần pagination nên lấy tất cả)
							while (have_posts()) : the_post();
							?>
								<div class="blog-card" style="padding: 15px;background:#fff; border:1px solid #eee; box-shadow:0 2px 8px rgba(0,0,0,0.05); display:flex; align-items:center; gap:15px; overflow:hidden; text-align:left;"> <!-- Flex: ảnh left, content right; Giảm shadow, gap -->
									<?php if (has_post_thumbnail()) : ?>
										<div>
											<img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>" style="width:200px; height:200px; object-fit:cover; flex-shrink:0;"> <!-- Ảnh bên trái, size nhỏ hơn, square -->
										</div>
									<?php else : ?>
										<div style="background:#f0f0f0; width:150px; height:150px; flex-shrink:0;"></div> <!-- Placeholder bên trái, size nhỏ hơn -->
									<?php endif; ?>

									<div style="padding:15px 15px 15px 15px; flex:1;"> <!-- Nội dung bên phải; Giảm padding -->
										<h3 style="font-size:18px; color:#333; margin:0 0 8px 0;"><?php the_title(); ?></h3> <!-- Giảm font-size -->
										<p style="font-size:14px; color:#555; line-height:1.5; margin:0 0 12px 0;"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p> <!-- Giảm words, line-height -->
										<a href="<?php the_permalink(); ?>" style="color:#ff6600; text-decoration:none; font-weight:bold; font-size:14px;">Read More</a> <!-- Giảm font-size -->
									</div>
								</div>
							<?php endwhile; ?>
						</div>

					</div>
				</section>

			</article>
		<?php
		/* Start the Loop */
		// while ( have_posts() ) : the_post();

		/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
		// get_template_part( 'template-parts/content', get_post_format() );

		// endwhile;

		else :

			get_template_part('template-parts/content', 'none');

		endif; ?>

	</main><!-- #main -->

	<?php
	/**
	 * After Posts hook
	 * @hooked jobscout_navigation - 15
	 */
	// do_action( 'jobscout_after_posts_content' );
	?>

</div><!-- #primary -->

<?php
// get_sidebar();
get_footer();
