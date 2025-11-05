<?php include 'auth_admin.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Database Maintenance Page</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; background-color: #f8f9fa; }
    h1 { text-align: center; color: #333; }
    h2 { margin-top: 30px; color: #444; border-bottom: 2px solid #ccc; padding-bottom: 5px; }
    ul { list-style-type: none; padding: 0; }
    li { margin: 8px 0; }
    a { text-decoration: none; color: #0066cc; }
    a:hover { text-decoration: underline; }
    .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .backlink { text-align: center; margin-top: 30px; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Database Maintenance Page</h1>
    <p style="text-align:center;">Use the links below to add or update data for each entity and relationship.</p>

    <h2>Entities</h2>
    <ul>
      <li><a href="entities/user/input_users.html">Users</a></li>
      <li><a href="entities/profileInfo/input_profileinfo.html">Profile Info</a></li>
      <li><a href="entities/movies/input_movie.html">Movies</a></li>
      <li><a href="entities/ageRating/input_agerating.html">Age Rating</a></li>
      <li><a href="entities/sources/input_sources.html">Sources</a></li>
      <li><a href="entities/tags/input_tags.html">Tags</a></li>
      <li><a href="entities/review/input_reviews.html">Reviews</a></li>
      <li><a href="entities/watchlist/input_watchlist.html">Watchlist</a></li>
    </ul>

    <h2>Relationships</h2>
    <ul>
      <li><a href="relationships/comments/input_comments.html">Comments</a></li>
      <li><a href="relationships/likes/input_likes.html">Likes</a></li>
      <li><a href="relationships/moviesources/input_moviesources.html">Movie–Sources</a></li>
      <li><a href="relationships/movietags/input_movietags.html">Movie–Tags</a></li>
    </ul>

    <div class="backlink">
      <p><a href="https://clabsql.constructor.university/~rbelachew/index.php"> Back to Project Home</a></p>
    </div>
  </div>
</body>
</html>

