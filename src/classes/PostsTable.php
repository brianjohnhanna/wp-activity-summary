<?php

namespace ST_Activity_Summary;

class PostsTable extends Table {
    public function get_records() {
        return (new \WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 10
        ]))->get_posts();
    }

    public function column_title($post) {
        return get_the_title($post) . ' (<a href="' . esc_url(get_the_permalink($post)) . '" target="_blank">View</a>)';
    }

    public function column_author($post) {
        return get_the_author_meta('display_name', $post->post_author);
    }

    public function column_posted_on($post) {
        return human_time_diff(get_the_time('U', $post->ID), current_time('timestamp')) . ' ago';
    }

    public function column_default($record, $column) {
        return 'this is a column';
    }

    public function get_columns() {
        return [
            'title'         => esc_html__( 'Title', 'wp-activity-summary' ),
            'author'        => esc_html__( 'Author', 'wp-activity-summary' ),
            'posted_on'     => esc_html__( 'Posted On', 'wp-activity-summary' ),
        ];
    }
}