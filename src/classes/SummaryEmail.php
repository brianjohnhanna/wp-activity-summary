<?php

namespace ST\WP_Activity_Summary;

class SummaryEmail {

    public function __toString() {
        return $this->html();
    }

    public function html() 
    {
        $weekly_date_query = [
            [
                'after' => '-1 week',
                'inclusive' => true
            ]
        ];
        ob_start();
        $this->inline_styles();

        echo '<p>This is your weekly email updating you on the current status of the blog on ' . get_bloginfo('name') . '.</p>';

        echo '<h3>Posts Published Since ' . date('m/d/Y', strtotime('-1 week')) . '</h3>';
        echo (new \ST\WP_Activity_Summary\PostsTable([
            'date_query' => $weekly_date_query,
            'posts_per_page' => -1
        ]))->html();

        echo '<h3>All Authors Ordered by Last Post</h3>';
        echo (new \ST\WP_Activity_Summary\RecentAuthorsTable(array(), [
            'date_query' => $weekly_date_query
        ]))->html();

        echo '<p>For questions on this email, please contact Brian Hanna at <a href="mailto:brian@stboston.com">brian@stboston.com</a>.</p>';
        return ob_get_clean();
    }

    public function inline_styles() {
        ?>
        <style>
            table {
                border-collapse: collapse;
            }

            table thead {
                background-color: #eee;
            }

            table th {
                text-align: left;
            }

            table th, table td {
                padding: 10px 15px;
                border: 1px solid #ddd;
            }
        </style>
        <?php
    }

}