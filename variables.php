<?php
/**
 *	Single use download variables
 *	Defines where the actual download location is
 *	Defines the path to the download file (download.php)
 *	Sets a fake files name to show to users (should not be the same name as the real file)
 *	Sets the admin password to generate a new download link
 *	Sets a date when the file will expire (examples: +1 year, +5 days, +13 hours)
 */	

        // functions
        function dirToArray($dir) {
          $result = array();
          $cdir = scandir($dir);
          foreach ($cdir as $key => $value)
          { 
            if($value === '.' || $value === '..') {continue;}
            if(is_file("$dir/$value")) {
              //$result[]="$dir/$value";
              $result[$value]['content_type'] = mime_content_type("$dir/$value");
              $result[$value]['suggested_name'] = $value;
              $result[$value]['protected_path'] = "$dir/$value";
              continue;
            }
            foreach(dirToArray("$dir/$value") as $value)
            { 
              $result[]=$value;
            }
           }
           return $result;
        }
 	
	/* Arrays of content type, suggested names and protected names
	$PROTECTED_DOWNLOADS = array(
		array(
			'content_type' => 'application/zip', 
			'suggested_name' => 'computing.zip', 
			'protected_path' => 'secret/file1.zip'
		),
		array(
			'content_type' => 'application/zip', 
			'suggested_name' => 'star.zip', 
			'protected_path' => 'secret/file2.zip'
		)
	); */

        $directory = '/download_folder';
        $PROTECTED_DOWNLOADS = dirToArray($directory);

	// The path to the download.php file (probably same dir as this file)
 	define('DOWNLOAD_PATH','/singleuse/download.php');
	
	// The admin password to generate a new download link
	define('ADMIN_PASSWORD','1234');
	
	// The expiration date of the link (examples: +1 year, +5 days, +13 hours)
	define('EXPIRATION_DATE', '+1 month');

        // Test if secure request or not
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        $REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
	
	// Don't worry about this
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: ".date('U', strtotime(EXPIRATION_DATE)));
?>
