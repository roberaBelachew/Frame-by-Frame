<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Like Feedback</title>
</head>
<body>
  <h2>Like Submission Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p> Like was added successfully</p>";
  } else {
      echo "<p style='color:red;'> Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_likes.html">Add Another Like</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Main</a></p>
</body>
</html>

