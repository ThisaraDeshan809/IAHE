<?php

define( 'ABSPATH', dirname( __DIR__ ) . '/' );

function plugin_dir_path( $file ) {
	return dirname( $file ) . '/';
}

function plugins_url( $path = '', $file = '' ) {
	return 'https://example.invalid/' . ltrim( $path, '/' );
}

function get_option( $name, $default = false ) {
	return $default;
}

function add_action() {
}

function add_shortcode() {
}

function register_activation_hook() {
}

chdir( sys_get_temp_dir() );
require dirname( __DIR__ ) . '/wp-content/plugins/wp-file-manager-pro/file_folder_manager_pro.php';

if (
	! class_exists( 'mk_file_folder_manager' ) ||
	! class_exists( 'wp_file_manager_pro_updates' ) ||
	! class_exists( 'wp_file_manager_common_function' )
) {
	fwrite( STDERR, "WP File Manager Pro bootstrap classes were not loaded.\n" );
	exit( 1 );
}

echo "WP File Manager Pro bootstrap passed outside the plugin working directory.\n";
