<?php

namespace ST\WP_Activity_Summary;

class RecentAuthorsTable extends Table {

    protected $args;

    public function __construct($params = array()) {
        $defaults = [
            'has_published_posts' => apply_filters('wpas/default_post_types', null),
            'role' => apply_filters('wpas/default_author_roles', null),
            'limit' => -1
        ];
        $this->args = wp_parse_args($params, $defaults);
    }

    public function get_records() {
        $users =  (new \WP_User_Query([
            'role' => $this->args['role'],
            'has_published_posts' =>  $this->args['has_published_posts']
        ]))->get_results();

        foreach ($users as $user) {
            $user->posts = (new \WP_Query([
                'author'      => $user->ID,
                'posts_per_page' => -1
            ]))->get_posts();
        }
        usort($users, [$this, 'sort_by_date']);
        
        if ($this->args['limit'] > 0) {
            return array_slice($users, 0, $this->args['limit']);
        }
        return $users;
    }

    public function sort_by_date($a, $b) {
        if (empty($a->posts) && !empty($b->posts)) return 1;
        if (empty($b->posts) && !empty($a->posts)) return -1;
        $t1 = strtotime($a->posts[0]->post_date);
        $t2 = strtotime($b->posts[0]->post_date);
        return $t2 - $t1;
    }

    public function get_columns() {
        return [
            'name'         => esc_html__( 'Name', 'wp-activity-summary' ),
            'last_published' => esc_html__( 'Last Post Published', 'wp-activity-summary' ),
            'total_posts'         => esc_html__( 'Total Posts Published', 'wp-activity-summary' ),
        ];
    }

    public function column_name($author) {
        return get_the_author_meta('display_name', $author->ID);
    }

    public function column_last_published($author) {
        if (empty($author->posts)) {
            return 'Never';
        }
        return human_time_diff(get_the_time('U', $author->posts[0]->ID), current_time('timestamp')) . ' ago';
    }

    public function column_total_posts($author) {
        return count($author->posts);
    }

    public function column_default($record, $column) {
        return '';
    }
}