<?php
/**
 * Template Name: About Professional Layout
 */
get_header(); ?>

<div class="company-about-page">

    <?php while (have_posts()):
        the_post(); ?>

        <?php
        // Lấy full content
        $content_raw = apply_filters('the_content', get_the_content());

        // Tách nội dung bằng DOMDocument
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content_raw);

        // Mảng chứa ảnh & <p>
        $imgs = [];
        $paras = [];

        // Duyệt toàn bộ node để tách chính xác theo thứ tự
        foreach ($dom->getElementsByTagName('*') as $node) {

            // Ảnh
            if ($node->nodeName === 'img') {
                $imgs[] = $dom->saveHTML($node);
            }

            // Paragraph <p>
            if ($node->nodeName === 'p') {
                $txt = trim($node->textContent);
                if ($txt !== '') {
                    $paras[] = $txt;
                }
            }
        }
        ?>

        <!-- ========================================= -->
        <!-- HERO SECTION -->
        <!-- ========================================= -->
        <section class="hero" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
        url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');">

            <div class="hero-overlay">
                <h1><?php echo isset($paras[0]) ? $paras[0] : get_the_title(); ?></h1>
            </div>
        </section>


        <!-- ========================================= -->
        <!-- ABOUT SECTION -->
        <!-- ========================================= -->
        <section class="about">
            <h2>ABOUT US</h2>

            <div class="about-grid">

                <!-- LEFT IMAGE -->
                <div class="about-left">
                    <?php echo isset($imgs[0]) ? $imgs[0] : '<div class="placeholder-image">About Image</div>'; ?>
                </div>

                <!-- RIGHT TEXT -->
                <div class="about-right">
                    <?php if (isset($paras[1])): ?>
                        <h3>Our Vision</h3>
                        <p><?php echo $paras[1]; ?></p>
                    <?php endif; ?>

                    <?php if (isset($paras[2])): ?>
                        <h3>Our Mission</h3>
                        <p><?php echo $paras[2]; ?></p>
                    <?php endif; ?>

                    <?php if (isset($paras[3])): ?>
                        <h3>Our Core Value</h3>
                        <p><?php echo $paras[3]; ?></p>
                    <?php endif; ?>
                </div>

            </div>
        </section>
        <!-- ========================================= -->
        <!-- BUSINESS DESCRIPTION -->
        <!-- ========================================= -->
        <section class="business-desc">

            <?php
            // MỤC TIÊU:
            // Tìm heading (H3 thật hoặc đoạn văn được coi như H3)
        
            $business_title = '';
            $business_desc = '';

            // 1️⃣ Lấy H3 thật từ DOM
            $h3_nodes = $dom->getElementsByTagName('h3');
            if ($h3_nodes->length > 0) {
                foreach ($h3_nodes as $h3) {
                    $txt = trim($h3->textContent);
                    if ($txt !== '') {
                        $business_title = $txt;
                        break;
                    }
                }
            }

            // 2️⃣ Nếu không có thẻ <h3>, tự coi đoạn văn chứa keywords là tiêu đề
            if (empty($business_title)) {
                foreach ($paras as $p) {
                    if (
                        stripos($p, 'hotels') !== false ||
                        stripos($p, 'restaurants') !== false ||
                        stripos($p, 'banquets') !== false ||
                        stripos($p, 'weddings') !== false
                    ) {
                        $business_title = $p; // gán chính dòng này làm H3
                        break;
                    }
                }
            }

            // 3️⃣ Paragraph mô tả Business
            foreach ($paras as $p) {
                if (
                    stripos($p, 'hotels') !== false ||
                    stripos($p, 'restaurants') !== false ||
                    stripos($p, 'business') !== false
                ) {
                    $business_desc = $p;
                    break;
                }
            }
            ?>

            <?php if (!empty($business_title)): ?>
                <h3 class="section-title-gold"><?php echo $business_title; ?></h3>
            <?php endif; ?>

            <?php if (!empty($business_desc)): ?>
                <p><?php echo $business_desc; ?></p>
            <?php endif; ?>

        </section>





        <!-- ========================================= -->
        <!-- COMPANY INFO -->
        <!-- ========================================= -->
        <section class="company-info">
            <div class="info-grid">
                <!-- LEFT TEXT -->
                <div class="info-left">
                    <div class="company-details">
                        <?php
                        // Tách thông tin company từ content
                        $company_data = [
                            'Established since' => '',
                            'Head Office' => '',
                            'Capital' => '',
                            'CEO' => '',
                            'Number of Employees' => ''
                        ];

                        foreach ($paras as $p) {
                            foreach ($company_data as $key => $value) {
                                if (stripos($p, $key) !== false) {
                                    // Lấy giá trị sau label
                                    $cleaned_value = trim(str_ireplace($key, '', $p));
                                    $company_data[$key] = $cleaned_value;
                                    break;
                                }
                            }
                        }
                        ?>

                        <?php foreach ($company_data as $label => $value): ?>
                            <?php if (!empty($value)): ?>
                                <div class="detail-item">
                                    <span class="detail-label"><?php echo $label; ?></span>
                                    <span class="detail-value"><?php echo $value; ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- RIGHT IMAGE -->
                <div class="info-right">
                    <?php echo isset($imgs[1]) ? $imgs[1] : '<div class="placeholder-image">Company Image</div>'; ?>
                </div>
            </div>
        </section>

    <?php endwhile; ?>

</div>

<?php get_footer(); ?>