<?php
$srcPath = 'public/images/Logo Brand Martip.png';
$src = imagecreatefrompng($srcPath);
if (!$src) {
    die("Failed to load image");
}

$width = imagesx($src);
$height = imagesy($src);

echo "Dimensions: {$width}x{$height}\n";

// Top logo: from x=0, y=0 to width, height*0.58
$topHeight = (int)($height * 0.58);
$topLogo = imagecrop($src, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $topHeight]);
if ($topLogo !== false) {
    imagepng($topLogo, 'public/images/logo-website.png');
    echo "Saved logo-website.png\n";
}

// Favicon: from the "FULL COLOR" icon in the bottom section
// The width has 4 icons. The second one is between 30% and 55% of the width.
// The height is from 60% to 95%.
$iconX = (int)($width * 0.35);
$iconY = (int)($height * 0.60);
$iconW = (int)($width * 0.20);
$iconH = (int)($height * 0.38);

$favicon = imagecrop($src, ['x' => $iconX, 'y' => $iconY, 'width' => $iconW, 'height' => $iconH]);
if ($favicon !== false) {
    imagepng($favicon, 'public/images/favicon.png');
    echo "Saved favicon.png\n";
}
