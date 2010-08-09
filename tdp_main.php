<?php
/*
Plugin Name: The dropbox plugin
Plugin URI: http://software.o-o.ro/dropbox-plugin-for-wordpress/
Description: Dropbox in wordpress. Based on Dropbox Connection (www.individual-it.net/Software.html) which in turn is based on Dropbox Uploader (jaka.kubje.org/)
Version: 0.010
Author: Andrew M
Author URI: http://software.o-o.ro

*/

add_action('admin_menu', 'wpdp_options');

function wpdp_options()
{
    add_options_page("WP-Dropbox", "WP-Dropbox", 'manage_options', 'wp-dropbox/tdp_options.php');
}

add_action( 'admin_init', 'wpdp_init' );

function wpdp_init()
{
  register_setting('wpdp_options', 'wpdp_dir');
}

//add_shortcode('dropbox', 'show_dropbox');

?>