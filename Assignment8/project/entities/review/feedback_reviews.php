<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Review Submission Feedback</title>
</head>
<body>
  <h2>Review Submission Result</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p>✅ Review added successfully!</p>";
  } else {
      echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_reviews.html">Add Another Review</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Main</a></p>
</body>
</html>

