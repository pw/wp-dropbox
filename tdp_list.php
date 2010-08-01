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

   require 'tdp_DropboxConnection.php';

  $shdt=get_option('tdp_date');
  $shsz=get_option('tdp_size');
   $sub_dir = trim($_GET['sub_dir']);
   //remove "/" at the end of $sub_dir if any 
   while (preg_match('/\/$/', $sub_dir))
   {
   	$sub_dir=substr($sub_dir,0,mb_strlen($sub_dir)-1);
   }
   echo "Viewing: " . $sub_dir;
   
   
    try {
 
 		$db_connection = new DropboxConnection($dbemail, $dbpassword);
 		$directories=$db_connection->getdirs($dbdir."/".$sub_dir); 
 		
 		if (isset($sub_dir))
 		{ 
 			$parent_dir_array=explode("/",$sub_dir);
 			
 			for($x=0;$x<count($parent_dir_array)-1;$x++){
  				  $parent_dir.=$parent_dir_array[$x] . "/"; 
			}
 			 
			echo "<a href='";the_permalink();if(strstr(get_permalink(),'?'))echo'&';else echo'?';echo'sub_dir='.$parent_dir."'>../</a><br><br>";
		}
 		
		foreach ($directories as $directory)
		{
			echo "DIR - <a href='";the_permalink();if(strstr(get_permalink(),'?'))echo'&';else echo'?';echo'sub_dir='.$sub_dir . "/".$directory."'>" . $directory . "</a><br>";
		}
		
 		$files=$db_connection->getfiles($dbdir."/".$sub_dir); 
 		
		foreach ($files as $file)
		{
			echo "<a href='";the_permalink();if(strstr(get_permalink(),'?'))echo'&';else echo'?';echo'get='.$sub_dir . "/". $file[0] . "&w=" . $file[1] . "'>".$file[0]."</a>";if($shdt!=1)echo" (".$file[3].")";if($shsz!=1)echo" - ".$file[2];echo"<br>";
		}      
      
    } catch(Exception $e) {
        echo '<span style="color: red">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
    }
   


?>