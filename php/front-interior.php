<?php

function frontInterior($image, $folderBase, $index) {

//Change the below to the file names for front.
//Exmample: "501_black_c.jpg"
    $cot = array("501_black_c.jpg", "505_espresso_brown_c.jpg", "514_nut_brown_c.jpg",
        "515_porcelain_c.jpg", "201_black_c.jpg", "205_espresso_brown_c.jpg", "214_nut_brown_c.jpg",
        "801_black_c.jpg", "805_espresso_brown_c.jpg", "808_crystal_grey_c.jpg", "814_nut_brown_c.jpg");
    $int = sizeof($cot);
    $total = 0;

    while ($total < $int) {
        $color = array("b8F", "b87", "bqe", "bq7", "0WF", "0W7", "0Se", "rEF", "rE7", "rEg", "r8e",);
        $count = sizeof($color);

        $counter = 0;
        while ($counter < $count) {

//Change the below codes with the ones from above, ensuring the order is the same.
//	$image = $_POST['image'];

            if (strpos($image, 'b8F')) {
                $newg = str_replace("b8F", "$color[$counter]", $image);
            } else if (strpos($image, 'b87')) {
                $newg = str_replace("b87", "$color[$counter]", $image);
            } else if (strpos($image, 'bqe')) {
                $newg = str_replace("bqe", "$color[$counter]", $image);
            } else if (strpos($image, 'bq7')) {
                $newg = str_replace("bq7", "$color[$counter]", $image);
            } else if (strpos($image, '0WF')) {
                $newg = str_replace("0WF", "$color[$counter]", $image);
            } else if (strpos($image, '0W7')) {
                $newg = str_replace("0W7", "$color[$counter]", $image);
            } else if (strpos($image, '0Se')) {
                $newg = str_replace("0Se", "$color[$counter]", $image);
            } else if (strpos($image, 'rEF')) {
                $newg = str_replace("rEF", "$color[$counter]", $image);
            } else if (strpos($image, 'rE7')) {
                $newg = str_replace("rE7", "$color[$counter]", $image);
            } else if (strpos($image, 'rEg')) {
                $newg = str_replace("rEg", "$color[$counter]", $image);
            } else if (strpos($image, 'r8e')) {
                $newg = str_replace("r8e", "$color[$counter]", $image);
            }


            /* $dirPath = $_POST['foldername'];
              $result = mkdir($dirPath ,0755); */
            $content = file_get_contents("$newg");
            /* file_put_contents("$result/$cot[$total]",$content); */
            echo "<img src='$newg'/>";
            /* file_put_contents("$result/$cot[$total]",$content); */

            //Change this to where you want to save your files in the wamp folder.	
//        file_put_contents("C:/wamp/www/EstateImages/$cot[$total]",$content);
            $folderPath = "$folderBase/front_interior/$index";
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }
            file_put_contents("$folderBase$index/$cot[$total]", $content);
            $total++;

            echo "Sucessfully Save";

            $counter++;
        }
    }
}



?>