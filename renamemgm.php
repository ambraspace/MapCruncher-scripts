<?php

function QuadKey2TileCoordinates($quadkey) {

  $x=0;
  $y=0;

  for ($i=0; $i<strlen($quadkey); $i++) {
    $x = $x*2;
    $y = $y*2;
    switch (substr($quadkey, $i, 1)) {
      case 0:
        break;
      case 1:
        $x++;
        break;
      case 2:
        $y++;
        break;
      case 3:
        $x++;
        $y++;
        break;
    }
  }

  return array($x,$y);

}


$filename=$argv[1];
$quadkey=substr($filename, 0, strpos($filename,"."));

$pos=QuadKey2TileCoordinates($quadkey);

symlink ("../{$filename}", "links/{$pos[1]}_{$pos[0]}.png");

?>