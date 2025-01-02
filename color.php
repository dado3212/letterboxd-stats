<?php
require_once("secret.php");
require_once("tmdb.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$PDO = getDatabase();
$stmt = $PDO->prepare("SELECT poster FROM movies LIMIT 120");
$stmt->execute();
$posters = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getDominantColor($imagePath) {
  $image = imagecreatefromjpeg($imagePath);
  $width = imagesx($image);
  $height = imagesy($image);

  $rTotal = $gTotal = $bTotal = $pixelCount = 0;

  for ($x = 0; $x < $width; $x++) {
      for ($y = 0; $y < $height; $y++) {
      $rgb = imagecolorat($image, $x, $y);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
      $b = $rgb & 0xFF;

      $rTotal += $r;
      $gTotal += $g;
      $bTotal += $b;
      $pixelCount++;
      }
  }

  imagedestroy($image);

  return [
      'r' => round($rTotal / $pixelCount),
      'g' => round($gTotal / $pixelCount),
      'b' => round($bTotal / $pixelCount)
  ];
}

function rgbToHsl($r, $g, $b) {
  $r /= 255;
  $g /= 255;
  $b /= 255;

  $max = max($r, $g, $b);
  $min = min($r, $g, $b);
  $delta = $max - $min;

  $h = 0;
  if ($delta > 0) {
    if ($max === $r) {
      $h = 60 * fmod((($g - $b) / $delta), 6);
    } elseif ($max === $g) {
      $h = 60 * (($b - $r) / $delta + 2);
    } else {
      $h = 60 * (($r - $g) / $delta + 4);
    }
  }

  $l = ($max + $min) / 2;
  $s = $delta == 0 ? 0 : $delta / (1 - abs(2 * $l - 1));

  return [
    'h' => ($h < 0 ? $h + 360 : $h),
    's' => $s,
    'l' => $l
  ];
}

// var_export($posters);

?>
<html>
    <head>
      <style>
        .poster {
          display: inline-block;
          padding: 5px;
          position: absolute;
        }
        </style>
        <script>
          let centerX, centerY, radiusScale;

          function relocate(poster) {
            const radius = radiusScale * poster.getAttribute('data-radius');
            const angle = poster.getAttribute('data-angle');
            const x = centerX + radius * Math.cos(angle);
            const y = centerY + radius * Math.sin(angle);
            poster.style.left = `${x}px`; // Adjust for poster's center
            poster.style.top = `${y}px`;  // Adjust for poster's center
          }
          
          document.addEventListener('DOMContentLoaded', function() {
            const width = window.innerWidth;
            const height = window.innerHeight;
            const size = Math.min(width, height);

            radiusScale = (size - 10) / 2;
            // 92 + (5 * 2) / 2, 138 + (5 * 2) / 2 with 5 padding
            centerX = (width / 2) - 51;
            centerY = (height / 2) - 74;

            document.querySelectorAll('.poster').forEach(poster => {
              relocate(poster);
            });
          });
        </script>
</head>
    <body><?php

foreach ($posters as $poster) {
  $poster = 'https://image.tmdb.org/t/p/w92' . $poster['poster'];

  $rgb = getDominantColor($poster);
  $hsl = rgbToHsl($rgb['r'], $rgb['g'], $rgb['b']);
  $angle = $hsl['h'] / 360.0 * 2 * M_PI; // Convert Hue to radians
  $radius = 1 - $hsl['l'];    // Inverse of Lightness

  echo '<div class="poster" style="background-color: rgb(' . $rgb['r'] . ', '  . $rgb['g'] . ', '  . $rgb['b'] . ')" data-angle="' . $angle . '" data-radius="' . $radius . '">';
  echo '<img src="' . $poster .'" /><br>';
  echo '</div>';
}
?>
</body></html>