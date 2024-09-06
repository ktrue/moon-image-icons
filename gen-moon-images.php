<?php
# ------------------------------------------------------------------------
#
# Program:  gen-moon-images.php
# 
# Purpose:  generate moon images for sunposa from NASA moon images
#
# Author:  Ken True - webmaster@saratoga-weather.org
#
# Version 1.00 - 03-Sep-2024 - initial release
#
# ------------------------------------------------------------------------

$Version ="gen-moon-images.php V1.00 - 02-Sep-2024";
header('Content-type: text/plain,charset=ISO-8859-1');
date_default_timezone_set("America/Los_Angeles");
print "$Version\n";

$rawFileDir = './NASA-moons/'; # source directory for NASA 1920x1280 moon images

$outFileDir = './moonimg/';   # directory to store the 50x50 transparent background image
#             './moonimg-lg/' directory used for full-size transparent background images

for ($i = 0;$i <=29;$i++) {
  # frame number = ( days � 8 ) + 1
  $frame = ($i * 8) +1;
  $inName = 'moon_'.sprintf('%03d',$frame).'.gif';
  $outNameNH = 'NH-moon'.sprintf('%02d',$i).'.gif';
  $outNameSH = 'SH-moon'.sprintf('%02d',$i).'.gif';
  $inFound = file_exists($rawFileDir.$inName)?'yes':'NO';
  print " $i\t$inName\t$inFound\t$outNameNH\t$outNameSH\n";
  maketransparent($rawFileDir.$inName,$outFileDir.$outNameNH,50,50);
}

function maketransparent($oldfile,$newfile,$width,$height)
// Turn black background transparent and re-size
{
	$info = getimagesize($oldfile);
	if(!is_array($info)) {
		if(!headers_sent() ) {header('Content-type: text/plain'); }
		print "-- error fetching image size from $oldfile.  Unable to continue.\n";
		exit(1);
	}
	$im = imagecreatefromgif($oldfile);
	if($im == false) {
		if(!headers_sent() ) {header('Content-type: text/plain'); }
		print "-- error fetching image file $oldfile for processing.  Unable to continue.\n";
		exit(1);
	}
	
	$img = imagecreatetruecolor($width,$height);
  #$trans = imagecolorat($im,1,1);
	#$trans = imagecolorallocate($img, 0,0,0);
  #$outline = imagecolorallocate($img,128,128,128);
  #imageellipse($img,960,540,345,345,$outline);
  #imagefill ($img, 0, 0, $trans);
	#imagecolortransparent($img,$trans);
  $x1 = 460; $y1 = 42;
  $x2 = 1458; $y2 = 1038;
  $w1 = $x2-$x1+1;
  $h1 = $y2-$y1+1;
  $extra = 1;
  # Create transparent moon image large
  $im2 = imagecreatetruecolor($w1,$h1);
  imagecopy($im2,$im,0,0,$x1,$y1,$w1,$h1);
  $outline = imagecolorallocate($im,255,0,0);
  imageRemoveOuterCircle($im2,$w1,$h1,5);
  #imagerectangle($im,$x1,$y1,$x2,$y2,$outline);
  $tFile = str_replace("moonimg","moonimg-lg",$newfile);
  #$tFile = str_replace(".gif",".png",$tFile);
  imagegif($im2,$tFile);
  $im2SH = imagerotate($im2,180.0,0,false);
  imageRemoveOuterCircle($im2SH,$w1,$h1,5);
  $tFile = str_replace('NH','SH',$tFile);
  imagegif($im2SH,$tFile);
  
  # make small images
	#imagecopyresampled($img,$im,0,0,$x1,$y1,$width,$height,$x2-$x1+$extra,$y2-$y1+$extra);
	imagecopyresampled($img,$im2,0,0,0,0,$width,$height,$w1+$extra,$h1+$extra);
  $margin = 0;
  imageRemoveOuterCircle($img,$width,$height,$margin);
	imagetruecolortopalette($img, true, 256);
	imagegif($img,$newfile);
  $imgSH = imagerotate($img,180.0,0,false);
  $newfileSH = str_replace('NH','SH',$newfile);
  imageRemoveOuterCircle($imgSH,$width,$height,$margin);
  imagegif($imgSH,$newfileSH);
	imagedestroy($img);
  imagedestroy($im);
  imagedestroy($im2);
  imagedestroy($im2SH);
}

$theDate = date('r');

$README = 'Generated by '.$Version.'
Date: '.$theDate.'
Ref: https://github.com/ktrue/SunMoon-graph
Contents: transparent GIF moon images for Northern and Southern Hemisphere for each day in the lunar cycle.
Based on images from NASA at https://svs.gsfc.nasa.gov/4310/

Credit: NASA\'s Scientific Visualization Studio
Visualizer: Ernie Wright (USRA)

Image reprocessing by Ken True - webmaster@saratoga-weather.org
';

file_put_contents($outFileDir.'README.txt',$README);
$tFileDir = str_replace('moonimg','moonimg-lg',$outFileDir);
file_put_contents($outFileDir.'README.txt',$README);

# Code from:
# https://stackoverflow.com/questions/999251/crop-or-mask-an-image-into-a-circle
// From https://stackoverflow.com/a/23215738/2590508

function hexColorAllocate($im,$hex){
    $hex = ltrim($hex,'#');
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
    return imagecolorallocate($im, $r, $g, $b);
}

function imageRemoveOuterCircle(&$image,$width=null,$height=null,$margin=0){
    // 2 arbitrary colors for transparency ; can be redefined if needed
    $transparentColor1="8d5ca4";
    $transparentColor2="8d5ca5";
    

    if(is_null($width)){
        $width=imagesx($image);
    }
    if(is_null($height)){
        $height=imagesy($image);
    }

    $mask=imagecreatetruecolor($width, $height);
    imagefilledrectangle(
        $mask,
        0,
        0,
        $width,
        $height,
        hexColorAllocate($mask,$transparentColor1)
    );
    imagefilledellipse(
        $mask,
        $width/2,
        $height/2,
        $width-$margin,
        $height-$margin,
        hexColorAllocate($mask,$transparentColor2)
    );
    imagecolortransparent($mask,hexColorAllocate($mask,$transparentColor2));
    imagecopy(
        $image,
        $mask,
        0,
        0,
        0,
        0,
        $width,
        $height
    );
    imagedestroy($mask);
    imagecolortransparent($image,hexColorAllocate($image,$transparentColor1));
}
