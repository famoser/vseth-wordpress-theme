<?php

/*
 * Add a portfolio custom post type. -> organisations
 */
add_action('init', 'create_custom_post_type_organisations');
function create_custom_post_type_organisations()
{
    $labels = [
        'name' => 'Organisationen',
        'singular_name' => 'Organisation'
    ];
    $args = [
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 20,
        'show_in_nav_menus' => true,
        'supports' => ['title', 'editor', 'thumbnail']
    ];
    register_post_type('organisation', $args);
    register_taxonomy("organisation-categories",
        ["organisation"],
        [
            "hierarchical" => true,
            "rewrite" => ['slug' => 'categories', 'with_front' => false]
        ]
    );
}

function order_custom_post_type_organisations($query)
{
    if ($query->get('post_type') == 'organisation')
    {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
}

add_action('pre_get_posts', 'order_custom_post_type_organisations');

// for polyland translations
add_filter('pll_get_post_types', 'pll_get_post_types_organisations');
function pll_get_post_types_organisations($types)
{
    return array_merge($types, array('organisation' => 'organisation'));
}


// add meta information to posts
add_filter('rwmb_meta_boxes', 'organisation_register_meta_boxes');
function organisation_register_meta_boxes($meta_boxes)
{
    $prefix = 'rw_';

    // 1st meta box
    $meta_boxes[] = [
        'id' => 'info',
        'title' => 'Kontaktinformationen',
        'pages' => ['organisation'],
        'context' => 'normal',
        'priority' => 'high',
        'autosave' => true,
        'fields' => [
            [
                'name' => 'Website',
                'desc' => 'Format: https://vseth.ethz.ch',
                'id' => $prefix . 'website',
                'type' => 'text'
            ],
            [
                'name' => 'Email',
                'desc' => 'Format: receiver@vseth.ethz.ch',
                'id' => $prefix . 'email',
                'type' => 'text'
            ],
            [
                'name' => 'Facebook',
                'desc' => 'Format: https://facebook.com/vseth',
                'id' => $prefix . 'facebook',
                'type' => 'text'
            ],
            [
                'name' => 'Instagram',
                'desc' => 'Format: https://instagram.com/vseth',
                'id' => $prefix . 'instagram',
                'type' => 'text'
            ],
        ]
    ];

    return $meta_boxes;
}

add_filter('template_include', 'portfolio_page_template', 99);
function portfolio_page_template($template)
{
    if (is_singular('organisation')) {
        var_dump("found");
        $new_template = locate_template(array('portfolio-page-template.php'));
        if ('' != $new_template) {
            return $new_template;
        }
    }
    return $template;
}