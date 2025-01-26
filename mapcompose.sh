#!/bin/sh

file=`expr substr "$1" 1 \( \( length "$1" \) - 4 \)`

convert ../../base.png "${file}.png" -compose Over -composite tmp1.png
convert "../topo/${file}.jpg" -modulate 110,0,100 -sigmoidal-contrast 3,50% tmp2.png
convert tmp1.png tmp2.png -compose Multiply -composite tmp1.png
convert tmp1.png -format jpg -quality 80% "../finished/${file}.jpg"
