<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Feedback</title>
</head>
<body>
  <h2>User Creation Feedback</h2>
  <?php
  $status = $_GET['status'] ?? '';
  $msg = $_GET['msg'] ?? '';

  if ($status === 'success') {
      echo "<p>✅ User added successfully!</p>";
  } else {
      echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($msg) . "</p>";
  }
  ?>
  <p><a href="input_users.html">Add Another User</a> | <a href="https://clabsql.constructor.university/~rbelachew/index.php">Back to Main</a></p>
</body>
</html>

