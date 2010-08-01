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
add_action( 'wp_footer' , 'tdp_link' );
add_shortcode('dropbox', 'show_dropbox');
add_shortcode('dropboxupload', 'tdp_upload');



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



function tdp_upload(){
ini_set('memory_limit', '32M');
if ($_POST) { require 'tdp_config.php';
    require 'tdp_DropboxConnection.php';
   
	
    try {
        // Rename uploaded file to reflect original name
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK)
            throw new Exception('File was not successfully uploaded from your computer.');
       
        $tmpDir = uniqid('/tmp/DropboxUploader-');
        if (!mkdir($tmpDir))
            throw new Exception('Cannot create temporary directory!');
       
        if ($_FILES['file']['name'] === "")
            throw new Exception('File name not supplied by the browser.');
           
        $tmpFile = $tmpDir.'/'.str_replace("/\0", '_', $_FILES['file']['name']);
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $tmpFile))
            throw new Exception('Cannot rename uploaded file!');
       
        // Upload
        $uploader = new DropboxConnection($dbemail, $dbpassword);
        $uploader->upload($tmpFile, $dbdir . "/". $_POST['dest']);
       
        echo '<span style="color: green">File successfully uploaded to your Dropbox!</span>';
    } catch(Exception $e) {
        echo '<span style="color: red">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
    }
   
    // Clean up
    if (isset($tmpFile) && file_exists($tmpFile))
        unlink($tmpFile);
       
    if (isset($tmpDir) && file_exists($tmpDir))
        rmdir($tmpDir);
}
echo'
        <form method="post" action="" enctype="multipart/form-data">
        <dl>
				<dt>Destination directory (optional)</dt><dd><input type="text" name="dest" /> e.g. "dir/subdir", will be created if it doesnt exist</dd>
            <dt>File</dt><dd><input type="file" name="file" /></dd>
            <dd><input type="submit" value="Upload the file to my Dropbox!" /></dd>
        </dl></form>';




}

function tdp_init(){
	register_setting( 'tdp-opt', 'tdp_mail' );
	register_setting( 'tdp-opt', 'tdp_pass' );
	register_setting( 'tdp-opt', 'tdp_dir' );
	register_setting( 'tdp-opt', 'tdp_cred' );
	register_setting( 'tdp-opt', 'tdp_date' );
	register_setting( 'tdp-opt', 'tdp_size' );
}

function tdp_link()
{if(get_option('tdp_cred')!=1){echo'<a href="http://software.o-o.ro" alt="Software, projects and code"> <img src="';bloginfo('wpurl');echo '/wp-content/plugins/dropbox-plugin/cred.jpg"> </a>'; }
}
?>