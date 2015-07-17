<?php

/**
 * Download Interior image for specific Class
 * Date - 14/07/2015
 * Author - htunlinnaung
 * Version - 0.0.1
 */

class Interior 
{
	
	public $index;
	public $base_folder;
	public $image_url;
	public $colors;
	public $our_colors;
	public $json;

	
	/*public function __construct()
	{
		$Toolkit_Dir = '../toolkit/';
		include $Toolkit_Dir . 'JPEG.php';                     
	}*/

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
	 * Getter Method
	 */
	function get($property)
	{		
		return $this->property;	
	}

	/**
	 * Setter Method
	 */
	function set($property,$value)
	{
		$this->property = $value;		
	}

	/**
	 * $metada(string) - Key-Value pair from the form input
	 * $image_url(string) - Image URL from the form input
	 * $foldername(string) - Folder name from the form input
	 * $colors(array) - Input colors from the color picker
	 */
	function interior_image($metadata, $image_url, $foldername, $colors)
	{
		$available_codes = Self::__available('interior_colors.json');
		
		$interior_views = array('IMGT=A27&POV=BI1','IMGT=A27&POV=BI2','IMGT=A27&POV=BI3');
		$available_views = array('IMGT=A27&POV=BI1','IMGT=A27&POV=BI2','IMGT=A27&POV=BI3','IMGT=A27&POV=BI4','IMGT=A4&POV=BI4','IMGT=A4&POV=BI3','IMGT=A4&POV=BI2','IMGT=A4&POV=BI1');
		
		// Self::dump($available_trims);exit;
		
		if(is_array($colors)):

			#loop for color picker
			for($i = 0; $i<count($colors); $i++):
				$codecolor = explode('|', $colors[$i]);
				$filenames[] = $codecolor[0];
				$urlcodes[] = $codecolor[1];

			endfor;
		endif;	
		
		for ($i=0; $i < count($urlcodes); $i++): 
			
			for ($j=0; $j < count($available_codes); $j++):

				$urlcode = $available_codes[$j];

				if(strpos($image_url, $urlcode)):

                	$new_image_url = str_replace($urlcode, $urlcodes[$i], $image_url);
					// echo 'Found color in ->'.$i.' '.$j;
                	// echo $new_image_url;
					
				endif;

			endfor;

			# loop for view
			for ($j=0; $j < count($interior_views); $j++) : 

				
				for ($k=0; $k < count($available_views) ; $k++):

	                if (strpos($new_image_url, $available_views[$k])):
                    
	                    $new_view = str_replace("$available_views[$k]", "$interior_views[$j]", $new_image_url);
	                	
						switch($j){
							case 0:
				                $filename = $filenames[$i].'_front_dashboard';
				                break;
				            case 1:
				                $filename = $filenames[$i].'_front_setie';
				                break;
				            case 2:
				                $filename = $filenames[$i].'_back';
				                break;

						}	
						$gg[] = $new_view.'|'.$filename;

						
						//Self::write_into_file($metadata, $foldername, $filename, $new_view);
						// echo 'Found view in ->'.$i.' '.$k;
						// Self::dump($new_image_url);

					endif;

				endfor;
					
			endfor;

		endfor;

		Self::trims($metadata,$gg,$foldername);
		// Self::dump($gg);exit;

	}

	static function trims($metadata,$image_urls,$foldername)
	{
		$trims = array('cIQC3','5MQC3','5uQC3');
		$available_trims = Self::__available('interior_trim_colors.json');
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
				                $filename = $filenames[$z].'_carbon.jpg';
				                break;
				            case 1:
				                $filename = $filenames[$z].'_light_brown.jpg';
				                break;
				            case 2:
				                $filename = $filenames[$z].'_gloss_brown.jpg';
				                break;

						}	
						
						Self::write_into_file($metadata, $foldername, $filename, $new_trim);

						$zz[] = $new_trim.'|'.$filename;

					endif;

				endfor;

			endfor;

		endfor;
		// Self::dump($zz);exit;

	}

	static function write_into_file($metadata, $foldername, $filename, $image_url)
	{
    	$base_folder = "images/";
		$toroot = $_SERVER['DOCUMENT_ROOT'] .$_SERVER['REQUEST_URI'];
        $toroot = substr($toroot, 0, -9);
		$curl_info = Self::get_info_remote_file($image_url);

		if($curl_info == 'image/jpeg'):
    		$folderpath = $toroot.$base_folder.$foldername;
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

	static function __available($json)
	{
		$jsonFile = file_get_contents('../data/'.$json);
    	$interiorColors = json_decode($jsonFile, true);
    	
    	

		foreach ($interiorColors as $color_codes) {
			$available[] = $color_codes['url_code'];
			
		}
		
		return $available;
	}

	static function get_info_remote_file($url)
	{
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_exec($ch);

	    # get the content type
	    $info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	    
	    return $info;
	}


}

