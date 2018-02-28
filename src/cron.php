<?php

namespace ST_Activity_Summary\Cron;

function setup() {
    add_action(
        'plugins_loaded', function() {
            add_action( 'init',  __NAMESPACE__ . '\add_schedule' );
            add_action( 'st_activity_summary_email', __NAMESPACE__ . '\process_email' );
            add_filter( 'cron_schedules', __NAMESPACE__ . '\custom_schedule' );
        }
    );
}

function add_schedule() {
    if (! wp_next_scheduled ( 'st_activity_summary_email' )) {
        wp_schedule_event(
            strtotime('monday 9am'), 
            'weekly', 
            'st_activity_summary_email'
        );
    }
}

function process_email() {
    wp_mail(
        'brian@stboston.com',
        'Weekly Activity Summary for ' . date('M j, Y'),
        new \ST_Activity_Summary\SummaryEmail(),
        array('Content-Type: text/html; charset=UTF-8')
    );
}

function custom_schedule($schedules) {
    $schedules['weekly'] = array(
		'display' => __( 'Once weekly', 'textdomain' ),
		'interval' => 604800,
	);
	return $schedules;
}