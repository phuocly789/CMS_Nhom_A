<?php
/**
 * Creating a custom job search form for homepage
 * FIXED VERSION - Points to correct jobs page
 *
 * @package JobScout
 */

// OVERRIDE: Force the correct jobs page URL instead of using WPJM settings
$jobs_page_url = home_url('/index.php/jobs-2/');

$ed_job_category = get_option( 'job_manager_enable_categories' );  
?>

<div class="job_listings">

  <form class="jobscout_job_filters" method="GET" action="<?php echo esc_url( $jobs_page_url ) ?>">
    <div class="search_jobs">

      <div class="search_keywords">
        <label for="search_keywords"><?php esc_html_e( 'Search for jobs, companies, skills', 'jobscout' ); ?></label>
        <input type="text" id="search_keywords" name="search_keywords" 
               placeholder="<?php esc_attr_e( 'Search for jobs, companies, skills', 'jobscout' ); ?>"
               value="<?php echo isset($_GET['search_keywords']) ? esc_attr($_GET['search_keywords']) : ''; ?>">
      </div>

      <div class="search_location">
        <label for="search_location"><?php esc_html_e( 'Location', 'jobscout' ); ?></label>
        <?php											
        global $wpdb;											
        // FIXED SQL QUERY
        $sql = "SELECT DISTINCT meta_value as location 
                FROM {$wpdb->postmeta} 
                WHERE meta_key = '_job_location' 
                AND meta_value != '' 
                ORDER BY meta_value";
        $data = $wpdb->get_results($sql);
        ?>											
                              
        <select id="search_location" name="search_location">
          <option value=""><?php esc_html_e( 'All Areas', 'jobscout' ); ?></option>											
          <?php foreach ($data as $value) : 
            $selected = (isset($_GET['search_location']) && $_GET['search_location'] == $value->location) ? 'selected' : '';
          ?>											
            <option value="<?php echo esc_attr( $value->location ); ?>" <?php echo $selected; ?>>
              <?php echo esc_html( $value->location ); ?>
            </option>											
          <?php endforeach ?>											
        </select>											          
      </div>											
      
      <?php if( $ed_job_category ){ ?>
          <div class="search_categories custom_search_categories">
            <label for="search_category"><?php esc_html_e( 'Job Category', 'jobscout' ); ?></label>
            <select id="search_category" class="robo-search-category" name="search_category">
              <option value=""><?php _e( 'All Categories', 'jobscout' ); ?></option>
              <?php 
              $job_categories = get_job_listing_categories();
              foreach ( $job_categories as $jobcat ) : 
                $selected = (isset($_GET['search_category']) && $_GET['search_category'] == $jobcat->term_id) ? 'selected' : '';
              ?>
                <option value="<?php echo esc_attr( $jobcat->term_id ); ?>" <?php echo $selected; ?>>
                  <?php echo esc_html( $jobcat->name ); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
      <?php } ?>
      
      <div class="search_submit">
        <input type="submit" value="<?php esc_attr_e( 'Search Jobs', 'jobscout'); ?>" />
      </div>

    </div>
  </form>

</div>