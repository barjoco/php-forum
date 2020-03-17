<?

// Add a post to your watch list
// Message the original poster to alert them that
// you have just subscribed to their post

if (isset($_POST['watch_list_submit'])) {

  session_start();
  require 'database_connection.php';

  $p = $_POST['p'];
  
  // Check if you are already watching this post
  $sql = 'SELECT id FROM watch_list WHERE post_id = ? AND username = ? LIMIT 1';
  $row = $db->query_row($sql, 'is', $p, $_SESSION['username']);

  if ($row) {
    $_SESSION['success'] = 'Post is already in watch list!';
    header("Location: /post.php?p=$p");
    exit(0);
  }

  $sql = 'SELECT title, created_by FROM posts WHERE id = ? LIMIT 1';
  $row = $db->query_row($sql, 's', $p);

  $created_by = $row['created_by'];

  // Check if you are trying to subscribe to your own post
  if ($created_by == $_SESSION['username']) {
    $_SESSION['err'] = 'Can\'t subscribe to your own post';
    header("Location: /post.php?p=$p");
    exit(0);
  }

  // Send message to the original poster
  $title = $row['title'];
  $subscriber = $_SESSION['username'];
  $sql = "INSERT INTO messages VALUES (NULL, ?, ?, ?, ?, NULL)";
  $db->exec(
    $sql, 'ssss',
    'A user subscribed to your post',
    "$subscriber has just subscribed to your post: $title",
    $created_by,
    'JS Forum'
  );

  $sql = 'INSERT INTO watch_list VALUES (NULL, ?, ?)';
  $db->exec($sql, 'is', $p, $_SESSION['username']);

  $_SESSION['success'] = 'Post added to watch list';
  header("Location: /post.php?p=$p");
}