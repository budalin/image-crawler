<?php
#loop for urlcode
		for ($i=0; $i < count($urlcodes); $i++): 
			$filename = $filenames[$i].'.jpg';
			for ($j=0; $j < count($available_codes); $j++):

				$urlcode = $available_codes[$j];

				if(strpos($image_url, $urlcode)):

                	$new_image_url = str_replace($urlcode, $urlcodes[$i], $image_url);
                
                	for ($k=0; $k < count($available_views); $k++):
                		switch ($k) {
                			case 0:
                				$filename = $filenames[$i].'_front.jpg';
                				break;
                			case 1:
                				$filename = $filenames[$i].'_front_back.jpg';
                				break;
                			case 2:
                				$filename = $filenames[$i].'_back.jpg';
                				break;
                		}
                		$view = $available_views[$k];
                		$new_views = str_replace($view, $available_views[$k], $new_image_url);
		                	$curl_info = Self::get_info_remote_file($new_views);
		                	if($curl_info == 'image/jpeg'):
		                		$folderpath = $toroot.$basefolder.$foldername;
		          				$content = file_get_contents($new_views);
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

		                	
		                	endif;

	                endfor;
                	// Self::dump($curl_info);
                	// Self::dump($new_image_url);
                else:
                	die('Make sure to provide the correct url for specific Class.');
				endif;

			endfor;

		endfor;