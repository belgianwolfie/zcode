<?php 

	
	/**
	 * Helper function that converts text/strings to PNG's and rotates them 90degrees
	 *
	 * LICENSE: TheAlliance.be 2012 (c)
	 *
	 * @author     Bart Stassen <bart@thealliance.be>
	 * @copyright  2012 The Alliance
	 * @project    Part of the Ninja Books zCode application
	 *
	 * @params
	 *		$string : our book title
	 * 		$isbn   : the isbn number of our book, which is unique, will serve as filename
	 */
	 
	function imgText($string,$isbn) {
			
			// set some general stuff
			$width=19;
			$height=230;
			$x=13; 
			$y=220; 
			$fontFace = 'arial.ttf'; 
			$fontSize=10; 
			$rotate=90; 
			$total_width=20; 
			
			// prepare the image object
			$imgObj = imagecreatetruecolor($width, $height); 
			$colorTitle = imagecolorallocate($imgObj, 255, 255, 255); 
			$colorCover = imagecolorallocate($imgObj, 0, 0, 0); 
			imagefilledrectangle($imgObj, 0, 0, $width, $height, $colorCover); 
		
			// write our title to the image using our truetypefont
			imagettftext($imgObj, $fontSize, $rotate, $x, $y, $colorTitle, $fontFace, $string); 

			// create unique filename based on our unique ISBN parameter
			imagepng($imgObj,$isbn.".png"); 
			echo "<img src='" . $isbn . ".png'/>"; 
			imagedestroy($imgObj); 
	}
	
?>