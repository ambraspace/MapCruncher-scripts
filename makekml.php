<?php

/*

  for folder in *; do
    php -f ~/makekml.php "$folder" > "${folder}/doc.kml"
  done

*/

  for ($i=0; $i<$argc; $i++) {
    if (is_dir($argv[$i])) {
      ProcessDir($argv[$i]);
    }
  }

  function ProcessDir($dir) {

    $files=scandir("$dir/files");

    echo <<<OL
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://earth.google.com/kml/2.2">
<Folder>
  <name>$dir</name>
  <Style>
    <ListStyle>
      <listItemType>checkHideChildren</listItemType>
      <bgColor>00ffffff</bgColor>
    </ListStyle>
  </Style>

OL;

    for ($i=0; $i<count($files); $i++) {
      if (substr($files[$i], strlen($files[i])-4)==".png") {
        OverlayText($dir, $files[$i]);
      }
    }

    echo <<<OL
</Folder>
</kml>

OL;
  }

  function OverlayText($dir, $filename) {

    $quadkey=substr($filename, 0, strpos($filename, "."));

    $tilecoordinates=QuadKey2TileCoordinates($quadkey);

    $lonlat=TileCoordinates2LonLat ($tilecoordinates, strlen($quadkey));

    echo
<<<OL
  <GroundOverlay>
    <name>$quadkey</name>
    <Icon>
      <href>files/$filename</href>
      <viewBoundScale>0.75</viewBoundScale>
    </Icon>
    <LatLonBox>
      <north>$lonlat[3]</north>
      <south>$lonlat[1]</south>
      <east>$lonlat[2]</east>
      <west>$lonlat[0]</west>
    </LatLonBox>
  </GroundOverlay>

OL;

  }

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

  function TileCoordinates2LonLat ($tilecoordinates, $level) {

    $pixelX=$tilecoordinates[0]*256;
    $pixelY=$tilecoordinates[1]*256;

    $lon0=($pixelX)/pow(2, $level+8)*360-180;
    $lon1=($pixelX+256)/pow(2, $level+8)*360-180;

    $E0=exp(4*M_PI*(0.5-(($pixelY+256)/pow(2, $level+8))));
    $E1=exp(4*M_PI*(0.5-($pixelY/pow(2, $level+8))));
    $lat0=asin(($E0-1)/($E0+1))*180.0/M_PI;
    $lat1=asin(($E1-1)/($E1+1))*180.0/M_PI;

    return array($lon0, $lat0, $lon1, $lat1);

  }

?>