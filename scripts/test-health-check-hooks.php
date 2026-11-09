<?php

define( 'ABSPATH', __DIR__ );

$registered_actions = array();

function add_action( $hook, $callback, $priority = 10 ) {
	global $registered_actions;
	$registered_actions[] = array( $hook, $callback, $priority );
}

function add_filter() {
}

function plugin_basename( $file ) {
	return basename( $file );
}

define( 'HEALTH_CHECK_PLUGIN_FILE', __FILE__ );
require dirname( __DIR__ ) . '/wp-content/plugins/health-check/HealthCheck/class-health-check.php';

new Health_Check();

foreach ( $registered_actions as $action ) {
	if ( 'init' === $action[0] && is_array( $action[1] ) && 'load_i18n' === $action[1][1] && 0 === $action[2] ) {
		echo "Health Check translation hook timing passed.\n";
		exit( 0 );
	}
}

fwrite( STDERR, "Health Check translation loader is not registered on init at priority 0.\n" );
exit( 1 );
