<?php
require_once("secret.php");
require_once("tmdb.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$image_size = 22; // 92
$border_width = 5;

?>
<html>
    <head>
      <style>
      .title {
        font-size: 2em;
        font-family: Georgia, serif;
        position: relative;
        left: 29px;
        top: -6px;
      }
        body {
          margin: 0;
          display: flex;
          justify-content: center;
          align-items: center;
        }
        .poster {
          display: inline-block;
          padding: <?php echo $border_width; ?>px;
          position: absolute;

          img {
            width: <?php echo $image_size; ?>px;
            height: <?php echo $image_size * 138 / 92; ?>px;
          }

          span {
            height: 10px;
            display: inline-block;
          }
        }
        </style>
</head>
    <body>
      <div class="title">Letterboxd</div>
      <?php

$PDO = getDatabase();
$stmt = $PDO->prepare("SELECT poster, primary_color FROM movies LIMIT 500"); // ORDER BY RAND()
$stmt->execute();
$posters = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posters as $poster) {
  $hsl = json_decode($poster['primary_color'], true);
  $poster = 'https://image.tmdb.org/t/p/w92' . $poster['poster'];

  $angle = ($hsl['h']) / 360.0 * 2 * M_PI; // Convert Hue to radians
  $radius = (1 - $hsl['s'] / 0.927); // (1 - $hsl['s']) * (1 + 0.2 * (1 - $hsl['l']));    // Inverse of Lightness

  echo '<div class="poster" style="background-color: hsl(' . $hsl['h'] . ', '  . $hsl['s'] * 100 . '%, '  . $hsl['l'] * 100 . '%)" data-angle="' . $angle . '" data-radius="' . $radius . '">';
  echo '<img src="' . $poster .'" /><br>';
  echo '</div>';
}
?>
<script>
  let centerX, centerY, radiusScale;
  const width = window.innerWidth;
  const height = window.innerHeight;

  const imageWidth = <?php echo $image_size; ?>;
  const imageHeight = imageWidth * 138/92;
  const borderWidth = <?php echo $border_width; ?>;
  
  if (width > height) {
    radiusScale = (height / 2 - imageHeight / 2) -50;
  } else {
    radiusScale = (width / 2 - imageWidth / 2) - 50;
  }

  centerX = (width / 2) - (imageWidth + borderWidth) / 2;
  centerY = (height / 2) - (imageHeight + borderWidth * 2) / 2;

  // For each poster calculate its desired position
  // Then iterate over the locations, choose the best poster, and place it
  let posters = [];

  document.querySelectorAll('.poster').forEach(poster => {
    let radius = radiusScale * poster.getAttribute('data-radius');
    let angle = poster.getAttribute('data-angle');
    const x = centerX + radius * Math.cos(angle);
    const y = centerY + radius * Math.sin(angle);
    posters.push({x, y, poster});
  });

  const widthScale = imageWidth + borderWidth * 2;
  const heightScale = imageHeight + borderWidth * 2;

  const numCols = Math.floor(width / widthScale);
  const numRows = Math.floor(height / heightScale);
  
  // The current poster position that we're trying to fill
  let row = Math.floor(numRows / 2);
  let col = Math.floor(numCols / 2) + 4;
  // The current direction that we're traveling
  let direction = 'EAST';
  // Each time we rotate, we increase
  let nextVertical = 1;
  let nextHorizontal = 5;
  let switchCounter = 1;

  for (var i = 0; i < 500; i++) {
    posters[i].poster.style.left = `${col * widthScale}px`;
    posters[i].poster.style.top = `${row * heightScale}px`;

    // Check if we should swap directions
    switchCounter -= 1;
    if (switchCounter == 0) {
      switch (direction) {
        case 'EAST':
          direction = 'SOUTH';
          nextHorizontal += 1;
          switchCounter = nextVertical;
          break;
        case 'SOUTH':
          direction = 'WEST';
          nextVertical += 1;
          switchCounter = nextHorizontal;
          break;
        case 'WEST':
          direction = 'NORTH';
          nextHorizontal += 1;
          switchCounter = nextVertical;
          break;
        case 'NORTH':
          direction = 'EAST';
          nextVertical += 1;
          switchCounter = nextHorizontal;
          break;
      }
    }

    switch (direction) {
      case 'EAST':
        col += 1;
        break;
      case 'WEST':
        col -= 1;
        break;
      case 'NORTH':
        row -= 1;
        break;
      case 'SOUTH':
        row += 1;
        break;
    }
  }
  
</script>
</body></html>