<?php

namespace ST\WP_Activity_Summary\DashboardUI;

function setup() {
    add_action(
        'plugins_loaded', function() {
            add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\add_dashboard_widget' );
        }
    );
}

function add_dashboard_widget() {
    wp_add_dashboard_widget(
        'ST\WP_Activity_Summary',         // Widget slug.
        'Activity Summary',         // Title.
        __NAMESPACE__ . '\render_dashboard_widget' // Display function.
    );
}

function render_dashboard_widget() {
    echo '<h3><u>Recent Posts</u></h3>';
    echo (new \ST\WP_Activity_Summary\PostsTable())->html();
    echo '<br />';
    echo '<h3><u>Recent Authors</u></h3>';
    echo (new \ST\WP_Activity_Summary\RecentAuthorsTable())->html();
}