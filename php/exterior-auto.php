<?php

function exteriorWithColors($metadata,$image, $folderBase, $index, $colors) {
  $ourAngles = array("020", "060", "140");
  exteriorWithColorsAndAngles($metadata,$image, $folderBase, $index, $colors, $ourAngles);
}

function exteriorWithColorsAndAngles($metadata,$image, $folderBase, $index, $colors, $ourAngles) {
  // echo "exterior color <br />";
  exteriorImages($metadata,$image, $folderBase, $index, $colors, $ourAngles, function($i, $j, $k, $newimage, $folderName, $fileNames) use($metadata,$folderBase, $index){
    $fileName = '';
    switch ($j) {
        case 0:
            $fileName = $fileNames[$i] . "_f.jpg";

            break;

        case 1:
            $fileName = $fileNames[$i] . "_s.jpg";

            break;
        default:
            $fileName = $fileNames[$i] . "_r.jpg";
            break;
    }
    // print_r(array_values($fileNames));
    // echo "$fileName <br />";
    // echo "$folderBase,  $index <br />";
    loadAndwriteFile($metadata,$folderBase, $newimage, $fileName, $index);
  });

}



?>
