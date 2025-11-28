<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JobScout
 */

?>

<div class="single-post-container"
    style="position: relative;max-width: 1200px;margin: 20px auto;/* padding: 20px 46px; */left: 132px;">

    <!-- Main Content (full width, giống ảnh) -->
    <div class="post-content" style="width: 100%; margin-bottom: 40px;">
        <!-- Custom Header Layout (giống ảnh: image left, title/meta right, share button top right) -->
        <header class="post-header-custom"
            style="display: flex; gap: 20px; margin-bottom: 30px; align-items: flex-start; background: #f9f9f9; padding: 20px; border-radius: 8px; position: relative;">
            <!-- Share Button (top right, giống ảnh) -->
            <div class="share-button" style="position: absolute; top: 10px; right: 10px;">
                <button onclick="sharePost()"
                    style="position: relative;padding: 8px 16px;background: #d4a01700;color: #000000;border: none;border-radius: 4px;cursor: pointer;font-size: 14px;top: 55px;right: 50px;width: 100px;border: 1px solid #000;">SHARE</button>
            </div>

            <!-- Left: Post Thumbnail -->
            <div class="post-thumbnail-left" style="flex: 1; max-width: 200px;">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium', array('style' => 'width: 100%; height: auto; border-radius: 4px;'));
                } else {
                    // Fallback image nếu không có thumbnail
                    echo '<img src="https://via.placeholder.com/200x150?text=No+Image" alt="No Image" style="width: 100%; height: auto; border-radius: 4px;">';
                }
                ?>
            </div>

            <!-- Right: Title, Meta -->
            <div class="post-meta-right" style="flex: 3; display: flex; flex-direction: column;">
                <!-- Title -->
                <h1 style="font-size: 28px; color: #333; margin: 0 0 10px 0; line-height: 1.2;"><?php the_title(); ?>
                </h1>

                <!-- Meta: Posted Date, Category, Location -->
                <div class="post-meta" style="color: #666; font-size: 14px;">
                    <p style="margin: 0 0 5px 0;">Posted: <?php echo get_the_date('M j, Y'); ?></p>
                    <p style="margin: 0;"><?php
                    // Category
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo $categories[0]->name . ' | ';
                    }
                    // Location (giả sử custom field '_job_location' từ Job Manager, hoặc meta tùy chỉnh)
                    $location = get_post_meta(get_the_ID(), '_job_location', true) ?: get_the_title();
                    echo esc_html($location);
                    ?></p>
                </div>
            </div>
        </header>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="content-details-blogs"
                style="background: #f9f9f9; padding: 20px; border-radius: 8px; max-width: 1000px;">
                <?php
                /**
                 * 
                 * @hooked jobscout_entry_header - 10
                 * @hooked jobscout_post_thumbnail - 15
                 */
                do_action('jobscout_before_single_post_entry_content');

                /**
                 * @hooked jobscout_entry_content - 15
                 * @hooked jobscout_entry_footer  - 20
                 */
                do_action('jobscout_single_post_entry_content');
                ?>
            </div>
        </article><!-- #post-<?php the_ID(); ?> -->
    </div>

    <!-- NEWEST BLOG ENTRIES (full width, 2x2 grid, giống ảnh) -->
    <aside class="post-sidebar" style="width: 100%; padding: 20px; border-radius: 8px;">
        <h3 style="font-size: 40px; color: #333; margin-bottom: 20px; padding-bottom: 10px; text-align: center;">NEWEST
            BLOG ENTRIES</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <?php
            // Query 8 recent posts (4 rows x 2 columns, giống ảnh)
            $recent_args = array(
                'post_type' => 'post',
                'posts_per_page' => 8,
                'post_status' => 'publish',
                'post__not_in' => array(get_the_ID()),  // Không bao gồm post hiện tại
            );
            $recent_posts = new WP_Query($recent_args);

            ?>
        </div>
    </aside>
</div>
