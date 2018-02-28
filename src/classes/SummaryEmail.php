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
                'after' => 'a week ago',
            ]
        ];
        ob_start();
        $this->inline_styles();

        echo '<p>This is your weekly email updating you on the current status of the blog on ' . get_bloginfo('name') . '.</p>';

        echo '<h3>Recent Posts</h3>';
        echo (new \ST\WP_Activity_Summary\PostsTable())->html([
            'date_query' => $weekly_date_query,
            'posts_per_page' => -1
        ]);
        echo '<h3>Recent Authors</h3>';
        echo (new \ST\WP_Activity_Summary\RecentAuthorsTable([
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