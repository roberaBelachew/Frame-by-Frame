<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie–Source Feedback</title>
</head>
<body>
  <h2>Movie–Source Submission Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p>✅ Relationship added successfully!</p>";
  } else {
      echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_moviesources.html">Add Another Relationship</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.html">Back to Main</a></p>
</body>
</html>

