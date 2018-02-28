<?php

namespace ST\WP_Activity_Summary;

class RecentAuthorsTable extends Table {

    protected $args;

    public function __construct($params = array()) {
        $defaults = [
            'limit' => -1,
        ];
        $this->args = array_merge($defaults, $params);
    }

    public function get_records() {
        $users =  get_users();
        foreach ($users as $user) {
            $user->posts = get_posts([
                'author'      => $user->ID,
                'orderby'     => 'date',
                'numberposts' => -1
            ]);
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
        $t1 = !empty($a->posts) ? strtotime($a->posts[0]->post_date) : 1;
        $t2 = !empty($b->posts) ? strtotime($b->posts[0]->post_date) : 1;
        return $t1 - $t2;
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