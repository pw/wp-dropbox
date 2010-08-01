<?php
/*
    This plugin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Dropbox Connection; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
    
    This Software is based on Dropbox Uploader version 1.1.3 written by Jaka Jancar
    [jaka@kubje.org] [http://jaka.kubje.org/] and Dropbox Connection v0.4 www.individual-it.net/Software.html
     
*/
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
		
		header($data['content_type']);
		header("Content-Disposition: attachment; filename=".$filename);
		echo $data['data'];		
		
	} catch(Exception $e) {
        echo '<span style="color: red">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
    }}

?>