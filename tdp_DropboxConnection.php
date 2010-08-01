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

class DropboxConnection {
    protected $email;
    protected $password;
    protected $caCertSourceType = self::CACERT_SOURCE_SYSTEM;
    const CACERT_SOURCE_SYSTEM = 0;
    const CACERT_SOURCE_FILE = 1;
    const CACERT_SOURCE_DIR = 2;
    protected $caCertSource;
    protected $loggedIn = false;
    protected $cookies = array();
    
    /**
     * Constructor
     *
     * @param string $email
     * @param string|null $password
     */
    public function __construct($email, $password) {
        // Check requirements
        if (!extension_loaded('curl'))
            throw new Exception('DropboxUploader requires the cURL extension.');
        
        $this->email = $email;
        $this->password = $password;
    }
    
    
    public function setCaCertificateFile($file)
    {
        $this->caCertSourceType = self::CACERT_SOURCE_FILE;
        $this->caCertSource = $file;
    }
    
    public function setCaCertificateDir($dir)
    {
        $this->caCertSourceType = self::CACERT_SOURCE_DIR;
        $this->caCertSource = $dir;
    }
    
    public function upload($filename, $remoteDir='/') {
        if (!file_exists($filename) or !is_file($filename) or !is_readable($filename))
            throw new Exception("File '$filename' does not exist or is not readable.");
        
        if (!is_string($remoteDir))
            throw new Exception("Remote directory must be a string, is ".gettype($remoteDir)." instead.");

        if (preg_match("/.+\.\..+/",$remoteDir))
            throw new Exception("Remote directory is impossible");
        

        
        if (!$this->loggedIn)
            $this->login();
        
        $data = $this->request('https://www.dropbox.com/home');
        $token = $this->extractToken($data, 'https://dl-web.dropbox.com/upload');
        
        $data = $this->request('https://dl-web.dropbox.com/upload', true, array('plain'=>'yes', 'file'=>'@'.$filename, 'dest'=>$remoteDir, 't'=>$token));
        if (strpos($data, 'HTTP/1.1 302 FOUND') === false)
            throw new Exception('Upload failed!');
    }
   
   //return all sub-directories in the $remoteDir
    public function getdirs($remoteDir='/') {


		  $directory_names=array();

		 if (preg_match("/\.\./",$remoteDir))
            throw new Exception("Remote directory is impossible");


		 if (preg_match("/.+\.\..+/",$remoteDir))
            throw new Exception("Remote directory is impossible");


        if (!is_string($remoteDir))
            throw new Exception("Remote directory must be a string, is ".gettype($remoteDir)." instead.");
        
        if (!$this->loggedIn)
            $this->login();
        $remoteDir=str_replace(" ","%20",$remoteDir);
        $data = $this->request('https://www.dropbox.com/browse_plain/'.$remoteDir.'?no_js=true');

        preg_match_all ( '/<div.*details-filename.*>(.*?)<\/div>/', $data, $file_array );
         
		  foreach ( $file_array[0] as  $file_name )
  			{
  			 $file_name = explode('</a>', $file_name);
  			 $file_name = spliti('<a href="\/.*true">', $file_name[0]);
  		  	
  		  	 if ($file_name[1]!='')
  			 array_push($directory_names, $file_name[1]);
 
			}  
				
			return $directory_names;
    }

   //return all files in the $remoteDir
    public function getfiles($remoteDir='/') {
		$shdt=get_option('tdp_date');
		$shsz=get_option('tdp_size');
		 if (preg_match("/.+\.\..+/",$remoteDir))
            throw new Exception("Remote directory is impossible");

		  $file_names=array();
        if (!is_string($remoteDir))
            throw new Exception("Remote directory must be a string, is ".gettype($remoteDir)." instead.");
        
        if (!$this->loggedIn)
            $this->login();
        $remoteDir=str_replace(" ","%20",$remoteDir);
        $data = $this->request('https://www.dropbox.com/browse_plain/'.$remoteDir.'?no_js=true');
        

        
        preg_match_all ( '/<div.*details-filename.*>(.*?)<\/div>/', $data, $file_array );
       if($shsz!=1) preg_match_all ( '/<div.*details-size.*>(.*?)<\/div>/', $data, $file_array1 );
	    if($shdt!=1) preg_match_all ( '/<div.*details-modified.*>(.*?)<\/div>/', $data, $file_array2 );
		$somevaiable=0;
		  foreach ( $file_array[0] as  $file_name )
  			{
  			 $href = explode('</a>', $file_name);
  			 $file_name = spliti('<a href=".*dl.*">', $href[0]);

  		
  			  if ($file_name[1]!='')
  			  {
	  			  $href = spliti('<a href=".*w=', $href[0]);
	  			  $href = explode('">', $href[1]);
	  			  array_push($file_names, array($file_name[1],$href[0],$file_array1[1][$somevariable],$file_array2[1][$somevariable]));
  			  }
				$somevariable++;
			}  
		
         			
			return $file_names;
    }
       
    //read the file-data and return it   
    public function getfile($remoteFile='/',$w) {

		  $file_names=array();

		 if (preg_match("/.+\.\..+/",$remoteFile))
            throw new Exception("Remote directory is impossible");  
        if (!is_string($remoteFile))
            throw new Exception("Remote directory must be a string, is ".gettype($remoteDir)." instead.");
        if (!preg_match('(^[a-z0-9]+$)',$w))
            throw new Exception("impossible w-string");

                
        
        if (!$this->loggedIn)
            $this->login();
        $remoteFile=str_replace(" ","%20",$remoteFile);
        $data = $this->request('https://dl-web.dropbox.com/get/'.$remoteFile.'?w='.$w);
        preg_match ( '/Content-Type: .+\/.+/', $data, $content_type );        

		          
        
        $data=substr(stristr($data, "\r\n\r\n"),4);
        return array("data"=>$data,"content_type"=>$content_type[0]);
       
    }
            
   
    
    protected function login() {
        $data = $this->request('https://www.dropbox.com/login');
        $token = $this->extractToken($data, '/login');
        
        $data = $this->request('https://www.dropbox.com/login', true, array('login_email'=>$this->email, 'login_password'=>$this->password, 't'=>$token));
        
        if (stripos($data, 'location: /home') === false)
            throw new Exception('Login unsuccessful.');
        
        $this->loggedIn = true;
    }

    protected function request($url, $post=false, $postData=array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        switch ($this->caCertSourceType) {
            case self::CACERT_SOURCE_FILE:
                curl_setopt($ch, CURLOPT_CAINFO, $this->caCertSource);
                break;
            case self::CACERT_SOURCE_DIR:
                curl_setopt($ch, CURLOPT_CAPATH, $this->caCertSource);
                break;
        }
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        
        // Send cookies
        $rawCookies = array();
        foreach ($this->cookies as $k=>$v)
            $rawCookies[] = "$k=$v";
        $rawCookies = implode(';', $rawCookies);
        curl_setopt($ch, CURLOPT_COOKIE, $rawCookies);
        
        $data = curl_exec($ch);
        
        if ($data === false)
            throw new Exception('Cannot execute request: '.curl_error($ch));
        
        // Store received cookies
        preg_match_all('/Set-Cookie: ([^=]+)=(.*?);/i', $data, $matches, PREG_SET_ORDER);
        foreach ($matches as $match)
            $this->cookies[$match[1]] = $match[2];
        
        curl_close($ch);
        
        return $data;
    }

    protected function extractToken($html, $formAction) {
        if (!preg_match('/<form [^>]*'.preg_quote($formAction, '/').'[^>]*>.*?(<input [^>]*name="t" [^>]*value="(.*?)"[^>]*>).*?<\/form>/is', $html, $matches) || !isset($matches[2]))
            throw new Exception("Cannot extract token! (form action=$formAction)");
        return $matches[2];
    }

}
