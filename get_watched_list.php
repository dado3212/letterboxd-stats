<?php
require_once("tmdb.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $file = $_FILES['file'];

  if (pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
    handleWatchlist($file);
    exit;
  } else if (pathinfo($file['name'], PATHINFO_EXTENSION) === 'zip') {
    handleZip($file);
  } else {
    http_response_code(400);
    echo 'Invalid file type. Please upload a .zip file.';
    exit;
  }
} else {
    http_response_code(400);
    echo 'No file uploaded.';
}

function handleWatchlist($file) {
    if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
        $data = []; // Array to store the CSV data as a dictionary
    
        // Get the header row
        $headers = fgetcsv($handle);
    
        if ($headers === false) {
          http_response_code(400);
          echo 'The CSV file is empty or invalid.';
          fclose($handle);
          exit;
        }
    
        // Process each row of the CSV
        while (($row = fgetcsv($handle)) !== false) {
          $data[] = array_combine($headers, $row);
        }
    
        fclose($handle);

        // Example: Return JSON response
        header('Content-Type: application/json');
        echo json_encode(handleMovies($data));
      } else {
        http_response_code(500);
        echo 'Failed to open uploaded file.';
      }
}

function handleZip($file) {
  // Open the .zip file directly from the uploaded file stream
  $zip = new ZipArchive();
  if ($zip->open($file['tmp_name']) === TRUE) {
    // Iterate over files in the .zip
    $files = [];
    for ($i = 0; $i < $zip->numFiles; $i++) {
      $fileName = $zip->getNameIndex($i);
      $fileContent = $zip->getFromIndex($i);
      
      // Store file data in an associative array
      $files[] = [
        'name' => $fileName,
        'content' => $fileContent,
      ];
    }
    $zip->close();

    // Process the extracted files in memory
    foreach ($files as $file) {
      echo "File: " . $file['name'] . "\n";
      // Example: Output first 100 characters of file content
      echo "Content (truncated): " . substr($file['content'], 0, 100) . "\n\n";
    }
  } else {
    http_response_code(500);
    echo 'Failed to open .zip file.';
  }
}

function handleMovies($watchlistMovies) {
  if (count($watchlistMovies) === 0) {
    return null;
  }

  $letterboxdUrls = [];
  foreach ($watchlistMovies as $movie) {
    $letterboxdUrls[] = $movie['Letterboxd URI'];
  }

  // Check if the URL has been uploaded to the database already
	$PDO = getDatabase();
  $placeholders = implode(',', array_fill(0, count($letterboxdUrls), '?'));
	$stmt = $PDO->prepare("SELECT * FROM movies WHERE letterboxd_url IN ($placeholders)");
  $stmt->execute($letterboxdUrls);

  $rawMovieInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $serverMovieInfo = [];
  foreach ($rawMovieInfo as $rawMovie) {
    $serverMovieInfo[$rawMovie['letterboxd_url']] = $rawMovie;
  }

  $toUpload = [];
  $movies = [];
  foreach ($watchlistMovies as $movie) {
    // If it's already in the database then we can return the data directly
    if (array_key_exists($movie['Letterboxd URI'], $serverMovieInfo)) {
      $movieInfo = $serverMovieInfo[$movie['Letterboxd URI']];
      $movieInfo['countries'] = json_decode($movieInfo['countries']);
      $movies[] = $movieInfo;
    } else {
      // If it's NOT in the database, it's a little more complicated. We'll create
      // basic rows for each of the new ones, and kick off populating that data
      // In the meantime we'll send back the information that we have from the 
      // watchlist. Additionally we'll send down an ID for the progress that will
      // monitor these movies.
      $toUpload[] = [$movie['Name'], $movie['Year']];
      $movies[] = [
        'movie_name' => $movie['Name'],
        'year' => $movie['Year'],
        'letterboxd_url' => $movie['Letterboxd URI'],
        'status' => 'pending',
      ];
      // $newInfo = getMovieInfo($movie['Name'], $movie['Year']);
      // if ($newInfo) {
      //   $formattedMovie = [
      //     'movie_name' => $movie['Name'],
      //     'year' => $movie['Year'],
      //     'id' => -1, // there is no ID yet because it hasn't been uploaded
      //     'tmdb_id' => $newInfo['tmdb_id'],
      //     'imdb_id' => $newInfo['imdb_id'],
      //     'letterboxd_url' => $movie['Letterboxd URI'],
      //     'has_female_director' => $newInfo['has_female_director'] ? 1 : 0,
      //     'language' => $newInfo['language'],
      //     'poster' => $newInfo['poster'],
      //     'countries' => array_values($newInfo['production_countries']),
      //   ];
      //   $movies[] = $formattedMovie;
      //   unset($formattedMovie['id']);
      //   $formattedMovie['countries'] = json_encode($formattedMovie['countries']);
      //   $toUpload[] = array_values($formattedMovie);
      // } else {
      //   // Error!
      // }
    }
  }

  // if (count($toUpload) > 0) {
  //   // Build the query dynamically
  //   $placeholders = [];
  //   $bindValues = [];
  //   foreach ($toUpload as $index => $info) {
  //     $placeholders[] = '(' . implode(',', array_fill(0, count($info), '?')) . ')';
  //     $bindValues = array_merge($bindValues, $info);
  //   }
    
  //   $sql = "INSERT INTO letterboxd.movies 
  //   (movie_name, `year`, tmdb_id, imdb_id, letterboxd_url, has_female_director, language, poster, countries) 
  //   VALUES " . implode(', ', $placeholders);
    
  //   // Prepare and execute the query
  //   $stmt = $PDO->prepare($sql);
  //   $stmt->execute($bindValues);
  // }

  usort($movies, function($a, $b) {
    return (float)json_decode($a['primary_color'] ?? "{'h': 0}", true)['h'] <=> (float)json_decode($b['primary_color'] ?? "{'h': 0}", true)['h'];
  });

  return $movies;
}