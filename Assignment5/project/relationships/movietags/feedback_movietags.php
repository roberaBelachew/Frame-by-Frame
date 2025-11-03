<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie–Tag Link Feedback</title>
</head>
<body>
  <h2>Movie–Tag Relationship Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p>✅ Movie successfully linked to Tag!</p>";
  } else {
      echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_movietags.html">Link Another</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.html">Back to Main</a></p>
</body>
</html>

