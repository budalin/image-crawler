<?php


function exterior360WithColorsAndAngles($image, $folderBase, $index, $colors, $ourAngles) {
  exteriorImages($image, $folderBase, $index, $colors, $ourAngles, function($i, $j, $k, $newimage, $folderName, $fileNames) use($folderBase, $index){
    $fileName = ($j + 1). ".jpg";
    // echo "$folderBase,  $index <br />";
    // echo "$fileName <br />";
    loadAndwriteFileWidthHeight($folderBase, $newimage, $fileName, $index, 1439, 1079, 100, 100);
  });
}

?>
