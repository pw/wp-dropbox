<?php
/*
Plugin Name: The dropbox plugin
Plugin URI: http://software.o-o.ro/dropbox-plugin-for-wordpress/
Description: Dropbox in wordpress. Based on Dropbox Connection (www.individual-it.net/Software.html) which in turn is based on Dropbox Uploader (jaka.kubje.org/)
Version: 0.010
Author: Andrew M
Author URI: http://software.o-o.ro

*/

function tdp_options()
{
    add_options_page("The Dropbox Plugin", "TDP Options", 'activate_plugins', 'dropbox-plugin/tdp_options.php');
}
add_action('admin_menu', 'tdp_options');
add_action( 'admin_init', 'tdp_init' );
add_shortcode('dropbox', 'show_dropbox');


if (isset($_GET['get'] ))
   add_action('init', 'tdp_save');
else
   remove_action('init', 'tdp_save');



function show_dropbox()
 {
require 'tdp_config.php';
 
 require 'tdp_list.php';}




function tdp_save(){
   require 'tdp_config.php';
   require 'tdp_DropboxConnection.php';
   $file_to_get = trim($_GET['get']);

   if (preg_match('(^[a-z0-9]+$)',$_GET['w']))
   {
   	$w=$_GET['w'];
   }
   else
   {
   	echo "impossible w-string";
   	die();
   }   
   
	  
   
	$db_connection = new DropboxConnection($dbemail, $dbpassword);
	
	try {

	   
		$data=$db_connection->getfile($dbdir ."/".$file_to_get,$w);
		
		$filename=explode("/",$file_to_get);
		$filename=$filename[count($filename)-1];
		$filename=str_replace(" "," ",$filename);
                
		header($data['content_type']);
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		echo $data['data'];		die();
		
	} catch(Exception $e) {
        echo '<span style="color: red">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
    }}

function tdp_init(){
	register_setting( 'tdp-opt', 'tdp_consumer_key' );
	register_setting( 'tdp-opt', 'tdp_consumer_secret' );
	register_setting( 'tdp-opt', 'tdp_dir' );
}

?>