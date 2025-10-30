<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tag Feedback</title>
</head>
<body>
  <h2>Tag Input Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p>✅ Tag added successfully!</p>";
  } else {
      echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_tags.html">Add Another Tag</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.html">Back</a></p>
</body>
</html>

