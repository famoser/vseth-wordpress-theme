<?php

// function that runs when shortcode is called
function shortcode_organisations()
{
    // add styles & javascript
    wp_enqueue_style('organisations-self-style', plugins_url('css/style.css', __FILE__));

    wp_enqueue_script('jquery');
    wp_enqueue_script('organisations-isotope-script', plugins_url('js/isotope.pkgd.min.js', __FILE__), [], '3.0.6');
    wp_enqueue_script('organisations-self-script', plugins_url('js/script.js', __FILE__));

    $isoposts = new WP_Query([
        'post_type' => "organisation",
        'paged' => false,
        'orderby' => "title",
        'posts_per_page' => -1
    ]);

    $meta_icons = [
        "rw_website" => plugins_url('icons/external-link-regular.svg', __FILE__),
        "rw_email" => plugins_url('icons/envelope-regular.svg', __FILE__),
        "rw_facebook" => plugins_url('icons/facebook-f-brands.svg', __FILE__),
        "rw_instagram" => plugins_url('icons/instagram-brands.svg', __FILE__),
    ];

    $terms = get_terms('organisation-categories');

    ob_start();

    ?>
    <ul class="organisation-filters">
        <li><a href="#" data-filter="*"><?= __('See all', 'organisations-see-all') ?></a></li>
        <?php foreach ($terms as $term) { ?>
            <li><a href="#<?= $term->slug ?>" data-filter=".<?= $term->slug ?>"><?= $term->name ?></a></li>
        <?php } ?>
    </ul>
    <div class="organisations-grid">
        <?php while ($isoposts->have_posts()) :
            $isoposts->the_post();

            // get meta
            $meta = [];
            foreach ($meta_icons as $meta_key => $meta_icon) {
                $meta_value = rwmb_meta($meta_key);
                if ($meta_value != null && trim($meta_value) != "") {
                    $meta[$meta_key] = $meta_value;
                }
            }

            ?>
            <div class="organisations-grid-item <?php
            $terms = get_the_terms($isoposts->post->ID, "organisation-categories");
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    echo $term->slug . ' ';
                }
            }
            $hasImage = get_the_post_thumbnail() != '';
            if (!$hasImage) {
                echo " no-image";
            }
            ?>">
                <?php
                if ($hasImage) { ?>
                    <img class="organisation-logo"
                         alt="Logo of <?= the_title() ?>"
                         src="<?= wp_get_attachment_url(get_post_thumbnail_id()) ?>">
                <?php } ?>
                <h4 class="organisation-title"><?= the_title(); ?></h4>
                <p class="organisation-content"><?= the_content(); ?></p>
                <p class="organisation-meta">
                    <?php foreach ($meta as $meta_key => $meta_value) { ?>
                        <a href="<?= $meta_key == "rw_email" ? "mailto:" . $meta_value : $meta_value ?>"
                           target="_blank">
                            <img alt="<?= $meta_key ?> of <?= the_title() ?>"
                                 src="<?= $meta_icons[$meta_key] ?>">
                        </a>
                    <?php } ?>
                </p>
            </div>
        <?php endwhile; ?>
    </div>

    <?php
    // Reset the post loop.
    wp_reset_postdata();

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

add_shortcode('organisations', 'shortcode_organisations');