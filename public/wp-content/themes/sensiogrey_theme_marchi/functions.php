<?php

// --- Custom rewrite for clean shop category URLs ---
add_action('init', function() {
    // Agnostic rewrite rule: matches /{lang}/{shop_slug}/{category}/
    add_rewrite_rule(
        '^([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?pagename=$matches[2]&shop_category=$matches[3]',
        'top'
    );
});

add_filter('query_vars', function($vars) {
    $vars[] = 'shop_category';
    return $vars;
});

add_action('template_redirect', function() {
    if (get_query_var('shop_category')) {
        // Optionally, you can set a global or do other logic here
        // so your template or filter block can access the category
        // e.g., set a global variable, or use get_query_var('shop_category') in your template
    }
});
