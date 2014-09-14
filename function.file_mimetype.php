<?php

////////		File_Mimetype Function
//
// Determines the file mimetype.
//
////	Usage
// File_Mimetype(__DIR__.'/../Downloads/ReadMe.docx');
// File_Mimetype(__DIR__.'/../Downloads/PrintMe.pdf');
// File_Mimetype(__DIR__.'/../Downloads/Move.mp4');
// 
////	References
// Modified from the code found at https://chrisjean.com/2009/02/14/generating-mime-type-in-php-is-not-magic/



// If no sitewide debug value, set to false.
if ( !isset($Sitewide_Debug) ) $Sitewide_Debug = false;



// So long as function is not defined.
if( !function_exists( 'File_Mimetype' ) ) {
	
	// Define the function.
	function File_Mimetype($Filename) {
		
		global $Sitewide_Debug;
		
		
		
		//// Fileinfo Functions
		// Try to use the latest, supported method.
		
		// IFFINFO If fileinfo functions exist.
		if (
			function_exists('finfo_open') &&
			function_exists('finfo_file') &&
			function_exists('finfo_close')
		) {
			
			// Set option as MIMEType
			$fileinfo = finfo_open( FILEINFO_MIME );
			// Get MIMEType of Filename
			$mime_type = finfo_file( $fileinfo, $Filename );
			// Close file
			finfo_close( $fileinfo );
			
			// IFFINFOFOUND If found the MIMEType
			if ( !empty($mime_type) ) {
				
				// Fileinfo Functions Successful
				
				if ( $Sitewide_Debug ) return array(
					'mime_type' => $mime_type,
					'method' => 'fileinfo'
				);
				
				return $mime_type;
				
			} // IFFINFOFOUND
			
		} // IFFINFO
		
		
		
		//// Mime_Content_Type Function
		// Try to use the previous, deprecated method.
		
		// IFMIMECONTENTTYPE
		if ( function_exists('mime_content_type') ) {
			
			$mime_type = mime_content_type($Filename);
			
			// IFMIMECONTENTTYPEFOUND
			if ( !empty( $mime_type ) ) {
				
				// Mime_Content_Type Function Successful
				
				if ( $Sitewide_Debug ) return array(
					'mime_type' => $mime_type,
					'method' => 'mime_content_type'
				);
				
				return $mime_type;
				
			} // IFMIMECONTENTTYPEFOUND
			
		} // IFMIMECONTENTTYPE
		
		
		
		//// Static Lookup
		// Try to use the MIMEType Map.
		
		// Include the Library
		include 'lib.mimetype_map.php';
		
		$ext = strtolower(array_pop(explode('.', $Filename)));
		
		// IFSTATICFOUND
		if ( !empty( $mimetype_map[$ext] ) ) {
			
			// Static Lookup Successful
			
			if ( $Sitewide_Debug ) return array(
				'mime_type' => $mimetype_map[$ext],
				'method' => 'from_array'
			);
			
			return $mimetype_map[$ext];
			
		} // IFSTATICFOUND
		
		
		
		//// Last Resort
		// Default to "application/octet-stream"
		
		if ( $Sitewide_Debug ) return array(
			'mime_type' => 'application/octet-stream',
			'method' => 'last_resort'
		);
		
		return 'application/octet-stream';
		
		
		
	}
}