<?php

/**
 * Download Interior image for specific Class
 * Date - 14/07/2015
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
	
	function get($property)
	{
		
		return $this->property;	
	}

	function set($property,$value)
	{
		$this->property = $value;
		
	}

	// function interior_image($this->image_url, $this->base_folder, $this->index, $this->colors, $this->our_colors)
	// {

	// }

	function available_colors($json)
	{
		$jsonFile = file_get_contents('../data/'.$json);
    	$interiorColors = json_decode($jsonFile, true);
    	$url_codes = array();

		foreach ($interiorColors as $color_codes) {
			$url_codes[] = $color_codes['url_code'];
		}

		return $url_codes;
	}

	function change_name($index)
	{
		switch ($index) {
			case 'value':
				# code...
				break;
			
			default:
				# code...
				break;
		}
	}
}

