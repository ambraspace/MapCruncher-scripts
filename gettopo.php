<?php

$zoom=17-15;

$filename=$argv[1];

$underscore=strpos($filename, "_");
$dot=strpos($filename, ".");
$x=substr($filename, $underscore+1, $dot-$underscore-1);
$y=substr($filename, 0, $underscore);

$server=rand(0,3);

system("wget -O ../topo/{$y}_{$x}.jpg \"http://mt{$server}.google.com/mt?n=404&v=w2p.99&x=$x&y=$y&zoom=$zoom\"", $retval);

exit ($retval);
?>
