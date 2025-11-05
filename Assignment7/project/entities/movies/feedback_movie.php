<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie Feedback</title>
</head>
<body>
  <h2>Movie Input Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';
  if ($status === 'success') {
      echo "<p> Movie was added </p>";
  } else {
      echo "<p style='color:red;'> Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_movie.html">Add another movie</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back</a></p>
</body>
</html>

