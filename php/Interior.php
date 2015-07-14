<?php

/**
 * Download Interior image for specific Class
 * Date - 14/07/2015
 * Version - 0.0.1
 */

class Interior 
{
	
	public $index = '';
	public $base_folder = '';
	public $image_url = '';
	public $colors = '';
	public $our_colors = '';
	public $json = '';
	
	function __get($property)
	{
		if(property_exists($this, $property)) {
			return $this->property;
		}
		
	}

	function __set($property, $value)
	{
		if(property_exists($this, $property)) {
			$this->property = $value;
		}

		return $this;
	}

	function interior_image($this->image_url, $this->base_folder, $this->index, $this->colors, $this->our_colors)
	{

	}

	function available_colors($this->json)
	{
    	$url_codes = array();

		foreach ($this->json as $color_codes) {
			$url_codes[] = $color_codes['url_code'];
		}

		return $url_codes;
	}
}

