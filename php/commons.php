<?php
$Toolkit_Dir = '../toolkit/';
       
include $Toolkit_Dir . 'JPEG.php';                     


function createFolder($folderPath) {
//    $folderPath = "$folderBase/front_interior/$index";
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0755, true);
    }
}

function exteriorImages($metadata,$image, $folderBase, $index, $colors, $ourAngles, $saveFile) {
    // echo "exterior images <br />";
    $folderName = $_POST['foldername'];
//    $availUrlCodes = array("mWa", "e9F", "ehP", "XR5", "nRi", "nh1", "nh3", "tha", "thP", "09B", "0hB", "Khk", "yMk", "yha", "nji", "y93", "whk", "wja", "ehl", "yh1", "th5", "thF", "nUl", "n9P", "eU1", "wU1", "0hl", "0hF", "XR5", "0hk", "eW3", "0h9");
    $jsonFile = file_get_contents("../data/exterior_colors_GLA-Class_x156.json");
    $exteriorColors = json_decode($jsonFile, true);

    $availUrlCodes = array();

    foreach ($exteriorColors as $jsonColor) {
        $availUrlCodes[] = $jsonColor["url_code"];
    }

    // $ourAngles = array("020", "060", "140");
    $availAngles = array("000", "010", "020", "030", "040", "050", "060", "070", "080", "090",
        "100", "110", "120", "130", "140", "150", "160", "170", "180", "190",
        "200", "210", "220", "230", "240", "250", "260", "270", "280", "290",
        "300", "310", "320", "330", "340", "350");

    $urlCodes = array();
    $fileNames = array();
    for ($i = 0; $i < sizeof($colors); $i++) {
        $codeColor = explode("|", $colors[$i]);
        $urlCodes[] = $codeColor[1];
        $fileNames[] = $codeColor[0];
    }
    // print_r(array_values($colors));
    // print_r(array_values($urlCodes));
    for ($i = 0; $i < sizeof($urlCodes); $i++) {
        // echo "$i <br />";
        $newg = '';

        for ($j = 0; $j < sizeof($availUrlCodes); $j++) {
            $urlCode = $availUrlCodes[$j];
            // echo "image, urlcode : $image, $urlCode <br />";
            if (strpos($image, $urlCode)) {
                // echo "newg : $urlCode, $urlCodes[$i], $image <br />";
                $newg = str_replace($urlCode, $urlCodes[$i], $image);

            }
        }

        for ($j = 0; $j < sizeof($ourAngles); $j++) {
          // echo "j : $k <br />";
            for ($k = 0; $k < sizeof($availAngles); $k++) {
              // echo "newg : $newg <br />";
                if (strpos("$newg", "BE$availAngles[$k]")) {
                    $newimage = str_replace("BE$availAngles[$k]", "BE$ourAngles[$j]", $newg);
                    // echo "$newimage <br />";
                    // echo "i : $i, j : $j, k : $k <br />";
                    $saveFile($i, $j, $k, $newimage, $folderName, $fileNames);
                    loadAndwriteFileWidthHeight($metadata,$folderBase, $newimage, $fileName, $index, 1439, 1079, 100, 100);


                }
            }
        }
    }
}

function loadAndwriteFile($metadata,$folderBase, $imagePath, $fileName, $index) {

  loadAndwriteFileWidthHeight($metadata,$folderBase, $imagePath, $fileName, $index, 1024, 424, 62, 62);

}

function getContentTypeOfRemoteFile($url)
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

function loadAndwriteFileWidthHeight($metadata,$folderBase, $imagePath, $fileName, $index, $resizeWidth, $resizeHeight, $percentageW, $percentageH) {
  // $size = get_image_size($imagePath);
  // $content = file_get_contents($imagePath);
  // $type = get_headers($imagePath, 1)["Content-Type"];
  // echo "$fileName<br />";
  if(strlen($fileName) > 0) {
      $info = getContentTypeOfRemoteFile($imagePath);


      if(strcasecmp($info, "text/plain") == 0) {
      // if($type == "text/plain") {
        echo "<a href=\"$imagePath\">$fileName not found.</a> <br />";
      } else {
        $toroot = $_SERVER['DOCUMENT_ROOT'] .$_SERVER['REQUEST_URI'];
        $toroot = substr($toroot, 0, -9);
          $content = file_get_contents($imagePath);
          $folderName = $_POST['foldername'];
          if ($folderName == '') {
              $folderName = 'exterior';
          }
          $folderPath = $toroot."$folderBase" . "$folderName/" . "$index";
          if (!file_exists($folderPath)) {
              //mkdir($folderPath, 0777, true);
            if (!mkdir($folderPath, 0777, true)) {
              die('Failed to create folders...');
            }
          }

          $file = "$folderPath/$fileName";
          $filePut = file_put_contents($file, $content);
        if($filePut){
            chmod($file, 0777);
            $jpeg_header_data = get_jpeg_header_data( $file );
            $new_header_data = put_jpeg_Comment( $jpeg_header_data, $metadata );
            put_jpeg_header_data($file,$file,$new_header_data);
        }else{
          die('file put error');
        }
        # ImageMagic
        // $resizeWidth = 1024;
        // $resizeHeight = 424;

        $thumb = new imagick($file);
        # image width & height
        // mostly 1920x1080
        $w = $thumb->getimagewidth();
        $h = $thumb->getimageheight();
        # resize
        $w = $w*($percentageW/100);
        $h = $h*($percentageH/100);
        # crop
        // get x and y of crop area
        $x = ($w/2) - ($resizeWidth/2);
        $y = ($h/2) - ($resizeHeight/2);

        $thumb->readImage($file);
        $thumb->resizeImage($w, $h, Imagick::FILTER_LANCZOS, 1);
        $thumb->cropImage($resizeWidth, $resizeHeight, $x, $y);

          $resizedFolderPath = $toroot . "$folderBase" . "$folderName/" . "$index/resized";
          if (!is_dir($resizedFolderPath)) {
              mkdir($resizedFolderPath);
          }
          $thumb->writeImage("$resizedFolderPath/$fileName");
          $thumb->clear();
          $thumb->destroy();
      }
  }


}

?>
