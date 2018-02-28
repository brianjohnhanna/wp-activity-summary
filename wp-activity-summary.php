<?php
/**
 * Plugin Name: WP Activity Summary
 * Plugin URI: https://stboston.com/
 * Description: Send emails with a weekly recap of published posts, add dashboard widget with recently published authors
 * Version: 1.1
 * Author: Brian Hanna, Stirling Technologies
 * Author URI: https://github.com/brianjohnhanna
 * GitHub Plugin URI: brianjohnhanna/wp-activity-summary
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * PSR-4 autoloading
 */
spl_autoload_register(
	function( $class ) {
        
        // project-specific namespace prefix
        $prefix = 'ST\WP_Activity_Summary\\';
        // base directory for the namespace prefix
        $base_dir = __DIR__ . '/src/classes/';
        // does the class use the namespace prefix?
        $len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}
        $relative_class = substr( $class, $len );
        
        $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
        // if the file exists, require it
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

register_deactivation_hook(
    __FILE__, function() {
        wp_clear_scheduled_hook('st_wp_activity_summary_email');
    }
);


require_once __DIR__ . '/src/dashboard-ui.php';
require_once __DIR__ . '/src/cron.php';

\ST\WP_Activity_Summary\DashboardUI\setup();
\ST\WP_Activity_Summary\Cron\setup();