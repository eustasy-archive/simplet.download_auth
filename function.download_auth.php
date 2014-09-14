<?php

////////			Download_Auth Function				////////
//
// Downloads file is user is authenticated.
// Good for members only downloads.
//
////	Usage
// Download_Auth($Member_Auth, __DIR__.'/../Downloads/ReadMe.docx');
// Download_Auth($Member_Auth, __DIR__.'/../Downloads/PrintMe.pdf', 'http://other.com/login?redirect=');
// Download_Auth($Member_Auth, __DIR__.'/../Downloads/Move.mp4');
// 
////	References
// http://php.net/manual/en/function.readfile.php
// http://php.net/manual/en/function.file-exists.php
// http://php.net/manual/en/function.is-readable.php
// http://php.net/manual/en/function.filesize.php
// 
// http://ready2gosoft.com/php/php-pdf-download.html
// http://ardamis.com/2008/06/11/protecting-a-download-using-a-unique-url/

function Download_Auth($Member_Auth, $Filename, $Redirect = false){
	
	global $Sitewide_Root;
	
	// IFAUTH If the user is not authenticated.
	if ( !$Member_Auth ) {
		
		// Current URL
		$Current = 'http';
		if ( $_SERVER['HTTPS'] == 'on' ) $Current .= 's';
		$Current .= '://'.$_SERVER['SERVER_NAME'];
		if ( $_SERVER['SERVER_PORT'] != '80' ) $Current .= ':'.$_SERVER['SERVER_PORT'];
		$Current .= $_SERVER['REQUEST_URI'];
		
		// Redirect
		if ( $Redirect ) header('Location: '.$Redirect.urlencode($Current));
		else header('Location: '.$Sitewide_Root.'account?login&redirect='.urlencode($Current));
		
		exit;
		
	// IFAUTH
	// IFFILEACCESSIBLE If the File is Accessible.
	} else if (
		
		// DEPRECATED: is_readable checks existence and permissions.
		//
		// If File Exists
		// WARN: Must be 64bit for files over 2 GB.
		// file_exists($Filename)
		//
		
		// If the file exists and the web server can read it.
		is_readable($Filename);
		
	) { // IFFILEACCESSIBLE
		
		// A descriptive name for the download file operation.
		header('Content-Description: File Transfer');
		
		////	Set as Expired
		// HTTP 1.0.
		header('Pragma: no-cache'); 
		// HTTP/1.1
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		// Expires
		// Does not work for older Internet Explorers
		// header('Expires: 0');
		// Works for Everyone
		header('Expires: Sun, 16 Jan 1994 03:33:00 GMT');
		
		// Tell the browser we're sending the file in binary mode.
		header('Content-Transfer-Encoding: binary');
		
		// Send the correct mimetype.
		require 'function.file_mimetype.php';
		$File_Mimetype = File_Mimetype($Filename);
		header('Content-Type: application/octet-stream');
		
		// Tell the browser the file size.
		header('Content-Length: '.filesize($Filename));
		
		// Tell the browser to save it with the right filename.
		header('Content-disposition: attachment; filename='.basename($Filename));
		
		// Send the file.
		readfile($Filename);
		
		// Don't send anything else.
		exit;
		
	} // IFFILEACCESSIBLE
	
}