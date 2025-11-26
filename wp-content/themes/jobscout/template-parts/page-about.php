<?php
/**
 * Template Name: About Professional Layout
 */
get_header(); ?>

<div class="company-about-page">

    <?php while (have_posts()):
        the_post(); ?>

        <?php
        // Lấy full content và tách bằng DOMDocument
        $content_raw = apply_filters('the_content', get_the_content());
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content_raw);

        // Mảng chứa tất cả elements theo thứ tự (chỉ top-level cho text, nhưng images sẽ lấy tất cả)
        $elements = [];

        // Duyệt toàn bộ node trong body
        $body = $dom->getElementsByTagName('body')->item(0);
        if ($body) {
            foreach ($body->childNodes as $node) {
                if ($node->nodeType === XML_ELEMENT_NODE) {
                    $elements[] = [
                        'type' => $node->nodeName,
                        'html' => $dom->saveHTML($node),
                        'text' => trim($node->textContent)
                    ];
                }
            }
        }

        // Lấy tất cả images từ toàn bộ DOM (không chỉ top-level)
        $all_images = [];
        $imgs = $dom->getElementsByTagName('img');
        foreach ($imgs as $img) {
            $all_images[] = $dom->saveHTML($img);
        }
        ?>

        <!-- ========================================= -->
        <!-- HERO SECTION -->
        <!-- ========================================= -->
        <section class="hero" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
        url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');">

            <div class="hero-overlay">
                <h1>
                    <?php
                    // Tìm đoạn đầu tiên có nội dung làm hero title
                    $hero_title = get_the_title();
                    foreach ($elements as $element) {
                        if ($element['type'] === 'p' && !empty($element['text'])) {
                            $hero_title = $element['text'];
                            break;
                        }
                    }
                    echo $hero_title;
                    ?>
                </h1>
            </div>
        </section>

        <!-- ========================================= -->
        <!-- ABOUT SECTION -->
        <!-- ========================================= -->
        <section class="about">
            <?php
            // Tìm heading thật từ content (h1, h2, h3)
            $about_heading = 'ABOUT US';
            foreach ($elements as $element) {
                if (in_array($element['type'], ['h1', 'h2', 'h3']) && !empty($element['text'])) {
                    $about_heading = $element['text'];
                    break;
                }
            }
            ?>
            <h2><?php echo $about_heading; ?></h2>

            <div class="about-grid">
                <!-- LEFT IMAGE -->
                <div class="about-left">
                    <?php
                    $about_image = !empty($all_images[0]) ? $all_images[0] : '<div class="placeholder-image">About Image</div>';
                    echo $about_image;
                    ?>
                </div>

                <!-- RIGHT TEXT -->
                <div class="about-right">
                    <?php
                    $sub_sections = [];
                    $current_section = [];

                    // Nhóm các đoạn văn thành sections dựa trên heading
                    foreach ($elements as $element) {
                        if (in_array($element['type'], ['h3', 'h4']) && !empty($element['text'])) {
                            if (!empty($current_section)) {
                                $sub_sections[] = $current_section;
                            }
                            $current_section = [
                                'title' => $element['text'],
                                'content' => ''
                            ];
                        } elseif ($element['type'] === 'p' && !empty($element['text']) && !empty($current_section)) {
                            $current_section['content'] = $element['text'];
                            $sub_sections[] = $current_section;
                            $current_section = [];
                        }
                    }

                    // Hiển thị các sub-sections
                    // Hiển thị các sub-sections (chỉ lấy 3 cái đầu tiên)
                    foreach (array_slice($sub_sections, 0, 3) as $section) {
                        if (!empty($section['title']) && !empty($section['content'])) {
                            echo '<h3>' . $section['title'] . '</h3>';
                            echo '<p>' . $section['content'] . '</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- ========================================= -->
        <!-- BUSINESS DESCRIPTION -->
        <!-- ========================================= -->
        <section class="business-desc">
            <?php
            $business_title = '';
            $business_paragraphs = [];
            $business_start_index = -1;

            // Tìm phần business description - lấy tiêu đề và content sau đó
            foreach ($elements as $index => $element) {
                $text_lower = strtolower($element['text']);

                // Tìm tiêu đề business với điều kiện cụ thể hơn để tránh vision
                if (
                    strpos($text_lower, 'management') !== false &&
                    (strpos($text_lower, 'hotels') !== false ||
                        strpos($text_lower, 'restaurant') !== false ||
                        strpos($text_lower, 'banquet') !== false ||
                        strpos($text_lower, 'wedding') !== false) &&
                    in_array($element['type'], ['h3', 'h4', 'p'])
                ) {
                    $business_title = $element['text'];
                    $business_start_index = $index;
                    break;
                }
            }

            // Nếu tìm thấy tiêu đề, thu thập content từ các p tiếp theo
            if (!empty($business_title) && $business_start_index !== -1) {
                $patterns = [
                    '/established since:?\s*(.+)/i',
                    '/head office:?\s*(.+)/i',
                    '/capital:?\s*(.+)/i',
                    '/ceo:?\s*(.+)/i',
                    '/number of employees:?\s*(.+)/i'
                ];

                for ($i = $business_start_index + 1; $i < count($elements); $i++) {
                    $el = $elements[$i];

                    // Dừng nếu gặp heading tiếp theo
                    if (in_array($el['type'], ['h3', 'h4'])) {
                        break;
                    }

                    // Nếu là p, kiểm tra xem có phải company info không
                    if ($el['type'] === 'p' && !empty($el['text'])) {
                        $text = $el['text'];
                        $is_company_info = false;

                        foreach ($patterns as $pattern) {
                            if (preg_match($pattern, $text)) {
                                $is_company_info = true;
                                break;
                            }
                        }

                        if ($is_company_info) {
                            break;
                        }

                        // Thêm vào paragraphs riêng biệt
                        $business_paragraphs[] = $el['text'];
                    }
                }
            }
            ?>

            <?php if (!empty($business_title)): ?>
                <h3 class="section-title-gold"><?php echo $business_title; ?></h3>
                <?php foreach ($business_paragraphs as $para): ?>
                    <?php if (!empty(trim($para))): ?>
                        <p><?php echo trim($para); ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
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
                        $company_details = [];

                        // Tìm các thông tin công ty
                        foreach ($elements as $element) {
                            if ($element['type'] === 'p' && !empty($element['text'])) {
                                $text = $element['text'];
                                // Kiểm tra các pattern thông tin công ty
                                $patterns = [
                                    'Established since' => '/established since:?\s*(.+)/i',
                                    'Head Office' => '/head office:?\s*(.+)/i',
                                    'Capital' => '/capital:?\s*(.+)/i',
                                    'CEO' => '/ceo:?\s*(.+)/i',
                                    'Number of Employees' => '/number of employees:?\s*(.+)/i'
                                ];

                                foreach ($patterns as $label => $pattern) {
                                    if (preg_match($pattern, $text, $matches)) {
                                        $company_details[$label] = trim($matches[1]);
                                        break;
                                    }
                                }
                            }
                        }

                        // Hiển thị company details
                        foreach ($company_details as $label => $value) {
                            echo '<div class="detail-item">';
                            echo '<span class="detail-label">' . $label . '</span>';
                            echo '<span class="detail-value">' . $value . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- RIGHT IMAGE -->
                <div class="info-right">
                    <?php
                    $company_image = !empty($all_images[1]) ? $all_images[1] : '<div class="placeholder-image">Company Image</div>';
                    echo $company_image;
                    ?>
                </div>
            </div>
        </section>

    <?php endwhile; ?>

</div>

<?php get_footer(); ?>