<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Age Rating Feedback</title>
</head>
<body>
  <h2>Age Rating Submission Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p>✅ Age rating added successfully!</p>";
  } else {
      echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_agerating.html">Add Another</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Main</a></p>
</body>
</html>

