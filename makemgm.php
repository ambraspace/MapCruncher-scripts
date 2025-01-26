<?php

function MakeEmptyMGM($outfilename) {
  global $tpfx, $tpfy;
  $outfile=fopen($outfilename, "w");
  fwrite($outfile, str_repeat("\0", 2+6*$tpfx*$tpfy));
  fclose($outfile);
}

$tpfx=8;
$tpfy=8;

$filename=$argv[1];

$us=strpos($filename, "_");
$dt=strpos($filename, ".");

$srcx=substr($filename, $us+1, $dt-$us-1);
$srcy=substr($filename, 0, $us);

$dstx=floor($srcx/$tpfx);
$dsty=floor($srcy/$tpfy);

$relx=$srcx-$dstx*$tpfx;
$rely=$srcy-$dsty*$tpfy;

$outfilename="../mgm/{$dstx}_{$dsty}.mgm";
$srcfilesize=filesize($filename);

if (! file_exists($outfilename) ) {
  MakeEmptyMGM($outfilename);
}

if (filesize($outfilename)==0) {
  MakeEmptyMGM($outfilename);
}

$outfile=fopen($outfilename, "r+");

fseek($outfile, 0, SEEK_SET);
$tilesinfile=ord(fgetc($outfile))<<8;
$tilesinfile+=ord(fgetc($outfile));

$outpos=2+6*$tpfx*$tpfy;
for ($i=0; $i<$tilesinfile; $i++) {
  $tmp=0;
  fseek($outfile, 2, SEEK_CUR);
  $tmp+=ord(fgetc($outfile))<<24;
  $tmp+=ord(fgetc($outfile))<<16;
  $tmp+=ord(fgetc($outfile))<<8;
  $tmp+=ord(fgetc($outfile));
  $outpos=$tmp;
}

fseek($outfile, $outpos, SEEK_SET);
fwrite($outfile, file_get_contents($filename), $srcfilesize);
//unlink($filename);
$nextoffset=ftell($outfile);
fseek($outfile, 0, SEEK_SET);
$tilesinfile++;
fwrite($outfile, chr(($tilesinfile>>8) & 0xFF), 1);
fwrite($outfile, chr($tilesinfile & 0xFF), 1);

fseek($outfile, 2+6*($tilesinfile-1), SEEK_SET);
fwrite($outfile, chr($relx), 1);
fwrite($outfile, chr($rely), 1);
fwrite($outfile, chr(($nextoffset>>24) & 0xFF), 1);
fwrite($outfile, chr(($nextoffset>>16) & 0xFF), 1);
fwrite($outfile, chr(($nextoffset>>8) & 0xFF), 1);
fwrite($outfile, chr($nextoffset & 0xFF), 1);

?>