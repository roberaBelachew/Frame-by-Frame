<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Comment Feedback</title>
</head>
<body>
  <h2>Comment Submission Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p> The comment has been added successfully</p>";
  } else {
      echo "<p style='color:red;'> Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_comments.html">Add Another Comment</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Main</a></p>
</body>
</html>

