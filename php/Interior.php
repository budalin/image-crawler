<?php

/**
 * Purpose - Download Interior image for specific car class
 * Date - 14/07/2015
 * Credit to- Exterior image creator
 * Modify by - htunlinnaung(htunlinnaung@appvantage.sg)
 * Version - 0.0.1
 */

namespace ImageCrawler;

class Interior 
{
	
	var $metadata;
	var $image_url;
	var $foldername;
	var $colors;
	var $json;

	/*
	* Tracing purpose only
	*/
	static function dump($val, $type = false)
	{
		if(is_string($val)):
			echo $val.'<br/>';
		elseif(is_array($val)):
			echo '<pre>';print_r($val);echo '</pre><br/>';
		elseif($type == true):
			var_dump($val);
		endif;
	}
	
	
	/**
	 * $metada(string) - Key-Value pair from the form input
	 * $image_url(string) - Image URL from the form input
	 * $foldername(string) - Folder name from the form input
	 * $colors(array) - Input colors from the color picker
	 */
	function interior_image($metadata, $image_url, $foldername, $colors)
	{
				
		$interior_views = array('IMGT=A27&POV=BI1','IMGT=A27&POV=BI2','IMGT=A27&POV=BI3','IMGT=A27&POV=BI4');
		$get_color_code = Self::get_code($image_url,'d0VXq','qtyO35');//extract color code from url
		$get_interior_view = Self::get_code($image_url,'&&',',SZT');//extract view code from url
		// Self::dump($get_color_code);exit;
		
		if(is_array($colors)):

			#loop for color picker
			$count_colors = count($colors);
			for($i = 0; $i < $count_colors; $i++):

				$codecolor = explode('|', $colors[$i]);
				$filenames[] = $codecolor[0];
				$urlcodes[] = $codecolor[1];

			endfor;
		endif;	
		
		$count_urlcodes = count($urlcodes);
		$count_interior_views = count($interior_views);
		

		for ($i=0; $i < $count_urlcodes; $i++): 

        	$new_image_url = str_replace($get_color_code, $urlcodes[$i], $image_url);

			# loop for view
			for ($j=0; $j < $count_interior_views; $j++) : 


                $new_view = str_replace($get_interior_view, "$interior_views[$j]", $new_image_url);
            	$uphostery_folder = "/$filenames[$i]";

				switch($j){
					case 0:
		                $filename = $filenames[$i].'_front_dashboard.jpg';
		                break;
		            case 1:
		                $filename = $filenames[$i].'_front_settie.jpg';
		                break;
		            case 2:
		                $filename = $filenames[$i].'_back_settie.jpg';
		                break;
		            case 3:
		            	$filename = $filenames[$i].'_back_to_front.jpg';
		                break;

				}	
				$gg[] = $new_view.'|'.$filename;
				
				Self::write_into_file($metadata, $foldername, $filename, $new_view,$uphostery_folder);

				// Self::dump($new_image_url);

			endfor;

		endfor;

		// Self::trims($metadata,$gg,$foldername);
		// Self::dump($gg);exit;

	}

	
	/**
	@ Pending to use this function :P
	  * $metada(string) - Key-Value pair from the form input
	  * $image_urls(array) - New image urls arrray from interior_image function
	  * $folder_name(string) - Folder name from the form input
	  */ 
	static function trims($metadata,$image_urls,$foldername)
	{
		$trims = Self::__available('interior_trim_colors_E-Class.json');
		$available_trims = Self::__available('interior_trim_colors_E-Class.json');
		$trim_folders = Self::__available('interior_trim_colors_E-Class.json',TRUE);
		// Self::dump($available_trims);exit;

		for ($z=0; $z < count($image_urls) ; $z++) : 
			$explode = explode('|', $image_urls[$z]);
			$url = $explode[0];
			$filenames[] = $explode[1];
			for ($t=0; $t < count($trims); $t++):

				for ($x=0; $x < count($available_trims); $x++): 
					
					if(strpos($url, $available_trims[$x])):

						$new_trim = str_replace($available_trims[$x],$trims[$t],$url);

						switch($t){
							case 0:
				                //$filename = $filenames[$z].'_carbon.jpg';
				                $trim_folder = "/$trim_folders[$t]";
				                break;
				            case 1:
				                //$filename = $filenames[$z].'_light_brown.jpg';
				                $trim_folder = "/$trim_folders[$t]";
				                break;
				            case 2:
				                //$filename = $filenames[$z].'_gloss_brown.jpg';
				                $trim_folder = "/$trim_folders[$t]";
				                break;

						}	
						
						Self::write_into_file($metadata, $foldername, $filenames[$z], $new_trim,$trim_folder);

						$zz[] = $new_trim;

					endif;

				endfor;

			endfor;

		endfor;
		// Self::dump($zz);exit;

	}

	/**
	 @ return type - JPEG file
	 @ return detail - create image accordingly from url 
	 * $metadata - key:value pairs from form input
	 * $foldername - folder name from form input 
	 * $image_url - URL string from interior_image function
	 * $uphostery_folder - folder name from interior_image function
	 */
	static function write_into_file($metadata, $foldername, $filename, $image_url,$uphostery_folder)
	{
    	$base_folder = "images/";
		$toroot = $_SERVER['DOCUMENT_ROOT'] .$_SERVER['REQUEST_URI'];
        $toroot = substr($toroot, 0, -9);
		$curl_info = Self::get_info_remote_file($image_url);

		if($curl_info == 'image/jpeg'):


				$folderpath = $toroot.$base_folder.$foldername.$uphostery_folder;
				// $folderpath = $toroot.$base_folder.$foldername;

					$content = file_get_contents($image_url);
					if(!file_exists($folderpath)):
		  				if(!mkdir($folderpath, 0777, true)):
		  					die('Folder create failed ');
		  				endif;
					endif;

					$file = $folderpath.'/'.$filename;
					$filePut = file_put_contents($file, $content);
		        if($filePut):
		            chmod($file, 0777);
		            $jpeg_header_data = get_jpeg_header_data( $file );
		            $new_header_data = put_jpeg_Comment( $jpeg_header_data, $metadata );
		            put_jpeg_header_data($file,$file,$new_header_data);
		        else:
		            die('File put failed');
		        endif;
	    else:
	    	echo "Can not find image while processing for <span class='highlight'>{ $filename }</span>.<br/>";
    	
    	endif;

	}

	/**
	 @ return type - Array
	 @ return detail - load json file for specific car class
	 * $json(json) - name of the json file to load(e.g - interior_trim_colors_E-Class.json)
	 * $name(bool) - for return only name array if TRUE
	 *
	 */
	static function __available($json,$name = false)
	{
		$jsonFile = file_get_contents('../data/'.$json);
    	$interiorColors = json_decode($jsonFile, true);

		if($name == TRUE):
			foreach ($interiorColors as  $color_codes) :

				$available[] = $color_codes['name'];
				
			endforeach;
		else:
			foreach ($interiorColors as $color_codes) :
				$available[] = $color_codes['url_code'];
			
			endforeach;
		endif;
		return $available;
	}

	/**
	 @ return type - String 
	 @ return detail - check image type from given url(e.g - image/jpeg,image/png)
	 * $url - image url
	 */
	static function get_info_remote_file($url)
	{
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_exec($ch);

	    # get the content type
	    $info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	    curl_close($ch);

	    return $info;
	}

	/**
	 @ retur type - string
	 @ return detail - part of string from given string
	 * $string - your desire string
	 * $start - first charater string before your desire string
	 * $end - second charater string after your desire string
	 */
	static function get_code($string, $start, $end)
	{
		$pos = stripos($string, $start);
		$str = substr($string, $pos);
		$str_two = substr($str, strlen($start));
		$second_pos = stripos($str_two, $end);
		$str_three = substr($str_two, 0, $second_pos);
		$unit = trim($str_three); // remove whitespaces
		 
		return $unit;
	}


}

// end of file Interior.php
// file location - image_crawler/php/Interior.php

