<?php

ini_set('max_execution_time', 800);

include '360-exterior.php';
include 'back-interior.php';
include 'exterior-auto.php';
include 'front-interior.php';
include 'Interior.php';
include 'commons.php';

$urls = $_POST['urls'];
$selectedForm= $_POST['selectedForm'];
$colors = $_POST['colors'];
$metadata = $_POST['metadata'];


$index = 1;

foreach ($urls as $url)
{

    $urlColors = explode("@", $url);
   //echo $urlColors[0];exit;
//    echo '<br />';
    // echo $urlColors[1];
    // echo '<br />';
    $baseFolder = "images/";
    switch ($selectedForm) {
    case 1:
        //360
        {
          $availAngles = array("000", "010", "020", "030", "040", "050", "060", "070", "080", "090",
              "100", "110", "120", "130", "140", "150", "160", "170", "180", "190",
              "200", "210", "220", "230", "240", "250", "260", "270", "280", "290",
              "300", "310", "320", "330", "340", "350");
        // exterior360($urlColors[0], $baseFolder, $index);
        exterior360WithColorsAndAngles($urlColors[0], $baseFolder, $index, explode(",", $urlColors[1]), $availAngles);
      }
        break;
    case 2:
        //back interior
        backInterior($urlColors[0], $baseFolder, $index);
        break;
    case 3:
        //exterior
        exteriorWithColors($metadata,$urlColors[0], $baseFolder, $index, explode(",", $urlColors[1]));
//        exterior($url, $baseFolder, $index);

        break;
    case 4:
        //front interior
        frontInterior($url[0], $baseFolder, $index);
        break;
    case 5:
        $Interior = new Interior();
        
        // $Interior->set('json','test.json');
        // $json = $Interior->get('json');
        // $available_code = $Interior->available_colors($json);
        // print_r( $available_code );echo '<br/>';
        // print_r(explode(",", $urlColors[1]));echo '<br/>';
        echo $Interior->get('our_colors');exit;
    }
    $index++;
}

?>
